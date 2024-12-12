<?php
session_start();
require_once '../backend/koneksi.php'; // Koneksi database

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID paket dari URL
if (isset($_GET['id'])) {
    $id_paket = intval($_GET['id']); // Pastikan ID adalah angka

    // Query untuk menghapus paket
    $sql = "DELETE FROM paket WHERE id_paket = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_paket);

    if ($stmt->execute()) {
        // Redirect dengan status delete_success
        $stmt->close(); // Close the statement before redirecting
        header("Location: admin.php?status=delete_success"); // Ubah status menjadi delete_success
        exit(); // Pastikan untuk keluar setelah redirect
    } else {
        // Redirect dengan status error
        $stmt->close(); // Close the statement before redirecting
        header("Location: admin.php?status=error");
        exit(); // Pastikan untuk keluar setelah redirect
    }
} else {
    // Redirect dengan status invalid jika ID tidak ada
    header("Location: admin.php?status=invalid");
}

$conn->close();
?>