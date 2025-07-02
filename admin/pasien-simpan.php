<?php
include '../assets/conn/config.php';
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'simpan') {
        $nama_lengkap = $_POST['nama_lengkap'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $umur = $_POST['umur'];

        // Ambil id_admin dari session
        $username = $_SESSION['username'];
        $data = mysqli_query($conn, "SELECT id_admin FROM tbl_admin WHERE username='$username'");
        if ($data) {
            $admin = mysqli_fetch_array($data);
            $id_admin = $admin['id_admin'];
        } else {
            die("Query error: " . mysqli_error($conn));
        }

        // Masukkan data ke tbl_admin (jika diperlukan)
        $query_admin = "INSERT INTO tbl_admin (username, nama_lengkap) VALUES ('$username', '$nama_lengkap')";
        if (!mysqli_query($conn, $query_admin)) {
            die("Query error: " . mysqli_error($conn));
        }

        // Masukkan data ke tbl_pasien
        $query_pasien = "INSERT INTO tbl_pasien (nama_lengkap, jenis_kelamin, id_admin, umur) VALUES ('$nama_lengkap', '$jenis_kelamin', '$id_admin', '$umur')";
        if (!mysqli_query($conn, $query_pasien)) {
            die("Query error: " . mysqli_error($conn));
        }

        header("location:pasien.php");
    }
}

include 'header.php';
?>

<div class="container">
    <div class="card shadow p-5 mb-5">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Tambah Data</h5>
        </div>

        <div class="card-body">
            <?php
            $username = $_SESSION['username'];
            $data = mysqli_query($conn, "SELECT * FROM tbl_admin WHERE username='$username'");

            if ($data) {
                $a = mysqli_fetch_array($data);
            } else {
                die("Query error: " . mysqli_error($conn));
            }
            ?>

            <form action="pasien-simpan.php?aksi=simpan" method="POST">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap"
                        required>
                </div>
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control required">
                        <option selected disabled>Pilih Jenis Kelamin Anda</option>
                        <option value="Laki-Laki"> Laki-Laki</option>
                        <option value="perempuan"> Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Umur</label>
                    <input type="number" name="umur" class="form-control" placeholder="Masukkan umur" min="1" required>
                </div>
                <a href="pasien.php" class="btn btn-secondary mb-2">Batal</a>
                <input type="submit" value="Simpan" class="btn btn-primary mb-2">
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>