<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
include '../assets/conn/config.php';

// Fungsi generate string acak
function generateRandomString(int $len = 10): string
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($chars), 0, $len);
}

// ============================ SIMPAN HASIL KE RIWAYAT ========================
if (($_GET['aksi'] ?? '') === 'simpan') {
    $no_regdiagnosa = $_POST['no_regdiagnosa'] ?? '';
    $id_pasien      = (int)($_POST['id_pasien'] ?? 0);
    $id_admin       = (int)($_POST['id_admin'] ?? 0);
    $penyakit_cf    = $_POST['penyakit_cf'] ?? '';
    $nilai_cf       = (float)($_POST['nilai_cf'] ?? 0);

    if (!$no_regdiagnosa || !$id_pasien || !$id_admin) {
        echo "error: data kosong atau id tidak valid";
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO tbl_hasil 
        (no_regdiagnosa, id_pasien, id_admin, penyakit_cf, nilai_cf, tgl_diagnoas)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");
    if (!$stmt) {
        echo "error prepare: " . $conn->error;
        exit;
    }

    $stmt->bind_param("siisd", $no_regdiagnosa, $id_pasien, $id_admin, $penyakit_cf, $nilai_cf);
    if ($stmt->execute()) {
        echo "ok";
    } else {
        echo "error execute: " . $stmt->error;
    }
    $stmt->close();
    exit;
}

// ============================ HANDLE SUBMIT DIAGNOSA =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['aksi'] ?? '') === 'diagnosa') {
    $no_regdiagnosa = generateRandomString();
    $tgl_diagnosa   = date('Y-m-d');
    $id_admin       = (int)($_POST['id_admin'] ?? 0);

    // 🔹 cari id_pasien dari tbl_pasien berdasarkan id_admin
    $id_pasien = 0;
    $sql_pasien = "SELECT id_pasien FROM tbl_pasien WHERE id_admin = ? LIMIT 1";
    $stmt_pasien = $conn->prepare($sql_pasien);
    $stmt_pasien->bind_param("i", $id_admin);
    $stmt_pasien->execute();
    $res_pasien = $stmt_pasien->get_result();
    if ($row_pasien = $res_pasien->fetch_assoc()) {
        $id_pasien = (int)$row_pasien['id_pasien'];
    }
    $stmt_pasien->close();

    // 🔹 simpan ke tbl_diagnosa
    if (!empty($_POST['kondisi']) && !empty($_POST['id_gejala'])) {
        $sqlInsert = "INSERT INTO tbl_diagnosa
                      (no_regdiagnosa, tgl_diagnosa, id_admin, id_gejala, nilai_pasien)
                      VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($sqlInsert) or die('Prepare gagal: ' . $conn->error);
        $stmt->bind_param('ssiid', $no_regdiagnosa, $tgl_diagnosa, $id_admin, $id_gejala, $nilai_pasien);

        foreach ($_POST['kondisi'] as $idx => $nilai_pasien) {
            $id_gejala   = (int)$_POST['id_gejala'][$idx];
            $nilai_pasien = (float)$nilai_pasien;
            $stmt->execute();
        }
        $stmt->close();
    }

    // redirect ke halaman diagnosa dengan parameter
    header("Location: diagnosa.php?no_regdiagnosa=$no_regdiagnosa&id_admin=$id_admin&id_pasien=$id_pasien");
    exit;
}

// ============================= DATA ADMIN ====================================
$username  = $_SESSION['username'] ?? '';
$id_admin  = 0;
$id_pasien = 0;
if ($username) {
    $resAdm = $conn->query("SELECT id_admin FROM tbl_admin WHERE username='" . $conn->real_escape_string($username) . "' LIMIT 1");
    if ($rowAdm = $resAdm->fetch_assoc()) {
        $id_admin = (int)$rowAdm['id_admin'];

        // 🔹 ambil id_pasien default
        $resPas = $conn->query("SELECT id_pasien FROM tbl_pasien WHERE id_admin=" . $id_admin . " LIMIT 1");
        if ($rowPas = $resPas->fetch_assoc()) {
            $id_pasien = (int)$rowPas['id_pasien'];
        }
    }
}

include 'header.php';

