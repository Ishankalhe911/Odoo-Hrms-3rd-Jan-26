<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireRole(['ADMIN']); // Only admin can create users

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role     = $_POST['role'] ?? 'EMPLOYEE';

if (!$email || !$password) {
    http_response_code(400);
    exit('Email and password required');
}

// Prevent duplicate users
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    http_response_code(409);
    exit('User already exists');
}

// Secure password hash
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO users (email, password_hash, role)
    VALUES (?, ?, ?)
");
$stmt->execute([$email, $hashedPassword, $role]);

echo json_encode([
    'status' => 'success',
    'message' => 'User created successfully'
]);
