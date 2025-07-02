<?php
include '../assets/conn/config.php';
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'ubah') {
        $id_admin = $_POST['id_admin'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $level = $_POST['level']; // Tambahkan role

        mysqli_query($conn, "UPDATE tbl_admin SET nama_lengkap='$nama_lengkap', 
        username='$username', password='$password', level='$level' WHERE id_admin='$id_admin'");
        header("location:index.php");
    }
}

include 'header.php';
?>

<div class="container">
    <div class="card shadow p-5 mb-5">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Akun Admin</h5>
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

            <form action="akun.php?aksi=ubah" method="post">
                <input type="hidden" name="id_admin" class="form-control" value="<?= $a['id_admin'] ?>">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= $a['nama_lengkap'] ?>">
                </div>
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" value="<?= $a['username'] ?>">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="text" name="password" class="form-control" value="<?= $a['password'] ?>">
                </div>

                <div class="form-group">
                    <select name="level" class="form-control">
                        <option selected disabled>Pilih level</option>
                        <option value="Admin" <?= $a['level'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="Pasien" <?= $a['level'] == 'Pasien' ? 'selected' : '' ?>>Pasien</option>
                    </select>
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