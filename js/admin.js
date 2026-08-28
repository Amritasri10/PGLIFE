function pglifeApi(url, formData) {
  return fetch(url, {
    method: "POST",
    body: formData,
    credentials: "same-origin"
  }).then(function (res) {
    return res.text().then(function (text) {
      var json = null;
      try {
        json = text ? JSON.parse(text) : null;
      } catch (e) {
        json = null;
      }
      return { httpStatus: res.status, raw: text, json: json };
    });
  });
}

function pglifeHandle(result, reload) {
  if (!result.json) {
    pglifeAlert("API did not return JSON. Status " + result.httpStatus + "\n" + (result.raw || "").slice(0, 300), false);
    return;
  }
  pglifeAlert(result.json.message || (result.json.success ? "Done" : "Request failed"), !!result.json.success).then(function () {
    if (result.json.success && reload !== false) {
      window.location.reload();
    }
  });
}

function pglifeSubmitAdminForm(form) {
  var fd = new FormData(form);
  pglifeApi(form.getAttribute("action"), fd).then(function (result) {
    pglifeHandle(result, form.getAttribute("data-reload") !== "false");
  });
}

document.addEventListener("submit", function (event) {
  var form = event.target;
  if (!form.classList.contains("js-admin-form")) {
    return;
  }
  event.preventDefault();
  var confirmMsg = form.getAttribute("data-confirm");
  if (confirmMsg) {
    pglifeConfirm(confirmMsg).then(function (ok) {
      if (ok) {
        pglifeSubmitAdminForm(form);
      }
    });
    return;
  }
  pglifeSubmitAdminForm(form);
});
