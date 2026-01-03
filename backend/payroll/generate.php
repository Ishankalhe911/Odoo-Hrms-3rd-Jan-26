<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';
require_once '../audit/log.php';

requireRole(['ADMIN']);

$pdo->prepare("
    INSERT INTO payroll (employee_id,structure_id,month,net_salary)
    SELECT employee_id,?, ?,0 FROM employees
")->execute([$_POST['structure_id'],$_POST['month']]);

auditLog($_SESSION['employee_id'],$_SESSION['company_id'],'Payroll Generated');
echo json_encode(['status'=>'generated']);
