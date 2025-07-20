<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'ubah') {
    $id_gejala = $_POST['id_gejala'];
    $nama_gejala = $_POST['nama_gejala'];
    $nilai_gejala = $_POST['nilai_gejala'];

    $query = "UPDATE tbl_gejala SET 
              nama_gejala='$nama_gejala', 
              nilai_gejala='$nilai_gejala' 
              WHERE id_gejala='$id_gejala'";

    if (mysqli_query($conn, $query)) {
        header("location:gejala.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

include 'header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0">Ubah Data Gejala</h5>
                </div>

                <?php
                $id_gejala = $_GET['id_gejala'] ?? '';
                $data = mysqli_query($conn, "SELECT * FROM tbl_gejala WHERE id_gejala='$id_gejala'");
                $a = mysqli_fetch_array($data);
                ?>

                <div class="card-body">
                    <form action="gejala-ubah.php?aksi=ubah" method="POST">
                        <input type="hidden" name="id_gejala" value="<?= $a['id_gejala'] ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Gejala</label>
                            <input type="text" name="nama_gejala" class="form-control rounded-pill"
                                value="<?= htmlspecialchars($a['nama_gejala']) ?>" placeholder="Masukkan Nama Gejala" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nilai Keyakinan</label>
                            <select name="nilai_gejala" class="form-select rounded-pill" required>
                                <option value="1" <?= $a['nilai_gejala'] == 1 ? 'selected' : '' ?>>Sangat Yakin (1)</option>
                                <option value="0.8" <?= $a['nilai_gejala'] == 0.8 ? 'selected' : '' ?>>Yakin (0.8)</option>
                                <option value="0.6" <?= $a['nilai_gejala'] == 0.6 ? 'selected' : '' ?>>Cukup Yakin (0.6)</option>
                                <option value="0.4" <?= $a['nilai_gejala'] == 0.4 ? 'selected' : '' ?>>Kurang Yakin (0.4)</option>
                                <option value="0.2" <?= $a['nilai_gejala'] == 0.2 ? 'selected' : '' ?>>Tidak Tahu (0.2)</option>
                                <option value="0" <?= $a['nilai_gejala'] == 0 ? 'selected' : '' ?>>Tidak (0)</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="gejala.php" class="btn btn-secondary rounded-pill px-4">Batal</a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">Ubah</button>
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