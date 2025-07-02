<?php
include '../assets/conn/config.php';
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'ubah') {
        $id_pasien = $_POST['id_pasien'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $umur = $_POST['umur'];

        mysqli_query($conn, "UPDATE tbl_pasien SET 
        nama_lengkap='$nama_lengkap', 
        jenis_kelamin='$jenis_kelamin', 
        umur='$umur' 
        WHERE id_pasien='$id_pasien'");

        header("location:pasien.php");
    }
}

include 'header.php';
?>

<div class="container">
    <div class="card shadow p-5 mb-5">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Ubah Data</h5>
        </div>

        <div class="card-body">
            <?php
            $data = mysqli_query($conn, "SELECT * FROM tbl_pasien WHERE id_pasien='$_GET[id_pasien]'");
            $a = mysqli_fetch_array($data); ?>

            <form action="pasien-ubah.php?aksi=ubah" method="POST">
                <input type="hidden" name="id_pasien" value="<?= $a['id_pasien'] ?>">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control"
                        value="<?= isset($a['nama_lengkap']) ? $a['nama_lengkap'] : '' ?>"
                        placeholder="Masukkan Nama Lengkap" required>
                </div>


                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control required">
                        <option selected>Pilih Jenis Kelamin Anda</option>
                        <option> Laki-Laki</option>
                        <option> Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Umur</label>
                    <input type="number" name="umur" class="form-control"
                        value="<?= isset($a['umur']) ? $a['umur'] : '' ?>" placeholder="Masukkan umur" min="1" required>
                </div>

                <br>
                <a href="pasien.php" class="btn btn-secondary mb-2">Batal</a>
                <input type="submit" value="Ubah" class="btn btn-primary mb-2">
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>