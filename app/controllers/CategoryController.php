<?php
/**
 * Category Controller
 * Handles category management
 */
class CategoryController extends Controller {
    private $categoryModel;
    
    public function __construct() {
        $this->categoryModel = $this->model('Category');
    }
    
    /**
     * Display categories in customer view
     */
    public function index() {
        // Get category tree
        $categories = $this->categoryModel->getCategoryTree();
        
        // Load view
        $this->view('customer/categories/index', [
            'categories' => $categories
        ]);
    }
    
    /**
     * Display category details
     * 
     * @param int $id Category ID
     */
    public function show($id = null) {
        // Check if ID is provided
        if(!$id) {
            redirect('categories');
        }
        
        // Get category
        $category = $this->categoryModel->getCategoryWithParent($id);
        
        // Check if category exists
        if(!$category) {
            redirect('categories');
        }
        
        // Get subcategories
        $subcategories = $this->categoryModel->getSubcategories($id);
        
        // Load view
        $this->view('customer/categories/show', [
            'category' => $category,
            'subcategories' => $subcategories
        ]);
    }
    
    /**
     * Admin: List all categories
     */
    public function adminIndex() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Get page number
        $page = $this->get('page', 1);
        
        // Get categories with pagination
        $categories = $this->categoryModel->paginate($page, 20, 'name', 'ASC');
        
