<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireRole(['EMPLOYEE']);

$stmt=$pdo->prepare("
    INSERT INTO leave_requests
    (employee_id,leave_type_id,start_date,end_date)
    VALUES (?,?,?,?)
");
$stmt->execute([
    $_SESSION['employee_id'],
    $_POST['leave_type_id'],
    $_POST['start_date'],
    $_POST['end_date']
]);

echo json_encode(['status'=>'leave_applied']);
