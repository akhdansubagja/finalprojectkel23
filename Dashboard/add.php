<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) { // Ganti 'user_id' dengan nama variabel sesi yang Anda gunakan
    header("Location: ../login.html"); // Arahkan ke halaman login jika belum login
    exit();
}

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { // Menggunakan user_role
    header("Location: ../unauthorized.html"); // Ganti dengan halaman yang sesuai
    exit();
}

require_once '../backend/koneksi.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Atur header untuk mencegah caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // Untuk HTTP 1.1
header("Pragma: no-cache"); // Untuk HTTP 1.0
header("Expires: 0"); // Untuk semua

// Proses ketika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_paket = $_POST['nama_paket'];
    $tujuan = $_POST['tujuan'];
    $durasi_hari = $_POST['durasi_hari'];
    $harga = $_POST['harga'];
    $status_paket = $_POST['status_paket'];
    $foto = $_FILES['foto']['name']; // Nama file foto
    $deskripsi = $_POST['deskripsi'];

    // Menyimpan foto
    $target_dir = "../uploads/";
    $foto_name = time() . "_" . basename($_FILES['foto']['name']); // Menambahkan timestamp
    $target_file = $target_dir . $foto_name;
    move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);


    // Query untuk memasukkan data ke dalam database
    $sql = "INSERT INTO paket (nama_paket, tujuan, durasi_hari, harga, status_paket, foto, deskripsi)
            VALUES ('$nama_paket', '$tujuan', '$durasi_hari', '$harga', '$status_paket', '$target_file', '$deskripsi')";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php?status=success"); // Redirect ke halaman utama setelah berhasil
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

