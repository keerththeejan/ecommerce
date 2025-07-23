<?php
namespace App\Controllers\Customer;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;

class CheckoutController extends Controller {
    public function index() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . BASE_URL . 'user/login');
            exit;
        }

        // Get product ID from URL
        $product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : null;

        // If no product ID is provided, redirect to cart
        if (!$product_id) {
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        // Get product details
        $product = new Product();
        $product_data = $product->getProductById($product_id);

        if (!$product_data || $product_data['stock_quantity'] <= 0) {
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        // Get user's cart
        $cart = new Cart();
        $cart_items = $cart->getCartItems($_SESSION['user_id']);

        // If product is not in cart, add it
        $in_cart = false;
        foreach ($cart_items as $item) {
            if ($item['product_id'] == $product_id) {
                $in_cart = true;
                break;
            }
        }

        if (!$in_cart) {
            $cart->addProduct($product_id, 1, $_SESSION['user_id']);
        }

        // Get updated cart items
        $cart_items = $cart->getCartItems($_SESSION['user_id']);

        // Pass data to view
        $this->view('customer/checkout/index', [
            'cart_items' => $cart_items,
            'total' => $cart->calculateTotal($_SESSION['user_id'])
        ]);
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process checkout
            $order = new Order();
            $cart = new Cart();
            
            // Create order
            $order_id = $order->createOrder($_SESSION['user_id']);
            
            // Add order items
            $cart_items = $cart->getCartItems($_SESSION['user_id']);
            foreach ($cart_items as $item) {
                $order->addOrderItem($order_id, $item['product_id'], $item['quantity']);
            }
            
            // Clear cart
            $cart->clearCart($_SESSION['user_id']);
            
            // Redirect to order confirmation
            header('Location: ' . BASE_URL . 'checkout/confirmation?order_id=' . $order_id);
            exit;
        }
    }

    public function confirmation() {
        if (!isLoggedIn()) {
            header('Location: ' . BASE_URL . 'user/login');
            exit;
        }

        $order_id = isset($_GET['order_id']) ? (int)$_GET['order_id'] : null;
        
        if (!$order_id) {
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        $order = new Order();
        $order_data = $order->getOrderById($order_id);

        if (!$order_data || $order_data['user_id'] != $_SESSION['user_id']) {
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        $this->view('customer/checkout/confirmation', [
            'order' => $order_data
        ]);
    }
}
