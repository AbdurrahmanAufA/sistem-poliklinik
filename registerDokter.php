<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    // Jika pengguna sudah login, tampilkan tombol "Logout"
    header("Location: index.php?page=loginUser");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];
    $id_poli = $_POST['id_poli'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        $query = "SELECT * FROM dokter WHERE nama = '$nama'";
        $result = $mysqli->query($query);

        if ($result === false) {
            die("Query error: " . $mysqli->error);
        }

        if ($result->num_rows == 0) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $insert_query = "INSERT INTO dokter (nip, password, nama, alamat, no_hp, id_poli) VALUES ('$nip', '$hashed_password', '$nama', '$alamat', '$no_hp', '$id_poli')";
            if (mysqli_query($mysqli, $insert_query)) {
                echo "<script>
                alert('Pendaftaran Berhasil'); 
                document.location='index.php?page=loginDokter';
                </script>";
            } else {
                $error = "Pendaftaran gagal";
            }
        } else {
            $error = "Username sudah digunakan";
        }
    } else {
        $error = "Password tidak cocok";
    }
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center" style="font-weight: bold; font-size: 32px;">Register</div>
                <div class="card-body">
                    <form method="POST" action="index.php?page=registerDokter">
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
                            <label for="nip">Nip</label>
                            <input type="text" name="nip" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="form-group">
                            <label for="nama">Username</label>
                            <input type="text" name="nama" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="form-group">
                            <label for="no_hp">No Hp</label>
                            <input type="number" name="no_hp" class="form-control" required placeholder="Masukkan nama anda">
                        </div>
                        <div class="form-group">
                            <?php
                            $result = mysqli_query($mysqli, "SELECT * FROM poli");
                            while ($data = mysqli_fetch_array($result)) {
                            ?>
                                <label for="id_poli">Poli</label>
                                <select class="form-control" name="id_poli" placeholder="Pilih poli anda" style="width: 100%;">
                                    <option value="<?php echo $data['id'] ?>"><?php echo $data['nama_poli'] ?></option>
                                </select>
                            <?php
                            }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required placeholder="Masukkan password konfirmasi">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </form>
                    <div class="text-center">
                        <p class="mt-3">Sudah Punya Akun? <a href="index.php?page=loginDokter">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>