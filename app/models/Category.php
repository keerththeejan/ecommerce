<?php
/**
 * Category Model
 */
class Category extends Model {
    protected $table = 'categories';
    
    /**
     * Ensure the categories table has a tax_id column with optional FK to tax_rates
     * This is safe to call before insert/update when we need to save tax_id.
     */
    public function ensureTaxIdColumn() {
        try {
            // Ensure base table exists
            if (!$this->db->tableExists($this->table)) {
                return false;
            }
            // Add tax_id column if missing
            if (!$this->db->columnExists($this->table, 'tax_id')) {
                $sql = "ALTER TABLE {$this->table} ADD COLUMN tax_id INT NULL AFTER parent_id";
                if ($this->db->query($sql)) {
                    $this->db->execute();
                }
            }
            // Add foreign key if tax_rates table exists and column now exists
            if ($this->db->columnExists($this->table, 'tax_id') && $this->db->tableExists('tax_rates')) {
                // Try to add FK constraint if not already present. Constraint name may vary; attempt guarded add.
                // Some MySQL versions don't support IF NOT EXISTS for FK; we'll catch errors silently.
                $fkSql = "ALTER TABLE {$this->table} ADD CONSTRAINT fk_categories_tax FOREIGN KEY (tax_id) REFERENCES tax_rates(id) ON DELETE SET NULL";
                if ($this->db->query($fkSql)) {
                    try { $this->db->execute(); } catch (Exception $e) { /* ignore if already exists */ }
                }
            }
            return true;
        } catch (Exception $e) {
            // Log but don't block the flow
            error_log('ensureTaxIdColumn error: ' . $e->getMessage());
            return false;
        }
    }
    
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
     * Get category by ID with tax information
     * 
     * @param int $id Category ID
     * @return array|bool The category data or false if not found
     */
    public function getWithTax($id) {
        // First get the basic category data with parent info
        $sql = "SELECT c.*, p.name as parent_name 
                FROM {$this->table} c
                LEFT JOIN {$this->table} p ON c.parent_id = p.id
                WHERE c.id = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':id', $id);
        $category = $this->db->single();
        
        if(!$category) {
            $this->lastError = "Category with ID {$id} not found";
            return false;
        }
        
        // If tax feature is available and tax_id is present, get the tax details
        if ($this->db->columnExists($this->table, 'tax_id') && !empty($category['tax_id']) && $this->db->tableExists('tax_rates')) {
            $taxSql = "SELECT name as tax_name, rate as tax_rate 
                      FROM tax_rates 
                      WHERE id = :tax_id";
            
            if($this->db->query($taxSql)) {
                $this->db->bind(':tax_id', $category['tax_id']);
                $taxData = $this->db->single();
                
                if ($taxData) {
                    $category = array_merge($category, $taxData);
                }
            }
        }
        
