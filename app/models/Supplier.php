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

    // Get all suppliers with their products (optimized)
    public function getAllSuppliersWithProducts() {
        try {
            // First get all suppliers
            $this->db->query('SELECT * FROM ' . $this->table . ' ORDER BY name ASC');
            $suppliers = $this->db->resultSet();
            
            if (empty($suppliers)) {
                return [];
            }
            
            // Get all products for these suppliers in one query
            $supplierIds = array_column($suppliers, 'id');
            $placeholders = rtrim(str_repeat('?,', count($supplierIds)), ',');
            
            $this->db->query('SELECT id, name, supplier_id FROM products WHERE supplier_id IN (' . $placeholders . ')');
            $this->db->execute($supplierIds);
            $products = $this->db->resultSet();
            
            // Group products by supplier_id
            $productsBySupplier = [];
            foreach ($products as $product) {
                $productsBySupplier[$product->supplier_id][] = $product;
            }
            
            // Combine suppliers with their products
            foreach ($suppliers as &$supplier) {
                $supplier->products = isset($productsBySupplier[$supplier->id]) 
                    ? $productsBySupplier[$supplier->id]
                    : [];
                
                // Add product_name for backward compatibility
                $supplier->product_name = !empty($supplier->products) ? $supplier->products[0]->name : '';
                
                // Ensure all expected fields exist
                $supplier->supplier_name = $supplier->name;
                $supplier->email = $supplier->email ?? '';
                $supplier->phone = $supplier->phone ?? '';
                $supplier->address = $supplier->address ?? '';
            }
            
            return $suppliers;
            
        } catch (Exception $e) {
            error_log('Error in Supplier::getAllSuppliersWithProducts - ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return [];
        }
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
    
    // Add a new supplier with product
    public function addSupplier($data) {
        try {
            error_log('Starting to add supplier with data: ' . print_r($data, true));
            
            // Start transaction
            $this->db->query('START TRANSACTION');
            
            // Add supplier
            $query = 'INSERT INTO ' . $this->table . ' 
                (name, email, phone, address, created_at, updated_at) 
                VALUES (:name, :email, :phone, :address, NOW(), NOW())';
                
            error_log('Executing query: ' . $query);
            $this->db->query($query);
            
            // Bind values
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email'] ?? null);
            $this->db->bind(':phone', $data['phone'] ?? null);
            $this->db->bind(':address', $data['address'] ?? null);
            
            if (!$this->db->execute()) {
                $error = $this->db->errorInfo();
                error_log('Error executing query: ' . print_r($error, true));
                $this->db->query('ROLLBACK');
                return false;
            }
            
            $supplierId = $this->db->lastInsertId();
            
            if ($supplierId && !empty($data['product_name'])) {
                try {
                    // Add product directly to the products table
                    $sql = "INSERT INTO products 
                            (name, description, sku, price, stock_quantity, status, category_id, brand_id, created_at, updated_at)
                            VALUES (:name, :description, :sku, :price, :stock_quantity, :status, :category_id, :brand_id, NOW(), NOW())";
                    
                    $this->db->query($sql);
                    
                    // Bind values
                    $this->db->bind(':name', $data['product_name']);
                    $this->db->bind(':description', 'Supplied by ' . $data['name']);
                    $this->db->bind(':sku', 'SUP' . time() . $supplierId); // Generate a simple SKU
                    $this->db->bind(':price', 0.00); // Default price
                    $this->db->bind(':stock_quantity', 0); // Default quantity
                    $this->db->bind(':status', 'active');
                    $this->db->bind(':category_id', 1); // Default category
                    $this->db->bind(':brand_id', 1); // Default brand
                    
                    if (!$this->db->execute()) {
                        error_log('Failed to add product for supplier');
                        $this->db->query('ROLLBACK');
                        return false;
                    }
                } catch (Exception $e) {
                    error_log('Error adding product: ' . $e->getMessage());
                    // Continue with the supplier addition even if product addition fails
                }
            }
            
            // Commit transaction
            $this->db->query('COMMIT');
            return $supplierId;
            
        } catch (Exception $e) {
            $this->db->query('ROLLBACK');
            error_log('Error in Supplier::addSupplier - ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }

    // Get supplier by ID
    public function getSupplierById($id) {
        try {
            // Get the supplier
            $this->db->query('SELECT * FROM ' . $this->table . ' WHERE id = :id');
            $this->db->bind(':id', $id);
            $supplier = $this->db->single();
            
            if ($supplier) {
                // Get products for this supplier
                $this->db->query('SELECT id, name FROM products WHERE supplier_id = :supplier_id');
                $this->db->bind(':supplier_id', $id);
                $products = $this->db->resultSet();
                
                // Add products array and first product name for backward compatibility
                $supplier->products = $products;
                $supplier->product_name = !empty($products) ? $products[0]->name : '';
                
                // Ensure all expected fields exist
                $supplier->supplier_name = $supplier->name;
                $supplier->email = $supplier->email ?? '';
                $supplier->phone = $supplier->phone ?? '';
                $supplier->address = $supplier->address ?? '';
            }
            
            return $supplier;
            
        } catch (Exception $e) {
            error_log('Error in Supplier::getSupplierById - ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            return null;
        }
    }

    // Add a new supplier (legacy method, use addSupplier instead)
    public function create($data) {
        return $this->addSupplier($data);
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
