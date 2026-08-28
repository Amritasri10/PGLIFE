<?php
  $db_hostname = "127.0.0.1";
  $db_username = "root";
  $db_password = "";
  $db_name = "pg_life";
  $db_port = 3307;

  mysqli_report(MYSQLI_REPORT_OFF);
  $con = mysqli_connect($db_hostname, $db_username, $db_password, $db_name, $db_port);
  if ($con) {
    require_once __DIR__ . "/schema.php";
    pglife_ensure_schema($con);
  }
?>
