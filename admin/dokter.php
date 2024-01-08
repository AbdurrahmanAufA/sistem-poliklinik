<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['simpan'])) {
    if (isset($_POST['id'])) {
        $ubah = mysqli_query($mysqli, "UPDATE dokter SET 
            nama = '" . $_POST['nama'] . "',
            alamat = '" . $_POST['alamat'] . "',
            no_hp = '" . $_POST['no_hp'] . "',
            id_poli = '" . $_POST['id_poli'] . "'
            WHERE
            id = '" . $_POST['id'] . "'");
    } else {
        $tambah = mysqli_query($mysqli, "INSERT INTO dokter (nama, alamat, no_hp, id_poli, password) 
            VALUES (
                '" . $_POST['nama'] . "',
                '" . $_POST['alamat'] . "',
                '" . $_POST['no_hp'] . "',
                '" . $_POST['id_poli'] . "',
                '" . password_hash($_POST['nama'], PASSWORD_DEFAULT) . "'
            )");
    }
    echo "<script> 
            document.location='index.php?page=dokter';
        </script>";
}
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        $hapus = mysqli_query($mysqli, "DELETE FROM dokter WHERE id = '" . $_GET['id'] . "'");
    }

    echo "<script> 
            document.location='index.php?page=dokter';
        </script>";
}
?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Quick Example</h3>
    </div>
    <form class="form row" method="POST" action="" name="myForm" onsubmit="return(validate());">
        <?php
        $nama = '';
        $alamat = '';
        $no_hp = '';
        $id_poli = '';
        if (isset($_GET['id'])) {
            $ambil = mysqli_query($mysqli, "SELECT * FROM dokter 
                    WHERE id='" . $_GET['id'] . "'");
            while ($row = mysqli_fetch_array($ambil)) {
                $nama = $row['nama'];
                $alamat = $row['alamat'];
                $no_hp = $row['no_hp'];
                $id_poli = $row['id_poli'];
            }
        ?>
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
        <?php
        }
        ?>
        <div class="card-body">
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
                <label for="id_poli">Poli Dokter</label>
                <div>
                    <select class="form-control" name="id_poli" placeholder="Pilih poli anda" style="width: 100%;">
                        <option selected>Pilih Poli...</option>
                        <?php
                        $result = mysqli_query($mysqli, "SELECT * FROM poli");

                        while ($data = mysqli_fetch_assoc($result)) {
                            $selected = ($data['id'] == $id_poli) ? 'selected' : '';
                            echo "<option $selected value='" . $data['id'] . "'>" . $data['nama_poli'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
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
                <th scope="col">#</th>
                <th scope="col">Nama</th>
                <th scope="col">Alamat</th>
                <th scope="col">No Hp</th>
                <th scope="col">Id Poli</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <!--tbody berisi isi tabel sesuai dengan judul atau head-->
        <tbody>
            <!-- Kode PHP untuk menampilkan semua isi dari tabel urut-->
            <?php
            $result = mysqli_query($mysqli, "SELECT * FROM dokter");
            $no = 1;
            while ($data = mysqli_fetch_array($result)) {
            ?>
                <tr>
                    <th scope="row"><?php echo $no++ ?></th>
                    <td><?php echo $data['nama'] ?></td>
                    <td><?php echo $data['alamat'] ?></td>
                    <td><?php echo $data['no_hp'] ?></td>
                    <td><?php echo $data['id_poli'] ?></td>
                    <td>
                        <a class="btn btn-success rounded-pill px-3" href="index.php?page=dokter&id=<?php echo $data['id'] ?>">Ubah</a>
                        <a class="btn btn-danger rounded-pill px-3" href="index.php?page=dokter&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>