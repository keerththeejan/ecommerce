<?php
/**
 * Brand Controller
 * Handles brand management
 */
class BrandController extends Controller {
    private $brandModel;
    private $productModel;
    
    public function __construct() {
        $this->brandModel = $this->model('Brand');
        $this->productModel = $this->model('Product');
    }
    
    /**
     * Display brands for customers
     */
    public function index() {
        // Get brands
        $brands = $this->brandModel->getActiveBrands();
        
        // Load view
        $this->view('customer/brands/index', [
            'brands' => $brands
        ]);
    }
    
    /**
     * Display products by brand for customers
     * * @param string $slug Brand slug
     */
    public function show($slug = null) {
        // Check if slug is provided
        if(!$slug) {
            redirect('brands');
        }
        
        // Get brand
        $brand = $this->brandModel->getBySlug($slug);
        
        // Check if brand exists and is active
        if(!$brand || $brand['status'] != 'active') {
            flash('brand_error', 'Brand not found', 'alert alert-danger');
            redirect('brands');
        }
        
        // Get page number
        $page = $this->get('page', 1);
        
        // Get products by brand
        $products = $this->brandModel->getProductsByBrand($brand['id'], $page, 12);
        
        // Load view
        $this->view('customer/brands/show', [
            'brand' => $brand,
            'products' => $products
        ]);
    }
    
    /**
     * Admin: List all brands
     */
    public function adminIndex() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Get page number
        $page = $this->get('page', 1);
        
        // Get search term
        $search = $this->get('search', '');
        
        // Get brands with pagination
        if(!empty($search)) {
            $brands = $this->brandModel->search($search, $page, 10);
        } else {
            $brands = $this->brandModel->paginate($page, 10, 'name', 'ASC');
        }
        
