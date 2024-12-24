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

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { // Menggunakan user_role
    header("Location: ../unauthorized.html"); // Ganti dengan halaman yang sesuai
    exit();
}

// Panggil fungsi untuk memperbarui status pesanan
updateOrderStatus($conn);

// Ambil ID pemesanan dari parameter URL
$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil detail pemesanan
$sql = "SELECT p.*, pk.nama_paket, u.no_hp 
        FROM pesanan p 
        JOIN paket pk ON p.id_paket = pk.id_paket 
        JOIN users u ON p.user_id = u.user_id 
        WHERE p.id_pesanan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Pesanan tidak ditemukan.");
}

$row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin.php">Manajemen Pesanan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="kelola_pesanan.php">Kelola Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Backend/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container my-5">
    <h1 class="text-center mb-4">Detail Pemesanan</h1>
    <table class="table table-bordered">
        <tr>
            <th>Nomor Pemesanan</th>
            <td><?= htmlspecialchars($row['id_pesanan']) ?></td>
        </tr>
        <tr>
            <th>Nama Pemesan</th>
            <td><?= htmlspecialchars($row['nama_pemesan']) ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?= htmlspecialchars($row['email']) ?></td>
        </tr>
        <tr>
            <th>No Handphone</th>
            <td><?= htmlspecialchars($row['no_hp']) ?></td>
        </tr>
        <tr>
            <th>Jumlah Peserta</th>
            <td><?= htmlspecialchars($row['jumlah_peserta']) ?></td>
        </tr>
        <tr>
            <th>Harga Total</th>
            <td>Rp <?= number_format($row['harga_total'], 2, ',', '.') ?></td>
        </tr>
        <tr>
            <th>Tanggal Pesan</th>
            <td><?= htmlspecialchars($row['tanggal_pesan']) ?></td>
        </tr>
        <tr>
            <th>Tanggal Perjalanan</th>
            <td><?= htmlspecialchars($row['tanggal_perjalanan']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= htmlspecialchars($row['status_pesanan']) ?></td>
        </tr>
        <tr>
            <th>Nama Paket</th>
            <td><?= htmlspecialchars($row['nama_paket']) ?></td>
        </tr>
        <tr>
            <th>Status Pembayaran</th>
            <td><?= htmlspecialchars($row['status_pembayaran']) ?></td>
        </tr>
        <tr>
            <th>Foto Transfer</th>
            <td>
                <?php if (!empty($row['foto_transfer'])): ?>
                    <img src="../uploads/bukti_pembayaran/<?= htmlspecialchars($row['foto_transfer']) ?>" class="img-fluid" alt="Foto Transfer">
                <?php else: ?>
                    Tidak ada foto.
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <a href="kelola_pesanan.php" class="btn btn-secondary">Kembali</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
