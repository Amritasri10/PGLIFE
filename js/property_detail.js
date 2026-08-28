window.addEventListener("load", function () {
  const search = window.location.search;
  const params = new URLSearchParams(search);
  const property_id = params.get("property_id");

  var is_interested_image = document.getElementsByClassName("is-interested-image")[0];
  if (is_interested_image) {
    is_interested_image.addEventListener("click", function (event) {
      var XHR = new XMLHttpRequest();
      XHR.addEventListener("load", toggle_interested_success);
      XHR.addEventListener("error", on_error);
      XHR.open("GET", "/PGLIFE/api/toggle_interested.php?property_id=" + property_id);
      XHR.send();
      if (document.getElementById("loading")) {
        document.getElementById("loading").style.display = "block";
      }
      event.preventDefault();
    });
  }

  var booking_form = document.getElementById("booking-form");
  if (booking_form) {
    booking_form.addEventListener("submit", function (event) {
      event.preventDefault();
      var XHR = new XMLHttpRequest();
      XHR.open("POST", "/PGLIFE/api/create_booking.php");
      XHR.addEventListener("load", function () {
        if (document.getElementById("loading")) {
          document.getElementById("loading").style.display = "none";
        }
        console.log("Booking API response:", XHR.responseText);
        var response;
        try {
          response = JSON.parse(XHR.responseText);
        } catch (e) {
          pglifeAlert("Booking API did not return JSON", false);
          return;
        }
        if (!response.success && response.is_logged_in === false) {
          window.$("#booking-modal").modal("hide");
          window.$("#login-modal").modal("show");
          return;
        }
        pglifeAlert(response.message, !!response.success).then(function () {
          if (response.success) {
            window.location.href = "/PGLIFE/dashboard.php";
          }
        });
      });
      XHR.addEventListener("error", on_error);
      XHR.send(new FormData(booking_form));
      if (document.getElementById("loading")) {
        document.getElementById("loading").style.display = "block";
      }
    });
  }
});

var toggle_interested_success = function (event) {
  if (document.getElementById("loading")) {
    document.getElementById("loading").style.display = "none";
  }
  var response;
  try {
    response = JSON.parse(event.target.responseText);
  } catch (e) {
    pglifeAlert("Interest API did not return JSON", false);
    return;
  }
  if (response.success) {
    var is_interested_image = document.getElementsByClassName("is-interested-image")[0];
    var interested_user_count = document.getElementsByClassName("interested-user-count")[0];
    if (response.is_interested) {
      is_interested_image.classList.add("fas");
      is_interested_image.classList.remove("far");
      interested_user_count.innerHTML = parseFloat(interested_user_count.innerHTML) + 1;
    } else {
      is_interested_image.classList.add("far");
      is_interested_image.classList.remove("fas");
      interested_user_count.innerHTML = parseFloat(interested_user_count.innerHTML) - 1;
    }
  } else if (!response.success && !response.is_logged_in) {
    window.$("#login-modal").modal("show");
  }
};

var on_error = function () {
  if (document.getElementById("loading")) {
    document.getElementById("loading").style.display = "none";
  }
  pglifeAlert("Connection to server could not be established!", false);
};
