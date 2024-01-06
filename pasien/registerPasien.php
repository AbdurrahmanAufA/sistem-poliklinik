<?php
include_once("../koneksi.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];

    // Membuat no_rm
    if ($result->num_rows == 0) {
        $result = mysqli_query($mysqli, "SELECT COUNT(*) as total FROM pasien");
        $row = mysqli_fetch_assoc($result);
        $totalPasien = $row['total'];

        // Generate no_rm based on the current date and total number of pasien
        $no_rm = date('Y') . date('m') . '-' . ($totalPasien + 1);

        $sql = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rm')";
        $tambah = mysqli_query($mysqli, $sql);

        $success = "No RM anda adalah $no_rm";
        header("Location: registerPasien.php?no_rm=$no_rm");
    } else {
        $error = "Gagal";
        // echo "
        //     <script> 
        //         alert('Berhasil menambah data.');
        //         document.location='index.php?page=daftarPasienBaru';
        //     </script>
        // ";
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

<body class="hold-transition register-page">
    <div class="register-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
            </div>
            <div class="card-body">
                <p class="register-box-msg">Sign in to start your session</p>

                <form method="POST" action="registerPasien.php">
                    <?php
                    if (!isset($error) && isset($_GET['no_rm'])) {
                        echo '<div class="alert alert-success"> Nomor RM anda adalah ' . $_GET['no_rm'] . '
                                    <button name="success" type="button" class="close" data-dismiss="alert" aria-label="Success">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                    }
                    if (isset($error)) {
                        echo '<div class="alert alert-danger">' . $error . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>';
                    }
                    ?>

                    <div class="input-group mb-3">
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                        <div class="input-group-append">
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="alamat" class="form-control" placeholder="Masukkan alamat" required>
                        <div class="input-group-append">
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="number" name="no_ktp" class="form-control" placeholder="Masukkan no ktp" required>
                        <div class="input-group-append">
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="number" name="no_hp" class="form-control" placeholder="Masukkan no hp" required>
                        <div class="input-group-append">
                        </div>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-4">
                            <button name="daftar" type="submit" class="btn btn-primary btn-block">Daftar</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <p class="mb-0">
                    <a href="loginPasien.php" class="text-center">Sudah mempunyai no_rm</a>
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