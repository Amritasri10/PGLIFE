<?php
session_start();
session_destroy();
session_start();

require "../includes/database_connect_hide_error.php";
require "../includes/json.php";

if (!$con) {
  pglife_json(array("success" => false, "message" => "Database Connectivity Error!"), 500);
}

$full_name = isset($_POST["full_name"]) ? trim($_POST["full_name"]) : "";
$phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : "";
$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
$password = isset($_POST["password"]) ? $_POST["password"] : "";
$college_name = isset($_POST["college_name"]) ? trim($_POST["college_name"]) : "";
$gender = isset($_POST["gender"]) && in_array($_POST["gender"], array("male", "female", "other"), true)
  ? $_POST["gender"]
  : "other";

if ($full_name === "" || $phone === "" || $email === "" || $password === "" || $college_name === "") {
  pglife_json(array("success" => false, "message" => "All fields are required"));
}

$stmt = mysqli_prepare($con, "SELECT id FROM users WHERE email = ?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$existing = mysqli_stmt_get_result($stmt);
if ($existing && mysqli_num_rows($existing) > 0) {
  pglife_json(array("success" => false, "message" => "This email id is already registered with us!"));
}

$password_hash = sha1($password);
$role = "user";
$ins = mysqli_prepare($con, "INSERT INTO users (full_name, phone, email, password, college_name, gender, role) VALUES (?,?,?,?,?,?,?)");
mysqli_stmt_bind_param($ins, "sssssss", $full_name, $phone, $email, $password_hash, $college_name, $gender, $role);

if (!mysqli_stmt_execute($ins)) {
  pglife_json(array("success" => false, "message" => "Something went wrong! Registration unsuccessful!"), 500);
}

pglife_json(array("success" => true, "message" => "User is successfully registered! Please login."));
