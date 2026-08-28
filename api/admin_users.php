<?php
session_start();
require "../includes/database_connect_hide_error.php";
require "../includes/json.php";
require "../includes/auth.php";

pglife_require_admin();
pglife_require_method("POST");
if (!$con) {
  pglife_json(array("success" => false, "message" => "Database Connectivity Error!"), 500);
}

$action = pglife_post("action");

if ($action === "update_role") {
  $id = pglife_int(pglife_post("id"));
  $role = pglife_post("role");
  if ($id <= 0 || !in_array($role, array("user", "admin"), true)) {
    pglife_json(array("success" => false, "message" => "Valid user and role are required"));
  }
  if ($id === intval($_SESSION["user_id"]) && $role !== "admin") {
    pglife_json(array("success" => false, "message" => "You cannot remove your own admin role"));
  }
  $stmt = mysqli_prepare($con, "UPDATE users SET role = ? WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "si", $role, $id);
  mysqli_stmt_execute($stmt);
  pglife_json(array("success" => true, "message" => "User role updated", "id" => $id, "role" => $role));
}

if ($action === "delete") {
  $id = pglife_int(pglife_post("id"));
  if ($id <= 0) {
    pglife_json(array("success" => false, "message" => "Invalid user"));
  }
  if ($id === intval($_SESSION["user_id"])) {
    pglife_json(array("success" => false, "message" => "You cannot delete your own account"));
  }
  mysqli_query($con, "DELETE FROM interested_users_properties WHERE user_id = " . $id);
  mysqli_query($con, "DELETE FROM bookings WHERE user_id = " . $id);
  $stmt = mysqli_prepare($con, "DELETE FROM users WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  pglife_json(array("success" => true, "message" => "User deleted", "id" => $id));
}

pglife_json(array("success" => false, "message" => "Unknown action"));
