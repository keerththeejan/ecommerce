<?php
/**
 * Wishlist Controller
 * Customer wishlist page + AJAX API (Amazon/Shopify style)
 */
class WishlistController extends Controller {
    private $wishlistModel;
    private $productModel;
    private $cartModel;

    public function __construct() {
        parent::__construct();
        $this->wishlistModel = $this->model('Wishlist');
        $this->productModel = $this->model('Product');
        $this->cartModel = $this->model('Cart');
    }

    /**
     * Require login for page actions; AJAX gets JSON + login redirect URL
     */
    private function requireLoginForWishlist() {
        if (isLoggedIn()) {
            return true;
        }

        $loginUrl = rtrim(BASE_URL, '/') . '/?controller=user&action=login';
        $returnTo = !empty($_SERVER['HTTP_REFERER'])
            ? $_SERVER['HTTP_REFERER']
            : (rtrim(BASE_URL, '/') . '/?controller=wishlist');

        $_SESSION['login_redirect'] = $returnTo;

        if ($this->isAjax() || $this->wantsJson()) {
            $this->json([
                'success' => false,
                'message' => 'Please log in to manage your wishlist',
                'redirect' => $loginUrl . '&redirect=' . urlencode($returnTo),
                'count' => 0,
                'require_login' => true
            ], 401);
            return false;
        }

        flash('login_required', 'Please login to manage your wishlist', 'alert alert-warning');
        $this->redirect($loginUrl . '&redirect=' . urlencode($returnTo));
        return false;
    }

    private function wantsJson() {
        $accept = isset($_SERVER['HTTP_ACCEPT']) ? (string)$_SERVER['HTTP_ACCEPT'] : '';
        if (stripos($accept, 'application/json') !== false) {
            return true;
        }
        $requestedWith = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? strtolower((string)$_SERVER['HTTP_X_REQUESTED_WITH']) : '';
        if ($requestedWith === 'xmlhttprequest') {
            return true;
        }
        if (isset($_GET['ajax']) || isset($_POST['ajax'])) {
            return true;
        }
        return false;
    }

    private function resolveProductId($productId = null) {
        if ($productId !== null && $productId !== '') {
            return (int)$productId;
        }
        if ($this->isPost()) {
            $fromPost = $this->post('product_id', $this->post('id', null));
            if ($fromPost !== null && $fromPost !== '') {
                return (int)$fromPost;
            }
        }
        foreach (['id', 'param', 'product_id'] as $key) {
            if (isset($_GET[$key]) && $_GET[$key] !== '') {
                return (int)$_GET[$key];
            }
        }
        return 0;
    }

    private function currentCount($userId) {
        return (int)$this->wishlistModel->getWishlistCount($userId);
    }

    private function currentCartCount($userId) {
        try {
            return (int)$this->cartModel->getCartCount($userId);
        } catch (Exception $e) {
            return 0;
        }
    }

    private function productPayload($product) {
        if (!$product) {
            return null;
        }
        $p = is_object($product) ? (array)$product : (array)$product;
        $stock = (float)($p['stock_quantity'] ?? 0);
        $price = !empty($p['price2']) ? (float)$p['price2'] : (!empty($p['sale_price']) ? (float)$p['sale_price'] : (float)($p['price'] ?? 0));
        return [
            'id' => (int)($p['id'] ?? 0),
            'name' => (string)($p['name'] ?? ''),
            'price' => $price,
            'stock_quantity' => $stock,
            'in_stock' => $stock > 0,
            'image' => $p['image'] ?? null,
            'status' => (string)($p['status'] ?? '')
        ];
    }

