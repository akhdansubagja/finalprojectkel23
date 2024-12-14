<?php
session_start();
require_once '../backend/koneksi.php';
require_once '../backend/notification.php'; // Tambahkan ini untuk mengakses fungsi notifikasi

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    markAsRead($id); // Panggil fungsi untuk menandai notifikasi sebagai dibaca
}

// Redirect kembali ke halaman dashboard
header("Location: admin.php");
exit();
?>
