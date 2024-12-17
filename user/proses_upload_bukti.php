<?php
include '../backend/koneksi.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    die("Anda harus login untuk mengunggah bukti pembayaran.");
}

// Cek apakah ID pesanan diterima
if (!isset($_POST['id_pesanan']) || !is_numeric($_POST['id_pesanan'])) {
    die("ID pesanan tidak valid.");
}

$id_pesanan = intval($_POST['id_pesanan']);

// Cek apakah file diunggah
if (isset($_FILES['foto_transfer']) && $_FILES['foto_transfer']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['foto_transfer']['tmp_name'];
 // Nama file asli
    $fileName = $_FILES['foto_transfer']['name'];
    // Ekstensi file
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Nama file baru
    $newFileName = $id_pesanan . "_" . time() . "." . $fileExtension;

    // Jalur upload
    $uploadPath = '../uploads/bukti_pembayaran/';

    // Pindahkan file ke folder upload
    if (move_uploaded_file($fileTmpPath, $uploadPath . $newFileName)) {
        // Perbarui database dengan nama file baru
        $sql = "UPDATE pesanan SET foto_transfer = ? WHERE id_pesanan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newFileName, $id_pesanan);

        if ($stmt->execute()) {
            echo "Bukti pembayaran berhasil diunggah.";
        } else {
            echo "Gagal mengunggah bukti pembayaran: " . $stmt->error;
        }
    } else {
        echo "Gagal mengunggah file.";
    }
} else {
    echo "Tidak ada file yang diunggah.";
}

// Tutup koneksi
$conn->close();
?>