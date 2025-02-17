<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Buat koneksi tanpa memilih database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat database jika belum ada
    $pdo->exec("CREATE DATABASE IF NOT EXISTS wedding_invitation");
    
    // Koneksi ulang dengan memilih database
    $pdo = new PDO("mysql:host=$host;dbname=wedding_invitation", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Buat tabel jika belum ada
    $pdo->exec("CREATE TABLE IF NOT EXISTS guests (
        id INT PRIMARY KEY AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        attendance_status ENUM('Hadir', 'Tidak Hadir', 'Belum Konfirmasi') DEFAULT 'Belum Konfirmasi',
        number_of_guests INT DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
        id INT PRIMARY KEY AUTO_INCREMENT,
        guest_id INT,
        message TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (guest_id) REFERENCES guests(id)
    )");
    
} catch(PDOException $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?> 