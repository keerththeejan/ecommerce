<?php
/**
 * Admin Controller
 * Handles admin dashboard and auth redirects for admin area
 */
class AdminController extends Controller {

    public function __construct() {
        parent::__construct();
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
        if (!function_exists('isAdmin')) {
            require_once APP_PATH . 'helpers.php';
        }
        if (!isAdmin()) {
            redirect('user/login');
            return;
        }

        $recentOrders = [];
        try {
            $orderModel = $this->model('Order');
            if (method_exists($orderModel, 'getRecentOrders')) {
                $recentOrders = $orderModel->getRecentOrders(5);
            }
        } catch (Throwable $e) {
            error_log('Admin dashboard: ' . $e->getMessage());
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
