<?php
/**
 * Simulate logged-in wishlist controller flow
 */
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'admin';
$_SESSION['user_role'] = 'admin';

$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
$_SERVER['HTTP_ACCEPT'] = 'application/json';
$_GET['ajax'] = 1;
$_POST['ajax'] = 1;
$_POST['product_id'] = 125;
$_GET['id'] = 125;

require_once 'C:/wamp64/www/ecommerce/config/config.php';
require_once 'C:/wamp64/www/ecommerce/config/database.php';
$GLOBALS['db'] = new Database();
require_once 'C:/wamp64/www/ecommerce/app/helpers.php';
require_once 'C:/wamp64/www/ecommerce/app/controllers/Controller.php';
require_once 'C:/wamp64/www/ecommerce/app/models/Model.php';

// Autoload models/controllers lightly
spl_autoload_register(function ($class) {
    foreach ([
        APP_PATH . 'controllers/' . $class . '.php',
        APP_PATH . 'models/' . $class . '.php',
    ] as $f) {
        if (file_exists($f)) {
            require_once $f;
            return;
        }
    }
});

$c = new WishlistController();

ob_start();
try {
    $c->toggle(125);
} catch (Throwable $e) {
    echo 'ERR: ' . $e->getMessage();
}
$out = ob_get_clean();
echo "TOGGLE OUTPUT:\n$out\n";

ob_start();
try {
    $c->count();
} catch (Throwable $e) {
    echo 'ERR: ' . $e->getMessage();
}
$out = ob_get_clean();
echo "COUNT OUTPUT:\n$out\n";

$w = new Wishlist();
echo "DB ITEMS:\n";
foreach ($w->getUserWishlist(1) as $it) {
    echo "- {$it['id']} {$it['name']}\n";
}
