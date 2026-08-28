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

$action = pglife_post("action");

if ($action === "create") {
  $property_id = pglife_int(pglife_post("property_id"));
  $user_name = pglife_post("user_name");
  $content = pglife_post("content");
  if ($property_id <= 0 || $user_name === "" || $content === "") {
    pglife_json(array("success" => false, "message" => "Property, reviewer name and review text are required"));
  }
  $check = mysqli_prepare($con, "SELECT id FROM properties WHERE id = ?");
  mysqli_stmt_bind_param($check, "i", $property_id);
  mysqli_stmt_execute($check);
  $prop = mysqli_stmt_get_result($check);
  if (!$prop || mysqli_num_rows($prop) === 0) {
    pglife_json(array("success" => false, "message" => "Property not found"));
  }
  $stmt = mysqli_prepare($con, "INSERT INTO testimonials (property_id, user_name, content) VALUES (?, ?, ?)");
  mysqli_stmt_bind_param($stmt, "iss", $property_id, $user_name, $content);
  if (!mysqli_stmt_execute($stmt)) {
    pglife_json(array("success" => false, "message" => "Could not add testimonial", "error" => mysqli_error($con)), 500);
  }
  pglife_json(array(
    "success" => true,
    "message" => "Testimonial added",
    "id" => mysqli_insert_id($con),
    "property_id" => $property_id
  ));
}

if ($action === "update") {
  $id = pglife_int(pglife_post("id"));
  $property_id = pglife_int(pglife_post("property_id"));
  $user_name = pglife_post("user_name");
  $content = pglife_post("content");
  if ($id <= 0 || $property_id <= 0 || $user_name === "" || $content === "") {
    pglife_json(array("success" => false, "message" => "All testimonial fields are required"));
  }
  $stmt = mysqli_prepare($con, "UPDATE testimonials SET property_id = ?, user_name = ?, content = ? WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "issi", $property_id, $user_name, $content, $id);
  if (!mysqli_stmt_execute($stmt)) {
    pglife_json(array("success" => false, "message" => "Could not update testimonial"), 500);
  }
  pglife_json(array("success" => true, "message" => "Testimonial updated", "id" => $id));
}

if ($action === "delete") {
  $id = pglife_int(pglife_post("id"));
  if ($id <= 0) {
    pglife_json(array("success" => false, "message" => "Invalid testimonial"));
  }
  $stmt = mysqli_prepare($con, "DELETE FROM testimonials WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  pglife_json(array("success" => true, "message" => "Testimonial deleted", "id" => $id));
}

pglife_json(array("success" => false, "message" => "Unknown action"));
