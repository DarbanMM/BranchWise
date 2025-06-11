<?php
// Kredensial dari Supabase "Direct connection"
$host = 'aws-0-ap-southeast-1.pooler.supabase.com';
$port = '5432';
$dbname = 'postgres';
$user = 'postgres.vhcghphnmdncvmbenqxa';
$password = 'databasespkwpm';

// Data Source Name (DSN) untuk PostgreSQL
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

// Opsi koneksi PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Buat instance PDO baru
    $pdo = new PDO($dsn, $user, $password, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>