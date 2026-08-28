<?php
function pglife_razorpay_config() {
  $config = require __DIR__ . "/razorpay_config.php";
  $key_id = isset($config["key_id"]) ? trim($config["key_id"]) : "";
  $key_secret = isset($config["key_secret"]) ? trim($config["key_secret"]) : "";
  $configured = $key_id !== "" && $key_secret !== ""
    && strpos($key_id, "YOUR_KEY") === false
    && strpos($key_secret, "YOUR_KEY") === false;
  return array(
    "key_id" => $key_id,
    "key_secret" => $key_secret,
    "configured" => $configured
  );
}

function pglife_booking_amount_rupees($rent, $months) {
  return max(1, intval($rent) * max(1, intval($months)));
}

function pglife_razorpay_request($method, $path, $payload = null) {
  $config = pglife_razorpay_config();
  if (!$config["configured"]) {
    return array("ok" => false, "error" => "Razorpay keys are not configured");
  }
  $ch = curl_init("https://api.razorpay.com/v1" . $path);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_USERPWD, $config["key_id"] . ":" . $config["key_secret"]);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
  if ($method === "POST") {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
  }
  $raw = curl_exec($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $err = curl_error($ch);
  curl_close($ch);
  if ($raw === false) {
    return array("ok" => false, "error" => $err ?: "Razorpay connection failed");
  }
  $json = json_decode($raw, true);
  if ($code < 200 || $code >= 300) {
    $message = is_array($json) && isset($json["error"]["description"]) ? $json["error"]["description"] : $raw;
    return array("ok" => false, "error" => $message, "http" => $code);
  }
  return array("ok" => true, "data" => $json);
}
