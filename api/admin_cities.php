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

if ($action === "list" && $_SERVER["REQUEST_METHOD"] === "GET") {
  $rows = array();
  $res = mysqli_query($con, "SELECT id, name FROM cities ORDER BY name");
  while ($res && $row = mysqli_fetch_assoc($res)) {
    $rows[] = $row;
  }
  pglife_json(array("success" => true, "cities" => $rows));
}

pglife_require_method("POST");

if ($action === "create") {
  $name = pglife_post("name");
  if ($name === "") {
    pglife_json(array("success" => false, "message" => "City name is required"));
  }
  $stmt = mysqli_prepare($con, "INSERT INTO cities (name) VALUES (?)");
  mysqli_stmt_bind_param($stmt, "s", $name);
  if (!mysqli_stmt_execute($stmt)) {
    pglife_json(array("success" => false, "message" => "City already exists or could not be added"));
  }
  pglife_json(array("success" => true, "message" => "City added", "city_id" => mysqli_insert_id($con), "name" => $name));
}

if ($action === "update") {
  $id = pglife_int(pglife_post("id"));
  $name = pglife_post("name");
  if ($id <= 0 || $name === "") {
    pglife_json(array("success" => false, "message" => "City id and name are required"));
  }
  $stmt = mysqli_prepare($con, "UPDATE cities SET name = ? WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "si", $name, $id);
  if (!mysqli_stmt_execute($stmt)) {
    pglife_json(array("success" => false, "message" => "Could not update city"));
  }
  pglife_json(array("success" => true, "message" => "City updated", "id" => $id, "name" => $name));
}

if ($action === "delete") {
  $id = pglife_int(pglife_post("id"));
  $check = mysqli_prepare($con, "SELECT COUNT(*) AS c FROM properties WHERE city_id = ?");
  mysqli_stmt_bind_param($check, "i", $id);
  mysqli_stmt_execute($check);
  $count_row = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
  if ($count_row && intval($count_row["c"]) > 0) {
    pglife_json(array("success" => false, "message" => "Cannot delete city with existing properties"));
  }
  $stmt = mysqli_prepare($con, "DELETE FROM cities WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  pglife_json(array("success" => true, "message" => "City deleted", "id" => $id));
}

pglife_json(array("success" => false, "message" => "Unknown action"));
