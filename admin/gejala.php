<?php
include '../assets/conn/config.php';
if (isset($_GET['aksi'])) {
    if ($_GET['aksi'] == 'hapus') {
        mysqli_query($conn, "DELETE FROM tbl_gejala WHERE id_gejala='$_GET[id_gejala]'");
        header("location:gejala.php");
    }
}

include 'header.php';
?>

<div class="container py-4">
    <div class="card border-0 shadow rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h5 class="m-0">Data Gejala</h5>
            <a href="gejala-simpan.php" class="btn btn-light btn-sm rounded-pill">
                <i class="fas fa-plus me-1"></i> Tambah Data
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Gejala</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $data = mysqli_query($conn, "SELECT * FROM tbl_gejala ORDER BY id_gejala");
                        $no = 1;
                        while ($a = mysqli_fetch_array($data)) {
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($a['nama_gejala']) ?></td>
                                <td><?= htmlspecialchars($a['nilai_gejala']) ?></td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <a href="gejala-ubah.php?id_gejala=<?= $a['id_gejala'] ?>"
                                            class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-2 px-3">
                                            <i class="fas fa-edit"></i> <span>Ubah</span>
                                        </a>

                                        <a href="gejala.php?id_gejala=<?= $a['id_gejala'] ?>&aksi=hapus"
                                            class="btn btn-outline-danger btn-sm rounded-pill d-flex align-items-center gap-2 px-3"
                                            onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <i class="fas fa-trash-alt"></i> <span>Hapus</span>
                                        </a>
                                    </div>

                                </td>
                            </tr>
                        <?php } ?>
                        <?php if (mysqli_num_rows($data) == 0): ?>
                            <tr>
                                <td colspan="4" class="text-center text-danger">Belum ada data gejala.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include 'footer.php';
?>