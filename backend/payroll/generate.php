<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';
require_once '../audit/log.php';

requireRole(['ADMIN']);

$month = $_POST['month'];

$stmt = $pdo->prepare("
    SELECT COUNT(*) FROM payroll
    WHERE month = ?
");
$stmt->execute([$month]);

if ($stmt->fetchColumn() > 0) {
    exit('Payroll already generated');
}

$pdo->beginTransaction();

// Example (simplified)
$stmt = $pdo->query("
    INSERT INTO payroll (user_id, month, net_salary)
    SELECT id, '$month', base_salary
    FROM employees
");

auditLog($_SESSION['user_id'], 'Payroll Generated', "Month:$month");

$pdo->commit();
echo json_encode(['status' => 'generated']);
