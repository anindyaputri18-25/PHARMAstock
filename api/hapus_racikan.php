<?php
include 'koneksi.php';
session_start();

$id = $_GET['id'];

// Menghapus racikan otomatis menghapus detail karena kita pakai 'ON DELETE CASCADE' di SQL
$query = mysqli_query($conn, "DELETE FROM racikan WHERE id_racikan = '$id'");

if ($query) {
    header("Location: racikan_obat.php?pesan=hapus");
} else {
    echo "Gagal menghapus.";
}
?>