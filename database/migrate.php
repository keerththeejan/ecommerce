<?php
require_once __DIR__ . '/../config/config.php';

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    
    // Read and execute the SQL file
    $sql = file_get_contents(__DIR__ . '/migrations/banners.sql');
    $pdo->exec($sql);
    echo "Banners table created successfully!\n";
} catch(PDOException $e) {
    echo "Error creating banners table: " . $e->getMessage() . "\n";
}
