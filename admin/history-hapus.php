<?php
include '../assets/conn/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Hapus berdasarkan id_hasil
    mysqli_query($conn, "DELETE FROM tbl_hasil WHERE id_hasil = '$id'");
    header("Location: history.php");
} else {
    echo "ID tidak ditemukan.";
}
