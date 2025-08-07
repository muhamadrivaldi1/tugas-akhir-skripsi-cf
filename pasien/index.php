<?php
include 'header.php';
include '../assets/conn/config.php';

$username = $_SESSION['username'];
$det = mysqli_query($conn, "SELECT * FROM tbl_admin WHERE username='$username'");
$aa = mysqli_fetch_assoc($det);

$query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_pasien");
$data = mysqli_fetch_assoc($query);
$Pasien = $data['total'];
?>

<div class="container my-5">
	<div class="row justify-content-center">
		<div class="col-lg-10 col-xl-8">
			<div class="card shadow-lg border-0 rounded-4">
				<div class="card-body p-5 text-center">
					<h4 class="text-primary fw-bold mb-4">
						✨ Selamat Datang di <br>Sistem Diagnosa Penyakit Pencernaan Anak <br><small class="text-muted">UPTD Puskesmas Kresek</small> ✨
					</h4>

					<p class="fs-5 mb-1">Halo, <strong><?= htmlspecialchars($aa['nama_lengkap']) ?></strong> 👋</p>
					<p class="text-muted mb-4">
						Terima kasih telah menggunakan sistem ini. Kami hadir untuk membantu mengenali gejala gangguan pencernaan pada anak secara dini dan akurat.
					</p>

					<div class="alert alert-info rounded-3 py-3 mb-4">
						<i class="fas fa-users me-2"></i> Terdapat <strong><?= $Pasien ?> pasien</strong> yang telah terdaftar dalam sistem.
					</div>

					<div class="mb-4">
						<p class="mb-2"><i class="fas fa-stethoscope text-danger me-2"></i>Sistem berbasis pengetahuan ahli medis.</p>
						<p class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Langkah awal sebelum konsultasi dokter.</p>
						<p><i class="fas fa-shield-alt text-warning me-2"></i>Aman & Mudah digunakan kapan saja.</p>
					</div>

					<hr class="my-4">

					<p class="text-muted mb-0">
						<i class="fas fa-user-md me-2"></i> Tim medis kami siap membantu Anda dengan sepenuh hati!
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>