<?php
// Load configuration
require_once '../config/config.php';

// Database credentials
$host = DB_HOST;
$user = DB_USER;
$pass = DB_PASS;
$dbname = DB_NAME;

try {
    // Create connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Read the migration file
    $migration = file_get_contents('migrations/20240701_create_footer_content_table.sql');
    
    // Execute the migration
    $conn->exec($migration);
    
    echo "Migration completed successfully!\n";
} catch(PDOException $e) {
    die("Migration failed: " . $e->getMessage() . "\n");
}
