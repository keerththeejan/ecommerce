<?php

error_reporting(0);
@ini_set('display_errors', 0);
// Ensure session is started for all requests (including AJAX)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/**
 * Main entry point for the e-commerce application
 */

// Load configuration
require_once '../config/config.php';

// Initialize database connection
require_once CONFIG_PATH . 'database.php';
$GLOBALS['db'] = new Database();

// Load helpers
require_once APP_PATH . 'helpers.php';

// Autoload classes
spl_autoload_register(function($className) {
    // Skip if the class is Database (already loaded)
    if ($className === 'Database') {
        return;
    }
    
    // Handle namespaces
    $className = str_replace('\\', '/', $className);
    
    // Controllers
    if(file_exists(APP_PATH . 'controllers/' . $className . '.php')) {
        require_once APP_PATH . 'controllers/' . $className . '.php';
        return;
    }
    
    // Models
    if(file_exists(APP_PATH . 'models/' . $className . '.php')) {
        require_once APP_PATH . 'models/' . $className . '.php';
        return;
    }
});

// Set up database connection
// Use the globally initialized Database instance
$db = isset($GLOBALS['db']) ? $GLOBALS['db'] : new Database();

// Simple router
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'Home';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
// Check for both 'param' and 'id' in the URL
$param = isset($_GET['param']) ? $_GET['param'] : (isset($_GET['id']) ? $_GET['id'] : null);

// Format controller name
$controllerName = ucfirst($controller) . 'Controller';

// Check if controller exists
if(file_exists(APP_PATH . 'controllers/' . $controllerName . '.php')) {
    // Create controller instance (uses global DB in base Controller)
    $controllerInstance = new $controllerName();
    
    // Check if action exists
    if(method_exists($controllerInstance, $action)) {
        // Call action with parameter
        if($param !== null) {
            $controllerInstance->$action($param);
        } else {
            $controllerInstance->$action();
        }
    } else {
        // Action not found
        echo '404 - Action not found';
    }
} else {
    // Controller not found
    echo '404 - Controller not found';
}
