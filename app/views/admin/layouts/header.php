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
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS (cache-busted) -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css?v=<?php echo time(); ?>">
    <style>
        /* Base styles for light theme */
        :root {
            --text-color: #212529;
            --bg-color: #ffffff;
            --sidebar-bg: #212529;
            --sidebar-text: #f8f9fa;
        }
        
        /* Dark theme overrides (custom data-theme for Bootstrap 4 compatibility) */
        [data-theme="dark"],
        [data-theme="dark"] body {
            --text-color: #f8f9fa;
            --bg-color: #212529;
            --sidebar-bg: #1a1e21;
            --sidebar-text: #f8f9fa;
            color: var(--text-color) !important;
            background-color: var(--bg-color) !important;
        }
        
        /* Apply theme colors */
        body {
            color: var(--text-color);
            background-color: var(--bg-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Force text color in light mode */
        :not([data-theme="dark"]) .text-body,
        :not([data-theme="dark"]) .text-dark,
        :not([data-theme="dark"]) .table,
        :not([data-theme="dark"]) .table th,
        :not([data-theme="dark"]) .table td,
        :not([data-theme="dark"]) .card,
        :not([data-theme="dark"]) .card-header,
        :not([data-theme="dark"]) .card-body {
            color: #212529 !important;
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
        
        /* Ensure last items in sidebar are reachable */
        #sidebar .position-sticky { 
            padding-bottom: 80px; 
            box-sizing: border-box; 
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
                width: 260px;
                overflow: hidden;
                transform: none;
                overscroll-behavior: contain;
            }
            #sidebar .position-sticky { height: 100vh; overflow: auto; overscroll-behavior: contain; }
            main {
                margin-left: 260px;
                height: 100vh;
                overflow: auto;
                overscroll-behavior: contain;
                padding-bottom: 32px;
                box-sizing: border-box;
            }
        }
    </style>
    <!-- jQuery and Bootstrap 4 in head so inline scripts can use them -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" role="navigation">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">Admin Dashboard</h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=home&action=admin">
                                <i class="fas fa-tachometer-alt mr-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex">
                                <i class="fas fa-box mr-2"></i>
                                Products
                            </a>
                        </li>



                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex">
                                <i class="fas fa-tags mr-2"></i>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex">
                                <i class="fas fa-building mr-2"></i>
                                Brands
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=country&action=adminIndex">
                                <i class="fas fa-globe-americas mr-2"></i>
                                Country of Origin
                            </a>
                        </li>

                        <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle text-white" href="#" id="purchaseDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-money-bill-wave mr-2"></i> Purchase
    </a>
    <ul class="dropdown-menu" aria-labelledby="purchaseDropdown">
        <li><a class="dropdown-item" href="?controller=purchase&action=create">Purchase with customer</a></li>
        <li><a class="dropdown-item" href="?controller=ListPurchaseController">List Purchase </a></li>
        <li><a class="dropdown-item" href="?controller=purchase&action=purchase2">Add Purchase</a></li>
        <li><a class="dropdown-item" href="?controller=purchase&action=purchase3">List Purchase Return</a></li>
        
    </ul>
</li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=banner">
                                <i class="fas fa-images mr-2"></i>
                                Hero Banners
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=supplier&action=index" id="suppliersLink">
                                <i class="fas fa-truck mr-2"></i>
                                Suppliers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex">
                                <i class="fas fa-users mr-2"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=user&action=customers" id="customersSidebarLink">
                                <i class="fas fa-user-friends mr-2"></i>
                                Customers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=report&action=index">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=mail&action=index">
                                <i class="fas fa-envelope mr-2"></i>
                                Mail
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=invoice&action=create">
                                <i class="fas fa-user-check mr-2"></i>
                                Billing & Invoices
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=setting&action=index">
                                <i class="fas fa-cog mr-2"></i>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=tax&action=index">
                                <i class="fas fa-percentage mr-2"></i>
                                Tax Management
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>">
                                <i class="fas fa-store mr-2"></i>
                                View Store
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="?controller=aboutStore">
                                <i class="fas fa-globe-americas mr-2"></i>
                                Manage About Store
                            </a>
                        </li>
                            <li class="nav-item">
                            <a class="nav-link text-white" href="?controller=contactinfo&action=index">
                                <i class="fas fa-address-book mr-2"></i>
                                Contact Info
                            </a>
                        </li>
                           
                        <li class="nav-item">
                            <a class="nav-link text-white" href="?controller=PaymentDue&action=index">
                                <i class="fas fa-money-bill-wave mr-2"></i>
                                Payment Dues
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" id="policyLink" href="<?php echo BASE_URL; ?>?controller=policy&action=index" data-keep-open="1">
                                <i class="fas fa-file-contract mr-2"></i>
                                Policy
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" id="clearCookiesBtn">
                                <i class="fas fa-cookie-bite mr-2"></i>
                                Clear Cookies
                            </a>
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
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=user&action=logout">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Backdrop for mobile -->
            <div class="sidebar-backdrop d-md-none" id="sidebarBackdrop"></div>

            <!-- Main Content -->
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center flex-wrap flex-md-nowrap pt-3 pb-2 mb-3 border-bottom">
                    <div class="d-flex align-items-center mb-2 mb-sm-0">
                        <button class="btn btn-outline-secondary mr-3 d-md-none" type="button" data-toggle="collapse" data-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation" id="sidebarToggleBtn">
                            <i class="fas fa-bars"></i>
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