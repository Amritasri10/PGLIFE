<?php
session_start();
require "../includes/database_connect_hide_error.php";
require "../includes/json.php";
require "../includes/auth.php";
require_once "../includes/schema.php";

pglife_require_admin();
if (!$con) {
  pglife_json(array("success" => false, "message" => "Database Connectivity Error!"), 500);
}

$action = pglife_post("action");
pglife_require_method("POST");

function pglife_sync_amenities($con, $property_id, $amenity_ids) {
  $del = mysqli_prepare($con, "DELETE FROM properties_amenities WHERE property_id = ?");
  mysqli_stmt_bind_param($del, "i", $property_id);
  mysqli_stmt_execute($del);
  if (!is_array($amenity_ids)) {
    return;
  }
  $ins = mysqli_prepare($con, "INSERT INTO properties_amenities (property_id, amenity_id) VALUES (?, ?)");
  foreach ($amenity_ids as $amenity_id) {
    $aid = intval($amenity_id);
    if ($aid > 0) {
      mysqli_stmt_bind_param($ins, "ii", $property_id, $aid);
      mysqli_stmt_execute($ins);
    }
  }
}

function pglife_save_image($property_id) {
  if (!isset($_FILES["image"]) || $_FILES["image"]["error"] === UPLOAD_ERR_NO_FILE) {
    return null;
  }
  if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
    return null;
  }
  $ext = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
  if (!in_array($ext, array("jpg", "jpeg", "png", "webp"), true)) {
    return null;
  }
  $dir = dirname(__DIR__) . "/img/properties/" . $property_id;
  if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
  }
  $filename = "cover." . ($ext === "jpeg" ? "jpg" : $ext);
  $dest = $dir . "/" . $filename;
  if (!move_uploaded_file($_FILES["image"]["tmp_name"], $dest)) {
    return null;
  }
  return "img/properties/" . $property_id . "/" . $filename;
}

if ($action === "create" || $action === "update") {
  $city_id = pglife_int(pglife_post("city_id"));
  $name = pglife_post("name");
  $address = pglife_post("address");
  $description = pglife_post("description");
  $gender = pglife_post("gender", "other");
  if (!in_array($gender, array("male", "female", "other"), true)) {
    $gender = "other";
  }
  $rent = pglife_int(pglife_post("rent"));
  $rating_clean = floatval(pglife_post("rating_clean", "4"));
  $rating_food = floatval(pglife_post("rating_food", "4"));
  $rating_safety = floatval(pglife_post("rating_safety", "4"));
  $amenity_ids = isset($_POST["amenity_ids"]) ? $_POST["amenity_ids"] : array();

  if ($city_id <= 0 || $name === "" || $address === "" || $rent <= 0) {
    pglife_json(array("success" => false, "message" => "City, name, address and rent are required"));
  }

  if ($action === "create") {
    $stmt = mysqli_prepare($con, "INSERT INTO properties (city_id, name, address, description, gender, rent, rating_clean, rating_food, rating_safety) VALUES (?,?,?,?,?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "issssiddd", $city_id, $name, $address, $description, $gender, $rent, $rating_clean, $rating_food, $rating_safety);
    if (!mysqli_stmt_execute($stmt)) {
      pglife_json(array("success" => false, "message" => "Could not add property", "error" => mysqli_error($con)), 500);
    }
    $property_id = mysqli_insert_id($con);
    pglife_sync_amenities($con, $property_id, $amenity_ids);
    $image_path = pglife_save_image($property_id);
    if ($image_path) {
      $up = mysqli_prepare($con, "UPDATE properties SET image_path = ? WHERE id = ?");
      mysqli_stmt_bind_param($up, "si", $image_path, $property_id);
      mysqli_stmt_execute($up);
    }
    pglife_json(array("success" => true, "message" => "Property added", "property_id" => $property_id, "name" => $name));
  }

  $property_id = pglife_int(pglife_post("id"));
  if ($property_id <= 0) {
    pglife_json(array("success" => false, "message" => "Invalid property id"));
  }
  $stmt = mysqli_prepare($con, "UPDATE properties SET city_id=?, name=?, address=?, description=?, gender=?, rent=?, rating_clean=?, rating_food=?, rating_safety=? WHERE id=?");
  mysqli_stmt_bind_param($stmt, "issssidddi", $city_id, $name, $address, $description, $gender, $rent, $rating_clean, $rating_food, $rating_safety, $property_id);
  if (!mysqli_stmt_execute($stmt)) {
    pglife_json(array("success" => false, "message" => "Could not update property"), 500);
  }
  pglife_sync_amenities($con, $property_id, $amenity_ids);
  $image_path = pglife_save_image($property_id);
  if ($image_path) {
    $up = mysqli_prepare($con, "UPDATE properties SET image_path = ? WHERE id = ?");
    mysqli_stmt_bind_param($up, "si", $image_path, $property_id);
    mysqli_stmt_execute($up);
  }
  pglife_json(array("success" => true, "message" => "Property updated", "property_id" => $property_id, "name" => $name));
}

if ($action === "delete") {
  $property_id = pglife_int(pglife_post("id"));
  if ($property_id <= 0) {
    pglife_json(array("success" => false, "message" => "Invalid property id"));
  }
  mysqli_query($con, "DELETE FROM properties_amenities WHERE property_id = " . $property_id);
  mysqli_query($con, "DELETE FROM interested_users_properties WHERE property_id = " . $property_id);
  mysqli_query($con, "DELETE FROM testimonials WHERE property_id = " . $property_id);
  mysqli_query($con, "DELETE FROM bookings WHERE property_id = " . $property_id);
  $stmt = mysqli_prepare($con, "DELETE FROM properties WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $property_id);
  mysqli_stmt_execute($stmt);
  pglife_json(array("success" => true, "message" => "Property deleted", "property_id" => $property_id));
}

pglife_json(array("success" => false, "message" => "Unknown action"));
