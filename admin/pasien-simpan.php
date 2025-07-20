<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpan') {
    $nama_lengkap  = $_POST['nama_lengkap'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $umur          = $_POST['umur'];
    $username      = $_POST['username'];
    $password      = $_POST['password'];

    $query_admin = "INSERT INTO tbl_admin (nama_lengkap, username, password, level) 
                    VALUES ('$nama_lengkap', '$username', '$password', 'Pasien')";

    if (mysqli_query($conn, $query_admin)) {
        $id_admin_baru = mysqli_insert_id($conn);

        $query_pasien = "INSERT INTO tbl_pasien (nama_lengkap, jenis_kelamin, umur, id_admin) 
                        VALUES ('$nama_lengkap', '$jenis_kelamin', '$umur', '$id_admin_baru')";

        if (mysqli_query($conn, $query_pasien)) {
            header("location:pasien.php");
            exit;
        } else {
            echo "Error simpan pasien: " . mysqli_error($conn);
        }
    } else {
        echo "Error simpan admin: " . mysqli_error($conn);
    }
}

include 'header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0">Tambah Data Pasien</h5>
                </div>

                <div class="card-body">
                    <form action="pasien-simpan.php?aksi=simpan" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" class="form-control rounded-pill" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select rounded-pill" required>
                                <option selected disabled>Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Umur</label>
                            <input type="number" name="umur" class="form-control rounded-pill" placeholder="Masukkan umur" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control rounded-pill" placeholder="Masukkan Username" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control rounded-pill" placeholder="Masukkan Password" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="pasien.php" class="btn btn-secondary rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
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
