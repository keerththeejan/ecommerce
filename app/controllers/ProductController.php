<?php
/**
 * Product Controller
 * Handles product listing and details
 */
class ProductController extends Controller {
    private $productModel;
    private $categoryModel;
    private $supplierModel;
    
    public function __construct() {
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
        $this->supplierModel = $this->model('Supplier');
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
        // Check if category ID is provided
        if(!$categoryId) {
            $categoryId = $this->get('param');
            
            if(!$categoryId) {
                redirect('product/index');
                return;
            }
        }
        
        // Get category
        $category = $this->categoryModel->getById($categoryId);
        
        // Check if category exists
        if(!$category) {
            redirect('product/index');
            return;
        }
        
        // Get products
        $products = $this->productModel->getProductsByCategory($categoryId);
        
        // Get all categories for sidebar
        $categories = $this->categoryModel->getActiveCategories();
        
        // Load view
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
        // Get products on sale
        $products = $this->productModel->getProductsOnSale();
        
        // Get categories for sidebar
        $categories = $this->categoryModel->getActiveCategories();
        
        // Load view
        $this->view('customer/products/sale', [
            'products' => $products,
            'categories' => $categories
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
        
        // Get page number
        $page = $this->get('page', 1);
        
        // Get products with pagination
        $products = $this->productModel->paginate($page, 20, 'id', 'DESC');
        
        // Normalize rows to associative arrays to avoid blank fields in view
        if (isset($products['data']) && is_array($products['data'])) {
            foreach ($products['data'] as $idx => $row) {
                if (is_object($row)) {
                    $products['data'][$idx] = (array)$row;
                }
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
            'supplierMap' => $supplierMap
        ]);
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
        $categories = $this->categoryModel->getActiveCategories();
        // Get suppliers for dropdown
        $suppliers = method_exists($this->supplierModel, 'getAllSuppliers') ? $this->supplierModel->getAllSuppliers() : [];
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'name' => sanitize($this->post('name')),
                'description' => sanitize($this->post('description')),
                'price' => $this->post('price'),
                'sale_price' => $this->post('sale_price') ?: null,
                'price2' => $this->post('price2') ?: $this->post('price'),
                'price3' => $this->post('price3') ?: $this->post('price'),
                'stock_quantity' => $this->post('stock_quantity'),
                'category_id' => $this->post('category_id'),
                'status' => $this->post('status'),
                'expiry_date' => $this->post('expiry_date') ?: null,
                'supplier' => sanitize($this->post('supplier')),
                'batch_number' => sanitize($this->post('batch_number'))
            ];
            
            // Handle SKU - generate if empty or check for uniqueness
            $sku = trim($this->post('sku'));
            if (empty($sku)) {
                // Generate a unique SKU based on product name and timestamp
                $baseSku = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', substr($data['name'], 0, 10)));
                $sku = $baseSku . time() % 10000; // Add some randomness with timestamp
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
                $fileName = time() . '_' . $image['name'];
                $targetPath = PUBLIC_PATH . 'uploads/products/' . $fileName;
                
                // Create directory if it doesn't exist
                if(!file_exists(PUBLIC_PATH . 'uploads/products/')) {
                    mkdir(PUBLIC_PATH . 'uploads/products/', 0777, true);
                }
                
                if(move_uploaded_file($image['tmp_name'], $targetPath)) {
                    $data['image'] = 'uploads/products/' . $fileName;
                }
            }
            
            // Validate data
            $validationRules = [
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'stock_quantity' => 'required|numeric|min:0',
                'category_id' => 'required|numeric',
                'price2' => 'nullable|numeric',
                'price3' => 'nullable|numeric',
                'sale_price' => 'nullable|numeric',
                'supplier' => 'nullable|max:255',
                'batch_number' => 'nullable|max:100',
                'expiry_date' => 'nullable'
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
                        
                        // Set success message
                        $success = 'Product created successfully';
                        
                        // If it's an AJAX request, return success
                        if($this->isAjax()) {
                            $this->json([
                                'success' => true, 
                                'message' => $success,
                                'productId' => $productId
                            ]);
                            return;
                        }
                        
                        // For regular form submission, stay on the same page with success message
                        $data = [
                            'name' => '',
                            'description' => '',
                            'price' => '',
                            'sale_price' => '',
                            'stock_quantity' => '',
                            'sku' => '',
                            'category_id' => '',
                            'status' => 'active',
                            'expiry_date' => '',
                            'supplier' => '',
                            'batch_number' => ''
                        ];
                        
                        $this->view('admin/products/create', [
                            'data' => $data,
                            'categories' => $categories,
                            'success' => $success,
                            'errors' => []
                        ]);
                        return;
                    } else {
                        throw new Exception('Failed to create product');
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                    error_log('Product creation error: ' . $error);
                    
                    if($this->isAjax()) {
                        $this->json([
                            'success' => false, 
                            'message' => $error
                        ], 500);
                        return;
                    }
                    
                    $errors['db_error'] = $error;
                }
            }
            
            // If we got here, there were errors or it's not an AJAX request
            $this->view('admin/products/create', [
                'errors' => $errors,
                'data' => $data,
                'categories' => $categories,
                'suppliers' => $suppliers
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
                'status' => 'active',
                'expiry_date' => '',
                'supplier' => '',
                'batch_number' => ''
            ];
            
            // Load view
            $this->view('admin/products/create', [
                'data' => $data,
                'categories' => $categories,
                'suppliers' => $suppliers,
                'errors' => []
            ]);
        }
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
        $categories = $this->categoryModel->getActiveCategories();
        // Get suppliers for dropdown
        $suppliers = method_exists($this->supplierModel, 'getAllSuppliers') ? $this->supplierModel->getAllSuppliers() : [];
        
        // Check for POST request
        if($this->isPost()) {
            try {
                // Process form
                $data = [
                    'name' => sanitize($this->post('name')),
                    'description' => sanitize($this->post('description')),
                    'price' => $this->post('price'),
                    'sale_price' => $this->post('sale_price') ?: null,
                    'price2' => $this->post('price2') ?: $this->post('price'),
                    'price3' => $this->post('price3') ?: $this->post('price'),
                    'stock_quantity' => $this->post('stock_quantity'),
                    'sku' => sanitize($this->post('sku')),
                    'category_id' => $this->post('category_id'),
                    'status' => $this->post('status') ?: 'active',
                    'expiry_date' => $this->post('expiry_date') ?: null,
                    'supplier' => sanitize($this->post('supplier')),
                    'batch_number' => sanitize($this->post('batch_number'))
                ];
                
                // Validate data before processing
                $errors = $this->validate($data, [
                    'name' => 'required|max:255',
                    'price' => 'required|numeric',
                    'stock_quantity' => 'required|numeric',
                    'sku' => 'required|max:50',
                    'category_id' => 'required',
                    'status' => 'required|in:active,inactive',
                    'supplier' => 'nullable|max:255',
                    'batch_number' => 'nullable|max:100',
                    'expiry_date' => 'nullable'
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
                
                // Update product in database
                if(!$this->productModel->update($id, $data)) {
                    $lastError = method_exists($this->productModel, 'getLastError') ? $this->productModel->getLastError() : '';
                    $detail = $lastError ? (': ' . $lastError) : '';
                    throw new Exception('Failed to update product in database' . $detail);
                }
                
                // Get updated product data
                $updatedProduct = $this->productModel->getById($id);
                
                // Prepare response using updated product data
                $payload = is_array($updatedProduct) ? $updatedProduct : $data;
                $response = [
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'data' => [
                        'id' => $id,
                        'name' => $payload['name'] ?? $data['name'] ?? $product['name'],
                        'description' => $payload['description'] ?? $data['description'] ?? $product['description'] ?? '',
                        'price' => isset($payload['price']) ? $payload['price'] : ($data['price'] ?? $product['price']),
                        'sale_price' => $payload['sale_price'] ?? ($data['sale_price'] ?? null),
                        'price2' => $payload['price2'] ?? ($data['price2'] ?? null),
                        'price3' => $payload['price3'] ?? ($data['price3'] ?? null),
                        'stock_quantity' => isset($payload['stock_quantity']) ? $payload['stock_quantity'] : ($data['stock_quantity'] ?? $product['stock_quantity']),
                        'image' => $payload['image'] ?? ($data['image'] ?? ($product['image'] ?? null)),
                        'sku' => $payload['sku'] ?? ($data['sku'] ?? $product['sku'] ?? ''),
                        'category_id' => $payload['category_id'] ?? ($data['category_id'] ?? $product['category_id'] ?? null),
                        'status' => $payload['status'] ?? ($data['status'] ?? $product['status'] ?? 'active'),
                        'expiry_date' => $payload['expiry_date'] ?? ($data['expiry_date'] ?? null),
                        'supplier' => $payload['supplier'] ?? ($data['supplier'] ?? null),
                        'batch_number' => $payload['batch_number'] ?? ($data['batch_number'] ?? null),
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
                    'suppliers' => $suppliers
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
                'errors' => []
            ]);
            return;
        }
    }
    
    /**
     * 
     * @param int $id Product ID
     */
    public function delete($id) {
        // Check if admin
        if(!isAdmin()) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Unauthorized access'], 401);
                return;
            }
            redirect('user/login');
        }
        
        // Check if ID is provided
        if(!$id) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Product ID is required'], 400);
                return;
            }
            flash('product_error', 'Product ID is required', 'alert alert-danger');
            redirect('admin/products');
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
        
        // Check for POST or DELETE request
        if($this->isPost() || $this->isDelete()) {
            try {
                // Delete product
                if($this->productModel->delete($id)) {
                    // Delete product image
                    if(!empty($product['image']) && file_exists(PUBLIC_PATH . $product['image'])) {
                        @unlink(PUBLIC_PATH . $product['image']);
                    }
                    
                    if($this->isAjax()) {
                        $this->json([
                            'success' => true, 
                            'message' => 'Product deleted successfully',
                            'id' => $id
                        ]);
                        return;
                    }
                    
                    flash('product_success', 'Product deleted successfully');
                } else {
                    throw new Exception('Failed to delete product');
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
                redirect('admin/products');
            }
        } else {
            // For GET requests, show confirmation page
            $this->view('admin/products/delete', [
                'product' => $product
            ]);
        }
    }
}
