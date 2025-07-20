<?php
date_default_timezone_set('Asia/Jakarta');
include '../assets/conn/config.php';

function generateRandomString(int $len = 10): string
{
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle($chars), 0, $len);
}

// ============================ HANDLE SUBMIT ==================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_GET['aksi'] ?? '') === 'diagnosa') {
    $no_regdiagnosa = generateRandomString();
    $tgl_diagnosa   = date('Y-m-d');
    $id_admin       = (int)($_POST['id_admin'] ?? 0);   // pastikan ada, default 0

    $sqlInsert = "INSERT INTO tbl_diagnosa
                  (no_regdiagnosa, tgl_diagnosa, id_akun, id_gejala, nilai_pasien)
                  VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($sqlInsert) or die('Prepare gagal: ' . $conn->error);

    $stmt->bind_param(
        'ssiid',
        $no_regdiagnosa,
        $tgl_diagnosa,
        $id_admin,
        $id_gejala,      // placeholder, diisi ulang di loop
        $nilai_pasien    // placeholder
    );

    foreach ($_POST['kondisi'] as $idx => $nilai_pasien) {
        $id_gejala   = (int)$_POST['id_gejala'][$idx];
        $nilai_pasien = (float)$nilai_pasien;
        $stmt->execute();
    }
    $stmt->close();
    header("Location: diagnosa.php?no_regdiagnosa=$no_regdiagnosa");
    exit;
}

// ============================= DATA ADMIN ====================================
$username  = $_SESSION['username'] ?? '';
$id_admin  = 0;
if ($username) {
    $resAdm = $conn->query("SELECT id_admin FROM tbl_admin WHERE username='" . $conn->real_escape_string($username) . "' LIMIT 1");
    if ($rowAdm = $resAdm->fetch_assoc()) {
        $id_admin = (int)$rowAdm['id_admin'];
    }
}

include 'header.php';

// ============================= FORM GEJALA ===================================
$gejalaRes = $conn->query('SELECT * FROM tbl_gejala ORDER BY id_gejala');

// ============================ HASIL DIAGNOSA =================================
$no_regdiagnosa_url = $_GET['no_regdiagnosa'] ?? null;
$resultDiag = null;
if ($no_regdiagnosa_url) {
    $sqlHasil = "SELECT p.nama_penyakit,
                        g.nama_gejala,
                        g.nilai_gejala  AS nilai_gejala,
                        d.nilai_pasien  AS nilai_pasien
                FROM tbl_diagnosa d
                JOIN tbl_gejala  g ON g.id_gejala  = d.id_gejala
                LEFT JOIN tbl_aturan  a ON a.id_gejala  = g.id_gejala
                LEFT JOIN tbl_penyakit p ON p.id_penyakit = a.id_penyakit
                WHERE d.id_akun = '$id_admin'
                AND d.no_regdiagnosa = '" . $conn->real_escape_string($no_regdiagnosa_url) . "'
                ORDER BY p.id_penyakit";
    $resultDiag = $conn->query($sqlHasil) or die('Query MySQL gagal: ' . mysqli_error($conn));
}
?>

