<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireRole(['ADMIN']);

$userId    = $_POST['user_id'];
$role      = $_POST['role'] ?? null;
$password  = $_POST['password'] ?? null;
$companyId = $_SESSION['company_id'];

if (!$userId) {
    http_response_code(400);
    exit('User ID required');
}

$fields = [];
$params = [];

if ($role) {
    $fields[] = "role = ?";
    $params[] = $role;
}

if ($password) {
    $fields[] = "password_hash = ?";
    $params[] = password_hash($password, PASSWORD_DEFAULT);
}

if (!$fields) {
    exit('Nothing to update');
}

$params[] = $userId;
$params[] = $companyId;

$sql = "
    UPDATE users
    SET " . implode(', ', $fields) . "
    WHERE user_id = ?
      AND company_id = ?
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode([
    'status' => 'user_updated'
]);
