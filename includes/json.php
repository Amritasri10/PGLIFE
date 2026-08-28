<?php
function pglife_json($data, $code = 200) {
  http_response_code($code);
  header("Content-Type: application/json; charset=utf-8");
  header("Cache-Control: no-store");
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
  exit;
}

function pglife_require_method($method) {
  if ($_SERVER["REQUEST_METHOD"] !== $method) {
    pglife_json(array("success" => false, "message" => "Invalid request method"), 405);
  }
}

function pglife_post($key, $default = "") {
  return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
}

function pglife_int($value) {
  return intval($value);
}
