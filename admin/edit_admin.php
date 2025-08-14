<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'ubah') {
    $id_admin = $_POST['id_admin'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Update tbl_admin dengan pengecekan level = 'admin'
    mysqli_query($conn, "UPDATE tbl_admin SET 
        nama_lengkap='$nama_lengkap', 
        username='$username', 
        password='$password' 
        WHERE id_admin='$id_admin' AND level='admin'");

    header("location:admin.php");
    exit;
}

include 'header.php';

// Ambil data admin berdasarkan id
$id_admin = $_GET['id'];
$data = mysqli_query($conn, "SELECT * FROM tbl_admin 
    WHERE id_admin='$id_admin' AND level='admin'");

if (!$data || mysqli_num_rows($data) == 0) {
    die("Data tidak ditemukan atau bukan level admin.");
}

$a = mysqli_fetch_array($data);
?>

<div class="container my-4">
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h5 class="m-0"><i class="fas fa-user-edit me-2"></i>Ubah Data Petugas</h5>
        </div>

        <div class="card-body">
            <form action="edit_admin.php?aksi=ubah" method="POST" class="row g-3">
                <input type="hidden" name="id_admin" value="<?= $a['id_admin'] ?>">

                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control rounded-pill"
                        value="<?= htmlspecialchars($a['nama_lengkap']) ?>" required>
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
                    <a href="admin.php" class="btn btn-secondary d-flex align-items-center gap-2 px-3 rounded-pill">
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

<?php include 'footer.php'; ?>