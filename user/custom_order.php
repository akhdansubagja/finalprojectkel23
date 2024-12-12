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

// Ambil data user dari session
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT name, email FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Ambil ID paket dari URL dan pastikan valid
if (isset($_GET['id_paket']) && is_numeric($_GET['id_paket'])) {
    $id_paket = intval($_GET['id_paket']);
} else {
    die("Paket tidak ditemukan.");
}

// Ambil data paket berdasarkan ID
$sql = "SELECT * FROM paket WHERE id_paket = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_paket);
$stmt->execute();
$paket = $stmt->get_result()->fetch_assoc();

if (!$paket) {
    die("Paket tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama_pemesan = $_POST['nama_pemesan'];
    $email = $_POST['email'];
    $jumlah_peserta = $_POST['jumlah_peserta'];
    $preferensi_hotel = $_POST['preferensi_hotel'];
    $tanggal_perjalanan = $_POST['tanggal_perjalanan'];
    $aktivitas_tambahan = $_POST['aktivitas_tambahan'];

    // Hitung harga custom (misalnya, harga paket per orang dikali jumlah peserta)
    $harga_custom = $paket['harga'] * $jumlah_peserta;

    // Query untuk memasukkan pesanan custom ke tabel pesanan_custom
    $insert_sql = "INSERT INTO pesanan_custom (id_user, id_paket, nama_pemesan, email, jumlah_peserta, preferensi_hotel, tanggal_perjalanan, aktivitas_tambahan, harga_custom, status_pesanan)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";

    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("iississss", $user_id, $id_paket, $nama_pemesan, $email, $jumlah_peserta, $preferensi_hotel, $tanggal_perjalanan, $aktivitas_tambahan, $harga_custom);
    
    if ($insert_stmt->execute()) {
        // Pesanan berhasil disimpan
        echo '<div class="alert alert-success" role="alert">Pesanan custom Anda berhasil dibuat! Kami akan menghubungi Anda untuk konfirmasi lebih lanjut.</div>';
    } else {
        // Pesanan gagal
        echo '<div class="alert alert-danger" role="alert">Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesan Paket Custom - <?= htmlspecialchars($paket['nama_paket']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Pesan Paket Custom - <?= htmlspecialchars($paket['nama_paket']) ?></h1>

        <form action="custom_order.php?id_paket=<?= $id_paket ?>" method="POST">
            <input type="hidden" name="id_paket" value="<?= $id_paket ?>">
            <input type="hidden" name="user_id" value="<?= $user_id ?>">

            <div class="mb-3">
                <label for="nama_pemesan" class="form-label">Nama Pemesan</label>
                <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" value="<?= htmlspecialchars($user['name']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>
            </div>

            <div class="mb-3">
                <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="1" required>
            </div>

            <div class="mb-3">
                <label for="preferensi_hotel" class="form-label">Preferensi Hotel</label>
                <textarea class="form-control" id="preferensi_hotel" name="preferensi_hotel" rows="3" placeholder="Masukkan preferensi hotel Anda (misalnya: tipe kamar, lokasi, dll.)"></textarea>
            </div>

            <div class="mb-3">
                <label for="tanggal_perjalanan" class="form-label">Tanggal Perjalanan</label>
                <input type="date" class="form-control" id="tanggal_perjalanan" name="tanggal_perjalanan" required>
            </div>

            <div class="mb-3">
                <label for="aktivitas_tambahan" class="form-label">Aktivitas Tambahan</label>
                <textarea class="form-control" id="aktivitas_tambahan" name="aktivitas_tambahan" rows="3" placeholder="Masukkan aktivitas tambahan yang Anda inginkan (misalnya: city tour, snorkeling, dll.)"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Pesan Paket Custom</button>
        </form>

        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
