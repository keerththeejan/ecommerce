<?php
/**
 * Web-based Banner Database Migration
 * Access via: http://localhost/ecommerce/migrate_banners.php
 */

// Include the database configuration
require_once 'config/config.php';

// Check if user is admin for security
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die('Access denied. Admin access required.');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Banner Management Database Migration</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        pre { background: #f5f5f5; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>🎯 Banner Management Database Migration</h1>
    
    <?php
    if (isset($_POST['run_migration'])) {
        echo "<div class='info'>Running migration...</div>";
        
        try {
            // Include database class
            require_once 'config/Database.php';
            $db = new Database();
            
            // Read migration SQL
            $sqlFile = __DIR__ . '/database/migrations/create_banners_table.sql';
            if (!file_exists($sqlFile)) {
                throw new Exception("Migration file not found: $sqlFile");
            }
            
            $sql = file_get_contents($sqlFile);
            
            // Split and execute statements
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            $results = [];
            
            foreach ($statements as $index => $statement) {
                if (!empty($statement)) {
                    try {
                        $db->query($statement);
                        $db->execute();
                        $results[] = "✓ Statement " . ($index + 1) . ": " . substr($statement, 0, 50) . "...";
                    } catch (Exception $e) {
                        $results[] = "❌ Statement " . ($index + 1) . " Error: " . $e->getMessage();
                    }
                }
            }
            
            echo "<h2 class='success'>🎉 Migration Results:</h2>";
            echo "<pre>";
            foreach ($results as $result) {
                echo $result . "\n";
            }
            echo "</pre>";
            
            echo "<div class='success'><strong>Migration completed!</strong></div>";
            echo "<p>The following tables should now exist:</p>";
            echo "<ul>";
            echo "<li>banners - Main banner storage</li>";
            echo "<li>banner_settings - Slider configuration</li>";
            echo "<li>banner_analytics - Click/impression tracking</li>";
            echo "</ul>";
            
        } catch (Exception $e) {
            echo "<div class='error'>❌ Migration Error: " . $e->getMessage() . "</div>";
        }
    } else {
    ?>
    
    <div class='info'>
        <p>This migration will create the necessary tables for the Banner Management system:</p>
        <ul>
            <li><strong>banners</strong> - Store banner images, settings, and metadata</li>
            <li><strong>banner_settings</strong> - Configure slider behavior and display options</li>
            <li><strong>banner_analytics</strong> - Track impressions, clicks, and user engagement</li>
        </ul>
        <p><strong>Note:</strong> This will also insert sample data for demonstration purposes.</p>
    </div>
    
    <form method="post">
        <input type="submit" name="run_migration" value="🚀 Run Migration" 
               style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
    </form>
    
    <?php } ?>
    
    <hr>
    <p><a href="?controller=admin&action=dashboard">← Back to Admin Dashboard</a></p>
    
</body>
</html>