    /**
     * Wishlist page — grid with search, sort, pagination
     */
    public function index() {
        if (!$this->requireLoginForWishlist()) {
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $normalized = $this->wishlistModel->getUserWishlist($userId);

        $q = trim((string)$this->get('q', $this->get('keyword', '')));
        $sort = (string)$this->get('sort', 'newest');

        if ($q !== '') {
            $needle = mb_strtolower($q);
            $normalized = array_values(array_filter($normalized, function ($row) use ($needle) {
                $name = mb_strtolower((string)($row['name'] ?? ''));
                $sku = mb_strtolower((string)($row['sku'] ?? ''));
                return (strpos($name, $needle) !== false) || (strpos($sku, $needle) !== false);
            }));
        }

        usort($normalized, function ($a, $b) use ($sort) {
            $priceA = (float)(!empty($a['price2']) ? $a['price2'] : (!empty($a['sale_price']) ? $a['sale_price'] : ($a['price'] ?? 0)));
            $priceB = (float)(!empty($b['price2']) ? $b['price2'] : (!empty($b['sale_price']) ? $b['sale_price'] : ($b['price'] ?? 0)));
            $nameA = (string)($a['name'] ?? '');
            $nameB = (string)($b['name'] ?? '');
            $dateA = strtotime((string)($a['added_date'] ?? '')) ?: 0;
            $dateB = strtotime((string)($b['added_date'] ?? '')) ?: 0;

            switch ($sort) {
                case 'name_asc':
                    return strcasecmp($nameA, $nameB);
                case 'name_desc':
                    return strcasecmp($nameB, $nameA);
                case 'price_asc':
                    return $priceA <=> $priceB;
                case 'price_desc':
                    return $priceB <=> $priceA;
                case 'oldest':
                    return $dateA <=> $dateB;
                case 'newest':
                default:
                    return $dateB <=> $dateA;
            }
        });

        $perPage = 12;
        $page = max(1, (int)$this->get('page', 1));
        $total = count($normalized);
        $lastPage = max(1, (int)ceil($total / $perPage));
        if ($page > $lastPage) {
            $page = $lastPage;
        }
        $pageItems = array_slice($normalized, ($page - 1) * $perPage, $perPage);

        $this->setPageTitle('My Wishlist');
        $this->view('customer/wishlist/index', [
            'title' => 'My Wishlist',
            'pageTitle' => 'My Wishlist',
            'wishlistItems' => $pageItems,
            'wishlistTotal' => $total,
            'wishlistPage' => $page,
            'wishlistLastPage' => $lastPage,
            'wishlistQuery' => $q,
            'wishlistSort' => $sort
        ]);
    }

    /**
     * Add product to wishlist
     */
    public function add($productId = null) {
        if (!$this->requireLoginForWishlist()) {
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $productId = $this->resolveProductId($productId);
        $isAjax = $this->isAjax() || $this->wantsJson();

        if ($productId <= 0) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Product ID is required', 'count' => $this->currentCount($userId), 'in_wishlist' => false], 422);
            }
            flash('wishlist_error', 'Product ID is required', 'alert alert-danger');
            $this->redirect(rtrim(BASE_URL, '/') . '/?controller=wishlist');
            return;
        }

        $product = $this->productModel->getById($productId);
        if (!$product) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Product not found', 'count' => $this->currentCount($userId), 'in_wishlist' => false], 404);
            }
            flash('wishlist_error', 'Product not found', 'alert alert-danger');
            $this->redirect(rtrim(BASE_URL, '/') . '/?controller=shop');
            return;
        }

        $already = $this->wishlistModel->isInWishlist($userId, $productId);
        $success = $already ? true : $this->wishlistModel->addToWishlist($userId, $productId);
        $count = $this->currentCount($userId);
        $message = $already ? 'Already in Wishlist' : ($success ? 'Added to Wishlist' : 'Failed to add to Wishlist');

        if ($isAjax) {
            $this->json([
                'success' => (bool)$success,
                'count' => $count,
                'in_wishlist' => true,
                'action' => 'added',
                'product_id' => $productId,
                'product_ids' => $this->wishlistModel->getProductIds($userId),
                'message' => $message,
                'product' => $this->productPayload($product)
            ]);
        }

        flash('wishlist_success', $message, 'alert alert-success');
        $this->redirectBackOrWishlist();
    }

    /**
     * Remove product from wishlist
     */
    public function remove($productId = null) {
        if (!$this->requireLoginForWishlist()) {
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $productId = $this->resolveProductId($productId);
        $isAjax = $this->isAjax() || $this->wantsJson();

        if ($productId <= 0) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Product ID is required', 'count' => $this->currentCount($userId)], 422);
            }
            flash('wishlist_error', 'Product ID is required', 'alert alert-danger');
            $this->redirect(rtrim(BASE_URL, '/') . '/?controller=wishlist');
            return;
        }

        $success = $this->wishlistModel->removeFromWishlist($userId, $productId);
        $count = $this->currentCount($userId);
        $message = $success ? 'Removed from Wishlist' : 'Failed to remove from Wishlist';

        if ($isAjax) {
            $this->json([
                'success' => (bool)$success,
                'count' => $count,
                'in_wishlist' => false,
                'action' => 'removed',
                'product_id' => $productId,
                'product_ids' => $this->wishlistModel->getProductIds($userId),
                'message' => $message
            ]);
        }

        flash($success ? 'wishlist_removed' : 'wishlist_error', $message, $success ? 'alert alert-success' : 'alert alert-danger');
        $this->redirect(rtrim(BASE_URL, '/') . '/?controller=wishlist');
    }

    /**
     * Toggle wishlist (primary AJAX endpoint)
     */
    public function toggle($productId = null) {
        if (!$this->requireLoginForWishlist()) {
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $productId = $this->resolveProductId($productId);

        if ($productId <= 0) {
            $this->json(['success' => false, 'message' => 'Product ID is required', 'count' => $this->currentCount($userId)], 422);
            return;
        }

        $product = $this->productModel->getById($productId);
        if (!$product) {
            $this->json(['success' => false, 'message' => 'Product not found', 'count' => $this->currentCount($userId)], 404);
            return;
        }

        $result = $this->wishlistModel->toggle($userId, $productId);
        $count = $this->currentCount($userId);
        $message = ($result['action'] === 'added') ? 'Added to Wishlist' : 'Removed from Wishlist';

        $this->json([
            'success' => (bool)$result['success'],
            'count' => $count,
            'in_wishlist' => (bool)$result['in_wishlist'],
            'action' => $result['action'],
            'product_id' => $productId,
            'product_ids' => $this->wishlistModel->getProductIds($userId),
            'message' => $result['success'] ? $message : 'Wishlist update failed',
            'product' => $this->productPayload($product)
        ]);
    }

    /**
     * Live wishlist count (header badge)
     */
    public function count() {
        if (!isLoggedIn()) {
            $this->json(['success' => true, 'count' => 0, 'product_ids' => []]);
            return;
        }
        $userId = (int)$_SESSION['user_id'];
        $this->json([
            'success' => true,
            'count' => $this->currentCount($userId),
            'product_ids' => $this->wishlistModel->getProductIds($userId)
        ]);
    }

    /**
     * Clear entire wishlist
     */
    public function clear() {
        if (!$this->requireLoginForWishlist()) {
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $success = $this->wishlistModel->clear($userId);
        $isAjax = $this->isAjax() || $this->wantsJson();

        if ($isAjax) {
            $this->json([
                'success' => (bool)$success,
                'count' => 0,
                'in_wishlist' => false,
                'action' => 'cleared',
                'product_ids' => [],
                'message' => $success ? 'Wishlist cleared' : 'Failed to clear wishlist'
            ]);
        }

        flash($success ? 'wishlist_success' : 'wishlist_error',
            $success ? 'Wishlist cleared' : 'Failed to clear wishlist',
            $success ? 'alert alert-success' : 'alert alert-danger');
        $this->redirect(rtrim(BASE_URL, '/') . '/?controller=wishlist');
    }

    /**
     * Move wishlist item to cart, then remove from wishlist
     */
    public function moveToCart($productId = null) {
        if (!$this->requireLoginForWishlist()) {
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $productId = $this->resolveProductId($productId);
        $isAjax = $this->isAjax() || $this->wantsJson();
        $quantity = (int)$this->post('quantity', 1);
        if ($quantity < 1) {
            $quantity = 1;
        }

        if ($productId <= 0) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Product ID is required', 'count' => $this->currentCount($userId)], 422);
            }
            flash('wishlist_error', 'Product ID is required', 'alert alert-danger');
            $this->redirect(rtrim(BASE_URL, '/') . '/?controller=wishlist');
            return;
        }

        $product = $this->productModel->getById($productId);
        if (!$product) {
            if ($isAjax) {
                $this->json(['success' => false, 'message' => 'Product not found', 'count' => $this->currentCount($userId)], 404);
            }
            flash('wishlist_error', 'Product not found', 'alert alert-danger');
            $this->redirect(rtrim(BASE_URL, '/') . '/?controller=wishlist');
            return;
        }

        $p = is_array($product) ? $product : (array)$product;
        $stock = (float)($p['stock_quantity'] ?? 0);
        if ($stock < 1 || (($p['status'] ?? '') !== '' && ($p['status'] ?? '') !== 'active')) {
            if ($isAjax) {
                $this->json([
                    'success' => false,
                    'message' => 'Product is not available',
                    'count' => $this->currentCount($userId),
                    'cartCount' => $this->currentCartCount($userId)
                ], 422);
            }
            flash('wishlist_error', 'Product is not available', 'alert alert-danger');
            $this->redirect(rtrim(BASE_URL, '/') . '/?controller=wishlist');
            return;
        }

        if ($quantity > $stock) {
            $quantity = (int)$stock;
        }

        $added = $this->cartModel->addToCart($userId, $productId, $quantity);
        if ($added) {
            $this->wishlistModel->removeFromWishlist($userId, $productId);
        }

        $wishlistCount = $this->currentCount($userId);
        $cartCount = $this->currentCartCount($userId);

        if ($isAjax) {
            $this->json([
                'success' => (bool)$added,
                'count' => $wishlistCount,
                'cartCount' => $cartCount,
                'in_wishlist' => false,
                'action' => 'moved_to_cart',
                'product_id' => $productId,
                'product_ids' => $this->wishlistModel->getProductIds($userId),
                'message' => $added ? 'Moved to Cart' : 'Failed to move to cart'
            ]);
        }

        flash($added ? 'wishlist_success' : 'wishlist_error',
            $added ? 'Moved to Cart' : 'Failed to move to cart',
            $added ? 'alert alert-success' : 'alert alert-danger');
        $this->redirect(rtrim(BASE_URL, '/') . '/?controller=wishlist');
    }

    /**
     * JSON list of wishlist items / product IDs
     */
    public function getWishlist() {
        if (!$this->requireLoginForWishlist()) {
            return;
        }

        $userId = (int)$_SESSION['user_id'];
        $items = $this->wishlistModel->getUserWishlist($userId);
        $ids = $this->wishlistModel->getProductIds($userId);

        $this->json([
            'success' => true,
            'count' => count($ids),
            'product_ids' => $ids,
            'items' => $items
        ]);
    }

    /**
     * Product IDs only (for marking hearts on page load)
     */
    public function ids() {
        if (!isLoggedIn()) {
            $this->json(['success' => true, 'product_ids' => [], 'count' => 0]);
            return;
        }
        $userId = (int)$_SESSION['user_id'];
        $ids = $this->wishlistModel->getProductIds($userId);
        $this->json([
            'success' => true,
            'product_ids' => $ids,
            'count' => count($ids)
        ]);
    }

    private function redirectBackOrWishlist() {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $referer = $_SERVER['HTTP_REFERER'];
            $baseHost = parse_url(BASE_URL, PHP_URL_HOST);
            $refHost = parse_url($referer, PHP_URL_HOST);
            if ($baseHost && $refHost && strcasecmp($baseHost, $refHost) === 0) {
                header('Location: ' . $referer);
                exit;
            }
        }
        $this->redirect(rtrim(BASE_URL, '/') . '/?controller=wishlist');
    }
}
