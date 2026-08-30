<?php
$admin_title = "Manage Properties";
$admin_page = "properties";
require "_header.php";
require_once dirname(__DIR__) . "/includes/schema.php";
$properties = mysqli_query($con, "SELECT p.*, c.name AS city_name FROM properties p LEFT JOIN cities c ON c.id = p.city_id ORDER BY p.id DESC");
?>
<p><a class="btn btn-primary" href="property_form.php">Add new property</a></p>
<table class="admin-table">
  <thead>
    <tr>
      <th>ID</th><th>Name</th><th>City</th><th>Gender</th><th>Rent</th><th></th>
    </tr>
  </thead>
  <tbody>
  <?php while ($p = mysqli_fetch_assoc($properties)) { ?>
    <tr>
      <td><?php echo intval($p["id"]); ?></td>
      <td><?php echo htmlspecialchars($p["name"]); ?></td>
      <td><?php echo htmlspecialchars($p["city_name"]); ?></td>
      <td><?php echo htmlspecialchars($p["gender"]); ?></td>
      <td>Rs <?php echo intval($p["rent"]); ?></td>
      <td class="admin-actions">
        <a class="btn btn-sm btn-primary" href="property_form.php?id=<?php echo intval($p["id"]); ?>">Edit</a>
        <a class="btn btn-sm btn-secondary" href="testimonials.php?property_id=<?php echo intval($p["id"]); ?>">Reviews</a>
        <a class="btn btn-sm btn-info" target="_blank" href="<?php echo BASE_URL; ?>/property_detail.php?property_id=<?php echo intval($p["id"]); ?>">View</a>
        <form class="js-admin-form" action="<?php echo BASE_URL; ?>/api/admin_properties.php" method="POST" data-confirm="Delete this property?">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?php echo intval($p["id"]); ?>">
          <button class="btn btn-sm btn-danger" type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php require "_footer.php"; ?>
