<?php
require_once '../config/db.php';

$name = $_POST['company_name'];

$stmt = $pdo->prepare("
    INSERT INTO companies (company_name)
    VALUES (?)
");
$stmt->execute([$name]);

echo json_encode([
    'status'=>'company_created',
    'company_id'=>$pdo->lastInsertId()
]);
