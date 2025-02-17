<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Simpan data tamu
        $stmt = $pdo->prepare("INSERT INTO guests (name, attendance_status, number_of_guests) VALUES (?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['attendance_status'],
            $_POST['number_of_guests']
        ]);
        
        $guest_id = $pdo->lastInsertId();
        
        // Jika ada pesan, simpan juga
        if (!empty($_POST['message'])) {
            $stmt = $pdo->prepare("INSERT INTO messages (guest_id, message) VALUES (?, ?)");
            $stmt->execute([$guest_id, $_POST['message']]);
        }
        
        // Redirect dengan status sukses
        header("Location: index.php?rsvp=success#rsvp-form");
        exit;
    } catch(PDOException $e) {
        header("Location: index.php?rsvp=error#rsvp-form");
        exit;
    }
}
?> 