<?php
// Simple script to check if about_store table exists and has data

try {
    // Try to include the database configuration
    require_once 'config/database.php';
    
    // Create database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // Check if about_store table exists
    $checkTable = $db->query("SHOW TABLES LIKE 'about_store'");
    
    if ($checkTable->rowCount() > 0) {
        echo "✅ about_store table exists.\n";
        
        // Check if table has data
        $result = $db->query("SELECT COUNT(*) as count FROM about_store");
        $row = $result->fetch(PDO::FETCH_ASSOC);
        
        echo "📊 Number of entries in about_store table: " . $row['count'] . "\n";
        
        if ($row['count'] > 0) {
            // Show first few entries
            $entries = $db->query("SELECT id, title, created_at FROM about_store ORDER BY created_at DESC LIMIT 3");
            echo "\nRecent entries:\n";
            while ($entry = $entries->fetch(PDO::FETCH_ASSOC)) {
                echo "- ID: " . $entry['id'] . ", Title: " . $entry['title'] . ", Created: " . $entry['created_at'] . "\n";
            }
        } else {
            echo "\nℹ️ The about_store table exists but is empty. You'll need to add content through the admin panel.\n";
        }
    } else {
        echo "❌ about_store table does not exist. You'll need to run the database migrations.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
