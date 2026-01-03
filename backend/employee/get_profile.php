<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireAuth();

$employeeId = $_SESSION['employee_id'];
$companyId  = $_SESSION['company_id'];

/*
 HR/Admin may pass employee_id to view others
 Employee can ONLY view self
*/
if (in_array($_SESSION['role'], ['ADMIN','HR']) && isset($_GET['employee_id'])) {
    $employeeId = $_GET['employee_id'];
}

$stmt = $pdo->prepare("
    SELECT 
        e.employee_id,
        u.email,
        u.role,
        d.department_name,
        e.company_id
    FROM employees e
    JOIN users u ON u.user_id = e.user_id
    LEFT JOIN departments d ON d.department_id = e.department_id
    WHERE e.employee_id = ?
      AND e.company_id = ?
");

$stmt->execute([$employeeId, $companyId]);
$profile = $stmt->fetch();

if (!$profile) {
    http_response_code(404);
    exit('Profile not found');
}

echo json_encode([
    'status'  => 'success',
    'profile' => $profile
]);
