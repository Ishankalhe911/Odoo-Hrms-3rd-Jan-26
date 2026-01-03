<?php
require_once '../config/db.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

// Check if user exists
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetchColumn() > 0) {
    http_response_code(400);
    exit('Email already exists');
}

// Insert new user
$stmt = $pdo->prepare("INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)");
$stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT), $role]);

echo json_encode(['status' => 'success']);
