<?php
include '../assets/conn/config.php';
// ===================== SIMPAN PENYAKIT =====================
if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpan') {
    $nama_penyakit = $_POST['nama_penyakit'];
    $keterangan    = $_POST['keterangan'];
    $solusi        = $_POST['solusi'];

    mysqli_query($conn, "INSERT INTO tbl_penyakit (nama_penyakit, keterangan, solusi)
                         VALUES ('$nama_penyakit', '$keterangan', '$solusi')");
    header('location:penyakit.php');
    exit;
}

include 'header.php';
?>

<div class="container">
    <div class="card shadow p-5 mb-5">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Tambah Data Penyakit</h5>
        </div>

        <div class="card-body">
            <form action="penyakit-simpan.php?aksi=simpan" method="POST">
                <div class="form-group">
                    <label>Nama Penyakit</label>
                    <input type="text" name="nama_penyakit" class="form-control" placeholder="Masukkan nama penyakit" required>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Deskripsi singkat penyakit" required></textarea>
                </div>

                <div class="form-group">
                    <label>Solusi / Penanganan</label>
                    <textarea name="solusi" class="form-control" rows="3" placeholder="Cara penanganan" required></textarea>
                </div>

                <a href="penyakit.php" class="btn btn-secondary mb-2">Batal</a>
                <input type="submit" value="Simpan" class="btn btn-primary mb-2">
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>