<?php
  session_start();
  $nav_page = "index";
  require_once "./includes/config.php";
  require_once "./includes/database_connect_hide_error.php";

  // Fetch cities with their images from DB
  $index_cities = [];
  if (isset($con) && $con) {
    $city_res = mysqli_query($con, "SELECT name, image_path FROM cities ORDER BY name ASC");
    if ($city_res) {
      while ($cr = mysqli_fetch_assoc($city_res)) {
        $index_cities[] = $cr;
      }
    }
  }
  // Fallback if DB unavailable
  if (empty($index_cities)) {
    $index_cities = [
      ["name" => "Delhi",     "image_path" => "img/delhi.png"],
      ["name" => "Mumbai",    "image_path" => "img/mumbai.png"],
      ["name" => "Bengaluru", "image_path" => "img/bangalore.png"],
      ["name" => "Hyderabad", "image_path" => "img/hyderabad.png"],
    ];
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
      $seo_title       = "PG Life | Find Verified Paying Guest Homes in India";
      $seo_description = "Find affordable, verified PG accommodations near colleges and workplaces in Delhi, Mumbai, Bengaluru, Hyderabad and more. Compare rent, amenities, book online.";
      $seo_keywords    = "PG accommodation India, paying guest near college, student PG Delhi Mumbai Bengaluru Hyderabad, affordable PG rooms, PG with food, PG booking online";
      $seo_url         = BASE_URL . "/index.php";
      $seo_image       = BASE_URL . "/img/bg.png";
      require "./includes/seo_head.php";
    ?>
    <link rel="icon" href="favicon.ico" />
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "PG Life",
      "url": "<?php echo BASE_URL; ?>",
      "description": "Find verified paying guest accommodations across India",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?php echo BASE_URL; ?>/property_list.php?city={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>

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
        <?php foreach ($index_cities as $c): ?>
          <a href="property_list.php?city=<?php echo urlencode($c["name"]); ?>"><?php echo htmlspecialchars($c["name"]); ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="city-container">
    <div class="section-heading">
      <h2>Major Cities</h2>
      <p>Pick a city and explore PG options tailored for students and professionals.</p>
    </div>
    <div class="city-box">
      <?php foreach ($index_cities as $c):
        // Use uploaded image if available, else show a placeholder
        if (!empty($c["image_path"])) {
          $img_src = BASE_URL . "/" . htmlspecialchars($c["image_path"]);
        } else {
          $img_src = BASE_URL . "/img/bg.png";
        }
      ?>
      <div class="city-img">
        <a href="property_list.php?city=<?php echo urlencode($c["name"]); ?>">
          <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($c["name"]); ?>">
          <span><?php echo htmlspecialchars($c["name"]); ?></span>
        </a>
      </div>
      <?php endforeach; ?>
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
