<?php
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user_session = $_SESSION['user'];
$query_auth = mysqli_query($conn, "SELECT role FROM users WHERE username = '$user_session'");
$data_auth = mysqli_fetch_assoc($query_auth);
$role_saat_ini = $data_auth['role'];

$halaman_sekarang = basename($_SERVER['PHP_SELF']);
$akses_izin = ['dashboard.php', 'logout.php'];

if ($role_saat_ini == 'Pending') {
    if (!in_array($halaman_sekarang, $akses_izin)) {
        header("Location: dashboard.php");
        exit();
    }
}
?>