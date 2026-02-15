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
        if (!function_exists('isAdmin')) {
            require_once APP_PATH . 'helpers.php';
        }
        if (!isAdmin()) {
            redirect('user/login');
        }

        $recentOrders = [];
        try {
            $recentOrders = $this->model('Order')->getRecentOrders(5);
        } catch (Exception $e) {
            error_log('Admin dashboard getRecentOrders: ' . $e->getMessage());
        }
        $this->view('admin/dashboard', [
            'recentOrders' => is_array($recentOrders) ? $recentOrders : []
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
