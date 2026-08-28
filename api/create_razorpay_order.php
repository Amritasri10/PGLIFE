<?php
session_start();
require "../includes/database_connect_hide_error.php";
require "../includes/json.php";
require "../includes/auth.php";
require "../includes/razorpay.php";

pglife_require_method("POST");
pglife_require_login();

if (!$con) {
  pglife_json(array("success" => false, "message" => "Database Connectivity Error!"), 500);
}

$config = pglife_razorpay_config();
if (!$config["configured"]) {
  pglife_json(array(
    "success" => false,
    "message" => "Razorpay keys missing. Paste Test Key ID and Secret in includes/razorpay_config.php"
  ));
}

$booking_id = pglife_int(pglife_post("booking_id"));
$user_id = intval($_SESSION["user_id"]);
if ($booking_id <= 0) {
  pglife_json(array("success" => false, "message" => "Invalid booking"));
}

$stmt = mysqli_prepare($con, "SELECT b.*, p.name AS property_name, p.rent, u.full_name, u.email, u.phone
  FROM bookings b
  JOIN properties p ON p.id = b.property_id
  JOIN users u ON u.id = b.user_id
  WHERE b.id = ? AND b.user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if (!$res || mysqli_num_rows($res) === 0) {
  pglife_json(array("success" => false, "message" => "Booking not found"));
}
$booking = mysqli_fetch_assoc($res);

if ($booking["status"] !== "confirmed") {
  pglife_json(array("success" => false, "message" => "Payment opens only after admin confirms the booking"));
}
if (isset($booking["payment_status"]) && $booking["payment_status"] === "paid") {
  pglife_json(array("success" => false, "message" => "This booking is already paid"));
}

$amount_rupees = pglife_booking_amount_rupees($booking["rent"], $booking["duration_months"]);
$amount_paise = $amount_rupees * 100;
$receipt = "booking_" . $booking_id . "_" . time();

$order = pglife_razorpay_request("POST", "/orders", array(
  "amount" => $amount_paise,
  "currency" => "INR",
  "receipt" => $receipt,
  "notes" => array(
    "booking_id" => (string) $booking_id,
    "property" => $booking["property_name"]
  )
));

if (!$order["ok"]) {
  pglife_json(array("success" => false, "message" => $order["error"]));
}

$order_id = $order["data"]["id"];
$up = mysqli_prepare($con, "UPDATE bookings SET razorpay_order_id = ?, payment_amount = ?, payment_status = 'unpaid' WHERE id = ?");
mysqli_stmt_bind_param($up, "sii", $order_id, $amount_rupees, $booking_id);
mysqli_stmt_execute($up);

$pay = mysqli_prepare($con, "INSERT INTO payments (booking_id, user_id, amount, currency, status, razorpay_order_id) VALUES (?, ?, ?, 'INR', 'created', ?)");
mysqli_stmt_bind_param($pay, "iiis", $booking_id, $user_id, $amount_rupees, $order_id);
mysqli_stmt_execute($pay);

pglife_json(array(
  "success" => true,
  "message" => "Razorpay order created",
  "key_id" => $config["key_id"],
  "order_id" => $order_id,
  "amount" => $amount_paise,
  "amount_rupees" => $amount_rupees,
  "currency" => "INR",
  "booking_id" => $booking_id,
  "property" => $booking["property_name"],
  "prefill" => array(
    "name" => $booking["full_name"],
    "email" => $booking["email"],
    "contact" => $booking["phone"]
  )
));
