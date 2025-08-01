<?php
/**
 * Base Controller
 * Loads models and views
 */
class Controller {
    /**
     * @var object Database connection
     */
    protected $db;
    
    /**
     * Constructor - initialize database connection
     */
    public function __construct() {
        // Use the global database connection if it exists
        if (isset($GLOBALS['db'])) {
            $this->db = $GLOBALS['db'];
        } else {
            // Fallback: Create a new database connection
            require_once ROOT_PATH . '/config/database.php';
            $this->db = new Database();
            $GLOBALS['db'] = $this->db; // Store in global scope for other instances
        }
    }
    
    /**
     * Load model
     * 
     * @param string $model Model name
     * @return object
     */
    public function model($model) {
        // Require model file
        require_once APP_PATH . 'models/' . $model . '.php';
        
        // Instantiate model
        return new $model();
    }
    
    /**
     * Load view
     * 
     * @param string $view View name
     * @param array $data Data to pass to the view
     * @return void
     */
    public function view($view, $data = []) {
        // Check for view file
        if(file_exists(APP_PATH . 'views/' . $view . '.php')) {
            // Extract data to make variables available in the view
            extract($data);
            
            require_once APP_PATH . 'views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist');
        }
    }
    
    /**
     * Render JSON response
     * 
     * @param mixed $data Data to encode as JSON
     * @param int $statusCode HTTP status code
     * @return void
     */
    public function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
    }
    
    /**
     * Handle file upload
     * 
     * @param string $fieldName Name of the file input field
     * @param string $uploadDir Directory to upload to (relative to UPLOAD_PATH)
     * @param array $allowedTypes Array of allowed MIME types
     * @param int $maxSize Maximum file size in bytes
     * @return array Upload result with success status, file name, and error message if any
     */
    protected function uploadFile($fieldName, $uploadDir, $allowedTypes = [], $maxSize = 2097152) {
        // Ensure upload directory exists and is writable
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                return [
                    'success' => false,
                    'error' => 'Failed to create upload directory',
                    'file_name' => ''
                ];
            }
        }
        
        if (!is_writable($uploadDir)) {
            return [
                'success' => false,
                'error' => 'Upload directory is not writable',
                'file_name' => ''
            ];
        }
        
        // Check if file was uploaded without errors
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            $error = 'No file was uploaded or an error occurred during upload';
            if (isset($_FILES[$fieldName]['error'])) {
                switch ($_FILES[$fieldName]['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $error = 'File is too large';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $error = 'File was only partially uploaded';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $error = 'No file was uploaded';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $error = 'Missing temporary folder';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $error = 'Failed to write file to disk';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $error = 'A PHP extension stopped the file upload';
                        break;
                }
            }
            return [
                'success' => false,
                'error' => $error,
                'file_name' => ''
            ];
        }
        
        $file = $_FILES[$fieldName];
        $fileName = $file['name'];
        $fileTmp = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileType = $file['type'];
        
        // Check file size
        if ($fileSize > $maxSize) {
            return [
                'success' => false,
                'error' => 'File is too large. Maximum size allowed: ' . ($maxSize / 1024 / 1024) . 'MB',
                'file_name' => ''
            ];
        }
        
        // Check file type
        if (!empty($allowedTypes) && !in_array($fileType, $allowedTypes)) {
            return [
                'success' => false,
                'error' => 'Invalid file type. Allowed types: ' . implode(', ', $allowedTypes),
                'file_name' => ''
            ];
        }
        
        // Generate a unique file name to prevent overwrites
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid() . '.' . $fileExt;
        $destination = rtrim($uploadDir, '/') . '/' . $newFileName;
        
        // Move the uploaded file to the destination
        if (move_uploaded_file($fileTmp, $destination)) {
            return [
                'success' => true,
                'file_name' => $newFileName,
                'original_name' => $fileName,
                'file_path' => $destination
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Failed to move uploaded file',
                'file_name' => ''
            ];
        }
    }
    
    /**
     * Redirect to a URL
     * 
     * @param string $url URL to redirect to
     * @return void
     */
    public function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    
    /**
     * Get POST data
     * 
     * @param string $field Field name
     * @param mixed $default Default value if field does not exist
     * @return mixed
     */
    public function post($field = null, $default = null) {
        if($field === null) {
            return $_POST;
        }
        
        return isset($_POST[$field]) ? $_POST[$field] : $default;
    }
    
    /**
     * Get GET data
     * 
     * @param string $field Field name
     * @param mixed $default Default value if field does not exist
     * @return mixed
     */
    public function get($field = null, $default = null) {
        if($field === null) {
            return $_GET;
        }
        
        return isset($_GET[$field]) ? $_GET[$field] : $default;
    }
    
    /**
     * Check if request is AJAX
     * 
     * @return bool
     */
    public function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Check if request is POST
     * 
     * @return bool
     */
    public function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Check if request is GET
     * 
     * @return bool
     */
    public function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
    
    /**
     * Validate form data
     * 
     * @param array $data Data to validate
     * @param array $rules Validation rules
     * @return array Errors
     */
    public function validate($data, $rules) {
        $errors = [];
        
        foreach($rules as $field => $rule) {
            $value = isset($data[$field]) ? $data[$field] : '';
            
            // Required
            if(strpos($rule, 'required') !== false && empty($value)) {
                $errors[$field] = ucfirst($field) . ' is required';
                continue;
            }
            
            // Email
            if(strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL) && !empty($value)) {
                $errors[$field] = ucfirst($field) . ' must be a valid email';
            }
            
            // Min length
            if(preg_match('/min:(\d+)/', $rule, $matches) && strlen($value) < $matches[1] && !empty($value)) {
                $errors[$field] = ucfirst($field) . ' must be at least ' . $matches[1] . ' characters';
            }
            
            // Max length
            if(preg_match('/max:(\d+)/', $rule, $matches) && strlen($value) > $matches[1]) {
                $errors[$field] = ucfirst($field) . ' must not exceed ' . $matches[1] . ' characters';
            }
            
            // Numeric
            if(strpos($rule, 'numeric') !== false && !is_numeric($value) && !empty($value)) {
                $errors[$field] = ucfirst($field) . ' must be a number';
            }
            
            // Match
            if(preg_match('/match:(\w+)/', $rule, $matches) && $value != $data[$matches[1]]) {
                $errors[$field] = ucfirst($field) . ' does not match ' . $matches[1];
            }
        }
        
        return $errors;
    }
}