        // Load view
        $this->view('admin/brands/index', [
            'brands' => $brands,
            'search' => $search
        ]);
    }
    
    /**
     * Admin: Create brand
     */
    public function create() {
        // Check if admin
        if(!isAdmin()) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Unauthorized access'], 401);
                return;
            }
            redirect('user/login');
        }
        
        // Check for POST
        if($this->isPost()) {
            try {
                // Process form
                $data = [
                    'name' => sanitize($this->post('name')),
                    'description' => sanitize($this->post('description')),
                    'status' => sanitize($this->post('status')) ?: 'active',
                    'logo' => ''
                ];
                
                // Generate slug
                $data['slug'] = $this->brandModel->generateSlug($data['name']);
                
                // Validate data
                $errors = $this->validate($data, [
                    'name' => 'required',
                    'status' => 'required|in:active,inactive'
                ]);
                
                // Handle logo upload if provided
                $logoPath = null;
                if(!empty($_FILES['logo']['name'])) {
                    $logoUpload = uploadImage($_FILES['logo'], 'brands');
                    
                    if($logoUpload['success']) {
                        // Store the relative path in the database
                        $logoPath = $logoUpload['path'];
                        $data['logo'] = $logoPath;
                    } else {
                        $errors['logo'] = $logoUpload['error'];
                    }
                }
                
                // If there are validation errors
                if(!empty($errors)) {
                    throw new Exception(implode("\n", $errors));
                }
                
                // Create brand
                $brandId = $this->brandModel->create($data);
                
                if(!$brandId) {
                    throw new Exception('Failed to create brand in database');
                }
                
                // Get the created brand
                $brand = $this->brandModel->getById($brandId);
                
                // Prepare response
                $response = [
                    'success' => true,
                    'message' => 'Brand created successfully',
                    'data' => [
                        'id' => $brandId,
                        'name' => $data['name'],
                        'slug' => $data['slug'],
                        'logo' => $logoPath,
                        'status' => $data['status']
                    ]
                ];
                
                if($this->isAjax()) {
                    $this->json($response);
                    return;
                }
                
                flash('brand_success', $response['message']);
                
            } catch (Exception $e) {
                $errorMsg = $e->getMessage();
                error_log('Brand creation error: ' . $errorMsg);
                
                if($this->isAjax()) {
                    $this->json([
                        'success' => false,
                        'message' => $errorMsg
                    ], 400);
                    return;
                }
                
                // For non-AJAX, load the view with errors
                $this->view('admin/brands/create', [
                    'data' => $data ?? [
                        'name' => '',
                        'description' => '',
                        'status' => 'active'
                    ],
                    'errors' => $errors ?? []
                ]);
                return;
            }
            
            // If not AJAX, redirect to brands list
            if(!$this->isAjax()) {
                redirect('admin/brands');
            }
        } else {
            // For GET requests, load the create form
            $data = [
                'name' => '',
                'description' => '',
                'status' => 'active'
            ];
            
            $this->view('admin/brands/create', [
                'data' => $data,
                'errors' => []
            ]);
        }
    }

    /**
     * Admin: Edit brand
     * * @param int $id Brand ID
     */
    public function edit($id = null) {
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
            $this->json(['success' => false, 'message' => 'Brand ID is required'], 400);
            return;
        }
        flash('brand_error', 'Brand ID is required', 'alert alert-danger');
        redirect('admin/brands');
        return;
    }

    // Get brand
    $brand = $this->brandModel->getById($id);

    // Check if brand exists
    if(!$brand) {
        if($this->isAjax()) {
            $this->json(['success' => false, 'message' => 'Brand not found'], 404);
            return;
        }
        flash('brand_error', 'Brand not found', 'alert alert-danger');
        redirect('admin/brands');
        return;
    }
    
    // Handle POST request
    if($this->isPost()) {
        try {
            // Process form
            $data = [
                'name' => sanitize($this->post('name')),
                'description' => sanitize($this->post('description')),
                'status' => sanitize($this->post('status')) ?: 'active',
                'logo' => $brand['logo']
            ];
            
            // Generate slug
            $data['slug'] = $this->brandModel->generateSlug($data['name'], $id);
            
            // Validate data
            $errors = $this->validate($data, [
                'name' => 'required',
                'status' => 'required|in:active,inactive'
            ]);
            
            // Upload logo if provided
            $logoPath = null;
            if(!empty($_FILES['logo']['name'])) {
                $logoUpload = uploadImage($_FILES['logo'], 'brands');
                
                if($logoUpload['success']) {
                    // Ensure the path is relative to the public directory
                    $logoPath = str_replace(BASE_URL, '/', $logoUpload['path']);
                    $data['logo'] = $logoPath;
                } else {
                    $errors['logo'] = $logoUpload['error'];
                }
            }
            
            // If there are validation errors
            if(!empty($errors)) {
                throw new Exception(implode("\n", $errors));
            }
            
            // Update brand
            if(!$this->brandModel->update($id, $data)) {
                throw new Exception('Failed to update brand in database');
            }
            
            // Update logo if new one was uploaded
            if($logoPath) {
                // Delete old logo if exists
                if(!empty($brand['logo']) && file_exists(PUBLIC_PATH . $brand['logo'])) {
                    @unlink(PUBLIC_PATH . $brand['logo']);
                }
                
                // Update logo in database
                $this->brandModel->updateLogo($id, $logoPath);
            }
            
            // Prepare response
            $response = [
                'success' => true,
                'message' => 'Brand updated successfully',
                'data' => [
                    'id' => $id,
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'logo' => $logoPath,
                    'status' => $data['status']
                ]
            ];
            
            if($this->isAjax()) {
                $this->json($response);
                return;
            }
            
            flash('brand_success', $response['message']);
            
        } catch (Exception $e) {
            $errorMsg = $e->getMessage();
            error_log('Brand update error: ' . $errorMsg);
            
            if($this->isAjax()) {
                $this->json([
                    'success' => false,
                    'message' => $errorMsg
                ], 400);
                return;
            }
            
            // For non-AJAX, load the view with errors
            $this->view('admin/brands/edit', [
                'brand' => $brand,
                'data' => $data ?? [
                    'id' => $brand['id'],
                    'name' => $brand['name'],
                    'description' => $brand['description'],
                    'status' => $brand['status']
                ],
                'errors' => $errors ?? []
            ]);
            return;
        }
        
        // If not AJAX, redirect to brands list
        if(!$this->isAjax()) {
            redirect('admin/brands');
            return;
        }
        
        // If we get here, it's an AJAX request and we've already sent the response
        return;
    }
        
    // For GET requests, load the edit form
    $data = [
        'id' => $brand['id'] ?? null,
        'name' => $brand['name'] ?? '',
        'description' => $brand['description'] ?? '',
        'status' => $brand['status'] ?? 'active'
    ];
        
    $this->view('admin/brands/edit', [
        'brand' => $brand,
        'data' => $data,
        'errors' => []
    ]);
}

/**
 * Admin: Delete brand
 * * @param int $id Brand ID
 */
