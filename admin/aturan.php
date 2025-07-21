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

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-modern {
        border: none;
        border-radius: 25px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .header-custom {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 25px;
        position: relative;
        overflow: hidden;
    }

    .header-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .header-custom h5 {
        position: relative;
        z-index: 2;
        font-weight: 700;
        font-size: 1.8rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .stats-container {
        background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .stat-item {
        text-align: center;
        color: #2d3748;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #4a5568;
        display: block;
    }

    .stat-label {
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #718096;
        margin-top: 5px;
    }

    .search-section {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 25px;
    }

    .search-input {
        border: none;
        border-radius: 50px;
        padding: 15px 25px;
        font-size: 16px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .btn-add {
        background: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
        border: none;
        border-radius: 50px;
        padding: 15px 30px;
        color: white;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(250, 112, 154, 0.3);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px rgba(250, 112, 154, 0.4);
        color: white;
        text-decoration: none;
    }

    .table-modern {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .table-modern thead {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .table-modern thead th {
        border: none;
        padding: 20px 15px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.9rem;
        position: relative;
    }

    .table-modern thead th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 3px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
    }

    .table-modern tbody tr {
        transition: all 0.3s ease;
        border: none;
        background: white;
    }

    .table-modern tbody tr:nth-child(even) {
        background: #f8f9ff;
    }

    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, #e8f4f8 0%, #f1f8e8 100%);
        transform: scale(1.02);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        z-index: 10;
        position: relative;
    }

    .table-modern tbody td {
        border: none;
        padding: 20px 15px;
        vertical-align: middle;
        position: relative;
    }

    .disease-badge {
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 3px 10px rgba(79, 172, 254, 0.3);
    }

    .symptom-badge {
        background: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 3px 10px rgba(250, 112, 154, 0.3);
    }

    .btn-edit {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-delete {
        background: linear-gradient(45deg, #ff6b6b 0%, #feca57 100%);
        color: white;
        border: none;
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
    }

    .btn-delete:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 107, 107, 0.4);
        color: white;
        text-decoration: none;
    }

    .page-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
        font-size: 2.5rem;
        text-align: center;
        margin-bottom: 30px;
    }

    .no-data {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border-radius: 20px;
        margin: 20px 0;
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

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }

    .slide-in-left {
        animation: slideInLeft 0.6s ease-out;
    }

    .floating-add-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        background: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 10px 30px rgba(250, 112, 154, 0.4);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .floating-add-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 15px 40px rgba(250, 112, 154, 0.6);
    }

    @media (max-width: 768px) {
        .btn-add {
            padding: 12px 20px;
            font-size: 0.9rem;
        }

        .stats-container {
            padding: 20px 15px;
        }

        .stat-number {
            font-size: 2rem;
        }
    }
</style>

<div class="container py-5">
    <!-- Page Title -->
    <h2 class="page-title fade-in-up">
        <i class="fas fa-cogs me-3"></i>
        Manajemen Aturan Certainty Factor
    </h2>

    <!-- Statistics Section -->
    <div class="stats-container fade-in-up" style="animation-delay: 0.1s">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-item">
                    <i class="fas fa-rules fa-2x mb-3 text-primary"></i>
                    <span class="stat-number"><?= $total_aturan ?></span>
                    <div class="stat-label">Total Aturan</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <i class="fas fa-disease fa-2x mb-3 text-success"></i>
                    <span class="stat-number"><?= $total_penyakit ?></span>
                    <div class="stat-label">Jenis Penyakit</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <i class="fas fa-symptoms fa-2x mb-3 text-warning"></i>
                    <span class="stat-number"><?= $total_gejala ?></span>
                    <div class="stat-label">Gejala Terkait</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Add Section -->
    <div class="search-section fade-in-up" style="animation-delay: 0.2s">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text bg-white border-0 rounded-start-pill">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control search-input border-0"
                        placeholder="Cari berdasarkan nama penyakit atau gejala..."
                        id="searchInput">
                </div>
            </div>
            <div class="col-md-4 text-end mt-3 mt-md-0">
                <a href="aturan-simpan.php" class="btn-add">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Aturan</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card card-modern fade-in-up" style="animation-delay: 0.3s">
                <div class="header-custom">
                    <h5>
                        <i class="fas fa-table"></i>
                        Daftar Aturan Diagnosis
                    </h5>
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
                            <table class="table table-modern mb-0" id="rulesTable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 8%;">No</th>
                                        <th class="text-center" style="width: 35%;">Nama Penyakit</th>
                                        <th class="text-center" style="width: 35%;">Nama Gejala</th>
                                        <th class="text-center" style="width: 22%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    while ($a = mysqli_fetch_array($data)) {
                                    ?>
                                        <tr class="slide-in-left" style="animation-delay: <?= ($no * 0.05) ?>s">
                                            <td class="text-center">
                                                <div class="fw-bold fs-5 text-primary"><?= $no++ ?></div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-disease text-white"></i>
                                                    </div>
                                                    <div>
                                                        <div class="disease-badge">
                                                            <i class="fas fa-heartbeat"></i>
                                                            <?= htmlspecialchars($a['nama_penyakit']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center me-3"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-exclamation-triangle text-white"></i>
                                                    </div>
                                                    <div>
                                                        <div class="symptom-badge">
                                                            <i class="fas fa-thermometer-half"></i>
                                                            <?= htmlspecialchars($a['nama_gejala']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="aturan-ubah.php?id_aturan=<?= $a['id_aturan'] ?>"
                                                        class="btn-edit">
                                                        <i class="fas fa-edit"></i>
                                                        <span>Edit</span>
                                                    </a>
                                                    <a href="aturan.php?id_aturan=<?= $a['id_aturan'] ?>&aksi=hapus"
                                                        class="btn-delete"
                                                        onclick="return confirm('Yakin ingin menghapus aturan ini?\n\nPenyakit: <?= htmlspecialchars($a['nama_penyakit']) ?>\nGejala: <?= htmlspecialchars($a['nama_gejala']) ?>')">
                                                        <i class="fas fa-trash"></i>
                                                        <span>Hapus</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                            <h4 class="text-muted mb-3">Belum Ada Aturan</h4>
                            <p class="text-muted mb-4">Mulai dengan menambahkan aturan diagnosis pertama Anda.</p>
                            <a href="aturan-simpan.php" class="btn-add">
                                <i class="fas fa-plus"></i>
                                <span>Tambah Aturan Pertama</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Add Button (Alternative) -->
<a href="aturan-simpan.php" class="floating-add-btn d-md-none" title="Tambah Aturan">
    <i class="fas fa-plus"></i>
</a>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#rulesTable tbody tr');

        let visibleRows = 0;
        tableRows.forEach(row => {
            const diseaseText = row.cells[1].textContent.toLowerCase();
            const symptomText = row.cells[2].textContent.toLowerCase();

            if (diseaseText.includes(searchTerm) || symptomText.includes(searchTerm)) {
                row.style.display = '';
                row.style.animation = 'fadeInUp 0.3s ease-out';
                visibleRows++;
            } else {
                row.style.display = 'none';
            }
        });

        // Show/hide no results message
        const noResultsMsg = document.getElementById('noResults');
        if (visibleRows === 0 && searchTerm.length > 0) {
            if (!noResultsMsg) {
                const tbody = document.querySelector('#rulesTable tbody');
                const tr = document.createElement('tr');
                tr.id = 'noResults';
                tr.innerHTML = '<td colspan="4" class="text-center py-5"><i class="fas fa-search fa-2x text-muted mb-3"></i><br><h5 class="text-muted">Tidak ada data yang ditemukan</h5><p class="text-muted">Coba gunakan kata kunci lain</p></td>';
                tbody.appendChild(tr);
            } else {
                noResultsMsg.style.display = '';
            }
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    });

    // Enhanced hover effects
    document.addEventListener('DOMContentLoaded', function() {
        const tableRows = document.querySelectorAll('#rulesTable tbody tr');

        tableRows.forEach((row, index) => {
            // Add staggered animation delay
            row.style.animationDelay = (index * 0.05) + 's';

            // Enhanced hover effect
            row.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.02) translateZ(10px)';
                this.style.zIndex = '20';
            });

            row.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) translateZ(0px)';
                this.style.zIndex = '1';
            });
        });

        // Button hover effects
        const buttons = document.querySelectorAll('.btn-edit, .btn-delete');
        buttons.forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.05)';
            });

            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
    });

    // Smooth scroll to top when page loads
    window.addEventListener('load', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>

<?php include 'footer.php'; ?>