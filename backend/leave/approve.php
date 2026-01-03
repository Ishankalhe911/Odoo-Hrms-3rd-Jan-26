<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';
require_once '../audit/log.php';

requireRole(['HR', 'ADMIN']);

$leaveId = $_POST['leave_id'];
$action  = $_POST['action']; // APPROVED / REJECTED

$pdo->beginTransaction();

$stmt = $pdo->prepare("
    UPDATE leaves 
    SET status = ?, approved_at = NOW()
    WHERE id = ? AND status = 'PENDING'
");
$stmt->execute([$action, $leaveId]);

auditLog($_SESSION['user_id'], "Leave $action", "LeaveID:$leaveId");

$pdo->commit();
echo json_encode(['status' => 'done']);
