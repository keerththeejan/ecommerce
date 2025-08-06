<?php
require_once __DIR__ . '/../config/config.php';

// Create a PDO instance
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if tax_id column already exists
    $checkColumn = $pdo->query("SHOW COLUMNS FROM categories LIKE 'tax_id'");
    if ($checkColumn->rowCount() == 0) {
        // Add tax_id column
        $sql = "ALTER TABLE categories 
                ADD COLUMN tax_id INT NULL,
                ADD CONSTRAINT fk_categories_tax 
                FOREIGN KEY (tax_id) REFERENCES tax_rates(id) 
                ON DELETE SET NULL";
        
        $pdo->exec($sql);
        echo "Successfully added tax_id column to categories table.\n";
    } else {
        echo "tax_id column already exists in categories table.\n";
    }
    
    // Verify the column was added
    $stmt = $pdo->query("DESCRIBE categories");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (in_array('tax_id', $columns)) {
        echo "tax_id column is now available in the categories table.\n";
    } else {
        echo "Warning: Failed to verify tax_id column in categories table.\n";
    }
    
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage() . "\n");
}

echo "Migration completed.\n";
