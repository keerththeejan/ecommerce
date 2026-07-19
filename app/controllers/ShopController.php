<?php
/**
 * Shop Controller
 * Customer-facing shopfront routes for ?controller=shop
 *
 * Reuses ProductController business logic — no duplicate catalog logic.
 */
class ShopController extends ProductController {

    /**
     * Shop home / product listing
     * GET ?controller=shop  or  ?controller=shop&action=index
     */
    public function index() {
        parent::index();
    }

    /**
     * Products by category
     * GET ?controller=shop&action=category&param={id}
     *
     * @param mixed $categoryId
     */
    public function category($categoryId = null) {
        if ($categoryId === null || $categoryId === '') {
            $categoryId = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['param']) ? $_GET['param'] : null);
        }

        if ($categoryId === null || $categoryId === '') {
            $this->index();
            return;
        }

        parent::category($categoryId);
    }

    /**
     * Single product detail (shop alias for ProductController::show)
     * GET ?controller=shop&action=product&param={id}
     *
     * @param mixed $id
     */
    public function product($id = null) {
        if ($id === null || $id === '') {
            $id = isset($_GET['id']) ? $_GET['id'] : (isset($_GET['param']) ? $_GET['param'] : null);
        }

        if ($id === null || $id === '') {
            flash('product_error', 'Product not found', 'alert alert-danger');
            $this->redirect('?controller=shop&action=index');
            return;
        }

        parent::show($id);
    }

    /**
     * Product search
     * GET ?controller=shop&action=search&keyword=...
     */
    public function search() {
        parent::search();
    }

    /**
     * Graceful fallback for unknown shop actions
     */
    public function error($message = 'Page not found') {
        http_response_code(404);
        if (method_exists($this, 'view')) {
            $this->view('customer/products/index', [
                'products' => ['data' => [], 'current_page' => 1, 'last_page' => 1],
                'categories' => [],
                'error' => $message
            ]);
            return;
        }
        echo '404 - ' . htmlspecialchars((string)$message);
    }
}
