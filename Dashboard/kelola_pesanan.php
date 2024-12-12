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

// Query untuk mengambil data dari tabel pesanan dan nama paket
$sql = "SELECT p.id, p.id_paket, p.user_id, p.nama_pemesan, p.email, p.jumlah_peserta, p.harga_total, p.tanggal_pesan, p.tanggal_perjalanan, p.status_pesanan, p.foto_transfer, pk.nama_paket 
FROM pesanan p 
JOIN paket pk ON p.id_paket = pk.id_paket"; // Mengambil nama paket dari tabel paket
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
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
                    <a class="nav-link active" href="#">Kelola Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Backend/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container my-5">
    <h1 class="text-center mb-4">Daftar Pesanan</h1>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Nomor Pemesanan</th>
                <th>Nama Pemesan</th>
                <th>Email</th>
                <th>Jumlah Peserta</th>
                <th>Harga Total</th>
                <th>Tanggal Pesan</th>
                <th>Tanggal Perjalanan</th>
                <th>Status</th>
                <th>Nama Paket</th> <!-- Kolom untuk nama paket -->
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $fotoPath = '../uploads/bukti_pembayaran/' . htmlspecialchars($row['foto_transfer']);
                    echo '<tr>
                            <td>' . $row['id'] . '</td>
                            <td>' . htmlspecialchars($row['nama_pemesan']) . '</td>
                            <td>' . htmlspecialchars($row['email']) . '</td>
                            <td>' . htmlspecialchars($row['jumlah_peserta']) . '</td>
                            <td>Rp ' . number_format($row['harga_total'], 2, ',', '.') . '</td>
                            <td>' . htmlspecialchars($row['tanggal_pesan']) . '</td>
                            <td>' . htmlspecialchars($row['tanggal_perjalanan']) . '</td>
                            <td>' . (isset($row['status_pesanan']) ? htmlspecialchars($row['status_pesanan']) : 'Tidak ada status') . '</td>
                            <td>' . htmlspecialchars($row['nama_paket']) . '</td> <!-- Tampilkan nama paket -->
                            <td>
                                <button type="button" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#fotoModal' . $row['id'] . '">
                                    <i class="bi bi-image" style="font-size: 1.5rem;"></i>
                                </button>
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Aksi">
                                    <a href="edit_pesanan.php?id=' . htmlspecialchars($row['id']) . '" class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <a href="delete_pesanan.php?id=' . htmlspecialchars($row['id']) . '" 
                                       class="btn btn-danger btn-sm" 
                                       onclick="return confirm(\'Yakin ingin menghapus pesanan ini?\')">
                                        <i class="bi bi-trash-fill"></i>
                                    </a>
                                </div>
                            </td>
                          </tr>';
                    
                    // Modal untuk foto
                    echo '<div class="modal fade" id="fotoModal' . $row['id'] . '" tabindex="-1" aria-labelledby="fotoModalLabel' . $row['id'] . '" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="fotoModalLabel' . $row['id'] . '">Foto Transfer</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="' . $fotoPath . '" class="img-fluid" alt="Foto Transfer">
                                    </div>
                                </div>
                            </div>
                          </div>';
                }
            } else {
                echo '<tr><td colspan="10" class="text-center">Tidak ada pesanan tersedia.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
