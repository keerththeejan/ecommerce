<?php
require_once 'C:/wamp64/www/ecommerce/config/config.php';
require_once 'C:/wamp64/www/ecommerce/config/database.php';
require_once 'C:/wamp64/www/ecommerce/app/models/Model.php';
require_once 'C:/wamp64/www/ecommerce/app/models/Wishlist.php';

$GLOBALS['db'] = new Database();
$w = new Wishlist();

$userId = 1;
$productId = 127;

echo "ADD: ";
var_export($w->addToWishlist($userId, $productId));
echo "\nCOUNT: " . $w->getWishlistCount($userId) . "\n";
echo "IDS: " . implode(',', $w->getProductIds($userId)) . "\n";
$items = $w->getUserWishlist($userId);
echo "ITEMS: " . count($items) . "\n";
if (!empty($items[0])) {
    echo "FIRST id=" . $items[0]['id'] . " name=" . $items[0]['name'] . " product_id=" . $items[0]['product_id'] . "\n";
}

echo "TOGGLE REMOVE: ";
print_r($w->toggle($userId, $productId));
echo "COUNT AFTER: " . $w->getWishlistCount($userId) . "\n";

echo "TOGGLE ADD: ";
print_r($w->toggle($userId, $productId));
echo "ADD SECOND: ";
var_export($w->addToWishlist($userId, 126));
echo "\nFINAL COUNT: " . $w->getWishlistCount($userId) . "\n";
$items = $w->getUserWishlist($userId);
foreach ($items as $it) {
    echo "- {$it['id']} {$it['name']}\n";
}
