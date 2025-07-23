<?php
/**
 * Cart Controller
 * Handles shopping cart functionality
 */
class CartController extends Controller {
    private $cartModel;
    private $productModel;
    
    public function __construct() {
        $this->cartModel = $this->model('Cart');
        $this->productModel = $this->model('Product');
    }
    
    /**
     * Display cart contents
     */
    public function index() {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Get cart items
        $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
        
        // Calculate cart total
        $cartTotal = $this->cartModel->getCartTotal($_SESSION['user_id']);
        
        // Load view
        $this->view('customer/cart/index', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal
        ]);
    }
    
    /**
     * Add product to cart
     * 
     * @param int $productId Product ID
     */
    public function add($productId = null) {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Check if product ID is provided
        if(!$productId && !$this->isPost()) {
            redirect('products');
        }
        
        // Get product ID from POST if not provided in URL
        if(!$productId) {
            $productId = $this->post('product_id');
        }
        
        // Get quantity from POST
        $quantity = $this->post('quantity', 1);
        
        // Get product
        $product = $this->productModel->getById($productId);
        
        // Check if product exists and is active
        if(!$product || $product['status'] != 'active') {
            flash('cart_error', 'Product not available', 'alert alert-danger');
            redirect('products');
        }
        
        // Check if quantity is valid
        if($quantity < 1) {
            $quantity = 1;
        }
        
        // Check if quantity is available
        if($quantity > $product['stock_quantity']) {
            flash('cart_error', 'Not enough stock available', 'alert alert-danger');
            redirect('products/show/' . $productId);
        }
        
        // Add to cart - no success message
        $success = $this->cartModel->addToCart($_SESSION['user_id'], $productId, $quantity);
        
        // Only show error message if failed
        if (!$success) {
            flash('cart_error', 'Failed to add product to cart', 'alert alert-danger');
        }
        
        // Check if AJAX request
        if($this->isAjax()) {
            // Return JSON response
            $cartCount = $this->cartModel->getCartCount($_SESSION['user_id']);
            $this->json([
                'success' => $success,
                'cartCount' => $cartCount
            ]);
        } else {
            // Redirect to cart
            redirect('cart');
        }
    }
    
    /**
     * Update cart item quantity
     */
    public function update() {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Check for POST
        if(!$this->isPost()) {
            redirect('cart');
        }
        
        // Get cart item ID and quantity
        $cartId = $this->post('cart_id');
        $quantity = $this->post('quantity');
        
        // Validate data
        if(!$cartId || !$quantity || $quantity < 1) {
            flash('cart_error', 'Invalid data', 'alert alert-danger');
            redirect('cart');
        }
        
        // Update cart - no success message needed
        if(!$this->cartModel->updateQuantity($cartId, $quantity)) {
            flash('cart_error', 'Failed to update cart', 'alert alert-danger');
        }
        
        // Check if AJAX request
        if($this->isAjax()) {
            // Get updated cart data
            $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
            $cartTotal = $this->cartModel->getCartTotal($_SESSION['user_id']);
            
            // Return JSON response
            $this->json([
                'success' => true,
                'cartTotal' => $cartTotal,
                'itemCount' => count($cartItems)
            ]);
        } else {
            // Redirect to cart
            redirect('cart');
        }
    }
    
    /**
     * Remove item from cart
     * 
     * @param int $cartId Cart item ID
     */
    public function remove($cartId = null) {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Check if cart ID is provided
        if(!$cartId && !$this->isPost()) {
            redirect('cart');
        }
        
        // Get cart ID from POST if not provided in URL
        if(!$cartId) {
            $cartId = $this->post('cart_id');
        }
        
        // Remove from cart - no success message
        $success = $this->cartModel->removeFromCart($cartId);
        
        // Only show error message if failed
        if (!$success) {
            flash('cart_error', 'Failed to remove item from cart', 'alert alert-danger');
        }
        
        // Check if AJAX request
        if($this->isAjax()) {
            // Get updated cart data
            $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
            $cartTotal = $this->cartModel->getCartTotal($_SESSION['user_id']);
            
            // Return JSON response
            $this->json([
                'success' => $success,
                'cartTotal' => $cartTotal,
                'itemCount' => count($cartItems)
            ]);
        } else {
            // Redirect to cart
            redirect('cart');
        }
    }
    
    /**
     * Clear cart
     */
    public function clear() {
        // Check if logged in
        if(!isLoggedIn()) {
            redirect('user/login');
        }
        
        // Clear cart - no success message
        $success = $this->cartModel->clearCart($_SESSION['user_id']);
        
        // Only show error message if failed
        if (!$success) {
            flash('cart_error', 'Failed to clear cart', 'alert alert-danger');
        }
        
        // Redirect to cart
        redirect('cart');
    }
    
    /**
     * Get cart count (for AJAX)
     */
    public function count() {
        // Check if logged in
        if(!isLoggedIn()) {
            $this->json(['count' => 0]);
            return;
        }
        
        // Get cart count
        $count = $this->cartModel->getCartCount($_SESSION['user_id']);
        
        // Return JSON response
        $this->json(['count' => $count]);
    }
}
