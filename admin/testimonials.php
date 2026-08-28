<?php
$admin_title = "Manage Testimonials";
$admin_page = "testimonials";
require "_header.php";

$filter_property = isset($_GET["property_id"]) ? intval($_GET["property_id"]) : 0;
$properties = mysqli_query($con, "SELECT id, name FROM properties ORDER BY name");
$property_options = array();
if ($properties) {
  while ($p = mysqli_fetch_assoc($properties)) {
    $property_options[] = $p;
  }
}

$sql = "SELECT t.id, t.property_id, t.user_name, t.content, p.name AS property_name
  FROM testimonials t
  JOIN properties p ON p.id = t.property_id";
if ($filter_property > 0) {
  $sql .= " WHERE t.property_id = " . $filter_property;
}
$sql .= " ORDER BY t.id DESC";
$testimonials = mysqli_query($con, $sql);
$add_href = "testimonial_form.php" . ($filter_property > 0 ? "?property_id=" . $filter_property : "");
?>
<p>
  <a class="btn btn-primary" href="<?php echo htmlspecialchars($add_href); ?>">Add new testimonial</a>
</p>
<form class="form-inline mb-3" method="GET">
  <label class="mr-2">Filter by PG</label>
  <select class="form-control mr-2" name="property_id" onchange="this.form.submit()">
    <option value="0">All properties</option>
    <?php foreach ($property_options as $p) { ?>
      <option value="<?php echo intval($p["id"]); ?>" <?php echo $filter_property === intval($p["id"]) ? "selected" : ""; ?>>
        <?php echo htmlspecialchars($p["name"]); ?>
      </option>
    <?php } ?>
  </select>
</form>
<table class="admin-table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Property</th>
      <th>Reviewer</th>
      <th>Review</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php if ($testimonials && mysqli_num_rows($testimonials) > 0) { ?>
    <?php while ($t = mysqli_fetch_assoc($testimonials)) {
      $preview = $t["content"];
      if (strlen($preview) > 80) {
        $preview = substr($preview, 0, 80) . "...";
      }
    ?>
      <tr>
        <td><?php echo intval($t["id"]); ?></td>
        <td><?php echo htmlspecialchars($t["property_name"]); ?></td>
        <td><?php echo htmlspecialchars($t["user_name"]); ?></td>
        <td><?php echo htmlspecialchars($preview); ?></td>
        <td class="admin-actions">
          <a class="btn btn-sm btn-primary" href="testimonial_form.php?id=<?php echo intval($t["id"]); ?>">Edit</a>
          <a class="btn btn-sm btn-info" target="_blank" href="/PGLIFE/property_detail.php?property_id=<?php echo intval($t["property_id"]); ?>">View</a>
          <form class="js-admin-form" action="/PGLIFE/api/admin_testimonials.php" method="POST" data-confirm="Delete this testimonial?">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo intval($t["id"]); ?>">
            <button class="btn btn-sm btn-danger" type="submit">Delete</button>
          </form>
        </td>
      </tr>
    <?php } ?>
  <?php } else { ?>
    <tr><td colspan="5">No testimonials yet. Click Add new testimonial.</td></tr>
  <?php } ?>
  </tbody>
</table>
<?php require "_footer.php"; ?>
