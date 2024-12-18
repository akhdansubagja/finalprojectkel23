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
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Ambil ID pesanan dari parameter URL
$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil detail pesanan
$sql = "
    SELECT p.id_pesanan AS id_pesanan, p.tanggal_pesan, pk.nama_paket, pk.tujuan, pk.durasi_hari, p.harga_total, p.tanggal_perjalanan, p.jumlah_peserta, p.nama_pemesan, p.email, p.foto_transfer, p.masa_pembayaran, p.status_pembayaran 
    FROM pesanan p 
    JOIN paket pk ON p.id_paket = pk.id_paket 
    WHERE p.id_pesanan = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$result = $stmt->get_result();

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

                // Jika waktu habis, tampilkan pesan
                if (distance < 0) {
                    clearInterval(timer);
                    countdownElement.innerHTML = "Waktu pembayaran telah habis.";
                    document.getElementById('uploadForm').style.display = 'none'; // Sembunyikan form upload
                }
            }, 1000);
        }

        // Ambil waktu batas pembayaran dari PHP
        window.onload = function() {
            const masaPembayaran = "<?= $row['masa_pembayaran'] ?>";
            const statusPembayaran = "<?= $row['status_pembayaran'] ?>";

            // Hentikan timer jika status pembayaran sudah dibayar
            if (statusPembayaran === 'Sudah Dibayar') {
                document.getElementById('countdown').innerHTML = "Pembayaran sudah dilakukan.";
                document.getElementById('uploadForm').style.display = 'none'; // Sembunyikan form upload
                document.getElementById('bankList').style.display = 'none'; // Sembunyikan form pembayaran

            } else {
                startCountdown(masaPembayaran);
            }

            // Cek apakah masa pembayaran sudah habis
            const now = new Date().getTime();
            const end = new Date(masaPembayaran).getTime();
            if (now > end) {
                document.getElementById('bankList').style.display = 'none'; // Sembunyikan form pembayaran
                document.getElementById('uploadForm').style.display = 'none'; // Sembunyikan form upload
                document.getElementById('countdown').innerHTML = "Waktu pembayaran telah habis.";
            }
        };
    </script>
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

        <!-- Menampilkan Waktu Pembayaran Tersisa -->
        <div class="mt-5">
            <h3>Waktu Pembayaran Tersisa</h3>
            <p id="countdown" class="text-danger"></p>
        </div>

        <!-- Form untuk Unggah Bukti Pembayaran -->
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

        <a href="history.php" class="btn btn-secondary mt-3">Kembali ke Riwayat Pesanan</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
