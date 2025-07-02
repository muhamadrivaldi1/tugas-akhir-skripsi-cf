<?php
include '../assets/conn/config.php';

// ==================== PROSES UPDATE ====================
if (isset($_GET['aksi']) && $_GET['aksi'] === 'ubah') {
    $id_penyakit   = $_POST['id_penyakit'];
    $nama_penyakit = $_POST['nama_penyakit'];
    $keterangan    = $_POST['keterangan'];
    $solusi        = $_POST['solusi'];

    mysqli_query($conn, "UPDATE tbl_penyakit SET 
                        nama_penyakit='$nama_penyakit',
                        keterangan   ='$keterangan',
                        solusi       ='$solusi'
                        WHERE id_penyakit='$id_penyakit'");

    header('location:penyakit.php');
    exit;
}

include 'header.php';

// ===================== AMBIL DATA ======================
$data = mysqli_query($conn, "SELECT * FROM tbl_penyakit WHERE id_penyakit='".$_GET['id_penyakit']."'");
$a    = mysqli_fetch_array($data);
?>

<div class="container">
    <div class="card shadow p-5 mb-5">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Ubah Data Penyakit</h5>
        </div>
        <div class="card-body">
            <form action="penyakit-ubah.php?aksi=ubah" method="POST">
                <input type="hidden" name="id_penyakit" value="<?= $a['id_penyakit'] ?>">

                <div class="form-group">
                    <label>Nama Penyakit</label>
                    <input type="text" name="nama_penyakit" class="form-control" value="<?= htmlspecialchars($a['nama_penyakit']) ?>" placeholder="Masukkan Nama Penyakit" required>
                </div>

                <div class="form-group">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Deskripsi singkat" required><?= htmlspecialchars($a['keterangan']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Solusi / Penanganan</label>
                    <textarea name="solusi" class="form-control" rows="3" placeholder="Cara penanganan" required><?= htmlspecialchars($a['solusi']) ?></textarea>
                </div>

                <a href="penyakit.php" class="btn btn-secondary mb-2">Batal</a>
                <button type="submit" class="btn btn-primary mb-2">Ubah</button>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
