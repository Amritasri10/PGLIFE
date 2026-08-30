<?php
  session_start();
  $nav_page = "about";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About Us | PG Life</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
  <link href="css/common.css" rel="stylesheet" />
  <link href="css/pages.css" rel="stylesheet" />
</head>
<body>
  <?php require "./includes/header.php"; ?>

  <section class="page-hero">
    <div class="page-hero-inner">
      <p class="eyebrow">About PG Life</p>
      <h1>Homes that feel like home</h1>
      <p>We help students and working professionals discover verified paying guest accommodations with clarity on rent, amenities, and community reviews.</p>
    </div>
  </section>

  <section class="content-section">
    <div class="content-shell about-grid">
      <div class="about-copy">
        <h2>Why PG Life?</h2>
        <p>Finding a safe, affordable PG near your college or workplace should not be stressful. PG Life brings listings, filters, bookings, and trusted reviews into one simple experience.</p>
        <ul class="feature-bullets">
          <li><i class="fas fa-check-circle"></i> City-wise verified PG listings</li>
          <li><i class="fas fa-check-circle"></i> Transparent rents and amenities</li>
          <li><i class="fas fa-check-circle"></i> Easy interest marking and booking</li>
          <li><i class="fas fa-check-circle"></i> Secure online payments after confirmation</li>
        </ul>
        <a class="btn btn-primary" href="property_list.php?city=Delhi">Explore PGs</a>
      </div>
      <div class="about-media">
        <img src="img/about.jpg" alt="About PG Life" />
      </div>
    </div>
  </section>

  <section class="content-section soft-bg">
    <div class="content-shell">
      <div class="section-heading">
        <h2>What we stand for</h2>
        <p>Comfort, trust, and a smooth move-in journey for every resident.</p>
      </div>
      <div class="info-cards">
        <article class="info-card">
          <i class="fas fa-shield-alt"></i>
          <h3>Trust</h3>
          <p>Listings and reviews are managed carefully so you can decide with confidence.</p>
        </article>
        <article class="info-card">
          <i class="fas fa-home"></i>
          <h3>Comfort</h3>
          <p>From Wi-Fi to safety ratings, compare what matters for daily living.</p>
        </article>
        <article class="info-card">
          <i class="fas fa-handshake"></i>
          <h3>Support</h3>
          <p>Book, track status, and pay securely once your booking is confirmed.</p>
        </article>
      </div>
    </div>
  </section>

  <?php require "./includes/signup_modal.php"; ?>
  <?php require "./includes/login_modal.php"; ?>
  <?php require "./includes/footer.php"; ?>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/common.js"></script>
</body>
</html>
