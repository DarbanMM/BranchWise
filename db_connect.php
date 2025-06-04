<?php
$servername = "localhost"; // Sesuaikan jika host database Anda berbeda
$username_db = "root";    // Sesuaikan dengan username database Anda (default XAMPP biasanya 'root')
$password_db = "";        // Sesuaikan dengan password database Anda (default XAMPP biasanya kosong)
$dbname = "db_branchwise"; // Nama database yang sudah Anda buat

// Buat koneksi ke database
$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
// echo "Koneksi berhasil!"; // Baris ini bisa Anda hapus setelah memastikan koneksi berhasil
?>