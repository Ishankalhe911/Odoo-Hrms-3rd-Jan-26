<?php
require_once '../config/db.php';
require_once '../auth/middleware.php';

requireRole(['EMPLOYEE']);

$eid=$_SESSION['employee_id'];
$today=date('Y-m-d');

$stmt=$pdo->prepare("
    SELECT * FROM attendance
    WHERE employee_id=? AND attendance_date=?
");
$stmt->execute([$eid,$today]);
$row=$stmt->fetch();

if(!$row){
    $pdo->prepare("
        INSERT INTO attendance (employee_id,attendance_date,check_in)
        VALUES (?,?,NOW())
    ")->execute([$eid,$today]);
}else{
    if($row['check_out']) exit('Already checked out');
    $pdo->prepare("
        UPDATE attendance
        SET check_out=NOW(),
        work_hours=TIMESTAMPDIFF(MINUTE,check_in,NOW())/60
        WHERE employee_id=? AND attendance_date=?
    ")->execute([$eid,$today]);
}

echo json_encode(['status'=>'attendance_updated']);
