<?php
// Set content type to HTML
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html><head><title>Database Migration</title><style>body{font-family: Arial, sans-serif; line-height: 1.6; margin: 20px;}</style></head><body>';
echo '<h1>Database Migration Tool</h1>';

// Load database configuration
require_once __DIR__ . '/config/database.php';

try {
    echo '<p>Connecting to database...</p>';
    
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo '<p>Connected to database successfully.</p>';
    
    // SQL to add columns
    $sql = [
        "ALTER TABLE users 
         ADD COLUMN IF NOT EXISTS last_activity DATETIME NULL DEFAULT NULL AFTER updated_at",
        
        "ALTER TABLE users 
         ADD COLUMN IF NOT EXISTS ip_address VARCHAR(45) NULL DEFAULT NULL AFTER last_activity",
        
        "ALTER TABLE users 
         ADD COLUMN IF NOT EXISTS user_agent TEXT NULL DEFAULT NULL AFTER ip_address",
        
        "CREATE INDEX IF NOT EXISTS idx_last_activity ON users(last_activity)"
    ];
    
    // Execute each SQL statement
    foreach ($sql as $query) {
        echo '<p>Executing: ' . htmlspecialchars($query) . '...</p>';
        $pdo->exec($query);
        echo '<p style="color: green;">✓ Success</p>';
    }
    
    echo '<h2 style="color: green;">Migration completed successfully!</h2>';
    
} catch (PDOException $e) {
    echo '<p style="color: red;">Error: ' . htmlspecialchars($e->getMessage()) . '</p>';
    echo '<p>SQL State: ' . htmlspecialchars($e->errorInfo[0] ?? '') . '</p>';
    echo '<p>Error Code: ' . htmlspecialchars($e->errorInfo[1] ?? '') . '</p>';
    echo '<p>Error Message: ' . htmlspecialchars($e->errorInfo[2] ?? '') . '</p>';
}

echo '</body></html>';
