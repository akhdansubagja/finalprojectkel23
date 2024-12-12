<?php
require_once 'koneksi.php'; // Pastikan Anda menghubungkan ke database
require '../vendor/autoload.php'; // Jika menggunakan Composer untuk PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string(trim($_POST['email']));

    // Cek apakah email ada di databases
    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    if ($result->num_rows > 0) {
        // Buat token untuk reset password
        $token = bin2hex(random_bytes(50));
        $conn->query("UPDATE users SET reset_token='$token' WHERE email='$email'");

        // Kirim email
        $mail = new PHPMailer(true);
        try {
            // Konfigurasi server
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Ganti dengan SMTP server Anda
            $mail->SMTPAuth = true;
            $mail->Username = 'tidakberkah6@gmail.com'; // Ganti dengan email Anda
            $mail->Password = 'cvxn tsdh zdsp dzbm'; // Ganti dengan password email Anda
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Pengaturan email
            $mail->setFrom('tidakberkah6@gmail.com', 'adon');
            $mail->addAddress($email);
            $mail->Subject = 'Reset Password';
            $mail->Body = "Click the link to reset your password: http://localhost/test/pemwebfp/Backend/reset.php?token=$token";
            $mail->send();

            // Tampilkan pesan dan alihkan ke halaman login setelah 5 detik
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Reset Password</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="container my-5">
                    <div class="alert alert-success" id="message">Reset link has been sent to your email. Back to login page <span id="countdown">5</span>s</div>
                </div>
                <script>
                    var countdown = 5;
                    var interval = setInterval(function() {
                        countdown--;
                        document.getElementById("countdown").innerText = countdown;
                        if (countdown <= 0) {
                            clearInterval(interval);
                            window.location.href = "../login.html"; // Ganti dengan URL halaman login Anda
                        }
                    }, 1000); // 1000 ms = 1 detik
                </script>
            </body>
            </html>';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo 'Email not found.';
    }
}
?>
