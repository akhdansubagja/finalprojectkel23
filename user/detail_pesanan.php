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

// Query untuk mengambil detail pesanan
$sql = "
    SELECT p.id AS id_pesanan, p.tanggal_pesan, pk.nama_paket, pk.tujuan, pk.durasi_hari, p.harga_total, p.tanggal_perjalanan, p.jumlah_peserta, p.nama_pemesan, p.email, p.foto_transfer 
    FROM pesanan p 
    JOIN paket pk ON p.id_paket = pk.id_paket 
    WHERE p.id = '$id_pesanan'
";
$result = $conn->query($sql);

// Periksa apakah pesanan ditemukan
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
    <title>Detail Pesanan - <?= htmlspecialchars($row['nama_paket']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Detail Pesanan</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['nama_paket']) ?></h5>
                <p><strong>Tanggal Pesan:</strong> <?= htmlspecialchars($row['tanggal_pesan']) ?></p>
                <p><strong>Tanggal Berangkat:</strong> <?= htmlspecialchars($row['tanggal_perjalanan']) ?></p>
                <p><strong>Tujuan:</strong> <?= htmlspecialchars($row['tujuan']) ?></p>
                <p><strong>Durasi:</strong> <?= htmlspecialchars($row['durasi_hari']) ?> Hari</p>
                <p><strong>Harga:</strong> Rp <?= number_format($row['harga_total'], 2, ',', '.') ?></p>
                <p><strong>Jumlah Peserta:</strong> <?= htmlspecialchars($row['jumlah_peserta']) ?></p>
                <p><strong>Nama Pemesan:</strong> <?= htmlspecialchars($row['nama_pemesan']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
                <p><strong>Foto Transfer:</strong> 
                    <?php if (!empty($row['foto_transfer'])): ?>
                        <img src="../uploads/bukti_pembayaran/<?= htmlspecialchars($row['foto_transfer']) ?>" alt="Foto Transfer" class="img-fluid" style="max-width: 300px;">
                    <?php else: ?>
                        Tidak ada foto transfer.
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <a href="history.php" class="btn btn-secondary mt-3">Kembali ke Riwayat Pesanan</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>