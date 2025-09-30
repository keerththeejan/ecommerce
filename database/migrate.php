<?php
require_once __DIR__ . '/../config/config.php';

function runMigration($pdo, $migrationFile, $successMessage) {
    try {
        $sql = file_get_contents($migrationFile);
        $pdo->exec($sql);
        echo $successMessage . "\n";
        return true;
    } catch(PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
        return false;
    }
}

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    
    // Run migrations
    runMigration($pdo, __DIR__ . '/migrations/banners.sql', 'Banners table created successfully!');
    runMigration($pdo, __DIR__ . '/migrations/about_store.sql', 'About Store table created successfully!');
    runMigration($pdo, __DIR__ . '/migrations/add_user_activity_columns.sql', 'User activity columns added successfully!');
    
} catch(PDOException $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
}
