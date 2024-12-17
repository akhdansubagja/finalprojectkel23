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

if (isset($_GET['id'])) {
    $id_pesanan = $_GET['id'];

    // Ambil detail pesanan biasa
    $sql = "SELECT * FROM pesanan WHERE id_pesanan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_pesanan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $pesanan = $result->fetch_assoc();
    } else {
        die("Pesanan tidak ditemukan.");
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status_pesanan = $_POST['status_pesanan'];

    // Update status pesanan
    $update_sql = "UPDATE pesanan SET status_pesanan = ? WHERE id_pesanan = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $status_pesanan, $id_pesanan);
    if ($update_stmt->execute()) {
        header("Location: kelola_pesanan.php?status=success");
        exit();
    } else {
        echo "Gagal mengupdate status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h1 class="text-center mb-4">Update Status Pesanan</h1>

        <form action="" method="POST">
            <div class="mb-3">
                <label for="status_pesanan" class="form-label">Status Pesanan</label>
                <select class="form-control" id="status_pesanan" name="status_pesanan">
                    <option value="Pending" <?php echo ($pesanan['status_pesanan'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Diproses" <?php echo ($pesanan['status_pesanan'] == 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                    <option value="Selesai" <?php echo ($pesanan['status_pesanan'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Status</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>