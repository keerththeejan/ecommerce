<?php

error_reporting(0);
@ini_set('display_errors', 0);
/**
 * Configuration file for the e-commerce application
 */

// Production config: if hosting on sivakamy.ch, load config.production.php (create from config.production.php.example)
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$productionFile = __DIR__ . '/config.production.php';
if (file_exists($productionFile) && strpos($host, 'sivakamy.ch') !== false) {
    require_once $productionFile;
}

// Local/development defaults (only if not already defined by config.production.php)
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '1234');
if (!defined('DB_NAME')) define('DB_NAME', 'sn');
if (!defined('BASE_URL')) define('BASE_URL', 'http://localhost/ecommerce/');
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__) . '/');
if (!defined('APPROOT')) define('APPROOT', dirname(dirname(__FILE__))); // Root directory of the application
if (!defined('APP_PATH')) define('APP_PATH', ROOT_PATH . 'app/');
if (!defined('PUBLIC_PATH')) define('PUBLIC_PATH', ROOT_PATH . 'public/');
if (!defined('ASSETS_PATH')) define('ASSETS_PATH', ROOT_PATH . 'assets/');
if (!defined('CONFIG_PATH')) define('CONFIG_PATH', ROOT_PATH . 'config/');

// URL Root (for links in views)
if (!defined('URLROOT')) define('URLROOT', defined('BASE_URL') ? BASE_URL : 'http://localhost/ecommerce/');

// File upload settings
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', ROOT_PATH . 'public/uploads/');

// Session configuration
session_start();

// Error reporting (production keeps errors off; config.production.php sets DISPLAY_ERRORS=false)
if (!defined('DISPLAY_ERRORS') || DISPLAY_ERRORS) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// Time zone
date_default_timezone_set('Asia/Kolkata');

// Currency settings
if (!defined('CURRENCY_SYMBOL')) define('CURRENCY_SYMBOL', 'CHF');
if (!defined('CURRENCY_CODE')) define('CURRENCY_CODE', 'CHF');
if (!defined('CURRENCY_FORMAT')) define('CURRENCY_FORMAT', 'CHF%s');  // %s will be replaced with the amount

// Mail / SMTP (used by MailController – set your SMTP credentials here)
if (!defined('MAIL_FROM_ADDRESS')) define('MAIL_FROM_ADDRESS', '');
if (!defined('MAIL_FROM_NAME')) define('MAIL_FROM_NAME', 'Store');
if (!defined('SMTP_HOST')) define('SMTP_HOST', 'smtp.gmail.com');
if (!defined('SMTP_PORT')) define('SMTP_PORT', 587);
if (!defined('SMTP_ENCRYPTION')) define('SMTP_ENCRYPTION', 'tls');
if (!defined('SMTP_USERNAME')) define('SMTP_USERNAME', '');
if (!defined('SMTP_PASSWORD')) define('SMTP_PASSWORD', '');