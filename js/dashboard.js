window.addEventListener("load", function () {
    var is_interested_images = document.getElementsByClassName("is-interested-image");
    Array.from(is_interested_images).forEach(element => {
        element.addEventListener("click", function (event) {
            var XHR = new XMLHttpRequest();
            var property_id = event.target.getAttribute("property_id");
            // console.log(typeof(property_id));
            // console.log(property_id);

            // On success
            XHR.addEventListener("load", remove_interested_success);

            // On error
            XHR.addEventListener("error", on_error);

            // Set up request
            XHR.open("GET", "/PGLIFE/api/handle_interested_dashboard.php?property_id=" + property_id);

            // Initiate the request
            XHR.send();

            document.getElementById("loading").style.display = 'block';
            event.preventDefault();
        });
    });

    Array.from(document.getElementsByClassName("js-cancel-booking")).forEach(function (btn) {
        btn.addEventListener("click", function () {
            var fd = new FormData();
            fd.append("booking_id", btn.getAttribute("data-id"));
            var XHR = new XMLHttpRequest();
            XHR.open("POST", "/PGLIFE/api/cancel_booking.php");
            XHR.addEventListener("load", function () {
                console.log("Cancel booking API:", XHR.responseText);
                var response = JSON.parse(XHR.responseText);
                pglifeAlert(response.message, !!response.success).then(function () {
                    if (response.success) {
                        window.location.reload();
                    }
                });
            });
            XHR.send(fd);
        });
    });

    function parseApi(text) {
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error("API did not return JSON", text);
            return null;
        }
    }

    Array.from(document.getElementsByClassName("js-pay-booking")).forEach(function (btn) {
        btn.addEventListener("click", function () {
            var fd = new FormData();
            fd.append("booking_id", btn.getAttribute("data-id"));
            if (document.getElementById("loading")) {
                document.getElementById("loading").style.display = "block";
            }
            fetch("/PGLIFE/api/create_razorpay_order.php", {
                method: "POST",
                body: fd,
                credentials: "same-origin"
            }).then(function (res) {
                return res.text();
            }).then(function (text) {
                if (document.getElementById("loading")) {
                    document.getElementById("loading").style.display = "none";
                }
                console.log("Create order API:", text);
                var data = parseApi(text);
                if (!data) {
                    pglifeAlert("Payment API did not return JSON", false);
                    return;
                }
                if (!data.success) {
                    pglifeAlert(data.message, false);
                    return;
                }
                if (typeof Razorpay === "undefined") {
                    pglifeAlert("Razorpay checkout script failed to load", false);
                    return;
                }
                var rzp = new Razorpay({
                    key: data.key_id,
                    amount: data.amount,
                    currency: data.currency,
                    name: "PG Life",
                    description: data.property,
                    order_id: data.order_id,
                    prefill: data.prefill,
                    theme: { color: "#20bc7e" },
                    handler: function (response) {
                        var verify = new FormData();
                        verify.append("booking_id", data.booking_id);
                        verify.append("razorpay_order_id", response.razorpay_order_id);
                        verify.append("razorpay_payment_id", response.razorpay_payment_id);
                        verify.append("razorpay_signature", response.razorpay_signature);
                        fetch("/PGLIFE/api/verify_razorpay_payment.php", {
                            method: "POST",
                            body: verify,
                            credentials: "same-origin"
                        }).then(function (r) { return r.text(); }).then(function (body) {
                            console.log("Verify payment API:", body);
                            var result = parseApi(body);
                            pglifeAlert(result ? result.message : "Verify API invalid response", !!(result && result.success)).then(function () {
                                if (result && result.success) {
                                    window.location.reload();
                                }
                            });
                        });
                    }
                });
                rzp.on("payment.failed", function (resp) {
                    pglifeAlert(resp.error && resp.error.description ? resp.error.description : "Payment failed", false);
                });
                rzp.open();
            }).catch(function () {
                if (document.getElementById("loading")) {
                    document.getElementById("loading").style.display = "none";
                }
                pglifeAlert("Could not start payment", false);
            });
        });
    });
});

var remove_interested_success = function (event) {
    document.getElementById("loading").style.display = 'none';

    var response = JSON.parse(event.target.responseText);
    if (response.success) {
        var property_id = response.property_id;

        var is_interested_image = document.querySelectorAll(".property-id-" + property_id + " .is-interested-image")[0];
        var interested_user_count = document.querySelectorAll(".property-id-" + property_id + " .interested-user-count")[0];

        if ( !response.is_interested ) {
          // Upon Click, the specific interested property will become not-interested, and this block will run...
          // We will hide that property that is being marked uninterested, by .hide class

            var card_box = document.getElementById(`card-${property_id}`);
            card_box.classList.add("hide");
            interested_user_count.innerHTML = parseFloat(interested_user_count.innerHTML) - 1;
        }
    }
    else if (!response.success && !response.is_logged_in) {
        window.$("#login-modal").modal("show");
    }
};

var on_error = function (event) {
    document.getElementById("loading").style.display = 'none';
    // alert('Oops! Something went wrong! (on_error)');
    pglifeAlert("Connection to server could not be established!", false);
};
