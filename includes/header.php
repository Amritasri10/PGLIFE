<?php
  if (!isset($nav_page)) {
    $nav_page = basename($_SERVER["PHP_SELF"], ".php");
  }
?>
<!-- NAVBAR -->
<div class="header sticky-top">
    <nav class="navbar navbar-expand-lg navbar-light site-navbar">
        <a class="navbar-brand brand-link" href="/PGLIFE/index.php" title="PG Life Home">
            <img class="brand-logo" src="/PGLIFE/img/logo.png" alt="PG Life" />
            <!-- <span class="brand-text">PG<span>Life</span></span> -->
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#my-navbar" aria-controls="my-navbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="my-navbar">
            <ul class="navbar-nav site-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo $nav_page === 'index' ? 'active' : ''; ?>" href="/PGLIFE/index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $nav_page === 'about' ? 'active' : ''; ?>" href="/PGLIFE/about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $nav_page === 'contact' ? 'active' : ''; ?>" href="/PGLIFE/contact.php">Contact</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="citiesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Cities
                    </a>
                    <div class="dropdown-menu" aria-labelledby="citiesDropdown">
                        <a class="dropdown-item" href="/PGLIFE/property_list.php?city=Delhi">Delhi</a>
                        <a class="dropdown-item" href="/PGLIFE/property_list.php?city=Mumbai">Mumbai</a>
                        <a class="dropdown-item" href="/PGLIFE/property_list.php?city=Bengaluru">Bengaluru</a>
                        <a class="dropdown-item" href="/PGLIFE/property_list.php?city=Hyderabad">Hyderabad</a>
                    </div>
                </li>
            </ul>

            <ul class="navbar-nav site-nav-auth align-items-lg-center">
              <?php
               if ( !isset($_SESSION["user_id"]) )
                {
              ?>
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
              <?php
                }
                else
                {
              ?>
                  <li class="nav-item">
                    <span class="nav-name">Hi, <?php
                      $first_name = explode(" ",$_SESSION["full_name"])[0];
                      echo htmlspecialchars($first_name);
                    ?></span>
                  </li>
                  <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") { ?>
                  <li class="nav-item">
                    <a class="nav-link btn-nav-solid" href="/PGLIFE/admin/index.php">
                      <i class="fas fa-cog"></i> Admin
                    </a>
                  </li>
                  <?php } else { ?>
                  <li class="nav-item">
                    <a class="nav-link btn-nav-outline" href="/PGLIFE/dashboard.php">
                      <i class="fas fa-user"></i> Dashboard
                    </a>
                  </li>
                  <?php } ?>
                  <li class="nav-item">
                    <a class="nav-link" href="/PGLIFE/logout.php">
                      <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                  </li>
              <?php
                }
              ?>
            </ul>
        </div>
    </nav>
</div>
