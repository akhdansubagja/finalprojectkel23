<?php
require '../vendor/autoload.php'; // Sertakan autoloader Composer

include '../backend/koneksi.php';
include '../backend/notification.php'; // Pastikan untuk menyertakan file notifikasi
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    die("Anda harus login untuk mengunggah bukti pembayaran.");
}

// Ambil ID pesanan
$id_pesanan = $_POST['id_pesanan'];

// Proses upload bukti pembayaran
if (isset($_FILES['foto_transfer']) && $_FILES['foto_transfer']['error'] == 0) {
    $target_dir = "../uploads/bukti_pembayaran/";
    $target_file = $target_dir . time() . "_" . basename($_FILES["foto_transfer"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek apakah file gambar adalah gambar yang sebenarnya
    $check = getimagesize($_FILES["foto_transfer"]["tmp_name"]);
    if ($check === false) {
        echo "File yang diunggah bukan gambar.";
        $uploadOk = 0;
    }

    // Cek ukuran file
    if ($_FILES["foto_transfer"]["size"] > 500000) {
        echo "Maaf, ukuran file terlalu besar.";
        $uploadOk = 0;
    }

    // Cek format file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Maaf, hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
        $uploadOk = 0;
    }

    // Cek apakah $uploadOk diatur ke 0 oleh kesalahan
    if ($uploadOk == 0) {
        echo "Maaf, file Anda tidak diunggah.";
    } else {
        if (move_uploaded_file($_FILES["foto_transfer"]["tmp_name"], $target_file)) {
            // Simpan nama file ke dalam variabel
            $foto_transfer = time() . "_" . basename($_FILES["foto_transfer"]["name"]);

            // Ambil nama pemesan dan nama paket dari tabel pesanan dan paket
            $sql_nama = "SELECT p.*, pk.nama_paket FROM pesanan p JOIN paket pk ON p.id_paket = pk.id_paket WHERE p.id_pesanan = ?";
            $stmt_nama = $conn->prepare($sql_nama);
            $stmt_nama->bind_param("i", $id_pesanan);
            $stmt_nama->execute();
            $result_nama = $stmt_nama->get_result();

            if ($result_nama->num_rows > 0) {
                $row_nama = $result_nama->fetch_assoc();
                $nama_pemesan = $row_nama['nama_pemesan'];
                $user_id = $row_nama['user_id']; // Ambil user_id
                $nama_paket = $row_nama['nama_paket']; // Ambil nama paket
                $tanggal_perjalanan = $row_nama['tanggal_perjalanan'];
                $jumlah_peserta = $row_nama['jumlah_peserta'];

                // Update foto_transfer dan status pembayaran di tabel pesanan
                $sql_update = "UPDATE pesanan SET foto_transfer = ?, status_pembayaran = 'Sudah Dibayar' WHERE id_pesanan = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("si", $foto_transfer, $id_pesanan);
                if ($stmt_update->execute()) {
                    echo "Bukti pembayaran berhasil diunggah.";

                    // Tambahkan notifikasi untuk admin
                    $pesan_notifikasi = "$nama_pemesan telah melakukan pembayaran untuk ID pesanan: " . $id_pesanan;
                    addNotification($pesan_notifikasi, $id_pesanan); // Panggil fungsi notifikasi dengan variabel yang benar

                    // Hapus bagian penggenerasian tiket
                    // Simpan data tiket ke database sesuai dengan jumlah peserta
                    // Kode penggenerasian tiket dihapus
                } else {
                    echo "Gagal memperbarui data pesanan: " . $conn->error;
                }
                $stmt_update->close();
            } else {
                echo "Nama pemesan tidak ditemukan.";
            }
            $stmt_nama->close();
        } else {
            echo "Maaf, terjadi kesalahan saat mengunggah file.";
        }
    }
} else {
    echo "Tidak ada file yang diunggah.";
}

$conn->close();

// Fungsi untuk menghasilkan tiket PDF dihapus dari sini
?>
