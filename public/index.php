<?php

error_reporting(0);
@ini_set('display_errors', 0);
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
// Support both pretty URLs via .htaccess (index.php?url=controller/action/param)
// and traditional query parameters (?controller=...&action=...&param=...)
// Parse pretty URL first but do not override explicitly provided query params
$urlController = null;
$urlAction = null;
$urlParam = null;
if (isset($_GET['url'])) {
    $segments = array_values(array_filter(explode('/', trim($_GET['url'], '/'))));
    if (!empty($segments)) {
        $urlController = $segments[0];
        if (isset($segments[1]) && $segments[1] !== '') {
            $urlAction = $segments[1];
        }
        if (isset($segments[2]) && $segments[2] !== '') {
            $urlParam = $segments[2];
        }
    }
}

$controllerParam = isset($_GET['controller']) ? $_GET['controller'] : ($urlController ?: 'Home');

// Normalize common plural aliases to singular controller names
$aliasMap = [
    'orders' => 'Order',
    'invoices' => 'Invoice',
    'addresses' => 'Address',
    'brands' => 'Brand',
    'countries' => 'Country',
    'categories' => 'Category',
    'products' => 'Product',
    'users' => 'User'
];
if (isset($aliasMap[strtolower($controllerParam)])) {
    $controllerParam = $aliasMap[strtolower($controllerParam)];
}
$action = isset($_GET['action']) ? $_GET['action'] : ($urlAction ?: 'index');
// Check for both 'param' and 'id' in the URL, falling back to pretty URL param
// Prioritize 'id' over 'param' for product category routes
$param = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['param']) ? $_GET['param'] : $urlParam);

// Format controller name, but do not double-append if already provided with suffix
if (preg_match('/Controller$/i', $controllerParam)) {
    $controllerName = $controllerParam;
} else {
    $controllerName = ucfirst($controllerParam) . 'Controller';
}

// Attempt to resolve and include the controller file explicitly
$controllerFile = APP_PATH . 'controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    // Try a couple of fallback name variants just in case
    $alt1 = APP_PATH . 'controllers/' . ucfirst(strtolower($controllerParam)) . 'Controller' . '.php';
    $alt2 = APP_PATH . 'controllers/' . strtolower($controllerParam) . 'Controller' . '.php';
    $alt3 = APP_PATH . 'controllers/' . ucfirst($controllerParam) . 'Controller' . '.php';
    foreach ([$alt1, $alt2, $alt3] as $alt) {
        if (file_exists($alt)) {
            $controllerFile = $alt;
            break;
        }
    }
}

if (file_exists($controllerFile)) {
    require_once $controllerFile;
}

// If class still doesn't exist, report not found
if (!class_exists($controllerName)) {
    echo '404 - Controller not found';
    return;
}

// Create controller instance (uses global DB in base Controller)
$controllerInstance = new $controllerName();

// Check if action exists
if (method_exists($controllerInstance, $action)) {
    // Call action with parameter
    // Convert param to string for consistent handling, but pass as-is
    if ($param !== null && $param !== '') {
        $controllerInstance->$action($param);
    } else {
        $controllerInstance->$action();
    }
} else {
    // Action not found
    echo '404 - Action not found';
}
