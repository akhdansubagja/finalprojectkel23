<?php
// register.php

// Mulai session
session_start();
require_once 'koneksi.php'; // Include file untuk koneksi database

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek apakah form telah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $password = $_POST['password'];

    // Mencegah SQL Injection
    $name = $conn->real_escape_string($name);
    $email = $conn->real_escape_string($email);
    $no_hp = $conn->real_escape_string($no_hp);
    $password = $conn->real_escape_string($password);

    // Hash password dengan password_hash untuk keamanan yang lebih baik
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Cek apakah email sudah terdaftar
    $check_email_sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($check_email_sql);

    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar. Silakan gunakan email lain.');</script>";
    } else {
        // Cek apakah no_hp sudah terdaftar
        $check_no_hp_sql = "SELECT * FROM users WHERE no_hp='$no_hp'";
        $result_no_hp = $conn->query($check_no_hp_sql);

        if ($result_no_hp->num_rows > 0) {
            echo "<script>alert('Nomor HP sudah terdaftar. Silakan gunakan nomor lain.');</script>";
        } else {
            // Query untuk menyimpan data pengguna baru
            $sql = "INSERT INTO users (name, email, no_hp, password) VALUES ('$name', '$email', '$no_hp', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Pendaftaran berhasil! Silakan login.');</script>";
                // Alihkan pengguna ke halaman login
                echo "<script>window.location.href = '../login.html';</script>";
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>
