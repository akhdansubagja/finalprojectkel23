<?php
include '../backend/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_customer = $conn->real_escape_string($_POST['nama_customer']);
    $email_customer = $conn->real_escape_string($_POST['email_customer']);
    $tujuan_custom = $conn->real_escape_string($_POST['tujuan_custom']);
    $jumlah_peserta = intval($_POST['jumlah_peserta']);
    $durasi_hari = intval($_POST['durasi_hari']);
    $fasilitas_tambahan = $conn->real_escape_string($_POST['fasilitas_tambahan']);
    $total_harga = floatval($_POST['total_harga']);

    $sql = "INSERT INTO pemesanan_custom (nama_customer, email_customer, tujuan_custom, jumlah_peserta, durasi_hari, fasilitas_tambahan, total_harga)
            VALUES ('$nama_customer', '$email_customer', '$tujuan_custom', $jumlah_peserta, $durasi_hari, '$fasilitas_tambahan', $total_harga)";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Pemesanan custom berhasil!'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
