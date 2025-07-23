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
     * Get all products with stock information
     * 
     * @return array Array of products with stock data
     */
    public function getAllProductsWithStock() {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                   FROM {$this->table} p
                   LEFT JOIN categories c ON p.category_id = c.id
                   ORDER BY p.name ASC";
            
            $this->db->query($sql);
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log('Error in Product::getAllProductsWithStock - ' . $this->lastError);
            return [];
        }
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
            $sql = "SELECT p.*, c.name as category_name 
                   FROM {$this->table} p
                   LEFT JOIN categories c ON p.category_id = c.id
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
     * Get all products
     * 
     * @return array Array of product objects
     */
    public function getAllProducts($includeInactive = false) {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                   FROM {$this->table} p
                   LEFT JOIN categories c ON p.category_id = c.id";
            
            if (!$includeInactive) {
                $sql .= " WHERE p.status = 'active'";
            }
            
            $sql .= " ORDER BY p.name ASC";
            
            if(!$this->db->query($sql)) {
                $this->lastError = $this->db->getError();
                return [];
            }
            
            return $this->db->resultSet();
            
        } catch (Exception $e) {
            error_log('Error in Product::getAllProducts - ' . $e->getMessage());
            return [];
        }
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
            
            // Get all products (temporarily removing status filter for testing)
            $sql = "SELECT p.id, p.name, p.sku, p.purchase_price, p.stock_quantity, 
                           p.status, p.image, IFNULL(c.name, 'Uncategorized') as category_name 
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                    ORDER BY p.name ASC";
            
            if(!$this->db->query($sql)) {
                $error = $this->db->getError();
                error_log('Error in Product::getActiveProducts query: ' . $error);
                $this->lastError = $error;
                return [];
            }
            $products = $this->db->resultSet();
            
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
        // Check if tables exist
        if(!$this->db->tableExists($this->table) || !$this->db->tableExists('categories')) {
            return [];
        }
        
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = :category_id AND p.status = :status";
                
        if(!$this->db->query($sql)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        if(!$this->db->bind(':category_id', $categoryId)) {
            $this->lastError = $this->db->getError();
            return [];
        }
        
        if(!$this->db->bind(':status', 'active')) {
            $this->lastError = $this->db->getError();
            return [];
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
        
        // Get products
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.{$orderBy} {$order}
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
