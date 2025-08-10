<?php
// Database configuration - using default WAMP MySQL settings
$db_config = [
    'host' => '127.0.0.1',
    'dbname' => 'ecommerce',
    'username' => 'root',
    'password' => ''
];

try {
    $conn = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};",
        $db_config['username'],
        $db_config['password']
    );
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'about_store'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "Table 'about_store' exists.\n";
        
        // Get count of entries
        $countStmt = $conn->query("SELECT COUNT(*) as count FROM about_store");
        $count = $countStmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "Number of entries in about_store: " . $count . "\n";
        
        // Show first few entries
        if ($count > 0) {
            $entriesStmt = $conn->query("SELECT id, title, created_at FROM about_store ORDER BY created_at DESC LIMIT 3");
            $entries = $entriesStmt->fetchAll(PDO::FETCH_ASSOC);
            echo "\nLatest entries:\n";
            print_r($entries);
        }
    } else {
        echo "Table 'about_store' does not exist.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
