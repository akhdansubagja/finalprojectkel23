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

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];

    // Ambil detail pesanan
    $sql = "SELECT pesanan.*, paket.nama_paket, users.name AS nama_user 
            FROM pesanan 
            JOIN paket ON pesanan.id_paket = paket.id_paket
            JOIN users ON pesanan.user_id = users.user_id
            WHERE pesanan.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pesanan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pesanan = $result->fetch_assoc();
    } else {
        die("Pesanan tidak ditemukan.");
    }
} else {
    die("ID pesanan tidak diberikan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Detail Pesanan</h1>

        <table class="table table-bordered">
            <tr>
                <th>ID Pesanan</th>
                <td><?php echo htmlspecialchars($pesanan['id']); ?></td>
            </tr>
            <tr>
                <th>Nama Pemesan</th>
                <td><?php echo htmlspecialchars($pesanan['nama_user']); ?></td>
            </tr>
            <tr>
                <th>Paket</th>
                <td><?php echo htmlspecialchars($pesanan['nama_paket']); ?></td>
            </tr>
            <tr>
                <th>Status Pesanan</th>
                <td><?php echo htmlspecialchars($pesanan['status_pesanan']); ?></td>
            </tr>
            <tr>
                <th>Tanggal Pemesanan</th>
                <td><?php echo htmlspecialchars($pesanan['tanggal_pesan']); ?></td>
            </tr>
            <tr>
                <th>jumlah Peserta</th>
                <td><?php echo htmlspecialchars($pesanan['jumlah_peserta']); ?></td>
            </tr>
            <tr>
                <th>Catatan</th>
                <td><?php echo htmlspecialchars($pesanan['catatan']); // Jika ada kolom catatan ?></td>
            </tr>
        </table>

        <div class="text-center">
            <a href="kelola_pesanan.php" class="btn btn-primary">Kembali</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>