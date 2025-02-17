<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guest_id'])) {
    try {
        // Hapus pesan terkait terlebih dahulu karena ada foreign key
        $stmt = $pdo->prepare("DELETE FROM messages WHERE guest_id = ?");
        $stmt->execute([$_POST['guest_id']]);
        
        // Kemudian hapus data tamu
        $stmt = $pdo->prepare("DELETE FROM guests WHERE id = ?");
        $stmt->execute([$_POST['guest_id']]);
        
        header("Location: dashboard.php?status=deleted");
    } catch(PDOException $e) {
        header("Location: dashboard.php?status=error");
    }
} 