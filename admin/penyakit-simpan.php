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

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0">Tambah Data Penyakit</h5>
                </div>

                <div class="card-body">
                    <form action="penyakit-simpan.php?aksi=simpan" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Nama Penyakit</label>
                            <input type="text" name="nama_penyakit" class="form-control rounded-pill"
                                   placeholder="Masukkan nama penyakit" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" class="form-control rounded-4" rows="3"
                                      placeholder="Deskripsi singkat penyakit" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Solusi / Penanganan</label>
                            <textarea name="solusi" class="form-control rounded-4" rows="3"
                                      placeholder="Cara penanganan" required></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="penyakit.php" class="btn btn-secondary rounded-pill px-4">Batal</a>
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
