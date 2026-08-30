<?php
session_start();
$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;
$admin_title = $id > 0 ? "Edit Property" : "Add Property";
$admin_page = "properties";
require "_header.php";

$property = array(
  "id" => 0,
  "city_id" => "",
  "name" => "",
  "address" => "",
  "description" => "",
  "gender" => "male",
  "rent" => "",
  "rating_clean" => "4.0",
  "rating_food" => "4.0",
  "rating_safety" => "4.0"
);
$selected_amenities = array();

if ($id > 0) {
  $stmt = mysqli_prepare($con, "SELECT * FROM properties WHERE id = ?");
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if ($res && mysqli_num_rows($res) === 1) {
    $property = mysqli_fetch_assoc($res);
  } else {
    echo "<p>Property not found.</p>";
    require "_footer.php";
    exit;
  }
  $am = mysqli_query($con, "SELECT amenity_id FROM properties_amenities WHERE property_id = " . $id);
  while ($am && $row = mysqli_fetch_assoc($am)) {
    $selected_amenities[] = intval($row["amenity_id"]);
  }
}

$cities = mysqli_query($con, "SELECT id, name FROM cities ORDER BY name");
$amenities = mysqli_query($con, "SELECT id, name, type FROM amenities ORDER BY type, name");
?>
<div class="admin-form">
  <form class="js-admin-form" action="<?php echo BASE_URL; ?>/api/admin_properties.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="<?php echo $id > 0 ? 'update' : 'create'; ?>">
    <?php if ($id > 0) { ?>
      <input type="hidden" name="id" value="<?php echo $id; ?>">
    <?php } ?>
    <div class="form-group">
      <label>City</label>
      <select class="form-control" name="city_id" required>
        <option value="">Select city</option>
        <?php while ($city = mysqli_fetch_assoc($cities)) { ?>
          <option value="<?php echo intval($city["id"]); ?>" <?php echo intval($property["city_id"]) === intval($city["id"]) ? "selected" : ""; ?>>
            <?php echo htmlspecialchars($city["name"]); ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div class="form-group">
      <label>Property name</label>
      <input class="form-control" name="name" required value="<?php echo htmlspecialchars($property["name"]); ?>">
    </div>
    <div class="form-group">
      <label>Address</label>
      <input class="form-control" name="address" required value="<?php echo htmlspecialchars($property["address"]); ?>">
    </div>
    <div class="form-group">
      <label>Description</label>
      <textarea class="form-control" name="description" rows="4"><?php echo htmlspecialchars($property["description"]); ?></textarea>
    </div>
    <div class="form-row">
      <div class="form-group col-md-4">
        <label>Gender</label>
        <select class="form-control" name="gender">
          <option value="male" <?php echo $property["gender"] === "male" ? "selected" : ""; ?>>Male</option>
          <option value="female" <?php echo $property["gender"] === "female" ? "selected" : ""; ?>>Female</option>
          <option value="other" <?php echo $property["gender"] !== "male" && $property["gender"] !== "female" ? "selected" : ""; ?>>Unisex / Other</option>
        </select>
      </div>
      <div class="form-group col-md-4">
        <label>Rent</label>
        <input class="form-control" type="number" name="rent" min="1" required value="<?php echo htmlspecialchars($property["rent"]); ?>">
      </div>
      <div class="form-group col-md-4">
        <label>Cover image</label>
        <input class="form-control-file" type="file" name="image" accept="image/*">
      </div>
    </div>
    <div class="form-row">
      <div class="form-group col-md-4">
        <label>Clean rating</label>
        <input class="form-control" type="number" step="0.1" min="0" max="5" name="rating_clean" value="<?php echo htmlspecialchars($property["rating_clean"]); ?>">
      </div>
      <div class="form-group col-md-4">
        <label>Food rating</label>
        <input class="form-control" type="number" step="0.1" min="0" max="5" name="rating_food" value="<?php echo htmlspecialchars($property["rating_food"]); ?>">
      </div>
      <div class="form-group col-md-4">
        <label>Safety rating</label>
        <input class="form-control" type="number" step="0.1" min="0" max="5" name="rating_safety" value="<?php echo htmlspecialchars($property["rating_safety"]); ?>">
      </div>
    </div>
    <div class="form-group">
      <label>Amenities</label>
      <div class="row">
        <?php while ($amenity = mysqli_fetch_assoc($amenities)) { ?>
          <div class="col-md-4">
            <label>
              <input type="checkbox" name="amenity_ids[]" value="<?php echo intval($amenity["id"]); ?>"
                <?php echo in_array(intval($amenity["id"]), $selected_amenities, true) ? "checked" : ""; ?>>
              <?php echo htmlspecialchars($amenity["name"]); ?>
              <small>(<?php echo htmlspecialchars($amenity["type"]); ?>)</small>
            </label>
          </div>
        <?php } ?>
      </div>
    </div>
    <button class="btn btn-primary" type="submit"><?php echo $id > 0 ? "Update property" : "Create property"; ?></button>
    <a class="btn btn-secondary" href="properties.php">Back</a>
  </form>
</div>
<?php require "_footer.php"; ?>
