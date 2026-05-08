<?php
/**
 * Admin Controller
 * Handles admin dashboard and auth redirects for admin area
 */
class AdminController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Default admin route -> dashboard
     */
    public function index() {
        return $this->dashboard();
    }

    /**
     * Admin dashboard
     */
    public function dashboard() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }

        // Set page title
        $this->setPageTitle('Dashboard');

        // Enable output caching for 5 minutes (300 seconds)
        // This caches the entire rendered HTML output
        $this->enableOutputCache(300);

        $recentOrders = [];
        $lowStockProducts = [];
        $lowStockCategories = [];
        $lowStockThreshold = $this->getLowStockThreshold();
        $lowStockPerPage = 8;
        $lowStockTotal = 0;
        
        try {
            $orderModel = $this->model('Order');
            if (method_exists($orderModel, 'getRecentOrders')) {
                $recentOrders = $orderModel->getRecentOrders(5);
            }
        } catch (Exception $e) {
            error_log('Admin dashboard recent orders: ' . $e->getMessage());
        }

        // Enhanced caching with memory + file cache for low stock data
        try {
            $cacheKey = 'admin_low_stock_initial_v3_' . $lowStockThreshold . '_' . $lowStockPerPage;
            
            // Try memory cache first (APCu if available)
            $cachedLowStock = $this->readMemoryCache($cacheKey);
            
            if (!is_array($cachedLowStock)) {
                // Fall back to file cache
                $cachedLowStock = $this->readCache($cacheKey, 300);
            }
            
            if (is_array($cachedLowStock) && !empty($cachedLowStock['rows'])) {
                $lowStockProducts = $cachedLowStock['rows'];
                $lowStockCategories = $cachedLowStock['categories'] ?? [];
                $lowStockTotal = (int)($cachedLowStock['total'] ?? 0);
            } else {
                $productModel = $this->model('Product');
                
                // Use a single efficient query with join instead of multiple queries
                $lowStockProducts = $productModel->getLowStockProducts($lowStockThreshold, $lowStockPerPage, 0);
                $lowStockCategories = $productModel->getLowStockCategories($lowStockThreshold);
                $lowStockTotal = $productModel->countLowStockProducts($lowStockThreshold);
                
                $cacheData = [
                    'rows' => $lowStockProducts,
                    'categories' => $lowStockCategories,
                    'total' => $lowStockTotal,
                    'cached_at' => time()
                ];
                
                // Write to both memory and file cache
                $this->writeMemoryCache($cacheKey, $cacheData, 300);
                $this->writeCache($cacheKey, $cacheData);
            }
        } catch (Throwable $e) {
            error_log('Admin dashboard low stock: ' . $e->getMessage());
        }

        // Check if user prefers modern layout (default to modern)
        $useModernLayout = $this->get('modern', '1') === '1';
        
        if ($useModernLayout) {
            // Use modern layout
            $content = APP_PATH . 'views/admin/dashboard-modern-content.php';
            $this->view('admin/layouts/dashboard-modern', [
                'content' => $content,
                'pageTitle' => 'Dashboard',
                'recentOrders' => is_array($recentOrders) ? $recentOrders : [],
                'lowStockProducts' => is_array($lowStockProducts) ? $lowStockProducts : [],
                'lowStockCategories' => is_array($lowStockCategories) ? $lowStockCategories : [],
                'lowStockThreshold' => $lowStockThreshold,
                'lowStockPerPage' => $lowStockPerPage,
                'lowStockTotal' => $lowStockTotal
            ]);
        } else {
            // Use legacy layout
            $this->view('admin/dashboard', [
                'recentOrders' => is_array($recentOrders) ? $recentOrders : [],
                'lowStockProducts' => is_array($lowStockProducts) ? $lowStockProducts : [],
                'lowStockCategories' => is_array($lowStockCategories) ? $lowStockCategories : [],
                'lowStockThreshold' => $lowStockThreshold,
                'lowStockPerPage' => $lowStockPerPage,
                'lowStockTotal' => $lowStockTotal
            ]);
        }
    }

    public function lowStockAlerts() {
        if (!function_exists('isAdmin')) {
            require_once APP_PATH . 'helpers.php';
        }
        if (!isAdmin()) {
            $this->json(['success' => false, 'message' => 'Unauthorized'], 401);
            return;
        }

        $threshold = $this->getLowStockThreshold();
        $perPage = max(1, min(50, (int)$this->get('per_page', 8)));
        $page = max(1, (int)$this->get('page', 1));
        $offset = ($page - 1) * $perPage;
        $search = trim((string)$this->get('search', ''));
        $category = trim((string)$this->get('category', ''));
        $status = strtolower(trim((string)$this->get('status', '')));
        if (!in_array($status, ['critical', 'low'], true)) {
            $status = '';
        }

        // Build cache key - use shorter hash for better performance
        $cacheKey = 'admin_low_stock_v3_' . md5("{$threshold}:{$page}:{$perPage}:{$search}:{$category}:{$status}");

        // Try memory cache first (faster)
        $cached = $this->readMemoryCache($cacheKey);
        if (!is_array($cached)) {
            // Fall back to file cache
            $cached = $this->readCache($cacheKey, 60); // Shorter TTL for paginated results
        }
        
        if (is_array($cached)) {
            // Add cache hit header for debugging
            header('X-Cache: HIT');
            $this->json(['success' => true] + $cached);
            return;
        }

        try {
            $productModel = $this->model('Product');
            
            // Execute queries efficiently
            $rows = $productModel->getLowStockProducts($threshold, $perPage, $offset, $search, $category, $status);
            $total = $productModel->countLowStockProducts($threshold, $search, $category, $status);
            
            $payload = [
                'rows' => $this->formatLowStockRows($rows, $threshold),
                'pagination' => [
                    'page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => max(1, (int)ceil($total / $perPage))
                ]
            ];
            
            // Cache to both memory and file
            $this->writeMemoryCache($cacheKey, $payload, 60);
            $this->writeCache($cacheKey, $payload);
            
            header('X-Cache: MISS');
            $this->json(['success' => true] + $payload);
        } catch (Throwable $e) {
            error_log('Admin lowStockAlerts: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Unable to load low stock products'], 500);
        }
    }

    /**
     * Show admin login (delegate to user login route for this codebase)
     */
    public function login() {
        if (isAdmin()) {
            redirect('admin/dashboard');
        }
        redirect('user/login');
    }

    /**
     * Logout admin and redirect to login
     */
    public function logout() {
        logout();
        redirect('user/login');
    }

    private function getLowStockThreshold() {
        try {
            $settingModel = $this->model('Setting');
            $value = $settingModel->getSetting('store_low_stock_threshold', 5);
            return is_numeric($value) && (int)$value > 0 ? (int)$value : 5;
        } catch (Throwable $e) {
            error_log('Admin low stock threshold: ' . $e->getMessage());
            return 5;
        }
    }

    private function formatLowStockRows(array $rows, $threshold) {
        $threshold = max(1, (int)$threshold);
        $criticalThreshold = max(1, (int)floor($threshold / 2));
        return array_map(function($row) use ($threshold, $criticalThreshold) {
            $stock = (int)($row['stock_quantity'] ?? 0);
            $category = trim((string)($row['category_name'] ?? 'Uncategorized'));
            $isCritical = $stock <= $criticalThreshold;
            return [
                'id' => (int)($row['id'] ?? 0),
                'name' => (string)($row['name'] ?? 'Product'),
                'sku' => (string)($row['sku'] ?? ''),
                'category_name' => $category !== '' ? $category : 'Uncategorized',
                'stock_quantity' => $stock,
                'minimum_stock' => $threshold,
                'percentage' => max(0, min(100, (int)round(($stock / $threshold) * 100))),
                'status' => $isCritical ? 'critical' : 'low',
                'status_label' => $isCritical ? 'Critical' : 'Low'
            ];
        }, $rows);
    }

    private function cacheDir() {
        $dir = ROOT_PATH . 'storage/cache/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }
        return $dir;
    }

    private function cachePath($key) {
        return $this->cacheDir() . md5($key) . '.json';
    }

    private function readCache($key, $ttlSeconds) {
        $path = $this->cachePath($key);
        if (!is_file($path) || filemtime($path) < (time() - (int)$ttlSeconds)) {
            return null;
        }
        $raw = @file_get_contents($path);
        if ($raw === false || $raw === '') {
            return null;
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : null;
    }

    private function writeCache($key, array $payload) {
        @file_put_contents($this->cachePath($key), json_encode($payload), LOCK_EX);
    }

    /**
     * Memory cache using APCu (if available) - much faster than file cache
     */
    private function readMemoryCache($key) {
        // Use APCu if available (requires apcu PHP extension)
        if (function_exists('apcu_fetch')) {
            $value = apcu_fetch($key, $success);
            return $success ? $value : null;
        }
        
        // Fall back to static in-memory cache for single request
        static $memory = [];
        return isset($memory[$key]) ? $memory[$key] : null;
    }

    private function writeMemoryCache($key, $payload, $ttl = 300) {
        // Use APCu if available
        if (function_exists('apcu_store')) {
            return apcu_store($key, $payload, (int)$ttl);
        }
        
        // Fall back to static in-memory cache
        static $memory = [];
        $memory[$key] = $payload;
        return true;
    }

    /**
     * Enable output caching using CodeIgniter-style page caching
     * This caches the entire rendered HTML output
     */
    private function enableOutputCache($ttlSeconds = 300) {
        // Check if we have a cached version of this page
        $cacheKey = 'output_' . md5($_SERVER['REQUEST_URI'] . ($_SESSION['user_id'] ?? 'guest'));
        $cached = $this->readCache($cacheKey, $ttlSeconds);
        
        if (is_string($cached) && !empty($cached)) {
            // Send cached content with compression
            $this->sendCompressedOutput($cached);
            exit;
        }
        
        // Start output buffering to capture the page
        ob_start();
        
        // Register shutdown function to cache the output
        register_shutdown_function(function() use ($cacheKey, $ttlSeconds) {
            $output = ob_get_flush();
            if (!empty($output)) {
                $this->writeCache($cacheKey, ['html' => $output, 'time' => time()]);
            }
        });
    }

    /**
     * Send compressed output with proper headers
     */
    private function sendCompressedOutput($cached) {
        $html = is_array($cached) ? ($cached['html'] ?? '') : $cached;
        
        if (empty($html)) {
            return;
        }
        
        // Check if client accepts gzip
        $acceptsGzip = isset($_SERVER['HTTP_ACCEPT_ENCODING']) && 
                       strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false;
        
        if ($acceptsGzip && strlen($html) > 1024) {
            header('Content-Encoding: gzip');
            header('Vary: Accept-Encoding');
            echo gzencode($html, 6);
        } else {
            echo $html;
        }
    }

    /**
     * Clear all low stock related caches - call this when stock changes
     */
    public function clearLowStockCache() {
        if (!isAdmin()) {
            return;
        }
        
        $cacheDir = $this->cacheDir();
        $files = glob($cacheDir . '*.json');
        $cleared = 0;
        
        foreach ($files as $file) {
            $basename = basename($file);
            if (strpos($basename, md5('admin_low_stock')) === 0 || 
                strpos($basename, md5('admin_low_stock_initial')) === 0 ||
                strpos($basename, md5('output_')) === 0) {
                @unlink($file);
                $cleared++;
            }
        }
        
        // Clear APCu cache if available
        if (function_exists('apcu_clear_cache')) {
            apcu_clear_cache();
        }
        
        $this->json(['success' => true, 'cleared' => $cleared]);
    }
}
