<?php
include '../assets/conn/config.php';
include 'header.php';

if (!isset($_GET['id'])) {
    echo "ID tidak ditemukan.";
    exit;
}

$id = $_GET['id'];
$data = mysqli_query($conn, "SELECT 
    h.*,
    p.nama_lengkap,
    p.jenis_kelamin,
    p.umur,
    a.username AS nama_admin
FROM tbl_hasil h
LEFT JOIN tbl_pasien p ON h.id_pasien = p.id_pasien
LEFT JOIN tbl_admin a ON h.id_akun = a.id_admin
WHERE h.id_hasil = '$id'");
$d = mysqli_fetch_assoc($data);
?>

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-detail {
        border: none;
        border-radius: 25px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        background: white;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        color: white;
        padding: 25px;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .card-header-custom::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .card-header-custom h5 {
        position: relative;
        z-index: 2;
        font-weight: 700;
        font-size: 1.5rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .back-btn {
        background: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        color: white;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 5px 15px rgba(250, 112, 154, 0.3);
    }

    .back-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(250, 112, 154, 0.4);
        color: white;
        text-decoration: none;
    }

    .info-card {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .detail-table {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        background: white;
    }

    .detail-table th {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 20px 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        position: relative;
    }

    .detail-table th::after {
        content: '';
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 20px;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
    }

    .detail-table td {
        padding: 20px 25px;
        border: none;
        background: #f8f9ff;
        font-size: 16px;
        color: #2d3748;
        position: relative;
    }

    .detail-table tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .detail-table tr:hover {
        transform: scale(1.01);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .detail-table tr:last-child {
        border-bottom: none;
    }

    .badge-custom {
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .badge-male {
        background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
        color: white;
    }

    .badge-female {
        background: linear-gradient(45deg, #fa709a 0%, #fee140 100%);
        color: white;
    }

    .badge-age {
        background: linear-gradient(45deg, #a8edea 0%, #fed6e3 100%);
        color: #2d3748;
    }

    .badge-diagnosis {
        background: linear-gradient(45deg, #ffecd2 0%, #fcb69f 100%);
        color: #2d3748;
        font-size: 16px;
        padding: 12px 20px;
        font-weight: 700;
    }

    .icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
    }

    .page-title {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 800;
        margin-bottom: 30px;
        text-align: center;
    }

    .medical-icon {
        font-size: 2rem;
        color: white;
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .slide-in {
        animation: slideInUp 0.6s ease-out;
    }

    .fade-in {
        animation: fadeIn 0.8s ease-out;
    }

    .print-btn {
        background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 50px;
        padding: 12px 30px;
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    }

    .print-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        color: white;
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="container py-5">
    <!-- Header Section -->
    <div class="text-center mb-4 fade-in">
        <h2 class="page-title">
            <i class="fas fa-clipboard-list me-3"></i>
            Detail Hasil Diagnosa Medis
        </h2>
    </div>

    <!-- Back Button -->
    <div class="mb-4 no-print slide-in">
        <a href="history.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Riwayat</span>
        </a>
        <button onclick="window.print()" class="print-btn float-end">
            <i class="fas fa-print me-2"></i>
            <span>Cetak Hasil</span>
        </button>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Info Summary Card -->
            <div class="info-card slide-in" style="animation-delay: 0.2s">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="icon-wrapper mx-auto">
                            <i class="fas fa-user-md medical-icon"></i>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <h4 class="mb-2 text-primary fw-bold"><?= htmlspecialchars($d['nama_lengkap']) ?></h4>
                        <p class="mb-1 text-muted">
                            <i class="fas fa-id-card me-2"></i>
                            No. Registrasi: <strong><?= htmlspecialchars($d['no_regdiagnosa']) ?></strong>
                        </p>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-calendar me-2"></i>
                            Tanggal Diagnosa: <strong><?= date('d F Y', strtotime($d['tgl_diagnoas'])) ?></strong>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Detail Card -->
            <div class="card card-detail slide-in" style="animation-delay: 0.4s">
                <div class="card-header-custom">
                    <h5>
                        <i class="fas fa-notes-medical"></i>
                        Informasi Detail Pasien
                    </h5>
                </div>

                <div class="card-body p-0">
                    <table class="table detail-table mb-0">
                        <tr>
                            <th style="width: 35%;">
                                <i class="fas fa-id-badge me-2"></i>
                                No Registrasi
                            </th>
                            <td>
                                <div class="d-flex align-items-center">
                                    <code class="bg-light p-2 rounded me-2"><?= htmlspecialchars($d['no_regdiagnosa']) ?></code>
                                    <small class="text-muted">ID Unik Pasien</small>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <i class="fas fa-calendar-alt me-2"></i>
                                Tanggal Diagnosa
                            </th>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <strong><?= date('l, d F Y', strtotime($d['tgl_diagnoas'])) ?></strong>
                                    <span class="ms-2 text-muted">(<?= date('H:i', strtotime($d['tgl_diagnoas'])) ?>)</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <i class="fas fa-user me-2"></i>
                                Nama Pasien
                            </th>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <strong class="fs-5"><?= htmlspecialchars($d['nama_lengkap']) ?></strong>
                                        <br>
                                        <small class="text-muted">Nama Lengkap Pasien</small>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <i class="fas fa-venus-mars me-2"></i>
                                Jenis Kelamin
                            </th>
                            <td>
                                <?php if ($d['jenis_kelamin'] == 'Laki-laki'): ?>
                                    <span class="badge-custom badge-male">
                                        <i class="fas fa-mars"></i>
                                        <?= htmlspecialchars($d['jenis_kelamin']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge-custom badge-female">
                                        <i class="fas fa-venus"></i>
                                        <?= htmlspecialchars($d['jenis_kelamin']) ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <i class="fas fa-birthday-cake me-2"></i>
                                Umur
                            </th>
                            <td>
                                <span class="badge-custom badge-age">
                                    <i class="fas fa-child"></i>
                                    <?= htmlspecialchars($d['umur']) ?> Tahun
                                </span>
                                <small class="text-muted ms-2">Umur saat diagnosa</small>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <i class="fas fa-stethoscope me-2"></i>
                                Hasil Diagnosa
                            </th>
                            <td>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="badge-custom badge-diagnosis">
                                        <i class="fas fa-heartbeat"></i>
                                        <?= htmlspecialchars($d['penyakit_cf']) ?>
                                    </span>
                                    <small class="text-muted">Certainty Factor</small>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Diagnosa berdasarkan analisis gejala menggunakan metode Certainty Factor
                                    </small>
                                </div>
                            </td>
                        </tr>
                        <?php if (!empty($d['nama_admin'])): ?>
                            <tr>
                                <th>
                                    <i class="fas fa-user-md me-2"></i>
                                    Diperiksa Oleh
                                </th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 35px; height: 35px;">
                                            <i class="fas fa-user-md text-white"></i>
                                        </div>
                                        <div>
                                            <strong><?= htmlspecialchars($d['nama_admin']) ?></strong>
                                            <br>
                                            <small class="text-muted">Administrator Sistem</small>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="text-center mt-4 slide-in" style="animation-delay: 0.6s">
                <div class="bg-light rounded-pill d-inline-block px-4 py-2">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt text-success me-1"></i>
                        Hasil diagnosa ini bersifat sementara dan memerlukan konfirmasi medis lebih lanjut
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add entrance animations
        const elements = document.querySelectorAll('.slide-in, .fade-in');
        elements.forEach((el, index) => {
            el.style.animationDelay = (index * 0.1) + 's';
        });

        // Add hover effects to table rows
        const tableRows = document.querySelectorAll('.detail-table tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#e8f4f8';
            });

            row.addEventListener('mouseleave', function() {
                this.querySelector('td').style.backgroundColor = '#f8f9ff';
            });
        });
    });

    // Print function
    function printResult() {
        window.print();
    }
</script>

<?php include 'footer.php'; ?>