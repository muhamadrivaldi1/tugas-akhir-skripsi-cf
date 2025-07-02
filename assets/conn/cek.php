<?php
// Cek koneksi
if (!isset($_SESSION['username'])) {
    header("location:../index.php");
}?>