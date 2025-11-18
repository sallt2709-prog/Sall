<?php
require 'config.php';
require_login();

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game          = $_POST['game'] ?? 'GNG';
    $maxDevices    = (int)($_POST['max_devices'] ?? 1);
    $durationType  = $_POST['duration_type'] ?? 'preset';

    if ($durationType === 'preset') {
        $durationValue = $_POST['preset_duration'] ?? '1 Days';
    } else {
        $d = (int)($_POST['custom_days'] ?? 0);
        $h = (int)($_POST['custom_hours'] ?? 0);
        $durationValue = "{$d} Days {$h} Hours";
    }

    // contoh: tiap license potong saldo 1.00 (opsional)
    $pdo->beginTransaction();
    try {
        // insert license
        $stmt = $pdo->prepare('INSERT INTO licenses 
          (user_id, game, plan, duration_type, duration_value, max_devices)
          VALUES (?, ?, "PREMIUM", ?, ?, ?)');
        $stmt->execute([$userId, $game, $durationType, $durationValue, $maxDevices]);

        // potong balance kalau mau
        $stmt = $pdo->prepare('UPDATE users SET balance = balance - 1 WHERE id = ?');
        $stmt->execute([$userId]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
        // bisa log error di sini
    }
}

header('Location: dashboard.php');
exit;
