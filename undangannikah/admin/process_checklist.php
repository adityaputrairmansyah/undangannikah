<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guest_id'])) {
    try {
        $stmt = $pdo->prepare("UPDATE guests SET is_checked = 1 WHERE id = ?");
        $stmt->execute([$_POST['guest_id']]);
        header("Location: dashboard.php?status=success");
    } catch(PDOException $e) {
        header("Location: dashboard.php?status=error");
    }
} 