<?php
function auditLog($userId, $action, $details) {
    global $pdo;
    $stmt = $pdo->prepare("
        INSERT INTO audit_logs (user_id, action, details)
        VALUES (?, ?, ?)
    ");
    $stmt->execute([$userId, $action, $details]);
}
