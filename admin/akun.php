<?php
include '../assets/conn/config.php';
include 'header.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'ubah') {
    $id_admin = $_POST['id_admin'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    mysqli_query($conn, "UPDATE tbl_admin SET 
        nama_lengkap='$nama_lengkap', 
        username='$username', 
        password='$password', 
        level='$level' 
        WHERE id_admin='$id_admin'");

    header("location:index.php");
    exit;
}

$username = $_SESSION['username'];
$data = mysqli_query($conn, "SELECT * FROM tbl_admin WHERE username='$username'");
$a = mysqli_fetch_array($data);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h5 class="mb-0">Pengaturan Akun</h5>
                </div>
                <div class="card-body p-4">
                    <form action="akun.php?aksi=ubah" method="post">
                        <input type="hidden" name="id_admin" value="<?= htmlspecialchars($a['id_admin']) ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control rounded-pill" value="<?= htmlspecialchars($a['nama_lengkap']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control rounded-pill" value="<?= htmlspecialchars($a['username']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="text" name="password" class="form-control rounded-pill" value="<?= htmlspecialchars($a['password']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Level</label>
                            <select name="level" class="form-select rounded-pill" required>
                                <option value="Admin" <?= $a['level'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
                                <option value="Pasien" <?= $a['level'] == 'Pasien' ? 'selected' : '' ?>>Pasien</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill">Simpan Perubahan</button>
                            <a href="index.php" class="btn btn-outline-secondary rounded-pill">Kembali ke Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
