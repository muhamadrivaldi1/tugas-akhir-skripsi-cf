<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    mysqli_query($conn, "DELETE FROM tbl_aturan WHERE id_aturan='$_GET[id_aturan]'");
    header("location:aturan.php");
    exit;
}

include 'header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="m-0">Aturan (Certainty Factor)</h5>
                </div>
                <div class="card-body">
                    <a href="aturan-simpan.php" class="btn btn-primary rounded-pill px-4 mb-3">
                        <i class="fa fa-plus"></i>&nbsp; Tambah Data
                    </a>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Penyakit</th>
                                    <th class="text-center">Nama Gejala</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $data = mysqli_query($conn, "
                                SELECT a.id_aturan, p.nama_penyakit, g.nama_gejala
                                FROM tbl_aturan a
                                INNER JOIN tbl_penyakit p ON a.id_penyakit = p.id_penyakit
                                INNER JOIN tbl_gejala g ON a.id_gejala = g.id_gejala
                                ORDER BY a.id_aturan
                            ");
                                $no = 1;
                                while ($a = mysqli_fetch_array($data)) {
                                ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($a['nama_penyakit']) ?></td>
                                        <td><?= htmlspecialchars($a['nama_gejala']) ?></td>
                                        <td class="text-center">
                                            <div class="d-flex flex-wrap gap-2 justify-content-center">
                                                <a href="aturan-ubah.php?id_aturan=<?= $a['id_aturan'] ?>"
                                                    class="btn btn-outline-primary rounded-pill btn-sm d-flex align-items-center gap-2 px-3">
                                                    <i class="fa-solid fa-pen"></i> <span>Ubah</span>
                                                </a>

                                                <a href="aturan.php?id_aturan=<?= $a['id_aturan'] ?>&aksi=hapus"
                                                    class="btn btn-outline-danger rounded-pill btn-sm d-flex align-items-center gap-2 px-3"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    <i class="fa-solid fa-trash"></i> <span>Hapus</span>
                                                </a>
                                            </div>

                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>