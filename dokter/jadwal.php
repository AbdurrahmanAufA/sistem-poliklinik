<!DOCTYPE html>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$id_dokter = $_SESSION['id'];
$username = $_SESSION['username'];
$id_poli = $_SESSION['id_poli'];

if ($username == "") {
    header("location:loginDokter.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['simpan'])) {
        $idPoli = $_SESSION['id_poli'];
        $idDokter = $_SESSION['id'];
        $hari = $_POST["hari"];
        $jamMulai = $_POST["jamMulai"];
        $jamSelesai = $_POST["jamSelesai"];

        // Check for overlapping schedules
        $queryOverlap = "SELECT * FROM jadwal_periksa 
                        INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id 
                        INNER JOIN poli ON dokter.id_poli = poli.id 
                        WHERE id_poli = '$idPoli' 
                        AND hari = '$hari' 
                        AND ((jam_mulai < '$jamSelesai' AND jam_selesai > '$jamMulai') OR (jam_mulai < '$jamMulai' AND jam_selesai > '$jamMulai'))";

        $resultOverlap = mysqli_query($mysqli, $queryOverlap);

        if (mysqli_num_rows($resultOverlap) > 0) {
            echo '<script>alert("Dokter lain telah mengambil jadwal ini");window.location.href="index.php?page=jadwal";</script>';
        } else {
            // Set default value for status
            $status = 'Y';

            // Insert new schedule into the database
            $query = "INSERT INTO jadwal_periksa (id_dokter, hari, jam_mulai, jam_selesai, status) 
                    VALUES ('$idDokter', '$hari', '$jamMulai', '$jamSelesai', '$status')";
            echo $query;
            if (mysqli_query($mysqli, $query)) {
                echo '<script>';
                echo 'alert("Jadwal berhasil ditambahkan!");';
                echo 'window.location.href = "index.php?page=jadwal";';
                echo '</script>';
                exit();
            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($mysqli);
            }
        }
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $status = $_POST["status"];

        // Modify the query to include 'status' only
        $query = "UPDATE jadwal_periksa SET status = '$status' WHERE id = '$id'";

        if (mysqli_query($mysqli, $query)) {
            echo '<script>';
            echo 'alert("Jadwal berhasil diubah!");';
            echo 'window.location.href = "index.php?page=jadwal";';
            echo '</script>';
            exit();
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($mysqli);
        }
    }
}

?>


<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Poliklinik</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- /.navbar -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Quick Example</h3>
            </div>
            <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
                <div class="card-body">
                    <div class="form-group">
                        <label for="hari">Hari</label>
                        <select class="form-control" id="hari" name="hari">
                            <?php
                            $hariArray = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU'];
                            foreach ($hariArray as $hari) {
                            ?>
                                <option value="<?php echo $hari ?>">
                                    <?php echo $hari ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="jamMulai">Jam Mulai</label>
                        <input type="time" class="form-control" id="jamMulai" name="jamMulai" required>
                    </div>
                    <div class="form-group">
                        <label for="jamSelesai">Jam Selesai</label>
                        <input type="time" class="form-control" id="jamSelesai" name="jamSelesai" required>
                    </div>
                    <!-- Tambah status -->
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="Y">Aktif</option>
                            <option value="N">Tidak Aktif</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-3 mt-auto" name="simpan">Simpan</button>
                </div>
                <!-- /.card-body -->
            </form>
            <br>
            <br>
            <table class="table table-hover">
                <!--thead atau baris judul-->
                <thead>
                    <tr>
                        <th class="col-1">No</th>
                        <th>Nama Dokter</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Status</th> <!-- Tambah kolom status -->
                        <th class="col-1">Aksi</th>
                    </tr>
                </thead>
                <!--tbody berisi isi tabel sesuai dengan judul atau head-->
                <tbody>
                    <!-- Kode PHP untuk menampilkan semua isi dari tabel urut-->
                    <?php
                    include_once("../koneksi.php");
                    $no = 1;
                    $query = mysqli_query($mysqli, "SELECT jadwal_periksa.id, jadwal_periksa.id_dokter, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, jadwal_periksa.status, dokter.id AS idDokter, dokter.nama, dokter.alamat, dokter.no_hp, dokter.id_poli, poli.id AS idPoli, poli.nama_poli, poli.keterangan
                            FROM jadwal_periksa
                            INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id
                            INNER JOIN poli ON dokter.id_poli = poli.id 
                            WHERE id_poli = '$id_poli' AND dokter.id = '$id_dokter';");

                    while ($data = mysqli_fetch_array($query)) {

                    ?>
                        <tr>
                            <th scope="row"><?php echo $no++ ?></th>
                            <td><?php echo $data['nama'] ?></td>
                            <td><?php echo $data['hari'] ?></td>
                            <td><?php echo $data['jam_mulai'] ?></td>
                            <td><?php echo $data['jam_selesai'] ?></td>
                            <td class="<?php echo ($data['status'] == 'N') ? 'text-danger' : 'text-success'; ?>">
                                <?php echo ($data['status'] == 'Y') ? 'Aktif' : 'Tidak Aktif'; ?>
                            </td>

                            <!-- Edit status -->
                            <td>
                                <?php
                                $cekJadwalPeriksa = "SELECT * FROM daftar_poli INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id WHERE jadwal_periksa.id_dokter = '$id_dokter' AND daftar_poli.status_periksa = '0'";
                                $queryCekJadwal = mysqli_query($mysqli, $cekJadwalPeriksa);
                                if (mysqli_num_rows($queryCekJadwal) > 0) {
                                ?>
                                    <button type='button' class='btn btn-sm btn-warning edit-btn' data-toggle="modal" data-target="#editModal<?php echo $data['id'] ?>" disabled>Edit</button>
                                <?php } else { ?>
                                    <button type='button' class='btn btn-sm btn-warning edit-btn' data-toggle="modal" data-target="#editModal<?php echo $data['id'] ?>" <?php echo $data['id_dokter'] == $id_dokter ? '' : 'disabled' ?>>Edit</button>
                                <?php } ?>
                            </td>

                            <div class="modal fade" id="editModal<?php echo $data['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="addModalLabel">Edit Data Jadwal</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>

                                        <div class="modal-body">
                                            <form action="" method="post" name="update">
                                                <input type="hidden" class="form-control" id="id" name="id" value="<?php echo $data['id'] ?>" required>

                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" id="status" name="status">
                                                        <option value="Y" <?php echo ($data['status'] == 'Y') ? 'selected' : ''; ?>>Aktif</option>
                                                        <option value="N" <?php echo ($data['status'] == 'N') ? 'selected' : ''; ?>>Tidak Aktif</option>
                                                    </select>
                                                </div>

                                                <button type="submit" class="btn btn-primary" name="update">Simpan</button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- Main Footer -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="assets/dist/js/adminlte.min.js"></script>
</body>

</html>