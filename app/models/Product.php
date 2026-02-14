<?php
/**
 * Product Model
 */

// Prevent multiple inclusions
if (class_exists('Product')) {
    return;
}

class Product extends Model {
    
    /**
     * @var Database Database connection
     */
    protected $db;
    
    /**
     * @var string Database table name
     */
    protected $table = 'products';
    
    /**
     * @var string Last error message
     */
    protected $lastError = '';
    
    /**
     * Get products by supplier ID
     * 
     * @param int $supplierId The ID of the supplier
     * @return array Array of products for the given supplier
     */
    public function getProductsBySupplierId($supplierId) {
        try {
            error_log("Fetching products for supplier ID: " . $supplierId);
            
            // First, verify the supplier exists
            $this->db->query("SELECT id FROM suppliers WHERE id = :supplier_id");
            $this->db->bind(':supplier_id', $supplierId);
            $supplier = $this->db->single();
            
            if (!$supplier) {
                error_log("Supplier with ID {$supplierId} not found");
                return [];
            }
            
            // Get all products for this supplier, including inactive ones
            $sql = "SELECT p.id, p.name, p.code, p.price, p.status, p.supplier_id 
                   FROM {$this->table} p 
                   WHERE p.supplier_id = :supplier_id 
                   ORDER BY p.name ASC";
            
            $this->db->query($sql);
            $this->db->bind(':supplier_id', $supplierId);
            
            $products = $this->db->resultSet();
            
            error_log("Found " . count($products) . " products for supplier ID: " . $supplierId);
            
            // If no products found, try to get any product to see if the table has data
            if (empty($products)) {
                $this->db->query("SELECT COUNT(*) as count FROM {$this->table}");
                $result = $this->db->single();
                error_log("Total products in database: " . ($result ? $result->count : 0));
                
                $this->db->query("SELECT * FROM {$this->table} LIMIT 5");
                $sampleProducts = $this->db->resultSet();
                error_log("Sample products: " . print_r($sampleProducts, true));
            }
            
            return $products;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::getProductsBySupplierId - ' . $this->lastError);
            return [];
        }
    }

