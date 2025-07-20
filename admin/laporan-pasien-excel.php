<?php
include '../assets/conn/config.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_pasien_summary.xls");

// Ambil data total
$total_penyakit = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_penyakit"))['total'];
$total_gejala = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_gejala"))['total'];
$total_pasien = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tbl_pasien"))['total'];

echo "
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin-top: 40px;
        }
        .table-container {
            width: 60%;
            margin: 0 auto;
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .header-title {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class='table-container'>
        <div class='header-title'>
            <h2>UPTD PUSKESMAS KRESEK</h2>
            <p>Laporan Data Sistem Pakar Diagnosa Penyakit Pencernaan</p>
            <p>Dicetak pada: " . date('d-m-Y') . "</p>
        </div>

        <table>
            <tr>
                <th>Jenis Data</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>Total Penyakit</td>
                <td>{$total_penyakit}</td>
            </tr>
            <tr>
                <td>Total Gejala</td>
                <td>{$total_gejala}</td>
            </tr>
            <tr>
                <td>Total Pasien</td>
                <td>{$total_pasien}</td>
            </tr>
        </table>
    </div>
</body>
</html>
";
?>
