<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpan') {
    $nama_lengkap  = $_POST['nama_lengkap'];
    $username      = $_POST['username'];
    $password      = $_POST['password'];

    $query_admin = "INSERT INTO tbl_admin (nama_lengkap, username, password, level) 
                    VALUES ('$nama_lengkap', '$username', '$password', 'Admin')";

    if (mysqli_query($conn, $query_admin)) {
        header("location:admin.php");
        exit;
    } else {
        echo "Gagal menyimpan data admin: " . mysqli_error($conn);
    }
}

include 'header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0"><i class="fas fa-user-plus me-2"></i>Tambah Data Petugas</h5>
                </div>

                <div class="card-body">
                    <form action="admin-simpan.php?aksi=simpan" method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control rounded-pill" placeholder="Masukkan nama lengkap" autocomplete="off" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control rounded-pill" placeholder="Masukkan username" autocomplete="new-username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control rounded-pill" placeholder="Masukkan password" autocomplete="new-password" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="admin.php" class="btn btn-secondary rounded-pill px-4">
                                <i class="fas fa-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-save me-1"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>