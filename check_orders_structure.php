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
    
    // Check if orders table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'orders'");
    if($stmt->rowCount() > 0) {
        echo "orders table exists.\n";
        
        // Show table structure
        $stmt = $pdo->query("DESCRIBE orders");
        echo "\nTable structure for orders:\n";
        echo str_pad("Field", 30) . str_pad("Type", 30) . str_pad("Null", 8) . str_pad("Key", 8) . str_pad("Default", 15) . "Extra\n";
        echo str_repeat("-", 100) . "\n";
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo str_pad($row['Field'], 30) . 
                 str_pad($row['Type'], 30) . 
                 str_pad($row['Null'], 8) . 
                 str_pad($row['Key'], 8) . 
                 str_pad($row['Default'] ?? 'NULL', 15) . 
                 $row['Extra'] . "\n";
        }
    } else {
        echo "orders table does not exist.\n";
    }
    
} catch(PDOException $e) {
    die("ERROR: " . $e->getMessage());
}
?>
