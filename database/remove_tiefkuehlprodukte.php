<?php
/**
 * One-time script: Remove category "Tiefkühlprodukte"
 * Run from project root: php database/remove_tiefkuehlprodukte.php
 */
require_once dirname(__DIR__) . '/config/config.php';
require_once CONFIG_PATH . 'database.php';

$db = new Database();
$pdo = $db->getConnection();

if (!$pdo) {
    die("Database connection failed.\n");
}

// Find category by name
$stmt = $pdo->prepare("SELECT id FROM categories WHERE name = 'Tiefkühlprodukte' LIMIT 1");
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    echo "Category 'Tiefkühlprodukte' not found. It may already be removed.\n";
    exit(0);
}

$categoryId = (int) $row['id'];

// Reassign products to NULL (or get uncategorized id if needed)
$stmt = $pdo->prepare("UPDATE products SET category_id = NULL WHERE category_id = ?");
$stmt->execute([$categoryId]);
$reassigned = $stmt->rowCount();

// Delete the category
$stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
$stmt->execute([$categoryId]);

echo "Category 'Tiefkühlprodukte' (id=$categoryId) removed. Products reassigned: $reassigned\n";
