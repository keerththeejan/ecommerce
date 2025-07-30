<?php

class Supplier {
    private $db;
    private $table = 'suppliers';

    public function __construct($db = null) {
        if ($db instanceof Database) {
            $this->db = $db;
        } else {
            $this->db = new Database;
        }
    }
    
    /**
     * Set the database connection
     * @param Database $db Database connection
     */
    public function setDb($db) {
        $this->db = $db;
    }

    // Get all suppliers
    public function getAllSuppliers() {
        try {
            $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY name ASC');
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in Supplier::getAllSuppliers - ' . $e->getMessage());
            return [];
        }
    }

    // Get supplier by ID
    public function getSupplierById($id) {
        try {
            $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (Exception $e) {
            error_log('Error in Supplier::getSupplierById - ' . $e->getMessage());
            return null;
        }
    }

    // Add a new supplier
    public function create($data) {
        try {
            $this->db->query('INSERT INTO ' . $this->table . ' 
                (name, email, phone, address, created_at, updated_at) 
                VALUES (:name, :email, :phone, :address, NOW(), NOW())');
            
            // Bind values
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email'] ?? null);
            $this->db->bind(':phone', $data['phone'] ?? null);
            $this->db->bind(':address', $data['address'] ?? null);
            
            // Execute
            return $this->db->execute() ? $this->db->lastInsertId() : false;
        } catch (Exception $e) {
            error_log('Error in Supplier::create - ' . $e->getMessage());
            return false;
        }
    }

    // Update a supplier
    public function update($data) {
        try {
            $this->db->query('UPDATE ' . $this->table . ' 
                SET name = :name, 
                    email = :email, 
                    phone = :phone, 
                    address = :address,
                    updated_at = NOW()
                WHERE id = :id');
            
            // Bind values
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email'] ?? null);
            $this->db->bind(':phone', $data['phone'] ?? null);
            $this->db->bind(':address', $data['address'] ?? null);
            
            // Execute
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in Supplier::update - ' . $e->getMessage());
            return false;
        }
    }

    // Delete a supplier
    public function delete($id) {
        try {
            $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error in Supplier::delete - ' . $e->getMessage());
            return false;
        }
    }

    // Search suppliers by name or email
    public function search($term) {
        try {
            $this->db->query('SELECT id, name, email, phone FROM ' . $this->table . ' 
                            WHERE name LIKE :term OR email LIKE :term 
                            ORDER BY name ASC');
            $this->db->bind(':term', '%' . $term . '%');
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log('Error in Supplier::search - ' . $e->getMessage());
            return [];
        }
    }
}
