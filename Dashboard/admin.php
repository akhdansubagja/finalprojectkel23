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

// Query untuk mengambil data dari tabel paket
$sql = "SELECT id_paket, nama_paket, tujuan, durasi_hari, harga, status_paket, foto, deskripsi FROM paket";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Paket Tour Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Manajemen Paket Tour</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Daftar Paket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kelola_pesanan.php">Pesanan Paket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../backend/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>    

<div class="container my-5">
    <h1 class="text-center mb-4">Daftar Paket Tour</h1>
    <!-- Notifikasi -->
    <?php if (isset($_GET['status'])): ?>
        <div class="alert alert-<?php echo ($_GET['status'] == 'success' || $_GET['status'] == 'edit_success' || $_GET['status'] == 'delete_success') ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
            <?php
            if ($_GET['status'] == 'success') {
                echo 'Paket berhasil ditambahkan.';
            } elseif ($_GET['status'] == 'edit_success') {
                echo 'Paket berhasil diedit.';
            } elseif ($_GET['status'] == 'delete_success') {
                echo 'Paket berhasil dihapus.';
            } elseif ($_GET['status'] == 'error') {
                echo 'Terjadi kesalahan saat memproses permintaan.';
            } elseif ($_GET['status'] == 'invalid') {
                echo 'ID paket tidak valid.';
            }
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            // Menghapus parameter status dari URL setelah 3 detik
            setTimeout(function() {
                const url = new URL(window.location);
                url.searchParams.delete('status');
                window.history.replaceState({}, document.title, url);
            }, 1000); // Ubah 3000 menjadi waktu dalam milidetik sesuai kebutuhan
        </script>
    <?php endif; ?>



    <!-- Tambah Paket -->
    <div class="mb-4 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Paket</button>
    </div>
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '
                <div class="col-md-4">
                    <div class="card mb-4">';

                // Cek jika foto ada dan valid
                if (!empty($row['foto']) && file_exists("../uploads/" . $row['foto'])) {
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
                                <strong>Harga:</strong> Rp ' . number_format($row['harga'], 2, ',', '.') . '/Orang<br>
                                <strong>Status:</strong> ' . htmlspecialchars($row['status_paket']) . '<br>
                                <strong>Deskripsi:</strong> ' . nl2br(htmlspecialchars($row['deskripsi'])) . '
                            </p>
                            <div class="d-flex justify-content-between">
                                <a href="edit.php?id=' . htmlspecialchars($row['id_paket']) . '" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete.php?id=' . htmlspecialchars($row['id_paket']) . '" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm(\'Yakin ingin menghapus paket ini?\')">Hapus</a>
                            </div>
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

<!-- Modal Tambah Paket -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="add.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Paket Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_paket" class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" id="nama_paket" name="nama_paket" required>
                    </div>
                    <div class="mb-3">
                        <label for="tujuan" class="form-label">Tujuan</label>
                        <input type="text" class="form-control" id="tujuan" name="tujuan" required>
                    </div>
                    <div class="mb-3">
                        <label for="durasi_hari" class="form-label">Durasi (Hari)</label>
                        <input type="number" class="form-control" id="durasi_hari" name="durasi_hari" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="status_paket" class="form-label">Status</label>
                        <select class="form-control" id="status_paket" name="status_paket">
                            <option value="Aktif">Aktif</option>
                            <option value="Nonaktif">Nonaktif</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi Paket</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Paket</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>