<?php
// Mulai session
session_start();
require 'koneksi.php'; // Koneksi ke database

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data email dan password dari form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Mencegah SQL Injection dengan menggunakan real_escape_string
    $email = $conn->real_escape_string($email);

    // Query untuk mengambil user berdasarkan email
    $sql = "SELECT user_id, name, email, password, role FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    // Cek apakah user ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verifikasi password menggunakan password_verify
        if (password_verify($password, $row['password'])) {
            // Login berhasil, simpan data user ke session
            $_SESSION['user_id'] = $row['user_id'];           // Menyimpan ID pengguna ke session
            $_SESSION['user_name'] = $row['name'];            // Menyimpan nama pengguna ke session
            $_SESSION['user_email'] = $row['email'];          // Menyimpan email pengguna (opsional)
            $_SESSION['user_role'] = $row['role'];            // Menyimpan peran pengguna ke session

            // Redirect berdasarkan peran pengguna
            if ($row['role'] === 'admin') {
                header("Location: ../Dashboard/admin.php"); // Arahkan ke halaman admin
            } else {
                header("Location: ../User/Dashboard.php"); // Arahkan ke halaman user
            }
            exit();
        } else {
            // Password salah
            echo "<script>alert('Password salah.');</script>";
        }
    } else {
        // Email tidak ditemukan
        echo "<script>alert('Email tidak ditemukan.');</script>";
    }
}

$conn->close();
?>
