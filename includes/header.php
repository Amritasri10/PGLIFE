<?php
  if (!isset($nav_page)) {
    $nav_page = basename($_SERVER["PHP_SELF"], ".php");
  }

  require_once __DIR__ . "/config.php";

  // Fetch cities from DB if connection is available
  $nav_cities = [];
  if (!isset($con)) {
    @include_once __DIR__ . "/database_connect_hide_error.php";
  }
  if (isset($con) && $con) {
    $city_result = mysqli_query($con, "SELECT name FROM cities ORDER BY name ASC");
    if ($city_result) {
      while ($city_row = mysqli_fetch_assoc($city_result)) {
        $nav_cities[] = $city_row["name"];
      }
    }
  }
  // Fallback if DB not available or no cities found
  if (empty($nav_cities)) {
    $nav_cities = ["Delhi", "Mumbai", "Bengaluru", "Hyderabad"];
  }
?>
<!-- NAVBAR -->
<div class="header sticky-top">
    <nav class="navbar navbar-expand-lg navbar-light site-navbar">
        <a class="navbar-brand brand-link" href="<?php echo BASE_URL; ?>/index.php" title="PG Life Home">
            <img class="brand-logo" src="<?php echo BASE_URL; ?>/img/logo.png" alt="PG Life" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#my-navbar" aria-controls="my-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="my-navbar">
            <ul class="navbar-nav site-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $nav_page === 'index' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $nav_page === 'about' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $nav_page === 'contact' ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/contact.php">Contact</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="citiesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Cities
                    </a>
                    <div class="dropdown-menu" aria-labelledby="citiesDropdown">
                        <?php foreach ($nav_cities as $city_name): ?>
                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>/property_list.php?city=<?php echo urlencode($city_name); ?>">
                            <?php echo htmlspecialchars($city_name); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav site-nav-auth align-items-lg-center">
              <?php if (!isset($_SESSION["user_id"])): ?>
                <li class="nav-item">
                    <a class="nav-link btn-nav-outline" href="#" data-toggle="modal" data-target="#signup-modal">
                        <i class="fas fa-user"></i> Signup
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn-nav-solid" href="#" data-toggle="modal" data-target="#login-modal">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
              <?php else: ?>
                  <li class="nav-item">
                    <span class="nav-name">Hi, <?php
                      $first_name = explode(" ", $_SESSION["full_name"])[0];
                      echo htmlspecialchars($first_name);
                    ?></span>
                  </li>
                  <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                  <li class="nav-item">
                    <a class="nav-link btn-nav-solid" href="<?php echo BASE_URL; ?>/admin/index.php">
                      <i class="fas fa-cog"></i> Admin
                    </a>
                  </li>
                  <?php else: ?>
                  <li class="nav-item">
                    <a class="nav-link btn-nav-outline" href="<?php echo BASE_URL; ?>/dashboard.php">
                      <i class="fas fa-user"></i> Dashboard
                    </a>
                  </li>
                  <?php endif; ?>
                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>/logout.php">
                      <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                  </li>
              <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>
