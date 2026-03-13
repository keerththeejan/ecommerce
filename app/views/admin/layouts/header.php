<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token if it doesn't exist
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Track user activity
if (isset($_SESSION['user_id'])) {
    // Get user's IP address
    $ip = $_SERVER['REMOTE_ADDR'];

    // Get user agent
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    // Update last activity time in session
    $_SESSION['last_activity'] = date('Y-m-d H:i:s');

    // Update last activity in database
    try {
        $userModel = new User();
        $userModel->updateActivity($_SESSION['user_id'], $ip, $userAgent);
    } catch (Exception $e) {
        // Log error but don't show to user
        error_log('Error updating user activity: ' . $e->getMessage());
    }
}
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/ecommerce/');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
    <!-- Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS (cache-busted) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css?v=<?php echo time(); ?>">
    <style>
        /* Theme tokens (Light defaults) */
        :root,
        [data-theme="light"],
        [data-theme="light"] body {
            --text-color: #111827;
            --bg-color: #f8f9fa;
            --surface-color: #ffffff;
            --surface-muted: #f8f9fa;
            --muted-color: #6b7280;
            --border-color: rgba(17, 24, 39, 0.10);
            --icon-color: #374151;

            /* Sidebar (keep modern dark sidebar even in light mode) */
            --sidebar-bg: #0f172a;
            --sidebar-text: rgba(255,255,255,0.92);
            --sidebar-text-muted: rgba(248,249,250,0.72);
            --sidebar-surface: rgba(255,255,255,0.06);
            --sidebar-surface-hover: rgba(255,255,255,0.10);
            --sidebar-border: rgba(255,255,255,0.08);
            --sidebar-icon: rgba(255,255,255,0.88);
        }
        
        /* Dark theme overrides (custom data-theme for Bootstrap 4 compatibility) */
        [data-theme="dark"],
        [data-theme="dark"] body {
            /* Preserve existing dark mode look */
            --text-color: #f8f9fa;
            --bg-color: #212529;
            --surface-color: #2c3034;
            --surface-muted: #2c3034;
            --muted-color: rgba(248,249,250,0.72);
            --border-color: #495057;
            --icon-color: rgba(248,249,250,0.90);

            --sidebar-bg: #1a1e21;
            --sidebar-text: #f8f9fa;
            --sidebar-text-muted: rgba(248,249,250,0.72);
            --sidebar-surface: rgba(255,255,255,0.06);
            --sidebar-surface-hover: rgba(255,255,255,0.10);
            --sidebar-border: rgba(255,255,255,0.08);
            --sidebar-icon: rgba(255,255,255,0.88);
            color: var(--text-color) !important;
            background-color: var(--bg-color) !important;
        }
        
        /* Apply theme colors */
        body {
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
            color: var(--text-color);
            background-color: var(--bg-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Global surfaces/text (prevents invisible labels in light mode) */
        .card,
        .card-header,
        .card-body,
        .table,
        .table th,
        .table td,
        .alert,
        .list-group-item,
        .modal-content {
            color: var(--text-color);
        }

        .card,
        .modal-content,
        .dropdown-menu,
        .list-group-item {
            background-color: var(--surface-color);
            border-color: var(--border-color);
        }

        .text-muted,
        small.text-muted {
            color: var(--muted-color) !important;
        }

        /* Fix: light mode pages that use text-white/text-light (from dark-mode styling) */
        :not([data-theme="dark"]) .text-white,
        :not([data-theme="dark"]) .text-light {
            color: var(--text-color) !important;
        }
        
        /* Light mode explicit text classes */
        :not([data-theme="dark"]) .text-body,
        :not([data-theme="dark"]) .text-dark {
            color: var(--text-color) !important;
        }
        
        /* Force text color in dark mode */
        [data-theme="dark"],
        [data-theme="dark"] body,
        [data-theme="dark"] .card,
        [data-theme="dark"] .card-header,
        [data-theme="dark"] .card-body,
        [data-theme="dark"] .table,
        [data-theme="dark"] .table th,
        [data-theme="dark"] .table td,
        [data-theme="dark"] .text-dark,
        [data-theme="dark"] .text-body,
        [data-theme="dark"] .text-muted,
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-label,
        [data-theme="dark"] .dropdown-menu,
        [data-theme="dark"] .dropdown-item {
            color: #f8f9fa !important;
        }
        
        /* Force background colors in dark mode */
        [data-theme="dark"] .bg-white,
        [data-theme="dark"] .bg-light,
        [data-theme="dark"] .card,
        [data-theme="dark"] .card-header,
        [data-theme="dark"] .card-body,
        [data-theme="dark"] .table,
        [data-theme="dark"] .table th,
        [data-theme="dark"] .table td,
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .dropdown-menu {
            background-color: #2c3034 !important;
            border-color: #495057 !important;
        }
        
        /* Table specific styles for dark mode */
        [data-theme="dark"] .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(255, 255, 255, 0.05);
            --bs-table-hover-bg: rgba(255, 255, 255, 0.1);
        }
        
        [data-theme="dark"] .table > :not(:last-child) > :last-child > * {
            border-bottom-color: #495057;
        }
        
        /* Sidebar theming */
        #sidebar {
            background-color: var(--sidebar-bg);
        }

        /* Modern sidebar UI (8px grid) */
        :root {
            --sidebar-width: 280px;
            --sidebar-width-collapsed: 84px;
            --sidebar-accent: #3b82f6; /* blue */
        }

        #sidebar {
            width: var(--sidebar-width);
        }

        body.sidebar-collapsed #sidebar {
            width: var(--sidebar-width-collapsed);
        }

        @media (min-width: 768px) {
            main {
                margin-left: var(--sidebar-width);
            }
            body.sidebar-collapsed main {
                margin-left: var(--sidebar-width-collapsed);
            }
        }

        .admin-sidebar {
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .admin-sidebar__brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            gap: 12px;
            border-bottom: 1px solid var(--sidebar-border);
        }

        .admin-sidebar__brand a {
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 600;
            letter-spacing: -0.01em;
            line-height: 1.2;
        }

        .admin-sidebar__brand .brand-subtitle {
            display: block;
            color: var(--sidebar-text-muted);
            font-weight: 500;
            font-size: 12px;
            margin-top: 2px;
        }

        .admin-sidebar__scroll {
            flex: 1;
            overflow: auto;
            padding: 12px 12px 80px;
            overscroll-behavior: contain;
        }

        .admin-sidebar__section {
            margin-top: 16px;
        }

        .admin-sidebar__section-title {
            color: var(--sidebar-text-muted);
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 8px 8px;
            margin-bottom: 8px;
        }

        .admin-nav-item {
            margin-bottom: 4px;
        }

        .admin-nav-link,
        .admin-nav-trigger {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            color: var(--sidebar-text);
            background: transparent;
            border: 0;
            text-decoration: none;
            outline: none;
            transition: background-color 180ms ease, color 180ms ease, transform 180ms ease;
        }

        .admin-nav-link:hover,
        .admin-nav-link:focus,
        .admin-nav-trigger:hover,
        .admin-nav-trigger:focus {
            background: var(--sidebar-surface-hover);
            color: #fff;
            text-decoration: none;
        }

        .admin-nav-link:active,
        .admin-nav-trigger:active {
            transform: translateY(1px);
        }

        .admin-nav-link.active,
        .admin-nav-trigger.active {
            background: rgba(59,130,246,0.22);
            box-shadow: inset 0 0 0 1px rgba(59,130,246,0.35);
            color: #fff;
        }

        .admin-nav-icon {
            width: 20px;
            height: 20px;
            color: var(--sidebar-icon);
            flex: 0 0 auto;
        }

        .admin-nav-text {
            font-weight: 500;
            font-size: 14px;
            line-height: 1.2;
            flex: 1 1 auto;
            text-align: left;
        }

        .admin-nav-caret {
            width: 18px;
            height: 18px;
            opacity: 0.75;
            transition: transform 180ms ease;
            flex: 0 0 auto;
        }

        .admin-nav-trigger[aria-expanded="true"] .admin-nav-caret {
            transform: rotate(90deg);
        }

        .admin-submenu {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 240ms ease, opacity 200ms ease;
            margin: 4px 0 8px;
            padding-left: 8px;
        }

        .admin-submenu.show {
            opacity: 1;
        }

        .admin-submenu .admin-nav-link {
            padding: 8px 12px;
            border-radius: 10px;
            color: rgba(255,255,255,0.84);
        }

        .admin-submenu .admin-nav-link .admin-nav-icon {
            width: 18px;
            height: 18px;
            opacity: 0.9;
        }

        /* Logout styling */
        .admin-nav-link.danger {
            color: rgba(255,255,255,0.92);
            background: rgba(239,68,68,0.10);
        }

        .admin-nav-link.danger:hover,
        .admin-nav-link.danger:focus {
            background: rgba(239,68,68,0.18);
        }

        /* Collapsed mini-icon sidebar mode */
        body.sidebar-collapsed .admin-nav-text,
        body.sidebar-collapsed .admin-sidebar__section-title,
        body.sidebar-collapsed .brand-text-block,
        body.sidebar-collapsed .admin-nav-caret {
            display: none !important;
        }

        body.sidebar-collapsed .admin-sidebar__brand {
            justify-content: center;
        }

        body.sidebar-collapsed .admin-nav-link,
        body.sidebar-collapsed .admin-nav-trigger {
            justify-content: center;
            padding: 10px 10px;
        }

        body.sidebar-collapsed .admin-submenu {
            display: none !important;
        }

        /* Light theme sidebar tweaks (intentionally dark sidebar; keep consistent) */
        :not([data-theme="dark"]) #sidebar {
            background: var(--sidebar-bg);
        }

        /* Light mode sidebar - force white text for visibility */
        :not([data-theme="dark"]) .admin-sidebar__section-title {
            color: rgba(255,255,255,0.72) !important;
        }

        :not([data-theme="dark"]) .admin-nav-link,
        :not([data-theme="dark"]) .admin-nav-trigger {
            color: rgba(255,255,255,0.92) !important;
        }

        :not([data-theme="dark"]) .admin-nav-icon {
            color: rgba(255,255,255,0.88) !important;
        }

        :not([data-theme="dark"]) .admin-nav-text {
            color: rgba(255,255,255,0.92) !important;
        }

        :not([data-theme="dark"]) .admin-sidebar__brand a {
            color: rgba(255,255,255,0.92) !important;
        }

        :not([data-theme="dark"]) .admin-sidebar__brand .brand-subtitle {
            color: rgba(255,255,255,0.72) !important;
        }

        :not([data-theme="dark"]) .admin-submenu .admin-nav-link {
            color: rgba(255,255,255,0.84) !important;
        }

        /* Custom scrollbar for sidebar menu */
        .admin-sidebar__scroll { scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.22) transparent; }
        .admin-sidebar__scroll::-webkit-scrollbar{ width:10px; height:10px; }
        .admin-sidebar__scroll::-webkit-scrollbar-thumb{ background: rgba(255,255,255,0.22); border-radius: 10px; border: 2px solid transparent; background-clip: padding-box; }
        .admin-sidebar__scroll::-webkit-scrollbar-thumb:hover{ background: rgba(255,255,255,0.30); background-clip: padding-box; }
        .admin-sidebar__scroll::-webkit-scrollbar-track{ background: transparent; }
        
        /* Ensure last items in sidebar are reachable */
        #sidebar .position-sticky { 
            padding-bottom: 80px; 
            box-sizing: border-box; 
        }
        /* Sidebar dropdowns: visible sub-menus, open to the right, high contrast */
        #sidebar .dropdown-menu {
            background: #2c3034 !important;
            border: 1px solid #495057;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,.3);
            padding: 0.25rem;
            min-width: 200px;
            margin-top: 0;
            margin-left: 0.25rem;
            z-index: 1060;
        }
        #sidebar .dropdown-item {
            color: #f8f9fa !important;
            padding: 0.5rem 0.85rem;
            border-radius: 6px;
            font-size: 0.9rem;
        }
        #sidebar .dropdown-item:hover,
        #sidebar .dropdown-item:focus {
            background: rgba(255,255,255,.15) !important;
            color: #fff !important;
        }

        /* Scrollbar styling - Sidebar and Main */
        /* Firefox */
        #sidebar .position-sticky { scrollbar-width: thin; scrollbar-color: #6c757d rgba(255,255,255,0.06); }
        main { scrollbar-width: thin; scrollbar-color: #adb5bd rgba(0,0,0,0.06); }

        /* WebKit (Chrome, Edge, Safari) */
        #sidebar .position-sticky::-webkit-scrollbar,
        main::-webkit-scrollbar { width: 10px; height: 10px; }

        #sidebar .position-sticky::-webkit-scrollbar-track { background: #1f2429; border-radius: 8px; }
        #sidebar .position-sticky::-webkit-scrollbar-thumb {
            background: #5a6b7a; border-radius: 8px; border: 2px solid #1f2429;
        }
        #sidebar .position-sticky::-webkit-scrollbar-thumb:hover { background: #7b8ea1; }

        main::-webkit-scrollbar-track { background: #e9ecef; border-radius: 8px; }
        main::-webkit-scrollbar-thumb {
            background: #adb5bd; border-radius: 8px; border: 2px solid #e9ecef;
        }
        main::-webkit-scrollbar-thumb:hover { background: #9099a2; }

        /* Bootstrap 4 responsive: mobile (< 768px) - sidebar offcanvas */
        @media (max-width: 767.98px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px;
                max-width: 85vw;
                z-index: 1050;
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
                background-color: #212529;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 4px 0 12px rgba(0,0,0,0.15);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            body.sidebar-open {
                overflow: hidden;
            }
            .sidebar-backdrop {
                position: fixed;
                top: 0; left: 0; right: 0; bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1049;
                display: none;
                opacity: 0;
                transition: opacity 0.2s ease;
            }
            .sidebar-backdrop.active {
                display: block;
                opacity: 1;
            }
            #sidebar .nav-link {
                padding: 0.65rem 1rem;
                font-size: 0.95rem;
            }
        }

        /* Desktop: >= 768px - sidebar always visible, fixed left */
        @media (min-width: 768px) {
            html, body { height: 100%; }
            body { overflow: hidden; }
            #sidebar.collapse {
                display: block !important;
                visibility: visible;
            }
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: var(--sidebar-width);
                overflow: visible;
                transform: none;
                overscroll-behavior: contain;
            }
            #sidebar .position-sticky { height: 100vh; overflow: auto; overscroll-behavior: contain; }
            main {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
                height: 100vh;
                overflow: auto;
                overscroll-behavior: contain;
                padding-bottom: 32px;
                box-sizing: border-box;
            }
            body.sidebar-collapsed main {
                margin-left: var(--sidebar-width-collapsed);
                width: calc(100% - var(--sidebar-width-collapsed));
            }
        }
    </style>
    <!-- jQuery and Bootstrap 4 in head so inline scripts can use them -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</head>

