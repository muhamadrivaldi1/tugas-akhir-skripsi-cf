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

<div class="container">
    <div class="card shadow p-5 mb-5">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Ubah Data</h5>
        </div>
        <hr>

        <?php
        $id_gejala = $_GET['id_gejala'];
        $data = mysqli_query($conn, "SELECT * FROM tbl_gejala WHERE id_gejala='$id_gejala'");
        $a = mysqli_fetch_array($data);
        ?>

        <div class="card-body">
            <form action="gejala-ubah.php?aksi=ubah" method="POST">
                <input type="hidden" name="id_gejala" value="<?= $a['id_gejala'] ?>">

                <div class="form-group">
                    <label>Nama Gejala</label>
                    <input type="text" name="nama_gejala" class="form-control"
                        value="<?= $a['nama_gejala'] ?>" placeholder="Masukkan Nama Gejala" required>
                </div>

                <div class="form-group">
                    <label>Nilai Keyakinan</label>
                    <select name="nilai_gejala" class="form-control required">
                        <option value="1">Sangat Yakin (1)</option>
                        <option value="0.8">Yakin (0.8)</option>
                        <option value="0.6">Cukup Yakin (0.6)</option>
                        <option value="0.4">Kuarang Yakin (0.4)</option>
                        <option value="0.2">Tidak tahu (0.2)</option>
                        <option value="0.">Tidak (0)</option>
                    </select>
                </div>
                <a href="gejala.php" class="btn btn-secondary mb-2">Batal</a>
                <input type="submit" value="Ubah" class="btn btn-primary mb-2">
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>