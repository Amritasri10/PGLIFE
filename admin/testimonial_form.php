<?php
session_start();
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$preselect_property = isset($_GET["property_id"]) ? intval($_GET["property_id"]) : 0;
$admin_title = $id > 0 ? "Edit Testimonial" : "Add Testimonial";
$admin_page = "testimonials";
require "_header.php";

$row = array(
  "id" => 0,
  "property_id" => $preselect_property,
  "user_name" => "",
  "content" => ""
);

if ($id > 0) {
  $stmt = mysqli_prepare($con, "SELECT * FROM testimonials WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if ($res && mysqli_num_rows($res) === 1) {
    $row = mysqli_fetch_assoc($res);
  } else {
    echo "<p>Testimonial not found.</p>";
    require "_footer.php";
    exit;
  }
}

$properties = mysqli_query($con, "SELECT id, name FROM properties ORDER BY name");
?>
<div class="admin-form">
  <form class="js-admin-form" action="/PGLIFE/api/admin_testimonials.php" method="POST" data-redirect="/PGLIFE/admin/testimonials.php">
    <input type="hidden" name="action" value="<?php echo $id > 0 ? "update" : "create"; ?>">
    <?php if ($id > 0) { ?>
      <input type="hidden" name="id" value="<?php echo $id; ?>">
    <?php } ?>
    <div class="form-group">
      <label>Property</label>
      <select class="form-control" name="property_id" required>
        <option value="">Select PG</option>
        <?php while ($p = mysqli_fetch_assoc($properties)) { ?>
          <option value="<?php echo intval($p["id"]); ?>" <?php echo intval($row["property_id"]) === intval($p["id"]) ? "selected" : ""; ?>>
            <?php echo htmlspecialchars($p["name"]); ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div class="form-group">
      <label>Reviewer name</label>
      <input class="form-control" name="user_name" required maxlength="100" value="<?php echo htmlspecialchars($row["user_name"]); ?>">
    </div>
    <div class="form-group">
      <label>Review</label>
      <textarea class="form-control" name="content" rows="5" required><?php echo htmlspecialchars($row["content"]); ?></textarea>
    </div>
    <button class="btn btn-primary" type="submit"><?php echo $id > 0 ? "Update testimonial" : "Create testimonial"; ?></button>
    <a class="btn btn-secondary" href="testimonials.php">Back</a>
  </form>
</div>
<?php require "_footer.php"; ?>
