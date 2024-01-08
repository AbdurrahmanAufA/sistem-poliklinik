<?php
if (!isset($_SESSION)) {
  session_start();
}
include_once("../koneksi.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $no_rm = $_POST['no_rm'];

  $query = "SELECT * FROM pasien WHERE no_rm = '$no_rm'";
  $result = $mysqli->query($query);

  if (!$result) {
    die("Query error: " . $mysqli->error);
  }

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $_SESSION['nama'] = $nama;
    $_SESSION['id_pasien'] = $row['id'];
    header("Location: daftarPoli.php?no_rm=$no_rm");
  } else {
    $error = "No. Rekam Medis tidak ditemukan";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="POST" action="loginPasien.php">
          <?php
          if (isset($error)) {
            echo '<div class="alert alert-danger">' . $error . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            </div>';
          }
          ?>
          <div class="input-group mb-3">
            <input type="text" name="no_rm" class="form-control form-control-lg bg-light fs-6" placeholder="Enter your no_rm" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <p class="mb-0">
          <a href="registerPasien.php." class="text-center">Register a new membership</a>
        </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="../plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="../dist/js/adminlte.min.js"></script>
</body>

</html>