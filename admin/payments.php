<?php
$admin_title = "Payments";
$admin_page = "payments";
require "_header.php";
$payments = mysqli_query($con, "SELECT pay.*, u.full_name, u.email, p.name AS property_name, b.status AS booking_status
  FROM payments pay
  JOIN users u ON u.id = pay.user_id
  JOIN bookings b ON b.id = pay.booking_id
  JOIN properties p ON p.id = b.property_id
  ORDER BY pay.id DESC");
?>
<table class="admin-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>User</th>
      <th>Property / Booking</th>
      <th>Amount</th>
      <th>Status</th>
      <th>Razorpay Order</th>
      <th>Payment ID</th>
      <th>Method</th>
      <th>Paid at</th>
    </tr>
  </thead>
  <tbody>
  <?php if ($payments && mysqli_num_rows($payments) > 0) { ?>
    <?php while ($row = mysqli_fetch_assoc($payments)) { ?>
      <tr>
        <td><?php echo intval($row["id"]); ?></td>
        <td><?php echo htmlspecialchars($row["full_name"]); ?><br><small><?php echo htmlspecialchars($row["email"]); ?></small></td>
        <td><?php echo htmlspecialchars($row["property_name"]); ?><br><small>Booking #<?php echo intval($row["booking_id"]); ?> (<?php echo htmlspecialchars($row["booking_status"]); ?>)</small></td>
        <td>Rs <?php echo intval($row["amount"]); ?> <?php echo htmlspecialchars($row["currency"]); ?></td>
        <td><?php echo htmlspecialchars($row["status"]); ?></td>
        <td><small><?php echo htmlspecialchars($row["razorpay_order_id"]); ?></small></td>
        <td><small><?php echo htmlspecialchars($row["razorpay_payment_id"]); ?></small></td>
        <td><?php echo htmlspecialchars($row["payment_method"]); ?><br><small><?php echo htmlspecialchars($row["payer_contact"]); ?></small></td>
        <td><?php echo htmlspecialchars($row["paid_at"]); ?></td>
      </tr>
    <?php } ?>
  <?php } else { ?>
    <tr><td colspan="9">No payments yet. Confirm a booking, then the user can pay from Dashboard.</td></tr>
  <?php } ?>
  </tbody>
</table>
<?php require "_footer.php"; ?>
