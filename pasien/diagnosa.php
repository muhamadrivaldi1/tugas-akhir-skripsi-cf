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
    <div class="card shadow p-5 mb-5">
        <div class="card-header">
            <h5 class="m-0 font-weight-bold text-primary">Diagnosa</h5>
        </div>
        <div class="card-body">
            <form action="diagnosa.php?aksi=diagnosa" method="post">
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gejala</th>
                            <th>Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($g = $gejalaRes->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td>Apakah Anda mengalami gejala <b><?= htmlspecialchars($g['nama_gejala']) ?></b>?</td>
                                <td>
                                    <!-- opsi default value 0  → jika user melewati, CF‑User = 0 -->
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
                <a href="index.php" class="btn btn-secondary mb-2">Batal</a>
                <button type="submit" class="btn btn-primary mb-2">Proses Diagnosa</button>
            </form>
        </div>

        <!-- ==================== HASIL ANALISA ==================== -->
        <?php if ($resultDiag && $resultDiag->num_rows): ?>
            <br><br>
            <center>
                <h2 class="font-weight-bold text-primary">
                    HASIL DIAGNOSA PENYAKIT PENCERNAAN DENGAN METODE CERTAINTY FACTOR
                </h2>
            </center>
            <hr>
            <h5 class="font-weight-bold">Rules</h5>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Penyakit</th>
                            <th>Gejala</th>
                            <th>CF Pakar (MB-MD)</th>
                            <th>CF User</th>
                            <th>Nilai CF&nbsp;(CF<sub>pakar</sub> × CF<sub>user</sub>)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1;
                        while ($row = $resultDiag->fetch_assoc()):
                            /* -------------------------------------------------
                       CF_pakar  = MB – MD  (di DB =  kolom nilai_gejala)
                       CF_user   = nilai_pasien
                       CF_akhir  = (MB‑MD) × CF_user
                    --------------------------------------------------*/
                            $cf_pakar  = (float)$row['nilai_gejala'];     // sudah MB‑MD
                            $cf_user   = (float)$row['nilai_pasien'];
                            $cf_akhir  = $cf_pakar * $cf_user;            // perkalian
                        ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['nama_penyakit']) ?></td>
                                <td class="text-center"><?= htmlspecialchars($row['nama_gejala']) ?></td>
                                <td class="text-center"><?= $cf_pakar ?></td>
                                <td class="text-center"><?= $cf_user ?></td>
                                <td class="text-center"><?= number_format($cf_akhir, 2) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>