<!-- ============================= HTML ============================= -->
<div class="container">
    <div class="card shadow-lg p-5 mb-5 bg-white rounded border-primary">
        <div class="card-header bg-primary text-white">
            <h4 class="m-0 font-weight-bold">Diagnosa Penyakit Pencernaan</h4>
            <small class="text-light">Silakan pilih kondisi Anda pada setiap gejala berikut:</small>
        </div>
        <div class="card-body">
            <form action="diagnosa.php?aksi=diagnosa" method="post">
                <table class="table table-hover table-bordered mb-4">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Gejala</th>
                            <th>Pilih Kondisi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($g = $gejalaRes->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center align-middle"><?= $no++ ?></td>
                                <td class="align-middle">
                                    <span class="badge badge-info">G<?= $g['id_gejala'] ?></span>
                                    Apakah Anda mengalami gejala <b><?= htmlspecialchars($g['nama_gejala']) ?></b>?
                                </td>
                                <td>
                                    <select class="form-control" name="kondisi[]">
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
                <input type="hidden" name="id_admin" value="<?= $id_admin ?>">
                <div class="d-flex justify-content-between">
                    <a href="index.php" class="btn btn-outline-secondary mb-2">Batal</a>
                    <button type="submit" class="btn btn-primary mb-2">
                        <i class="fas fa-stethoscope"></i> Proses Diagnosa
                    </button>
                </div>
            </form>
        </div>

        <!-- ==================== HASIL ANALISA ==================== -->
        <?php if ($resultDiag && $resultDiag->num_rows): ?>
            <div class="mt-5">
                <center>
                    <h3 class="font-weight-bold text-primary mb-3">
                        Hasil Diagnosa Penyakit Pencernaan
                    </h3>
                    <p class="lead">Berikut adalah hasil diagnosa berdasarkan gejala yang Anda pilih:</p>
                </center>
                <hr>
                <h5 class="font-weight-bold mb-3">Detail Perhitungan Setiap Gejala</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Penyakit</th>
                                <th>Gejala</th>
                                <th>CF Pakar</th>
                                <th>CF User</th>
                                <th>Nilai CF (CF<sub>pakar</sub> × CF<sub>user</sub>)</th>
                                <th>Persentase (%)</th>
                                <th>Rumus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $data_cf = [];
                            $penyakit_persen = [];
                            while ($row = $resultDiag->fetch_assoc()):
                                $cf_pakar  = (float)$row['nilai_gejala'];
                                $cf_user   = (float)$row['nilai_pasien'];
                                $cf_akhir  = $cf_pakar * $cf_user;
                                $persen    = $cf_akhir * 100;
                                $nama_penyakit = $row['nama_penyakit'] ?: '-';
                                // Simpan data untuk perhitungan detail
                                $data_cf[] = [
                                    'nama_penyakit' => $nama_penyakit,
                                    'nama_gejala' => $row['nama_gejala'],
                                    'cf_pakar' => $cf_pakar,
                                    'cf_user' => $cf_user,
                                    'cf_akhir' => $cf_akhir
                                ];
                                // Akumulasi persentase per penyakit
                                if (!isset($penyakit_persen[$nama_penyakit])) $penyakit_persen[$nama_penyakit] = 0;
                                $penyakit_persen[$nama_penyakit] += $cf_akhir;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($nama_penyakit) ?></td>
                                    <td><?= htmlspecialchars($row['nama_gejala']) ?></td>
                                    <td class="text-center"><?= $cf_pakar ?></td>
                                    <td class="text-center"><?= $cf_user ?></td>
                                    <td class="text-center"><?= number_format($cf_akhir, 2) ?></td>
                                    <td class="text-center text-success font-weight-bold"><?= number_format($persen, 2) ?>%</td>
                                    <td>
                                        <?= "CF_pakar × CF_user = {$cf_pakar} × {$cf_user} = " . number_format($cf_akhir, 2) ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($data_cf)): ?>
            <div class="border p-4 mt-4 bg-light rounded">
                <h5 class="font-weight-bold text-primary mb-3">
                    Rekapitulasi Persentase Penyakit Berdasarkan Gejala
                </h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Jenis Penyakit</th>
                                <th>Total Nilai CF</th>
                                <th>Persentase (%)</th>
                                <th>Rumus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($penyakit_persen as $penyakit => $total_cf):
                                $persen = $total_cf * 100;
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($penyakit) ?></td>
                                    <td class="text-center"><?= number_format($total_cf, 2) ?></td>
                                    <td class="text-center text-primary font-weight-bold"><?= number_format($persen, 2) ?>%</td>
                                    <td>
                                        <?= "Total CF × 100 = " . number_format($total_cf, 2) . " × 100 = " . number_format($persen, 2) . "%" ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <hr>
                <h5 class="font-weight-bold text-success mt-4">
                    Detail Perhitungan CF Combine
                </h5>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Jenis Penyakit</th>
                                <th>Gejala</th>
                                <th>CF Pakar</th>
                                <th>CF User</th>
                                <th>CF Combine</th>
                                <th>Rumus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Kelompokkan data_cf berdasarkan penyakit
                            $cf_by_penyakit = [];
                            foreach ($data_cf as $cf) {
                                $penyakit = $cf['nama_penyakit'];
                                if (!isset($cf_by_penyakit[$penyakit])) $cf_by_penyakit[$penyakit] = [];
                                $cf_by_penyakit[$penyakit][] = $cf;
                            }
                            $no = 1;
                            foreach ($cf_by_penyakit as $penyakit => $list_cf) {
                                $cf_combine = 0;
                                foreach ($list_cf as $i => $cf) {
                                    $cf_akhir = $cf['cf_akhir'];
                                    if ($i == 0) {
                                        $cf_combine = $cf_akhir;
                                        $rumus = "CFcombine = {$cf_akhir}";
                                    } else {
                                        $prev = $cf_combine;
                                        $cf_combine = $cf_combine + $cf_akhir * (1 - $cf_combine);
                                        $rumus = "CFcombine = " . number_format($prev, 6) . " + " . number_format($cf_akhir, 2) . " × (1 - " . number_format($prev, 6) . ") = " . number_format($cf_combine, 6);
                                    }
                            ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($penyakit) ?></td>
                                        <td><?= htmlspecialchars($cf['nama_gejala']) ?></td>
                                        <td class="text-center"><?= $cf['cf_pakar'] ?></td>
                                        <td class="text-center"><?= $cf['cf_user'] ?></td>
                                        <td class="text-center text-success"><?= number_format($cf_combine, 4) ?></td>
                                        <td><?= $rumus ?></td>
                                    </tr>
                                <?php
                                }
                                // Tampilkan hasil akhir combine per penyakit
                                ?>
                                <tr class="table-info">
                                    <td colspan="5" class="text-right font-weight-bold">Persentase combine pada penyakit (<?= htmlspecialchars($penyakit) ?>)</td>
                                    <td class="text-primary font-weight-bold"><?= number_format($cf_combine * 100, 2) ?>%</td>
                                    <td class="font-italic">CFcombine × 100 = <?= number_format($cf_combine, 4) ?> × 100 = <?= number_format($cf_combine * 100, 2) ?>%</td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="alert alert-info mt-3">
                        <strong>Hasil Akhir CF Combine:
                            <span class="text-primary">
                                <?php
                                // Tampilkan hasil combine terbesar
                                $max_cf = 0;
                                $max_penyakit = '';
                                foreach ($cf_by_penyakit as $penyakit => $list_cf) {
                                    $cf_combine = 0;
                                    foreach ($list_cf as $i => $cf) {
                                        $cf_akhir = $cf['cf_akhir'];
                                        if ($i == 0) {
                                            $cf_combine = $cf_akhir;
                                        } else {
                                            $cf_combine = $cf_combine + $cf_akhir * (1 - $cf_combine);
                                        }
                                    }
                                    if ($cf_combine > $max_cf) {
                                        $max_cf = $cf_combine;
                                        $max_penyakit = $penyakit;
                                    }
                                }
                                echo htmlspecialchars($max_penyakit) . " (" . number_format($max_cf * 100, 2) . "%)";
                                ?>
                            </span>
                        </strong>
                        <br>
                        <small>Semakin tinggi persentase, semakin besar kemungkinan Anda mengalami penyakit tersebut.</small>
                    </div>
                </div>
                <div class="border p-3">
                    <h5 class="font-weight-bold text-primary">Solusi</h5>
                    <?php
                    // Ambil solusi penyakit dengan CF combine tertinggi
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
                    } else {
                        echo '<div class="alert alert-info">Belum ada hasil diagnosa untuk menampilkan solusi.</div>';
                    }
                    ?>
                    <!-- <form action="../admin/history.php" method="post" class="mt-3">
                        <input type="hidden" name="no_regdiagnosa" value="<?= htmlspecialchars($no_regdiagnosa_url) ?>">
                        <input type="hidden" name="id_admin" value="<?= $id_admin ?>">
                        <input type="hidden" name="penyakit" value="<?= htmlspecialchars($max_penyakit) ?>">
                        <input type="hidden" name="persentase" value="<?= number_format($max_cf * 100, 2) ?>">

                        <button type="submit" class="btn btn-success mt-2">
                            <i class="fas fa-save"></i> Simpan ke Riwayat
                        </button>
                    </form> -->

                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include 'footer.php'; ?>