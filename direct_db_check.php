<?php
// Direct database check without relying on application config

try {
    // Using PDO directly with default WAMP MySQL settings
    $pdo = new PDO('mysql:host=localhost;dbname=ecommerce;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if about_store table exists
    $tableCheck = $pdo->query("SHOW TABLES LIKE 'about_store'");
    
    if ($tableCheck->rowCount() > 0) {
        echo "✅ about_store table exists.\n";
        
        // Check if table has data
        $count = $pdo->query("SELECT COUNT(*) as count FROM about_store")->fetch(PDO::FETCH_ASSOC);
        echo "📊 Number of entries: " . $count['count'] . "\n";
        
        if ($count['count'] > 0) {
            // Show first few entries
            $entries = $pdo->query("SELECT id, title, created_at FROM about_store ORDER BY created_at DESC LIMIT 3");
            echo "\nRecent entries:\n";
            foreach ($entries as $entry) {
                echo "- ID: " . $entry['id'] . ", Title: " . $entry['title'] . ", Created: " . $entry['created_at'] . "\n";
            }
        } else {
            echo "\nℹ️ The about_store table is empty. Please add content through the admin panel.\n";
        }
    } else {
        echo "❌ about_store table does not exist. You need to run the database migrations.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
    
    // Try to list all tables to help with debugging
    try {
        $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        echo "\nAvailable tables in the database:\n";
        foreach ($tables as $table) {
            echo "- " . $table . "\n";
        }
    } catch (Exception $e) {
        // Ignore errors in this fallback
    }
}
?>
