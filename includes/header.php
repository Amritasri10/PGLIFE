
<!-- NAVBAR -->
<div class="header sticky-top">
    <nav class="navbar navbar-expand-md navbar-light">
        <a class="navbar-brand" href="/PGLIFE/index.php">
            <img src="/PGLIFE/img/logo.png" />
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#my-navbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="my-navbar">
            <ul class="navbar-nav">

              <?php
               if ( !isset($_SESSION["user_id"]) )
                {
              ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#signup-modal">
                        <i class="fas fa-user"></i>Signup
                    </a>
                </li>
                <div class="nav-vl"></div>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="modal" data-target="#login-modal">
                        <i class="fas fa-sign-in-alt"></i>Login
                    </a>
                </li>
              <?php
                }
                else
                {
              ?>
                  <div class='nav-name'>
                      Hi, <?php
                      $first_name = explode(" ",$_SESSION["full_name"])[0];
                      echo htmlspecialchars($first_name);
                      ?>
                  </div>
                  <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") { ?>
                  <li class="nav-item">
                    <a class="nav-link" href="/PGLIFE/admin/index.php">
                      <i class="fas fa-cog"></i>Admin Panel
                    </a>
                  </li>
                  <div class="nav-vl"></div>
                  <?php } else { ?>
                  <li class="nav-item">
                    <a class="nav-link" href="/PGLIFE/dashboard.php">
                      <i class="fas fa-user"></i>Dashboard
                    </a>
                  </li>
                  <div class="nav-vl"></div>
                  <?php } ?>
                  <li class="nav-item">
                    <a class="nav-link" href="/PGLIFE/logout.php">
                      <i class="fas fa-sign-out-alt"></i>Logout
                    </a>
                  </li>
              <?php
                }
              ?>

            </ul>
        </div>
    </nav>
</div>
