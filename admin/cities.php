<?php
$admin_title = "Manage Cities";
$admin_page = "cities";
require "_header.php";
$cities = mysqli_query($con, "SELECT c.id, c.name, COUNT(p.id) AS property_count FROM cities c LEFT JOIN properties p ON p.city_id = c.id GROUP BY c.id, c.name ORDER BY c.name");
?>
<div class="admin-form">
  <h5>Add city</h5>
  <form class="js-admin-form form-inline" action="/PGLIFE/api/admin_cities.php" method="POST">
    <input type="hidden" name="action" value="create">
    <input class="form-control mr-2 mb-2" name="name" placeholder="City name" required>
    <button class="btn btn-primary mb-2" type="submit">Add City</button>
  </form>
</div>
<table class="admin-table">
  <thead><tr><th>ID</th><th>City</th><th>Properties</th><th>Actions</th></tr></thead>
  <tbody>
  <?php while ($city = mysqli_fetch_assoc($cities)) { ?>
    <tr>
      <td><?php echo intval($city["id"]); ?></td>
      <td>
        <form class="js-admin-form form-inline" action="/PGLIFE/api/admin_cities.php" method="POST">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="id" value="<?php echo intval($city["id"]); ?>">
          <input class="form-control form-control-sm mr-2" name="name" value="<?php echo htmlspecialchars($city["name"]); ?>" required>
          <button class="btn btn-sm btn-primary" type="submit">Save</button>
        </form>
      </td>
      <td><?php echo intval($city["property_count"]); ?></td>
      <td>
        <form class="js-admin-form" action="/PGLIFE/api/admin_cities.php" method="POST" data-confirm="Delete this city?">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?php echo intval($city["id"]); ?>">
          <button class="btn btn-sm btn-danger" type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php require "_footer.php"; ?>
