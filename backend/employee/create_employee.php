<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireRole(['ADMIN']);

$stmt=$pdo->prepare("
    INSERT INTO employees (user_id,company_id,department_id)
    VALUES (?,?,?)
");
$stmt->execute([
    $_POST['user_id'],
    $_SESSION['company_id'],
    $_POST['department_id']
]);

echo json_encode(['status'=>'employee_created']);
