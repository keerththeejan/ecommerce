<?php
/**
 * Report Controller
 * Handles generating reports for the admin panel
 */
class ReportController extends Controller {
    private $orderModel;
    private $productModel;
    private $userModel;
    
    public function __construct() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        $this->orderModel = $this->model('Order');
        $this->productModel = $this->model('Product');
        $this->userModel = $this->model('User');
    }
    
    /**
     * Report dashboard
     */
    public function index() {
        // Get sales summary
        $salesSummary = $this->orderModel->getSalesSummary();
        
        // Get top selling products
        $topProducts = $this->productModel->getTopSellingProducts(5);
        
        // Get recent orders
        $recentOrders = $this->orderModel->getRecentOrders(5);
        
        // Get customer statistics
        $customerStats = $this->userModel->getCustomerStatistics();
        
        // Load view
        $this->view('admin/reports/index', [
            'salesSummary' => $salesSummary,
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'customerStats' => $customerStats
        ]);
    }
    
    /**
     * Sales report
     */
    public function sales() {
        // Get filter parameters
        $startDate = $this->get('start_date', date('Y-m-d', strtotime('-30 days')));
        $endDate = $this->get('end_date', date('Y-m-d'));
        
        // Get sales data
        $salesData = $this->orderModel->getSalesReport($startDate, $endDate);
        
        // Load view
        $this->view('admin/reports/sales', [
            'salesData' => $salesData,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    /**
     * Product report
     */
    public function products() {
        // Get filter parameters
        $categoryId = $this->get('category_id', 0);
        $sortBy = $this->get('sort_by', 'sales');
        
        // Get product data
        $productData = $this->productModel->getProductReport($categoryId, $sortBy);
        
        // Get all categories for filter
        $categoryModel = $this->model('Category');
        $categories = $categoryModel->getActiveCategories();
        
        // Load view
        $this->view('admin/reports/products', [
            'productData' => $productData,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'sortBy' => $sortBy
        ]);
    }
    
    /**
     * Customer report
     */
    public function customers() {
        // Get filter parameters
        $sortBy = $this->get('sort_by', 'orders');
        
        // Get customer data
        $customerData = $this->userModel->getCustomerReport($sortBy);
        
        // Load view
        $this->view('admin/reports/customers', [
            'customerData' => $customerData,
            'sortBy' => $sortBy
        ]);
    }
}
