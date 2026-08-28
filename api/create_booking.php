<?php
session_start();
require "../includes/database_connect_hide_error.php";
require "../includes/json.php";
require "../includes/auth.php";

pglife_require_method("POST");

if (!$con) {
  pglife_json(array("success" => false, "message" => "Database Connectivity Error!"), 500);
}

pglife_require_login();

if (pglife_is_admin()) {
  pglife_json(array("success" => false, "message" => "Admin accounts cannot create user bookings"));
}

$property_id = pglife_int(pglife_post("property_id"));
$move_in_date = pglife_post("move_in_date");
$duration_months = pglife_int(pglife_post("duration_months"));
$notes = pglife_post("notes");
$user_id = intval($_SESSION["user_id"]);

if ($property_id <= 0 || $move_in_date === "" || $duration_months < 1) {
  pglife_json(array("success" => false, "message" => "Property, move-in date and duration are required"));
}

$prop = mysqli_prepare($con, "SELECT id, name, rent FROM properties WHERE id = ?");
mysqli_stmt_bind_param($prop, "i", $property_id);
mysqli_stmt_execute($prop);
$prop_res = mysqli_stmt_get_result($prop);
if (!$prop_res || mysqli_num_rows($prop_res) === 0) {
  pglife_json(array("success" => false, "message" => "Property not found"));
}
$property = mysqli_fetch_assoc($prop_res);

$dup = mysqli_prepare($con, "SELECT id FROM bookings WHERE user_id = ? AND property_id = ? AND status IN ('pending','confirmed')");
mysqli_stmt_bind_param($dup, "ii", $user_id, $property_id);
mysqli_stmt_execute($dup);
$dup_res = mysqli_stmt_get_result($dup);
if ($dup_res && mysqli_num_rows($dup_res) > 0) {
  pglife_json(array("success" => false, "message" => "You already have an active booking for this PG"));
}

$ins = mysqli_prepare($con, "INSERT INTO bookings (user_id, property_id, move_in_date, duration_months, status, notes) VALUES (?,?,?,?, 'pending', ?)");
mysqli_stmt_bind_param($ins, "iisis", $user_id, $property_id, $move_in_date, $duration_months, $notes);
if (!mysqli_stmt_execute($ins)) {
  pglife_json(array("success" => false, "message" => "Could not create booking"), 500);
}

pglife_json(array(
  "success" => true,
  "message" => "Booking request submitted for " . $property["name"],
  "booking_id" => mysqli_insert_id($con),
  "status" => "pending",
  "property" => $property["name"],
  "rent" => intval($property["rent"])
));
