<?php
/**
 * Product Controller
 * Handles product listing and details
 */
class ProductController extends Controller {
    private $productModel;
    private $categoryModel;
    
    public function __construct() {
        $this->productModel = $this->model('Product');
        $this->categoryModel = $this->model('Category');
    }
    
    /**
     * Display all products
     */
    public function index() {
        // Get page number
        $page = $this->get('page', 1);
        
        // Get products that are on sale with pagination
        $products = $this->productModel->getProductsOnSale($page, 12);
        
        // Get categories for filter
        $categories = $this->categoryModel->getActiveCategories();
        
        // Load view
        $this->view('customer/products/index', [
            'products' => $products,
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
        
        // Load view
        $this->view('admin/products/index', [
            'products' => $products
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
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'name' => sanitize($this->post('name')),
                'description' => sanitize($this->post('description')),
                'price' => $this->post('price'),
                'sale_price' => $this->post('sale_price'),
                'stock_quantity' => $this->post('stock_quantity'),
                'sku' => sanitize($this->post('sku')),
                'category_id' => $this->post('category_id'),
                'status' => $this->post('status')
            ];
            
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
            $errors = $this->validate($data, [
                'name' => 'required|max:255',
                'price' => 'required|numeric',
                'stock_quantity' => 'required|numeric',
                'sku' => 'required|max:50',
                'category_id' => 'required'
            ]);
            
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
                        
                        flash('product_success', 'Product created successfully', 'alert alert-success');
                        
                        // Reset form data for new entry
                        $data = [
                            'name' => '',
                            'description' => '',
                            'price' => '',
                            'sale_price' => '',
                            'stock_quantity' => '',
                            'sku' => '',
                            'category_id' => '',
                            'status' => 'active',
                            'image' => ''
                        ];
                        
                        // Clear any file inputs
                        echo "<script>if(window.jQuery) { $('input[type=file]').val(''); }</script>";
                        
                        // Reload the view with success message and empty form
                        $this->view('admin/products/create', [
                            'data' => $data,
                            'categories' => $categories,
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
                'categories' => $categories
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
                'status' => 'active'
            ];
            
            // Load view
            $this->view('admin/products/create', [
                'data' => $data,
                'categories' => $categories,
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
        
        // Check for POST request
        if($this->isPost()) {
            try {
                // Process form
                $data = [
                    'name' => sanitize($this->post('name')),
                    'description' => sanitize($this->post('description')),
                    'price' => $this->post('price'),
                    'sale_price' => $this->post('sale_price') ?: null,
                    'stock_quantity' => $this->post('stock_quantity'),
                    'sku' => sanitize($this->post('sku')),
                    'category_id' => $this->post('category_id'),
                    'status' => $this->post('status') ?: 'active'
                ];
                
                // Validate data before processing
                $errors = $this->validate($data, [
                    'name' => 'required|max:255',
                    'price' => 'required|numeric',
                    'stock_quantity' => 'required|numeric',
                    'sku' => 'required|max:50',
                    'category_id' => 'required',
                    'status' => 'required|in:active,inactive'
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
                    throw new Exception('Failed to update product in database');
                }
                
                // Get updated product data
                $updatedProduct = $this->productModel->getById($id);
                
                // Prepare response
                $response = [
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'data' => [
                        'id' => $id,
                        'name' => $data['name'],
                        'price' => number_format($data['price'], 2),
                        'sale_price' => $data['sale_price'] ? number_format($data['sale_price'], 2) : null,
                        'stock_quantity' => (int)$data['stock_quantity'],
                        'image' => $data['image'] ?? $product['image'] ?? null,
                        'status' => $data['status']
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
                    'categories' => $categories
                ]);
                return;
            }
            
            // If not AJAX, redirect to products list
            if(!$this->isAjax()) {
                redirect('admin/products');
            }
        } else {
            // Load view
            $this->view('admin/products/edit', [
                'product' => $product,
                'categories' => $categories,
                'errors' => []
            ]);
        }
    }
    
    /**
     * Admin: Delete product
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