public function delete($id = null) {
    try {
        // Check if admin
        if (!isAdmin()) {
            throw new Exception('Unauthorized access. Please log in as admin.', 401);
        }

        // Check if ID is provided and valid
        if (!$id || !is_numeric($id)) {
            throw new Exception('Invalid brand ID provided.', 400);
        }
        
        // Get brand first to check existence and get logo path
        $brand = $this->brandModel->getById($id);
        
        // Check if brand exists
        if (!$brand) {
            throw new Exception('Brand not found', 404);
        }
        
        // If it's a GET request, show confirmation page
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if ($this->isAjax()) {
                throw new Exception('Invalid request method for this endpoint. Use DELETE or POST with _method=DELETE.', 405);
            }
            $this->view('admin/brands/delete', ['brand' => $brand]);
            return;
        }
        
        // For POST/DELETE requests, verify CSRF token
        if ($this->isPost() || $this->isDeleteMethod()) {
            $requestToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf_token'] ?? '');
            $sessionToken = $_SESSION['csrf_token'] ?? '';
            
            if (empty($requestToken) || $requestToken !== $sessionToken) {
                throw new Exception('Invalid or missing CSRF token', 403);
            }
        }
        
        // Check for associated products
        $this->db->query("SELECT COUNT(*) as count FROM products WHERE brand_id = :id");
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        if ($result && $result['count'] > 0) {
            $message = sprintf(
                'Cannot delete brand because it is associated with %d product(s). ' . 
                'Please remove or reassign these products first.', 
                $result['count']
            );
            throw new Exception($message, 400);
        }

        // Start transaction
        $this->db->beginTransaction();

        // Delete logo file if it exists
        if (!empty($brand['logo'])) {
            $this->deleteBrandLogo($brand['logo']);
        }

        // Delete the brand from database
        $deleted = $this->brandModel->delete($id);
        if (!$deleted) {
            throw new Exception('Failed to delete brand from database');
        }

        // Commit transaction
        $this->db->commit();
        
        // Log the successful deletion
        error_log(sprintf("Brand deleted successfully - ID: %d, Name: %s", $id, $brand['name']));
        
        // Clear cache
        $this->clearBrandCache($id);
        
        // Prepare and handle success response
        $response = [
            'success' => true,
            'message' => 'Brand deleted successfully',
            'data' => ['id' => $id]
        ];
        
        $this->handleResponse($response, 200);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        }
        
        $errorCode = $e->getCode() ?: 500;
        $response = [
            'success' => false,
            'message' => $e->getMessage(),
            'data' => null
        ];
        error_log(sprintf('Brand deletion error (ID: %s): %s', $id ?? 'unknown', $e->getMessage()));
        
        $this->handleResponse($response, $errorCode);
    }
}

/**
 * Handle response for both AJAX and regular requests
 */
private function handleResponse($response, $statusCode = 200) {
    if ($this->isAjax()) {
        header('Content-Type: application/json', true, $statusCode);
        echo json_encode($response);
        exit;
    }
    
    // For non-AJAX requests
    if ($response['success']) {
        flash('brand_success', $response['message']);
    } else {
        flash('brand_error', $response['message'], 'alert alert-danger');
    }
    
    redirect('admin/brands');
}

/**
 * Check if the request method is DELETE
 */
private function isDeleteMethod() {
    return $_SERVER['REQUEST_METHOD'] === 'DELETE' || 
          ($_SERVER['REQUEST_METHOD'] === 'POST' && 
           isset($_POST['_method']) && 
           strtoupper($_POST['_method']) === 'DELETE');
}

/**
 * Delete brand logo and its variations
 */
private function deleteBrandLogo($logoPath) {
    if (empty($logoPath)) {
        return;
    }

    // Normalize the path
    $logoPath = ltrim($logoPath, '/\\');
    $fullPath = rtrim(ROOT_PATH, '/\\') . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $logoPath);
    
    try {
        // Delete main logo file
        if (file_exists($fullPath) && is_file($fullPath) && is_writable($fullPath)) {
            if (!@unlink($fullPath)) {
                error_log("Failed to delete logo file: " . $fullPath);
            }
        }
        
        // Delete any resized versions
        $pathInfo = pathinfo($fullPath);
        $pattern = sprintf(
            '%s%s%s_*%s',
            $pathInfo['dirname'],
            DIRECTORY_SEPARATOR,
            $pathInfo['filename'],
            isset($pathInfo['extension']) ? '.' . $pathInfo['extension'] : ''
        );
        
        $matchingFiles = glob($pattern);
        if ($matchingFiles === false) {
            error_log("Failed to find matching files for pattern: " . $pattern);
            return;
        }
        
        foreach ($matchingFiles as $file) {
            if (is_file($file) && is_writable($file)) {
                if (!@unlink($file)) {
                    error_log("Failed to delete resized logo file: " . $file);
                }
            }
        }
    } catch (Exception $e) {
        error_log("Error deleting brand logo: " . $e->getMessage());
    }
}

/**
 * Clear cache related to the brand
 */
private function clearBrandCache($brandId) {
    try {
        // Clear opcache if enabled
        if (function_exists('opcache_invalidate')) {
            $modelPath = rtrim(ROOT_PATH, '/\\') . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'Brand.php';
            if (file_exists($modelPath)) {
                opcache_invalidate($modelPath, true);
            }
        }
        
        // Clear any cached queries if the method exists
        if (method_exists($this->db, 'clearCache')) {
            $this->db->clearCache('brand_' . $brandId);
        }
        
        // Clear APCu cache if available
        if (function_exists('apcu_clear_cache')) {
            apcu_clear_cache();
        }
    } catch (Exception $e) {
        error_log("Error clearing brand cache: " . $e->getMessage());
    }
}
}