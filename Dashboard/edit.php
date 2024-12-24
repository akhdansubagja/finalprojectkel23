<?php
session_start();
// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') { // Menggunakan user_role
    header("Location: ../unauthorized.html"); // Ganti dengan halaman yang sesuai
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

// Ambil ID dari parameter URL dan validasi
$id = intval($_GET['id']);

// Ambil data paket berdasarkan ID
$sql = "SELECT * FROM paket WHERE id_paket = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Paket tidak ditemukan.");
}

// Fungsi untuk mencatat log ke file
function logToFile($message) {
    $logFile = '../debug.log';
    $timestamp = date("Y-m-d H:i:s");
    file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validasi dan sanitasi input
    $nama_paket = $conn->real_escape_string(trim($_POST['nama_paket']));
    $tujuan = $conn->real_escape_string(trim($_POST['tujuan']));
    $durasi_hari = intval($_POST['durasi_hari']);
    $harga = floatval(str_replace('.', '', $_POST['harga']));
    $status_paket = $conn->real_escape_string(trim($_POST['status_paket']));
    $deskripsi = $conn->real_escape_string(trim($_POST['deskripsi']));

    // Cek apakah ada foto baru yang diunggah
    $foto_name = $data['foto']; // Default ke foto lama
    if (!empty($_FILES['foto']['name'])) {
        // Menyimpan foto baru
        $target_dir = "../uploads/";
        $foto_name = time() . "_" . basename($_FILES['foto']['name']); // Menambahkan timestamp
        $target_file = $target_dir . $foto_name;

        // Hapus foto lama jika ada
        if (!empty($data['foto']) && file_exists($target_dir . $data['foto'])) {
            unlink($target_dir . $data['foto']);
        }

        // Pindahkan file yang diunggah
        move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
    }

    // Logging: Cek nilai yang akan dikirim
    logToFile("Nama Paket: $nama_paket");
    logToFile("Tujuan: $tujuan");
    logToFile("Durasi Hari: $durasi_hari");
    logToFile("Harga: $harga");
    logToFile("Status Paket: $status_paket");
    logToFile("Deskripsi: $deskripsi");
    logToFile("Foto: $foto_name");
    logToFile("ID Paket: $id");

    // Update data ke database
    $sql = "UPDATE paket SET 
                nama_paket = ?, 
                tujuan = ?, 
                durasi_hari = ?, 
                harga = ?, 
                status_paket = ?, 
                deskripsi = ?, 
                foto = ? 
            WHERE id_paket = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssidsssi", $nama_paket, $tujuan, $durasi_hari, $harga, $status_paket, $deskripsi, $foto_name, $id);

    if ($stmt->execute()) {
        logToFile("Paket dengan ID $id berhasil diperbarui.");
        header('Location: admin.php?status=edit_success');
        exit();
    } else {
        logToFile("Error saat memperbarui paket: " . $stmt->error);
        echo "Error: " . $stmt->error; // Ini bisa dihapus jika tidak ingin menampilkan di browser
    }    
}
?>

<!-- HTML untuk form edit -->
<!DOCTYPE html>
<html>
<head>
    <title>Edit Paket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center">Edit Paket</h1>
        <form method="POST" enctype="multipart/form-data" class="mx-auto" style="max-width: 600px;">
            <div class="mb-3">
                <label for="nama_paket" class="form-label">Nama Paket</label>
                <input type="text" class="form-control" id="nama_paket" name="nama_paket" value="<?= htmlspecialchars($data['nama_paket']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="tujuan" class="form-label">Tujuan</label>
                <input type="text" class="form-control" id="tujuan" name="tujuan" value="<?= htmlspecialchars($data['tujuan']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="durasi_hari" class="form-label">Durasi (Hari)</label>
                <input type="number" class="form-control" id="durasi_hari" name="durasi_hari" value="<?= htmlspecialchars($data['durasi_hari']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="harga" class="form-label">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?= number_format($data['harga'], 0, ',', '.') ?>" required>
            </div>
            <div class="mb-3">
                <label for="status_paket" class="form-label">Status</label>
                <select class="form-control" id="status_paket" name="status_paket" required>
                    <option value="Aktif" <?= $data['status_paket'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Nonaktif" <?= $data['status_paket'] == 'Nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto Paket</label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                <?php if (!empty($data['foto'])): ?>
                    <p class="mt-2">Foto Saat Ini: <img src="../uploads/<?= htmlspecialchars($data['foto']) ?>" alt="Foto Paket" style="max-width: 100px;"></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="admin.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const hargaInput = document.getElementById('harga');

        // Format input harga saat user mengetik
        hargaInput.addEventListener('input', function () {
            let value = this.value.replace(/\./g, ''); // Hapus titik yang ada
            if (!isNaN(value)) {
                this.value = new Intl.NumberFormat('id-ID').format(value); // Format angka dengan titik
            }
        });

        // Unformat input harga sebelum dikirim ke server
        const form = document.querySelector('form');
        form.addEventListener('submit', function () {
            hargaInput.value = hargaInput.value.replace(/\./g, ''); // Hapus titik sebelum submit
        });
    </script>
</body>
</html>
