<?php
// Load database configuration
require_once __DIR__ . '/config/database.php';

// Set content type to HTML
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html>
<html>
<head>
    <title>User Activity Migration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .success { color: #28a745; }
        .error { color: #dc3545; }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Activity Migration Tool</h1>
        <div class="card mt-4">
            <div class="card-body">';

try {
    // Create database connection
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    
    // SQL to add columns
    $migrations = [
        'Adding last_activity column' => 
            "ALTER TABLE users ADD COLUMN IF NOT EXISTS last_activity DATETIME NULL DEFAULT NULL AFTER updated_at",
            
        'Adding ip_address column' => 
            "ALTER TABLE users ADD COLUMN IF NOT EXISTS ip_address VARCHAR(45) NULL DEFAULT NULL AFTER last_activity",
            
        'Adding user_agent column' => 
            "ALTER TABLE users ADD COLUMN IF NOT EXISTS user_agent TEXT NULL DEFAULT NULL AFTER ip_address",
            
        'Creating index on last_activity' => 
            "CREATE INDEX IF NOT EXISTS idx_last_activity ON users(last_activity)",
            
        'Setting default last_activity for existing users' => 
            "UPDATE users SET last_activity = NOW() WHERE last_activity IS NULL"
    ];
    
    // Execute each migration
    foreach ($migrations as $description => $sql) {
        echo "<p>Executing: <strong>{$description}</strong>... ";
        try {
            $pdo->exec($sql);
            echo "<span class='success'>✓ Success</span></p>";
        } catch (PDOException $e) {
            echo "<span class='error'>✗ Failed: " . htmlspecialchars($e->getMessage()) . "</span></p>";
        }
    }
    
    echo "<div class='alert alert-success mt-4'><h4>✓ Migration completed successfully!</h4>";
    echo "<p class='mb-0'>The user activity tracking feature has been set up. You can now <a href='" . BASE_URL . "?controller=user&action=active' class='alert-link'>view active users</a>.</p></div>";
    
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'><h4>Migration Failed</h4>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database configuration in <code>config/database.php</code> and make sure the database user has sufficient privileges.</p></div>";
}

echo '            </div>
        </div>
    </div>
</body>
</html>';
?>
