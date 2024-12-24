<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

require_once '../backend/koneksi.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID pesanan dari parameter URL
$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil detail pesanan
$sql = "SELECT foto_transfer FROM pesanan WHERE id_pesanan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$result = $stmt->get_result();

// Periksa apakah pesanan ditemukan
if ($result->num_rows == 0) {
    die("Pesanan tidak ditemukan.");
}

$row = $result->fetch_assoc();
$pdf_file = '../uploads/tiket/tiket_' . $id_pesanan . '.pdf'; // Sesuaikan dengan path file PDF Anda

// Cek apakah file PDF ada
if (file_exists($pdf_file)) {
    // Set header untuk mengunduh file
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($pdf_file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($pdf_file));
    readfile($pdf_file);
    exit;
} else {
    die("File tidak ditemukan.");
}
?>
