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
     * 
     * @param string $slug Brand slug
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
     * 
     * @param int $id Brand ID
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
 * 
 * @param int $id Brand ID
 */
public function delete($id = null) {
    // Check if admin
    if(!isAdmin()) {
        if($this->isAjax()) {
            $this->json([
                'success' => false, 
                'message' => 'Unauthorized access. Please log in as admin.'
            ], 401);
            return;
        }
        redirect('user/login');
        return;
    }
        
    // Check if ID is provided
    if(!$id) {
        if($this->isAjax()) {
            $this->json([
                'success' => false, 
                'message' => 'Brand ID is required'
            ], 400);
            return;
        }
        flash('brand_error', 'Brand ID is required', 'alert alert-danger');
        redirect('admin/brands');
        return;
    }

    try {
        // Get brand
        $brand = $this->brandModel->getById($id);
        
        // Check if brand exists
        if(!$brand) {
            throw new Exception('Brand not found', 404);
        }
        
        // Check if this is a POST, DELETE, or POST with _method=DELETE
        $isDeleteRequest = $_SERVER['REQUEST_METHOD'] === 'DELETE' || 
                         ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && strtoupper($_POST['_method']) === 'DELETE');
        
        if(!$this->isPost() && !$isDeleteRequest) {
            // For non-AJAX GET requests, show confirmation page
            if($this->isAjax()) {
                $this->json([
                    'success' => false,
                    'message' => 'Invalid request method'
                ], 405);
                return;
            }
            $this->view('admin/brands/delete', [
                'brand' => $brand
            ]);
            return;
        }
        
        // First, check if brand has associated products
        $this->db->query("SELECT COUNT(*) as count FROM products WHERE brand_id = :id");
        $this->db->bind(':id', $id);
        $result = $this->db->single();

        if($result && $result['count'] > 0) {
            $message = 'Cannot delete brand because it is associated with ' . $result['count'] . ' product(s). Please remove or reassign these products first.';
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => $message], 400);
                return;
            }
            flash('brand_error', $message, 'alert alert-danger');
            redirect('admin/brands');
            return;
        }

        // Start transaction
        $this->db->beginTransaction();

        try {
            // First delete the brand's logo file if it exists
            if(!empty($brand['logo']) && file_exists(ROOT_PATH . $brand['logo'])) {
                @unlink(ROOT_PATH . $brand['logo']);
            }

            // Then delete the brand from database
            $this->db->query("DELETE FROM brands WHERE id = :id");
            $this->db->bind(':id', $id);
            $deleted = $this->db->execute();

            if(!$deleted) {
                throw new Exception('Failed to delete brand from database');
            }

            // Commit transaction
            $this->db->commit();

            // Log the deletion
            error_log("Brand deleted successfully - ID: " . $id);
            
            // Prepare success response
            $response = [
                'success' => true,
                'message' => 'Brand deleted successfully',
                'data' => [
                    'id' => $id
                ]
            ];
            
            // Handle AJAX response
            if($this->isAjax()) {
                $this->json($response);
                return;
            }
            
            // For non-AJAX requests, redirect to brands list
            flash('brand_success', $response['message']);
            redirect('admin/brands');
            return;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->db->rollBack();
            throw $e;
        }

    } catch (Exception $e) {
        $errorCode = $e->getCode() ?: 500;
        $errorMessage = $e->getMessage();
        
        error_log('Brand deletion error: ' . $errorMessage);
        
        // Prepare error response
        $response = [
            'success' => false,
            'message' => $errorMessage
        ];
        
        // Handle AJAX response
        if($this->isAjax()) {
            $this->json($response, $errorCode);
            return;
        }
        
        // For non-AJAX requests, show error message and redirect
        flash('brand_error', $errorMessage, 'alert alert-danger');
        redirect('admin/brands');
        return;
    }
}
}