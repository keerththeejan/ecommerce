<?php
/**
 * Admin dashboard layout bridge (frontend only).
 *
 * AdminController still renders this view when modern=1.
 * The previous standalone modern shell broke sidebar/menus/overlay/JS contracts.
 * Reuse the proven header/footer shell + premium dashboard so all admin
 * interactions work again without changing controller/model logic.
 *
 * Optional: pass ?modern_content=1 to render dashboard-modern-content.php
 * inside the classic shell (for gradual UI experiments).
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/ecommerce/');
}

$useModernFragment = isset($_GET['modern_content']) && (string)$_GET['modern_content'] === '1';
$fragment = isset($content) ? $content : (APP_PATH . 'views/admin/dashboard-modern-content.php');

if ($useModernFragment && is_readable($fragment)) {
    require APP_PATH . 'views/admin/layouts/header.php';
    // Lightweight styles so modern fragment classes still look decent in classic shell
    echo '<link rel="stylesheet" href="' . htmlspecialchars(BASE_URL) . 'assets/css/admin-modern.css?v=' . (defined('ASSET_VERSION') ? ASSET_VERSION : time()) . '">';
    include $fragment;
    require APP_PATH . 'views/admin/layouts/footer.php';
    return;
}

// Default: full working premium dashboard (includes header + footer)
require APP_PATH . 'views/admin/dashboard.php';
