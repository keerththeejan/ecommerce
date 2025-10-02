<?php
/**
 * Admin Controller
 * Handles admin dashboard and auth redirects for admin area
 */
class AdminController extends Controller {
    private $productModel;

    public function __construct() {
        parent::__construct();
        $this->productModel = $this->model('Product');
    }

    /**
     * Default admin route -> dashboard
     */
    public function index() {
        return $this->dashboard();
    }

    /**
     * Admin dashboard
     */
    public function dashboard() {
        // Require admin login
        if (!isAdmin()) {
            // Route to existing user login page if not admin
            redirect('user/login');
        }

        // Recent orders
        $recentOrders = $this->model('Order')->getRecentOrders(5);

        // Low stock products
        $lowStockProducts = $this->productModel->getLowStockProducts();

        // Sales statistics
        $salesStats = $this->model('Order')->getSalesStats();

        // Render view
        $this->view('admin/dashboard', [
            'recentOrders' => $recentOrders,
            'lowStockProducts' => $lowStockProducts,
            'salesStats' => $salesStats
        ]);
    }

    /**
     * Show admin login (delegate to user login route for this codebase)
     */
    public function login() {
        if (isAdmin()) {
            redirect('admin/dashboard');
        }
        redirect('user/login');
    }

    /**
     * Logout admin and redirect to login
     */
    public function logout() {
        logout();
        redirect('user/login');
    }
}
