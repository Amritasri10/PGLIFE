function pglifeAlert(message, isSuccess) {
  var icon = "info";
  if (isSuccess === true) {
    icon = "success";
  } else if (isSuccess === false) {
    icon = "error";
  }
  if (typeof Swal === "undefined") {
    window.alert(message);
    return Promise.resolve();
  }
  return Swal.fire({
    text: String(message || ""),
    icon: icon,
    confirmButtonText: "OK",
    confirmButtonColor: "#20bc7e"
  });
}

function pglifeConfirm(message) {
  if (typeof Swal === "undefined") {
    return Promise.resolve(window.confirm(message));
  }
  return Swal.fire({
    text: String(message || "Are you sure?"),
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "Cancel",
    confirmButtonColor: "#dc3545",
    cancelButtonColor: "#6c757d"
  }).then(function (result) {
    return result.isConfirmed;
  });
}
