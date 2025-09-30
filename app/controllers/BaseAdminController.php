<?php
/**
 * BaseAdminController
 * Common functionality and access control for admin controllers
 */
class BaseAdminController extends Controller {
    public function __construct() {
        parent::__construct();
        // Basic auth guard for admin area
        if (!function_exists('isAdmin')) {
            require_once APP_PATH . 'helpers.php';
        }
        if (!isAdmin()) {
            redirect('admin/login');
        }
    }
}
