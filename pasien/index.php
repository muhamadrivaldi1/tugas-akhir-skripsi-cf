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

<div class="container">
	<div class="card shadow mb-4">
		<div class="card-body text-center">
			<h4 class="text-primary font-weight-bold mb-3">✨ Selamat datang di sistem diagnosa penyakit pencernaan pada anak anak UPTD PUSKESMAS KRESEK ✨</h4>
			<p>Halo <strong><?php echo $aa['nama_lengkap']; ?></strong>! 👋</p>
			<p>Selamat datang di Sistem Pakar Diagnosa Penyakit Pencernaan Anak. Sistem ini dirancang untuk membantu orang tua dan tenaga medis dalam mengenali gejala awal gangguan pencernaan pada anak-anak secara cepat dan tepat.</p>
			<p>🩺 Informasi yang diberikan berasal dari basis pengetahuan yang telah disusun oleh para ahli. Gunakan sistem ini sebagai langkah awal sebelum melakukan pemeriksaan langsung ke dokter.</p>
			<p class="mt-3">📋 Saat ini terdapat <strong><?php echo $Pasien; ?> pasien</strong> yang terdaftar dalam sistem kami.</p>
			<hr>
			<p class="text-muted"><i class="fas fa-user-md"></i> Kami siap membantu Anda dengan pelayanan terbaik!</p>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>