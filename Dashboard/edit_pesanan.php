<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) { // Ganti 'user_id' dengan nama variabel sesi yang Anda gunakan
    header("Location: ../login.html"); // Arahkan ke halaman login jika belum login
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

// Ambil ID pesanan dari parameter URL
$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pesanan berdasarkan ID
$sql = "SELECT * FROM pesanan WHERE id = '$id_pesanan'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    die("Pesanan tidak ditemukan.");
}

$row = $result->fetch_assoc();

// Proses form jika ada pengiriman
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status_pesanan = $_POST['status_pesanan'];

    // Update status pesanan
    $update_sql = "UPDATE pesanan SET status_pesanan = '$status_pesanan' WHERE id = '$id_pesanan'";
    if ($conn->query($update_sql) === TRUE) {
        // Kirim tiket ke email pengguna
        $to = $row['email'];
        $subject = "Tiket Pesanan Anda";
        $message = "Terima kasih telah memesan. Berikut adalah detail pesanan Anda:\n\n" .
                   "Nama Pemesan: " . $row['nama_pemesan'] . "\n" .
                   "Nama Paket: " . $row['id_paket'] . "\n" . // Anda mungkin ingin mengganti ini dengan nama paket yang sesuai
                   "Status Pesanan: " . $status_pesanan . "\n" .
                   "Tanggal Pesan: " . $row['tanggal_pesan'] . "\n" .
                   "Tanggal Perjalanan: " . $row['tanggal_perjalanan'] . "\n\n" .
                   "Terima kasih!";
        
        // Kirim email
        mail($to, $subject, $message);

        // Redirect ke halaman kelola pesanan dengan status sukses
        header("Location: kelola_pesanan.php?status=success");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Edit Pesanan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="kelola_pesanan.php">Kelola Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>    
    <div class="container my-5">
        <h1 class="text-center mb-4">Edit Pesanan</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="status_pesanan" class="form-label">Status Pesanan</label>
                <select class="form-control" id="status_pesanan" name="status_pesanan" required>
                    <option value="Pending" <?= $row['status_pesanan'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Dikonfirmasi" <?= $row['status_pesanan'] == 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="Dibatalkan" <?= $row['status_pesanan'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                    <option value="Selesai" <?= $row['status_pesanan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>