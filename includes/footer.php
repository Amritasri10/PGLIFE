<?php
  require_once __DIR__ . "/config.php";
  $property_list_path = BASE_URL . "/property_list.php";
?>

<footer class="site-footer">
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand-col">
        <a class="footer-brand" href="<?php echo BASE_URL; ?>/index.php">
          <img src="<?php echo BASE_URL; ?>/img/logo.png" alt="PG Life" class="footer-logo" />
        </a>
        <p class="footer-tagline">Find verified paying guest homes near colleges and offices across India. Comfortable stays, transparent rents, easy booking.</p>
      </div>
      <div class="footer-links-col">
        <h6>Explore</h6>
        <a href="<?php echo BASE_URL; ?>/index.php">Home</a>
        <a href="<?php echo BASE_URL; ?>/about.php">About Us</a>
        <a href="<?php echo BASE_URL; ?>/contact.php">Contact</a>
        <a href="<?php echo BASE_URL; ?>/property_list.php?city=Delhi">Browse PGs</a>
      </div>
      <div class="footer-links-col">
        <h6>Popular Cities</h6>
        <a href="<?php echo $property_list_path; ?>?city=Delhi">PG in Delhi</a>
        <a href="<?php echo $property_list_path; ?>?city=Mumbai">PG in Mumbai</a>
        <a href="<?php echo $property_list_path; ?>?city=Bengaluru">PG in Bangalore</a>
        <a href="<?php echo $property_list_path; ?>?city=Hyderabad">PG in Hyderabad</a>
      </div>
      <div class="footer-links-col">
        <h6>Contact</h6>
        <p><i class="fas fa-envelope"></i> support@pglife.com</p>
        <p><i class="fas fa-phone-alt"></i> +91 99999 99999</p>
        <p><i class="fas fa-map-marker-alt"></i> India</p>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?php echo date("Y"); ?> PG Life. All rights reserved.</span>
      <span>Made for comfortable student living</span>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>window.PGLIFE_BASE = "<?php echo addslashes(BASE_URL); ?>";</script>
<script src="<?php echo BASE_URL; ?>/js/swal.js"></script>
