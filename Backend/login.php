<?php
// login.php

session_start(); // Memulai sesi

$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "user_management";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password']; // Ambil password dari input

    // Siapkan dan bind
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password, $role);
        $stmt->fetch();

        // Verifikasi password
        if (password_verify($password, $hashed_password)) {
            // Set session variable
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            // Redirect ke dashboard admin jika role adalah admin
            if ($role === 'admin') {
                header("Location: ../Dashboard/Admin.html");
                exit();
            } else {
                echo "Login successful! You are a regular user.";
                // Anda bisa mengarahkan pengguna biasa ke halaman lain
            }
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>