<?php
  session_start();
  session_destroy();
  require_once __DIR__ . "/includes/config.php";
  header("location: " . BASE_URL . "/index.php");
  exit();
?>
