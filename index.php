<?php
  session_start();
  $nav_page = "index";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PG Life | Find Your Perfect Paying Guest Home</title>
    <link rel="icon" href="favicon.ico" />

    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <link href="css/common.css" rel="stylesheet" />
    <link href="css/index.css" rel="stylesheet" />
    <link href="css/pages.css" rel="stylesheet" />
</head>

<body>
  <?php require "./includes/header.php"; ?>

  <div id="loading"></div>

  <section class="container-fluid pg-search-container">
    <div class="hero-panel">
      <p class="eyebrow light">Verified PG homes across India</p>
      <h1 class="hero-title">Happiness per Square Foot</h1>
      <p class="hero-subtitle">Search comfortable, affordable paying guest accommodations near colleges and workplaces.</p>
      <form class="hero-search" action="property_list.php" method="GET">
        <div class="input-group hero-search-group">
          <input name="city" class="form-control" type="text" placeholder="Search by city — Delhi, Mumbai, Bengaluru..." aria-label="Search">
          <div class="input-group-append">
            <button type="submit" class="btn btn-primary hero-search-btn">
              <i class="fas fa-search" aria-hidden="true"></i> Search
            </button>
          </div>
        </div>
      </form>
      <div class="hero-chips">
        <a href="property_list.php?city=Delhi">Delhi</a>
        <a href="property_list.php?city=Mumbai">Mumbai</a>
        <a href="property_list.php?city=Bengaluru">Bengaluru</a>
        <a href="property_list.php?city=Hyderabad">Hyderabad</a>
      </div>
    </div>
  </section>

  <section class="city-container">
    <div class="section-heading">
      <h2>Major Cities</h2>
      <p>Pick a city and explore PG options tailored for students and professionals.</p>
    </div>
    <div class="city-box">
      <div class="city-img">
        <a href="property_list.php?city=Delhi">
          <img src="./img/delhi.png" alt="Delhi">
          <span>Delhi</span>
        </a>
      </div>
      <div class="city-img">
        <a href="property_list.php?city=Mumbai">
          <img src="./img/mumbai.png" alt="Mumbai">
          <span>Mumbai</span>
        </a>
      </div>
      <div class="city-img">
        <a href="property_list.php?city=Hyderabad">
          <img src="./img/hyderabad.png" alt="Hyderabad">
          <span>Hyderabad</span>
        </a>
      </div>
      <div class="city-img">
        <a href="property_list.php?city=Bengaluru">
          <img src="./img/bangalore.png" alt="Bengaluru">
          <span>Bengaluru</span>
        </a>
      </div>
    </div>
  </section>

  <section class="content-section soft-bg">
    <div class="content-shell">
      <div class="section-heading">
        <h2>Why choose PG Life</h2>
        <p>A simpler way to discover, shortlist, book, and move into your next PG.</p>
      </div>
      <div class="info-cards">
        <article class="info-card">
          <i class="fas fa-search-location"></i>
          <h3>Smart search</h3>
          <p>Filter by city, rent, and ratings to find homes that match your budget.</p>
        </article>
        <article class="info-card">
          <i class="fas fa-heart"></i>
          <h3>Save favourites</h3>
          <p>Mark interested PGs and revisit them anytime from your dashboard.</p>
        </article>
        <article class="info-card">
          <i class="fas fa-calendar-check"></i>
          <h3>Easy booking</h3>
          <p>Request a booking online and track confirmation from your account.</p>
        </article>
        <article class="info-card">
          <i class="fas fa-lock"></i>
          <h3>Secure payments</h3>
          <p>Pay online with Razorpay after your booking is confirmed by admin.</p>
        </article>
      </div>
    </div>
  </section>

  <?php require "./includes/signup_modal.php"; ?>
  <?php require "./includes/login_modal.php"; ?>
  <?php require "./includes/footer.php"; ?>

  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>
  <script type="text/javascript" src="js/common.js"></script>
</body>

</html>
