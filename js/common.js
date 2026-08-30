// window.PGLIFE_BASE is injected by PHP (footer.php) so paths work on any host
var _base = window.PGLIFE_BASE || "";

window.addEventListener("load", function () {
  function parseJson(text) {
    try {
      return JSON.parse(text);
    } catch (e) {
      console.error("API did not return JSON", text);
      return null;
    }
  }

  var signup_form = document.getElementById("signup-form");
  if (signup_form) {
    signup_form.addEventListener("submit", function (event) {
      event.preventDefault();
      var XHR = new XMLHttpRequest();
      var form_data = new FormData(signup_form);
      XHR.open("POST", _base + "/api/signup_submit.php");
      XHR.addEventListener("load", function () {
        if (document.getElementById("loading")) {
          document.getElementById("loading").style.display = "none";
        }
        var response = parseJson(XHR.responseText);
        if (!response) {
          pglifeAlert("Signup API returned an invalid response", false);
          return;
        }
        pglifeAlert(response.message, !!response.success).then(function () {
          if (response.success) {
            window.location.href = _base + "/index.php";
          }
        });
      });
      XHR.addEventListener("error", on_error);
      XHR.send(form_data);
      if (document.getElementById("loading")) {
        document.getElementById("loading").style.display = "block";
      }
    });
  }

  var login_form = document.getElementById("login-form");
  if (login_form) {
    login_form.addEventListener("submit", function (event) {
      event.preventDefault();
      var XHR = new XMLHttpRequest();
      var form_data = new FormData(login_form);
      XHR.open("POST", _base + "/api/login_submit.php");
      XHR.addEventListener("load", function () {
        if (document.getElementById("loading")) {
          document.getElementById("loading").style.display = "none";
        }
        console.log("Login API status:", XHR.status);
        console.log("Login API response:", XHR.responseText);
        var response = parseJson(XHR.responseText);
        if (!response) {
          pglifeAlert("Login API returned an invalid response", false);
          return;
        }
        pglifeAlert(response.message, !!response.success).then(function () {
          if (response.success) {
            window.location.href = response.redirect || (_base + "/index.php");
          }
        });
      });
      XHR.addEventListener("error", on_error);
      XHR.send(form_data);
      if (document.getElementById("loading")) {
        document.getElementById("loading").style.display = "block";
      }
    });
  }
});

var on_error = function () {
  if (document.getElementById("loading")) {
    document.getElementById("loading").style.display = "none";
  }
  pglifeAlert("Connection to server could not be established!", false);
};
