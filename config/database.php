<?php
// config/database.php
$host = 'localhost'; // Di InfinityFree pake localhost
$dbname = 'your_database_name'; // Ganti sesuai database lo
$username = 'your_username'; // Ganti sesuai username lo
$password = 'your_password'; // Ganti sesuai password lo

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Mulai session
session_start();
?>
