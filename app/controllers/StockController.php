<?php

class StockController {
    private $db;
    private $productModel;
    private $categoryModel;

    public function __construct() {
        // Initialize database connection
        $this->db = new Database();
        
        // Load required models
        require_once APP_PATH . 'models/Product.php';
        require_once APP_PATH . 'models/Category.php';
        
        $this->productModel = new Product($this->db);
        $this->categoryModel = new Category($this->db);
        
        // Check if user is logged in and has admin privileges
        $this->checkAdminAccess();
    }
    
    /**
     * Check if user is logged in and has admin access
     * Redirects to login if not authenticated or home if not admin
     */
    private function checkAdminAccess() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        if (!isAdmin()) {
            flash('error', 'You do not have permission to access this page');
            redirect('');
        }
    }

    /**
     * Display stock overview with filtering and pagination
     */
    public function index() {
        try {
            // Get filter parameters
            $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
            $categoryId = filter_input(INPUT_GET, 'category', FILTER_VALIDATE_INT) ?? 0;
            $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
            $perPage = 20; // Items per page
            
            // Get all products with stock information
            $products = $this->productModel->getAllProductsWithStock();
            
            // Convert arrays to objects if needed
            $products = array_map(function($product) {
                return is_array($product) ? (object)$product : $product;
            }, $products);
            
            // Apply filters
            $filteredProducts = [];
            foreach ($products as $product) {
                $match = true;
                
                // Apply search filter
                if (!empty($search)) {
                    $search = strtolower($search);
                    $productName = strtolower($product->name ?? '');
                    $productSku = strtolower($product->sku ?? '');
                    $categoryName = isset($product->category_name) ? strtolower($product->category_name) : '';
                    
                    if (strpos($productName, $search) === false && 
                        strpos($productSku, $search) === false && 
                        strpos($categoryName, $search) === false) {
                        $match = false;
                    }
                }
                
                // Apply category filter
                if ($categoryId > 0 && isset($product->category_id) && $product->category_id != $categoryId) {
                    $match = false;
                }
                
                if ($match) {
                    $filteredProducts[] = $product;
                }
            }
            
            // Get paginated results
            $totalItems = count($filteredProducts);
            $totalPages = ceil($totalItems / $perPage);
            $offset = ($page - 1) * $perPage;
            $paginatedProducts = array_slice($filteredProducts, $offset, $perPage);
            
            // Get all categories for filter dropdown and convert to objects if needed
            $categories = $this->categoryModel->getAllCategories();
            $categories = array_map(function($category) {
                return is_array($category) ? (object)$category : $category;
            }, $categories);
            
            // Prepare result with paginated products as objects
            $paginatedProducts = array_map(function($product) {
                return is_array($product) ? (object)$product : $product;
            }, $paginatedProducts);
            
            $result = [
                'data' => $paginatedProducts,
                'total' => $totalItems
            ];
            
            $data = [
                'title' => 'Stock Management',
                'products' => $result['data'],
                'categories' => $categories,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'search' => $search,
                'selectedCategory' => $categoryId,
                'totalItems' => $result['total']
            ];
            
            $this->view('admin/stock/index', $data);
            
        } catch (Exception $e) {
            error_log('Error in StockController::index - ' . $e->getMessage());
            flash('error', 'An error occurred while loading stock information.');
            redirect('home');
        }
    }
    
    /**
     * Display stock adjustment form
     */
    public function adjust($productId = null) {
        try {
            if (!$productId) {
                throw new Exception('Product ID is required');
            }
            
            $product = $this->productModel->getProductWithCategory($productId);
            if (!$product) {
                throw new Exception('Product not found');
            }
            
            $data = [
                'title' => 'Adjust Stock',
                'product' => $product
            ];
            
            $this->view('admin/stock/adjust', $data);
            
        } catch (Exception $e) {
            error_log('Error in StockController::adjust - ' . $e->getMessage());
            flash('error', $e->getMessage());
            redirect('stock');
        }
    }
    
    /**
     * Process stock adjustment
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('stock');
        }
        
        try {
            // Sanitize and validate input
            $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
            $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_FLOAT);
            $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? '';
            
            if (!$productId || $quantity === false || !in_array($action, ['add', 'subtract', 'set'])) {
                throw new Exception('Invalid input data');
            }
            
            // Get current product
            $product = $this->productModel->getById($productId);
            if (!$product) {
                throw new Exception('Product not found');
            }
            
            // Calculate new stock
            $currentStock = (float)$product['stock_quantity'];
            $quantity = (float)$quantity;
            
            // Validate quantity based on action
            if ($quantity <= 0) {
                throw new Exception('Quantity must be greater than 0');
            }
            
            if ($action === 'subtract' && $quantity > $currentStock) {
                throw new Exception('Cannot remove more than current stock');
            }
            
            // Calculate new stock value
            switch ($action) {
                case 'add':
                    $newStock = $currentStock + $quantity;
                    $adjustment = $quantity;
                    break;
                case 'subtract':
                    $newStock = $currentStock - $quantity;
                    $adjustment = -$quantity;
                    break;
                case 'set':
                default:
                    $newStock = $quantity;
                    $adjustment = $newStock - $currentStock;
                    break;
            }
            
            // Update stock
            $result = $this->productModel->updateStock($productId, $newStock);
            
            if ($result) {
                // Log the stock adjustment if the table exists
                try {
                    $this->logStockAdjustment([
                        'product_id' => $productId,
                        'previous_quantity' => $currentStock,
                        'new_quantity' => $newStock,
                        'adjustment' => $adjustment,
                        'action' => $action,
                        'notes' => $notes,
                        'user_id' => $_SESSION['user_id'] ?? 0
                    ]);
                } catch (Exception $e) {
                    // Log the error but don't fail the operation
                    error_log('Error logging stock adjustment: ' . $e->getMessage());
                }
                
                flash('success', 'Stock updated successfully');
            } else {
                throw new Exception($this->productModel->getLastError() ?: 'Failed to update stock');
            }
            
        } catch (Exception $e) {
            error_log('Error in StockController::update - ' . $e->getMessage());
            flash('error', 'Failed to update stock: ' . $e->getMessage());
            
            // Redirect back to the adjust page if we have a product ID
            if (!empty($productId)) {
                redirect('stock/adjust/' . $productId);
                return;
            }
        }
        
        redirect('stock');
    }
    
    /**
     * Log stock adjustment
     */
    private function logStockAdjustment($data) {
        try {
            $sql = "INSERT INTO stock_movements 
                    (product_id, previous_quantity, new_quantity, adjustment, action, notes, user_id, created_at) 
                    VALUES (:product_id, :previous_quantity, :new_quantity, :adjustment, :action, :notes, :user_id, NOW())";
            
            $this->db->query($sql);
            $this->db->bind(':product_id', $data['product_id']);
            $this->db->bind(':previous_quantity', $data['previous_quantity']);
            $this->db->bind(':new_quantity', $data['new_quantity']);
            $this->db->bind(':adjustment', $data['adjustment']);
            $this->db->bind(':action', $data['action']);
            $this->db->bind(':notes', $data['notes']);
            $this->db->bind(':user_id', $data['user_id']);
            
            return $this->db->execute();
            
        } catch (Exception $e) {
            error_log('Error in StockController::logStockAdjustment - ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * View stock movement history
     */
    public function history($productId = null) {
        try {
            $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
            $perPage = 20;
            $isPartial = isset($_GET['partial']) && (int)$_GET['partial'] === 1;
            
            if ($productId) {
                $product = $this->productModel->getById($productId);
                if (!$product) {
                    throw new Exception('Product not found');
                }
                
                $movements = $this->getProductStockMovements($productId, $page, $perPage);
                $totalMovements = $this->countProductStockMovements($productId);
                
                $data = [
                    'title' => 'Stock History - ' . $product['name'],
                    'movements' => $movements,
                    'product' => $product,
                    'currentPage' => $page,
                    'totalPages' => ceil($totalMovements / $perPage)
                ];
                
                if ($isPartial) {
                    // Render partial table only
                    extract($data);
                    $partialFile = APP_PATH . 'views/admin/stock/partials/product_history_table.php';
                    if (file_exists($partialFile)) {
                        include $partialFile;
                    } else {
                        // Fallback minimal table
                        header('Content-Type: text/html; charset=utf-8');
                        echo '<div class="alert alert-danger">History partial not found.</div>';
                    }
                    return;
                }
                
                $this->view('admin/stock/product_history', $data);
            } else {
                // Show all products with recent stock movements
                $movements = $this->getRecentStockMovements($page, $perPage);
                $totalMovements = $this->countAllStockMovements();
                
                $data = [
                    'title' => 'Stock Movement History',
                    'movements' => $movements,
                    'currentPage' => $page,
                    'totalPages' => ceil($totalMovements / $perPage)
                ];
                
                $this->view('admin/stock/history', $data);
            }
            
        } catch (Exception $e) {
            error_log('Error in StockController::history - ' . $e->getMessage());
            flash('error', $e->getMessage());
            redirect('stock');
        }
    }
    
    /**
     * Get product stock movements with pagination
     */
    private function getProductStockMovements($productId, $page, $perPage) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT sm.*, u.name as user_name, p.name as product_name, p.sku
                FROM stock_movements sm
                LEFT JOIN users u ON sm.user_id = u.id
                LEFT JOIN products p ON sm.product_id = p.id
                WHERE sm.product_id = :product_id
                ORDER BY sm.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':limit', $perPage, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get recent stock movements across all products
     */
    private function getRecentStockMovements($page, $perPage) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT sm.*, u.name as user_name, p.name as product_name, p.sku
                FROM stock_movements sm
                LEFT JOIN users u ON sm.user_id = u.id
                LEFT JOIN products p ON sm.product_id = p.id
                ORDER BY sm.created_at DESC
                LIMIT :limit OFFSET :offset";
        
        $this->db->query($sql);
        $this->db->bind(':limit', $perPage, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    /**
     * Count total product stock movements
     */
    private function countProductStockMovements($productId) {
        $sql = "SELECT COUNT(*) as total FROM stock_movements WHERE product_id = :product_id";
        $this->db->query($sql);
        $this->db->bind(':product_id', $productId);
        $result = $this->db->single();
        return $result ? $result->total : 0;
    }
    
    /**
     * Count all stock movements
     */
    private function countAllStockMovements() {
        $sql = "SELECT COUNT(*) as total FROM stock_movements";
        $result = $this->db->query($sql)->single();
        return $result ? $result->total : 0;
    }
    
    /**
     * Export stock data to CSV
     */
    public function export() {
        try {
            // Set headers for file download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="stock_export_' . date('Y-m-d') . '.csv"');
            
            // Create output stream
            $output = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($output, ['SKU', 'Product Name', 'Category', 'Current Stock', 'Status', 'Last Updated']);
            
            // Get all products with stock
            $products = $this->productModel->getAllProductsWithStock();
            
            // Add data rows
            foreach ($products as $product) {
                fputcsv($output, [
                    $product->sku,
                    $product->name,
                    $product->category_name ?? 'Uncategorized',
                    $product->stock_quantity,
                    ucfirst($product->status),
                    !empty($product->updated_at) ? date('Y-m-d H:i', strtotime($product->updated_at)) : 'N/A'
                ]);
            }
            
            fclose($output);
            exit;
            
        } catch (Exception $e) {
            error_log('Error in StockController::export - ' . $e->getMessage());
            flash('error', 'Failed to export stock data');
            redirect('stock');
        }
    }
    
    /**
     * View to render templates
     */
    protected function view($view, $data = []) {
        // Extract data variables
        extract($data);
        
        // Check if view file exists
        $viewFile = APP_PATH . 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            // Include the header
            $headerFile = APP_PATH . 'views/admin/layouts/header.php';
            if (file_exists($headerFile)) {
                include $headerFile;
            } else {
                throw new Exception('Header file not found: ' . $headerFile);
            }
            
            // Include the view file
            include $viewFile;
            
            // Include the footer
            $footerFile = APP_PATH . 'views/admin/layouts/footer.php';
            if (file_exists($footerFile)) {
                include $footerFile;
            } else {
                throw new Exception('Footer file not found: ' . $footerFile);
            }
        } else {
            // View doesn't exist
            throw new Exception('View file not found: ' . $viewFile);
        }
    }
}
