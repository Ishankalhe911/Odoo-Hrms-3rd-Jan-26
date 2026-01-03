<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireRole(['ADMIN']);

$email=$_POST['email'];
$password=$_POST['password'];
$role=$_POST['role'];
$companyId=$_SESSION['company_id'];

$hash=password_hash($password,PASSWORD_DEFAULT);

$stmt=$pdo->prepare("
    INSERT INTO users (email,password_hash,role,company_id)
    VALUES (?,?,?,?)
");
$stmt->execute([$email,$hash,$role,$companyId]);

echo json_encode(['status'=>'user_created']);
