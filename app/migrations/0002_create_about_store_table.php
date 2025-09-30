<?php
class CreateAboutStoreTable {
    public function up() {
        $db = Database::getInstance();
        $sql = "CREATE TABLE IF NOT EXISTS about_store (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            image_path VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";
        $db->query($sql);
    }

    public function down() {
        $db = Database::getInstance();
        $sql = "DROP TABLE IF EXISTS about_store";
        $db->query($sql);
    }
}
