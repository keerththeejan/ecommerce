<?php
/**
 * Product Controller
 * Handles product listing and details
 */
class ProductController extends Controller {
    private $productModel;
    private $categoryModel;
    private $supplierModel;
    private $unitModel;
    
    public function __construct() {
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->supplierModel = $this->model('Supplier');
        $this->unitModel = $this->model('Unit');
        if (method_exists($this->unitModel, 'ensureSchema')) {
            $this->unitModel->ensureSchema();
        }
    }

    /**
     * Suggest product names with availability for autocomplete
     * Returns JSON: [{ name, available, stock }]
     */
    public function suggest() {
        // Allow both admins and logged-in customers to fetch suggestions
        if (!isLoggedIn() && !isAdmin()) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
            return;
        }

        $q = trim((string)$this->get('q', ''));
        $limit = (int)$this->get('limit', 10);
        if ($limit <= 0 || $limit > 50) { $limit = 10; }

        $results = [];
        try {
            if ($q === '') {
                // Default: first N active products ordered by name
                $all = $this->productModel->getActiveProducts();
                foreach ($all as $row) {
                    $r = is_object($row) ? (array)$row : (array)$row;
                    $stock = (int)($r['stock_quantity'] ?? 0);
                    $status = (string)($r['status'] ?? 'inactive');
                    $results[] = [
                        'name' => (string)($r['name'] ?? ''),
                        'available' => ($status === 'active' && $stock > 0),
                        'stock' => $stock,
                    ];
                    if (count($results) >= $limit) break;
                }
            } else {
                // Simple name LIKE search among active products
                // Reuse searchProducts but filter active already
                $matched = $this->productModel->searchProducts($q);
                foreach ($matched as $row) {
                    $r = is_object($row) ? (array)$row : (array)$row;
                    $stock = (int)($r['stock_quantity'] ?? 0);
                    $status = (string)($r['status'] ?? 'inactive');
                    $results[] = [
                        'name' => (string)($r['name'] ?? ''),
                        'available' => ($status === 'active' && $stock > 0),
                        'stock' => $stock,
                    ];
                    if (count($results) >= $limit) break;
                }
            }

            $this->json(['success' => true, 'data' => $results]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Quick create a product (for use inside Purchase -> purchase2)
     * Accepts minimal fields and auto-fills the rest. Returns JSON.
     */
    public function quickCreate() {
        // Only allow AJAX POST from authenticated admin context
        if (!isAdmin()) { $this->json(['success' => false, 'message' => 'Unauthorized'], 401); return; }
        if (!$this->isPost() || !$this->isAjax()) { $this->json(['success' => false, 'message' => 'Invalid request'], 400); return; }

        try {
            $name = trim((string)$this->post('name'));
            $price = (float)($this->post('price') ?? 0);
            $salePrice = $this->post('sale_price');
            $stockQty = $this->post('stock_quantity');
            $supplierId = (int)($this->post('supplier_id') ?? 0);
            $sku = trim((string)($this->post('sku') ?? ''));

            if ($name === '' || $price <= 0) {
                $this->json(['success' => false, 'message' => 'Name and price are required'], 422);
                return;
            }

            // Generate SKU if not provided
            if ($sku === '') {
                $baseSku = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', substr($name, 0, 10)));
                $sku = $baseSku . (time() % 100000);
            } else {
                // Ensure uniqueness
                $existing = $this->productModel->getSingleBy('sku', $sku);
                if ($existing) {
                    $this->json(['success' => false, 'message' => 'SKU already exists'], 422);
                    return;
                }
            }

            // Build minimal data with sane defaults
            $data = [
                'name' => $name,
                'description' => '',
                'price' => $price,
                'sale_price' => ($salePrice === '' ? null : $salePrice),
                'price2' => $price,
                'price3' => $price,
                'stock_quantity' => is_numeric($stockQty) ? (float)$stockQty : 0,
                'category_id' => null,
                'country_id' => null,
                'brand_id' => null,
                'status' => 'active',
                'expiry_date' => null,
                'supplier' => null,
                'batch_number' => null,
                'sku' => $sku,
            ];

            // Map supplier field based on schema
            try {
                // Prefer supplier_id column if present
                $hasSupplierId = false;
                try {
                    $this->db->query("SHOW COLUMNS FROM products LIKE 'supplier_id'");
                    $cols = $this->db->resultSet();
                    $hasSupplierId = !empty($cols);
                } catch (Exception $e) {
                    $hasSupplierId = false;
                }

                if ($supplierId > 0) {
                    if ($hasSupplierId) {
                        $data['supplier_id'] = $supplierId;
                    } else {
                        // Fallback to supplier name string
                        if (method_exists($this->supplierModel, 'getById')) {
                            $s = $this->supplierModel->getById($supplierId);
                            $supplierName = is_array($s) ? ($s['name'] ?? null) : (is_object($s) ? ($s->name ?? null) : null);
                            if ($supplierName) { $data['supplier'] = $supplierName; }
                        }
                    }
                }
            } catch (Exception $e) {
                // ignore supplier mapping errors
            }

            // Create the product
            $newId = $this->productModel->create($data);
            if (!$newId) {
                $err = method_exists($this->productModel, 'getLastError') ? $this->productModel->getLastError() : 'Create failed';
                $this->json(['success' => false, 'message' => $err], 500);
                return;
            }

            $this->json(['success' => true, 'message' => 'Product created', 'productId' => $newId]);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Display all products
     */
    public function index() {
        // Get page number
        $page = $this->get('page', 1);
        
        // Get all products with pagination
        $products = $this->productModel->getAllProducts($page, 12);
        
        // Get specific products in the requested order
        $specificProductNames = ['Ashwiny', 'keerthtikan', 'keethan', 'kujinsha', 'pirathi', 'thilu', 'vanu', 'yathu'];
        $specificProducts = [];
        
        // Fetch each specific product by name
        foreach ($specificProductNames as $name) {
            $product = $this->productModel->getProductByName($name);
            if ($product) {
                $specificProducts[] = $product;
            }
        }
        
        // Get categories for filter
        $categories = $this->categoryModel->getActiveCategories();
        
        // Load view with both regular and specific products
        $this->view('customer/products/index', [
            'products' => $products,
            'specificProducts' => $specificProducts,
            'categories' => $categories
        ]);
    }
    
    /**
     * Display all products (alternative route for /all)
     */
    public function all() {
        // Get page number
        $page = $this->get('page', 1);
        $limit = 12;
        
        // Get all active products (without pagination restrictions)
        $allProducts = $this->productModel->getActiveProducts();
        
        // Apply pagination manually
        $total = count($allProducts);
        $totalPages = ceil($total / $limit);
        $offset = ($page - 1) * $limit;
        $products = array_slice($allProducts, $offset, $limit);
        
        // Ensure products is an array of arrays
        $productsArray = [];
        foreach ($products as $product) {
            $productsArray[] = is_object($product) ? (array)$product : $product;
        }
        
        // Get categories for filter
        $categories = $this->categoryModel->getActiveCategories();
        
        // Load view
        $this->view('customer/product/all', [
            'products' => $productsArray,
            'categories' => $categories,
            'total' => $total,
            'limit' => $limit,
            'page' => $page,
            'totalPages' => $totalPages
        ]);
    }
    
    /**
     * Display product details
     * 
     * @param int $id Product ID
     */
    public function show($id) {
        // Get product
        $product = $this->productModel->getProductWithCategory($id);
        
        // Check if product exists
        if(!$product) {
            redirect('products');
        }
        
        // Get related products
        $relatedProducts = $this->productModel->getRelatedProducts($id, $product['category_id']);
        
        // Load view
        $this->view('customer/products/show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
    
    /**
     * Display products by category
     * 
     * @param int $categoryId Category ID
     */
    public function category($categoryId = null) {
        // First, try to get category ID from function parameter (passed by router)
        // If not provided or is invalid, try GET parameters directly
        if($categoryId === null || $categoryId === '' || $categoryId === '0' || $categoryId === 0) {
            // Try 'id' first (used in URL: ?controller=product&action=category&id=63)
            $categoryId = $this->get('id');
            
            // Fall back to 'param' if 'id' is not found
            if($categoryId === null || $categoryId === '' || $categoryId === '0' || $categoryId === 0) {
                $categoryId = $this->get('param');
            }
        }
        
        // Validate we have a category ID (check for null, empty string, '0', or 0)
        if($categoryId === null || $categoryId === '' || $categoryId === '0' || $categoryId === 0) {
            redirect('product/index');
            return;
        }
        
        // Ensure categoryId is numeric and valid
        $categoryId = (int)$categoryId;
        
        // Log for debugging (remove in production if needed)
        // error_log("CategoryController::category() - Processing category ID: {$categoryId}");
        
        // Validate category ID is positive
        if($categoryId <= 0) {
            redirect('product/index');
            return;
        }
        
        // Get category by ID
        $categoryIdInt = (int)$categoryId;
        $category = $this->categoryModel->getById($categoryIdInt);
        
        // Check if category exists
        if(!$category) {
            error_log("Category not found for ID: {$categoryIdInt}");
            redirect('product/index');
            return;
        }
        
        // Ensure category is an array
        if(is_object($category)) {
            $category = (array)$category;
        }
        
        // Validate category data structure
        if(!is_array($category) || empty($category)) {
            error_log("Invalid category data structure for ID: {$categoryIdInt}");
            redirect('product/index');
            return;
        }
        
        // Double-check the category ID matches what we requested
        $fetchedCategoryId = isset($category['id']) ? (int)$category['id'] : 0;
        if($fetchedCategoryId !== $categoryIdInt) {
            error_log("Category ID mismatch: Requested {$categoryIdInt}, but got category ID {$fetchedCategoryId}");
            redirect('product/index');
            return;
        }
        
        // Verify category name exists
        if(empty($category['name'])) {
            error_log("Category name is empty for ID: {$categoryIdInt}");
            // Don't redirect, but log the issue - category might have empty name
        }
        
        // Update categoryId to the validated integer value
        $categoryId = $categoryIdInt;
        
        // Get products
        $products = $this->productModel->getProductsByCategory($categoryId);
        
        // All active categories for sidebar
        $categories = $this->categoryModel->getActiveCategories();
        
        // Load view - show the actual clicked category name
        $this->view('customer/products/category', [
            'products' => $products,
            'category' => $category,
            'categories' => $categories
        ]);
    }
    
    /**
     * Search products
     */
    public function search() {
        // Get search keyword
        $keyword = $this->get('keyword', '');
        
        // Get products
        $products = [];
        if(!empty($keyword)) {
            $products = $this->productModel->searchProducts($keyword);
        }
        
        // Get categories for sidebar
        $categories = $this->categoryModel->getActiveCategories();
        
        // Load view
        $this->view('customer/products/search', [
            'products' => $products,
            'keyword' => $keyword,
            'categories' => $categories
        ]);
    }
    
    /**
     * Display products on sale
     */
    public function sale() {
        // Get products on sale (paginated structure)
        $result = $this->productModel->getProductsOnSale();

        // Extract the actual rows and normalize each row to an associative array
        $rows = [];
        if (is_array($result)) {
            $data = $result['data'] ?? [];
            if (is_array($data)) {
                foreach ($data as $row) {
                    $rows[] = is_object($row) ? (array)$row : (array)$row;
                }
            }
        }

        // Get categories for sidebar
        $categories = $this->categoryModel->getActiveCategories();

        // Load view with normalized rows only to match the view's expectations
        $this->view('customer/products/sale', [
            'products' => $rows,
            'categories' => $categories,
        ]);
    }
    
    /**
     * Admin: List all products
     */
    public function adminIndex() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Get page number, per-page filter, and search
        $page = (int) $this->get('page', 1);
        $perPageParam = $this->get('per_page', '20');
        $search = trim((string) $this->get('search', ''));
        $showInactive = (string)$this->get('show_inactive', '0');
        $includeInactive = ($showInactive === '1' || strtolower($showInactive) === 'true');
        $allowedPerPage = ['20', '50', '100', 'all'];
        $perPage = in_array($perPageParam, $allowedPerPage, true) ? $perPageParam : '20';
        $perPageNum = ($perPage === 'all') ? 9999 : (int) $perPage;
        if ($perPageNum < 1) {
            $perPageNum = 20;
        }
        
        // Get products (ID ascending), with optional search
        $products = $this->productModel->paginate($page, $perPageNum, 'id', 'ASC', $search ?: null, $includeInactive);
        $products['per_page_param'] = $perPage;
        $products['search'] = $search;
        $products['show_inactive'] = $includeInactive ? '1' : '0';
        
        // Normalize rows to associative arrays to avoid blank fields in view
        if (isset($products['data']) && is_array($products['data'])) {
            foreach ($products['data'] as $idx => $row) {
                if (is_object($row)) {
                    $products['data'][$idx] = (array)$row;
                }
            }

            // Defensive: hide inactive products in admin list unless explicitly requested
            if (!$includeInactive) {
                $products['data'] = array_values(array_filter($products['data'], function($r) {
                    $status = is_array($r) && isset($r['status']) ? strtolower((string)$r['status']) : '';
                    return $status === 'active' || $status === '';
                }));
            }
        }
        
        // Preload suppliers map for display fallback
        $supplierMap = [];
        if (property_exists($this, 'supplierModel') && $this->supplierModel) {
            try {
                $suppliers = $this->supplierModel->getAllSuppliers();
                if (is_array($suppliers)) {
                    foreach ($suppliers as $s) {
                        if (isset($s['id']) && isset($s['name'])) {
                            $supplierMap[$s['id']] = $s['name'];
                        }
                    }
                }
            } catch (Exception $e) {
                // ignore supplier preload errors in admin list
            }
        }
        
        // Load view
        $this->view('admin/products/index', [
            'products' => $products,
            'supplierMap' => $supplierMap,
            'categories' => $this->categoryModel ? $this->categoryModel->getActiveCategories() : [],
            'suppliers' => ($this->supplierModel && method_exists($this->supplierModel, 'getAllSuppliers')) ? $this->supplierModel->getAllSuppliers() : []
        ]);
    }

    public function inlineUpdate() {
        if(!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Unauthorized access'], 401);
            return;
        }

        if(!$this->isAjax()) {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
            return;
        }

        if(!$this->isPost()) {
            $this->json(['success' => false, 'message' => 'Method not allowed'], 405);
            return;
        }

        $payload = [];
        $raw = file_get_contents('php://input');
        if (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $payload = $decoded;
            }
        }

        $id = (int)($payload['id'] ?? ($_POST['id'] ?? 0));
        $field = (string)($payload['field'] ?? ($_POST['field'] ?? ''));
        $value = $payload['value'] ?? ($_POST['value'] ?? null);

        if ($id <= 0 || $field === '') {
            $this->json(['success' => false, 'message' => 'Invalid payload'], 400);
            return;
        }

        $allowed = [
            'sku' => 'string',
            'price' => 'number',
            'sale_price' => 'number_nullable',
            'price2' => 'number_nullable',
            'price3' => 'number_nullable',
            'customs_charge' => 'number_nullable',
            'transport_charge' => 'number_nullable',
            'stock_quantity' => 'number',
            'category_id' => 'int_nullable',
            'supplier' => 'string_nullable',
            'expiry_date' => 'date_nullable'
        ];

        if (!isset($allowed[$field])) {
            $this->json(['success' => false, 'message' => 'Field not editable'], 400);
            return;
        }

        $product = $this->productModel->getById($id);
        if (!$product) {
            $this->json(['success' => false, 'message' => 'Product not found'], 404);
            return;
        }

        $type = $allowed[$field];
        $normalized = null;

        if ($type === 'string') {
            $normalized = sanitize((string)$value);
            if ($normalized === '') {
                $this->json(['success' => false, 'message' => 'Value is required'], 400);
                return;
            }
        } elseif ($type === 'string_nullable') {
            $normalized = sanitize((string)$value);
            if (trim((string)$normalized) === '') {
                $normalized = null;
            }
        } elseif ($type === 'number') {
            if ($value === null || $value === '' || !is_numeric($value)) {
                $this->json(['success' => false, 'message' => 'Invalid number'], 400);
                return;
            }
            $normalized = (float)$value;
            if ($normalized < 0) {
                $this->json(['success' => false, 'message' => 'Value cannot be negative'], 400);
                return;
            }
            if ($field === 'stock_quantity') {
                $normalized = (float)$normalized;
            }
        } elseif ($type === 'number_nullable') {
            if ($value === null || $value === '') {
                $normalized = null;
            } else {
                if (!is_numeric($value)) {
                    $this->json(['success' => false, 'message' => 'Invalid number'], 400);
                    return;
                }
                $normalized = (float)$value;
                if ($normalized < 0) {
                    $this->json(['success' => false, 'message' => 'Value cannot be negative'], 400);
                    return;
                }
            }
        } elseif ($type === 'int_nullable') {
            if ($value === null || $value === '' || (string)$value === '0') {
                $normalized = null;
            } else {
                if (!is_numeric($value)) {
                    $this->json(['success' => false, 'message' => 'Invalid value'], 400);
                    return;
                }
                $normalized = (int)$value;
                if ($normalized <= 0) {
                    $normalized = null;
                }
            }
        } elseif ($type === 'date_nullable') {
            $s = trim((string)$value);
            if ($s === '') {
                $normalized = null;
            } else {
                $ts = strtotime($s);
                if (!$ts) {
                    $this->json(['success' => false, 'message' => 'Invalid date'], 400);
                    return;
                }
                $normalized = date('Y-m-d', $ts);
            }
        }

        if ($field === 'sku') {
            if (strlen((string)$normalized) > 50) {
                $this->json(['success' => false, 'message' => 'SKU too long'], 400);
                return;
            }
            $existing = $this->productModel->getSingleBy('sku', $normalized);
            if ($existing && (int)($existing['id'] ?? 0) !== $id) {
                $this->json(['success' => false, 'message' => 'SKU already exists'], 400);
                return;
            }
        }

        if (!$this->productModel->update($id, [$field => $normalized])) {
            $error = method_exists($this->productModel, 'getLastError') ? $this->productModel->getLastError() : 'Failed to update';
            $this->json(['success' => false, 'message' => $error], 400);
            return;
        }

        $updated = $this->productModel->getById($id);
        $rawValue = is_array($updated) && array_key_exists($field, $updated) ? $updated[$field] : $normalized;
        $displayValue = $rawValue;

        if (in_array($field, ['price', 'sale_price', 'price2', 'price3', 'customs_charge', 'transport_charge'], true)) {
            try {
                $displayValue = ($rawValue === null || $rawValue === '') ? '' : formatPrice($rawValue);
            } catch (Exception $e) {
                $displayValue = $rawValue;
            }
        }

        if ($field === 'category_id') {
            $catName = '';
            try {
                $cats = $this->categoryModel ? $this->categoryModel->getActiveCategories() : [];
                foreach ($cats as $c) {
                    if ((int)($c['id'] ?? 0) === (int)$rawValue) {
                        $catName = (string)($c['name'] ?? '');
                        break;
                    }
                }
            } catch (Exception $e) {
                $catName = '';
            }
            $displayValue = $catName !== '' ? $catName : 'Uncategorized';
        }

        $this->json([
            'success' => true,
            'message' => 'Saved',
            'id' => $id,
            'field' => $field,
            'value' => $rawValue,
            'display' => $displayValue
        ]);
    }
    
    /**
     * Admin: Export products to CSV
     */
    public function export() {
        if (!isAdmin()) {
            redirect('user/login');
        }
        $products = $this->productModel->getAllForExport();
        $filename = 'products_export_' . date('Y-m-d_H-i-s') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['name', 'description', 'sku', 'price', 'sale_price', 'price2', 'price3', 'stock_quantity', 'category_id', 'brand_id', 'country_id', 'supplier', 'batch_number', 'status', 'add_date', 'expiry_date', 'tax_id']);
        foreach ($products as $p) {
            fputcsv($out, [
                $p['name'] ?? '',
                $p['description'] ?? '',
                $p['sku'] ?? '',
                $p['price'] ?? '',
                $p['sale_price'] ?? '',
                $p['price2'] ?? '',
                $p['price3'] ?? '',
                $p['stock_quantity'] ?? 0,
                $p['category_id'] ?? '',
                $p['brand_id'] ?? '',
                $p['country_id'] ?? '',
                $p['supplier'] ?? '',
                $p['batch_number'] ?? 0,
                $p['status'] ?? 'active',
                $p['add_date'] ?? date('Y-m-d'),
                $p['expiry_date'] ?? '',
                $p['tax_id'] ?? ''
            ]);
        }
        fclose($out);
        exit;
    }
    
    /**
     * Admin: Import products from CSV
     */
    public function import() {
        if (!isAdmin()) {
            redirect('user/login');
        }
        if (!$this->isPost()) {
            flash('product_error', 'Please upload a CSV file.', 'alert alert-danger');
            header('Location: ' . BASE_URL . '?controller=product&action=adminIndex');
            exit;
        }
        $file = $_FILES['import_file'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            flash('product_error', 'Invalid or missing file upload.', 'alert alert-danger');
            header('Location: ' . BASE_URL . '?controller=product&action=adminIndex');
            exit;
        }
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if ($ext !== 'csv') {
            flash('product_error', 'Only CSV files are allowed.', 'alert alert-danger');
            header('Location: ' . BASE_URL . '?controller=product&action=adminIndex');
            exit;
        }
        $handle = fopen($file['tmp_name'], 'r');
        if (!$handle) {
            flash('product_error', 'Could not read uploaded file.', 'alert alert-danger');
            header('Location: ' . BASE_URL . '?controller=product&action=adminIndex');
            exit;
        }
        $headers = fgetcsv($handle);
        $created = 0;
        $errors = [];
        $rowNum = 1;
        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            $data = array_combine(array_map('trim', $headers ?: []), $row);
            if (!$data) continue;
            $data = array_change_key_case($data, CASE_LOWER);
            if (empty(trim($data['name'] ?? ''))) continue;
            $sku = trim($data['sku'] ?? '');
            if (empty($sku)) {
                $sku = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', substr($data['name'], 0, 10))) . (time() % 10000 + $rowNum);
            }
            if ($this->productModel->getSingleBy('sku', $sku)) {
                $sku = $sku . '_' . $rowNum;
            }
            $addDate = !empty(trim($data['add_date'] ?? '')) ? trim($data['add_date']) : date('Y-m-d');
            $expiryDate = !empty(trim($data['expiry_date'] ?? '')) ? trim($data['expiry_date']) : null;
            $insert = [
                'name' => sanitize($data['name'] ?? ''),
                'description' => sanitize($data['description'] ?? ''),
                'sku' => $sku,
                'price' => (float)($data['price'] ?? 0),
                'sale_price' => !empty(trim($data['sale_price'] ?? '')) ? (float)$data['sale_price'] : null,
                'price2' => !empty(trim($data['price2'] ?? '')) ? (float)$data['price2'] : (float)($data['price'] ?? 0),
                'price3' => !empty(trim($data['price3'] ?? '')) ? (float)$data['price3'] : (float)($data['price'] ?? 0),
                'stock_quantity' => (int)($data['stock_quantity'] ?? 0),
                'category_id' => (int)($data['category_id'] ?? 0) ?: null,
                'brand_id' => (int)($data['brand_id'] ?? 0) ?: null,
                'country_id' => (int)($data['country_id'] ?? 0) ?: null,
                'supplier' => sanitize($data['supplier'] ?? ''),
                'batch_number' => (int)($data['batch_number'] ?? 0),
                'status' => in_array(trim($data['status'] ?? ''), ['active', 'inactive']) ? trim($data['status']) : 'active',
                'add_date' => $addDate,
                'expiry_date' => $expiryDate,
                'tax_id' => ($tid = (int)($data['tax_id'] ?? 0)) > 0 ? $tid : null
            ];
            if ($this->productModel->create($insert)) {
                $created++;
            } else {
                $errors[] = "Row {$rowNum}: " . ($this->productModel->lastError ?? 'Failed');
            }
        }
        fclose($handle);
        if ($created > 0) {
            flash('product_success', "Successfully imported {$created} product(s).", 'alert alert-success');
        }
        if (!empty($errors)) {
            flash('product_error', implode(' ', array_slice($errors, 0, 5)) . (count($errors) > 5 ? ' ...' : ''), 'alert alert-danger');
        }
        header('Location: ' . BASE_URL . '?controller=product&action=adminIndex');
        exit;
    }
    
    /**
     * Admin: Create product form
     */
    public function create() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Get categories for dropdown
        $categories = method_exists($this->categoryModel, 'getActiveCategoriesWithTaxRate')
            ? $this->categoryModel->getActiveCategoriesWithTaxRate()
            : $this->categoryModel->getActiveCategories();
        $units = method_exists($this->unitModel, 'getActiveUnits')
            ? $this->unitModel->getActiveUnits()
            : [];
        // Get suppliers for dropdown
        $suppliers = method_exists($this->supplierModel, 'getAllSuppliers') ? $this->supplierModel->getAllSuppliers() : [];
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'name' => sanitize($this->post('name')),
                'description' => sanitize($this->post('description')),
                'hsn_code' => sanitize($this->post('hsn_code')),
                'price' => ($p = $this->post('price')) === '' || $p === null ? null : $p,
                'sale_price' => $this->post('sale_price') ?: null,
                'price2' => $this->post('price2') ?: $this->post('price'),
                'price3' => $this->post('price3') ?: $this->post('price'),
                'customs_charge' => ($cc = $this->post('customs_charge')) === '' || $cc === null ? null : $cc,
                'transport_charge' => ($tc = $this->post('transport_charge')) === '' || $tc === null ? null : $tc,
                'stock_quantity' => ($sq = $this->post('stock_quantity')) === '' || $sq === null ? 0 : $sq,
                'category_id' => $this->post('category_id'),
                'country_id' => ($cid = $this->post('country_id')) && (int)$cid > 0 ? (int)$cid : null,
                'brand_id' => ($bid = $this->post('brand_id')) && (int)$bid > 0 ? (int)$bid : null,
                'status' => $this->post('status'),
                'expiry_date' => $this->post('expiry_date') ?: null,
                'supplier' => sanitize($this->post('supplier')),
                'batch_number' => sanitize($this->post('batch_number')),
                'tax_id' => ($tid = $this->post('tax_id')) && (int)$tid > 0 ? (int)$tid : null,
                'unit_id' => ($uid = $this->post('unit_id')) && (int)$uid > 0 ? (int)$uid : null
            ];

            if ($data['price'] === null && ($data['price2'] !== '' && $data['price2'] !== null)) {
                $data['price'] = $data['price2'];
            }
            
            // Handle SKU - generate if empty or check for uniqueness
            $errors = $errors ?? [];
            $sku = trim($this->post('sku'));
            if (empty($sku)) {
                // Generate a unique SKU based on product name and timestamp
                $baseSku = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', substr($data['name'] ?? '', 0, 10)));
                $baseSku = $baseSku !== '' ? $baseSku : 'SKU';
                $sku = $baseSku . (time() % 100000);
            } else {
                // Check if SKU already exists
                $existingProduct = $this->productModel->getSingleBy('sku', $sku);
                if ($existingProduct) {
                    $errors['sku'] = 'This SKU is already in use. Please choose a different one.';
                }
            }
            $data['sku'] = $sku;
            
            // Handle image upload
            $image = $_FILES['image'] ?? null;
            if($image && $image['error'] == 0) {
                $safeOriginal = preg_replace('/[^a-zA-Z0-9_.-]/', '_', (string)($image['name'] ?? 'image'));
                $baseName = pathinfo($safeOriginal, PATHINFO_FILENAME);
                $baseName = $baseName !== '' ? $baseName : 'product';
                $fileName = time() . '_' . $baseName . '.jpg';
                $targetPath = PUBLIC_PATH . 'uploads/products/' . $fileName;
                
                // Create directory if it doesn't exist
                if(!file_exists(PUBLIC_PATH . 'uploads/products/')) {
                    mkdir(PUBLIC_PATH . 'uploads/products/', 0777, true);
                }

                $tmpInputPath = rtrim(sys_get_temp_dir(), '\\/') . DIRECTORY_SEPARATOR . 'product_upload_' . time() . '_' . mt_rand(1000, 9999);
                if (move_uploaded_file($image['tmp_name'], $tmpInputPath)) {
                    $finalPath = $this->processProductImage($tmpInputPath, $targetPath);
                    @unlink($tmpInputPath);
                    if ($finalPath) {
                        $data['image'] = 'uploads/products/' . basename($finalPath);
                    }
                }
            }
            
            // Validate data
            $validationRules = [
                'name' => 'required|max:255',
                'price' => 'nullable|numeric|min:0',
                'stock_quantity' => 'nullable|numeric|min:0',
                'category_id' => 'required|numeric',
                'country_id' => 'nullable|numeric',
                'brand_id' => 'nullable|numeric',
                'price2' => 'required|numeric',
                'price3' => 'nullable|numeric',
                'sale_price' => 'nullable|numeric',
                'hsn_code' => 'nullable|max:50',
                'customs_charge' => 'nullable|numeric|min:0',
                'transport_charge' => 'nullable|numeric|min:0',
                'supplier' => 'nullable|max:255',
                'batch_number' => 'nullable|max:100',
                'expiry_date' => 'nullable',
                'tax_id' => 'nullable|numeric'
                ,'unit_id' => 'nullable|numeric'
            ];
            
            $validationErrors = $this->validate($data, $validationRules);
            $errors = array_merge($errors ?? [], $validationErrors);
            
            // Make sure there are no errors
            if(empty($errors)) {
                try {
                    // Create product
                    $productId = $this->productModel->create($data);
                    
                    if($productId) {
                        if($this->isAjax()) {
                            $this->json([
                                'success' => true, 
                                'message' => 'Product created successfully',
                                'productId' => $productId
                            ]);
                            return;
                        }
                        
                        $success = 'Product created successfully';
                        $data = [
                            'name' => '', 'description' => '', 'price' => '', 'sale_price' => '',
                            'stock_quantity' => '', 'sku' => '', 'category_id' => '', 'country_id' => '',
                            'brand_id' => '', 'status' => 'active', 'expiry_date' => '',
                            'hsn_code' => '', 'customs_charge' => '', 'transport_charge' => '',
                            'supplier' => '', 'batch_number' => '', 'tax_id' => '', 'unit_id' => ''
                        ];
                        $this->view('admin/products/create', [
                            'data' => $data, 'categories' => $categories, 'suppliers' => $suppliers,
                            'units' => $units, 'success' => $success, 'errors' => []
                        ]);
                        return;
                    }
                    $dbError = $this->productModel->getLastError();
                    throw new Exception($dbError ?: 'Failed to create product');
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    error_log('Product creation error: ' . $error);
                    if($this->isAjax()) {
                        $this->json(['success' => false, 'message' => $error], 500);
                        return;
                    }
                    $errors = $errors ?? [];
                    $errors['db_error'] = $error;
                }
            }
            
            // If we got here, there were validation errors
            if($this->isAjax()) {
                $this->json([
                    'success' => false,
                    'message' => implode(' ', array_values($errors)),
                    'errors' => $errors
                ], 422);
                return;
            }
            $this->view('admin/products/create', [
                'errors' => $errors,
                'data' => $data,
                'categories' => $categories,
                'suppliers' => $suppliers,
                'units' => $units
            ]);
        } else {
            // Init data
            $data = [
                'name' => '',
                'description' => '',
                'price' => '',
                'sale_price' => '',
                'stock_quantity' => '',
                'sku' => '',
                'category_id' => '',
                'country_id' => '',
                'brand_id' => '',
                'status' => 'active',
                'expiry_date' => '',
                'hsn_code' => '',
                'customs_charge' => '',
                'transport_charge' => '',
                'supplier' => '',
                'batch_number' => '',
                'tax_id' => '',
                'unit_id' => ''
            ];
            
            // Load view
            $this->view('admin/products/create', [
                'data' => $data,
                'categories' => $categories,
                'suppliers' => $suppliers,
                'units' => $units,
                'errors' => []
            ]);
        }
    }

    private function processProductImage($inputPath, $outputJpgPath, $size = 800) {
        if (!is_string($inputPath) || $inputPath === '' || !file_exists($inputPath)) {
            return null;
        }
        $workingPath = $inputPath;

        $apiKey = defined('REMOVE_BG_API_KEY') ? (string)REMOVE_BG_API_KEY : '';
        if ($apiKey !== '') {
            $removed = $this->removeBackgroundViaRemoveBg($workingPath, $apiKey);
            if ($removed && file_exists($removed)) {
                $workingPath = $removed;
            }
        }

        $outDir = dirname($outputJpgPath);
        if (!is_dir($outDir)) {
            @mkdir($outDir, 0777, true);
        }

        $resultPath = $this->fitImageToSquare($workingPath, $outputJpgPath, (int)$size);

        if ($workingPath !== $inputPath) {
            @unlink($workingPath);
        }

        return $resultPath;
    }

    private function removeBackgroundViaRemoveBg($inputPath, $apiKey) {
        if (!function_exists('curl_init')) {
            return null;
        }
        $endpoint = 'https://api.remove.bg/v1.0/removebg';
        $tmpOut = rtrim(sys_get_temp_dir(), '\\/') . DIRECTORY_SEPARATOR . 'removebg_' . time() . '_' . mt_rand(1000, 9999) . '.png';

        $ch = curl_init();
        $postFields = [
            'image_file' => new CURLFile($inputPath),
            'size' => 'auto'
        ];
        curl_setopt_array($ch, [
            CURLOPT_URL => $endpoint,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                'X-Api-Key: ' . $apiKey
            ],
            CURLOPT_TIMEOUT => 60,
        ]);

        $response = curl_exec($ch);
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($response === false || $httpCode < 200 || $httpCode >= 300) {
            return null;
        }

        if (@file_put_contents($tmpOut, $response) === false) {
            return null;
        }
        return $tmpOut;
    }

    private function fitImageToSquare($inputPath, $outputJpgPath, $size = 800) {
        if (!extension_loaded('gd')) {
            return null;
        }
        $info = @getimagesize($inputPath);
        if (!$info || empty($info[0]) || empty($info[1])) {
            return null;
        }

        $mime = $info['mime'] ?? '';
        $src = null;
        if ($mime === 'image/jpeg') {
            $src = @imagecreatefromjpeg($inputPath);
        } elseif ($mime === 'image/png') {
            $src = @imagecreatefrompng($inputPath);
        } elseif ($mime === 'image/webp' && function_exists('imagecreatefromwebp')) {
            $src = @imagecreatefromwebp($inputPath);
        }
        if (!$src) {
            return null;
        }

        $srcW = imagesx($src);
        $srcH = imagesy($src);
        if ($srcW <= 0 || $srcH <= 0) {
            imagedestroy($src);
            return null;
        }

        $dst = imagecreatetruecolor($size, $size);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        $scale = min($size / $srcW, $size / $srcH);
        $newW = (int)max(1, round($srcW * $scale));
        $newH = (int)max(1, round($srcH * $scale));
        $dstX = (int)floor(($size - $newW) / 2);
        $dstY = (int)floor(($size - $newH) / 2);

        imagecopyresampled($dst, $src, $dstX, $dstY, 0, 0, $newW, $newH, $srcW, $srcH);

        $dir = dirname($outputJpgPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $saved = @imagejpeg($dst, $outputJpgPath, 85);
        imagedestroy($src);
        imagedestroy($dst);

        return $saved ? $outputJpgPath : null;
    }
    
    /**
     * Admin: Edit product form
     * 
     * @param int $id Product ID
     */
    public function edit($id) {
        // Check if admin
        if(!isAdmin()) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Unauthorized access'], 401);
                return;
            }
            redirect('user/login');
        }
        
        // Get product
        $product = $this->productModel->getById($id);
        
        // Check if product exists
        if(!$product) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Product not found'], 404);
                return;
            }
            flash('product_error', 'Product not found', 'alert alert-danger');
            redirect('admin/products');
        }
        
        // Get categories for dropdown
        $categories = method_exists($this->categoryModel, 'getActiveCategoriesWithTaxRate')
            ? $this->categoryModel->getActiveCategoriesWithTaxRate()
            : $this->categoryModel->getActiveCategories();
        $units = method_exists($this->unitModel, 'getActiveUnits')
            ? $this->unitModel->getActiveUnits()
            : [];
        // Get suppliers for dropdown
        $suppliers = method_exists($this->supplierModel, 'getAllSuppliers') ? $this->supplierModel->getAllSuppliers() : [];
        
        // Check for POST request
        if($this->isPost()) {
            try {
                // Process form
                $data = [
                    'name' => sanitize($this->post('name')),
                    'description' => sanitize($this->post('description')),
                    'price' => ($p = $this->post('price')) === '' || $p === null ? null : $p,
                    'sale_price' => $this->post('sale_price') ?: null,
                    'price2' => $this->post('price2') ?: $this->post('price'),
                    'price3' => $this->post('price3') ?: $this->post('price'),
                    'hsn_code' => sanitize($this->post('hsn_code')),
                    'customs_charge' => ($cc = $this->post('customs_charge')) === '' || $cc === null ? null : $cc,
                    'transport_charge' => ($tc = $this->post('transport_charge')) === '' || $tc === null ? null : $tc,
                    'stock_quantity' => ($sq = $this->post('stock_quantity')) === '' || $sq === null ? 0 : $sq,
                    'sku' => sanitize($this->post('sku')),
                    'category_id' => $this->post('category_id'),
                    'country_id' => ($cid = $this->post('country_id')) && (int)$cid > 0 ? (int)$cid : null,
                    'brand_id' => ($bid = $this->post('brand_id')) && (int)$bid > 0 ? (int)$bid : null,
                    'status' => $this->post('status') ?: 'active',
                    'expiry_date' => $this->post('expiry_date') ?: null,
                    'supplier' => sanitize($this->post('supplier')),
                    'batch_number' => sanitize($this->post('batch_number')),
                    'tax_id' => ($tid = $this->post('tax_id')) && (int)$tid > 0 ? (int)$tid : null,
                    'unit_id' => ($uid = $this->post('unit_id')) && (int)$uid > 0 ? (int)$uid : null
                ];

                if ($data['price'] === null && ($data['price2'] !== '' && $data['price2'] !== null)) {
                    $data['price'] = $data['price2'];
                }
                
                // Validate data before processing
                $errors = $this->validate($data, [
                    'name' => 'required|max:255',
                    'price' => 'nullable|numeric|min:0',
                    'stock_quantity' => 'nullable|numeric|min:0',
                    'sku' => 'required|max:50',
                    'category_id' => 'required',
                    'country_id' => 'nullable|numeric',
                    'brand_id' => 'nullable|numeric',
                    'status' => 'required|in:active,inactive',
                    'hsn_code' => 'nullable|max:50',
                    'customs_charge' => 'nullable|numeric|min:0',
                    'transport_charge' => 'nullable|numeric|min:0',
                    'supplier' => 'nullable|max:255',
                    'batch_number' => 'nullable|max:100',
                    'expiry_date' => 'nullable',
                    'tax_id' => 'nullable|numeric',
                    'unit_id' => 'nullable|numeric'
                ]);
                
                if(!empty($errors)) {
                    throw new Exception(implode("\n", $errors));
                }
                
                // Handle image upload if provided
                $imagePath = null;
                $image = $_FILES['image'] ?? null;
                if($image && $image['error'] == 0) {
                    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $image['name']);
                    $targetDir = PUBLIC_PATH . 'uploads/products/';
                    $targetPath = $targetDir . $fileName;
                    
                    // Create directory if it doesn't exist
                    if(!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    
                    // Check if file is an actual image
                    $check = getimagesize($image['tmp_name']);
                    if($check === false) {
                        throw new Exception('File is not an image.');
                    }
                    
                    // Check file size (max 5MB)
                    if($image['size'] > 5000000) {
                        throw new Exception('Sorry, your file is too large. Max 5MB allowed.');
                    }
                    
                    // Allow certain file formats
                    $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
                    if(!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        throw new Exception('Sorry, only JPG, JPEG, PNG, GIF & WebP files are allowed.');
                    }
                    
                    // Upload file
                    if(move_uploaded_file($image['tmp_name'], $targetPath)) {
                        $imagePath = 'uploads/products/' . $fileName;
                    } else {
                        throw new Exception('Sorry, there was an error uploading your file.');
                    }
                }
                
                // Update product data with new image if uploaded
                if($imagePath) {
                    // Delete old image if it exists and is not the default
                    if(!empty($product['image']) && file_exists(PUBLIC_PATH . $product['image']) && 
                       strpos($product['image'], 'default-product') === false) {
                        @unlink(PUBLIC_PATH . $product['image']);
                    }
                    $data['image'] = $imagePath;
                }
                
                $before = is_array($product) ? $product : [];

                // Update product in database
                if(!$this->productModel->update($id, $data)) {
                    $lastError = method_exists($this->productModel, 'getLastError') ? $this->productModel->getLastError() : '';
                    $detail = $lastError ? (': ' . $lastError) : '';
                    throw new Exception('Failed to update product in database' . $detail);
                }
                
                // Get updated product data
                $updatedProduct = $this->productModel->getById($id);
                
                $changeSet = [];
                foreach ($data as $field => $newVal) {
                    $oldVal = $before[$field] ?? null;
                    if ((string)$oldVal !== (string)$newVal) {
                        $changeSet[$field] = ['before' => $oldVal, 'after' => $newVal];
                    }
                }

                // Prepare response using updated product data
                $payload = is_array($updatedProduct) ? $updatedProduct : $data;
                $response = [
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'meta' => [
                        'last_updated' => date('Y-m-d H:i:s'),
                        'changes' => $changeSet
                    ],
                    'data' => [
                        'id' => $id,
                        'name' => $payload['name'] ?? $data['name'] ?? $product['name'],
                        'description' => $payload['description'] ?? $data['description'] ?? $product['description'] ?? '',
                        'price' => isset($payload['price']) ? $payload['price'] : ($data['price'] ?? $product['price']),
                        'sale_price' => $payload['sale_price'] ?? ($data['sale_price'] ?? null),
                        'price2' => $payload['price2'] ?? ($data['price2'] ?? null),
                        'price3' => $payload['price3'] ?? ($data['price3'] ?? null),
                        'hsn_code' => $payload['hsn_code'] ?? ($data['hsn_code'] ?? ($product['hsn_code'] ?? null)),
                        'customs_charge' => $payload['customs_charge'] ?? ($data['customs_charge'] ?? ($product['customs_charge'] ?? null)),
                        'transport_charge' => $payload['transport_charge'] ?? ($data['transport_charge'] ?? ($product['transport_charge'] ?? null)),
                        'stock_quantity' => isset($payload['stock_quantity']) ? $payload['stock_quantity'] : ($data['stock_quantity'] ?? $product['stock_quantity']),
                        'image' => $payload['image'] ?? ($data['image'] ?? ($product['image'] ?? null)),
                        'sku' => $payload['sku'] ?? ($data['sku'] ?? $product['sku'] ?? ''),
                        'category_id' => $payload['category_id'] ?? ($data['category_id'] ?? $product['category_id'] ?? null),
                        'country_id' => $payload['country_id'] ?? ($data['country_id'] ?? $product['country_id'] ?? null),
                        'brand_id' => $payload['brand_id'] ?? ($data['brand_id'] ?? $product['brand_id'] ?? null),
                        'status' => $payload['status'] ?? ($data['status'] ?? $product['status'] ?? 'active'),
                        'expiry_date' => $payload['expiry_date'] ?? ($data['expiry_date'] ?? null),
                        'supplier' => $payload['supplier'] ?? ($data['supplier'] ?? null),
                        'batch_number' => $payload['batch_number'] ?? ($data['batch_number'] ?? null),
                        'tax_id' => $payload['tax_id'] ?? ($data['tax_id'] ?? ($product['tax_id'] ?? null)),
                        'unit_id' => $payload['unit_id'] ?? ($data['unit_id'] ?? ($product['unit_id'] ?? null)),
                    ]
                ];
                
                if($this->isAjax()) {
                    $this->json($response);
                    return;
                }
                
                flash('product_success', $response['message']);
                
            } catch (Exception $e) {
                $errorMsg = $e->getMessage();
                error_log('Product update error: ' . $errorMsg);
                
                if($this->isAjax()) {
                    $this->json([
                        'success' => false,
                        'message' => $errorMsg
                    ], 400);
                    return;
                }
                
                // For non-AJAX, load the view with errors
                $this->view('admin/products/edit', [
                    'errors' => ['form' => $errorMsg],
                    'product' => array_merge($product, $data ?? []),
                    'categories' => $categories,
                    'suppliers' => $suppliers,
                    'units' => $units
                ]);
                return;
            }
            
            // If not AJAX, redirect to products list
            if(!$this->isAjax()) {
                redirect('admin/products');
            }
        } else {
            // Load view on initial GET
            $this->view('admin/products/edit', [
                'product' => $product,
                'categories' => $categories,
                'suppliers' => $suppliers,
                'units' => $units,
                'errors' => []
            ]);
            return;
        }
    }

    public function addStock($id = null) {
        if(!isAdmin()) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Unauthorized access'], 401);
                return;
            }
            redirect('user/login');
        }

        if ($id === null || $id === '' || (is_numeric($id) && (int)$id <= 0)) {
            $id = $_GET['id'] ?? null;
        }
        $id = (int)$id;

        if ($id <= 0) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Product ID is required'], 400);
                return;
            }
            flash('product_error', 'Product ID is required', 'alert alert-danger');
            redirect('admin/products');
        }

        if(!$this->isPost()) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Invalid request'], 405);
                return;
            }
            redirect('admin/products');
        }

        $payload = [];
        $raw = file_get_contents('php://input');
        if (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                $payload = $decoded;
            }
        }

        $addQty = $payload['addQty'] ?? ($_POST['addQty'] ?? null);
        if ($addQty === null || $addQty === '') {
            $addQty = $payload['quantity'] ?? ($_POST['quantity'] ?? null);
        }

        $newQty = $this->productModel->incrementStock($id, $addQty);
        if ($newQty === false) {
            $error = $this->productModel->getLastError() ?: 'Failed to update stock';
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => $error], 400);
                return;
            }
            flash('product_error', $error, 'alert alert-danger');
            redirect('admin/products');
        }

        if($this->isAjax()) {
            $this->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'id' => $id,
                'stock_quantity' => $newQty
            ]);
            return;
        }

        flash('product_success', 'Stock updated successfully');
        redirect('admin/products');
    }
    
    /**
     * 
     * @param int $id Product ID
     */
    public function delete($id = null) {
        // Check if admin
        if(!isAdmin()) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Unauthorized access'], 401);
                return;
            }
            redirect('user/login');
        }

        // Determine back URL (stay on Products list by default)
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $defaultBack = (defined('BASE_URL') ? BASE_URL : '/') . '?controller=product&action=adminIndex';
        $backUrl = (strpos($referer, 'controller=product') !== false && strpos($referer, 'action=adminIndex') !== false) ? $referer : $defaultBack;

        // Query-string routing compatibility: allow id via ?id=...
        if ($id === null || $id === '' || (is_numeric($id) && (int)$id <= 0)) {
            $id = $_GET['id'] ?? null;
        }
        $id = (int)$id;

        // Check if ID is provided
        if($id <= 0) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Product ID is required'], 400);
                return;
            }
            flash('product_error', 'Product ID is required', 'alert alert-danger');
            header('Location: ' . $backUrl);
            exit;
        }
        
        // Get product
        $product = $this->productModel->getById($id);
        
        // Check if product exists
        if(!$product) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Product not found'], 404);
                return;
            }
            flash('product_error', 'Product not found', 'alert alert-danger');
            header('Location: ' . $backUrl);
            exit;
        }
        
        // Check for POST or DELETE request (avoid undefined isDelete())
        if($this->isPost() || (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE')) {
            try {
                // Remove product image file from disk (ID and row kept for orders)
                if(!empty($product['image'])) {
                    $paths = [
                        PUBLIC_PATH . $product['image'],
                        ROOT_PATH . 'public/' . $product['image'],
                        $product['image']
                    ];
                    foreach ($paths as $path) {
                        if (file_exists($path)) {
                            @unlink($path);
                            break;
                        }
                    }
                }
                // Soft delete: keep product row/ID for order references, set inactive and clear image in DB
                if($this->productModel->softDelete($id)) {
                    if($this->isAjax()) {
                        $this->json([
                            'success' => true, 
                            'message' => 'Product removed (ID kept for orders)',
                            'id' => $id
                        ]);
                        return;
                    }
                    flash('product_success', 'Product removed. ID kept for order history.');
                } else {
                    throw new Exception($this->productModel->getLastError() ?: 'Failed to remove product');
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                error_log('Product deletion error: ' . $error);
                
                if($this->isAjax()) {
                    $this->json([
                        'success' => false, 
                        'message' => $error
                    ], 500);
                    return;
                }
                
                flash('product_error', $error, 'alert alert-danger');
            }
            
            if(!$this->isAjax()) {
                header('Location: ' . $backUrl);
                exit;
            }
        } else {
            // For GET requests, show confirmation page
            $this->view('admin/products/delete', [
                'product' => $product
            ]);
        }
    }
}