<body>
    <?php
        // Determine current route for active highlighting
        $currentController = strtolower((string)($_GET['controller'] ?? ''));
        $currentAction = strtolower((string)($_GET['action'] ?? ''));

        $isActive = function($controller, $action = null) use ($currentController, $currentAction) {
            $controller = strtolower((string)$controller);
            $action = $action === null ? null : strtolower((string)$action);
            if ($controller !== '' && $controller !== $currentController) return false;
            if ($action !== null && $action !== $currentAction) return false;
            return true;
        };

        $isAnyActive = function($items) use ($isActive) {
            if (!is_array($items)) return false;
            foreach ($items as $it) {
                $c = $it['controller'] ?? '';
                $a = $it['action'] ?? null;
                if ($isActive($c, $a)) return true;
            }
            return false;
        };
    ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="bg-dark sidebar collapse" role="navigation" aria-label="Admin Sidebar">
                <div class="admin-sidebar">
                    <!-- Brand / header -->
                    <div class="admin-sidebar__brand">
                        <a href="<?php echo BASE_URL; ?>?controller=home&action=admin" class="d-flex align-items-center gap-2">
                            <span class="brand-text-block">
                                <span style="display:block; font-size:14px;">Admin Dashboard</span>
                                <span class="brand-subtitle">Control Panel</span>
                            </span>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-light d-none d-md-inline-flex" id="sidebarCollapseBtn" aria-label="Toggle sidebar" title="Toggle sidebar">
                            <span data-lucide="panel-left" class="admin-nav-icon"></span>
                        </button>
                    </div>

                    <!-- Scrollable menu -->
                    <div class="admin-sidebar__scroll" id="adminSidebarScroll" tabindex="0">
                        <?php
                            $mainItems = [
                                [
                                    'type' => 'link',
                                    'label' => 'Dashboard',
                                    'icon' => 'layout-dashboard',
                                    'href' => BASE_URL . '?controller=home&action=admin',
                                    'active' => $isActive('home', 'admin') || ($currentController === '' && $currentAction === ''),
                                ],
                            ];

                            $managementGroups = [
                                [
                                    'label' => 'Products',
                                    'icon' => 'package',
                                    'items' => [
                                        ['label' => 'All Products', 'icon' => 'list', 'href' => BASE_URL . '?controller=product&action=adminIndex', 'controller' => 'product', 'action' => 'adminindex'],
                                        ['label' => 'Add Product', 'icon' => 'plus', 'href' => BASE_URL . '?controller=product&action=create', 'controller' => 'product', 'action' => 'create'],
                                    ],
                                ],
                                [
                                    'label' => 'Categories',
                                    'icon' => 'tags',
                                    'items' => [
                                        ['label' => 'All Categories', 'icon' => 'list', 'href' => BASE_URL . '?controller=category&action=adminIndex', 'controller' => 'category', 'action' => 'adminindex'],
                                        ['label' => 'Add Category', 'icon' => 'plus', 'href' => BASE_URL . '?controller=category&action=create', 'controller' => 'category', 'action' => 'create'],
                                    ],
                                ],
                                [
                                    'label' => 'Brands',
                                    'icon' => 'building-2',
                                    'items' => [
                                        ['label' => 'All Brands', 'icon' => 'list', 'href' => BASE_URL . '?controller=brand&action=adminIndex', 'controller' => 'brand', 'action' => 'adminindex'],
                                        ['label' => 'Add Brand', 'icon' => 'plus', 'href' => BASE_URL . '?controller=brand&action=create', 'controller' => 'brand', 'action' => 'create'],
                                    ],
                                ],
                                [
                                    'label' => 'Suppliers',
                                    'icon' => 'truck',
                                    'items' => [
                                        ['label' => 'List Suppliers', 'icon' => 'list', 'href' => BASE_URL . '?controller=supplier&action=index', 'controller' => 'supplier', 'action' => 'index'],
                                    ],
                                ],
                                [
                                    'label' => 'Orders',
                                    'icon' => 'shopping-cart',
                                    'items' => [
                                        ['label' => 'All Orders', 'icon' => 'list', 'href' => BASE_URL . '?controller=order&action=adminIndex', 'controller' => 'order', 'action' => 'adminindex'],
                                    ],
                                ],
                                [
                                    'label' => 'Users',
                                    'icon' => 'users',
                                    'items' => [
                                        ['label' => 'All Users', 'icon' => 'list', 'href' => BASE_URL . '?controller=user&action=adminIndex', 'controller' => 'user', 'action' => 'adminindex'],
                                        ['label' => 'Add User', 'icon' => 'plus', 'href' => BASE_URL . '?controller=user&action=adminCreate', 'controller' => 'user', 'action' => 'admincreate'],
                                        ['label' => 'Customers', 'icon' => 'user-round', 'href' => BASE_URL . '?controller=user&action=customers', 'controller' => 'user', 'action' => 'customers'],
                                    ],
                                ],
                                [
                                    'label' => 'Purchase',
                                    'icon' => 'credit-card',
                                    'items' => [
                                        ['label' => 'Purchase with customer', 'icon' => 'user-round', 'href' => BASE_URL . '?controller=purchase&action=create', 'controller' => 'purchase', 'action' => 'create'],
                                        ['label' => 'List Purchase', 'icon' => 'list', 'href' => BASE_URL . '?controller=ListPurchaseController', 'controller' => 'listpurchasecontroller', 'action' => null],
                                        ['label' => 'Add Purchase', 'icon' => 'plus', 'href' => BASE_URL . '?controller=purchase&action=purchase2', 'controller' => 'purchase', 'action' => 'purchase2'],
                                        ['label' => 'List Purchase Return', 'icon' => 'rotate-ccw', 'href' => BASE_URL . '?controller=purchase&action=purchase3', 'controller' => 'purchase', 'action' => 'purchase3'],
                                    ],
                                ],
                            ];

                            $systemGroups = [
                                [
                                    'label' => 'Reports',
                                    'icon' => 'bar-chart-3',
                                    'items' => [
                                        ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'href' => BASE_URL . '?controller=report&action=index', 'controller' => 'report', 'action' => 'index'],
                                        ['label' => 'Sales Report', 'icon' => 'trending-up', 'href' => BASE_URL . '?controller=report&action=sales', 'controller' => 'report', 'action' => 'sales'],
                                        ['label' => 'Products Report', 'icon' => 'package', 'href' => BASE_URL . '?controller=report&action=products', 'controller' => 'report', 'action' => 'products'],
                                        ['label' => 'Customers Report', 'icon' => 'users', 'href' => BASE_URL . '?controller=report&action=customers', 'controller' => 'report', 'action' => 'customers'],
                                    ],
                                ],
                                [
                                    'label' => 'Settings',
                                    'icon' => 'settings',
                                    'items' => [
                                        ['label' => 'Store Settings', 'icon' => 'store', 'href' => BASE_URL . '?controller=setting&action=index', 'controller' => 'setting', 'action' => 'index'],
                                    ],
                                ],
                                [
                                    'label' => 'Tax Management',
                                    'icon' => 'percent',
                                    'items' => [
                                        ['label' => 'Tax Settings', 'icon' => 'percent', 'href' => BASE_URL . '?controller=tax&action=index', 'controller' => 'tax', 'action' => 'index'],
                                    ],
                                ],
                                [
                                    'label' => 'Store',
                                    'icon' => 'shopping-bag',
                                    'items' => [
                                        ['label' => 'View Store', 'icon' => 'external-link', 'href' => BASE_URL, 'controller' => '', 'action' => null],
                                        ['label' => 'Manage About Store', 'icon' => 'file-text', 'href' => BASE_URL . '?controller=aboutStore', 'controller' => 'aboutstore', 'action' => null],
                                        ['label' => 'Add About Entry', 'icon' => 'plus', 'href' => BASE_URL . '?controller=aboutStore&action=create', 'controller' => 'aboutstore', 'action' => 'create'],
                                    ],
                                ],
                                [
                                    'label' => 'Contact Info',
                                    'icon' => 'contact',
                                    'items' => [
                                        ['label' => 'Contact Info', 'icon' => 'contact', 'href' => BASE_URL . '?controller=contactinfo&action=index', 'controller' => 'contactinfo', 'action' => 'index'],
                                    ],
                                ],
                                [
                                    'label' => 'Payment Dues',
                                    'icon' => 'wallet',
                                    'items' => [
                                        ['label' => 'Payment Dues', 'icon' => 'wallet', 'href' => BASE_URL . '?controller=PaymentDue&action=index', 'controller' => 'paymentdue', 'action' => 'index'],
                                    ],
                                ],
                                [
                                    'label' => 'Clear Cookies',
                                    'icon' => 'trash-2',
                                    'items' => [
                                        ['label' => 'Clear All Cookies', 'icon' => 'trash-2', 'href' => '#', 'controller' => '', 'action' => null, 'id' => 'clearCookiesBtn'],
                                    ],
                                ],
                            ];
                        ?>

                        <div class="admin-sidebar__section">
                            <div class="admin-sidebar__section-title">Main</div>
                            <?php foreach ($mainItems as $it): ?>
                                <div class="admin-nav-item">
                                    <a class="admin-nav-link <?php echo !empty($it['active']) ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($it['href']); ?>" aria-current="<?php echo !empty($it['active']) ? 'page' : 'false'; ?>">
                                        <span data-lucide="<?php echo htmlspecialchars($it['icon']); ?>" class="admin-nav-icon" aria-hidden="true"></span>
                                        <span class="admin-nav-text"><?php echo htmlspecialchars($it['label']); ?></span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="admin-sidebar__section">
                            <div class="admin-sidebar__section-title">Management</div>
                            <?php foreach ($managementGroups as $idx => $group): ?>
                                <?php
                                    $groupActive = $isAnyActive($group['items']);
                                    $submenuId = 'submenu_mgmt_' . $idx;
                                ?>
                                <div class="admin-nav-item">
                                    <button type="button" class="admin-nav-trigger <?php echo $groupActive ? 'active' : ''; ?>" aria-expanded="<?php echo $groupActive ? 'true' : 'false'; ?>" aria-controls="<?php echo htmlspecialchars($submenuId); ?>" data-submenu-toggle>
                                        <span data-lucide="<?php echo htmlspecialchars($group['icon']); ?>" class="admin-nav-icon" aria-hidden="true"></span>
                                        <span class="admin-nav-text"><?php echo htmlspecialchars($group['label']); ?></span>
                                        <span data-lucide="chevron-right" class="admin-nav-caret" aria-hidden="true"></span>
                                    </button>
                                    <div id="<?php echo htmlspecialchars($submenuId); ?>" class="admin-submenu <?php echo $groupActive ? 'show' : ''; ?>" role="region" aria-label="<?php echo htmlspecialchars($group['label']); ?> submenu">
                                        <?php foreach ($group['items'] as $sub): ?>
                                            <?php
                                                $subActive = $isActive($sub['controller'] ?? '', $sub['action'] ?? null);
                                                $subId = $sub['id'] ?? '';
                                            ?>
                                            <a class="admin-nav-link <?php echo $subActive ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($sub['href']); ?>" <?php echo $subId !== '' ? 'id="' . htmlspecialchars($subId) . '"' : ''; ?> aria-current="<?php echo $subActive ? 'page' : 'false'; ?>">
                                                <span data-lucide="<?php echo htmlspecialchars($sub['icon']); ?>" class="admin-nav-icon" aria-hidden="true"></span>
                                                <span class="admin-nav-text"><?php echo htmlspecialchars($sub['label']); ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="admin-sidebar__section">
                            <div class="admin-sidebar__section-title">System</div>
                            <?php foreach ($systemGroups as $idx => $group): ?>
                                <?php
                                    $groupActive = $isAnyActive($group['items']);
                                    $submenuId = 'submenu_sys_' . $idx;
                                ?>
                                <div class="admin-nav-item">
                                    <button type="button" class="admin-nav-trigger <?php echo $groupActive ? 'active' : ''; ?>" aria-expanded="<?php echo $groupActive ? 'true' : 'false'; ?>" aria-controls="<?php echo htmlspecialchars($submenuId); ?>" data-submenu-toggle>
                                        <span data-lucide="<?php echo htmlspecialchars($group['icon']); ?>" class="admin-nav-icon" aria-hidden="true"></span>
                                        <span class="admin-nav-text"><?php echo htmlspecialchars($group['label']); ?></span>
                                        <span data-lucide="chevron-right" class="admin-nav-caret" aria-hidden="true"></span>
                                    </button>
                                    <div id="<?php echo htmlspecialchars($submenuId); ?>" class="admin-submenu <?php echo $groupActive ? 'show' : ''; ?>" role="region" aria-label="<?php echo htmlspecialchars($group['label']); ?> submenu">
                                        <?php foreach ($group['items'] as $sub): ?>
                                            <?php
                                                $subActive = $isActive($sub['controller'] ?? '', $sub['action'] ?? null);
                                                $subId = $sub['id'] ?? '';
                                            ?>
                                            <a class="admin-nav-link <?php echo $subActive ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($sub['href']); ?>" <?php echo $subId !== '' ? 'id="' . htmlspecialchars($subId) . '"' : ''; ?> aria-current="<?php echo $subActive ? 'page' : 'false'; ?>">
                                                <span data-lucide="<?php echo htmlspecialchars($sub['icon']); ?>" class="admin-nav-icon" aria-hidden="true"></span>
                                                <span class="admin-nav-text"><?php echo htmlspecialchars($sub['label']); ?></span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <div class="admin-nav-item" style="margin-top: 12px;">
                                <a class="admin-nav-link danger" href="<?php echo BASE_URL; ?>?controller=user&action=logout" aria-label="Logout">
                                    <span data-lucide="log-out" class="admin-nav-icon" aria-hidden="true"></span>
                                    <span class="admin-nav-text">Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <script>
                                document.getElementById('clearCookiesBtn').addEventListener('click', function(e) {
                                    e.preventDefault();
                                    if (confirm('Are you sure you want to clear all cookies? This will log out all users.')) {
                                        fetch('<?php echo BASE_URL; ?>?controller=home&action=clearCookies', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-Requested-With': 'XMLHttpRequest'
                                                },
                                                credentials: 'same-origin'
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    // Show success message
                                                    const alertDiv = document.createElement('div');
                                                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                                                    alertDiv.role = 'alert';
                                                    alertDiv.innerHTML = data.message + ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';

                                                    var mainContent = document.querySelector('main');
                                                    if (mainContent) {
                                                        mainContent.insertBefore(alertDiv, mainContent.firstChild);
                                                        setTimeout(function() {
                                                            $(alertDiv).alert('close');
                                                        }, 5000);
                                                    }
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                alert('An error occurred while clearing cookies.');
                                            });
                                    }
                                });
            </script>

            <!-- Backdrop for mobile -->
            <div class="sidebar-backdrop d-md-none" id="sidebarBackdrop"></div>

            <!-- Main Content -->
            <main class="px-2 px-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
                    <div class="d-flex align-items-center mb-2 mb-sm-0">
                        <button class="btn btn-outline-secondary mr-3 d-md-none" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation" id="sidebarToggleBtn">
                            <i class="fas fa-bars"></i>
                        </button>
                        <button class="btn btn-outline-secondary mr-2 d-none d-md-inline-flex" type="button" aria-label="Collapse sidebar" id="sidebarCollapseBtnTop">
                            <span data-lucide="panel-left" class="admin-nav-icon" aria-hidden="true"></span>
                        </button>
                        <h1 class="h2 mb-0">Admin Dashboard</h1>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <!-- Dark/Light Mode Toggle -->
                        <button class="btn btn-sm btn-outline-secondary mr-2" type="button" id="themeToggle">
                            <i class="fas fa-moon mr-1"></i> <span>Dark Mode</span>
                        </button>
                        
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user mr-1"></i> <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=profile">My Profile</a></li>
                                <li><div class="dropdown-divider"></div></li>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=logout">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <script src="https://unpkg.com/lucide@0.454.0/dist/umd/lucide.min.js"></script>
                <script>
                    // Sidebar: mobile drawer toggle (Bootstrap 4 collapse + custom backdrop)
                    document.addEventListener('DOMContentLoaded', function() {
                        try {
                            if (window.lucide && typeof window.lucide.createIcons === 'function') {
                                window.lucide.createIcons();
                            }
                        } catch (e) { /* ignore */ }

                        var body = document.body;
                        var sidebar = document.getElementById('sidebar');
                        var toggleBtn = document.getElementById('sidebarToggleBtn');
                        var backdrop = document.getElementById('sidebarBackdrop');

                        function openSidebar() {
                            if (!sidebar) return;
                            sidebar.classList.add('show');
                            body.classList.add('sidebar-open');
                            if (backdrop) backdrop.classList.add('active');
                            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
                            sidebar.setAttribute('aria-hidden', 'false');
                        }

                        function closeSidebar() {
                            if (!sidebar) return;
                            sidebar.classList.remove('show');
                            body.classList.remove('sidebar-open');
                            if (backdrop) backdrop.classList.remove('active');
                            if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
                            sidebar.setAttribute('aria-hidden', 'true');
                        }

                        function isMobile() {
                            return window.innerWidth < 768;
                        }

                        if (toggleBtn) {
                            toggleBtn.addEventListener('click', function(e) {
                                if (!isMobile()) return;
                                e.preventDefault();
                                if (sidebar && sidebar.classList.contains('show')) closeSidebar();
                                else openSidebar();
                            });
                        }

                        if (backdrop) {
                            backdrop.addEventListener('click', function() {
                                closeSidebar();
                            });
                        }

                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape' && isMobile()) {
                                closeSidebar();
                            }
                        });

                        // Accordion submenu
                        function setSubmenuState(trigger, open) {
                            if (!trigger) return;
                            var id = trigger.getAttribute('aria-controls');
                            var submenu = id ? document.getElementById(id) : null;
                            trigger.setAttribute('aria-expanded', open ? 'true' : 'false');
                            if (!submenu) return;
                            if (open) {
                                submenu.classList.add('show');
                                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                            } else {
                                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                                requestAnimationFrame(function() {
                                    submenu.classList.remove('show');
                                    submenu.style.maxHeight = '0px';
                                });
                            }
                        }

                        function closeOtherSubmenus(currentTrigger) {
                            document.querySelectorAll('[data-submenu-toggle]').forEach(function(t) {
                                if (t !== currentTrigger) {
                                    setSubmenuState(t, false);
                                }
                            });
                        }

                        document.querySelectorAll('[data-submenu-toggle]').forEach(function(trigger) {
                            var expanded = trigger.getAttribute('aria-expanded') === 'true';
                            setSubmenuState(trigger, expanded);

                            trigger.addEventListener('click', function(e) {
                                e.preventDefault();
                                if (body.classList.contains('sidebar-collapsed') && !isMobile()) {
                                    return;
                                }
                                var isOpen = trigger.getAttribute('aria-expanded') === 'true';
                                closeOtherSubmenus(trigger);
                                setSubmenuState(trigger, !isOpen);
                            });

                            trigger.addEventListener('keydown', function(e) {
                                if (e.key === 'Enter' || e.key === ' ') {
                                    e.preventDefault();
                                    trigger.click();
                                }
                            });
                        });

                        // Desktop collapsed mini mode (persist)
                        var collapseBtns = [
                            document.getElementById('sidebarCollapseBtn'),
                            document.getElementById('sidebarCollapseBtnTop')
                        ].filter(Boolean);

                        function applyCollapsedState(state) {
                            if (state) body.classList.add('sidebar-collapsed');
                            else body.classList.remove('sidebar-collapsed');
                            try { localStorage.setItem('adminSidebarCollapsed', state ? '1' : '0'); } catch (e) { /* ignore */ }
                        }

                        (function initCollapsed() {
                            if (isMobile()) {
                                applyCollapsedState(false);
                                return;
                            }
                            var saved = '0';
                            try { saved = localStorage.getItem('adminSidebarCollapsed') || '0'; } catch (e) { saved = '0'; }
                            applyCollapsedState(saved === '1');
                        })();

                        collapseBtns.forEach(function(btn) {
                            btn.addEventListener('click', function(e) {
                                e.preventDefault();
                                if (isMobile()) return;
                                applyCollapsedState(!body.classList.contains('sidebar-collapsed'));
                            });
                        });

                        window.addEventListener('resize', function() {
                            if (isMobile()) {
                                applyCollapsedState(false);
                                closeSidebar();
                            }
                        });

                        // Close drawer after clicking any link on mobile
                        document.querySelectorAll('#sidebar a.admin-nav-link').forEach(function(a) {
                            a.addEventListener('click', function() {
                                if (isMobile()) closeSidebar();
                            });
                        });
                    });
                </script>

                <!-- Flash Messages -->
                <?php flash('product_success'); ?>
                <?php flash('product_error'); ?>
                <?php flash('category_success'); ?>
                <?php flash('category_error'); ?>
                <?php flash('order_success'); ?>
                <?php flash('order_error'); ?>
                <?php flash('user_success'); ?>
                <?php flash('user_error'); ?>
                <?php flash('setting_success'); ?>
                <?php flash('setting_error'); ?>
                <?php flash('brand_success'); ?>
                <?php flash('brand_error'); ?>
                <?php flash('banner_success'); ?>
                <?php flash('banner_error'); ?>

                <script>
                    // Bootstrap 4: mobile sidebar UX – backdrop and body scroll
                    (function() {
                        var sidebar = document.getElementById('sidebar');
                        var backdrop = document.getElementById('sidebarBackdrop');
                        function updateState() {
                            var isOpen = sidebar && sidebar.classList.contains('show');
                            document.body.classList.toggle('sidebar-open', isOpen);
                            if (backdrop) backdrop.classList.toggle('active', isOpen);
                        }
                        $(document).on('shown.bs.collapse', '#sidebar', function() { updateState(); });
                        $(document).on('hidden.bs.collapse', '#sidebar', function() { updateState(); });
                        if (backdrop) {
                            backdrop.addEventListener('click', function() {
                                $('#sidebar').collapse('hide');
                            });
                        }
                        if (sidebar) {
                            sidebar.addEventListener('click', function(e) {
                                if (window.innerWidth < 768) {
                                    var link = e.target.closest('a.nav-link');
                                    if (!link) return;
                                    var isDropdownToggle = link.classList.contains('dropdown-toggle') || link.getAttribute('data-toggle') === 'dropdown';
                                    if (link.id === 'policyLink' || link.getAttribute('data-keep-open') === '1' || isDropdownToggle) return;
                                    $('#sidebar').collapse('hide');
                                }
                            });
                        }
                        (function autoOpenForPolicy() {
                            try {
                                var params = new URLSearchParams(window.location.search);
                                var isPolicy = (params.get('controller') || '').toLowerCase() === 'policy';
                                if (isPolicy && window.innerWidth < 768 && $('#sidebar').length) {
                                    $('#sidebar').collapse('show');
                                    updateState();
                                }
                            } catch (_) { /* no-op */ }
                        })();
                    })();
                </script>
                <script>
                    // Bootstrap 4: open sidebar and highlight Customers (e.g. from dashboard)
                    window.openCustomersSidebar = function() {
                        try {
                            $('#sidebar').collapse('show');
                            $('#sidebar .nav-link').removeClass('active');
                            var customersLink = document.getElementById('customersSidebarLink');
                            if (customersLink) {
                                customersLink.classList.add('active');
                                customersLink.scrollIntoView({ block: 'nearest' });
                            }
                            document.body.classList.add('sidebar-open');
                            var backdrop = document.getElementById('sidebarBackdrop');
                            if (backdrop) backdrop.classList.add('active');
                        } catch (e) { /* no-op */ }
                    };
                </script>
                <script>
                    // Theme Toggle Functionality
                    document.addEventListener('DOMContentLoaded', function() {
                        const themeToggle = document.getElementById('themeToggle');
                        const icon = themeToggle.querySelector('i');
                        const text = themeToggle.querySelector('span');
                        
                        // Check for saved user preference, if any
                        let currentTheme = localStorage.getItem('theme') || 'light';
                        
                        // Apply the saved theme
                        function applyTheme(theme) {
                            if (theme === 'dark') {
                                document.documentElement.setAttribute('data-theme', 'dark');
                                document.body.style.color = '#f8f9fa';
                                document.body.style.backgroundColor = '#212529';
                                
                                // Update all text colors
                                document.querySelectorAll('h1, h2, h3, h4, h5, h6, p, span, td, th, label, input, select, textarea').forEach(el => {
                                    const style = getComputedStyle(el);
                                    if (style.color === 'rgb(33, 37, 41)' || 
                                        style.color === 'rgb(0, 0, 0)' ||
                                        style.color === 'rgb(108, 117, 125)') {
                                        el.style.color = '#f8f9fa';
                                    }
                                });
                                
                                icon.classList.remove('fa-moon');
                                icon.classList.add('fa-sun');
                                text.textContent = 'Light Mode';
                            } else {
                                document.documentElement.removeAttribute('data-theme');
                                document.body.style.color = '#212529';
                                document.body.style.backgroundColor = '#ffffff';
                                
                                // Force black text in light mode
                                document.querySelectorAll('h1, h2, h3, h4, h5, h6, p, span, td, th, label, input, select, textarea, .text-body, .text-dark, .table, .table th, .table td, .card, .card-header, .card-body').forEach(el => {
                                    el.style.color = '#212529';
                                    if (el.classList.contains('text-muted')) {
                                        el.style.color = '#6c757d';
                                    }
                                });
                                
                                icon.classList.remove('fa-sun');
                                icon.classList.add('fa-moon');
                                text.textContent = 'Dark Mode';
                            }
                            // Force update of all text colors
                            document.querySelectorAll('body, body *').forEach(el => {
                                if (theme === 'dark') {
                                    if (getComputedStyle(el).color === 'rgb(33, 37, 41)') {
                                        el.style.color = '#f8f9fa';
                                    }
                                    if (getComputedStyle(el).backgroundColor === 'rgb(255, 255, 255)') {
                                        el.style.backgroundColor = '#2c3034';
                                    }
                                }
                            });
                        }
                        
                        // Apply the current theme
                        applyTheme(currentTheme);
                        
                        // Toggle between light and dark theme
                        themeToggle.addEventListener('click', function() {
                            currentTheme = currentTheme === 'dark' ? 'light' : 'dark';
                            localStorage.setItem('theme', currentTheme);
                            applyTheme(currentTheme);
                        });
                    });
                    
                    // Preserve sidebar scroll position across page loads
                    (function() {
                        const KEY = 'admin_sidebar_scrollTop_v1';
                        document.addEventListener('DOMContentLoaded', function () {
                            try {
                                const scroller = document.querySelector('#sidebar .position-sticky');
                                if (!scroller) return;
                                const saved = parseInt(localStorage.getItem(KEY) || '0', 10);
                                if (!isNaN(saved)) {
                                    scroller.scrollTop = saved;
                                }

                                // Save on scroll
                                scroller.addEventListener('scroll', function() {
                                    localStorage.setItem(KEY, String(scroller.scrollTop));
                                }, { passive: true });

                                // Save right before navigating away (e.g., link click causes full page load)
                                document.querySelector('#sidebar').addEventListener('click', function(e) {
                                    const link = e.target.closest('a.nav-link, a.dropdown-item');
                                    if (link && link.getAttribute('href')) {
                                        localStorage.setItem(KEY, String(scroller.scrollTop));
                                    }
                                });

                                window.addEventListener('beforeunload', function() {
                                    localStorage.setItem(KEY, String(scroller.scrollTop));
                                });
                            } catch (_) { /* no-op */ }
                        });
                    })();
                </script>
                <script>
                    // Auto-convert all admin tables to stacked responsive layout on small/medium screens
                    document.addEventListener('DOMContentLoaded', function () {
                        try {
                            if (window.innerWidth > 768) return; // apply up to 768px
                            document.querySelectorAll('main table.table').forEach(function(table){
                                if (table.classList.contains('no-stack')) return; // opt-out
                                // add responsive class so CSS applies
                                table.classList.add('responsive-table');
                                const headers = Array.from(table.querySelectorAll('thead th')).map(function(th){
                                    return (th.textContent || '').trim();
                                });
                                table.querySelectorAll('tbody tr').forEach(function(tr){
                                    tr.querySelectorAll('td').forEach(function(td, idx){
                                        if (!td.hasAttribute('data-label')) {
                                            td.setAttribute('data-label', headers[idx] || '');
                                        }
                                    });
                                });
                            });
                        } catch (e) { /* no-op */ }
                    });
                </script>