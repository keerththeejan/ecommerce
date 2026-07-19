<?php
require_once 'C:/wamp64/www/ecommerce/config/config.php';
require_once 'C:/wamp64/www/ecommerce/config/database.php';
require_once 'C:/wamp64/www/ecommerce/app/models/Model.php';
require_once 'C:/wamp64/www/ecommerce/app/models/Wishlist.php';

$db = new Database();
$GLOBALS['db'] = $db;

echo "=== DESCRIBE wishlist ===\n";
$db->query('DESCRIBE wishlist');
foreach ($db->resultSet() as $row) {
    $r = (array)$row;
    echo ($r['Field'] ?? $r['field'] ?? '?') . ' ' . ($r['Type'] ?? '') . "\n";
}

echo "\n=== COUNT ===\n";
$db->query('SELECT COUNT(*) AS c FROM wishlist');
$c = $db->single();
print_r($c);

echo "\n=== SAMPLE ROWS ===\n";
$db->query('SELECT w.id wid, w.user_id, w.product_id, p.id pid, p.name FROM wishlist w LEFT JOIN products p ON p.id = w.product_id ORDER BY w.id DESC LIMIT 15');
foreach ($db->resultSet() as $row) {
    print_r($row);
}

echo "\n=== MODEL FETCH TEST ===\n";
$w = new Wishlist();
$db->query('SELECT user_id FROM wishlist LIMIT 1');
$u = $db->single();
$userId = is_object($u) ? (int)$u->user_id : (int)($u['user_id'] ?? 0);
echo "userId=$userId\n";
if ($userId) {
    $items = $w->getUserWishlist($userId);
    echo 'items=' . count($items) . "\n";
    if (!empty($items[0])) {
        $first = $items[0];
        echo "type=" . gettype($first) . "\n";
        if (is_object($first)) {
            echo "id=" . ($first->id ?? 'missing') . " name=" . ($first->name ?? 'missing') . " wishlist_id=" . ($first->wishlist_id ?? 'missing') . "\n";
            echo "array cast keys: " . implode(',', array_keys((array)$first)) . "\n";
        } else {
            print_r($first);
        }
    }
    echo 'count=' . $w->getWishlistCount($userId) . "\n";
    echo 'ids=' . implode(',', $w->getProductIds($userId)) . "\n";
}
