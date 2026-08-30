    </section>
  </main>
</div>
<?php require_once dirname(__DIR__) . "/includes/config.php"; ?>
<script>window.PGLIFE_BASE = "<?php echo addslashes(BASE_URL); ?>";</script>
<script src="<?php echo BASE_URL; ?>/js/jquery.js"></script>
<script src="<?php echo BASE_URL; ?>/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?php echo BASE_URL; ?>/js/swal.js"></script>
<script src="<?php echo BASE_URL; ?>/js/admin.js"></script>
</body>
</html>
<?php
if (isset($con) && $con) {
  mysqli_close($con);
}
