<?php
// koneksi.php

$host = 'localhost';   // Host where MySQL is running
$username = 'root';    // MySQL username
$password = '';        // MySQL password (empty for default XAMPP)
$database = 'db_paket';// Name of the database you want to connect to
$port = 3306;          // Custom MySQL port (as you mentioned 3306)

// Create connection with specified port
$conn = new mysqli($host, $username, $password, $database, $port);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// koneksi.php
function updateOrderStatus($conn) {
    $sql = "
        UPDATE pesanan 
        SET status_pesanan = 'Dibatalkan' 
        WHERE status_pesanan = 'Pending' AND masa_pembayaran < NOW() AND status_pembayaran = 'Belum Dibayar'
    ";

    if ($conn->query($sql) === TRUE) {
        // Status pesanan berhasil diperbarui
    } else {
        error_log("Error updating order status: " . $conn->error);
    }
}


// Remove echo for "Connected successfully"
// Use this for debugging only if needed:
// echo "Connected successfully";
?>
