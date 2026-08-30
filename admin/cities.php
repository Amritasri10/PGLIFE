<?php
$admin_title = "Manage Cities";
$admin_page = "cities";
require "_header.php";
$cities = mysqli_query($con, "SELECT c.id, c.name, c.image_path, COUNT(p.id) AS property_count FROM cities c LEFT JOIN properties p ON p.city_id = c.id GROUP BY c.id, c.name, c.image_path ORDER BY c.name");
?>
<div class="admin-form">
  <h5>Add City</h5>
  <form class="js-admin-form" action="<?php echo BASE_URL; ?>/api/admin_cities.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="create">
    <div class="form-row align-items-end">
      <div class="form-group col-md-4">
        <label>City Name</label>
        <input class="form-control" name="name" placeholder="e.g. Pune" required>
      </div>
      <div class="form-group col-md-4">
        <label>City Image <small class="text-muted">(JPG/PNG, shown on homepage)</small></label>
        <input class="form-control-file" type="file" name="image" accept="image/*">
      </div>
      <div class="form-group col-md-2">
        <button class="btn btn-primary btn-block" type="submit">Add City</button>
      </div>
    </div>
  </form>
</div>

<table class="admin-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Image</th>
      <th>City Name</th>
      <th>Properties</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php while ($city = mysqli_fetch_assoc($cities)) { ?>
    <tr>
      <td><?php echo intval($city["id"]); ?></td>
      <td>
        <?php if (!empty($city["image_path"])): ?>
          <img src="<?php echo BASE_URL . '/' . htmlspecialchars($city["image_path"]); ?>"
               alt="<?php echo htmlspecialchars($city["name"]); ?>"
               style="width:60px;height:45px;object-fit:cover;border-radius:4px;">
        <?php else: ?>
          <span class="text-muted">No image</span>
        <?php endif; ?>
      </td>
      <td>
        <form class="js-admin-form" action="<?php echo BASE_URL; ?>/api/admin_cities.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="id" value="<?php echo intval($city["id"]); ?>">
          <div class="form-row align-items-center">
            <div class="col-md-5">
              <input class="form-control form-control-sm" name="name" value="<?php echo htmlspecialchars($city["name"]); ?>" required>
            </div>
            <div class="col-md-5">
              <input class="form-control-file form-control-sm" type="file" name="image" accept="image/*">
              <small class="text-muted">Leave blank to keep current image</small>
            </div>
            <div class="col-md-2">
              <button class="btn btn-sm btn-primary" type="submit">Save</button>
            </div>
          </div>
        </form>
      </td>
      <td><?php echo intval($city["property_count"]); ?></td>
      <td>
        <form class="js-admin-form" action="<?php echo BASE_URL; ?>/api/admin_cities.php" method="POST" data-confirm="Delete this city?">
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
