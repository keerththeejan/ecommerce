<?php
/**
 * Category Model
 */
class Category extends Model {
    protected $table = 'categories';
    
    /**
     * Get all categories including inactive ones
     * 
     * @return array
     */
    public function getAllCategories() {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return [];
        }
        
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get all active categories
     * 
     * @return array
     */
    public function getActiveCategories() {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return [];
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE status = :status ORDER BY name ASC";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        if(!$this->db->bind(':status', 1)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get parent categories
     * 
     * @return array
     */
    public function getParentCategories() {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return [];
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE parent_id IS NULL AND status = :status ORDER BY name ASC";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        if(!$this->db->bind(':status', 1)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get subcategories
     * 
     * @param int $parentId Parent category ID
     * @return array
     */
    public function getSubcategories($parentId) {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return [];
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE parent_id = :parent_id AND status = :status ORDER BY name ASC";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        if(!$this->db->bind(':parent_id', $parentId)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        if(!$this->db->bind(':status', 1)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get category with parent
     * 
     * @param int $id Category ID
     * @return array|bool
     */
    public function getCategoryWithParent($id) {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return false;
        }
        
        $sql = "SELECT c.*, p.name as parent_name, p.id as parent_id 
                FROM {$this->table} c
                LEFT JOIN {$this->table} p ON c.parent_id = p.id
                WHERE c.id = :id";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        if(!$this->db->bind(':id', $id)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        return $this->db->single();
    }
    
    /**
     * Get category tree
     * 
     * @return array
     */
    public function getCategoryTree() {
        // Check if table exists
        if(!$this->db->tableExists($this->table)) {
            return [];
        }
        
        // Get parent categories
        $parents = $this->getParentCategories();
        
        if(empty($parents)) {
            return [];
        }
        
        // Get subcategories for each parent
        foreach($parents as &$parent) {
            $parent['children'] = $this->getSubcategories($parent['id']);
        }
        
        return $parents;
    }
    
    /**
     * Get all categories with parent information
     * 
     * @return array
     */
    public function getAllWithParent() {
        $sql = "SELECT c.*, p.name as parent_name 
                FROM {$this->table} c
                LEFT JOIN {$this->table} p ON c.parent_id = p.id
                ORDER BY c.name ASC";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Check if category has products
     * 
     * @param int $id Category ID
     * @return bool
     */
    public function hasProducts($id) {
        $sql = "SELECT COUNT(*) as count FROM products WHERE category_id = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        
        return $result['count'] > 0;
    }
    
    /**
     * Check if category has subcategories
     * 
     * @param int $id Category ID
     * @return bool
     */
    public function hasSubcategories($id) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE parent_id = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        
        return $result['count'] > 0;
    }
    
    /**
     * Paginate categories
     * 
     * @param int $page Current page
     * @param int $perPage Items per page
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @return array
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = 'id', $order = 'DESC') {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        
        if(!$this->db->query($countSql)) {
            $this->lastError = $this->db->getError();
            return [
                'data' => [],
                'total' => 0,
                'current_page' => $page,
                'per_page' => $perPage,
                'total_pages' => 0
            ];
        }
        
        $totalResult = $this->db->single();
        $total = $totalResult['total'];
        $totalPages = ceil($total / $perPage);
        
        // Get categories with parent info
        $sql = "SELECT c.*, p.name as parent_name 
                FROM {$this->table} c
                LEFT JOIN {$this->table} p ON c.parent_id = p.id
                ORDER BY c.{$orderBy} {$order}
                LIMIT :limit OFFSET :offset";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [
                'data' => [],
                'total' => $total,
                'current_page' => $page,
                'per_page' => $perPage,
                'total_pages' => $totalPages
            ];
        }
        
        $this->db->bind(':limit', $perPage);
        $this->db->bind(':offset', $offset);
        
        $data = $this->db->resultSet();
        
        return [
            'data' => $data,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }
}
