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

// Ambil data user dari session (misalnya nama)
$user_name = $_SESSION['user_name']; // Pastikan session sudah menyimpan data nama pengguna

// Query untuk mengambil data dari tabel paket
$sql = "SELECT id_paket, nama_paket, tujuan, durasi_hari, harga, status_paket, foto, deskripsi FROM paket WHERE status_paket='Aktif'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard User - Paket Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard User</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto me-auto"> <!-- Tambahkan me-auto untuk mengatur margin otomatis di kanan -->
                <li class="nav-item center">
                    <span class="nav-link text-center">Selamat datang, <?= htmlspecialchars($user_name) ?>!</span> <!-- Tampilkan nama pengguna -->
                </li>
            </ul>
            <ul class="navbar-nav"> <!-- Buat navbar-nav terpisah untuk item lainnya -->
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profil</a> <!-- Link ke halaman profil -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="history.php">Riwayat Pesanan</a> <!-- Link ke halaman riwayat pesanan -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Backend/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="container my-5">
        <h1 class="text-center mb-4">Paket Tour Tersedia</h1>
        <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="col-md-4">
                    <div class="card mb-4">';

                // Menampilkan gambar paket
                if (!empty($row['foto'])) {
                    echo '<img src="../uploads/' . htmlspecialchars($row['foto']) . '" class="card-img-top" alt="Foto Paket">';
                } else {
                    echo '<img src="../uploads/default.jpg" class="card-img-top" alt="Foto Default">';
                }
                
                echo '
                        <div class="card-body">
                            <h5 class="card-title">' . htmlspecialchars($row['nama_paket']) . '</h5>
                            <p class="card-text">
                                <strong>Tujuan:</strong> ' . htmlspecialchars($row['tujuan']) . '<br>
                                <strong>Durasi:</strong> ' . htmlspecialchars($row['durasi_hari']) . ' Hari<br>
                                <strong>Harga:</strong> Rp ' . number_format($row['harga'], 2, ',', '.') . '<br>
                                <strong>Status:</strong> ' . htmlspecialchars($row['status_paket']) . '<br>
                                <strong>Deskripsi:</strong> ' . nl2br(htmlspecialchars($row['deskripsi'])) . '
                            </p>
                            <a href="detail.php?id_paket=' . htmlspecialchars($row['id_paket']) . '" class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>';
            }
        } else {
            echo '<p class="text-center">Tidak ada paket tersedia.</p>';
        } 
        ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js /bootstrap.bundle.min.js"></script>
</body>
</html>