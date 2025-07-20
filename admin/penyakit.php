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

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0">Data Penyakit</h5>
                </div>

                <div class="card-body">
                    <a href="penyakit-simpan.php" class="btn btn-primary rounded-pill mb-3 px-4">
                        <i class="fa fa-plus"></i> Tambah Data
                    </a>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th>No</th>
                                    <th>Nama Penyakit</th>
                                    <th>Keterangan</th>
                                    <th>Solusi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $data = mysqli_query($conn, "SELECT * FROM tbl_penyakit ORDER BY id_penyakit");
                                $no = 1;
                                while ($a = mysqli_fetch_array($data)) {
                                ?>
                                    <tr class="text-center">
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($a['nama_penyakit']) ?></td>
                                        <td><?= htmlspecialchars($a['keterangan']) ?></td>
                                        <td><?= htmlspecialchars($a['solusi']) ?></td>
                                        <td>
                                            <a href="penyakit-ubah.php?id_penyakit=<?= $a['id_penyakit'] ?>"
                                               class="btn btn-outline-primary rounded-pill btn-sm d-flex align-items-center gap-2 px-3">
                                                    <i class="fa-solid fa-pen"></i> <span>Ubah</span>
                                            </a>

                                            <a href="penyakit.php?id_penyakit=<?= $a['id_penyakit'] ?>&aksi=hapus"
                                                 class="btn btn-outline-danger rounded-pill btn-sm d-flex align-items-center gap-2 px-3"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fa-solid fa-trash"></i> <span>Hapus</span>
                                            </a>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <?php
                        if (mysqli_num_rows($data) == 0) {
                            echo '<div class="alert alert-info text-center">Belum ada data penyakit.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>