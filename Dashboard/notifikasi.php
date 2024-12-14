<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

require_once '../backend/koneksi.php';
require_once '../backend/notification.php'; // Tambahkan ini untuk mengakses fungsi notifikasi

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Hapus notifikasi jika diminta
if (isset($_POST['delete_ids'])) {
    $delete_ids = $_POST['delete_ids'];
    foreach ($delete_ids as $id) {
        $sql_delete = "DELETE FROM notifications WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id);
        $stmt_delete->execute();
    }
    header("Location: notifikasi.php"); // Redirect setelah menghapus
    exit();
}

// Hapus semua notifikasi jika diminta
if (isset($_POST['delete_all'])) {
    $sql_delete_all = "DELETE FROM notifications";
    $conn->query($sql_delete_all);
    header("Location: notifikasi.php"); // Redirect setelah menghapus semua
    exit();
}

// Ambil notifikasi
$notifications = getNotifications();

// Mengubah status notifikasi saat diklik
if (isset($_GET['mark_read'])) {
    $id = $_GET['mark_read'];
    markAsRead($id);
    header("Location: notifikasi.php"); // Redirect setelah mengubah status
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Notifikasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function toggleCheckboxes(source) {
            const checkboxes = document.querySelectorAll('input[name="delete_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }
    </script>
    <style>
        .read {
            color: #6c757d; /* Warna lebih gelap untuk notifikasi yang sudah dibaca */
        }
        .unread {
            font-weight: bold; /* Teks tebal untuk notifikasi yang belum dibaca */
        }
        .new-badge {
            background-color: #28a745; /* Warna hijau untuk badge "Baru" */
            color: white;
            border-radius: 12px;
            padding: 2px 8px;
            font-size: 0.75rem;
            margin-left: 10px;
        }
    </style>
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
                    <a class="nav-link" href="admin.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../backend/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container my-5">
    <h1 class="text-center mb-4">Notifikasi</h1>

    <form method="POST" action="">
        <div class="mb-3">
            <button type="submit" name="delete_ids" class="btn btn-danger">Hapus</button>
            <button type="submit" name="delete_all" class="btn btn-warning">Hapus Semua</button>
        </div>

        <h5>Semua Notifikasi</h5>
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="selectAll" onclick="toggleCheckboxes(this)">
            <label class="form-check-label" for="selectAll">Pilih Semua</label>
        </div>
        <?php if (count($notifications) > 0): ?>
            <ul class="list-group mb-4">
                <?php foreach ($notifications as $notification): ?>
                    <li class="list-group-item <?php echo $notification['is_read'] ? 'read' : 'unread'; ?>">
                        <input type="checkbox" name="delete_ids[]" value="<?php echo $notification['id']; ?>">
                        <a href="?mark_read=<?php echo $notification['id']; ?>" class="text-decoration-none">
                            <?php echo htmlspecialchars($notification['message']); ?>
                        </a>
                        <small class="text-muted"> (<?php echo $notification['created_at']; ?>)</small>
                        <?php if (!$notification['is_read']): ?>
                            <span class="new-badge">Baru</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Tidak ada notifikasi.</p>
        <?php endif; ?>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
