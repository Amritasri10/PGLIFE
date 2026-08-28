<?php
$admin_title = "Dashboard";
$admin_page = "dashboard";
require "_header.php";

function pglife_count($con, $sql) {
  $res = mysqli_query($con, $sql);
  if (!$res) {
    return 0;
  }
  $row = mysqli_fetch_row($res);
  return intval($row[0]);
}

$users = pglife_count($con, "SELECT COUNT(*) FROM users WHERE role='user'");
$admins = pglife_count($con, "SELECT COUNT(*) FROM users WHERE role='admin'");
$properties = pglife_count($con, "SELECT COUNT(*) FROM properties");
$cities = pglife_count($con, "SELECT COUNT(*) FROM cities");
$bookings = pglife_count($con, "SELECT COUNT(*) FROM bookings");
$pending = pglife_count($con, "SELECT COUNT(*) FROM bookings WHERE status='pending'");
$paid = pglife_count($con, "SELECT COUNT(*) FROM bookings WHERE payment_status='paid'");
$testimonials = pglife_count($con, "SELECT COUNT(*) FROM testimonials");
?>
<div class="admin-welcome">
  <!-- <img src="/PGLIFE/img/logo.png" alt="PG Life" class="admin-welcome-logo" /> -->
  <div>
    <h2>Welcome to PG Life Admin</h2>
    <p>Manage listings, reviews, bookings, and payments from one place.</p>
  </div>
</div>
<div class="admin-cards">
  <div class="admin-card"><span>Properties</span><strong><?php echo $properties; ?></strong></div>
  <div class="admin-card"><span>Cities</span><strong><?php echo $cities; ?></strong></div>
  <div class="admin-card"><span>Users</span><strong><?php echo $users; ?></strong></div>
  <div class="admin-card"><span>Admins</span><strong><?php echo $admins; ?></strong></div>
  <div class="admin-card"><span>All bookings</span><strong><?php echo $bookings; ?></strong></div>
  <div class="admin-card"><span>Pending bookings</span><strong><?php echo $pending; ?></strong></div>
  <div class="admin-card"><span>Paid bookings</span><strong><?php echo $paid; ?></strong></div>
  <div class="admin-card"><span>Testimonials</span><strong><?php echo $testimonials; ?></strong></div>
</div>
<p>Add PGs from <a href="properties.php">Properties</a>. PG reviews from <a href="testimonials.php">Testimonials</a>. Confirm bookings, then users pay via Razorpay.</p>
<?php require "_footer.php"; ?>
