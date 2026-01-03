<?php
function auditLog($employeeId,$companyId,$action){
    global $pdo;
    $pdo->prepare("
        INSERT INTO audit_logs (employee_id,company_id,action)
        VALUES (?,?,?)
    ")->execute([$employeeId,$companyId,$action]);
}
