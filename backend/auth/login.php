<?php
require_once '../config/db.php';
session_start();

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("
    SELECT id, password_hash, role 
    FROM users 
    WHERE email = ?
");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    exit('Invalid credentials');
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];

echo json_encode(['status' => 'success']);