// ============================= FORM GEJALA ===================================
$gejalaRes = $conn->query('SELECT * FROM tbl_gejala ORDER BY id_gejala');

// ============================ HASIL DIAGNOSA =================================
$no_regdiagnosa_url = $_GET['no_regdiagnosa'] ?? null;
$resultDiag = null;
$data_cf = [];
$cf_by_penyakit = [];
$max_cf = 0;
$max_penyakit = '';
$id_penyakit_tertinggi = 0;

if ($no_regdiagnosa_url) {
    $sqlHasil = "SELECT p.nama_penyakit,
                        g.nama_gejala,
                        g.nilai_gejala  AS nilai_gejala,
                        d.nilai_pasien  AS nilai_pasien,
                        p.id_penyakit
                FROM tbl_diagnosa d
                JOIN tbl_gejala  g ON g.id_gejala  = d.id_gejala
                LEFT JOIN tbl_aturan  a ON a.id_gejala  = g.id_gejala
                LEFT JOIN tbl_penyakit p ON p.id_penyakit = a.id_penyakit
                WHERE d.id_admin = '$id_admin'
                AND d.no_regdiagnosa = '" . $conn->real_escape_string($no_regdiagnosa_url) . "'
                ORDER BY p.id_penyakit";
    $resultDiag = $conn->query($sqlHasil) or die('Query MySQL gagal: ' . mysqli_error($conn));

    // Proses data CF untuk setiap penyakit
    while ($row = $resultDiag->fetch_assoc()) {
        $cf_pakar  = (float)$row['nilai_gejala'];
        $cf_user   = (float)$row['nilai_pasien'];
        $cf_akhir  = $cf_pakar * $cf_user;
        $nama_penyakit = $row['nama_penyakit'] ?: '-';

        $data_cf[] = [
            'id_penyakit' => $row['id_penyakit'],
            'nama_penyakit' => $nama_penyakit,
            'nama_gejala' => $row['nama_gejala'],
            'cf_pakar' => $cf_pakar,
            'cf_user' => $cf_user,
            'cf_akhir' => $cf_akhir
        ];

        if (!isset($cf_by_penyakit[$nama_penyakit])) $cf_by_penyakit[$nama_penyakit] = [];
        $cf_by_penyakit[$nama_penyakit][] = [
            'cf_akhir' => $cf_akhir
        ];
    }

    // Hitung CF Combine tertinggi
    foreach ($cf_by_penyakit as $penyakit => $list_cf) {
        $cf_combine = 0;
        foreach ($list_cf as $i => $cf) {
            $cf_akhir = $cf['cf_akhir'];
            $cf_combine = $i == 0 ? $cf_akhir : $cf_combine + $cf_akhir * (1 - $cf_combine);
        }
        if ($cf_combine > $max_cf) {
            $max_cf = $cf_combine;
            $max_penyakit = $penyakit;
        }
    }

    // 🔹 ambil id_penyakit dari penyakit dengan CF tertinggi
    if (!empty($max_penyakit)) {
        $sqlP = "SELECT id_penyakit FROM tbl_penyakit WHERE nama_penyakit = ? LIMIT 1";
        $stmtP = $conn->prepare($sqlP);
        $stmtP->bind_param("s", $max_penyakit);
        $stmtP->execute();
        $stmtP->bind_result($id_penyakit_tertinggi);
        $stmtP->fetch();
        $stmtP->close();
    }
}
?>

