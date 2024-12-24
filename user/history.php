<?php
// history.php
session_start(); // Memulai sesi
require_once '../backend/koneksi.php'; // Koneksi database

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Panggil fungsi untuk memperbarui status pesanan
updateOrderStatus($conn);

// Ambil data user dari session (misalnya ID pengguna)
$user_id = $_SESSION['user_id']; // Pastikan session sudah menyimpan data ID pengguna

// Query untuk mengambil data pesanan user dengan join ke tabel paket
$sql = "
    SELECT p.id_pesanan AS id_pesanan, p.tanggal_pesan, pk.nama_paket, p.status_pesanan 
    FROM pesanan p 
    JOIN paket pk ON p.id_paket = pk.id_paket 
    WHERE p.user_id = '$user_id'
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Pesanan - Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Riwayat Pesanan</h1>
        <?php
        if ($result->num_rows > 0) {
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>ID Pesanan</th><th>Tanggal Pesan</th><th>Nama Paket</th><th>Status Paket</th><th>Aksi</th></tr></thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id_pesanan']) . '</td>';
                echo '<td>' . htmlspecialchars($row['tanggal_pesan']) . '</td>';
                echo '<td>' . htmlspecialchars($row['nama_paket']) . '</td>';
                echo '<td>' . htmlspecialchars($row['status_pesanan']) . '</td>';
                echo '<td><a href="detail_pesanan.php?id=' . htmlspecialchars($row['id_pesanan']) . '" class="btn btn-info btn-sm">Lihat Detail</a></td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p class="text-center">Tidak ada riwayat pesanan.</p>';
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>