        return $category;
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
        $t0 = microtime(true);
        $sql = "SELECT COUNT(*) as count FROM products WHERE category_id = :id";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':id', $id);
        $result = $this->db->single();
        error_log('[Category::hasProducts] id=' . $id . ' count=' . ($result['count'] ?? 'n/a') . ' in ' . round((microtime(true)-$t0)*1000) . 'ms');
        
        return $result['count'] > 0;
    }

    /**
     * Reassign all products of a category to NULL (detach from category)
     *
     * @param int $id Category ID
     * @return bool
     */
    public function reassignProductsToNull($id) {
        // If products table doesn't exist, nothing to reassign
        if (!$this->db->tableExists('products')) {
            return true;
        }

        $t0 = microtime(true);
        $sql = "UPDATE products SET category_id = NULL WHERE category_id = :id";
        if (!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        if (!$this->db->bind(':id', $id)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        $ok = $this->db->execute();
        error_log('[Category::reassignProductsToNull] id=' . $id . ' ok=' . ($ok ? '1' : '0') . ' in ' . round((microtime(true)-$t0)*1000) . 'ms');
        return $ok;
    }

    /**
     * Reassign all products from one category to another category ID
     *
     * @param int $fromId Source category ID
     * @param int $toId Destination category ID
     * @return bool
     */
    public function reassignProductsToCategory($fromId, $toId) {
        if (!$this->db->tableExists('products')) {
            return true;
        }
        $t0 = microtime(true);
        $sql = "UPDATE products SET category_id = :toId WHERE category_id = :fromId";
        if (!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        if (!$this->db->bind(':toId', $toId) || !$this->db->bind(':fromId', $fromId)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        $ok = $this->db->execute();
        error_log('[Category::reassignProductsToCategory] from=' . $fromId . ' to=' . $toId . ' ok=' . ($ok ? '1' : '0') . ' in ' . round((microtime(true)-$t0)*1000) . 'ms');
        return $ok;
    }

    /**
     * Get or create a default 'Uncategorized' category and return its ID
     *
     * @return int|false
     */
    public function getOrCreateUncategorizedId() {
        // Ensure categories table exists
        if (!$this->db->tableExists($this->table)) {
            $this->lastError = 'Categories table not found';
            return false;
        }
        // Try to find existing
        $sql = "SELECT id FROM {$this->table} WHERE name = :name LIMIT 1";
        if (!$this->db->query($sql)) { $this->lastError = $this->db->getError(); return false; }
        $this->db->bind(':name', 'Uncategorized');
        $row = $this->db->single();
        if ($row && isset($row['id'])) {
            return (int)$row['id'];
        }
        // Create it
        $insert = "INSERT INTO {$this->table} (name, parent_id, status) VALUES (:name, NULL, 1)";
        if (!$this->db->query($insert)) { $this->lastError = $this->db->getError(); return false; }
        $this->db->bind(':name', 'Uncategorized');
        if (!$this->db->execute()) { $this->lastError = $this->db->getError(); return false; }
        return (int)$this->db->lastInsertId();
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
     * @param string|null $search Optional search keyword (searches name, description, parent_name, tax_name)
     * @return array
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = 'id', $order = 'DESC', $search = null) {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        $searchKeyword = trim((string)$search);
        
        $baseJoins = " FROM {$this->table} c\n                LEFT JOIN {$this->table} p ON c.parent_id = p.id";
        if ($this->db->columnExists($this->table, 'tax_id') && $this->db->tableExists('tax_rates')) {
            $baseJoins .= "\n                LEFT JOIN tax_rates t ON c.tax_id = t.id";
        }
        
        $whereClause = '';
        if ($searchKeyword !== '') {
            $whereClause = " WHERE (c.name LIKE :s1 OR c.description LIKE :s2 OR p.name LIKE :s3";
            if ($this->db->columnExists($this->table, 'tax_id') && $this->db->tableExists('tax_rates')) {
                $whereClause .= " OR t.name LIKE :s4";
            }
            $whereClause .= ")";
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total" . $baseJoins . $whereClause;
        
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
        
        if ($searchKeyword !== '') {
            $term = '%' . $searchKeyword . '%';
            $this->db->bind(':s1', $term);
            $this->db->bind(':s2', $term);
            $this->db->bind(':s3', $term);
            if ($this->db->columnExists($this->table, 'tax_id') && $this->db->tableExists('tax_rates')) {
                $this->db->bind(':s4', $term);
            }
        }
        $totalResult = $this->db->single();
        $total = $totalResult ? (int)($totalResult['total'] ?? 0) : 0;
        $totalPages = $perPage > 0 ? (int)ceil($total / $perPage) : 1;
        $offset = (int)$offset;
        $perPage = (int)$perPage;
        $limitVal = max(1, $perPage);
        $offsetVal = max(0, $offset);
        $orderBySafe = in_array($orderBy, ['id', 'name', 'status'], true) ? $orderBy : 'id';
        $orderSafe = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        
        // Build base query with parent info, and conditionally include tax info if available
        $select = "SELECT c.*, p.name as parent_name";
        $joins = $baseJoins;
        if ($this->db->columnExists($this->table, 'tax_id') && $this->db->tableExists('tax_rates')) {
            $select .= ", t.name as tax_name, t.rate as tax_rate";
        }
        $sql = $select . $joins . $whereClause . "\n                ORDER BY c.{$orderBySafe} {$orderSafe}\n                LIMIT " . $limitVal . " OFFSET " . $offsetVal;
                
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
        
        if ($searchKeyword !== '') {
            $term = '%' . $searchKeyword . '%';
            $this->db->bind(':s1', $term);
            $this->db->bind(':s2', $term);
            $this->db->bind(':s3', $term);
            if ($this->db->columnExists($this->table, 'tax_id') && $this->db->tableExists('tax_rates')) {
                $this->db->bind(':s4', $term);
            }
        }
        
        $data = $this->db->resultSet();
        
        return [
            'data' => $data,
            'total' => $total,
            'total_records' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }
}
