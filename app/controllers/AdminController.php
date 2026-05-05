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
        if (!function_exists('isAdmin')) {
            require_once APP_PATH . 'helpers.php';
        }
        if (!isAdmin()) {
            redirect('user/login');
            return;
        }

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
        } catch (Throwable $e) {
            error_log('Admin dashboard: ' . $e->getMessage());
        }

        try {
            $cacheKey = 'admin_low_stock_initial_v2_' . $lowStockThreshold . '_' . $lowStockPerPage;
            $cachedLowStock = $this->readCache($cacheKey, 300);
            if (is_array($cachedLowStock)) {
                $lowStockProducts = $cachedLowStock['rows'] ?? [];
                $lowStockCategories = $cachedLowStock['categories'] ?? [];
                $lowStockTotal = (int)($cachedLowStock['total'] ?? 0);
            } else {
                $productModel = $this->model('Product');
                $lowStockProducts = $productModel->getLowStockProducts($lowStockThreshold, $lowStockPerPage, 0);
                $lowStockCategories = $productModel->getLowStockCategories($lowStockThreshold);
                $lowStockTotal = $productModel->countLowStockProducts($lowStockThreshold);
                $this->writeCache($cacheKey, [
                    'rows' => $lowStockProducts,
                    'categories' => $lowStockCategories,
                    'total' => $lowStockTotal
                ]);
            }
        } catch (Throwable $e) {
            error_log('Admin dashboard low stock: ' . $e->getMessage());
        }

        $this->view('admin/dashboard', [
            'recentOrders' => is_array($recentOrders) ? $recentOrders : [],
            'lowStockProducts' => is_array($lowStockProducts) ? $lowStockProducts : [],
            'lowStockCategories' => is_array($lowStockCategories) ? $lowStockCategories : [],
            'lowStockThreshold' => $lowStockThreshold,
            'lowStockPerPage' => $lowStockPerPage,
            'lowStockTotal' => $lowStockTotal
        ]);
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

        $cacheKey = 'admin_low_stock_page_v2_' . md5(json_encode([
            'threshold' => $threshold,
            'page' => $page,
            'per_page' => $perPage,
            'search' => $search,
            'category' => $category,
            'status' => $status
        ]));

        $cached = $this->readCache($cacheKey, 300);
        if (is_array($cached)) {
            $this->json(['success' => true] + $cached);
            return;
        }

        try {
            $productModel = $this->model('Product');
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
            $this->writeCache($cacheKey, $payload);
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
}
