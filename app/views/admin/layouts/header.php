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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sivakamy</title>
    <meta name="csrf-token" content="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin.css">
    <style>
        /* Mobile sidebar behavior */
        @media (max-width: 767.98px) {
            #sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 260px;
                z-index: 1050;
                overflow-y: auto;
                background-color: #212529;
                /* match bg-dark */
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }

            #sidebar.show {
                transform: translateX(0);
            }

            /* Prevent body from shifting when sidebar opens */
            body.sidebar-open {
                overflow: hidden;
            }

            /* Add a simple backdrop */
            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1049;
                display: none;
            }

            .sidebar-backdrop.active {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h5 class="text-white">Admin Dashboard</h5>
                    </div>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=home&action=admin">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex">
                                <i class="fas fa-box me-2"></i>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex">
                                <i class="fas fa-tags me-2"></i>
                                Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex">
                                <i class="fas fa-building me-2"></i>
                                Brands
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=country&action=adminIndex">
                                <i class="fas fa-globe-americas me-2"></i>
                                Country of Origin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=banner">
                                <i class="fas fa-images me-2"></i>
                                Hero Banners
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=supplier&action=index" id="suppliersLink">
                                <i class="fas fa-truck me-2"></i>
                                Suppliers
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex">
                                <i class="fas fa-users me-2"></i>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=report&action=index">
                                <i class="fas fa-chart-bar me-2"></i>
                                Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=user&action=active">
                                <i class="fas fa-user-check me-2"></i>
                                Active Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=setting&action=index">
                                <i class="fas fa-cog me-2"></i>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>?controller=tax&action=index">
                                <i class="fas fa-percentage me-2"></i>
                                Tax Management
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="<?php echo BASE_URL; ?>">
                                <i class="fas fa-store me-2"></i>
                                View Store
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="?controller=aboutStore">
                                <i class="fas fa-globe-americas me-2"></i>
                                Manage About Store
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="?controller=PaymentDue&action=index">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                Payment Dues
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link text-white" href="#" id="clearCookiesBtn">
                                <i class="fas fa-cookie-bite me-2"></i>
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
                                                    alertDiv.innerHTML = `
                                                ${data.message}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            `;

                                                    // Insert the alert at the top of the main content
                                                    const mainContent = document.querySelector('main');
                                                    if (mainContent) {
                                                        mainContent.insertBefore(alertDiv, mainContent.firstChild);

                                                        // Auto-dismiss after 5 seconds
                                                        setTimeout(() => {
                                                            const bsAlert = new bootstrap.Alert(alertDiv);
                                                            bsAlert.close();
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
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Backdrop for mobile -->
            <div class="sidebar-backdrop d-md-none" id="sidebarBackdrop"></div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-outline-secondary me-3 d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation" id="sidebarToggleBtn">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="h2 mb-0">Admin Dashboard</h1>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-1"></i> <?php echo $_SESSION['user_name']; ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=profile">My Profile</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
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
                    // Enhance mobile sidebar UX: manage backdrop and body scroll
                    (function() {
                        const sidebar = document.getElementById('sidebar');
                        const backdrop = document.getElementById('sidebarBackdrop');
                        const toggleBtn = document.getElementById('sidebarToggleBtn');

                        function updateState() {
                            const isOpen = sidebar.classList.contains('show');
                            document.body.classList.toggle('sidebar-open', isOpen);
                            if (backdrop) backdrop.classList.toggle('active', isOpen);
                        }

                        document.addEventListener('shown.bs.collapse', function(e) {
                            if (e.target === sidebar) updateState();
                        });
                        document.addEventListener('hidden.bs.collapse', function(e) {
                            if (e.target === sidebar) updateState();
                        });
                        if (backdrop) {
                            backdrop.addEventListener('click', function() {
                                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(sidebar);
                                bsCollapse.hide();
                            });
                        }
                        // Close sidebar after clicking a link on mobile
                        sidebar.addEventListener('click', function(e) {
                            if (window.innerWidth < 768 && e.target.closest('a.nav-link')) {
                                const bsCollapse = bootstrap.Collapse.getOrCreateInstance(sidebar);
                                bsCollapse.hide();
                            }
                        });
                    })();
                </script>