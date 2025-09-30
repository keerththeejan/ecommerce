<?php
/**
 * Home Controller
 * Handles the main pages of the website
 */
class HomeController extends Controller {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        parent::__construct();
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
    }
    
    /**
     * Homepage
     */
    public function index() {
        try {
            // Get featured products
            $featuredProducts = $this->productModel->getFeaturedProducts(8);
            
            // Get products on sale
            $saleProducts = $this->productModel->getProductsOnSale();
            
            // Get new products
            $newProducts = $this->productModel->getNewProducts(4);
            
            // Get categories
            $categories = $this->categoryModel->getActiveCategories();
            
            // Get brands (use processed active brands so logos have proper URLs)
            $brandModel = $this->model('Brand');
            $brands = $brandModel->getActiveBrands();

            // Get latest About Store entry (for homepage intro)
            require_once APP_PATH . 'models/AboutStore.php';
            $aboutModel = new AboutStore($this->db);
            $aboutEntries = $aboutModel->getAll();
            $aboutLatest = !empty($aboutEntries) ? $aboutEntries[0] : null;
            
            // Load view
            $this->view('customer/home/index', [
                'featuredProducts' => $featuredProducts,
                'saleProducts' => $saleProducts,
                'newProducts' => $newProducts,
                'categories' => $categories,
                'brands' => $brands,
                'aboutLatest' => $aboutLatest
            ]);
        } catch (Exception $e) {
            // Log error
            error_log('Error in HomeController::index: ' . $e->getMessage());
            
            // Load view with empty data
            $this->view('customer/home/index', [
                'featuredProducts' => [],
                'saleProducts' => [],
                'newProducts' => [],
                'categories' => [],
                'brands' => [],
                'banners' => []
            ]);
        }
    }
    
    /**
     * About page
     */
    public function about() {
        $this->view('customer/home/about');
    }
    
    /**
     * Load more products via AJAX
     */
    public function loadMoreProducts() {
        // Check if this is an AJAX request
        if (!$this->isAjax()) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request']);
            return;
        }
        
        try {
            // Get page number and items per page from request
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 8;
            
            if ($page < 1) $page = 1;
            if ($perPage < 1) $perPage = 8;
            
            // Calculate offset
            $offset = ($page - 1) * $perPage;
            
            // Get products with pagination
            $productModel = $this->model('Product');
            $products = $productModel->getProductsWithPagination($offset, $perPage);
            
            // Format products for JSON response
            $formattedProducts = [];
            foreach ($products as $product) {
                $formattedProducts[] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'description' => $product['description'] ?? '',
                    'price' => (float)$product['price'],
                    'sale_price' => !empty($product['sale_price']) ? (float)$product['sale_price'] : null,
                    'stock_quantity' => (int)$product['stock_quantity'],
                    'image' => !empty($product['image']) ? $product['image'] : 'assets/images/product-placeholder.jpg',
                    'category_name' => $product['category_name'] ?? 'Uncategorized',
                    'slug' => $product['slug'] ?? ''
                ];
            }
            
            // Return JSON response
            $this->jsonResponse([
                'success' => true,
                'products' => $formattedProducts
            ]);
            
        } catch (Exception $e) {
            // Log error
            error_log('Error in HomeController::loadMoreProducts: ' . $e->getMessage());
            
            // Return error response
            $this->jsonResponse([
                'success' => false,
                'message' => 'An error occurred while loading products.'
            ]);
        }
    }
    
    /**
     * Check if the request is an AJAX request
     * 
     * @return bool True if the request is an AJAX request, false otherwise
     */
    public function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Send JSON response
     */
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Contact page
     */
    public function contact() {
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'name' => sanitize($this->post('name')),
                'email' => sanitize($this->post('email')),
                'subject' => sanitize($this->post('subject')),
                'message' => sanitize($this->post('message'))
            ];
            
            // Validate data
            $errors = $this->validate($data, [
                'name' => 'required',
                'email' => 'required|email',
                'subject' => 'required',
                'message' => 'required'
            ]);
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Send email (this is just a placeholder - you would need to implement actual email sending)
                
                flash('contact_success', 'Your message has been sent. We will get back to you soon!');
                redirect('home/contact');
            } else {
                // Load view with errors
                $this->view('customer/home/contact', [
                    'errors' => $errors,
                    'data' => $data
                ]);
            }
        } else {
            // Init data
            $data = [
                'name' => '',
                'email' => '',
                'subject' => '',
                'message' => ''
            ];
            
            // Load view
            $this->view('customer/home/contact', [
                'data' => $data,
                'errors' => []
            ]);
        }
    }
    
    /**
     * Admin dashboard
     */
    public function admin() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Get recent orders
        $recentOrders = $this->model('Order')->getRecentOrders(5);
        
        // Get low stock products
        $lowStockProducts = $this->productModel->getLowStockProducts();
        
        // Get sales statistics
        $salesStats = $this->model('Order')->getSalesStats();
        
        // Load view
        $this->view('admin/dashboard', [
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'salesStats' => $salesStats
        ]);
    }
    
    /**
     * Clear all cookies
     */
    public function clearCookies() {
        // Check if admin
        if(!isAdmin()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }
        
        // Clear all cookies by setting expiration to the past
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                // Skip the PHPSESSID to maintain the current admin session
                if ($name !== 'PHPSESSID') {
                    setcookie($name, '', time() - 1000);
                    setcookie($name, '', time() - 1000, '/');
                }
            }
        }
        
        // Return success response
        echo json_encode(['success' => true, 'message' => 'All cookies have been cleared successfully!']);
        exit;
    }
}
