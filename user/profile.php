<?php
session_start();
require_once '../backend/koneksi.php'; // Koneksi database

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html"); // Redirect ke login jika belum login
    exit();
}

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];

// Query untuk mengambil data pengguna berdasarkan user_id
$sql = "SELECT * FROM users WHERE user_id = ?"; // Gunakan user_id, bukan id
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Pengguna tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard User</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Backend/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <h1 class="text-center mb-4">Profil Pengguna</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Nama: <?php echo htmlspecialchars($user['name']); ?></h5> <!-- Sesuaikan dengan kolom 'name' di database -->
                <p class="card-text"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p class="card-text"><strong>Nomor Handphone:</strong> <?php echo htmlspecialchars($user['no_hp']); ?></p>
                <a href="edit_profile.php" class="btn btn-primary">Edit Profil</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
