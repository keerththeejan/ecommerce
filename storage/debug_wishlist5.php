<?php
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_name'] = 'admin';
$_SESSION['user_role'] = 'admin';
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
$_SERVER['HTTP_ACCEPT'] = 'application/json';
$_GET['ajax'] = 1;
$_POST['ajax'] = 1;
$_POST['product_id'] = 127;
$_POST['quantity'] = 1;
$_GET['id'] = 127;

require_once 'C:/wamp64/www/ecommerce/config/config.php';
require_once 'C:/wamp64/www/ecommerce/config/database.php';
$GLOBALS['db'] = new Database();
require_once 'C:/wamp64/www/ecommerce/app/helpers.php';
require_once 'C:/wamp64/www/ecommerce/app/controllers/Controller.php';
require_once 'C:/wamp64/www/ecommerce/app/models/Model.php';
spl_autoload_register(function ($class) {
    foreach ([APP_PATH . 'controllers/' . $class . '.php', APP_PATH . 'models/' . $class . '.php'] as $f) {
        if (file_exists($f)) { require_once $f; return; }
    }
});

$c = new WishlistController();
$c->moveToCart(127);
