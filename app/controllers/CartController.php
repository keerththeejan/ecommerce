<?php
/**
 * Cart Controller
 * Handles shopping cart functionality
 */
class CartController extends Controller {
    private $cartModel;
    private $productModel;
    protected $db;
    
    public function __construct() {
        parent::__construct();
        $this->cartModel = $this->model('Cart');
        $this->productModel = $this->model('Product');
        $this->db = new Database();
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
     * Checkout and create order from cart
     */
    public function checkout() {
        // Check if logged in
        if(!isLoggedIn()) {
            if($this->isAjax()) {
                $this->json([
                    'success' => false,
                    'message' => 'Please login to checkout',
                    'redirect' => BASE_URL . '?controller=user&action=login'
                ]);
                return;
            }
            redirect('user/login');
        }
        
        try {
            // Get cart items
            $cartItems = $this->cartModel->getCartItems($_SESSION['user_id']);
            
            if (empty($cartItems)) {
                throw new Exception('Your cart is empty');
            }
            
            // Prepare order data
            $orderData = [
                'user_id' => $_SESSION['user_id'],
                'shipping_id' => 1, // Default shipping
                'payment_method' => 'cod',
                'status' => 'pending',
                'total_amount' => 0,
                'items' => []
            ];
            
            // Calculate total and prepare items
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $itemPrice = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                $itemTotal = $itemPrice * $item['quantity'];
                $totalAmount += $itemTotal;
                
                $orderData['items'][] = [
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $itemPrice
                ];
            }
            
            $orderData['total_amount'] = $totalAmount;
            
            // Create order
            $orderModel = $this->model('Order');
            $orderId = $orderModel->createOrder($orderData);
            
            if (!$orderId) {
                throw new Exception('Failed to create order');
            }
            
            // Clear cart after creating order
            $this->cartModel->clearCart($_SESSION['user_id']);
            
            // Return success response
            $response = [
                'success' => true,
                'message' => 'Order created successfully',
                'redirect' => BASE_URL . '?controller=order&action=adminIndex',
                'orderId' => $orderId
            ];
            
        } catch (Exception $e) {
            // Log error
            error_log('CartController::checkout - ' . $e->getMessage());
            
            // Return error response
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        
        // Return JSON response for AJAX or redirect for normal requests
        if($this->isAjax()) {
            $this->json($response);
        } else {
            if(isset($response['success']) && $response['success']) {
                flash('order_success', 'Your order has been placed successfully!', 'alert alert-success');
                redirect('order/adminIndex');
            } else {
                flash('order_error', $response['message'] ?? 'An error occurred while processing your order', 'alert alert-danger');
                redirect('cart');
            }
        }
    }
    
    /**
     * Add product to cart
     * 
     * @param int $productId Product ID
     */
    public function add($productId = null) {
        // Check if logged in
        if(!isLoggedIn()) {
            if($this->isAjax()) {
                $this->json([
                    'success' => false,
                    'message' => 'Please login to add items to cart',
                    'redirect' => BASE_URL . '?controller=user&action=login'
                ]);
                return;
            }
            redirect('user/login');
        }
        
        try {
            // Check if product ID is provided
            if(!$productId && !$this->isPost()) {
                throw new Exception('Invalid request');
            }
            
            // Get product ID from POST if not provided in URL
            if(!$productId) {
                $productId = $this->post('product_id');
            }
            
            // Get quantity from POST
            $quantity = (int)$this->post('quantity', 1);
            
            // Validate quantity
            if($quantity < 1) {
                $quantity = 1;
            }
            
            // Get product
            $product = $this->productModel->getById($productId);
            
            // Check if product exists and is active
            if(!$product || $product['status'] != 'active') {
                throw new Exception('Product not available');
            }
            
            // Check if quantity is available
            if($quantity > $product['stock_quantity']) {
                throw new Exception('Not enough stock available');
            }
            
            // Calculate item total
            $itemPrice = !empty($product['sale_price']) ? $product['sale_price'] : $product['price'];
            $itemTotal = $itemPrice * $quantity;
            
            // Begin transaction
            $this->db->beginTransaction();
            
            try {
                // 1. Create order
                $sql = "INSERT INTO orders (
                    user_id, 
                    payment_method, 
                    status, 
                    total_amount, 
                    created_at
                ) VALUES (
                    :user_id, 
                    :payment_method, 
                    :status, 
                    :total_amount, 
                    NOW()
                )";
                
                $this->db->query($sql);
                $this->db->bind(':user_id', $_SESSION['user_id']);
                $this->db->bind(':payment_method', 'cod');
                $this->db->bind(':status', 'pending');
                $this->db->bind(':total_amount', $itemTotal);
                
                if(!$this->db->execute()) {
                    throw new Exception('Failed to create order');
                }
                
                $orderId = $this->db->lastInsertId();
                
                // 2. Add order item
                $sql = "INSERT INTO order_items (
                    order_id, 
                    product_id, 
                    quantity, 
                    price, 
                    created_at
                ) VALUES (
                    :order_id, 
                    :product_id, 
                    :quantity, 
                    :price, 
                    NOW()
                )";
                
                $this->db->query($sql);
                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':product_id', $product['id']);
                $this->db->bind(':quantity', $quantity);
                $this->db->bind(':price', $itemPrice);
                
                if(!$this->db->execute()) {
                    throw new Exception('Failed to add order item');
                }
                
                // 3. Update product stock
                $sql = "UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :id";
                $this->db->query($sql);
                $this->db->bind(':quantity', $quantity);
                $this->db->bind(':id', $product['id']);
                
                if(!$this->db->execute()) {
                    throw new Exception('Failed to update product stock');
                }
                
                // Commit transaction
                $this->db->endTransaction();
                
                // Return success response
                $response = [
                    'success' => true,
                    'message' => 'Order created successfully',
                    'redirect' => BASE_URL . '?controller=order&action=adminIndex',
                    'orderId' => $orderId
                ];
                
            } catch (Exception $e) {
                // Rollback transaction on error
                $this->db->cancelTransaction();
                throw $e;
            }
            
        } catch (Exception $e) {
            // Log error
            error_log('CartController::add - ' . $e->getMessage());
            
            // Return error response
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        
        // Return JSON response for AJAX or redirect for normal requests
        if($this->isAjax()) {
            $this->json($response);
        } else {
            if(isset($response['success']) && $response['success']) {
                flash('order_success', $response['message'], 'alert alert-success');
                redirect('order/adminIndex');
            } else {
                flash('order_error', $response['message'] ?? 'An error occurred', 'alert alert-danger');
                redirect(isset($productId) ? 'products/show/' . $productId : 'products');
            }
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
