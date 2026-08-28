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
  pglife_json(array("success" => false, "message" => "Razorpay keys are not configured"));
}

$booking_id = pglife_int(pglife_post("booking_id"));
$order_id = pglife_post("razorpay_order_id");
$payment_id = pglife_post("razorpay_payment_id");
$signature = pglife_post("razorpay_signature");
$user_id = intval($_SESSION["user_id"]);

if ($booking_id <= 0 || $order_id === "" || $payment_id === "" || $signature === "") {
  pglife_json(array("success" => false, "message" => "Incomplete Razorpay response"));
}

$expected = hash_hmac("sha256", $order_id . "|" . $payment_id, $config["key_secret"]);
if (!hash_equals($expected, $signature)) {
  $fail = mysqli_prepare($con, "UPDATE bookings SET payment_status = 'failed' WHERE id = ? AND user_id = ?");
  mysqli_stmt_bind_param($fail, "ii", $booking_id, $user_id);
  mysqli_stmt_execute($fail);
  pglife_json(array("success" => false, "message" => "Payment signature verification failed"));
}

$stmt = mysqli_prepare($con, "SELECT b.*, p.rent FROM bookings b JOIN properties p ON p.id = b.property_id WHERE b.id = ? AND b.user_id = ?");
mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if (!$res || mysqli_num_rows($res) === 0) {
  pglife_json(array("success" => false, "message" => "Booking not found"));
}
$booking = mysqli_fetch_assoc($res);
if ($booking["status"] !== "confirmed") {
  pglife_json(array("success" => false, "message" => "Booking is not confirmed"));
}
if (isset($booking["razorpay_order_id"]) && $booking["razorpay_order_id"] !== "" && $booking["razorpay_order_id"] !== $order_id) {
  pglife_json(array("success" => false, "message" => "Order does not match this booking"));
}

$rzp_payment = pglife_razorpay_request("GET", "/payments/" . rawurlencode($payment_id));
$method = "";
$payer_email = "";
$payer_contact = "";
if ($rzp_payment["ok"]) {
  $pdata = $rzp_payment["data"];
  $method = isset($pdata["method"]) ? $pdata["method"] : "";
  if (isset($pdata["bank"]) && $pdata["bank"]) {
    $method .= " / " . $pdata["bank"];
  }
  if (isset($pdata["wallet"]) && $pdata["wallet"]) {
    $method .= " / " . $pdata["wallet"];
  }
  if (isset($pdata["vpa"]) && $pdata["vpa"]) {
    $method .= " / " . $pdata["vpa"];
  }
  $payer_email = isset($pdata["email"]) ? $pdata["email"] : "";
  $payer_contact = isset($pdata["contact"]) ? $pdata["contact"] : "";
}

$amount_rupees = pglife_booking_amount_rupees($booking["rent"], $booking["duration_months"]);
$up = mysqli_prepare($con, "UPDATE bookings SET payment_status='paid', payment_amount=?, razorpay_order_id=?, razorpay_payment_id=?, razorpay_signature=?, payment_method=?, paid_at=NOW() WHERE id=? AND user_id=?");
mysqli_stmt_bind_param($up, "issssii", $amount_rupees, $order_id, $payment_id, $signature, $method, $booking_id, $user_id);
mysqli_stmt_execute($up);

$pay_up = mysqli_prepare($con, "UPDATE payments SET status='paid', razorpay_payment_id=?, razorpay_signature=?, payment_method=?, payer_email=?, payer_contact=?, paid_at=NOW() WHERE razorpay_order_id=? AND booking_id=?");
mysqli_stmt_bind_param($pay_up, "ssssssi", $payment_id, $signature, $method, $payer_email, $payer_contact, $order_id, $booking_id);
mysqli_stmt_execute($pay_up);
if (mysqli_stmt_affected_rows($pay_up) < 1) {
  $ins = mysqli_prepare($con, "INSERT INTO payments (booking_id, user_id, amount, currency, status, razorpay_order_id, razorpay_payment_id, razorpay_signature, payment_method, payer_email, payer_contact, paid_at) VALUES (?, ?, ?, 'INR', 'paid', ?, ?, ?, ?, ?, ?, NOW())");
  mysqli_stmt_bind_param($ins, "iiissssss", $booking_id, $user_id, $amount_rupees, $order_id, $payment_id, $signature, $method, $payer_email, $payer_contact);
  mysqli_stmt_execute($ins);
}

pglife_json(array(
  "success" => true,
  "message" => "Payment successful",
  "booking_id" => $booking_id,
  "payment_status" => "paid",
  "razorpay_payment_id" => $payment_id,
  "razorpay_order_id" => $order_id,
  "amount" => $amount_rupees,
  "method" => $method
));