        // Load view
        $this->view('admin/categories/index', [
            'categories' => $categories
        ]);
    }
    
    /**
     * Admin: Create category form
     */
    public function create() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        // Get parent categories for dropdown
        $parentCategories = $this->categoryModel->getParentCategories();
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'name' => sanitize($this->post('name')),
                'description' => sanitize($this->post('description')),
                'parent_id' => $this->post('parent_id') ? $this->post('parent_id') : null,
                'status' => $this->post('status') ? 1 : 0
            ];
            
            // Handle file upload
            if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/categories/';
                
                // Create upload directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generate unique filename
                $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $fileExt;
                $targetPath = $uploadDir . $fileName;
                
                // Move uploaded file
                if(move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $data['image'] = $targetPath;
                } else {
                    $errors['image'] = 'Failed to upload image. Please try again.';
                }
            }
            
            // Initialize errors array
            $validationErrors = $this->validate($data, [
                'name' => 'required|max:255'
            ]);
            
            // Merge file upload errors if any
            $errors = isset($errors) ? array_merge($errors, $validationErrors) : $validationErrors;
            
            // Make sure there are no errors
            if(empty($errors)) {
                try {
                    // Create category
                    if($this->categoryModel->create($data)) {
                        flash('category_success', 'Category created successfully!', 'alert alert-success');
                        // Redirect back to create form to add another category
                        $this->view('admin/categories/create', [
                            'data' => [
                                'name' => '',
                                'description' => '',
                                'parent_id' => null,
                                'status' => 1
                            ],
                            'parentCategories' => $parentCategories,
                            'errors' => []
                        ]);
                        return;
                    } else {
                        throw new Exception('Failed to create category: ' . $this->categoryModel->getLastError());
                    }
                } catch (Exception $e) {
                    // If there was an error, delete the uploaded file if it exists
                    if(isset($data['image']) && file_exists($data['image'])) {
                        @unlink($data['image']);
                    }
                    $errors['db_error'] = $e->getMessage();
                    error_log('Category creation error: ' . $e->getMessage());
                }
            }
            
            // Load view with errors and existing data
            $this->view('admin/categories/create', [
                'errors' => $errors,
                'data' => $data,
                'parentCategories' => $parentCategories
            ]);
        } else {
            // Init data
            $data = [
                'name' => '',
                'description' => '',
                'parent_id' => null,
                'status' => 1
            ];
            
            // Load view
            $this->view('admin/categories/create', [
                'data' => $data,
                'parentCategories' => $parentCategories,
                'errors' => []
            ]);
        }
    }
    
    /**
     * Admin: Edit category form
     * 
     * @param int $id Category ID
     */
    public function edit($id = null) {
        // Check if admin
        if(!isAdmin()) {
            flash('category_error', 'Unauthorized access', 'alert alert-danger');
            redirect('user/login');
        }
        
        // Check if ID is provided
        if(!$id) {
            flash('category_error', 'Category ID is required', 'alert alert-danger');
            redirect('category/adminIndex');
        }
        
        // Get category
        $category = $this->categoryModel->getById($id);
        
        // Debug: Log the category data
        error_log('Category data: ' . print_r($category, true));
        
        // Check if category exists
        if(!$category) {
            $errorMsg = 'Category with ID ' . $id . ' not found';
            error_log($errorMsg);
            flash('category_error', $errorMsg, 'alert alert-danger');
            redirect('category/adminIndex');
        }
        
        // Ensure category is an array (some database layers return objects)
        if (is_object($category)) {
            $category = (array)$category;
        }
        
        // Get parent categories for dropdown
        $parentCategories = $this->categoryModel->getParentCategories();
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'name' => sanitize($this->post('name')),
                'description' => sanitize($this->post('description')),
                'parent_id' => $this->post('parent_id') ? $this->post('parent_id') : null,
                'status' => $this->post('status') ? 1 : 0,
                'image' => $category['image'] // Keep existing image by default
            ];
            
            // Handle file upload if a new image is provided
            if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/categories/';
                
                // Create upload directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generate unique filename
                $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $fileExt;
                $targetPath = $uploadDir . $fileName;
                
                // Move uploaded file
                if(move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    // If there was a previous image, delete it
                    if(!empty($category['image']) && file_exists($category['image'])) {
                        unlink($category['image']);
                    }
                    $data['image'] = $targetPath;
                } else {
                    $errors['image'] = 'Failed to upload image. Please try again.';
                }
            }
            
            // Validate data
            $validationErrors = $this->validate($data, [
                'name' => 'required|max:255'
            ]);
            
            // Merge file upload errors if any
            $errors = isset($errors) ? array_merge($errors, $validationErrors) : $validationErrors;
            
            // Prevent category from being its own parent
            if($data['parent_id'] == $id) {
                $errors['parent_id'] = 'A category cannot be its own parent';
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Update category
                if($this->categoryModel->update($id, $data)) {
                    flash('category_success', 'Category updated successfully');
                    redirect('category/adminIndex');
                } else {
                    $errors['db_error'] = 'Failed to update category: ' . $this->categoryModel->getLastError();
                }
            }
            
            // Load view with errors
            $this->view('admin/categories/edit', [
                'errors' => $errors,
                'category' => array_merge($category, $data),
                'parentCategories' => $parentCategories
            ]);
        } else {
            // Load view
            $this->view('admin/categories/edit', [
                'category' => $category,
                'parentCategories' => $parentCategories,
                'errors' => []
            ]);
        }
    }
    
    /**
     * Admin: Delete category
     * 
     * @param int $id Category ID
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
        
        // Check if ID is provided
        if(!$id) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Category ID is required'], 400);
                return;
            }
            flash('category_error', 'Category ID is required', 'alert alert-danger');
            redirect('category/adminIndex');
        }
        
        // Get category
        $category = $this->categoryModel->getById($id);
        
        // Check if category exists
        if(!$category) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Category not found'], 404);
                return;
            }
            flash('category_error', 'Category not found', 'alert alert-danger');
            redirect('category/adminIndex');
        }
        
        // Check if category has products
        if($this->categoryModel->hasProducts($id)) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Cannot delete category with associated products'], 400);
                return;
            }
            flash('category_error', 'Cannot delete category with associated products', 'alert alert-danger');
            redirect('category/adminIndex');
        }
        
        // Check if category has subcategories
        if($this->categoryModel->hasSubcategories($id)) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Cannot delete category with subcategories'], 400);
                return;
            }
            flash('category_error', 'Cannot delete category with subcategories', 'alert alert-danger');
            redirect('category/adminIndex');
        }
        
        // Check for POST
        if($this->isPost() || $this->isDelete()) {
            // Delete category image if exists
            if(!empty($category['image']) && file_exists(PUBLIC_PATH . $category['image'])) {
                @unlink(PUBLIC_PATH . $category['image']);
            }
            
            // Delete category
            if($this->categoryModel->delete($id)) {
                if($this->isAjax()) {
                    $this->json([
                        'success' => true, 
                        'message' => 'Category deleted successfully',
                        'id' => $id
                    ]);
                    return;
                }
                flash('category_success', 'Category deleted successfully');
            } else {
                $error = 'Failed to delete category: ' . $this->categoryModel->getLastError();
                if($this->isAjax()) {
                    $this->json(['success' => false, 'message' => $error], 500);
                    return;
                }
                flash('category_error', $error, 'alert alert-danger');
            }
            
            // For non-AJAX requests, redirect
            if(!$this->isAjax()) {
                $page = $this->get('page', 1);
                redirect('category/adminIndex' . ($page > 1 ? '?page=' . $page : ''));
            }
        } else {
            // Load view
            $this->view('admin/categories/delete', [
                'category' => $category
            ]);
        }
    }
}
