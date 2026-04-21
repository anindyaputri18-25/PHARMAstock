<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Ambil data utama dan bersihkan dari karakter aneh
    $nama_racikan = mysqli_real_escape_string($conn, $_POST['nama_racikan']);
    $tipe_racikan = $_POST['tipe_racikan'];
    $stok_racikan = (int)$_POST['stok_racikan'];
    $keterangan   = mysqli_real_escape_string($conn, $_POST['keterangan']);
    
    // Buat kode unik otomatis
    $kode_racikan = "RAC-" . strtoupper(substr(md5(time()), 0, 5));

    // Cek apakah ada obat yang dicentang
    if (!isset($_POST['obat_dipilih']) || empty($_POST['obat_dipilih'])) {
        echo "<script>alert('Pilih minimal satu bahan obat!'); window.history.back();</script>";
        exit();
    }

    // 2. Insert ke tabel 'racikan' terlebih dahulu
    $sql_racikan = "INSERT INTO racikan (nama_racikan, kode_racikan, tipe_racikan, stok_racikan, keterangan) 
                    VALUES ('$nama_racikan', '$kode_racikan', '$tipe_racikan', '$stok_racikan', '$keterangan')";
    
    if (mysqli_query($conn, $sql_racikan)) {
        // Ambil ID racikan yang baru saja dibuat
        $id_racikan_baru = mysqli_insert_id($conn);
        
        $obat_dipilih = $_POST['obat_dipilih']; // Isinya array ID obat
        $semua_jumlah = $_POST['jumlah_pakai']; // Isinya array jumlah (indexnya ID obat)

        foreach ($obat_dipilih as $id_obat) {
            // Ambil jumlah pakai untuk ID obat ini, pastikan angkanya valid
            $jml = isset($semua_jumlah[$id_obat]) ? (int)$semua_jumlah[$id_obat] : 0;

            if ($jml > 0) {
                // 3. Masukkan ke tabel detail racikan
                $sql_detail = "INSERT INTO racikan_detail (id_racikan, id_obat, jumlah_digunakan) 
                               VALUES ('$id_racikan_baru', '$id_obat', '$jml')";
                mysqli_query($conn, $sql_detail);

                // 4. KURANGI STOK DI TABEL MEDICINES
                // Pastikan nama tabel kamu 'medicines' dan kolomnya 'jumlah'
                $sql_update_stok = "UPDATE medicines SET jumlah = jumlah - $jml WHERE id = '$id_obat'";
                mysqli_query($conn, $sql_update_stok);
            }
        }

        echo "<script>
                alert('Berhasil! Racikan tersimpan dan stok obat telah dikurangi.'); 
                window.location='racikan_obat.php';
              </script>";
    } else {
        // Jika insert ke tabel racikan gagal, tampilkan error MySQL-nya apa
        echo "Error pada Tabel Racikan: " . mysqli_error($conn);
    }
} else {
    header("Location: tambah_racikan.php");
}
?>