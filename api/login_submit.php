<?php
session_start();
require "../includes/database_connect_hide_error.php";
require "../includes/json.php";
require_once "../includes/config.php";

header("Content-Type: application/json; charset=utf-8");

if (!$con) {
  pglife_json(array("success" => false, "message" => "Database Connectivity Error!"), 500);
}

$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";

if ($email === "" || $password === "") {
  pglife_json(array("success" => false, "message" => "Email and password are required"));
}

$password_hash = sha1($password);
$stmt = mysqli_prepare($con, "SELECT id, full_name, role FROM users WHERE email = ? AND password = ?");
if (!$stmt) {
  pglife_json(array("success" => false, "message" => "We couldn't log you in at the moment!"), 500);
}

mysqli_stmt_bind_param($stmt, "ss", $email, $password_hash);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) === 1) {
  $row = mysqli_fetch_assoc($result);
  $role = isset($row["role"]) && $row["role"] === "admin" ? "admin" : "user";
  $_SESSION["user_id"] = intval($row["id"]);
  $_SESSION["full_name"] = $row["full_name"];
  $_SESSION["role"] = $role;
  $redirect = $role === "admin" ? BASE_URL . "/admin/index.php" : BASE_URL . "/index.php";
  pglife_json(array(
    "success" => true,
    "message" => "Successfully logged in",
    "role" => $role,
    "full_name" => $row["full_name"],
    "redirect" => $redirect
  ));
}

pglife_json(array("success" => false, "message" => "Incorrect Username or Password!"));
