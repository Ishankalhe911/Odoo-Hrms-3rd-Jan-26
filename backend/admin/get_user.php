<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireRole(['ADMIN','HR']);

$stmt=$pdo->prepare("
    SELECT u.user_id,u.email,u.role,e.employee_id
    FROM users u
    LEFT JOIN employees e ON u.user_id=e.user_id
    WHERE u.company_id=?
");
$stmt->execute([$_SESSION['company_id']]);

echo json_encode($stmt->fetchAll());
