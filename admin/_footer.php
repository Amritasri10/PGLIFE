    </section>
  </main>
</div>
<script src="/PGLIFE/js/jquery.js"></script>
<script src="/PGLIFE/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/PGLIFE/js/swal.js"></script>
<script src="/PGLIFE/js/admin.js"></script>
</body>
</html>
<?php
if (isset($con) && $con) {
  mysqli_close($con);
}
