<?php
session_start();

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

// Koneksi ke database
$conn = new mysqli('localhost', 'root', '', 'db_paket');
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT name, email, password FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Data pengguna tidak ditemukan.";
        exit();
    }
    $stmt->close();
}

// Tangani pembaruan data profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['update_password'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Validasi data
    if (empty($name) || empty($email)) {
        echo "Semua kolom wajib diisi.";
    } else {
        $query = "UPDATE users SET name = ?, email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssi', $name, $email, $user_id);

        if ($stmt->execute()) {
            echo "Profil berhasil diperbarui.";
        } else {
            echo "Terjadi kesalahan saat memperbarui profil.";
        }
        $stmt->close();
    }
}

// Tangani pembaruan password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo "Semua kolom password wajib diisi.";
    } elseif ($new_password !== $confirm_password) {
        echo "Password baru dan konfirmasi password tidak cocok.";
    } else {
        // Cek password saat ini
        $query = "SELECT password FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();

        // Verifikasi password saat ini
        if (password_verify($current_password, $user_data['password'])) {
            // Hash password baru
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET password = ? WHERE user_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('si', $hashed_password, $user_id);

            if ($update_stmt->execute()) {
                echo "Password berhasil diperbarui.";
            } else {
                echo "Terjadi kesalahan saat memperbarui password.";
            }
            $update_stmt->close();
        } else {
            echo "Password saat ini salah.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
</head>
<body>
    <h1>Edit Profil</h1>
    <form action="edit_profile.php" method="POST">
        <label for="name">Nama:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <h2>Ubah Password</h2>
    <form action="edit_profile.php" method="POST">
        <label for="current_password">Password Saat Ini:</label><br>
        <input type="password" id="current_password" name="current_password" required><br><br>

        <label for="new_password">Password Baru:</label><br>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="confirm_password">Konfirmasi Password Baru:</label><br>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <button type="submit" name="update_password">Ubah Password</button>
    </form>

    <a href="profile.php">Kembali ke Profil</a>
</body>
</html>
