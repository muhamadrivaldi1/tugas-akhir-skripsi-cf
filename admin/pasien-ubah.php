<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'ubah') {
    $id_pasien = $_POST['id_pasien'];
    $id_admin = $_POST['id_admin'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $umur = $_POST['umur'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Update tbl_pasien
    mysqli_query($conn, "UPDATE tbl_pasien SET 
        nama_lengkap='$nama_lengkap', 
        jenis_kelamin='$jenis_kelamin', 
        umur='$umur' 
        WHERE id_pasien='$id_pasien'");

    // Update tbl_admin dengan pengecekan level
    mysqli_query($conn, "UPDATE tbl_admin SET 
        username='$username', 
        password='$password' 
        WHERE id_admin='$id_admin' AND level='Pasien'");

    header("location:pasien.php");
    exit;
}

include 'header.php';

// Ambil data pasien dan admin berdasarkan id_pasien
$id_pasien = $_GET['id_pasien'];
$data = mysqli_query($conn, "SELECT p.*, a.username, a.password, a.level, a.id_admin 
    FROM tbl_pasien p 
    JOIN tbl_admin a ON p.id_admin = a.id_admin 
    WHERE p.id_pasien='$id_pasien' AND a.level = 'Pasien'");

if (!$data || mysqli_num_rows($data) == 0) {
    die("Data tidak ditemukan atau bukan level pasien.");
}

$a = mysqli_fetch_array($data);
?>

<div class="container my-4">
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h5 class="m-0">Ubah Data Pasien</h5>
        </div>

        <div class="card-body">
            <form action="pasien-ubah.php?aksi=ubah" method="POST" class="row g-3">
                <input type="hidden" name="id_pasien" value="<?= $a['id_pasien'] ?>">
                <input type="hidden" name="id_admin" value="<?= $a['id_admin'] ?>">

                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control rounded-pill"
                        value="<?= htmlspecialchars($a['nama_lengkap']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select rounded-pill" required>
                        <option value="Laki-Laki" <?= $a['jenis_kelamin'] == 'Laki-Laki' ? 'selected' : '' ?>>Laki-Laki</option>
                        <option value="Perempuan" <?= $a['jenis_kelamin'] == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Umur</label>
                    <input type="number" name="umur" class="form-control rounded-pill"
                        value="<?= htmlspecialchars($a['umur']) ?>" min="1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control rounded-pill"
                        value="<?= htmlspecialchars($a['username']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="text" name="password" class="form-control rounded-pill"
                        value="<?= htmlspecialchars($a['password']) ?>" required>
                </div>

                <div class="col-12 d-flex gap-2 mt-3">
                    <a href="pasien.php" class="btn btn-secondary d-flex align-items-center gap-2 px-3 rounded-pill">
                        <i class="fas fa-arrow-left"></i> <span>Batal</span>
                    </a>

                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2 px-3 rounded-pill">
                        <i class="fas fa-save"></i> <span>Ubah</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
include 'footer.php';
?>