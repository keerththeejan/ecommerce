<?php
require_once __DIR__ . '/../../Core/Controller.php';
require_once __DIR__ . '/../../Core/Database.php';
require_once __DIR__ . '/../../Models/Product.php';

class ProductController extends Controller {
    protected $db;
    protected $product;

    public function __construct() {
        parent::__construct();
        $this->db = new Database();
        $this->product = new Product();
    }

    public function all() {
        // Get all products with pagination
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12; // Products per page
        $offset = ($page - 1) * $limit;

        $product = new Product();
        
        // Get total count
        $total = $product->getTotalProducts();
        
        // Get products with pagination
        $products = $product->getAllProducts($limit, $offset);
        
        // Calculate total pages
        $totalPages = ceil($total / $limit);

        // Get categories for filter
        $sql = "SELECT * FROM categories ORDER BY name ASC";
        $this->db->query($sql);
        $categories = $this->db->resultSet();

        // Pass data to view
        $this->view('customer/product/all', [
            'categories' => $categories,
            'products' => $products,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function show() {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        
        if (!$id) {
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        $product = new Product();
        $productData = $product->getProductById($id);

        if (!$productData) {
            header('Location: ' . BASE_URL . 'home');
            exit;
        }

        // Pass data to view
        $this->view('customer/product/show', [
            'product' => $productData
        ]);
    }
}
