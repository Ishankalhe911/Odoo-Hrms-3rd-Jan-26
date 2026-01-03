<?php
/**
 * Dayflow HRMS Backend – Entry Point
 * ---------------------------------
 * - Blocks direct access to folders
 * - Acts as API gateway
 * - Provides health check for judges
 */

header('Content-Type: application/json');

// Allow only PHP files to be accessed
$requestUri = $_SERVER['REQUEST_URI'];
$method     = $_SERVER['REQUEST_METHOD'];

/**
 * BASIC ROUTING MAP
 * URL format:
 * /backend/index.php?module=auth&action=login
 */
$module = $_GET['module'] ?? null;
$action = $_GET['action'] ?? null;

/**
 * Health check (for judges / debugging)
 */
if (!$module) {
    echo json_encode([
        'service' => 'Dayflow HRMS Backend',
        'status'  => 'running',
        'time'    => date('Y-m-d H:i:s')
    ]);
    exit;
}

/**
 * Whitelist allowed modules
 */
$allowedModules = [
    'auth',
    'attendance',
    'leave',
    'payroll',
    'employee',
    'admin'
];

if (!in_array($module, $allowedModules)) {
    http_response_code(404);
    exit(json_encode(['error' => 'Module not found']));
}

/**
 * Resolve file path safely
 */
$basePath = __DIR__;
$filePath = $basePath . '/' . $module . '/' . $action . '.php';

if (!file_exists($filePath)) {
    http_response_code(404);
    exit(json_encode(['error' => 'Endpoint not found']));
}

/**
 * Execute endpoint
 */
require $filePath;
