<?php
class ContactInfo {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAll() {
        $this->db->query('SELECT * FROM contact_info ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getLatest() {
        $this->db->query('SELECT * FROM contact_info ORDER BY created_at DESC LIMIT 1');
        return $this->db->single();
    }

    public function getById($id) {
        $this->db->query('SELECT * FROM contact_info WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query('INSERT INTO contact_info (address, phone, email, hours_weekdays, hours_weekends, map_embed, created_at, updated_at) VALUES (:address, :phone, :email, :hours_weekdays, :hours_weekends, :map_embed, NOW(), NOW())');
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':hours_weekdays', $data['hours_weekdays']);
        $this->db->bind(':hours_weekends', $data['hours_weekends']);
        $this->db->bind(':map_embed', $data['map_embed'] ?? null);
        return $this->db->execute();
    }

    public function update($id, $data) {
        $this->db->query('UPDATE contact_info SET address = :address, phone = :phone, email = :email, hours_weekdays = :hours_weekdays, hours_weekends = :hours_weekends, map_embed = :map_embed, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':hours_weekdays', $data['hours_weekdays']);
        $this->db->bind(':hours_weekends', $data['hours_weekends']);
        $this->db->bind(':map_embed', $data['map_embed'] ?? null);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query('DELETE FROM contact_info WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Ensure the contact_info table exists (create if missing)
     */
    public function ensureTable() {
        $sql = "CREATE TABLE IF NOT EXISTS contact_info (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  address TEXT NOT NULL,
  phone VARCHAR(100) NOT NULL,
  email VARCHAR(191) NOT NULL,
  hours_weekdays VARCHAR(191) NOT NULL,
  hours_weekends VARCHAR(191) NOT NULL,
  map_embed TEXT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->db->query($sql);
        return $this->db->execute();
    }
}
