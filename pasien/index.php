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
	<div class="card shadow rounded-4 border-0">
		<div class="card-body text-center p-5">
			<h4 class="text-primary fw-bold mb-4">✨ Selamat datang di Sistem Diagnosa Penyakit Pencernaan Anak UPTD PUSKESMAS KRESEK ✨</h4>

			<p class="fs-5">Halo <strong><?= htmlspecialchars($aa['nama_lengkap']) ?></strong>! 👋</p>

			<p>Sistem ini dirancang untuk membantu orang tua dan tenaga medis dalam mengenali gejala awal gangguan pencernaan pada anak-anak secara cepat dan tepat.</p>

			<p class="mb-4">🩺 Informasi yang diberikan berasal dari basis pengetahuan yang telah disusun oleh para ahli. Gunakan sistem ini sebagai langkah awal sebelum melakukan pemeriksaan langsung ke dokter.</p>

			<div class="alert alert-info rounded-4 py-3">
				📋 Saat ini terdapat <strong><?= $Pasien ?> pasien</strong> yang terdaftar dalam sistem kami.
			</div>

			<hr class="my-4">

			<p class="text-muted">
				<i class="fas fa-user-md me-2"></i> Kami siap membantu Anda dengan pelayanan terbaik!
			</p>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>