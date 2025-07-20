<?php
include '../assets/conn/config.php';

// ===================== SIMPAN ATURAN =====================
if (isset($_GET['aksi']) && $_GET['aksi'] == 'simpan') {
    $id_penyakit = $_POST['id_penyakit'];
    $id_gejala   = $_POST['id_gejala'];

    mysqli_query($conn, "INSERT INTO tbl_aturan (id_penyakit, id_gejala)
                         VALUES ('$id_penyakit', '$id_gejala')");
    header('location:aturan.php');
    exit;
}

include 'header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0">Tambah Data Aturan (Certainty Factor)</h5>
                </div>
                <div class="card-body">
                    <form action="aturan-simpan.php?aksi=simpan" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Pilih Penyakit</label>
                            <select name="id_penyakit" class="form-select rounded-pill" required>
                                <option selected disabled>-- Pilih Penyakit --</option>
                                <?php
                                $penyakit = mysqli_query($conn, "SELECT * FROM tbl_penyakit ORDER BY nama_penyakit");
                                while ($p = mysqli_fetch_array($penyakit)) {
                                    echo "<option value='{$p['id_penyakit']}'>{$p['nama_penyakit']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pilih Gejala</label>
                            <select name="id_gejala" class="form-select rounded-pill" required>
                                <option selected disabled>-- Pilih Gejala --</option>
                                <?php
                                $gejala = mysqli_query($conn, "SELECT * FROM tbl_gejala ORDER BY nama_gejala");
                                while ($g = mysqli_fetch_array($gejala)) {
                                    echo "<option value='{$g['id_gejala']}'>{$g['nama_gejala']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="aturan.php" class="btn btn-secondary rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
