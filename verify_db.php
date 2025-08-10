<?php
// Verify database connection with application credentials

// Application database credentials
$db_config = [
    'host' => 'localhost',
    'dbname' => 'ecommerce30',
    'username' => 'root',
    'password' => '1234'
];

try {
    // Try to connect to MySQL server first (without selecting a database)
    $pdo = new PDO(
        "mysql:host={$db_config['host']}",
        $db_config['username'],
        $db_config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    echo "✅ Successfully connected to MySQL server\n";
    
    // Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE 'ecommerce30'");
    
    if ($stmt->rowCount() > 0) {
        echo "✅ Database 'ecommerce30' exists\n";
        
        // Now connect to the specific database
        $pdo = new PDO(
            "mysql:host={$db_config['host']};dbname={$db_config['dbname']}",
            $db_config['username'],
            $db_config['password'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        
        // Check if about_store table exists
        $tableCheck = $pdo->query("SHOW TABLES LIKE 'about_store'");
        
        if ($tableCheck->rowCount() > 0) {
            echo "✅ about_store table exists\n";
            
            // Check if table has data
            $count = $pdo->query("SELECT COUNT(*) as count FROM about_store")->fetch(PDO::FETCH_ASSOC);
            echo "📊 Number of entries in about_store: " . $count['count'] . "\n";
            
            if ($count['count'] > 0) {
                // Show first few entries
                $entries = $pdo->query("SELECT id, title, created_at FROM about_store ORDER BY created_at DESC LIMIT 3");
                echo "\nRecent entries:\n";
                foreach ($entries as $entry) {
                    echo "- ID: " . $entry['id'] . ", Title: " . $entry['title'] . ", Created: " . $entry['created_at'] . "\n";
                }
            } else {
                echo "\nℹ️ The about_store table is empty. You need to add content through the admin panel.\n";
                echo "   Go to: " . URLROOT . "?controller=aboutStore\n";
            }
        } else {
            echo "❌ about_store table does not exist in the ecommerce30 database.\n";
            echo "   You may need to run database migrations or import the database schema.\n";
        }
        
    } else {
        echo "❌ Database 'ecommerce30' does not exist.\n";
        echo "   You need to create the database and import the schema.\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        echo "   Please check your database username and password in config/config.php\n";
    } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "   The database 'ecommerce30' doesn't exist. You need to create it first.\n";
    }
}
?>
