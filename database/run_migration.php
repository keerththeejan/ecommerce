<?php
/**
 * Banner Database Migration Runner
 * Executes the banner table creation SQL
 */

// Database configuration - WAMP defaults
$host = 'localhost';
$dbname = 'sivamgnb_sn'; // Use the actual database name from your system
$username = 'root';
$password = '';

try {
    // Connect to database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to database successfully.\n";
    
    // Read and execute the migration SQL
    $sqlFile = __DIR__ . '/migrations/create_banners_table.sql';
    if (file_exists($sqlFile)) {
        $sql = file_get_contents($sqlFile);
        
        // Split SQL into individual statements
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                echo "Executing: " . substr($statement, 0, 50) . "...\n";
                $pdo->exec($statement);
                echo "✓ Success\n";
            }
        }
        
        echo "\n🎉 Banner Management database migration completed successfully!\n";
        echo "Tables created:\n";
        echo "- banners\n";
        echo "- banner_settings\n";
        echo "- banner_analytics\n";
        echo "\nSample data inserted for demonstration.\n";
        
    } else {
        echo "❌ Migration file not found: $sqlFile\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
