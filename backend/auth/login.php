<?php
require_once '../config/db.php';
session_start();

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $pdo->prepare("
    SELECT user_id, password_hash, role, company_id
    FROM users WHERE email=?
");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password,$user['password_hash'])) {
    http_response_code(401);
    exit('Invalid credentials');
}

$stmt = $pdo->prepare("
    SELECT employee_id FROM employees WHERE user_id=?
");
$stmt->execute([$user['user_id']]);
$emp = $stmt->fetch();

session_regenerate_id(true);
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['employee_id'] = $emp['employee_id'] ?? null;
$_SESSION['company_id'] = $user['company_id'];
$_SESSION['role'] = $user['role'];

echo json_encode(['status'=>'login_success']);
