<?php
// Koneksi ke database
require_once 'koneksi.php'; // Pastikan ini mengarah ke file koneksi database Anda

// Fungsi untuk menambahkan notifikasi
function addNotification($message, $id_pesanan) {
    global $conn; // Menggunakan koneksi global

    $stmt = $conn->prepare("INSERT INTO notifications (message, id_pesanan, created_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("si", $message, $id_pesanan);
    
    if ($stmt->execute()) {
        // Berhasil menambahkan notifikasi
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Fungsi untuk mengambil notifikasi
function getNotifications() {
    global $conn; // Menggunakan koneksi global

    $result = $conn->query("SELECT * FROM notifications ORDER BY created_at DESC");
    $notifications = [];

    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    return $notifications;
}

// Fungsi untuk menandai notifikasi sebagai dibaca
function markAsRead($id_notif) {
    global $conn; // Menggunakan koneksi global

    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id_notif = ?");
    $stmt->bind_param("i", $id_notif);
    
    if ($stmt->execute()) {
        // Berhasil menandai notifikasi sebagai dibaca
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Contoh penggunaan (uncomment untuk menguji)
// addNotification("Pesanan baru telah diterima untuk 50 orang.", $id_pesanan);
// $notifications = getNotifications();
// print_r($notifications);
?>
