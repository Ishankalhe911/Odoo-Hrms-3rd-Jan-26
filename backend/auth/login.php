<?php
require_once '../config/db.php';
session_start();

$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$stmt = $pdo->prepare("
    SELECT id, password_hash, role
    FROM users
    WHERE email = ?
");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    http_response_code(401);
    exit('Invalid credentials');
}

/**
 * STEP 1: Verify password FIRST
 */
if (!password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    exit('Invalid credentials');
}

/**
 * STEP 2: Rehash ONLY after successful login
 * (Optional but good practice)
 */
if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
    $newHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("
        UPDATE users SET password_hash = ?
        WHERE id = ?
    ");
    $stmt->execute([$newHash, $user['id']]);
}

/**
 * STEP 3: Start authenticated session
 */
session_regenerate_id(true);
$_SESSION['user_id'] = $user['id'];
$_SESSION['role']    = $user['role'];

echo json_encode(['status' => 'login_success']);
