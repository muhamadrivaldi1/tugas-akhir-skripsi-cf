<?php
include '../assets/conn/config.php';
include 'header.php';

// Ambil data riwayat diagnosa
$query = mysqli_query($conn, "SELECT 
    h.*,
    p.nama_lengkap AS nama_pasien,
    p.jenis_kelamin,
    p.umur,
    a.username AS nama_admin
FROM tbl_hasil h
LEFT JOIN tbl_pasien p ON h.id_pasien = p.id_pasien
LEFT JOIN tbl_admin a ON h.id_akun = a.id_admin
ORDER BY h.tgl_diagnoas DESC");
?>

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-custom {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .search-container {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 25px;
    }

    .search-input {
        border: none;
        border-radius: 50px;
        padding: 12px 20px;
        font-size: 16px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .btn-custom {
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .btn-detail {
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 172, 254, 0.4);
        color: white;
    }

    .btn-delete {
        background: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(250, 112, 154, 0.4);
        color: white;
    }

    .table-modern {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
    }

    .table-modern thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-modern thead th {
        border: none;
        padding: 18px 15px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .table-modern tbody tr {
        transition: all 0.3s ease;
        border: none;
    }

    .table-modern tbody tr:hover {
        background-color: #f8f9ff;
        transform: scale(1.01);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .table-modern tbody td {
        border: none;
        padding: 15px;
        vertical-align: middle;
    }

    .stats-card {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .no-data-container {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border-radius: 15px;
        margin: 20px 0;
    }

    .page-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
        margin-bottom: 30px;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeInUp 0.6s ease-out;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Page Title -->
            <h2 class="text-center page-title fade-in">
                <i class="fas fa-history me-3"></i>
                Riwayat Diagnosa Penyakit Pencernaan Anak
            </h2>

            <!-- Statistics Card -->
            <div class="stats-card fade-in">
                <div class="row">
                    <div class="col-md-4">
                        <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                        <h4><?= mysqli_num_rows($query) ?></h4>
                        <p class="mb-0">Total Diagnosa</p>
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-users fa-2x text-success mb-2"></i>
                        <h4><?= mysqli_num_rows(mysqli_query($conn, "SELECT DISTINCT id_pasien FROM tbl_hasil")) ?></h4>
                        <p class="mb-0">Pasien Unik</p>
                    </div>
                    <div class="col-md-4">
                        <i class="fas fa-calendar-day fa-2x text-warning mb-2"></i>
                        <h4><?= date('d-m-Y') ?></h4>
                        <p class="mb-0">Hari Ini</p>
                    </div>
                </div>
            </div>

            <!-- Search Container -->
            <div class="search-container fade-in">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-0 rounded-start-pill">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control search-input border-0"
                                placeholder="Cari berdasarkan nama pasien, no registrasi, atau diagnosa..."
                                id="searchInput">
                        </div>
                    </div>
                    <div class="col-md-4 text-end">
                        <button class="btn btn-light btn-custom me-2" onclick="clearSearch()">
                            <i class="fas fa-refresh me-1"></i> Reset
                        </button>
                        <button class="btn btn-success btn-custom" onclick="exportData()">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="card card-custom fade-in">
                <div class="card-body p-0">
                    <?php if (mysqli_num_rows($query) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-modern mb-0" id="historyTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pasien</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Umur</th>
                                        <th>No Registrasi</th>
                                        <th>Tanggal Diagnosa</th>
                                        <th>Diagnosa (CF)</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    mysqli_data_seek($query, 0); // Reset pointer
                                    while ($d = mysqli_fetch_array($query)) {
                                    ?>
                                        <tr>
                                            <td class="text-center fw-bold"><?= $no++ ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?= htmlspecialchars($d['nama_pasien']) ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($d['jenis_kelamin'] == 'Laki-laki'): ?>
                                                    <span class="badge bg-primary rounded-pill">
                                                        <i class="fas fa-mars me-1"></i><?= htmlspecialchars($d['jenis_kelamin']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger rounded-pill">
                                                        <i class="fas fa-venus me-1"></i><?= htmlspecialchars($d['jenis_kelamin']) ?>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info rounded-pill">
                                                    <?= htmlspecialchars($d['umur']) ?> Tahun
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <code class="bg-light p-2 rounded"><?= htmlspecialchars($d['no_regdiagnosa']) ?></code>
                                            </td>
                                            <td class="text-center">
                                                <div>
                                                    <i class="fas fa-calendar-alt text-muted me-1"></i>
                                                    <?= date('d-m-Y', strtotime($d['tgl_diagnoas'])) ?>
                                                </div>
                                                <small class="text-muted"><?= date('H:i', strtotime($d['tgl_diagnoas'])) ?></small>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success rounded-pill fs-6">
                                                    <?= htmlspecialchars($d['penyakit_cf']) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="hasil-detail.php?id=<?= $d['id_hasil'] ?>"
                                                        class="btn btn-detail btn-custom btn-sm">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                    <a href="history-hapus.php?id=<?= $d['id_hasil'] ?>"
                                                        class="btn btn-delete btn-custom btn-sm"
                                                        onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                        <i class="fas fa-trash me-1"></i> Hapus
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-data-container">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Belum ada data riwayat diagnosa</h4>
                            <p class="text-muted">Data diagnosa akan muncul di sini setelah ada pemeriksaan.</p>
                            <a href="diagnosa.php" class="btn btn-primary btn-custom">
                                <i class="fas fa-plus me-1"></i> Mulai Diagnosa
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#historyTable tbody tr');

        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTerm)) {
                row.style.display = '';
                row.style.animation = 'fadeInUp 0.3s ease-out';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Clear search
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        const tableRows = document.querySelectorAll('#historyTable tbody tr');
        tableRows.forEach(row => {
            row.style.display = '';
        });
    }

    // Export functionality (placeholder)
    function exportData() {
        alert('Fitur export akan segera tersedia!');
        // Implement actual export logic here
    }

    // Add fade in animation to table rows
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('#historyTable tbody tr');
        rows.forEach((row, index) => {
            row.style.animationDelay = (index * 0.1) + 's';
            row.classList.add('fade-in');
        });
    });
</script>

<?php include 'footer.php'; ?>