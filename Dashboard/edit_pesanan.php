<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html"); // Arahkan ke halaman login jika belum login
    exit();
}

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../unauthorized.html"); // Ganti dengan halaman yang sesuai
    exit();
}

require '../vendor/autoload.php';
require_once '../backend/koneksi.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Atur header untuk mencegah caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // Untuk HTTP 1.1
header("Pragma: no-cache"); // Untuk HTTP 1.0
header("Expires: 0"); // Untuk semua

// Ambil ID pesanan dari parameter URL
$id_pesanan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pesanan berdasarkan ID
$sql = "SELECT p.*, pk.nama_paket, u.email FROM pesanan p JOIN paket pk ON p.id_paket = pk.id_paket JOIN users u ON p.user_id = u.user_id WHERE p.id_pesanan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Pesanan tidak ditemukan.");
}

$row = $result->fetch_assoc();

// Proses form jika ada pengiriman
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status_pesanan = $_POST['status_pesanan'];

    // Update status pesanan
    $update_sql = "UPDATE pesanan SET status_pesanan = '$status_pesanan' WHERE id_pesanan = ?";
    $stmt_update = $conn->prepare($update_sql);
    $stmt_update->bind_param("i", $id_pesanan);
    if ($stmt_update->execute()) {
        echo "Status pesanan berhasil diperbarui menjadi $status_pesanan.";

        // Jika status pesanan diubah menjadi "Dikonfirmasi", generate tiket dan kirim email
        if ($status_pesanan === 'Dikonfirmasi') {
            // Ambil informasi pesanan untuk menggenerate tiket
            $user_id = $row['user_id'];
            $nama_paket = $row['nama_paket']; // Ambil nama_paket dari hasil query
            $tanggal_perjalanan = $row['tanggal_perjalanan'];
            $jumlah_peserta = $row['jumlah_peserta'];
            $email_user = $row['email']; // Ambil email pengguna

            // Generate tiket
            $kode_tiket_array = [];
            for ($i = 0; $i < $jumlah_peserta; $i++) {
                $kode_tiket = uniqid('TKT-'); // Generate kode tiket unik
                $kode_tiket_array[] = $kode_tiket;

                $sql_insert_tiket = "INSERT INTO tiket (id_pesanan, user_id, nama_paket, tanggal_perjalanan, jumlah_peserta, kode_tiket) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt_insert_tiket = $conn->prepare($sql_insert_tiket);
                $stmt_insert_tiket->bind_param("iissis", $id_pesanan, $user_id, $nama_paket, $tanggal_perjalanan, $jumlah_peserta, $kode_tiket);
                
                if (!$stmt_insert_tiket->execute()) {
                    echo "Gagal menyimpan data tiket: " . $stmt_insert_tiket->error;
                }
                $stmt_insert_tiket->close();
            }

            // Generate tiket PDF
            $pdfPath = generateTicketPDF($kode_tiket_array, $nama_paket, $tanggal_perjalanan, $jumlah_peserta, $id_pesanan);

            // Kirim email kepada pengguna
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
                $mail->addAddress($email_user);
                $mail->Subject = 'Pesanan Dikonfirmasi';
                $mail->Body = "Pesanan Anda untuk paket '$nama_paket' telah dikonfirmasi. Anda dapat mengunduh tiket Anda pada halaman Detail Pesanan: http://localhost/test/pemwebfp/user/detail_pesanan.php?id=$id_pesanan";
                $mail->send();

                echo "Email konfirmasi telah dikirim ke $email_user.";
            } catch (Exception $e) {
                echo "Pesan tidak dapat dikirim. Kesalahan Mailer: {$mail->ErrorInfo}";
            }
        }

        // Redirect ke halaman kelola pesanan dengan status sukses
        header("Location: kelola_pesanan.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Edit Pesanan</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="kelola_pesanan.php">Kelola Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>    
    <div class="container my-5">
        <h1 class="text-center mb-4">Edit Pesanan</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="status_pesanan" class="form-label">Status Pesanan</label>
                <select class="form-control" id="status_pesanan" name="status_pesanan" required>
                    <option value="Pending" <?= $row['status_pesanan'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Dikonfirmasi" <?= $row['status_pesanan'] == 'Dikonfirmasi' ? 'selected' : '' ?>>Dikonfirmasi</option>
                    <option value="Dibatalkan" <?= $row['status_pesanan'] == 'Dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                    <option value="Selesai" <?= $row['status_pesanan'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Fungsi untuk menghasilkan tiket PDF
function generateTicketPDF($kode_tiket_array, $nama_paket, $tanggal_perjalanan, $jumlah_peserta, $id_pesanan) {
    $pdf = new \TCPDF(); // Menggunakan namespace penuh
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Zein Persada Utama');
    $pdf->SetTitle('Tiket Pesanan');
    $pdf->SetSubject('Detail Tiket');
    $pdf->SetKeywords('TCPDF, PDF, tiket, pesanan');

    // Set ukuran halaman A7 dalam orientasi horizontal
    $width = 105; // Lebar dalam mm
    $height = 74; // Tinggi dalam mm
    $pdf->SetMargins(5, 5, 5); // Margin kiri, atas, kanan
    $pdf->SetAutoPageBreak(TRUE, 5); // Aktifkan pemisahan halaman otomatis

    foreach ($kode_tiket_array as $kode_tiket) {
        $pdf->AddPage('L', [$width, $height]); // Tambahkan halaman baru dengan ukuran A7 horizontal
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'Tiket Pesanan', 0, 1, 'C');
        $pdf->Ln(5); // Tambahkan jarak

        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'ID Pemesanan: ' . $id_pesanan, 0, 1);
        $pdf->Cell(0, 10, 'Kode Tiket: ' . $kode_tiket, 0, 1);
        $pdf->Cell(0, 10, 'Nama Paket: ' . $nama_paket, 0, 1);
        $pdf->Cell(0, 10, 'Tanggal Perjalanan: ' . $tanggal_perjalanan, 0, 1);
        
        // Tambahkan garis pemisah
        $pdf->Ln(3); // Tambahkan jarak
        $pdf->Cell(0, 0, '', 'T', 1, 'C'); // Garis horizontal
        $pdf->Ln(3); // Tambahkan jarak
    }
    
    // Pastikan folder ada dan dapat ditulis
    $outputPath = __DIR__ . '/../uploads/tiket/tiket_' . $id_pesanan . '.pdf'; // Menggunakan path absolut
    $pdf->Output($outputPath, 'F'); // Simpan PDF
    echo "File tiket berhasil dibuat: " . $outputPath; // Tampilkan pesan bahwa file berhasil dibuat
    return $outputPath;
}
?>
