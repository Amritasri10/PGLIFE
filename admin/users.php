<?php
$admin_title = "Manage Users";
$admin_page = "users";
require "_header.php";
$users = mysqli_query($con, "SELECT id, full_name, email, phone, gender, college_name, role FROM users ORDER BY id DESC");
?>
<table class="admin-table">
  <thead>
    <tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th></th></tr>
  </thead>
  <tbody>
  <?php while ($user = mysqli_fetch_assoc($users)) { ?>
    <tr>
      <td><?php echo intval($user["id"]); ?></td>
      <td><?php echo htmlspecialchars($user["full_name"]); ?></td>
      <td><?php echo htmlspecialchars($user["email"]); ?></td>
      <td><?php echo htmlspecialchars($user["phone"]); ?></td>
      <td>
        <form class="js-admin-form form-inline" action="<?php echo BASE_URL; ?>/api/admin_users.php" method="POST">
          <input type="hidden" name="action" value="update_role">
          <input type="hidden" name="id" value="<?php echo intval($user["id"]); ?>">
          <select class="form-control form-control-sm mr-2" name="role">
            <option value="user" <?php echo $user["role"] === "user" ? "selected" : ""; ?>>user</option>
            <option value="admin" <?php echo $user["role"] === "admin" ? "selected" : ""; ?>>admin</option>
          </select>
          <button class="btn btn-sm btn-primary" type="submit">Save</button>
        </form>
      </td>
      <td>
        <form class="js-admin-form" action="<?php echo BASE_URL; ?>/api/admin_users.php" method="POST" data-confirm="Delete this user?">
          <input type="hidden" name="action" value="delete">
          <input type="hidden" name="id" value="<?php echo intval($user["id"]); ?>">
          <button class="btn btn-sm btn-danger" type="submit">Delete</button>
        </form>
      </td>
    </tr>
  <?php } ?>
  </tbody>
</table>
<?php require "_footer.php"; ?>
