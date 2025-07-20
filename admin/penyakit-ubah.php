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
$data = mysqli_query($conn, "SELECT * FROM tbl_penyakit WHERE id_penyakit='" . $_GET['id_penyakit'] . "'");
$a    = mysqli_fetch_array($data);
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0">Ubah Data Penyakit</h5>
                </div>
                <div class="card-body">
                    <form action="penyakit-ubah.php?aksi=ubah" method="POST">
                        <input type="hidden" name="id_penyakit" value="<?= $a['id_penyakit'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Penyakit</label>
                            <input type="text" name="nama_penyakit" class="form-control rounded-pill"
                                value="<?= htmlspecialchars($a['nama_penyakit']) ?>"
                                placeholder="Masukkan Nama Penyakit" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control rounded-4" rows="3"
                                placeholder="Deskripsi singkat" required><?= htmlspecialchars($a['keterangan']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Solusi / Penanganan</label>
                            <textarea name="solusi" class="form-control rounded-4" rows="3"
                                placeholder="Cara penanganan" required><?= htmlspecialchars($a['solusi']) ?></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="penyakit.php" class="btn btn-secondary rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Ubah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>