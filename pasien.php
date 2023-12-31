<?php
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_ktp = $_POST['no_ktp'];
    $no_hp = $_POST['no_hp'];
    $no_rm = $_POST['no_rm'];


    $query = "SELECT * FROM pasien WHERE nama = '$nama'";
    $result = $mysqli->query($query);

    if ($result === false) {
        die("Query error: " . $mysqli->error);
    }

    if ($result->num_rows == 0) {

        $insert_query = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES ('$nama', '$alamat', '$no_ktp', '$no_hp', '$no_rm')";
        if (mysqli_query($mysqli, $insert_query)) {
            echo "<script>
                alert('Pendaftaran Berhasil'); 
                document.location='index.php?page=pasien';
                </script>";
        } else {
            $error = "Pendaftaran gagal";
        }
    } else {
        $error = "Username sudah digunakan";
    }
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center" style="font-weight: bold; font-size: 32px;">Register</div>
                <div class="card-body">
                    <form method="POST" action="index.php?page=pasien">
                        <?php
                        if (isset($error)) {
                            echo '<div class="alert alert-danger">' . $error . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>';
                        }
                        ?>
                        <div class="form-group">
                            <label for="nama">Username</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="form-group">
                            <label for="no_ktp">No Ktp</label>
                            <input type="number" name="no_ktp" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="form-group">
                            <label for="no_hp">No Hp</label>
                            <input type="number" name="no_hp" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="form-group">
                            <label for="no_rm">No Rm</label>
                            <input type="number" name="no_rm" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>