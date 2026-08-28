<?php
$admin_title = "Manage Bookings";
$admin_page = "bookings";
require "_header.php";
$bookings = mysqli_query($con, "SELECT b.*, u.full_name, u.email, p.name AS property_name, p.rent
  FROM bookings b
  JOIN users u ON u.id = b.user_id
  JOIN properties p ON p.id = b.property_id
  ORDER BY b.id DESC");
?>
<table class="admin-table">
  <thead>
    <tr>
      <th>ID</th><th>User</th><th>Property</th><th>Move-in</th><th>Months</th><th>Amount</th><th>Booking</th><th>Payment</th><th>Update</th>
    </tr>
  </thead>
  <tbody>
  <?php if ($bookings && mysqli_num_rows($bookings) > 0) { ?>
    <?php while ($b = mysqli_fetch_assoc($bookings)) {
      $pay_status = isset($b["payment_status"]) ? $b["payment_status"] : "unpaid";
      $amount = intval($b["rent"]) * intval($b["duration_months"]);
    ?>
      <tr>
        <td><?php echo intval($b["id"]); ?></td>
        <td><?php echo htmlspecialchars($b["full_name"]); ?><br><small><?php echo htmlspecialchars($b["email"]); ?></small></td>
        <td><?php echo htmlspecialchars($b["property_name"]); ?></td>
        <td><?php echo htmlspecialchars($b["move_in_date"]); ?></td>
        <td><?php echo intval($b["duration_months"]); ?></td>
        <td>Rs <?php echo $amount; ?></td>
        <td><span class="badge badge-<?php echo htmlspecialchars($b["status"]); ?>"><?php echo htmlspecialchars($b["status"]); ?></span></td>
        <td>
          <strong><?php echo htmlspecialchars($pay_status); ?></strong>
          <?php if (!empty($b["razorpay_payment_id"])) { ?>
            <br><small>Pay ID: <?php echo htmlspecialchars($b["razorpay_payment_id"]); ?></small>
          <?php } ?>
          <?php if (!empty($b["payment_method"])) { ?>
            <br><small><?php echo htmlspecialchars($b["payment_method"]); ?></small>
          <?php } ?>
        </td>
        <td>
          <form class="js-admin-form form-inline" action="/PGLIFE/api/admin_bookings.php" method="POST">
            <input type="hidden" name="id" value="<?php echo intval($b["id"]); ?>">
            <select class="form-control form-control-sm mr-2" name="status">
              <?php foreach (array("pending","confirmed","cancelled","completed") as $st) { ?>
                <option value="<?php echo $st; ?>" <?php echo $b["status"] === $st ? "selected" : ""; ?>><?php echo $st; ?></option>
              <?php } ?>
            </select>
            <button class="btn btn-sm btn-primary" type="submit">Update</button>
          </form>
        </td>
      </tr>
    <?php } ?>
  <?php } else { ?>
    <tr><td colspan="9">No bookings yet. Users can book from a property detail page.</td></tr>
  <?php } ?>
  </tbody>
</table>
<?php require "_footer.php"; ?>
