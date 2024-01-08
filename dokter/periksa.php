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
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="../plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">



        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="col-2">No Antrian</th>
                                            <th class="col-4">Nama Pasien</th>
                                            <th class="col-5">Keluhan</th>
                                            <th class="col-1">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        require '../koneksi.php';
                                        $query = "SELECT pasien.nama, daftar_poli.keluhan, daftar_poli.status_periksa, daftar_poli.id, daftar_poli.no_antrian FROM daftar_poli INNER JOIN pasien ON daftar_poli.id_pasien = pasien.id INNER JOIN jadwal_periksa ON daftar_poli.id_jadwal = jadwal_periksa.id INNER JOIN dokter ON jadwal_periksa.id_dokter = dokter.id WHERE dokter.id = '$id_dokter' ORDER BY daftar_poli.no_antrian ASC";
                                        $result = mysqli_query($mysqli, $query);

                                        while ($data = mysqli_fetch_assoc($result)) {
                                        ?>

                                            <tr>
                                                <td><?php echo $data['no_antrian']; ?></td>
                                                <td><?php echo $data['nama']; ?></td>
                                                <td><?php echo $data['keluhan']; ?></td>
                                                <td>
                                                    <?php if ($data['status_periksa'] == 1) {
                                                    ?>
                                                        <button type='button' class='btn btn-sm btn-warning edit-btn' data-toggle="modal" data-target="#editModal<?php echo $data['id'] ?>">Edit</button>
                                                        <a href='invoice.php?id=<?php echo $data['id'] ?>' class='btn btn-sm btn-secondary edit-btn'>Cetak</a>
                                                        <div class="modal fade" id="editModal<?php echo $data['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="addModalLabel">Edit Periksa Pasien
                                                                        </h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <?php
                                                                        $idDaftarPoli = $data['id'];
                                                                        require '../koneksi.php';
                                                                        $ambilDataPeriksa = mysqli_query($mysqli, "SELECT * FROM periksa INNER JOIN daftar_poli ON periksa.id_daftar_poli = daftar_poli.id WHERE daftar_poli.id = '$idDaftarPoli'");
                                                                        $ambilData = mysqli_fetch_assoc($ambilDataPeriksa);
                                                                        ?>
                                                                        <form action="index.php?page=editPeriksa" method="post">
                                                                            <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
                                                                            <div class="form-group">
                                                                                <label for="nama">Nama Pasien</label>
                                                                                <input type="text" class="form-control" id="nama" name="nama" required value="<?php echo $data['nama'] ?>" readonly>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="tanggal_periksa">Tanggal Periksa</label>
                                                                                <input type="datetime-local" class="form-control" id="tanggal_periksa" name="tanggal_periksa" required value="<?php echo $ambilData['tgl_periksa'] ?>">
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label for="catatan">Catatan</label>
                                                                                <textarea class="form-control" rows="3" id="catatan" name="catatan" required><?php echo $ambilData['catatan'] ?></textarea>
                                                                            </div>
                                                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php  } else { ?>
                                                        <button type='button' class='btn btn-sm btn-primary edit-btn' data-toggle="modal" data-target="#periksaModal<?php echo $data['id'] ?>">Periksa</button>
                                                        <div class="modal fade" id="periksaModal<?php echo $data['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="addModalLabel">Periksa Pasien</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        <form action="index.php?page=periksaPasien" method="post">
                                                                            <input type="hidden" name="id" value="<?php echo $data['id'] ?>">
                                                                            <div class="form-group">
                                                                                <label for="nama">Nama Pasien</label>
                                                                                <input type="text" class="form-control" id="nama" name="nama" required value="<?php echo $data['nama'] ?>" disabled>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="tanggal_periksa">Tanggal Periksa</label>
                                                                                <input type="datetime-local" class="form-control" id="tanggal_periksa" name="tanggal_periksa" required>
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label for="catatan">Catatan</label>
                                                                                <textarea class="form-control" rows="3" id="catatan" name="catatan" required></textarea>
                                                                            </div>
                                                                            <div class="form-group mb-3">
                                                                                <label>Obat</label>
                                                                                <br>
                                                                                <select class="select2" multiple="multiple" data-placeholder="Pilih Obat" style="width: 100%;" name="obat[]">
                                                                                    <?php
                                                                                    require '../koneksi.php';
                                                                                    $getObat = "SELECT * FROM obat";
                                                                                    $queryObat = mysqli_query($mysqli, $getObat);
                                                                                    while ($datas = mysqli_fetch_assoc($queryObat)) {
                                                                                    ?>
                                                                                        <option value="<?php echo $datas['id'] ?>">
                                                                                            <?php echo $datas['nama_obat'] ?></option>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                            <button type="submit" class="btn btn-primary">Periksa</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
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

        <!-- Main Footer -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../plugins/select2/js/select2.full.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../dist/js/adminlte.min.js"></script>
    <script>
        $(function() {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', {
                'placeholder': 'dd/mm/yyyy'
            })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', {
                'placeholder': 'mm/dd/yyyy'
            })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });

            //Date and time picker
            $('#reservationdatetime').datetimepicker({
                icons: {
                    time: 'far fa-clock'
                }
            });

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                    format: 'MM/DD/YYYY hh:mm A'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker({
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month').endOf('month')]
                    },
                    startDate: moment().subtract(29, 'days'),
                    endDate: moment()
                },
                function(start, end) {
                    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                        'MMMM D, YYYY'))
                }
            )

            //Timepicker
            $('#timepicker').datetimepicker({
                format: 'LT'
            })

            //Bootstrap Duallistbox
            $('.duallistbox').bootstrapDualListbox()

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            $('.my-colorpicker2').on('colorpickerChange', function(event) {
                $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
            })

            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })

        })
        // BS-Stepper Init
        document.addEventListener('DOMContentLoaded', function() {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
        })
    </script>
</body>

</html>