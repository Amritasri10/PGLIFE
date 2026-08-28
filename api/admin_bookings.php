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

$booking_id = pglife_int(pglife_post("id"));
$status = pglife_post("status");
$allowed = array("pending", "confirmed", "cancelled", "completed");
if ($booking_id <= 0 || !in_array($status, $allowed, true)) {
  pglife_json(array("success" => false, "message" => "Valid booking and status are required"));
}

$cur = mysqli_prepare($con, "SELECT status, payment_status FROM bookings WHERE id = ?");
mysqli_stmt_bind_param($cur, "i", $booking_id);
mysqli_stmt_execute($cur);
$cur_res = mysqli_stmt_get_result($cur);
$row = $cur_res ? mysqli_fetch_assoc($cur_res) : null;
if (!$row) {
  pglife_json(array("success" => false, "message" => "Booking not found"));
}
if (isset($row["payment_status"]) && $row["payment_status"] === "paid" && in_array($status, array("pending", "cancelled"), true)) {
  pglife_json(array("success" => false, "message" => "Paid bookings cannot be set to pending or cancelled"));
}

$stmt = mysqli_prepare($con, "UPDATE bookings SET status = ? WHERE id = ?");
mysqli_stmt_bind_param($stmt, "si", $status, $booking_id);
mysqli_stmt_execute($stmt);

pglife_json(array("success" => true, "message" => "Booking status updated", "id" => $booking_id, "status" => $status, "payment_status" => $row["payment_status"]));
