<?php
/**
 * Helper functions for the e-commerce application
 */

/**
 * Redirect to a specific page
 * 
 * @param string $page The page to redirect to
 * @return void
 */
function redirect($page) {
    header('Location: ' . BASE_URL . $page);
    exit;
}

/**
 * Display flash messages
 * 
 * @param string $name The name of the message
 * @param string $message The message text
 * @param string $class The CSS class for styling
 * @return void
 */
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if(!empty($name)) {
        if(!empty($message) && empty($_SESSION[$name])) {
            if(!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }
            if(!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

/**
 * Check if user is logged in
 * 
 * @return boolean
 */
function isLoggedIn() {
    if(isset($_SESSION['user_id'])) {
        return true;
    } else {
        // Check for remember me cookie
        if(isset($_COOKIE['remember_token'])) {
            $token = $_COOKIE['remember_token'];
            
            // Load models
            require_once APP_PATH . 'models/RememberToken.php';
            require_once APP_PATH . 'models/User.php';
            
            $tokenModel = new RememberToken();
            $userModel = new User();
            
            // Find valid token
            $tokenData = $tokenModel->findValidToken($token);
            
            if($tokenData) {
                // Get user
                $user = $userModel->getById($tokenData['user_id']);
                
                if($user) {
                    // Create session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['username'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    
                    return true;
                }
            }
            
            // Invalid token, clear cookie
            setcookie('remember_token', '', time() - 3600, '/');
        }
        
        return false;
    }
}

/**
 * Logout user
 * 
 * @return void
 */
function logout() {
    // Clear remember me token if exists
    if(isset($_COOKIE['remember_token'])) {
        $token = $_COOKIE['remember_token'];
        
        // Load model
        require_once APP_PATH . 'models/RememberToken.php';
        $tokenModel = new RememberToken();
        
        // Delete token
        $tokenModel->deleteToken($token);
        
        // Clear cookie
        setcookie('remember_token', '', time() - 3600, '/');
    }
    
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
}

/**
 * Check if user is admin
 * 
 * @return boolean
 */
function isAdmin() {
    if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if user is staff
 * 
 * @return boolean
 */
function isStaff() {
    if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'staff' || $_SESSION['user_role'] == 'admin')) {
        return true;
    } else {
        return false;
    }
}

/**
 * Format price with currency symbol from settings
 * 
 * @param float $price The price to format
 * @return string
 */
function formatPrice($price) {
    static $currencySymbol = null;
    
    // Get currency symbol from settings if not already loaded
    if ($currencySymbol === null) {
        $settingModel = new Setting();
        $currencySymbol = $settingModel->getSetting('store_currency_symbol', '₹');
    }
    
    return $currencySymbol . ' ' . number_format((float)$price, 2);
}

/**
 * Format currency with symbol
 * 
 * @param float $amount The amount to format
 * @param string $currency The currency symbol (optional, will use from settings if not provided)
 * @return string
 */
function formatCurrency($amount, $currency = null) {
    if ($currency === null) {
        // Get currency symbol from settings if not provided
        $settingModel = new Setting();
        $currency = $settingModel->getSetting('store_currency_symbol', '₹');
    }
    return $currency . ' ' . number_format((float)$amount, 2);
}

/**
 * Sanitize data
 * 
 * @param string|null $data The data to sanitize
 * @return string
 */
function sanitize($data) {
    if ($data === null) {
        return '';
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Generate a random string
 * 
 * @param int $length The length of the string
 * @return string
 */
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

/**
 * Get current date and time
 * 
 * @return string
 */
function getCurrentDateTime() {
    return date('Y-m-d H:i:s');
}

/**
 * Calculate discount percentage
 * 
 * @param float $original The original price
 * @param float $discounted The discounted price
 * @return int
 */
function calculateDiscountPercentage($original, $discounted) {
    if($original == 0) {
        return 0;
    }
    return round(($original - $discounted) / $original * 100);
}

/**
 * Truncate text to a specific length
 * 
 * @param string $text The text to truncate
 * @param int $length The length to truncate to
 * @return string
 */
function truncateText($text, $length = 100) {
    if(strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}

/**
 * Upload an image file
 * 
 * @param array $file The $_FILES array element for the file
 * @param string $subfolder The subfolder in the uploads directory (e.g., 'brands', 'products')
 * @return array ['success' => bool, 'path' => string, 'error' => string]
 */
function uploadImage($file, $subfolder = 'uploads') {
    $uploadDir = ROOT_PATH . 'public/uploads/' . trim($subfolder, '/') . '/';
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'File upload error: ';
        switch ($file['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $error .= 'File is too large';
                break;
            case UPLOAD_ERR_PARTIAL:
                $error .= 'File was only partially uploaded';
                break;
            case UPLOAD_ERR_NO_FILE:
                $error .= 'No file was uploaded';
                break;
            default:
                $error .= 'Unknown error occurred';
        }
        return ['success' => false, 'error' => $error];
    }
    
    // Check if file is an image
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];
    
    if (!in_array($mime, array_keys($allowedMimes))) {
        return ['success' => false, 'error' => 'Only JPG, PNG, GIF, and WebP files are allowed'];
    }
    
    // Generate unique filename
    $extension = $allowedMimes[$mime];
    $filename = uniqid() . '.' . $extension;
    $filepath = $uploadDir . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        // Return the relative path from public directory
        $relativePath = 'uploads/' . trim($subfolder, '/') . '/' . $filename;
        return [
            'success' => true, 
            'path' => $relativePath,
            'full_path' => $filepath
        ];
    }
    
    return ['success' => false, 'error' => 'Failed to move uploaded file'];
}

/**
 * Get pagination links
 * 
 * @param int $currentPage The current page
 * @param int $totalPages The total number of pages
 * @param string $url The base URL for pagination
 * @return string
 */
function getPaginationLinks($currentPage, $totalPages, $url) {
    $links = '';
    
    if($totalPages > 1) {
        $links .= '<ul class="pagination">';
        
        // Previous link
        if($currentPage > 1) {
            $links .= '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($currentPage - 1) . '">Previous</a></li>';
        } else {
            $links .= '<li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>';
        }
        
        // Page links
        for($i = 1; $i <= $totalPages; $i++) {
            if($i == $currentPage) {
                $links .= '<li class="page-item active"><a class="page-link" href="#">' . $i . '</a></li>';
            } else {
                $links .= '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . $i . '">' . $i . '</a></li>';
            }
        }
        
        // Next link
        if($currentPage < $totalPages) {
            $links .= '<li class="page-item"><a class="page-link" href="' . $url . '?page=' . ($currentPage + 1) . '">Next</a></li>';
        } else {
            $links .= '<li class="page-item disabled"><a class="page-link" href="#">Next</a></li>';
        }
        
        $links .= '</ul>';
    }
    
    return $links;
}
