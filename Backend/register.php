<?php
// register.php

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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_ARGON2I); // Hash password menggunakan Argon2
    $role = "user"; // Tetapkan role sebagai "user"

    // Cek apakah email sudah terdaftar
    $email_check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $email_check_stmt->bind_param("s", $email);
    $email_check_stmt->execute();
    $email_check_stmt->store_result();

    if ($email_check_stmt->num_rows > 0) {
        echo "Email already registered.";
    } else {
        // Siapkan dan bind
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $password, $role);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error; // Menampilkan kesalahan SQL
        }

        $stmt->close();
    }

    $email_check_stmt->close();
}

$conn->close();
?>