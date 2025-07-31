<?php
/**
 * Country Controller
 */
class CountryController extends Controller {
    private $countryModel;
    private $productModel;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->countryModel = $this->model('Country');
        $this->productModel = $this->model('Product');
    }
    
    /**
     * Index - List all countries
     */
    public function index() {
        // Get all countries
        $countries = $this->countryModel->getActiveCountries();
        
        // Load view
        $this->view('customer/countries/index', [
            'countries' => $countries,
            'title' => 'Countries of Origin'
        ]);
    }
    
    /**
     * Show - Show products from a specific country
     * 
     * @param int $id Country ID
     */
    public function show($id = null) {
        // Get country
        $country = $this->countryModel->getCountryById($id);
        
        if(!$country) {
            redirect('?controller=country&action=index');
        }
        
        // Get products by country
        $products = $this->countryModel->getProductsByCountry($id);
        
        // Load view
        $this->view('customer/countries/show', [
            'country' => $country,
            'products' => $products,
            'title' => $country['name'] . ' Products'
        ]);
    }
    
    /**
     * Admin - Manage countries
     */
    public function adminIndex() {
        // Check if admin is logged in
        if(!isAdmin()) {
            redirect('?controller=home&action=admin');
        }
        
        // Get all countries with product counts
        $countries = $this->countryModel->getAllCountriesWithProductCounts();
        
        // Get selected country (from URL parameter or default to first one)
        $selectedCountry = null;
        if (isset($_GET['id'])) {
            $selectedCountry = $this->countryModel->getCountryById($_GET['id']);
        }
        
        // If no country is selected or the selected country is not found, use the first one
        if ((!$selectedCountry || !isset($selectedCountry['id'])) && !empty($countries)) {
            $selectedCountry = $countries[0];
        }
        
        // Load view with full path
        $this->view('admin/countries/index', [
            'countries' => $countries,
            'selectedCountry' => $selectedCountry,
            'title' => 'Manage Countries of Origin'
        ]);
    }
    
    /**
     * Create a new country
     */
    public function create() {
        // Check if admin is logged in
        if(!isAdmin()) {
            redirect('?controller=home&action=admin');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $data = [
                'name' => trim($_POST['name']),
                'code' => strtoupper(substr(trim($_POST['name']), 0, 2)),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Validate input
            if (empty($data['name'])) {
                $error = 'Please enter country name';
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('HTTP/1.1 400 Bad Request');
                    echo json_encode(['success' => false, 'message' => $error]);
                    exit;
                } else {
                    flash('country_message', $error, 'alert alert-danger');
                    redirect('?controller=country&action=adminIndex');
                }
                return;
            }
            
            // Handle file upload
            if (!empty($_FILES['flag_image']['name'])) {
                $uploadDir = UPLOAD_PATH . 'flags/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                
                if (in_array($_FILES['flag_image']['type'], $allowedTypes) && 
                    $_FILES['flag_image']['size'] <= $maxSize) {
                    
                    $fileExt = pathinfo($_FILES['flag_image']['name'], PATHINFO_EXTENSION);
                    $fileName = 'flag_' . time() . '_' . uniqid() . '.' . $fileExt;
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['flag_image']['tmp_name'], $targetPath)) {
                        $data['flag_image'] = $fileName;
                    }
                } else {
                    $error = 'Invalid file type or file too large (max 2MB)';
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                        header('HTTP/1.1 400 Bad Request');
                        echo json_encode(['success' => false, 'message' => $error]);
                        exit;
                    } else {
                        flash('country_message', $error, 'alert alert-danger');
                        redirect('?controller=country&action=adminIndex');
                    }
                    return;
                }
            }
            
            // Create country
            $newCountryId = $this->countryModel->addCountry($data);
            if ($newCountryId) {
                $successMessage = 'Country added successfully';
                $newCountry = $this->countryModel->getCountryById($newCountryId);
                
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true, 
                        'message' => $successMessage,
                        'country' => $newCountry
                    ]);
                    exit;
                } else {
                    flash('country_message', $successMessage, 'alert alert-success');
                    redirect('?controller=country&action=adminIndex&id=' . $newCountryId);
                }
            } else {
                $error = 'Error adding country: ' . $this->countryModel->getLastError();
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('HTTP/1.1 500 Internal Server Error');
                    echo json_encode(['success' => false, 'message' => $error]);
                    exit;
                } else {
                    flash('country_message', $error, 'alert alert-danger');
                    redirect('?controller=country&action=adminIndex');
                }
            }
        }
        
        redirect('?controller=country&action=adminIndex');
    }
    
    /**
     * Update country
     */
    public function update() {
        // Check if admin is logged in
        if(!isAdmin()) {
            redirect('?controller=home&action=admin');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $data = [
                'id' => (int)$_POST['id'],
                'name' => trim($_POST['name']),
                'status' => isset($_POST['status']) ? 'active' : 'inactive',
                'code' => strtoupper(substr(trim($_POST['name']), 0, 2)) // Generate 2-letter country code
            ];
            
            // Add description if provided
            if (isset($_POST['description']) && !empty(trim($_POST['description']))) {
                $data['description'] = trim($_POST['description']);
            }
            
            // Handle flag image upload
            if (!empty($_FILES['flag_image']['name'])) {
                $uploadDir = UPLOAD_PATH . 'flags/';
                
                // Create directory if it doesn't exist
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                
                if (in_array($_FILES['flag_image']['type'], $allowedTypes) && 
                    $_FILES['flag_image']['size'] <= $maxSize) {
                    
                    $fileExt = pathinfo($_FILES['flag_image']['name'], PATHINFO_EXTENSION);
                    $fileName = 'flag_' . time() . '_' . uniqid() . '.' . $fileExt;
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['flag_image']['tmp_name'], $targetPath)) {
                        // Delete old flag if exists
                        // Get old flag filename and delete it if exists
                        $oldCountry = $this->countryModel->getCountryById($data['id']);
                        if ($oldCountry && !empty($oldCountry['flag_image'])) {
                            $oldFlagPath = $uploadDir . $oldCountry['flag_image'];
                            if (file_exists($oldFlagPath)) {
                                @unlink($oldFlagPath);
                            }
                        }
                        
                        $data['flag_image'] = $fileName;
                    }
                } else {
                    flash('country_message', 'Invalid file type or file too large (max 2MB)', 'alert alert-danger');
                    redirect('?controller=country&action=adminIndex&id=' . $data['id']);
                    return;
                }
            }
            
            // Update country
            if ($this->countryModel->updateCountry($data)) {
                flash('country_message', 'Country updated successfully', 'alert alert-success');
            } else {
                flash('country_message', 'Something went wrong', 'alert alert-danger');
            }
            
            redirect('?controller=country&action=adminIndex&id=' . $data['id']);
        }
        
        redirect('?controller=country&action=adminIndex');
    }
    
    /**
     * Delete a country
     */
    public function delete() {
        // Check if admin is logged in
        if(!isAdmin()) {
            redirect('?controller=home&action=admin');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $countryId = (int)$_POST['id'];
            
            if ($this->countryModel->deleteCountry($countryId)) {
                flash('country_message', 'Country deleted successfully', 'alert alert-success');
            } else {
                $error = $this->countryModel->getLastError() ?: 'Failed to delete country';
                flash('country_message', $error, 'alert alert-danger');
            }
        } else {
            flash('country_message', 'Invalid request', 'alert alert-danger');
        }
        
        redirect('?controller=country&action=adminIndex');
    }
    
    /**
     * Helper function to handle file uploads
     */
    private function uploadImage($fieldName, $targetDir) {
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        
        $file = $_FILES[$fieldName];
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        // Check if image file is an actual image
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            return false;
        }
        
        // Check file size (max 5MB)
        if ($file['size'] > 5000000) {
            return false;
        }
        
        // Allow certain file formats
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            return false;
        }
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $fileName;
        }
        
        return false;
    }
}
