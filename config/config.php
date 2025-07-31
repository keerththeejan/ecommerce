<?php
/**
 * Configuration file for the e-commerce application
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '1234');
define('DB_NAME', 'ecommerce30');

// Application paths
define('BASE_URL', 'http://localhost/ecommerce/');
define('ROOT_PATH', dirname(__DIR__) . '/');
define('APPROOT', dirname(dirname(__FILE__))); // Root directory of the application
define('APP_PATH', ROOT_PATH . 'app/');
define('PUBLIC_PATH', ROOT_PATH . 'public/');
define('ASSETS_PATH', ROOT_PATH . 'assets/');
define('CONFIG_PATH', ROOT_PATH . 'config/');

// URL Root (for links in views)
define('URLROOT', 'http://localhost/ecommerce');

// File upload settings
define('UPLOAD_PATH', ROOT_PATH . 'public/uploads/');

// Session configuration
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Time zone
date_default_timezone_set('Asia/Kolkata');

// Currency settings
define('CURRENCY_SYMBOL', 'CHF');
define('CURRENCY_CODE', 'CHF');
define('CURRENCY_FORMAT', 'CHF%s');  // %s will be replaced with the amount
