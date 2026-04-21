<?php
include 'koneksi.php';
session_start();
include 'autentikasi.php'; 

if ($role_saat_ini == 'Kasir') {
    header("Location: dashboard.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = mysqli_query($conn, "SELECT * FROM medicines WHERE id = '$id'");
    $data = mysqli_fetch_array($query);

    if (!$data) {
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}

// --- LOGIKA UPDATE ---
if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_obat']);
    $kat  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $qty  = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $exp  = mysqli_real_escape_string($conn, $_POST['expired']);
    // Tambahan untuk supplier
    $supp = mysqli_real_escape_string($conn, $_POST['supplier']);
    $wa   = mysqli_real_escape_string($conn, $_POST['wa_supplier']);

    // Update query menyertakan supplier dan wa_supplier
    $sql = "UPDATE medicines SET 
            nama_obat='$nama', 
            kategori='$kat', 
            jumlah='$qty', 
            expired_date='$exp',
            supplier='$supp',
            wa_supplier='$wa' 
            WHERE id='$id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Data Berhasil Diupdate!'); window.location='dashboard.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Obat - Pharma Stock</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-6">

    <div class="w-full max-w-md bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200 border border-slate-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-600">
                <i class="fas fa-edit text-xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Edit Data Obat</h2>
        </div>
        
        <form method="POST" class="space-y-4">
            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 mb-1 block">Nama Obat</label>
                <input type="text" name="nama_obat" value="<?php echo htmlspecialchars($data['nama_obat']); ?>" required 
                    class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 mb-1 block">Kategori</label>
                <select name="kategori" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 transition appearance-none">
                    <option value="Obat Bebas" <?php if($data['kategori'] == 'Obat Bebas') echo 'selected'; ?>>Obat Bebas</option>
                    <option value="Obat Keras" <?php if($data['kategori'] == 'Obat Keras') echo 'selected'; ?>>Obat Keras</option>
                    <option value="Obat Tradisional" <?php if($data['kategori'] == 'Obat Tradisional') echo 'selected'; ?>>Obat Tradisional</option>
                </select>
            </div>

            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 mb-1 block">Nama Supplier</label>
                <input type="text" name="supplier" value="<?php echo htmlspecialchars($data['supplier']); ?>" placeholder="Contoh: PT. Medika" required 
                    class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>

            <div>
                <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 mb-1 block">WhatsApp Supplier</label>
                <input type="text" name="wa_supplier" value="<?php echo htmlspecialchars($data['wa_supplier']); ?>" placeholder="6281234..." required 
                    class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 mb-1 block">Stok</label>
                    <input type="number" name="jumlah" value="<?php echo $data['jumlah']; ?>" required 
                        class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase ml-2 mb-1 block">Expired</label>
                    <input type="date" name="expired" value="<?php echo $data['expired_date']; ?>" required 
                        class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none focus:ring-2 focus:ring-blue-500 text-xs transition">
                </div>
            </div>

            <div class="pt-6 flex items-center gap-4">
                <a href="dashboard.php" class="flex-1 text-center py-4 text-slate-400 font-bold hover:text-slate-600 transition uppercase text-sm tracking-widest">
                    Batal
                </a>
                <button name="update" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-black shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-95 transition uppercase text-sm tracking-widest">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</body>
</html>