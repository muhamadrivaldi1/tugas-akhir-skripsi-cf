<?php
include '../assets/conn/config.php';
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'simpan') {
        $nama_gejala = $_POST['nama_gejala'];
        $nilai_gejala = $_POST['nilai_gejala'];

        mysqli_query($conn, "INSERT INTO tbl_gejala (nama_gejala, nilai_gejala)VALUES
        ('$nama_gejala',  '$nilai_gejala')");
        header("location:gejala.php");
    }
}

include 'header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0">Tambah Data Gejala</h5>
                </div>

                <div class="card-body">
                    <form action="gejala-simpan.php?aksi=simpan" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Gejala</label>
                            <input type="text" name="nama_gejala" class="form-control rounded-pill" placeholder="Masukkan Nama Gejala" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nilai Keyakinan</label>
                            <select name="nilai_gejala" class="form-select rounded-pill" required>
                                <option selected disabled>Pilih Tingkat Keyakinan</option>
                                <option value="1">Sangat Yakin (1)</option>
                                <option value="0.8">Yakin (0.8)</option>
                                <option value="0.6">Cukup Yakin (0.6)</option>
                                <option value="0.4">Kurang Yakin (0.4)</option>
                                <option value="0.2">Tidak Tahu (0.2)</option>
                                <option value="0">Tidak (0)</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="gejala.php" class="btn btn-secondary rounded-pill px-4">Batal</a>
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