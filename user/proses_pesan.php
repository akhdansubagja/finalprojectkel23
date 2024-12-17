<?php
include '../backend/koneksi.php';
require_once '../backend/notification.php'; // Tambahkan ini untuk mengakses fungsi notifikasi
session_start(); // Memulai sesi

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login untuk melakukan pemesanan.");
}

// Ambil data dari form
$id_paket = $_POST['id_paket'];
$user_id = $_POST['user_id'];
$nama_pemesan = $_POST['nama_pemesan'];
$email = $_POST['email'];
$jumlah_peserta = $_POST['jumlah_peserta'];
$tanggal_perjalanan = $_POST['tanggal_perjalanan'];

// Ambil harga paket dari database
$sql_harga = "SELECT harga FROM paket WHERE id_paket = ?";
$stmt_harga = $conn->prepare($sql_harga);
$stmt_harga->bind_param("i", $id_paket);
$stmt_harga->execute();
$result_harga = $stmt_harga->get_result();

if ($result_harga->num_rows > 0) {
    $row_harga = $result_harga->fetch_assoc();
    $harga_per_orang = $row_harga['harga'];

    // Hitung total harga
    $harga_total = $harga_per_orang * $jumlah_peserta;

    // Masukkan data pesanan ke tabel pesanan
    $sql_insert = "INSERT INTO pesanan (id_paket, user_id, nama_pemesan, email, jumlah_peserta, harga_total, tanggal_pesan, tanggal_perjalanan) 
                   VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("iississ", $id_paket, $user_id, $nama_pemesan, $email, $jumlah_peserta, $harga_total, $tanggal_perjalanan);
    
    if ($stmt_insert->execute()) {
        // Ambil id_pesanan yang baru saja dimasukkan
        $id_pesanan = $stmt_insert->insert_id;

        // Tambahkan notifikasi setelah pemesanan berhasil
        addNotification("Pesanan baru telah diterima untuk paket ID: " . htmlspecialchars($id_paket) . " oleh " . htmlspecialchars($nama_pemesan), $id_pesanan);

        // Redirect ke halaman pembayaran
        header("Location: pembayaran.php?id_pesanan=" . $id_pesanan); // Mengalihkan ke halaman pembayaran
        exit();
    } else {
        die("Gagal melakukan pemesanan: " . $conn->error);
    }
} else {
    die("Paket tidak ditemukan.");
}
?>
