<?php
include '../assets/conn/config.php';
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        mysqli_query($conn, "DELETE FROM tbl_penyakit WHERE id_penyakit='$_GET[id_penyakit]'");
        header("location:penyakit.php");
    }
}

include 'header.php';
?>

<div class="container">
    <div class=" card shadow p-5 mb-5">
        <div class="card-body">
            <h5 class="font-wight-bold text-primary">Penyakit</h5>
        </div>

        <div class="card-body">
            <a href="penyakit-simpan.php" class="btn btn-primary"><span class="fa fa-plus">
                </span>&nbsp; Tambah Data</a>
            <br>
            <br>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Nama penyakit</th>
                        <th class="text-center">Keterangan</th>
                        <th class="text-center">Solusi</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                    <?php
                    $data = mysqli_query($conn, "SELECT * FROM tbl_penyakit ORDER BY id_penyakit");
                    $no = 1;
                    while ($a = mysqli_fetch_array($data)) {
                    ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td class="text-center"><?= $a['nama_penyakit'] ?></td>
                            <td class="text-center"><?= $a['keterangan'] ?></td>
                            <td class="text-center"><?= $a['solusi'] ?></td>
                            <td class="text-center">
                                <a href="penyakit-ubah.php?id_penyakit=<?= $a['id_penyakit'] ?>" class="btn btn-secondary">
                                    <span class="fa fa-pen"></span>
                                </a>
                                <a href="penyakit.php?id_penyakit=<?= $a['id_penyakit'] ?>&aksi=hapus" class="btn btn-danger">
                                    <span class="fa fa-trash"></span>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>
