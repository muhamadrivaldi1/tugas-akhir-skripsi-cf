<?php
$host = "localhost"; // Server database
$user = "root"; // Username MySQL
$pass = ""; // Password MySQL (kosong jika default XAMPP)
$db   = "db_metode_cf"; // Nama database

// Membuat koneksi
$conn = mysqli_connect($host, $user, $pass, $db)or die("Tidak terkoneksi keserver")
?>