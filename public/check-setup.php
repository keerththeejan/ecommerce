<?php
/**
 * Debug script: helps diagnose 500 error on cPanel
 * Access: https://sivakamy.ch/check-setup.php
 * DELETE this file after fixing the issue.
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: text/html; charset=utf-8');

echo "<h1>Setup Check (sivakamy.ch)</h1>";

// 1. Load config
try {
    require_once __DIR__ . '/../config/config.php';
    echo "<p>✓ Config loaded</p>";
    echo "<p>BASE_URL: " . (defined('BASE_URL') ? BASE_URL : 'not set') . "</p>";
    echo "<p>ROOT_PATH: " . (defined('ROOT_PATH') ? ROOT_PATH : 'not set') . "</p>";
} catch (Throwable $e) {
    echo "<p style='color:red'>✗ Config error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    exit;
}

// 2. Database
try {
    require_once CONFIG_PATH . 'database.php';
    $db = new Database();
    echo "<p>✓ Database class loaded</p>";
    $pdo = $db->getConnection();
    if ($pdo) {
        echo "<p>✓ Database connected</p>";
    } else {
        echo "<p style='color:red'>✗ Database connection failed</p>";
    }
} catch (Throwable $e) {
    echo "<p style='color:red'>✗ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// 3. AdminController
try {
    require_once APP_PATH . 'helpers.php';
    require_once APP_PATH . 'controllers/AdminController.php';
    echo "<p>✓ AdminController loaded</p>";
} catch (Throwable $e) {
    echo "<p style='color:red'>✗ AdminController error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<p><strong>If all checks pass, the app should work. If 500 persists, check cPanel → Metrics → Errors.</strong></p>";
echo "<p><small>Delete this file (check-setup.php) after debugging.</small></p>";
