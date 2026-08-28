<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION["user_id"]) || !isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
  header("Location: /PGLIFE/index.php");
  exit();
}
require_once dirname(__DIR__) . "/includes/database_connect.php";
if (!$con) {
  die("Database connection failed");
}
$admin_page = isset($admin_page) ? $admin_page : "dashboard";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo htmlspecialchars($admin_title); ?> | PG Life Admin</title>
  <link href="/PGLIFE/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="/PGLIFE/css/admin.css" rel="stylesheet" />
</head>
<body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <a class="admin-brand" href="/PGLIFE/admin/index.php" title="Admin Dashboard">
      <img src="/PGLIFE/img/logo.png" alt="PG Life" class="admin-logo" />
      <span class="admin-brand-text"><small>Admin</small></span>
    </a>
    <nav>
      <a class="<?php echo $admin_page === 'dashboard' ? 'active' : ''; ?>" href="/PGLIFE/admin/index.php"><i class="fas fa-chart-line"></i> Dashboard</a>
      <a class="<?php echo $admin_page === 'properties' ? 'active' : ''; ?>" href="/PGLIFE/admin/properties.php"><i class="fas fa-building"></i> Properties</a>
      <a class="<?php echo $admin_page === 'testimonials' ? 'active' : ''; ?>" href="/PGLIFE/admin/testimonials.php"><i class="fas fa-quote-left"></i> Testimonials</a>
      <a class="<?php echo $admin_page === 'cities' ? 'active' : ''; ?>" href="/PGLIFE/admin/cities.php"><i class="fas fa-city"></i> Cities</a>
      <a class="<?php echo $admin_page === 'bookings' ? 'active' : ''; ?>" href="/PGLIFE/admin/bookings.php"><i class="fas fa-calendar-check"></i> Bookings</a>
      <a class="<?php echo $admin_page === 'payments' ? 'active' : ''; ?>" href="/PGLIFE/admin/payments.php"><i class="fas fa-rupee-sign"></i> Payments</a>
      <a class="<?php echo $admin_page === 'users' ? 'active' : ''; ?>" href="/PGLIFE/admin/users.php"><i class="fas fa-users"></i> Users</a>
    </nav>
    <div class="admin-side-foot">
      <a href="/PGLIFE/index.php"><i class="fas fa-globe"></i> View website</a>
      <a href="/PGLIFE/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
  </aside>
  <main class="admin-main">
    <header class="admin-topbar">
      <div class="admin-topbar-left">
        <a href="/PGLIFE/index.php" class="admin-top-logo" title="Open website home">
          <img src="/PGLIFE/img/logo.png" alt="PG Life" />
        </a>
        <h1><?php echo htmlspecialchars($admin_title); ?></h1>
      </div>
      <div class="admin-topbar-user">Hi, <?php echo htmlspecialchars(explode(" ", $_SESSION["full_name"])[0]); ?></div>
    </header>
    <section class="admin-content">
