<?php
// Koneksi ke database
require_once 'koneksi.php'; // Pastikan ini mengarah ke file koneksi database Anda

// Fungsi untuk menambahkan notifikasi
function addNotification($message) {
    global $conn; // Menggunakan koneksi global

    $stmt = $conn->prepare("INSERT INTO notifications (message) VALUES (?)");
    $stmt->bind_param("s", $message);
    
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
function markAsRead($id) {
    global $conn; // Menggunakan koneksi global

    $stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Berhasil menandai notifikasi sebagai dibaca
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Contoh penggunaan (uncomment untuk menguji)
// addNotification("Pesanan baru telah diterima untuk 50 orang.");
// $notifications = getNotifications();
// print_r($notifications);
?>
