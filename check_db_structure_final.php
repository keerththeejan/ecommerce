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
    } else {
        echo "purchase_payments table does not exist.\n";
    }
    
    // Check if purchases table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'purchases'");
    if($stmt->rowCount() > 0) {
        echo "purchases table exists.\n";
        
        // Check for payment_status column
        $stmt = $pdo->query("SHOW COLUMNS FROM `purchases` LIKE 'payment_status'");
        if($stmt->rowCount() > 0) {
            echo "payment_status column exists in purchases table.\n";
        } else {
            echo "payment_status column does not exist in purchases table.\n";
        }
        
        // Check for due_date column
        $stmt = $pdo->query("SHOW COLUMNS FROM `purchases` LIKE 'due_date'");
        if($stmt->rowCount() > 0) {
            echo "due_date column exists in purchases table.\n";
        } else {
            echo "due_date column does not exist in purchases table.\n";
        }
    } else {
        echo "purchases table does not exist.\n";
    }
    
} catch(PDOException $e) {
    die("ERROR: " . $e->getMessage());
}
?>
