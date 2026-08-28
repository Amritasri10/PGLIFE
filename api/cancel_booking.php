<?php
session_start();
require "../includes/database_connect_hide_error.php";
require "../includes/json.php";
require "../includes/auth.php";

pglife_require_method("POST");
pglife_require_login();

if (!$con) {
  pglife_json(array("success" => false, "message" => "Database Connectivity Error!"), 500);
}

$booking_id = pglife_int(pglife_post("booking_id"));
$user_id = intval($_SESSION["user_id"]);
if ($booking_id <= 0) {
  pglife_json(array("success" => false, "message" => "Invalid booking"));
}

$stmt = mysqli_prepare($con, "UPDATE bookings SET status='cancelled' WHERE id = ? AND user_id = ? AND status IN ('pending','confirmed') AND (payment_status IS NULL OR payment_status <> 'paid')");
mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
mysqli_stmt_execute($stmt);
if (mysqli_stmt_affected_rows($stmt) < 1) {
  pglife_json(array("success" => false, "message" => "Booking cannot be cancelled"));
}

pglife_json(array("success" => true, "message" => "Booking cancelled", "booking_id" => $booking_id, "status" => "cancelled"));
