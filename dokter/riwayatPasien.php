<!DOCTYPE html>
<?php
$id_dokter = $_SESSION['id'];
$username = $_SESSION['username'];
$id_poli = $_SESSION['id_poli'];

if ($username == "") {
    header("location:login.php");
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
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Riwayat Pasien</h3>
                            </div>
                            <!-- /.card-header -->


                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="col-1">No</th>
                                            <th>Nama Pasien</th>
                                            <th>Alamat</th>
                                            <th>No. KTP</th>
                                            <th>No. Telepon</th>
                                            <th>No. RM</th>
                                            <th class="col-1">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        require '../koneksi.php';
                                        $query = "SELECT daftar_poli.status_periksa, periksa.id, pasien.alamat, pasien.id as idPasien, pasien.no_ktp, pasien.no_hp, pasien.no_rm, periksa.tgl_periksa, pasien.nama as namaPasien, dokter.nama, daftar_poli.keluhan, periksa.catatan, GROUP_CONCAT(obat.nama_obat) as namaObat, SUM(obat.harga) AS hargaObat FROM detail_periksa INNER JOIN periksa ON detail_periksa.id_periksa = periksa.id INNER JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id INNER JOIN pasien ON daftar_poli.id_pasien = pasien.id INNER JOIN obat ON detail_periksa.id_obat = obat.id INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id WHERE dokter.id = '$id_dokter' AND status_periksa = '1' GROUP BY pasien.id";
                                        $result = mysqli_query($mysqli, $query);

                                        while ($data = mysqli_fetch_assoc($result)) {
                                            # code...
                                        ?>
                                            <tr>
                                                <td><?php echo $no++ ?></td>
                                                <td><?php echo $data['namaPasien']; ?></td>
                                                <td><?php echo $data['alamat']; ?></td>
                                                <td><?php echo $data['no_ktp']; ?></td>
                                                <td><?php echo $data['no_hp']; ?></td>
                                                <td><?php echo $data['no_rm']; ?></td>
                                                <td>
                                                    <button type='button' class='btn btn-sm btn-success edit-btn' data-toggle="modal" data-target="#detailModal<?php echo $data['id'] ?>">Detail
                                                        Riwayat Periksa
                                                    </button>

                                                    <div class="modal fade" id="detailModal<?php echo $data['id'] ?>">
                                                        <div class="modal-dialog modal-xl">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Riwayat
                                                                        <?php echo $data['namaPasien'] ?></h4>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="card-body table-responsive p-0">
                                                                        <table class="table table-hover text-nowrap">
                                                                            <thead>
                                                                                <tr>
                                                                                    <td>No</td>
                                                                                    <td>Tanggal Periksa</td>
                                                                                    <td>Nama Pasien</td>
                                                                                    <td>Nama Dokter</td>
                                                                                    <td>Keluhan</td>
                                                                                    <td>Obat</td>
                                                                                    <td>Biaya</td>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?php
                                                                                $idPasien = $data['idPasien'];
                                                                                $nomor = 1;
                                                                                require '../koneksi.php';
                                                                                $ambilData = "SELECT detail_periksa.id as idDetailPeriksa,periksa.tgl_periksa, pasien.nama as namaPasien, dokter.nama, daftar_poli.keluhan, periksa.catatan, GROUP_CONCAT(obat.nama_obat) as namaObat, SUM(obat.harga) + periksa.biaya_periksa AS hargaObat FROM detail_periksa INNER JOIN periksa ON detail_periksa.id_periksa = periksa.id INNER JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id INNER JOIN pasien ON daftar_poli.id_pasien = pasien.id INNER JOIN obat ON detail_periksa.id_obat = obat.id INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id  WHERE dokter.id = '$id_dokter' AND pasien.id = '$idPasien' GROUP BY pasien.id,periksa.tgl_periksa";

                                                                                $results = mysqli_query($mysqli, $ambilData);
                                                                                while ($datas = mysqli_fetch_assoc($results)) {
                                                                                    # code...
                                                                                ?>
                                                                                    <tr>
                                                                                        <td><?php echo $nomor++; ?></td>
                                                                                        <td><?php echo $datas['tgl_periksa'] ?></td>
                                                                                        <td><?php echo $datas['namaPasien'] ?></td>
                                                                                        <td><?php echo $datas['nama'] ?></td>
                                                                                        <td style="white-space: pre-line;"><?php echo $datas['keluhan'] ?></td>
                                                                                        <td style="white-space: pre-line;"><?php echo $datas['namaObat'] ?></td>
                                                                                        <td><?php echo $datas['hargaObat'] ?></td>
                                                                                    </tr>
                                                                                <?php } ?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer justify-content-end">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                        <!-- /.modal-dialog -->
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
        <!-- /.control-sidebar -->

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