<!-- ============================= HTML ============================= -->
<div class="container">
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Form Diagnosa Gejala</h5>
        </div>
        <div class="card-body">
            <form action="diagnosa.php?aksi=diagnosa" method="post">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-primary">
                            <tr class="text-center">
                                <th>No</th>
                                <th>Gejala</th>
                                <th>Pilih Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            while ($g = $gejalaRes->fetch_assoc()): ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $no++ ?></td>
                                    <td>
                                        <span class="badge bg-info text-dark">G<?= $g['id_gejala'] ?></span>
                                        Apakah Anda mengalami gejala <strong><?= htmlspecialchars($g['nama_gejala']) ?></strong>?
                                    </td>
                                    <td>
                                        <select class="form-select" name="kondisi[]">
                                            <option value="0" selected>Pilih Kondisi</option>
                                            <option value="0.8">Yakin (0.8)</option>
                                            <option value="0.6">Cukup Yakin (0.6)</option>
                                            <option value="0.4">Kurang Yakin (0.4)</option>
                                            <option value="0.2">Tidak Yakin (0.2)</option>
                                            <option value="0">Sangat Tidak Yakin (0)</option>
                                        </select>
                                    </td>
                                </tr>
                                <input type="hidden" name="id_gejala[]" value="<?= $g['id_gejala'] ?>">
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="id_admin" value="<?= $id_admin ?>">
                <div class="d-flex justify-content-between mt-4">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-stethoscope me-1"></i> Proses Diagnosa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== HASIL ANALISA ==================== -->
    <?php if ($resultDiag && count($data_cf)): ?>
        <div class="container mt-5">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">
                            <i class="fas fa-notes-medical me-2"></i>Hasil Diagnosa Penyakit Pencernaan
                        </h3>
                        <p class="text-muted">Berikut adalah hasil diagnosa berdasarkan gejala yang Anda pilih:</p>
                    </div>
                    <hr>
                    <h5 class="fw-bold text-primary mb-3">Detail Perhitungan Setiap Gejala</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped align-middle">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Jenis Penyakit</th>
                                    <th>Gejala</th>
                                    <th>CF Pakar</th>
                                    <th>CF User</th>
                                    <th>Nilai CF</th>
                                    <th>Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($data_cf as $row): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['nama_penyakit']) ?></td>
                                        <td><?= htmlspecialchars($row['nama_gejala']) ?></td>
                                        <td class="text-center"><?= $row['cf_pakar'] ?></td>
                                        <td class="text-center"><?= $row['cf_user'] ?></td>
                                        <td class="text-center fw-semibold text-primary"><?= number_format($row['cf_akhir'], 2) ?></td>
                                        <td class="text-center text-success fw-bold"><?= number_format($row['cf_akhir'] * 100, 2) ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <strong>Hasil Akhir CF Combine Tertinggi: </strong>
            <span class="text-primary fw-bold">
                <?= htmlspecialchars($max_penyakit) . " (" . number_format($max_cf * 100, 2) . "%)" ?>
            </span>
        </div>

        <div class="card border-0 bg-light p-3 mt-4">
            <h5 class="fw-bold text-primary mb-2"><i class="fas fa-lightbulb me-1"></i> Solusi</h5>
            <?php
            if (!empty($max_penyakit)) {
                $sqlSolusi = "SELECT solusi FROM tbl_penyakit WHERE nama_penyakit = ?";
                $stmtSolusi = $conn->prepare($sqlSolusi);
                $stmtSolusi->bind_param('s', $max_penyakit);
                $stmtSolusi->execute();
                $stmtSolusi->bind_result($solusi);
                if ($stmtSolusi->fetch() && !empty($solusi)) {
                    echo '<div class="alert alert-success">' . nl2br(htmlspecialchars($solusi)) . '</div>';
                } else {
                    echo '<div class="alert alert-warning">Solusi untuk penyakit ini belum tersedia.</div>';
                }
                $stmtSolusi->close();
            }
            ?>

            <!-- Form Simpan Riwayat -->
            <form id="formSimpanRiwayat" method="post" class="mt-3">
                <input type="hidden" name="no_regdiagnosa" value="<?= htmlspecialchars($no_regdiagnosa_url) ?>">
                <input type="hidden" name="id_pasien" value="<?= (int)$id_pasien ?>">
                <input type="hidden" name="id_admin" value="<?= (int)$id_admin ?>">
                <input type="hidden" name="penyakit_cf" value="<?= htmlspecialchars($max_penyakit) ?>">
                <input type="hidden" name="nilai_cf" value="<?= round(($max_cf ?? 0) * 100, 2) ?>">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save me-1"></i> Simpan ke Riwayat
                </button>
            </form>
        </div>
    <?php endif; ?>
</div>

<!-- jQuery + Simpan Alert -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $('#formSimpanRiwayat').submit(function(e) {
            e.preventDefault();
            $.post('../admin/history.php?aksi=simpan', $(this).serialize(), function(res) {
                alert("✅ Data berhasil disimpan ke Riwayat.");
            }).fail(function() {
                alert("❌ Gagal menyimpan data.");
            });
        });
    });
</script>
