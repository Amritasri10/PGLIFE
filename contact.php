<?php
  session_start();
  $nav_page = "contact";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contact Us | PG Life</title>
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
      <p class="eyebrow">Contact</p>
      <h1>We are here to help</h1>
      <p>Questions about listings, bookings, or payments? Reach out and our team will get back to you.</p>
    </div>
  </section>

  <section class="content-section">
    <div class="content-shell contact-grid">
      <div class="contact-cards">
        <div class="contact-card">
          <i class="fas fa-envelope"></i>
          <h3>Email</h3>
          <p>support@pglife.com</p>
        </div>
        <div class="contact-card">
          <i class="fas fa-phone-alt"></i>
          <h3>Phone</h3>
          <p>+91 99999 99999</p>
        </div>
        <div class="contact-card">
          <i class="fas fa-clock"></i>
          <h3>Hours</h3>
          <p>Mon – Sat, 10:00 AM – 7:00 PM</p>
        </div>
      </div>

      <div class="contact-form-card">
        <h2>Send a message</h2>
        <p class="text-muted">Fill the form and we will respond shortly.</p>
        <form id="contact-form">
          <div class="form-group">
            <label>Full name</label>
            <input class="form-control" type="text" name="name" required maxlength="80" placeholder="Your name">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input class="form-control" type="email" name="email" required placeholder="you@example.com">
          </div>
          <div class="form-group">
            <label>Message</label>
            <textarea class="form-control" name="message" rows="4" required placeholder="How can we help?"></textarea>
          </div>
          <button class="btn btn-primary btn-block" type="submit">Send Message</button>
        </form>
      </div>
    </div>
  </section>

  <?php require "./includes/signup_modal.php"; ?>
  <?php require "./includes/login_modal.php"; ?>
  <?php require "./includes/footer.php"; ?>
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/common.js"></script>
  <script>
    document.getElementById("contact-form").addEventListener("submit", function (e) {
      e.preventDefault();
      pglifeAlert("Thanks! Your message has been noted. Our team will contact you soon.", true);
      this.reset();
    });
  </script>
</body>
</html>
