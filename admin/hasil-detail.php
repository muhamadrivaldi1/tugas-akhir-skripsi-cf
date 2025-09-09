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
    @media print {

        /* Hilangkan elemen yang tidak perlu dicetak */
        .no-print,
        header,
        footer,
        .sidebar,
        .navbar {
            display: none !important;
        }

        body {
            background: white !important;
            margin: 0;
            padding: 0;
        }

        /* Biar full halaman A4 */
        @page {
            size: A4;
            margin: 20mm;
        }

        /* Posisikan konten di tengah */
        .container {
            width: 100% !important;
            max-width: 800px;
            margin: 0 auto;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 100vh;
            /* full tinggi kertas */
        }

        /* Card biar rapi */
        .card {
            box-shadow: none !important;
            border: 1px solid #ddd;
        }
    }
</style>
<div class="container py-5">
    <!-- Header -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="fas fa-clipboard-list me-2"></i>
            Detail Hasil Diagnosa
        </h2>
        <p class="text-muted">Informasi detail hasil diagnosa pasien</p>
    </div>

    <!-- Tombol -->
    <div class="d-flex justify-content-between mb-4 no-print">
        <a href="history.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print me-2"></i>Cetak
        </button>
    </div>

    <!-- Card Informasi -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="card-title text-primary fw-bold mb-3"><?= htmlspecialchars($d['nama_lengkap']) ?></h5>
            <p class="mb-1">
                <i class="fas fa-id-card me-2 text-muted"></i>
                <strong>No. Registrasi:</strong> <?= htmlspecialchars($d['no_regdiagnosa']) ?>
            </p>
            <p class="mb-0">
                <i class="fas fa-calendar me-2 text-muted"></i>
                <strong>Tanggal Diagnosa:</strong> <?= date('d F Y', strtotime($d['tgl_diagnoas'])) ?>
            </p>
        </div>
    </div>

    <!-- Detail Pasien -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white fw-semibold">
            <i class="fas fa-user me-2"></i> Informasi Pasien
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>No Registrasi</th>
                    <td><?= htmlspecialchars($d['no_regdiagnosa']) ?></td>
                </tr>
                <tr>
                    <th>Tanggal Diagnosa</th>
                    <td><?= date('l, d F Y - H:i', strtotime($d['tgl_diagnoas'])) ?></td>
                </tr>
                <tr>
                    <th>Nama Pasien</th>
                    <td><?= htmlspecialchars($d['nama_lengkap']) ?></td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>
                        <?php if ($d['jenis_kelamin'] == 'Laki-laki'): ?>
                            <span class="badge bg-primary"><i class="fas fa-mars me-1"></i><?= $d['jenis_kelamin'] ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger"><i class="fas fa-venus me-1"></i><?= $d['jenis_kelamin'] ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th>Umur</th>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($d['umur']) ?> Tahun</span></td>
                </tr>
                <tr>
                    <th>Hasil Diagnosa</th>
                    <td>
                        <span class="badge bg-warning text-dark fs-6">
                            <i class="fas fa-heartbeat me-1"></i><?= htmlspecialchars($d['penyakit_cf']) ?>
                        </span>
                        <br>
                        <small class="text-muted">
                            Certainty Factor: <?= isset($d['nilai_cf']) ? number_format((float)$d['nilai_cf'], 2) . '%' : '0.00%' ?>
                        </small>
                    </td>
                </tr>
                <?php if (!empty($d['nama_admin'])): ?>
                    <tr>
                        <th>Diperiksa Oleh</th>
                        <td>
                            <i class="fas fa-user-md me-2 text-success"></i>
                            <?= htmlspecialchars($d['nama_admin']) ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>

    <!-- Catatan -->
    <div class="alert alert-info mt-4 text-center">
        <i class="fas fa-info-circle me-2"></i>
        Hasil diagnosa ini bersifat sementara dan memerlukan konfirmasi medis lebih lanjut.
    </div>
</div>

<?php include 'footer.php'; ?>