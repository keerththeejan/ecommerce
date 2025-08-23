<?php
/**
 * ListPurchaseController
 * Admin page to list products with filters for creating purchases
 */
class ListPurchaseController extends Controller {
    public function __construct() {
        parent::__construct();
        // Optionally protect with admin guard if available
        if (function_exists('isAdmin') && !isAdmin()) {
            redirect('admin/login');
        }
        // Load helpers if needed
        if (!function_exists('formatPrice')) {
            require_once APP_PATH . 'helpers/currency_helper.php';
        }
    }

    /**
     * Show product list (for purchase selection)
     */
    public function index() {
        // Fetch from DB (products, categories, brands, taxes)
        // Require model files explicitly if no autoloader
        if (!class_exists('Product')) require_once APP_PATH . 'models/Product.php';
        if (!class_exists('Category')) require_once APP_PATH . 'models/Category.php';
        if (!class_exists('Brand')) require_once APP_PATH . 'models/Brand.php';
        if (!class_exists('TaxModel')) require_once APP_PATH . 'models/TaxModel.php';

        $productModel = new Product();
        $categoryModel = new Category();
        $brandModel = new Brand();
        $taxModel = new TaxModel();

        // Products: include inactive too for admin listing; 0 page => no pagination
        $rawProducts = $productModel->getAllProducts(0, 0, true);
        $products = [];
        foreach ((array)$rawProducts as $row) { $products[] = (array)$row; }

        // Categories
        $rawCategories = $categoryModel->getAllCategories();
        $categories = [];
        foreach ((array)$rawCategories as $row) { $categories[] = (array)$row; }

        // Brands
        $rawBrands = $brandModel->getAll();
        $brands = [];
        foreach ((array)$rawBrands as $row) { $brands[] = (array)$row; }

        // Taxes
        $rawTaxes = $taxModel->getTaxRates(true);
        $taxRates = [];
        foreach ((array)$rawTaxes as $row) { $taxRates[] = (array)$row; }

        // Build quick lookup maps for view convenience
        $brandMap = [];
        foreach ($brands as $b) { if (isset($b['id'])) $brandMap[$b['id']] = $b; }
        $categoryMap = [];
        foreach ($categories as $c) { if (isset($c['id'])) $categoryMap[$c['id']] = $c; }

        $data = [
            'title' => 'Products',
            'subtitle' => 'Manage your products',
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'taxRates' => $taxRates,
            'brandMap' => $brandMap,
            'categoryMap' => $categoryMap,
        ];

        // Render admin layout + view
        $this->renderAdmin('admin/purchases/list', $data);
    }

    /**
     * Render a view wrapped with admin header/footer
     */
    private function renderAdmin($view, $data = []) {
        extract($data);
        require_once APP_PATH . 'views/admin/layouts/header.php';
        require_once APP_PATH . 'views/' . $view . '.php';
        require_once APP_PATH . 'views/admin/layouts/footer.php';
    }
}
