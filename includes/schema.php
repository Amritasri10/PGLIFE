<?php
function pglife_add_column($con, $table, $column, $definition) {
  $safe_table = preg_replace("/[^a-z0-9_]/i", "", $table);
  $safe_column = preg_replace("/[^a-z0-9_]/i", "", $column);
  $res = mysqli_query($con, "SHOW COLUMNS FROM `$safe_table` LIKE '$safe_column'");
  if ($res && mysqli_num_rows($res) === 0) {
    mysqli_query($con, "ALTER TABLE `$safe_table` ADD COLUMN $definition");
  }
}

function pglife_ensure_schema($con) {
  if (!$con) {
    return;
  }

  $role_col = mysqli_query($con, "SHOW COLUMNS FROM users LIKE 'role'");
  if ($role_col && mysqli_num_rows($role_col) === 0) {
    mysqli_query($con, "ALTER TABLE users ADD COLUMN role ENUM('user','admin') NOT NULL DEFAULT 'user'");
  }

  $img_col = mysqli_query($con, "SHOW COLUMNS FROM properties LIKE 'image_path'");
  if ($img_col && mysqli_num_rows($img_col) === 0) {
    mysqli_query($con, "ALTER TABLE properties ADD COLUMN image_path VARCHAR(500) DEFAULT NULL");
  }

  mysqli_query($con, "CREATE TABLE IF NOT EXISTS bookings (
    id INT(11) NOT NULL AUTO_INCREMENT,
    user_id INT(11) NOT NULL,
    property_id INT(11) NOT NULL,
    move_in_date DATE NOT NULL,
    duration_months INT(11) NOT NULL DEFAULT 1,
    status ENUM('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
    notes VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY property_id (property_id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

  pglife_add_column($con, "bookings", "payment_status", "payment_status ENUM('unpaid','paid','failed') NOT NULL DEFAULT 'unpaid'");
  pglife_add_column($con, "bookings", "payment_amount", "payment_amount INT(11) NOT NULL DEFAULT 0");
  pglife_add_column($con, "bookings", "razorpay_order_id", "razorpay_order_id VARCHAR(100) DEFAULT NULL");
  pglife_add_column($con, "bookings", "razorpay_payment_id", "razorpay_payment_id VARCHAR(100) DEFAULT NULL");
  pglife_add_column($con, "bookings", "razorpay_signature", "razorpay_signature VARCHAR(255) DEFAULT NULL");
  pglife_add_column($con, "bookings", "payment_method", "payment_method VARCHAR(80) DEFAULT NULL");
  pglife_add_column($con, "bookings", "paid_at", "paid_at DATETIME DEFAULT NULL");

  mysqli_query($con, "CREATE TABLE IF NOT EXISTS payments (
    id INT(11) NOT NULL AUTO_INCREMENT,
    booking_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    amount INT(11) NOT NULL,
    currency VARCHAR(10) NOT NULL DEFAULT 'INR',
    status ENUM('created','paid','failed') NOT NULL DEFAULT 'created',
    razorpay_order_id VARCHAR(100) DEFAULT NULL,
    razorpay_payment_id VARCHAR(100) DEFAULT NULL,
    razorpay_signature VARCHAR(255) DEFAULT NULL,
    payment_method VARCHAR(80) DEFAULT NULL,
    payer_email VARCHAR(200) DEFAULT NULL,
    payer_contact VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    paid_at DATETIME DEFAULT NULL,
    PRIMARY KEY (id),
    KEY booking_id (booking_id),
    KEY user_id (user_id)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

  mysqli_query($con, "ALTER TABLE testimonials MODIFY id INT(11) NOT NULL AUTO_INCREMENT");

  $admin_email = "admin@pglife.com";
  $stmt = mysqli_prepare($con, "SELECT id FROM users WHERE email = ?");
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $admin_email);
    mysqli_stmt_execute($stmt);
    $existing = mysqli_stmt_get_result($stmt);
    if ($existing && mysqli_num_rows($existing) === 0) {
      $name = "PG Life Admin";
      $phone = "9999999999";
      $password = sha1("Admin@123");
      $college = "PG Life HQ";
      $gender = "male";
      $role = "admin";
      $ins = mysqli_prepare($con, "INSERT INTO users (email, password, full_name, phone, gender, college_name, role) VALUES (?,?,?,?,?,?,?)");
      if ($ins) {
        mysqli_stmt_bind_param($ins, "sssssss", $admin_email, $password, $name, $phone, $gender, $college, $role);
        mysqli_stmt_execute($ins);
        mysqli_stmt_close($ins);
      }
    } else {
      mysqli_query($con, "UPDATE users SET role='admin' WHERE email='admin@pglife.com'");
    }
    mysqli_stmt_close($stmt);
  }
}

function pglife_property_image($row) {
  if (is_array($row) && !empty($row["image_path"])) {
    return $row["image_path"];
  }
  return "img/properties/1/1d4f0757fdb86d5f.jpg";
}
