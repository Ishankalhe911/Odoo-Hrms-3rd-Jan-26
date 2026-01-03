<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';
require_once '../audit/log.php';

requireRole(['ADMIN','HR']);

$pdo->prepare("
    UPDATE leave_requests
    SET status='APPROVED',approved_at=NOW()
    WHERE request_id=?
")->execute([$_POST['request_id']]);

auditLog($_SESSION['employee_id'],$_SESSION['company_id'],'Leave Approved');
echo json_encode(['status'=>'approved']);
