<?php
header('Content-Type: application/json');

$module = $_GET['module'] ?? null;
$action = $_GET['action'] ?? null;

if (!$module || !$action) {
    echo json_encode(['status'=>'HRMS Backend Running']);
    exit;
}

$file = __DIR__."/$module/$action.php";

if (!file_exists($file)) {
    http_response_code(404);
    exit('Endpoint not found');
}

require $file;
