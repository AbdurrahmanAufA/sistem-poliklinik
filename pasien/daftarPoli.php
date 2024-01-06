<?php
include_once("../koneksi.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $keluhan = $_POST['keluhan'];
    $id_jadwal = $_POST['id_jadwal'];

    // Check if the patient has already registered
    $check_query = "SELECT * FROM daftar_poli WHERE id_pasien = '" . $_SESSION['id_pasien'] . "'";
    $check_result = $mysqli->query($check_query);

    // Check if the form fields are not empty
    $query = "SELECT MAX(no_antrian) as max_no FROM daftar_poli WHERE id_jadwal = '$id_jadwal'";
    $result = $mysqli->query($query);
    $row = $result->fetch_assoc();
    $no_antrian = $row['max_no'] !== null ? $row['max_no'] + 1 : 1;

    // Insert the new poli registration into the daftar_poli table
    $insert_query = "INSERT INTO daftar_poli (id_pasien, id_jadwal, keluhan, no_antrian, tanggal) VALUES ('" . $_SESSION['id_pasien'] . "', '$id_jadwal', '$keluhan', '$no_antrian', NOW())";
    if (mysqli_query($mysqli, $insert_query)) {
        // echo "<script>alert('No antrian anda adalah $no_antrian');</script>";
        $success = "No antrian anda adalah $no_antrian";
        // $button_disabled = "disabled";
        // Redirect to prevent form resubmission
        header("Location: daftarPoli.php?no_antrian=$no_antrian");
    } else {
        $error = "Pendaftaran gagal";
    }
}

$query = "SELECT dokter.id AS dokter_id, dokter.nama AS dokter_nama, jadwal_periksa.id AS jadwal_id, jadwal_periksa.hari AS hari, jadwal_periksa.jam_mulai AS jam_mulai, jadwal_periksa.jam_selesai AS jam_selesai FROM dokter JOIN jadwal_periksa ON dokter.id = jadwal_periksa.id_dokter";
$result = $mysqli->query($query);
if (!$result) {
    die("Query error: " . $mysqli->error);
}
$dokter_schedules = $result->fetch_all(MYSQLI_ASSOC);
?>

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
    <link rel="stylesheet" href="../../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
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

                <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
                    <?php
                    if (!isset($error) && isset($_GET['no_antrian'])) {
                        echo '<div class="alert alert-success"> Nomor RM anda adalah ' . $_GET['no_antrian'] . '
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
                        <select class="form-control select2" style="width: 100%;" aria-label=" id_poli" name="id_poli">
                            <option selected>Pilih Poli...</option>
                            <?php
                            $result = mysqli_query($mysqli, "SELECT * FROM poli");

                            while ($data = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $data['id'] . "'>" . $data['nama_poli'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class=" input-group mb-3">
                        <select class="form-control select2" style="width: 100%;" aria-label="id_dokter" name="id_dokter">
                            <option selected>Pilih Dokter...</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <select class="form-control select2" style="width: 100%;" aria-label="id_jadwal" name="id_jadwal">
                            <option selected>Pilih Jadwal...</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <textarea placeholder="Keluhan anda" class="form-control" name="keluhan" id="keluhan" aria-label="With textarea"></textarea>
                    </div>
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-4">
                            <button name="simpan" type="submit" class="btn btn-primary btn-block">Daftar</button>
                            <!-- /.col -->
                        </div>
                </form>
                <p class="mb-0">
                    <a href="loginPasien.php" class="text-center">Register a new membership</a>
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

<script>
    document.querySelector("select[name='id_poli']").addEventListener('change', function() {
        var id_poli = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'ambilDokter.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status == 200) {
                var response = JSON.parse(this.responseText);
                var len = response.length;
                var select = document.querySelector("select[name='id_dokter']");
                select.innerHTML = "<option selected>Pilih Poli...</option>";
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var nama = response[i]['nama'];
                    select.innerHTML += "<option value='" + id + "'>" + nama + "</option>";
                }
            }
        }
        xhr.send('id_poli=' + id_poli);
    })


    document.querySelector("select[name='id_dokter']").addEventListener('change', function() {
        var id_dokter = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'ambilJadwal.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (this.status == 200) {
                var response = JSON.parse(this.responseText);
                var len = response.length;
                var select = document.querySelector("select[name='id_jadwal']");
                select.innerHTML = "<option selected>Pilih Jadwal...</option>";
                for (var i = 0; i < len; i++) {
                    var id = response[i]['id'];
                    var hari = response[i]['hari'];
                    var jam_mulai = response[i]['jam_mulai'];
                    var jam_selesai = response[i]['jam_selesai'];
                    select.innerHTML += "<option value='" + id + "'>" + hari + " , " + jam_mulai + " - " + jam_selesai + "</option>";
                }
            }
        }
        xhr.send('id_dokter=' + id_dokter);
    })
</script>