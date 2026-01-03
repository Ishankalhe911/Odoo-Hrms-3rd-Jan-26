<?php
session_start();

function requireAuth() {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        exit('Unauthorized');
    }
}

function requireRole(array $roles) {
    requireAuth();
    if (!in_array($_SESSION['role'], $roles)) {
        http_response_code(403);
        exit('Forbidden');
    }
}
