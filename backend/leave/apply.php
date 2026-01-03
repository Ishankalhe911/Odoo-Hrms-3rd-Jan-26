<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireRole(['EMPLOYEE']);

$userId = $_SESSION['user_id'];
$start = $_POST['start_date'];
$end   = $_POST['end_date'];
$type  = $_POST['type'];

$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM leaves
    WHERE user_id = ?
      AND status = 'APPROVED'
      AND NOT (end_date < ? OR start_date > ?)
");
$stmt->execute([$userId, $start, $end]);

if ($stmt->fetchColumn() > 0) {
    http_response_code(400);
    exit('Overlapping approved leave exists');
}

$stmt = $pdo->prepare("
    INSERT INTO leaves (user_id, start_date, end_date, type, status)
    VALUES (?, ?, ?, ?, 'PENDING')
");
$stmt->execute([$userId, $start, $end, $type]);

echo json_encode(['status' => 'applied']);