    /**
     * Get all products with optional pagination
     * 
     * @param int $page Page number (0 for no pagination)
     * @param int $perPage Number of items per page
     * @param bool $includeInactive Whether to include inactive products
     * @return array Array of products with pagination info if paginated, or just products array
     */
    public function getAllProducts($page = 0, $perPage = 12, $includeInactive = false) {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                   FROM {$this->table} p
                   LEFT JOIN categories c ON p.category_id = c.id";
            
            $where = [];
            if (!$includeInactive) {
                $where[] = "p.status = 'active'";
            }
            // Only show products with a sale price
            $where[] = "p.sale_price IS NOT NULL AND p.sale_price > 0";
            
            if (!empty($where)) {
                $sql .= " WHERE " . implode(' AND ', $where);
            }
            
            // If page is 0, return all results without pagination
            if ($page === 0) {
                $sql .= " ORDER BY p.name ASC";
                $this->db->query($sql);
                return $this->db->resultSet();
            }
            
            // Otherwise, handle pagination
            $offset = ($page - 1) * $perPage;
            
            // Get total count for pagination
            $countSql = "SELECT COUNT(*) as total FROM {$this->table} p";
            if (!empty($where)) {
                $countSql .= " WHERE " . implode(' AND ', $where);
            }
            
            $this->db->query($countSql);
            $result = $this->db->single();
            $total = is_object($result) ? $result->total : 0;
            $totalPages = ceil($total / $perPage);
            
            // Add pagination to main query
            $sql .= " ORDER BY p.created_at DESC LIMIT :limit OFFSET :offset";
            $this->db->query($sql);
            $this->db->bind(':limit', $perPage);
            $this->db->bind(':offset', $offset);
            
            $products = $this->db->resultSet();
            
            return [
                'data' => $products,
                'current_page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => (int)$total,
                'last_page' => $totalPages,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ];
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::getAllProducts - ' . $this->lastError);
            
            if ($page === 0) {
                return [];
            }
            
            return [
                'data' => [],
                'current_page' => 1,
                'per_page' => $perPage,
                'total' => 0,
                'last_page' => 1,
                'from' => 0,
                'to' => 0
            ];
        }
    }
    
    /**
     * Get all products with stock information
     * 
     * @return array Array of products with stock data
     */
    public function getAllProductsWithStock() {
        return $this->getAllProducts(0, 0, true);
    }
    
    /**
     * Get product by ID
     * 
     * @param int $id Product ID
     * @return array|bool Product data or false if not found
     */
    public function getById($id) {
        try {
            $this->db->query("SELECT * FROM {$this->table} WHERE id = :id");
            $this->db->bind(':id', $id);
            
            $result = $this->db->single();
            return $result ? (array)$result : false;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::getById - ' . $this->lastError);
            return false;
        }
    }
    
    /**
     * Get product by ID with category information
     * 
     * @param int $id Product ID
     * @return array|bool Product data or false if not found
     */
    public function getProductWithCategory($id) {
        try {
            $sql = "SELECT p.*, c.name as category_name, co.name AS country_name 
                   FROM {$this->table} p
                   LEFT JOIN categories c ON p.category_id = c.id
                   LEFT JOIN countries co ON p.country_id = co.id
                   WHERE p.id = :id";
            
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            
            return $this->db->single();
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::getProductWithCategory - ' . $this->lastError);
            return false;
        }
    }
    
    /**
     * Get product by name
     * 
     * @param string $name Product name
     * @return array|bool Product data or false if not found
     */
    public function getProductByName($name) {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                   FROM {$this->table} p
                   LEFT JOIN categories c ON p.category_id = c.id
                   WHERE p.name = :name";
            
            $this->db->query($sql);
            $this->db->bind(':name', $name);
            
            $result = $this->db->single();
            return $result ? (array)$result : false;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::getProductByName - ' . $this->lastError);
            return false;
        }
    }
    
    /**
     * Get products with pagination
     * 
     * @param int $offset Offset for pagination
     * @param int $limit Number of products to return
     * @return array Array of products
     */
    public function getProductsWithPagination($offset = 0, $limit = 8) {
        try {
            $sql = "SELECT p.*, c.name as category_name, 
                   (SELECT image FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
                   FROM {$this->table} p
                   LEFT JOIN categories c ON p.category_id = c.id
                   WHERE p.status = 'active'
                   ORDER BY p.created_at DESC
                   LIMIT :limit OFFSET :offset";
            
            $this->db->query($sql);
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            
            $results = $this->db->resultSet();
            
            // Ensure we return an array of arrays, not objects
            $products = [];
            foreach ($results as $result) {
                $products[] = (array)$result;
            }
            
            return $products;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::getProductsWithPagination - ' . $this->lastError);
            return [];
        }
    }
    
    /**
     * Get current stock for a product
     * 
     * @param int $productId Product ID
     * @return int Current stock quantity
     */
    public function getProductStock($productId) {
        try {
            $sql = "SELECT stock_quantity FROM {$this->table} WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(':id', $productId);
            
            $result = $this->db->single();
            return $result ? (int)$result->stock_quantity : 0;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::getProductStock - ' . $this->lastError);
            return 0;
        }
    }
    
    /**
     * Constructor
     * @param Database|null $db Database connection (optional)
     */
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
    

    
    /**
     * Get all active products
     * 
     * @return array
     */
    public function getActiveProducts() {
        try {
            // Check if table exists by querying it directly
            try {
                $result = $this->db->query("SELECT 1 FROM {$this->table} LIMIT 1");
                if ($result === false) {
                    error_log('Products table does not exist or is not accessible');
                    return [];
                }
                
                // Get table structure for debugging
                $columns = [];
                $columnResult = $this->db->query("SHOW COLUMNS FROM {$this->table}");
                if ($columnResult !== false) {
                    $columns = $this->db->resultSet();
                    error_log('Products table columns: ' . print_r(array_column($columns, 'Field'), true));
                }
            } catch (Exception $e) {
                error_log('Error checking products table: ' . $e->getMessage());
                return [];
            }
            
            // Get only active products with all necessary fields
            $sql = "SELECT 
                        p.id,
                        p.name,
                        p.description,
                        p.category_id,
                        p.price,
                        p.sale_price,
                        p.stock_quantity,
                        p.image,
                        p.status,
                        p.sku,
                        IFNULL(c.name, 'Uncategorized') as category_name
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.status = 'active'
                    ORDER BY p.name ASC";

            if(!$this->db->query($sql)) {
                $error = $this->db->getError();
                error_log('Error in Product::getActiveProducts query: ' . $error);
                $this->lastError = $error;
                return [];
            }
            $results = $this->db->resultSet();
            // Normalize to array-of-arrays (POS view uses array access)
            $products = [];
            foreach ($results as $row) {
                $products[] = (array)$row;
            }
            
            // Log the number of products found
            error_log('Found ' . count($products) . ' active products');
            
            return $products;
            
        } catch (Exception $e) {
            $error = 'Error in Product::getActiveProducts - ' . $e->getMessage();
            error_log($error);
            $this->lastError = $error;
            return [];
        }
    }
    
    /**
     * Get product by SKU
     * 
     * @param string $sku Product SKU
     * @return object|bool Product object or false if not found
     */
    public function getProductBySku($sku) {
        $sql = "SELECT * FROM {$this->table} WHERE sku = :sku";
        
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        
        $this->db->bind(':sku', $sku);
        return $this->db->single();
    }
    
    /**
     * Get products by category
     * 
     * @param int $categoryId Category ID
     * @return array
     */
    public function getProductsByCategory($categoryId) {
        $categoryId = (int) $categoryId;
        return $this->getProductsByCategoryIds([$categoryId]);
    }

    /**
     * Get products by category IDs (e.g. main category + subcategories)
     *
     * @param int[] $categoryIds Category IDs
     * @return array
     */
    public function getProductsByCategoryIds(array $categoryIds) {
        // Check if tables exist
        if(!$this->db->tableExists($this->table) || !$this->db->tableExists('categories')) {
            return [];
        }
        $categoryIds = array_values(array_unique(array_map('intval', array_filter($categoryIds))));
        if (empty($categoryIds)) {
            return [];
        }
        $placeholders = [];
        foreach ($categoryIds as $i => $id) {
            $placeholders[] = ':cat_' . $i;
        }
        $inList = implode(',', $placeholders);
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id IN ({$inList}) AND p.status = 'active'
                ORDER BY p.name ASC";
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        foreach ($categoryIds as $i => $id) {
            if(!$this->db->bind(':cat_' . $i, $id, \PDO::PARAM_INT)) {
                $this->lastError = $this->db->getError();
                return [];
            }
        }
        return $this->db->resultSet();
    }
    
    /**
     * Search products
     * 
     * @param string $keyword Search keyword
     * @return array
     */
    public function searchProducts($keyword) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p.name LIKE :keyword OR p.description LIKE :keyword OR c.name LIKE :keyword)
                AND p.status = :status";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':keyword', '%' . $keyword . '%');
        $this->db->bind(':status', 'active');
        return $this->db->resultSet();
    }

    /**
     * Soft delete product: keep row (ID) for orders, clear image path, set inactive.
     * Caller should delete the image file from disk before or after.
     *
     * @param int $id Product ID
     * @return bool
     */
    public function softDelete($id) {
        if (!$this->getById($id)) {
            $this->lastError = "Product with ID {$id} not found";
            return false;
        }
        $sql = "UPDATE {$this->table} SET status = 'inactive', image = NULL WHERE {$this->primaryKey} = :id";
        if (!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return false;
        }
        $this->db->bind(':id', (int) $id);
        if (!$this->db->execute()) {
            $this->lastError = $this->db->getError();
            return false;
        }
        return true;
    }
    
    /**
     * Get product with category
     * 
     * @param int $id Product ID
     * @return array|bool
     */
    /**
     * Get products that are on sale
     * 
     * @param int $page Page number
     * @param int $perPage Items per page
     * @return array
     */
    public function getProductsOnSale($page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.sale_price > 0 AND p.sale_price < p.price 
                AND p.status = 'active'
                ORDER BY p.id DESC
                LIMIT :limit OFFSET :offset";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':limit', $perPage);
        $this->db->bind(':offset', $offset);
        
        $products = $this->db->resultSet();
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} 
                    WHERE sale_price > 0 AND sale_price < price 
                    AND status = 'active'";
        $this->db->query($countSql);
        $result = $this->db->single();
        $total = is_object($result) ? $result->total : (is_array($result) ? $result['total'] : 0);
        
        return [
            'data' => $products,
            'total' => (int)$total,
            'per_page' => (int)$perPage,
            'current_page' => (int)$page,
            'last_page' => (int)ceil($total / $perPage)
        ];
    }
    

    
    /**
     * Update product stock
     * 
     * @param int $id Product ID
     * @param float $newStock New stock quantity
     * @return bool True on success, false on failure
     */
    public function updateStock($id, $newStock) {
        try {
            // First check if product exists
            $product = $this->getById($id);
            if(!$product) {
                $this->lastError = "Product not found";
                return false;
            }
            
            // Ensure stock is a valid number
            if (!is_numeric($newStock) || $newStock < 0) {
                $this->lastError = "Invalid stock quantity";
                return false;
            }
            
            // Update stock
            $sql = "UPDATE {$this->table} SET stock_quantity = :quantity, updated_at = NOW() WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(':id', $id);
            $this->db->bind(':quantity', $newStock);
            
            $result = $this->db->execute();
            
            if(!$result) {
                $this->lastError = $this->db->getError();
                error_log('Error updating product stock: ' . $this->lastError);
            }
            
            return $result;
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::updateStock - ' . $this->lastError);
            return false;
        }
    }
    
    /**
     * Get low stock products
     * 
     * @param int $threshold Stock threshold
     * @return array
     */
    /**
     * Get low stock products
     * 
     * @param int $threshold Stock threshold
     * @return array
     */
    public function getLowStockProducts($threshold = 10) {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.stock_quantity <= :threshold
                    ORDER BY p.stock_quantity ASC";
                    
            if(!$this->db->query($sql)) {
                $this->lastError = $this->db->getError();
                return [];
            }
            
            $this->db->bind(':threshold', $threshold);
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::getLowStockProducts - ' . $this->lastError);
            return [];
        }
    }
    
    /**
     * Get featured products
     * 
     * @param int $limit Number of products to return
     * @return array
     */
    public function getFeaturedProducts($limit = 8) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = :status
                ORDER BY p.id DESC
                LIMIT :limit";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':status', 'active');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    

    /**
     * Get related products
     * 
     * @param int $productId Product ID
     * @param int $categoryId Category ID
     * @param int $limit Number of products to return
     * @return array
     */
    public function getRelatedProducts($productId, $categoryId, $limit = 4) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = :category_id AND p.id != :product_id
                AND p.status = :status
                LIMIT :limit";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':status', 'active');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    
    /**
     * Paginate products
     * 
     * @param int $page Current page
     * @param int $perPage Items per page
     * @param string $orderBy Column to order by
     * @param string $order Order direction (ASC or DESC)
     * @param string|null $search Optional search keyword (searches name, sku, description, category_name)
     * @return array
     */
    public function paginate($page = 1, $perPage = 10, $orderBy = 'id', $order = 'DESC', $search = null) {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $searchKeyword = trim((string)$search);
        if ($searchKeyword !== '') {
            // Use unique param names (PDO doesn't allow same named param twice)
            $whereClause = " p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE (p.name LIKE :s1 OR p.sku LIKE :s2 OR p.description LIKE :s3 
                       OR p.supplier LIKE :s4 OR CAST(p.batch_number AS CHAR) LIKE :s5
                       OR c.name LIKE :s6)";
        }
        
        // Get total count
        if ($whereClause !== '') {
            $countSql = "SELECT COUNT(*) as total FROM {$this->table}" . $whereClause;
        } else {
            $countSql = "SELECT COUNT(*) as total FROM {$this->table} p";
        }
        
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
            $this->db->bind(':s4', $term);
            $this->db->bind(':s5', $term);
            $this->db->bind(':s6', $term);
        }
        $totalResult = $this->db->single();
        if ($totalResult === false) {
            $this->lastError = $this->db->getError();
            return [
                'data' => [],
                'total' => 0,
                'total_records' => 0,
                'current_page' => $page,
                'per_page' => $perPage,
                'total_pages' => 0
            ];
        }
        $total = (int) ($totalResult['total'] ?? 0);
        $totalPages = $perPage > 0 ? (int) ceil($total / $perPage) : 1;
        $offset = (int) $offset;
        $perPage = (int) $perPage;
        
        // Get products (use intval for LIMIT/OFFSET to avoid MySQL PDO binding issues)
        $dataWhere = $whereClause !== '' ? $whereClause : " p
                LEFT JOIN categories c ON p.category_id = c.id";
        $orderBySafe = in_array($orderBy, ['id', 'name', 'sku', 'price', 'stock_quantity', 'status', 'created_at'], true) ? $orderBy : 'id';
        $orderSafe = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';
        
        $limitVal = max(1, $perPage);
        $offsetVal = max(0, $offset);
        
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table}" . $dataWhere . "
                ORDER BY p.{$orderBySafe} {$orderSafe}
                LIMIT " . (int) $limitVal . " OFFSET " . (int) $offsetVal;
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [
                'data' => [],
                'total' => $total,
                'total_records' => $total,
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
            $this->db->bind(':s4', $term);
            $this->db->bind(':s5', $term);
            $this->db->bind(':s6', $term);
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
    
    /**
     * Get all products for export (admin)
     * @return array
     */
    public function getAllForExport() {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.id ASC";
        if (!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        $rows = $this->db->resultSet();
        return array_map(function($r) { return (array)$r; }, $rows);
    }
    
    /**
     * Get top selling products
     * 
     * @param int $limit Number of products to return
     * @return array
     */
    public function getTopSellingProducts($limit = 5) {
        $sql = "SELECT p.*, c.name as category_name, 
                SUM(oi.quantity) as total_sold,
                COUNT(DISTINCT o.id) as order_count
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN order_items oi ON p.id = oi.product_id
                LEFT JOIN orders o ON oi.order_id = o.id
                WHERE p.status = :status
                GROUP BY p.id
                ORDER BY total_sold DESC
                LIMIT :limit";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':status', 'active');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    
    /**
     * Get product report
     * 
     * @param int $categoryId Category ID (0 for all)
     * @param string $sortBy Sort by (sales, stock, price)
     * @return array
     */
    public function getProductReport($categoryId = 0, $sortBy = 'sales') {
        $categoryFilter = $categoryId > 0 ? "AND p.category_id = :category_id" : "";
        $orderBy = "total_sold DESC";
        
        switch($sortBy) {
            case 'stock':
                $orderBy = "p.stock_quantity ASC";
                break;
            case 'price':
                $orderBy = "p.price DESC";
                break;
            case 'sales':
            default:
                $orderBy = "total_sold DESC";
                break;
        }
        
        $sql = "SELECT p.*, c.name as category_name, 
                SUM(IFNULL(oi.quantity, 0)) as total_sold,
                COUNT(DISTINCT o.id) as order_count,
                SUM(IFNULL(oi.quantity * oi.price, 0)) as revenue
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN order_items oi ON p.id = oi.product_id
                LEFT JOIN orders o ON oi.order_id = o.id
                WHERE p.status = :status {$categoryFilter}
                GROUP BY p.id
                ORDER BY {$orderBy}";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':status', 'active');
        
        if($categoryId > 0) {
            $this->db->bind(':category_id', $categoryId);
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get new products
     * 
     * @param int $limit Number of products to return
     * @return array
     */
    public function getNewProducts($limit = 4) {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = :status
                ORDER BY p.created_at DESC
                LIMIT :limit";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        $this->db->bind(':status', 'active');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
}
