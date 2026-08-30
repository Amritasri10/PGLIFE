<?php
  // ── CHANGE THESE FOR PRODUCTION (InfinityFree) ──────────────────────────
  // InfinityFree → Control Panel → MySQL Databases se copy karo
  $db_hostname = "127.0.0.1";   // InfinityFree dega: e.g. "sql200.infinityfree.com"
  $db_username = "root";         // InfinityFree dega: e.g. "if0_12345678"
  $db_password = "";             // Tumhara set kiya hua password
  $db_name     = "pg_life";      // InfinityFree dega: e.g. "if0_12345678_pg_life"
  $db_port     = 3306;           // InfinityFree standard port — 3306 (localhost ke liye 3307 tha)
  // ────────────────────────────────────────────────────────────────────────

  mysqli_report(MYSQLI_REPORT_OFF);
  $con = @mysqli_connect($db_hostname, $db_username, $db_password, $db_name, $db_port);
  if ($con) {
    require_once __DIR__ . "/schema.php";
    pglife_ensure_schema($con);
  }
?>
