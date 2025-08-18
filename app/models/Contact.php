<?php

class Contact {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Get all contacts
    public function getAll() {
        $this->db->query('SELECT * FROM contacts ORDER BY created_at DESC');
        return $this->db->resultSet();
    }
    
    // Get single contact by ID
    public function getById($id) {
        $this->db->query('SELECT * FROM contacts WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Create new contact
    public function create($data) {
        $this->db->query('INSERT INTO contacts (name, email, phone, subject, message, status, created_at) 
                         VALUES (:name, :email, :phone, :subject, :message, :status, NOW())');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':status', 'unread');
        
        return $this->db->execute();
    }
    
    // Update contact status
    public function updateStatus($id, $status) {
        $this->db->query('UPDATE contacts SET status = :status, updated_at = NOW() WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    // Delete contact
    public function delete($id) {
        $this->db->query('DELETE FROM contacts WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    // Get count by status
    public function getCountByStatus($status) {
        $this->db->query('SELECT COUNT(*) as count FROM contacts WHERE status = :status');
        $this->db->bind(':status', $status);
        $result = $this->db->single();
        return $result->count;
    }
}
