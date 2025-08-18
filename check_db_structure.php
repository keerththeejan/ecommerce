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
    
    // Check if purchase_payments table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'purchase_payments'");
    if($stmt->rowCount() > 0) {
        echo "purchase_payments table exists.\n";
        
        // Show table structure
        $stmt = $pdo->query("DESCRIBE purchase_payments");
        echo "\nTable structure for purchase_payments:\n";
        echo str_pad("Field", 20) . str_pad("Type", 30) . str_pad("Null", 8) . str_pad("Key", 8) . str_pad("Default", 15) . "Extra\n";
        echo str_repeat("-", 90) . "\n";
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo str_pad($row['Field'], 20) . 
                 str_pad($row['Type'], 30) . 
                 str_pad($row['Null'], 8) . 
                 str_pad($row['Key'], 8) . 
                 str_pad($row['Default'] ?? 'NULL', 15) . 
                 $row['Extra'] . "\n";
        }
    } else {
        echo "purchase_payments table does not exist.\n";
    }
    
    // Check if purchase table has payment_status and due_date columns
    $stmt = $pdo->query("SHOW COLUMNS FROM purchase LIKE 'payment_status'");
    if($stmt->rowCount() > 0) {
        echo "\npayment_status column exists in purchase table.\n";
    } else {
        echo "\npayment_status column does not exist in purchase table.\n";
    }
    
    $stmt = $pdo->query("SHOW COLUMNS FROM purchase LIKE 'due_date'");
    if($stmt->rowCount() > 0) {
        echo "due_date column exists in purchase table.\n";
    } else {
        echo "due_date column does not exist in purchase table.\n";
    }
    
} catch(PDOException $e) {
    die("ERROR: " . $e->getMessage());
}
?>
