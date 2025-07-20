<?php
include '../assets/conn/config.php';

// ==================== PROSES UPDATE ATURAN ====================
if (isset($_GET['aksi']) && $_GET['aksi'] === 'ubah') {
    $id_aturan   = $_POST['id_aturan'];
    $id_penyakit = $_POST['id_penyakit'];
    $id_gejala   = $_POST['id_gejala'];

    mysqli_query($conn, "UPDATE tbl_aturan SET 
                        id_penyakit = '$id_penyakit',
                        id_gejala   = '$id_gejala'
                        WHERE id_aturan = '$id_aturan'");

    header('location:aturan.php');
    exit;
}

include 'header.php';

// ===================== AMBIL DATA ATURAN ======================
$id_aturan = $_GET['id_aturan'] ?? '';

if (!$id_aturan) {
    echo "<div class='alert alert-danger container mt-4'>ID aturan tidak ditemukan.</div>";
    include 'footer.php';
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM tbl_aturan WHERE id_aturan = '$id_aturan'");
$a    = mysqli_fetch_array($data);
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0">Ubah Data Aturan (Certainty Factor)</h5>
                </div>
                <div class="card-body">
                    <form action="aturan-ubah.php?aksi=ubah" method="POST">
                        <input type="hidden" name="id_aturan" value="<?= $a['id_aturan'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Pilih Penyakit</label>
                            <select name="id_penyakit" class="form-select rounded-pill" required>
                                <option disabled>-- Pilih Penyakit --</option>
                                <?php
                                $penyakit = mysqli_query($conn, "SELECT * FROM tbl_penyakit ORDER BY nama_penyakit");
                                while ($p = mysqli_fetch_array($penyakit)) {
                                    $selected = $p['id_penyakit'] == $a['id_penyakit'] ? 'selected' : '';
                                    echo "<option value='{$p['id_penyakit']}' $selected>{$p['nama_penyakit']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pilih Gejala</label>
                            <select name="id_gejala" class="form-select rounded-pill" required>
                                <option disabled>-- Pilih Gejala --</option>
                                <?php
                                $gejala = mysqli_query($conn, "SELECT * FROM tbl_gejala ORDER BY nama_gejala");
                                while ($g = mysqli_fetch_array($gejala)) {
                                    $selected = $g['id_gejala'] == $a['id_gejala'] ? 'selected' : '';
                                    echo "<option value='{$g['id_gejala']}' $selected>{$g['nama_gejala']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="aturan.php" class="btn btn-secondary rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Ubah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
