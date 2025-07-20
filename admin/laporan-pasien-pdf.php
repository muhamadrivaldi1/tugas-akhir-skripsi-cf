<?php
require '../assets/vendor/autoload.php'; // Composer autoload

require '../assets/vendor/setasign/fpdf/fpdf.php'; // FPDF utama

include '../assets/conn/config.php'; // Koneksi database Anda

// Membuat instance FPDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->AddPage();

// Logo (pastikan path relatif terhadap file ini)
$pdf->Image('../assets/img/puskesmas.jpeg', 10, 10, 20, 20);

// Judul
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(190, 10, 'UPTD PUSKESMAS KRESEK', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(190, 10, 'Laporan Data Sistem Pakar Diagnosa Penyakit Pencernaan', 0, 1, 'C');
$pdf->Cell(190, 10, 'Dicetak pada: ' . date('d-m-Y'), 0, 1, 'C');
$pdf->Ln(20);

// Header tabel
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(95, 10, 'Jenis Data', 1, 0, 'C');
$pdf->Cell(95, 10, 'Total', 1, 1, 'C');

// Ambil data dari database
$total_penyakit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_penyakit"))['total'];
$total_gejala = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_gejala"))['total'];
$total_pasien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_pasien"))['total'];

// Isi tabel
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(95, 10, 'Total Penyakit', 1, 0);
$pdf->Cell(95, 10, $total_penyakit, 1, 1);
$pdf->Cell(95, 10, 'Total Gejala', 1, 0);
$pdf->Cell(95, 10, $total_gejala, 1, 1);
$pdf->Cell(95, 10, 'Total Pasien', 1, 0);
$pdf->Cell(95, 10, $total_pasien, 1, 1);

// Output PDF otomatis download
$pdf->Output('D', 'laporan_summary.pdf');
?>
