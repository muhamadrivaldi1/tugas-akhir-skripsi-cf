<?php
include '../assets/conn/config.php';
include 'header.php';

// Hapus data jika ada parameter 'aksi=hapus'
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id_aturan'])) {
    $id = intval($_GET['id_aturan']); // hindari SQL injection
    mysqli_query($conn, "DELETE FROM tbl_aturan WHERE id_aturan = $id");
    header("Location: aturan.php");
    exit;
}

// Ambil data aturan lengkap
$result = mysqli_query($conn, "SELECT 
        a.id_aturan, 
        p.nama_penyakit, 
        g.nama_gejala, 
        g.nilai_gejala
    FROM tbl_aturan a
    INNER JOIN tbl_penyakit p ON a.id_penyakit = p.id_penyakit
    INNER JOIN tbl_gejala g ON a.id_gejala = g.id_gejala
    ORDER BY a.id_aturan
");

if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-primary">Data Aturan</h3>
    </div>

    <div class="card border-0 shadow rounded">
        <div class="card-header bg-primary text-white fw-semibold">
            <i class="fas fa-database me-1"></i> Tabel Aturan Diagnosa
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle borderless">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Gejala</th>
                            <th>Nama Penyakit</th>
                            <th>Nilai CF</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php $no = 1;
                            while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td class="text-capitalize"><?= htmlspecialchars($row['nama_gejala']) ?></td>
                                    <td class="text-capitalize"><?= htmlspecialchars($row['nama_penyakit']) ?></td>
                                    <td class="text-center"><?= htmlspecialchars($row['nilai_gejala']) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted">Tidak ada data aturan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>