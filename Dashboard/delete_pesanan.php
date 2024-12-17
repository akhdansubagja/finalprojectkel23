<?php
// delete_pesanan.php

session_start(); // Memulai sesi
require_once '../backend/koneksi.php'; // Koneksi ke database

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Periksa apakah parameter 'id' ada pada URL
if (isset($_GET['id'])) {
    $id_pesanan = intval($_GET['id']); // Mengamankan input dengan intval

    // Query untuk menghapus pesanan berdasarkan ID
    $sql = "DELETE FROM pesanan WHERE id_pesanan = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id_pesanan); // Bind parameter 'id'
        if ($stmt->execute()) {
            echo "<script>alert('Pesanan berhasil dihapus.');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menghapus pesanan.');</script>";
        }
        $stmt->close(); // Tutup statement
    } else {
        echo "<script>alert('Terjadi kesalahan dalam memproses permintaan.');</script>";
    }
} else {
    echo "<script>alert('ID pesanan tidak valid.');</script>";
}

// Redirect ke halaman kelola pesanan
echo "<script>window.location.href = 'kelola_pesanan.php';</script>";

$conn->close(); // Tutup koneksi database
?>
