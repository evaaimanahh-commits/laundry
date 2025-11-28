<?php
include '../koneksi.php';

$id          = $_POST['id'];
$pelanggan   = $_POST['pelanggan'];
$berat       = $_POST['berat'];
$tgl_selesai = $_POST['tgl_selesai'];
$status      = $_POST['status'];

// Ambil harga per kilo
$h = mysqli_query($koneksi, "SELECT harga_per_kilo FROM harga");
$harga_per_kilo = mysqli_fetch_assoc($h);

// Hitung total harga
$harga = $berat * $harga_per_kilo['harga_per_kilo'];

// Update data transaksi
mysqli_query($koneksi, "UPDATE transaksi 
    SET transaksi_pelanggan='$pelanggan',
        transaksi_harga='$harga',
        transaksi_berat='$berat',
        transaksi_tgl_selesai='$tgl_selesai',
        transaksi_status='$status'
    WHERE transaksi_id='$id'");

// Hapus data pakaian lama
mysqli_query($koneksi, "DELETE FROM pakaian WHERE pakaian_transaksi='$id'");

// Tambahkan data pakaian baru
if (!empty($_POST['jenis_pakaian'])) {
    $jenis_pakaian  = $_POST['jenis_pakaian'];
    $jumlah_pakaian = $_POST['jumlah_pakaian'];

    for ($x = 0; $x < count($jenis_pakaian); $x++) {
        if ($jenis_pakaian[$x] != "") {
            mysqli_query($koneksi, "INSERT INTO pakaian VALUES('', '$id', '$jenis_pakaian[$x]', '$jumlah_pakaian[$x]')");
        }
    }
}

header("Location: transaksi.php");
exit;
?>