<?php
include 'header.php';
include '../assets/conn/config.php';
?>

<div class="container my-5">

    <?php
    $gejala = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_gejala"));
    $pasien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_pasien"));
    $penyakit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM tbl_penyakit"));

    $maxValue = max($penyakit['total'], $gejala['total'], $pasien['total']);
    if ($maxValue == 0) {
        $maxValue = 1;
    }
    ?>

    <div class="row justify-content-center">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow rounded-4 text-center p-3">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-disease fa-3x text-primary"></i>
                    </div>
                    <h6 class="text-uppercase text-primary fw-bold">Total Penyakit</h6>
                    <h4 class="fw-bold"><?= $penyakit['total'] ?> Penyakit</h4>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow rounded-4 text-center p-3">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-notes-medical fa-3x text-success"></i>
                    </div>
                    <h6 class="text-uppercase text-success fw-bold">Total Gejala</h6>
                    <h4 class="fw-bold"><?= $gejala['total'] ?> Gejala</h4>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow rounded-4 text-center p-3">
                <div class="card-body">
                    <div class="mb-2">
                        <i class="fas fa-users fa-3x text-danger"></i>
                    </div>
                    <h6 class="text-uppercase text-danger fw-bold">Total Pasien</h6>
                    <h4 class="fw-bold"><?= $pasien['total'] ?> Pasien</h4>
                </div>
            </div>
        </div>
    </div>
    <hr class="my-4">

    <!-- Grafik CSS Murni -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow rounded-4 p-4">
                <h6 class="mb-4 text-center fw-bold">Grafik Data</h6>
                <div class="d-flex align-items-end justify-content-around" style="height: 250px;">
                    <?php
                    function renderBar($label, $value, $color, $maxValue, $barMaxHeight)
                    {
                        $height = ($value / $maxValue) * $barMaxHeight;
                        $height = $height < 20 ? 20 : $height;
                        echo "
                            <div class='text-center'>
                                <div style='background:{$color}; width:50px; height:{$height}px; border-radius:8px; margin:auto;'></div>
                                <div class='mt-2 fw-bold'>{$label} <br> ({$value})</div>
                            </div>
                        ";
                    }

                    renderBar("Penyakit", $penyakit['total'], "#3498db", $maxValue, 200);
                    renderBar("Gejala", $gejala['total'], "#27ae60", $maxValue, 200);
                    renderBar("Pasien", $pasien['total'], "#e74c3c", $maxValue, 200);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center my-4">
    <div class="col-md-4 text-center mb-3 d-grid gap-2">
        <a href="laporan-pasien-pdf.php" class="btn btn-danger d-flex align-items-center justify-content-center gap-2 rounded-pill">
            <i class="fas fa-file-pdf"></i> <span>Download Laporan PDF</span>
        </a>

        <a href="laporan-pasien-excel.php" class="btn btn-success d-flex align-items-center justify-content-center gap-2 rounded-pill">
            <i class="fas fa-file-excel"></i> <span>Download Laporan Excel</span>
        </a>
    </div>
</div>


<?php
include 'footer.php';
?>