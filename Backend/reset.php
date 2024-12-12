<?php
require_once 'koneksi.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek token di database
    $result = $conn->query("SELECT * FROM users WHERE reset_token='$token'");
    if ($result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET password='$new_password', reset_token=NULL WHERE reset_token='$token'");
            echo 'Password has been reset successfully.';
        }
    } else {
        echo 'Invalid token.';
    }
} else {
    echo 'No token provided.';
}
?>

<form action="" method="POST">
    <input type="password" name="password" placeholder="New Password" required>
    <button type="submit">Reset Password</button>
</form>
