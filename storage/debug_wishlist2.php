<?php
require_once 'C:/wamp64/www/ecommerce/config/config.php';
require_once 'C:/wamp64/www/ecommerce/config/database.php';
$db = new Database();

$db->query('SELECT id, name, status FROM products WHERE id IN (62,66,67,68,118,120,121,125,126,127)');
echo "PRODUCTS:\n";
print_r($db->resultSet());

$db->query('SELECT MIN(id) mn, MAX(id) mx, COUNT(*) c FROM products');
echo "PRODUCT RANGE:\n";
print_r($db->single());

$db->query('SELECT id, name FROM categories WHERE id IN (62,66,67,68)');
echo "CATEGORIES:\n";
print_r($db->resultSet());

$db->query('SELECT id, username, role FROM users WHERE id = 1');
echo "USER 1:\n";
print_r($db->single());
