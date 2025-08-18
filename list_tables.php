<?php
// Database configuration
$host = 'localhost';
$dbname = 'ecommerce30';
$username = 'root';
$password = '1234';

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "No tables found in the database.\n";
    } else {
        echo "Tables in database:\n";
        foreach ($tables as $table) {
            echo "- $table\n";
        }
    }
    
} catch(PDOException $e) {
    die("ERROR: " . $e->getMessage());
}
?>
