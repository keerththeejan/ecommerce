<?php

error_reporting(0);
@ini_set('display_errors', 0);
/**
 * Configuration file for the e-commerce application
 */

    // Database configuration
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '1234');
    define('DB_NAME', 'sn');

    // Application paths
    define('BASE_URL', 'http://localhost/ecommerce/');
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(__DIR__) . '/');
if (!defined('APPROOT')) define('APPROOT', dirname(dirname(__FILE__))); // Root directory of the application
if (!defined('APP_PATH')) define('APP_PATH', ROOT_PATH . 'app/');
if (!defined('PUBLIC_PATH')) define('PUBLIC_PATH', ROOT_PATH . 'public/');
if (!defined('ASSETS_PATH')) define('ASSETS_PATH', ROOT_PATH . 'assets/');
if (!defined('CONFIG_PATH')) define('CONFIG_PATH', ROOT_PATH . 'config/');

// URL Root (for links in views)
if (!defined('URLROOT')) define('URLROOT', 'http://localhost/ecommerce/');

// File upload settings
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', ROOT_PATH . 'public/uploads/');

// Session configuration
session_start();

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Time zone
date_default_timezone_set('Asia/Kolkata');

// Currency settings
if (!defined('CURRENCY_SYMBOL')) define('CURRENCY_SYMBOL', 'CHF');
if (!defined('CURRENCY_CODE')) define('CURRENCY_CODE', 'CHF');
if (!defined('CURRENCY_FORMAT')) define('CURRENCY_FORMAT', 'CHF%s');  // %s will be replaced with the amount