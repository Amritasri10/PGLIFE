<?php
session_start();
require "../includes/database_connect_hide_error.php";
require "../includes/json.php";
require "../includes/auth.php";

pglife_require_admin();
if (!$con) {
  pglife_json(array("success" => false, "message" => "Database Connectivity Error!"), 500);
}

$action = pglife_post("action", isset($_GET["action"]) ? $_GET["action"] : "list");

// ── Helper: upload city image ──────────────────────────────────────────────
function upload_city_image($file_key) {
  if (empty($_FILES[$file_key]["name"])) {
    return null; // no file uploaded
  }
  $file    = $_FILES[$file_key];
  $allowed = ["image/jpeg", "image/png", "image/gif", "image/webp"];
  $finfo   = finfo_open(FILEINFO_MIME_TYPE);
  $mime    = finfo_file($finfo, $file["tmp_name"]);
  finfo_close($finfo);
  if (!in_array($mime, $allowed, true)) {
    return false; // invalid type
  }
  $ext      = pathinfo($file["name"], PATHINFO_EXTENSION);
  $filename = bin2hex(random_bytes(8)) . "." . strtolower($ext);
  $dir      = dirname(__DIR__) . "/img/cities/";
  if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
  }
  if (!move_uploaded_file($file["tmp_name"], $dir . $filename)) {
    return false;
  }
  return "img/cities/" . $filename;
}

// ── GET list ──────────────────────────────────────────────────────────────
if ($action === "list" && $_SERVER["REQUEST_METHOD"] === "GET") {
  $rows = array();
  $res  = mysqli_query($con, "SELECT id, name, image_path FROM cities ORDER BY name");
  while ($res && $row = mysqli_fetch_assoc($res)) {
    $rows[] = $row;
  }
  pglife_json(array("success" => true, "cities" => $rows));
}

pglife_require_method("POST");

// ── CREATE ────────────────────────────────────────────────────────────────
if ($action === "create") {
  $name = pglife_post("name");
  if ($name === "") {
    pglife_json(array("success" => false, "message" => "City name is required"));
  }

  $image_path = upload_city_image("image");
  if ($image_path === false) {
    pglife_json(array("success" => false, "message" => "Invalid image file. Use JPG, PNG, GIF or WebP."));
  }

  $stmt = mysqli_prepare($con, "INSERT INTO cities (name, image_path) VALUES (?, ?)");
  mysqli_stmt_bind_param($stmt, "ss", $name, $image_path);
  if (!mysqli_stmt_execute($stmt)) {
    pglife_json(array("success" => false, "message" => "City already exists or could not be added"));
  }
  pglife_json(array("success" => true, "message" => "City added", "city_id" => mysqli_insert_id($con), "name" => $name));
}

// ── UPDATE ────────────────────────────────────────────────────────────────
if ($action === "update") {
  $id   = pglife_int(pglife_post("id"));
  $name = pglife_post("name");
  if ($id <= 0 || $name === "") {
    pglife_json(array("success" => false, "message" => "City id and name are required"));
  }

  $image_path = upload_city_image("image");
  if ($image_path === false) {
    pglife_json(array("success" => false, "message" => "Invalid image file. Use JPG, PNG, GIF or WebP."));
  }

  if ($image_path !== null) {
    // New image uploaded — delete old one if exists
    $old = mysqli_query($con, "SELECT image_path FROM cities WHERE id = " . $id);
    if ($old) {
      $old_row = mysqli_fetch_assoc($old);
      if (!empty($old_row["image_path"])) {
        $old_file = dirname(__DIR__) . "/" . $old_row["image_path"];
        if (file_exists($old_file)) {
          @unlink($old_file);
        }
      }
    }
    $stmt = mysqli_prepare($con, "UPDATE cities SET name = ?, image_path = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "ssi", $name, $image_path, $id);
  } else {
    // No new image — only update name
    $stmt = mysqli_prepare($con, "UPDATE cities SET name = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "si", $name, $id);
  }

  if (!mysqli_stmt_execute($stmt)) {
    pglife_json(array("success" => false, "message" => "Could not update city"));
  }
  pglife_json(array("success" => true, "message" => "City updated", "id" => $id, "name" => $name));
}

// ── DELETE ────────────────────────────────────────────────────────────────
if ($action === "delete") {
  $id    = pglife_int(pglife_post("id"));
  $check = mysqli_prepare($con, "SELECT COUNT(*) AS c FROM properties WHERE city_id = ?");
  mysqli_stmt_bind_param($check, "i", $id);
  mysqli_stmt_execute($check);
  $count_row = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
  if ($count_row && intval($count_row["c"]) > 0) {
    pglife_json(array("success" => false, "message" => "Cannot delete city with existing properties"));
  }

  // Delete image file
  $old = mysqli_query($con, "SELECT image_path FROM cities WHERE id = " . $id);
  if ($old) {
    $old_row = mysqli_fetch_assoc($old);
    if (!empty($old_row["image_path"])) {
      $old_file = dirname(__DIR__) . "/" . $old_row["image_path"];
      if (file_exists($old_file)) {
        @unlink($old_file);
      }
    }
  }

  $stmt = mysqli_prepare($con, "DELETE FROM cities WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  pglife_json(array("success" => true, "message" => "City deleted", "id" => $id));
}

pglife_json(array("success" => false, "message" => "Unknown action"));
