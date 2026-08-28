<?php
session_start();
require "../includes/database_connect_hide_error.php";
require "../includes/json.php";
require "../includes/auth.php";

if (!$con) {
  pglife_json(array("success" => false, "message" => "Database Connectivity Error!"), 500);
}

pglife_require_login();

$property_id = isset($_GET["property_id"]) ? intval($_GET["property_id"]) : 0;
if ($property_id <= 0) {
  pglife_json(array("success" => false, "message" => "Invalid property"));
}

$user_id = intval($_SESSION["user_id"]);
$del = mysqli_prepare($con, "DELETE FROM interested_users_properties WHERE user_id = ? AND property_id = ?");
mysqli_stmt_bind_param($del, "ii", $user_id, $property_id);
if (!mysqli_stmt_execute($del)) {
  pglife_json(array("success" => false, "message" => "Something went wrong"));
}

pglife_json(array(
  "success" => true,
  "is_interested" => false,
  "property_id" => $property_id,
  "message" => "Removed from interested list"
));
