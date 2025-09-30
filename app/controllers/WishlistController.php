<?php
/**
 * Wishlist Controller
 * Handles wishlist related operations
 */
class WishlistController {
    private $wishlistModel;
    private $productModel;
    
    public function __construct() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('login_required', 'Please login to manage your wishlist', 'alert alert-warning');
            $redirect = '?controller=auth&action=login';
            if (isset($_SERVER['REQUEST_URI'])) {
                $redirect .= '&redirect=' . urlencode(ltrim($_SERVER['REQUEST_URI'], '/'));
            }
            redirect($redirect);
        }
        
        // Load models
        $this->wishlistModel = $this->model('Wishlist');
        $this->productModel = $this->model('Product');
    }
    
    /**
     * Add product to wishlist
     */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
            $productId = (int)$_GET['id'];
            $userId = $_SESSION['user_id'];
            
            // Check if product exists
            $product = $this->productModel->getById($productId);
            if (!$product) {
                flash('product_not_found', 'Product not found', 'alert alert-danger');
                redirect('?controller=home');
            }
            
            // Check if already in wishlist
            if ($this->wishlistModel->isInWishlist($userId, $productId)) {
                flash('wishlist_exists', 'Product is already in your wishlist', 'alert alert-info');
                redirect('?controller=home');
            }
            
            // Add to wishlist
            if ($this->wishlistModel->addToWishlist($userId, $productId)) {
                flash('wishlist_success', 'Product added to your wishlist', 'alert alert-success');
            } else {
                flash('wishlist_error', 'Failed to add product to wishlist', 'alert alert-danger');
            }
            
            // Redirect back to previous page or home
            $redirect = '?controller=home';
            if (isset($_SERVER['HTTP_REFERER'])) {
                $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);
                $query = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
                if ($query) {
                    parse_str($query, $params);
                    if (isset($params['controller']) && $params['controller'] === 'wishlist') {
                        $redirect = '?controller=wishlist';
                    } else {
                        $redirect = '?' . $query;
                    }
                }
            }
            redirect($redirect);
        }
    }
    
    /**
     * Remove product from wishlist
     */
    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
            $productId = (int)$_GET['id'];
            $userId = $_SESSION['user_id'];
            
            if ($this->wishlistModel->removeFromWishlist($userId, $productId)) {
                flash('wishlist_removed', 'Product removed from wishlist', 'alert alert-success');
            } else {
                flash('wishlist_error', 'Failed to remove product from wishlist', 'alert alert-danger');
            }
            
            // Redirect back to wishlist page
            redirect('?controller=wishlist');
        }
    }
    
    /**
     * View wishlist
     */
    public function index() {
        $userId = $_SESSION['user_id'];
        $wishlistItems = $this->wishlistModel->getUserWishlist($userId);
        
        $data = [
            'title' => 'My Wishlist',
            'wishlistItems' => $wishlistItems
        ];
        
        $this->view('customer/wishlist/index', $data);
    }
    
    /**
     * Helper method to load a model
     */
    protected function model($model) {
        require_once APP_PATH . 'models/' . $model . '.php';
        return new $model();
    }
    
    /**
     * Helper method to load a view
     */
    protected function view($view, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Load header
        require_once APP_PATH . 'views/customer/layouts/header.php';
        
        // Load the view
        require_once APP_PATH . 'views/' . $view . '.php';
        
        // Load footer
        require_once APP_PATH . 'views/customer/layouts/footer.php';
    }
}
