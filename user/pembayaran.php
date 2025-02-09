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

// Atur header untuk mencegah caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // Untuk HTTP 1.1
header("Pragma: no-cache"); // Untuk HTTP 1.0
header("Expires: 0"); // Untuk semua

// Cek apakah ada ID pesanan yang diterima
if (!isset($_GET['id_pesanan']) || !is_numeric($_GET['id_pesanan'])) {
    die("Pesanan tidak ditemukan.");
}

$id_pesanan = intval($_GET['id_pesanan']);

// Ambil data pesanan dari database
$sql = "SELECT *, masa_pembayaran, status_pembayaran FROM pesanan WHERE id_pesanan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$result = $stmt->get_result();

// Pastikan pesanan ditemukan
if ($result->num_rows === 0) {
    die("Pesanan tidak ditemukan.");
}

$pesanan = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran - Pesanan #<?= htmlspecialchars($pesanan['id_pesanan']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Menghitung waktu mundur
        function startCountdown(endTime) {
            const countdownElement = document.getElementById('countdown');
            const end = new Date(endTime).getTime();

            const timer = setInterval(function() {
                const now = new Date().getTime();
                const distance = end - now;

                // Hitung waktu yang tersisa
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Tampilkan hasil dalam elemen countdown
                countdownElement.innerHTML = hours + " jam " + minutes + " menit " + seconds + " detik ";

                // Jika waktu habis, tampilkan pesan dan sembunyikan elemen
                if (distance < 0) {
                    clearInterval(timer);
                    countdownElement.innerHTML = "Waktu pembayaran telah habis.";
                    document.getElementById('uploadForm').style.display = 'none'; // Sembunyikan form upload
                    document.getElementById('bankList').style.display = 'none'; // Sembunyikan daftar bank
                }
            }, 1000);
        }

        // Ambil waktu batas pembayaran dari PHP
        window.onload = function() {
            const masaPembayaran = "<?= $pesanan['masa_pembayaran'] ?>";
            const statusPembayaran = "<?= $pesanan['status_pembayaran'] ?>";

            // Hentikan timer jika status pembayaran sudah dibayar
            if (statusPembayaran === 'Sudah Dibayar') {
                document.getElementById('countdown').innerHTML = "Pembayaran sudah dilakukan.";
                document.getElementById('uploadForm').style.display = 'none'; // Sembunyikan form upload
                document.getElementById('bankList').style.display = 'none'; // Sembunyikan daftar bank
            } else {
                startCountdown(masaPembayaran);
            }
        };
    </script>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Pembayaran Pesanan</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Detail Pesanan</h5>
                <p class="card-text"><strong>Nama Pemesan:</strong> <?= htmlspecialchars($pesanan['nama_pemesan']) ?></p>
                <p class="card-text"><strong>Email:</strong> <?= htmlspecialchars($pesanan['email']) ?></p>
                <p class="card-text"><strong>Jumlah Peserta:</strong> <?= htmlspecialchars($pesanan['jumlah_peserta']) ?></p>
                <p class="card-text"><strong>Tanggal Pesan:</strong> <?= htmlspecialchars($pesanan['tanggal_pesan']) ?></p>
                <p class="card-text"><strong>Tanggal Perjalanan:</strong> <?= htmlspecialchars($pesanan['tanggal_perjalanan']) ?></p>
                <p class="card-text"><strong>Harga Total:</strong> <?= number_format($pesanan['harga_total'], 2, ',', '.') ?></p>
                <p class="card-text"><strong>Foto Transfer:</strong> <?= htmlspecialchars($pesanan['foto_transfer']) ?: 'Belum ada' ?></p>
            </div>
        </div>

        <div class="mt-4" id="bankList">
            <h3>Daftar Bank</h3>
            <ul class="list-group">
                <li class="list-group-item">Bank Mandiri - 1234567890 Atas nama ihdihid</li>
                <li class="list-group-item">Bank BCA - 0987654321</li>
                <li class="list-group-item">Bank BRI - 1122334455</li>
                <li class="list-group-item">Bank BTN - 5566778899</li>
                <li class="list-group-item">Bank CIMB Niaga - 2233445566</li>
            </ul>
        </div>

        <div class="mt-5" id="uploadForm">
            <h3>Unggah Bukti Pembayaran</h3>
            <form action="proses_upload_bukti.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_pesanan" value="<?= $id_pesanan ?>">
                <div class="mb-3">
                    <label for="foto_transfer" class="form-label">Pilih Bukti Pembayaran</label>
                    <input type="file" class="form-control" id="foto_transfer" name="foto_transfer" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Unggah Bukti Pembayaran</button>
            </form>
        </div>

        <div class="mt-4">
            <h3>Waktu Pembayaran Tersisa</h3>
            <p id="countdown" class="text-danger"></p>
        </div>

        <div class="mt-4 text-center">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
