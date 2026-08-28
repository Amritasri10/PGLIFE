<?php
function pglife_require_login() {
  if (!isset($_SESSION["user_id"])) {
    pglife_json(array("success" => false, "is_logged_in" => false, "message" => "Please login first"), 401);
  }
}

function pglife_require_admin() {
  pglife_require_login();
  if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    pglife_json(array("success" => false, "message" => "Admin access required"), 403);
  }
}

function pglife_is_admin() {
  return isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
}
