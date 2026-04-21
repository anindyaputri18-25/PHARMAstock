<?php
include 'koneksi.php';
session_start();

$user_session = $_SESSION['user'];
$q = mysqli_query($conn, "SELECT role FROM users WHERE username = '$user_session'");
$data = mysqli_fetch_assoc($q);

if ($data['role'] != 'Pending') {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Menunggu Persetujuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">
    <div class="max-w-md w-full bg-white p-10 rounded-[3rem] shadow-xl shadow-slate-200 border border-slate-100 text-center">
        <div class="w-20 h-20 bg-orange-100 text-orange-500 rounded-3xl flex items-center justify-center mx-auto mb-6 text-3xl animate-bounce">
            <i class="fas fa-clock"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-800 uppercase mb-2">Akses Tertunda</h2>
        <p class="text-slate-500 mb-8 leading-relaxed">
            Halo <span class="font-bold text-slate-700"><?php echo $_SESSION['user']; ?></span>, akun Anda sedang menunggu verifikasi dari Admin. Halaman stok obat akan muncul setelah akun Anda disetujui.
        </p>
        
        <div class="space-y-3">
            <a href="logout.php" class="block w-full py-4 bg-slate-800 text-white rounded-2xl font-bold hover:bg-slate-900 transition uppercase text-xs tracking-widest">
                Keluar & Cek Nanti
            </a>
        </div>
    </div>
</body>
</html>