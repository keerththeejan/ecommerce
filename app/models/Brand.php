<?php
/**
 * Brand Model
 * Handles database operations for brands
 */
class Brand extends Model {
    /**
     * Get all brands
     * 
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @return array
     */
    public function getAll($orderBy = null, $order = 'ASC') {
        $orderBy = $orderBy ?: 'name';
        
        // Check if table exists, create if not
        if(!$this->db->tableExists('brands')) {
            return [];
        }
        
        if(!$this->db->query("SELECT * FROM brands ORDER BY {$orderBy} {$order}")) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get active brands
     * 
     * @return array
     */
    public function getActiveBrands() {
        // Check if table exists, create if not
        if(!$this->db->tableExists('brands')) {
            return [];
        }
        
        if(!$this->db->query("SELECT * FROM brands WHERE status = 'active' ORDER BY name ASC")) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get brand by ID
     * 
     * @param int $id Brand ID
     * @return array|false
     */
    public function getById($id) {
        $this->db->query("SELECT * FROM brands WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Get brand by slug
     * 
     * @param string $slug Brand slug
     * @return array|false
     */
    public function getBySlug($slug) {
        $this->db->query("SELECT * FROM brands WHERE slug = :slug");
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }
    
    /**
     * Create new brand
     * 
     * @param array $data Brand data
     * @return int|false
     */
    public function create($data) {
        // Prepare query
        $this->db->query("INSERT INTO brands (name, slug, description, logo, status, created_at, updated_at) 
                          VALUES (:name, :slug, :description, :logo, :status, NOW(), NOW())");
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':logo', $data['logo']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    /**
     * Update brand
     * 
     * @param int $id Brand ID
     * @param array $data Brand data
     * @return bool
     */
    public function update($id, $data) {
        // Prepare query
        $this->db->query("UPDATE brands 
                          SET name = :name, 
                              slug = :slug, 
                              description = :description, 
                              status = :status,
                              updated_at = NOW()
                          WHERE id = :id");
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $data['slug']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $id);
        
        // Execute
        return $this->db->execute();
    }
    
    /**
     * Update brand logo
     * 
     * @param int $id Brand ID
     * @param string $logo Logo path
     * @return bool
     */
    public function updateLogo($id, $logo) {
        // Prepare query
        $this->db->query("UPDATE brands SET logo = :logo, updated_at = NOW() WHERE id = :id");
        
        // Bind values
        $this->db->bind(':logo', $logo);
        $this->db->bind(':id', $id);
        
        // Execute
        return $this->db->execute();
    }
    
    /**
     * Delete brand
     * 
     * @param int $id Brand ID
     * @return bool
     */
    public function delete($id) {
        // Check if brand is used in products
        $this->db->query("SELECT COUNT(*) as count FROM products WHERE brand_id = :id");
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        
        if($result['count'] > 0) {
            return false; // Brand is in use
        }
        
        // Delete brand
        $this->db->query("DELETE FROM brands WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    /**
     * Get brands with pagination
     * 
     * @param int $page Page number
     * @param int $perPage Items per page
     * @param string $orderBy Order by field
     * @param string $order Order direction (ASC, DESC)
     * @return array
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = 'name', $order = 'ASC') {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        if(!$this->db->query("SELECT COUNT(*) as total FROM brands")) {
            return [
                'data' => [],
                'total' => 0,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => 0,
                'from' => 0,
                'to' => 0
            ];
        }
        
        $result = $this->db->single();
        $totalCount = $result ? $result['total'] : 0;
        
        // Calculate total pages
        $totalPages = ceil($totalCount / $perPage);
        
        // Get brands for current page
        if(!$this->db->query("SELECT * FROM brands ORDER BY {$orderBy} {$order} LIMIT :perPage OFFSET :offset")) {
            return [
                'data' => [],
                'total' => $totalCount,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => $totalPages,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $totalCount)
            ];
        }
        
        $this->db->bind(':perPage', $perPage, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        $brands = $this->db->resultSet();
        
        // Return pagination data
        return [
            'data' => $brands,
            'total' => $totalCount,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $totalPages,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $totalCount)
        ];
    }
    
    /**
     * Search brands
     * 
     * @param string $search Search term
     * @param int $page Page number
     * @param int $perPage Items per page
     * @return array
     */
    public function search($search, $page = 1, $perPage = 10) {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        if(!$this->db->query("SELECT COUNT(*) as total FROM brands WHERE name LIKE :search")) {
            return [
                'data' => [],
                'total' => 0,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => 0,
                'from' => 0,
                'to' => 0
            ];
        }
        
        $this->db->bind(':search', '%' . $search . '%');
        $result = $this->db->single();
        $totalCount = $result ? $result['total'] : 0;
        
        // Calculate total pages
        $totalPages = ceil($totalCount / $perPage);
        
        // Get brands for current page
        if(!$this->db->query("SELECT * FROM brands WHERE name LIKE :search ORDER BY name ASC LIMIT :perPage OFFSET :offset")) {
            return [
                'data' => [],
                'total' => $totalCount,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => $totalPages,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $totalCount)
            ];
        }
        
        $this->db->bind(':search', '%' . $search . '%');
        $this->db->bind(':perPage', $perPage, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        $brands = $this->db->resultSet();
        
        // Return pagination data
        return [
            'data' => $brands,
            'total' => $totalCount,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $totalPages,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $totalCount)
        ];
    }
    
    /**
     * Generate unique slug
     * 
     * @param string $name Brand name
     * @param int $id Brand ID for updates
     * @return string
     */
    public function generateSlug($name, $id = null) {
        // Generate initial slug
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $name)));
        
        // Check if slug exists
        $this->db->query("SELECT COUNT(*) as count FROM brands WHERE slug = :slug" . ($id ? " AND id != :id" : ""));
        $this->db->bind(':slug', $slug);
        if($id) {
            $this->db->bind(':id', $id);
        }
        $result = $this->db->single();
        
        // If slug exists, append number
        if($result['count'] > 0) {
            $i = 1;
            do {
                $newSlug = $slug . '-' . $i;
                $this->db->query("SELECT COUNT(*) as count FROM brands WHERE slug = :slug" . ($id ? " AND id != :id" : ""));
                $this->db->bind(':slug', $newSlug);
                if($id) {
                    $this->db->bind(':id', $id);
                }
                $result = $this->db->single();
                $i++;
            } while($result['count'] > 0);
            $slug = $newSlug;
        }
        
        return $slug;
    }
    
    /**
     * Get products by brand
     * 
     * @param int $brandId Brand ID
     * @param int $page Page number
     * @param int $perPage Items per page
     * @return array
     */
    public function getProductsByBrand($brandId, $page = 1, $perPage = 12) {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        if(!$this->db->query("SELECT COUNT(*) as total FROM products WHERE brand_id = :brand_id AND status = 'active'")) {
            return [
                'data' => [],
                'total' => 0,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => 0,
                'from' => 0,
                'to' => 0
            ];
        }
        
        $this->db->bind(':brand_id', $brandId);
        $result = $this->db->single();
        $totalCount = $result ? $result['total'] : 0;
        
        // Calculate total pages
        $totalPages = ceil($totalCount / $perPage);
        
        // Get products for current page
        if(!$this->db->query("SELECT * FROM products WHERE brand_id = :brand_id AND status = 'active' ORDER BY name ASC LIMIT :perPage OFFSET :offset")) {
            return [
                'data' => [],
                'total' => $totalCount,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => $totalPages,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $totalCount)
            ];
        }
        
        $this->db->bind(':brand_id', $brandId);
        $this->db->bind(':perPage', $perPage, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        $products = $this->db->resultSet();
        
        // Return pagination data
        return [
            'data' => $products,
            'total' => $totalCount,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => $totalPages,
            'from' => $offset + 1,
            'to' => min($offset + $perPage, $totalCount)
        ];
    }
}
