<?php
include '../assets/conn/config.php';

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    mysqli_query($conn, "DELETE FROM tbl_aturan WHERE id_aturan='$_GET[id_aturan]'");
    header("location:aturan.php");
    exit;
}

include 'header.php';

// Get statistics
$total_aturan = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tbl_aturan"));
$total_penyakit = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT id_penyakit FROM tbl_aturan"));
$total_gejala = mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT id_gejala FROM tbl_aturan"));
?>

<div class="container py-5">
    <!-- Page Title -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-cogs me-2"></i> Manajemen Aturan Certainty Factor
        </h2>
        <p class="text-muted">Kelola aturan diagnosis, penyakit, dan gejala</p>
    </div>

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="fas fa-list fa-2x text-primary mb-2"></i>
                    <h4 class="fw-bold"><?= $total_aturan ?></h4>
                    <p class="text-muted small mb-0">Total Aturan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="fas fa-heartbeat fa-2x text-success mb-2"></i>
                    <h4 class="fw-bold"><?= $total_penyakit ?></h4>
                    <p class="text-muted small mb-0">Jenis Penyakit</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="fas fa-thermometer-half fa-2x text-danger mb-2"></i>
                    <h4 class="fw-bold"><?= $total_gejala ?></h4>
                    <p class="text-muted small mb-0">Gejala Terkait</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Add -->
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div class="input-group w-auto">
            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Cari penyakit atau gejala...">
        </div>
        <a href="aturan-simpan.php" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus me-1"></i> Tambah Aturan
        </a>
    </div>

    <!-- Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-table me-2"></i> Daftar Aturan
        </div>
        <div class="card-body p-0">
            <?php
            $data = mysqli_query($conn, "
                SELECT a.id_aturan, p.nama_penyakit, g.nama_gejala
                FROM tbl_aturan a
                INNER JOIN tbl_penyakit p ON a.id_penyakit = p.id_penyakit
                INNER JOIN tbl_gejala g ON a.id_gejala = g.id_gejala
                ORDER BY a.id_aturan
            ");
            ?>

            <?php if (mysqli_num_rows($data) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="rulesTable">
                        <thead class="table-primary">
                            <tr>
                                <th class="text-center" style="width:5%">No</th>
                                <th style="width:35%">Nama Penyakit</th>
                                <th style="width:35%">Nama Gejala</th>
                                <th class="text-center" style="width:25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1; while ($a = mysqli_fetch_array($data)) { ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><span class="badge bg-primary bg-gradient px-3 py-2"><?= htmlspecialchars($a['nama_penyakit']) ?></span></td>
                                    <td><span class="badge bg-danger bg-gradient px-3 py-2"><?= htmlspecialchars($a['nama_gejala']) ?></span></td>
                                    <td class="text-center">
                                        <a href="aturan-ubah.php?id_aturan=<?= $a['id_aturan'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="aturan.php?id_aturan=<?= $a['id_aturan'] ?>&aksi=hapus"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Yakin ingin menghapus aturan ini?\n\nPenyakit: <?= htmlspecialchars($a['nama_penyakit']) ?>\nGejala: <?= htmlspecialchars($a['nama_gejala']) ?>')">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <h5>Belum Ada Aturan</h5>
                    <p>Mulai dengan menambahkan aturan diagnosis pertama Anda.</p>
                    <a href="aturan-simpan.php" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> Tambah Aturan Pertama
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const term = this.value.toLowerCase();
        document.querySelectorAll('#rulesTable tbody tr').forEach(row => {
            const penyakit = row.cells[1].textContent.toLowerCase();
            const gejala = row.cells[2].textContent.toLowerCase();
            row.style.display = (penyakit.includes(term) || gejala.includes(term)) ? '' : 'none';
        });
    });
</script>

<?php include 'footer.php'; ?>