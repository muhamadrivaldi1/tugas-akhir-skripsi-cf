<?php
include '../assets/conn/config.php';
include 'header.php';

// Ambil id_admin (default admin pertama jika ada)
$sql_admin = "SELECT id_admin FROM tbl_admin LIMIT 1";
$res_admin = mysqli_query($conn, $sql_admin);
if ($res_admin && mysqli_num_rows($res_admin) > 0) {
    $row_admin = mysqli_fetch_assoc($res_admin);
    $id_admin_default = $row_admin['id_admin'];
} else {
    $id_admin_default = 0;
}

// Proses simpan data hasil diagnosa ke tbl_hasil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_regdiagnosa = mysqli_real_escape_string($conn, $_POST['no_regdiagnosa']);
    $id_pasien      = (int) $_POST['id_pasien'];
    $penyakit       = mysqli_real_escape_string($conn, $_POST['penyakit']);
    $persentase     = (float) preg_replace('/[^0-9.]/', '', $_POST['persentase']);
    $tanggal        = date('Y-m-d H:i:s');

    $id_admin = $id_admin_default;

    $sql_insert = "INSERT INTO tbl_hasil 
                    (no_regdiagnosa, id_pasien, id_admin, penyakit_cf, nilai_cf, tgl_diagnoas) 
                   VALUES (?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql_insert);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // string, int, int, string, double, string
    $stmt->bind_param("siisds", $no_regdiagnosa, $id_pasien, $id_admin, $penyakit, $persentase, $tanggal);

    if (!$stmt->execute()) {
        die("Insert error: " . $stmt->error);
    }
    $stmt->close();
}

// Filter berdasarkan pasien
$id_pasien = isset($_GET['id_pasien']) ? intval($_GET['id_pasien']) : 0;

// ✅ Perbaiki tgl_diagnoas → tgl_diagnosa
$sql = "SELECT 
    h.id_hasil,
    h.id_pasien,
    h.id_admin,
    h.no_regdiagnosa,
    h.tgl_diagnoas,
    h.penyakit_cf,
    h.nilai_cf,
    p.nama_lengkap,
    p.jenis_kelamin,
    p.umur
FROM tbl_hasil h
LEFT JOIN tbl_pasien p ON h.id_pasien = p.id_pasien";

if ($id_pasien > 0) {
    $sql .= " WHERE h.id_pasien = " . intval($id_pasien);
}
$sql .= " ORDER BY h.tgl_diagnoas DESC";

$query = mysqli_query($conn, $sql) or die("Query error: " . mysqli_error($conn));

$hasil = [];
while ($row = mysqli_fetch_assoc($query)) {
    $hasil[] = $row;
}

// Statistik
$totalDiagnosa = count($hasil);

$sql_unique = "SELECT COUNT(DISTINCT id_pasien) as total_unique FROM tbl_hasil";
if ($id_pasien > 0) {
    $sql_unique .= " WHERE id_pasien = " . intval($id_pasien);
}
$query_unique = mysqli_query($conn, $sql_unique);
$totalPasienUnik = 0;
if ($query_unique) {
    $row_unique = mysqli_fetch_assoc($query_unique);
    $totalPasienUnik = $row_unique['total_unique'];
}
?>


<div class="container py-5">
    <!-- Title -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-history me-2"></i> Riwayat Diagnosa
        </h2>
        <p class="text-muted">Penyakit Pencernaan Anak</p>
    </div>

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-notes-medical fa-2x text-primary mb-2"></i>
                    <h5 class="fw-bold"><?= $totalDiagnosa ?></h5>
                    <small class="text-muted">Total Diagnosa</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-success mb-2"></i>
                    <h5 class="fw-bold"><?= $totalPasienUnik ?></h5>
                    <small class="text-muted">Pasien Unik</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <i class="fas fa-calendar-day fa-2x text-warning mb-2"></i>
                    <h5 class="fw-bold"><?= date('d-m-Y') ?></h5>
                    <small class="text-muted">Hari Ini</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Pencarian -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-0 shadow-sm"
                            placeholder="Cari nama pasien, no registrasi, diagnosa..."
                            id="searchInput">
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-outline-secondary me-2" onclick="clearSearch()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button class="btn btn-success" onclick="exportData()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Riwayat -->
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if ($totalDiagnosa > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped align-middle" id="historyTable">
                        <thead class="table-primary">
                            <tr>
                                <th>No</th>
                                <th>Nama Pasien</th>
                                <th>JK</th>
                                <th>Umur</th>
                                <th>No Registrasi</th>
                                <th>Tanggal</th>
                                <th>Diagnosa</th>
                                <th>Nilai CF</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($hasil as $d): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($d['nama_lengkap'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($d['jenis_kelamin'] == 'Laki-Laki'): ?>
                                            <span class="badge bg-primary">L</span>
                                        <?php elseif ($d['jenis_kelamin'] == 'Perempuan'): ?>
                                            <span class="badge bg-danger">P</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><span class="badge bg-info"><?= $d['umur'] ?? '0' ?> Th</span></td>
                                    <td><code><?= htmlspecialchars($d['no_regdiagnosa']) ?></code></td>
                                    <td>
                                        <?= date('d-m-Y H:i', strtotime($d['tgl_diagnoas'])) ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($d['penyakit_cf'])): ?>
                                            <span class="badge bg-success"><?= htmlspecialchars($d['penyakit_cf']) ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= number_format((float)$d['nilai_cf'], 2) ?>%</td>
                                    <td>
                                        <a href="hasil-detail.php?id=<?= $d['id_hasil'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="history-hapus.php?id=<?= $d['id_hasil'] ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Hapus data ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center p-5 text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>Belum ada data riwayat diagnosa</p>
                    <a href="diagnosa.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Mulai Diagnosa
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    // Search
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#historyTable tbody tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchInput').dispatchEvent(new Event('keyup'));
    }

    // Export CSV
    function exportData() {
        const rows = document.querySelectorAll('#historyTable tbody tr:not([style*="display: none"])');
        if (rows.length === 0) {
            alert('Tidak ada data untuk diekspor!');
            return;
        }
        let csv = "No,Nama Pasien,JK,Umur,No Registrasi,Tanggal,Diagnosa,Nilai CF\n";
        rows.forEach(r => {
            const cells = r.querySelectorAll('td');
            let row = Array.from(cells).map(td => `"${td.innerText.trim()}"`).join(",");
            csv += row + "\n";
        });
        const blob = new Blob([csv], {
            type: "text/csv;charset=utf-8;"
        });
        const link = document.createElement("a");
        link.href = URL.createObjectURL(blob);
        link.download = "riwayat_diagnosa.csv";
        link.click();
    }
</script>

<?php
if (isset($query)) mysqli_free_result($query);
if (isset($query_unique)) mysqli_free_result($query_unique);
include 'footer.php';
?>