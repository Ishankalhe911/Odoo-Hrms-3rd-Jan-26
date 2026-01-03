<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireRole(['EMPLOYEE']);

$userId = $_SESSION['user_id'];
$today = date('Y-m-d');

$pdo->beginTransaction();

$stmt = $pdo->prepare("
    SELECT id, check_in, check_out 
    FROM attendance 
    WHERE user_id = ? AND date = ?
    FOR UPDATE
");
$stmt->execute([$userId, $today]);
$attendance = $stmt->fetch();

if (!$attendance) {
    // Check-in
    $stmt = $pdo->prepare("
        INSERT INTO attendance (user_id, date, check_in)
        VALUES (?, ?, NOW())
    ");
    $stmt->execute([$userId, $today]);
} else {
    if ($attendance['check_out']) {
        $pdo->rollBack();
        exit('Already checked out');
    }

    // Check-out
    $stmt = $pdo->prepare("
        UPDATE attendance 
        SET check_out = NOW(),
            work_hours = TIMESTAMPDIFF(MINUTE, check_in, NOW()) / 60
        WHERE id = ?
    ");
    $stmt->execute([$attendance['id']]);
}

$pdo->commit();
echo json_encode(['status' => 'ok']);
