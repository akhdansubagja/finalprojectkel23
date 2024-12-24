<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

include '../backend/koneksi.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Atur header untuk mencegah caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // Untuk HTTP 1.1
header("Pragma: no-cache"); // Untuk HTTP 1.0
header("Expires: 0"); // Untuk semua

// Panggil fungsi untuk memperbarui status pesanan
updateOrderStatus($conn);

// Cek apakah user sudah login
$is_logged_in = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

// Jika login, ambil data user
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];
    $user_sql = "SELECT name, email FROM users WHERE user_id = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user = $user_stmt->get_result()->fetch_assoc();
}

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
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $paket = $result->fetch_assoc();
} else {
    die("Paket tidak ditemukan.");
}

// Ambil tanggal yang sudah dipesan untuk paket ini dengan status "pending" dan "Dikonfirmasi"
$sql_dates = "SELECT tanggal_perjalanan FROM pesanan WHERE id_paket = ? AND (status_pesanan = 'Pending' OR status_pesanan = 'Dikonfirmasi')";
$stmt_dates = $conn->prepare($sql_dates);
$stmt_dates->bind_param("i", $id_paket);
$stmt_dates->execute();
$result_dates = $stmt_dates->get_result();

$booked_dates = [];
while ($row = $result_dates->fetch_assoc()) {
    $booked_dates[] = $row['tanggal_perjalanan'];
}


// Mendapatkan tanggal hari ini
$current_date = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Paket - <?= htmlspecialchars($paket['nama_paket']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4"><?= htmlspecialchars($paket['nama_paket']) ?></h1>
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($paket['foto'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($paket['foto']) ?>" class="img-fluid rounded" alt="Foto Paket">
                <?php else: ?>
                    <img src="../uploads/default.jpg" class="img-fluid rounded" alt="Foto Default">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Detail Paket</h5>
                        <p class="card-text"><strong>Tujuan:</strong> <?= htmlspecialchars($paket['tujuan']) ?></p>
                        <p class="card-text"><strong>Durasi:</strong> <?= htmlspecialchars($paket['durasi_hari']) ?> Hari</p>
                        <p class="card-text"><strong>Harga:</strong> Rp <?= number_format($paket['harga'], 2, ',', '.') ?> /Orang</p>
                        <p class="card-text"><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($paket['deskripsi'])) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3>Pesan Paket</h3>
            <?php if ($is_logged_in): ?>
                <form action="proses_pesan.php" method="POST" class="mt-3" id="pesanForm">
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
                        <input type="number" class="form-control" id="jumlah_peserta" name="jumlah_peserta" min="20" value="20" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_perjalanan" class="form-label">Tanggal Perjalanan</label>
                        <input type="text" class="form-control" id="tanggal_perjalanan" name="tanggal_perjalanan" required>
                    </div>
                    <div class="mb-3">
                        <label for="catatan" class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="total_harga" class="form-label">Total Harga</label>
                        <p id="total_harga">Rp <?= number_format($paket['harga'], 2, ',', '.') ?></p>
                    </div>

                    <button type="submit" class="btn btn-primary">Pesan Sekarang</button>
                </form>
            <?php else: ?>
                <div class="alert alert-warning" role="alert">
                    Anda harus <a href="../login.html" class="alert-link">login</a> untuk memesan paket ini.
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-4 text-center">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <!-- Modal untuk peringatan -->
    <div class="modal fade" id="tanggalModal" tabindex="-1" aria-labelledby="tanggalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tanggalModalLabel">Peringatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Tanggal perjalanan harus diisi sebelum melanjutkan pemesanan.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mengambil elemen-elemen form
        const jumlahPesertaInput = document.getElementById('jumlah_peserta');
        const hargaPerOrang = <?= $paket['harga']; ?>; // Harga per orang dari PHP
        const totalHargaField = document.getElementById('total_harga');

        // Fungsi untuk menghitung total harga
        function updateTotalHarga() {
            const jumlahPeserta = parseInt(jumlahPesertaInput.value) || 1; // Default ke 1 jika tidak diisi
            const totalHarga = jumlahPeserta * hargaPerOrang;
            totalHargaField.innerText = "Rp " + totalHarga.toLocaleString();
        }

        // Update total harga saat jumlah peserta berubah
        jumlahPesertaInput.addEventListener('input', updateTotalHarga);

        // Panggil fungsi untuk pertama kali
        updateTotalHarga();

        // Inisialisasi Flatpickr
        const bookedDates = <?= json_encode($booked_dates); ?>; // Mengambil tanggal yang sudah dipesan dari PHP
        const tanggalInput = document.getElementById('tanggal_perjalanan');

        flatpickr(tanggalInput, {
            minDate: "today", // Menonaktifkan tanggal yang sudah lewat
            disable: bookedDates, // Menonaktifkan tanggal yang sudah dipesan
            dateFormat: "Y-m-d", // Format tanggal
            onChange: function(selectedDates, dateStr, instance) {
                if (bookedDates.includes(dateStr)) {
                    alert("Tanggal ini sudah dipesan. Silakan pilih tanggal lain.");
                    tanggalInput.clear(); // Menghapus tanggal yang dipilih
                }
            }
        });

        // Validasi sebelum mengirimkan formulir
        document.getElementById('pesanForm').addEventListener('submit', function(event) {
            const tanggalInput = document.getElementById('tanggal_perjalanan').value;
            if (!tanggalInput) {
                event.preventDefault(); // Mencegah pengiriman formulir
                // Tampilkan modal
                const modal = new bootstrap.Modal(document.getElementById('tanggalModal'));
                modal.show();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
