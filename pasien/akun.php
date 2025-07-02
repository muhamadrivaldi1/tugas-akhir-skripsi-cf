<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'ubah') {
        $id_admin = $_POST['id_admin'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $umur = $_POST['umur'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Update tbl_admin
        if (!empty($password)) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "UPDATE tbl_admin SET nama_lengkap='$nama_lengkap', username='$username', password='$password_hashed' WHERE id_admin='$id_admin'");
        } else {
            mysqli_query($conn, "UPDATE tbl_admin SET nama_lengkap='$nama_lengkap', username='$username' WHERE id_admin='$id_admin'");
        }

        // Update tbl_pasien (diluar if/else agar selalu jalan)
        if (mysqli_query($conn, "UPDATE tbl_pasien SET nama_lengkap='$nama_lengkap', jenis_kelamin='$jenis_kelamin', umur='$umur' WHERE id_admin='$id_admin'") === false) {
            die("Error updating tbl_pasien: " . mysqli_error($conn));
        }

        header("location:index.php");
        exit;
    }
}

include 'header.php';
?>

<div class="container">
    <div class="card shadow p-5 mb-5">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Akun Pasien</h5>
        </div>

        <div class="card-body">
            <?php
            $username = $_SESSION['username'];
            $data = mysqli_query($conn, "SELECT * FROM tbl_admin WHERE username='$username'");
            $a = mysqli_fetch_array($data); // Fetch data for admin

            if ($a) {
                $nama_lengkap = mysqli_real_escape_string($conn, $a['nama_lengkap']);
                $dat = mysqli_query($conn, "SELECT * FROM tbl_pasien WHERE id_admin='{$a['id_admin']}'");
                $aa = mysqli_fetch_array($dat);
            } else {
                $a = [];
                $aa = [];
            }
            ?>
            <form action="akun.php?aksi=ubah" method="post">
                <input type="hidden" name="id_admin" class="form-control" value="<?= isset($a['id_admin']) ? $a['id_admin'] : '' ?>">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control"
                        value="<?= isset($a['nama_lengkap']) ? htmlspecialchars($a['nama_lengkap'], ENT_QUOTES, 'UTF-8') : '' ?>">
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option selected disabled>
                            <?= isset($aa['jenis_kelamin']) ? $aa['jenis_kelamin'] : 'Pilih Jenis Kelamin' ?>
                        </option>
                        <option value="Laki-Laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Umur</label>
                    <input type="number" name="umur" class="form-control" min="1"
                        value="<?= isset($aa['umur']) && $aa['umur'] > 0 ? $aa['umur'] : '' ?>">
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control"
                        value="<?= isset($a['username']) ? htmlspecialchars($a['username'], ENT_QUOTES, 'UTF-8') : '' ?>" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
                    <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                </div>

                <a href="index.php" class="btn btn-secondary">Batal</a>
                <input type="submit" value="Ubah" class="btn btn-primary">
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>