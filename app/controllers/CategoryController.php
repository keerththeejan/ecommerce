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
     * @param int|string $id Category ID (from param= in URL)
     */
    public function show($id = null) {
        // Check if ID is provided (from ?param= or router)
        if ($id === null || $id === '') {
            redirect('categories');
        }
        $categoryId = (int) $id;
        if ($categoryId <= 0) {
            redirect('categories');
        }

        // Get category
        $category = $this->categoryModel->getCategoryWithParent($categoryId);
        if (!$category) {
            redirect('categories');
        }
        // Ensure array for view
        if (is_object($category)) {
            $category = (array) $category;
        }

        // Get subcategories
        $subcategories = $this->categoryModel->getSubcategories($categoryId);

        // Build list of category IDs: this category + all subcategories (filter products by category only)
        $subcategoryIds = [];
        foreach ($subcategories as $sub) {
            $subId = is_array($sub) ? ($sub['id'] ?? null) : (isset($sub->id) ? $sub->id : null);
            if ($subId !== null && $subId !== '') {
                $subcategoryIds[] = (int) $subId;
            }
        }
        $categoryIds = array_merge([$categoryId], $subcategoryIds);

        $productModel = $this->model('Product');
        $products = $productModel->getProductsByCategoryIds($categoryIds);

        // Load view
        $this->view('customer/categories/show', [
            'category' => $category,
            'subcategories' => $subcategories,
            'products' => $products
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
        
        // Get page number, per-page filter, and search
        $page = (int) $this->get('page', 1);
        $perPageParam = $this->get('per_page', '20');
        $search = trim((string) $this->get('search', ''));
        $allowedPerPage = ['20', '50', '100', 'all'];
        $perPage = in_array($perPageParam, $allowedPerPage, true) ? $perPageParam : '20';
        $perPageNum = ($perPage === 'all') ? 9999 : (int) $perPage;
        if ($perPageNum < 1) {
            $perPageNum = 20;
        }
        
        // Get categories (ID ascending: 1, 2, 3...), with optional search
        $categories = $this->categoryModel->paginate($page, $perPageNum, 'id', 'ASC', $search ?: null);
        $categories['per_page_param'] = $perPage;
        $categories['search'] = $search;
        
        // Ensure tax info is present for listing (fallback enrichment)
        if (!empty($categories['data'])) {
            try {
                $taxModel = new TaxModel();
                $rates = $taxModel->getTaxRates(false);
                // Build a quick lookup map id => [name, rate]
                $rateMap = [];
                foreach ($rates as $r) {
                    $rid = is_object($r) ? $r->id : (isset($r['id']) ? $r['id'] : null);
                    if ($rid !== null) {
                        $rateMap[(string)$rid] = [
                            'name' => is_object($r) ? $r->name : ($r['name'] ?? ''),
                            'rate' => is_object($r) ? $r->rate : ($r["rate"] ?? ''),
                        ];
                    }
                }
                // Attach tax_name and tax_rate where missing
                foreach ($categories['data'] as &$cat) {
                    $tid = null;
                    if (is_array($cat) && array_key_exists('tax_id', $cat)) {
                        $tid = $cat['tax_id'];
                    }
                    if ($tid !== null && $tid !== '' && isset($rateMap[(string)$tid])) {
                        // Only set if not already provided by model
                        if (!isset($cat['tax_name'])) {
                            $cat['tax_name'] = $rateMap[(string)$tid]['name'];
                        }
                        if (!isset($cat['tax_rate'])) {
                            $cat['tax_rate'] = $rateMap[(string)$tid]['rate'];
                        }
                    }
                }
                unset($cat);
            } catch (Exception $e) {
                // Log and proceed without enrichment
                error_log('adminIndex tax enrichment error: ' . $e->getMessage());
            }
        }
        
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
        // Load lists for parent categories and tax rates
        $parentCategories = $this->categoryModel->getParentCategories();
        $taxModel = new TaxModel();
        $taxRates = $taxModel->getTaxRates(true);
        // Ensure schema supports saving tax_id if provided
        if (method_exists($this->categoryModel, 'ensureTaxIdColumn')) {
            $this->categoryModel->ensureTaxIdColumn();
        }
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'name' => sanitize($this->post('name')),
                'parent_id' => $this->post('parent_id') ? $this->post('parent_id') : null,
                'tax_id' => $this->post('tax_id') ? $this->post('tax_id') : null,
                'status' => $this->post('status') ? 1 : 0
            ];
            
            // Debug: Log the data being saved
            error_log('Creating category with data: ' . print_r($data, true));
            
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
                        redirect('?controller=category&action=adminIndex');
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
                'parentCategories' => $parentCategories,
                'taxRates' => $taxRates
            ]);
        } else {
            // Init data
            $data = [
                'name' => '',
                'parent_id' => null,
                'tax_id' => null,
                'status' => 1
            ];
            
            // Load view
            $this->view('admin/categories/create', [
                'data' => $data,
                'parentCategories' => $parentCategories,
                'taxRates' => $taxRates,
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
            redirect('?controller=category&action=adminIndex');
        }
        
        // Get category with tax information
        $category = $this->categoryModel->getWithTax($id);
        
        // Debug: Log the category data
        error_log('Category data with tax: ' . print_r($category, true));
        
        // Check if category exists
        if(!$category) {
            $errorMsg = 'Category with ID ' . $id . ' not found';
            error_log($errorMsg);
            flash('category_error', $errorMsg, 'alert alert-danger');
            redirect('?controller=category&action=adminIndex');
        }
        
        // Ensure category is an array (some database layers return objects)
        if (is_object($category)) {
            $category = (array)$category;
        }
        
        // Get parent categories for dropdown
        $parentCategories = $this->categoryModel->getParentCategories();
        
        // Get tax rates for dropdown
        $taxModel = new TaxModel();
        $taxRates = $taxModel->getTaxRates(true);
        
        // Check for POST
        if($this->isPost()) {
            // Process form
            
            // Sanitize POST data
            $data = [
                'name' => sanitize($this->post('name')),
                'description' => sanitize($this->post('description')),
                'parent_id' => $this->post('parent_id') ? $this->post('parent_id') : null,
                'tax_id' => $this->post('tax_id') ? $this->post('tax_id') : null,
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
                    // Reload the category with updated data
                    $category = $this->categoryModel->getWithTax($id);
                    if (is_object($category)) {
                        $category = (array)$category;
                    }
                    
                    flash('category_success', 'Category updated successfully!', 'alert alert-success');
                    
                    // Reload view with updated category data
                    $this->view('admin/categories/edit', [
                        'category' => $category,
                        'parentCategories' => $parentCategories,
                        'taxRates' => $taxRates,
                        'errors' => []
                    ]);
                    return;
                } else {
                    $errors['db_error'] = 'Failed to update category: ' . $this->categoryModel->getLastError();
                }
            }
            
            // Load view with errors
            $this->view('admin/categories/edit', [
                'errors' => $errors,
                'category' => array_merge($category, $data),
                'parentCategories' => $parentCategories,
                'taxRates' => $taxRates
            ]);
        } else {
            // Load view
            $this->view('admin/categories/edit', [
                'category' => $category,
                'parentCategories' => $parentCategories,
                'taxRates' => $taxRates,
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
            redirect('?controller=category&action=adminIndex');
        }
        
        // Debug: timing start
        $t0 = microtime(true);
        error_log("[Category::delete] start id={$id}");

        // Get category
        $category = $this->categoryModel->getById($id);
        // Ensure array shape for consistent access
        if (is_object($category)) {
            $category = (array)$category;
        }
        
        // Check if category exists
        if(!$category) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Category not found'], 404);
                return;
            }
            flash('category_error', 'Category not found', 'alert alert-danger');
            redirect('?controller=category&action=adminIndex');
        }
        
        // If category has products, detach them before delete
        if($this->categoryModel->hasProducts($id)) {
            $reassigned = false;
            try {
                error_log("[Category::delete] hasProducts=true, starting reassignment id={$id}");
                $tRe1 = microtime(true);
                // 1) Try detaching to NULL
                $reassigned = $this->categoryModel->reassignProductsToNull($id);
                error_log("[Category::delete] reassignToNull done (ok=" . ($reassigned ? '1' : '0') . ") in " . round((microtime(true)-$tRe1)*1000) . "ms");
                if (!$reassigned) {
                    // 2) Fallback: assign to 'Uncategorized'
                    $tRe2 = microtime(true);
                    $uncatId = $this->categoryModel->getOrCreateUncategorizedId();
                    error_log("[Category::delete] getOrCreateUncategorizedId => " . var_export($uncatId, true));
                    if ($uncatId && (int)$uncatId !== (int)$id) {
                        $reassigned = $this->categoryModel->reassignProductsToCategory($id, $uncatId);
                    } else {
                        error_log("[Category::delete] skip reassign to same category id or invalid uncatId");
                    }
                    error_log("[Category::delete] reassignToCategory done (ok=" . ($reassigned ? '1' : '0') . ") in " . round((microtime(true)-$tRe2)*1000) . "ms");
                }
            } catch (Exception $e) {
                error_log('category delete reassign error: ' . $e->getMessage());
                $reassigned = false;
            }
            if (!$reassigned) {
                $msg = 'Failed to detach or reassign products from this category: ' . $this->categoryModel->getLastError();
                if($this->isAjax()) {
                    $this->json(['success' => false, 'message' => $msg], 500);
                    return;
                }
                flash('category_error', $msg, 'alert alert-danger');
                redirect('?controller=category&action=adminIndex');
            }
            error_log("[Category::delete] reassignment successful id={$id}");
        }
        
        // Check if category has subcategories
        $tSub = microtime(true);
        $hasSubs = $this->categoryModel->hasSubcategories($id);
        error_log("[Category::delete] hasSubcategories=" . ($hasSubs ? '1' : '0') . " in " . round((microtime(true)-$tSub)*1000) . "ms");
        if($hasSubs) {
            if($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Cannot delete category with subcategories'], 400);
                return;
            }
            flash('category_error', 'Cannot delete category with subcategories', 'alert alert-danger');
            redirect('?controller=category&action=adminIndex');
        }
        
        // Check for POST
        if($this->isPost() || $this->isDelete()) {
            // Permanent delete: remove image from disk, then remove row from DB
            if(!empty($category['image'])) {
                $tImg = microtime(true);
                $possiblePaths = [
                    // Project root relative path
                    (defined('ROOT_PATH') ? ROOT_PATH : __DIR__ . '/../../') . $category['image'],
                    // Public path variant
                    (defined('PUBLIC_PATH') ? PUBLIC_PATH : ((defined('ROOT_PATH') ? ROOT_PATH : __DIR__ . '/../../') . 'public/')) . $category['image'],
                    // As-is (relative to current working dir)
                    $category['image']
                ];
                foreach ($possiblePaths as $path) {
                    if (@file_exists($path)) {
                        @unlink($path);
                        break;
                    }
                }
                error_log("[Category::delete] image cleanup took " . round((microtime(true)-$tImg)*1000) . "ms");
            }
            
            // Delete category
            $tDel = microtime(true);
            if($this->categoryModel->delete($id)) {
                if($this->isAjax()) {
                    $this->json([
                        'success' => true, 
                        'message' => 'Category deleted successfully',
                        'id' => $id
                    ]);
                    error_log("[Category::delete] delete success in " . round((microtime(true)-$tDel)*1000) . "ms; total " . round((microtime(true)-$t0)*1000) . "ms");
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
                redirect('?controller=category&action=adminIndex' . ($page > 1 ? '&page=' . $page : ''));
            }
        } else {
            // Load view
            $this->view('admin/categories/delete', [
                'category' => $category
            ]);
        }
        error_log("[Category::delete] end id={$id} total " . round((microtime(true)-$t0)*1000) . "ms");
    }
}
