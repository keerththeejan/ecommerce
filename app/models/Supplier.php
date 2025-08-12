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
            return ['success' => false, 'message' => 'Failed to fetch suppliers'];
        }
    }

    // Get supplier by ID
    public function getSupplierById($id) {
        try {
            $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
            $this->db->bind(':id', $id);
            $result = $this->db->single();
            
            if ($result) {
                return ['success' => true, 'data' => $result];
            } else {
                return ['success' => false, 'message' => 'Supplier not found'];
            }
        } catch (Exception $e) {
            error_log('Error in Supplier::getSupplierById - ' . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }

    // Add a new supplier
    public function create($data) {
        try {
            // Validate required fields
            if (empty($data['name'])) {
                return ['success' => false, 'message' => 'Supplier name is required'];
            }

            // Check if supplier with same name already exists
            $this->db->query('SELECT id FROM ' . $this->table . ' WHERE name = :name');
            $this->db->bind(':name', $data['name']);
            if ($this->db->single()) {
                return ['success' => false, 'message' => 'Supplier with this name already exists'];
            }

            // Insert new supplier
            $this->db->query('INSERT INTO ' . $this->table . ' 
                (name, product_name, email, phone, address, created_at, updated_at) 
                VALUES (:name, :product_name, :email, :phone, :address, NOW(), NOW())');
            
            // Bind values
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':product_name', $data['product_name'] ?? '');
            $this->db->bind(':email', $data['email'] ?? '');
            $this->db->bind(':phone', $data['phone'] ?? '');
            $this->db->bind(':address', $data['address'] ?? '');
            
            if ($this->db->execute()) {
                return [
                    'success' => true, 
                    'message' => 'Supplier added successfully',
                    'id' => $this->db->lastInsertId()
                ];
            } else {
                throw new Exception('Failed to execute query');
            }
        } catch (Exception $e) {
            // Fallback if product_name column doesn't exist
            try {
                $this->db->query('INSERT INTO ' . $this->table . ' 
                    (name, email, phone, address, created_at, updated_at) 
                    VALUES (:name, :email, :phone, :address, NOW(), NOW())');
                
                $this->db->bind(':name', $data['name']);
                $this->db->bind(':email', $data['email'] ?? '');
                $this->db->bind(':phone', $data['phone'] ?? '');
                $this->db->bind(':address', $data['address'] ?? '');
                
                if ($this->db->execute()) {
                    return [
                        'success' => true, 
                        'message' => 'Supplier added successfully',
                        'id' => $this->db->lastInsertId()
                    ];
                } else {
                    throw new Exception('Failed to execute fallback query');
                }
            } catch (Exception $e2) {
                error_log('Error in Supplier::create - ' . $e->getMessage() . ' | Fallback failed - ' . $e2->getMessage());
                return ['success' => false, 'message' => 'Failed to add supplier'];
            }
        }
    }

    // Update a supplier
    public function update($data) {
        try {
            // Validate required fields
            if (empty($data['id']) || empty($data['name'])) {
                return ['success' => false, 'message' => 'Invalid supplier data'];
            }

            // Check if supplier exists
            $existing = $this->getSupplierById($data['id']);
            if (!$existing['success']) {
                return $existing;
            }

            // Check if another supplier with the same name exists
            $this->db->query('SELECT id FROM ' . $this->table . ' WHERE name = :name AND id != :id');
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':id', $data['id']);
            if ($this->db->single()) {
                return ['success' => false, 'message' => 'Another supplier with this name already exists'];
            }

            // Update supplier
            $this->db->query('UPDATE ' . $this->table . ' 
                SET name = :name, 
                    product_name = :product_name,
                    email = :email, 
                    phone = :phone, 
                    address = :address,
                    updated_at = NOW()
                WHERE id = :id');
            
            // Bind values
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':product_name', $data['product_name'] ?? '');
            $this->db->bind(':email', $data['email'] ?? '');
            $this->db->bind(':phone', $data['phone'] ?? '');
            $this->db->bind(':address', $data['address'] ?? '');
            
            if ($this->db->execute()) {
                return ['success' => true, 'message' => 'Supplier updated successfully'];
            } else {
                throw new Exception('Failed to execute update query');
            }
        } catch (Exception $e) {
            // Fallback if product_name column doesn't exist
            try {
                $this->db->query('UPDATE ' . $this->table . ' 
                    SET name = :name,
                        email = :email,
                        phone = :phone,
                        address = :address,
                        updated_at = NOW()
                    WHERE id = :id');
                
                $this->db->bind(':id', $data['id']);
                $this->db->bind(':name', $data['name']);
                $this->db->bind(':email', $data['email'] ?? '');
                $this->db->bind(':phone', $data['phone'] ?? '');
                $this->db->bind(':address', $data['address'] ?? '');
                
                if ($this->db->execute()) {
                    return ['success' => true, 'message' => 'Supplier updated successfully'];
                } else {
                    throw new Exception('Failed to execute fallback update');
                }
            } catch (Exception $e2) {
                error_log('Error in Supplier::update - ' . $e->getMessage() . ' | Fallback failed - ' . $e2->getMessage());
                return ['success' => false, 'message' => 'Failed to update supplier'];
            }
        }
    }

    // Delete a supplier
    public function delete($id) {
        try {
            // Check if supplier exists
            $existing = $this->getSupplierById($id);
            if (!$existing['success']) {
                return $existing; // Return error if supplier not found
            }

            // Delete supplier
            $this->db->query('DELETE FROM ' . $this->table . ' WHERE id = :id');
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                return ['success' => true, 'message' => 'Supplier deleted successfully'];
            } else {
                throw new Exception('Failed to execute delete query');
            }
        } catch (Exception $e) {
            error_log('Error in Supplier::delete - ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to delete supplier'];
        }
    }
    
    // Alias for update method to maintain backward compatibility
    public function updateSupplier($data) {
        return $this->update($data);
    }
    
    // Alias for delete method to maintain backward compatibility
    public function deleteSupplier($id) {
        return $this->delete($id);
    }

    // Search suppliers by name or email
    public function search($term) {
        try {
            $this->db->query('SELECT id, name, email, phone FROM ' . $this->table . ' 
                            WHERE name LIKE :term OR email LIKE :term 
                            ORDER BY name ASC');
            $this->db->bind(':term', '%' . $term . '%');
            $results = $this->db->resultSet();
            return ['success' => true, 'data' => $results];
        } catch (Exception $e) {
            error_log('Error in Supplier::search - ' . $e->getMessage());
            return ['success' => false, 'message' => 'Search failed', 'data' => []];
        }
    }
}