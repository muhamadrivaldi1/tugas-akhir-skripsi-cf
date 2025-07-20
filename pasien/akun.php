<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'ubah') {
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

    // Update tbl_pasien
    mysqli_query($conn, "UPDATE tbl_pasien SET nama_lengkap='$nama_lengkap', jenis_kelamin='$jenis_kelamin', umur='$umur' WHERE id_admin='$id_admin'");

    header("location:index.php");
    exit;
}

include 'header.php';

$username = $_SESSION['username'];
$data = mysqli_query($conn, "SELECT * FROM tbl_admin WHERE username='$username'");
$a = mysqli_fetch_array($data);
$aa = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_pasien WHERE id_admin='{$a['id_admin']}'"));
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h5 class="mb-0">Pengaturan Akun Pasien</h5>
                </div>
                <div class="card-body p-4">
                    <form action="akun.php?aksi=ubah" method="post">
                        <input type="hidden" name="id_admin" value="<?= htmlspecialchars($a['id_admin']) ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control rounded-pill"
                                value="<?= htmlspecialchars($a['nama_lengkap']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki" value="Laki-Laki"
                                    <?= isset($aa['jenis_kelamin']) && $aa['jenis_kelamin'] == 'Laki-Laki' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="laki">
                                    Laki-Laki
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan"
                                    <?= isset($aa['jenis_kelamin']) && $aa['jenis_kelamin'] == 'Perempuan' ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="perempuan">
                                    Perempuan
                                </label>
                            </div>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Umur</label>
                            <input type="number" name="umur" class="form-control rounded-pill" min="1"
                                value="<?= $aa['umur'] ?? '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control rounded-pill"
                                value="<?= htmlspecialchars($a['username']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control rounded-pill"
                                placeholder="Kosongkan jika tidak ingin mengubah password">
                            <div class="form-text">Kosongkan jika tidak ingin mengubah password.</div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary rounded-pill">Simpan Perubahan</button>
                            <a href="index.php" class="btn btn-outline-secondary rounded-pill">Kembali ke Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>