<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Sivakamy</title>
    <!-- Bootstrap 5 CSS - Latest Stable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">

    <script>
        (function() {
            try {
                var saved = localStorage.getItem('theme_mode');
                if (saved === 'dark' || saved === 'light') {
                    document.documentElement.setAttribute('data-theme', saved);
                }
            } catch (e) {}
        })();
    </script>
</head>
<body class="mobile-nav-fixed d-flex flex-column min-vh-100">
    <?php
    // Get site settings
    $settingModel = new Setting();
    $siteLogo = $settingModel->getSetting('site_logo');
    $siteName = $settingModel->getSetting('site_name') ?: 'Sivakamy';
    $headerBgColor = $settingModel->getSetting('header_bg_color', '#ffffff');
    $headerWidth = $settingModel->getSetting('header_width', 'boxed');
    $headerBgColor = (!empty($headerBgColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $headerBgColor)) ? $headerBgColor : '#ffffff';
    $headerWidth = in_array($headerWidth, ['boxed', 'full'], true) ? $headerWidth : 'boxed';
    $headerContainerClass = $headerWidth === 'full' ? 'container-fluid' : 'container';

    $themePrimaryColor = $settingModel->getSetting('theme_primary_color', '#0d6efd');
    $themeSecondaryColor = $settingModel->getSetting('theme_secondary_color', '#6c757d');
    $themeBackgroundColor = $settingModel->getSetting('theme_background_color', '#ffffff');
    $themeTextColor = $settingModel->getSetting('theme_text_color', '#212529');
    $themeDefaultMode = $settingModel->getSetting('theme_default_mode', 'light');
    $themeDarkPrimaryColor = $settingModel->getSetting('theme_dark_primary_color', '#4dabf7');
    $themeDarkSecondaryColor = $settingModel->getSetting('theme_dark_secondary_color', '#adb5bd');
    $themeDarkBackgroundColor = $settingModel->getSetting('theme_dark_background_color', '#0b1220');
    $themeDarkTextColor = $settingModel->getSetting('theme_dark_text_color', '#e9ecef');
    $themePrimaryColor = (!empty($themePrimaryColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themePrimaryColor)) ? $themePrimaryColor : '#0d6efd';
    $themeSecondaryColor = (!empty($themeSecondaryColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeSecondaryColor)) ? $themeSecondaryColor : '#6c757d';
    $themeBackgroundColor = (!empty($themeBackgroundColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeBackgroundColor)) ? $themeBackgroundColor : '#ffffff';
    $themeTextColor = (!empty($themeTextColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeTextColor)) ? $themeTextColor : '#212529';
    $themeDefaultMode = in_array($themeDefaultMode, ['light', 'dark'], true) ? $themeDefaultMode : 'light';
    $themeDarkPrimaryColor = (!empty($themeDarkPrimaryColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeDarkPrimaryColor)) ? $themeDarkPrimaryColor : '#4dabf7';
    $themeDarkSecondaryColor = (!empty($themeDarkSecondaryColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeDarkSecondaryColor)) ? $themeDarkSecondaryColor : '#adb5bd';
    $themeDarkBackgroundColor = (!empty($themeDarkBackgroundColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeDarkBackgroundColor)) ? $themeDarkBackgroundColor : '#0b1220';
    $themeDarkTextColor = (!empty($themeDarkTextColor) && preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $themeDarkTextColor)) ? $themeDarkTextColor : '#e9ecef';
    ?>

    <style>
        :root {
            --theme-primary: <?php echo htmlspecialchars($themePrimaryColor); ?>;
            --theme-secondary: <?php echo htmlspecialchars($themeSecondaryColor); ?>;
            --theme-bg: <?php echo htmlspecialchars($themeBackgroundColor); ?>;
            --theme-text: <?php echo htmlspecialchars($themeTextColor); ?>;
        }

        html[data-theme="light"] {
            --theme-text: #000000;
        }

        html[data-theme="dark"] {
            --theme-primary: <?php echo htmlspecialchars($themeDarkPrimaryColor); ?>;
            --theme-secondary: <?php echo htmlspecialchars($themeDarkSecondaryColor); ?>;
            --theme-bg: <?php echo htmlspecialchars($themeDarkBackgroundColor); ?>;
            --theme-text: <?php echo htmlspecialchars($themeDarkTextColor); ?>;
        }

        body {
            background-color: var(--theme-bg) !important;
            color: var(--theme-text);
        }

        /* Ensure Brands section inherits page background (remove white strip) */
        #brands {
            background: transparent !important;
        }

        html[data-theme="dark"] .navbar,
        html[data-theme="dark"] .navbar[style*="background"],
        html[data-theme="dark"] .mobile-top-bar,
        html[data-theme="dark"] .mobile-top-bar[style*="background"],
        html[data-theme="dark"] .mobile-bottom-nav,
        html[data-theme="dark"] #mobileNavbar,
        html[data-theme="dark"] #mobileNavbar[style*="background"],
        html[data-theme="dark"] .dropdown-menu,
        html[data-theme="dark"] .card,
        html[data-theme="dark"] .modal-content,
        html[data-theme="dark"] .list-group-item {
            background-color: var(--theme-bg) !important;
            background: var(--theme-bg) !important;
            color: var(--theme-text) !important;
        }
        
        /* Force navbar border color in dark mode */
        html[data-theme="dark"] .navbar.border-bottom {
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        html[data-theme="dark"] .mobile-top-bar.border-bottom {
            border-bottom-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Ensure mobile bottom nav has dark background */
        html[data-theme="dark"] .mobile-bottom-nav {
            background-color: var(--theme-bg) !important;
            background: var(--theme-bg) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        
        /* Override any inline background styles in dark mode for header elements */
        html[data-theme="dark"] nav[style*="background"],
        html[data-theme="dark"] div[style*="background"].mobile-top-bar,
        html[data-theme="dark"] div[style*="background"]#mobileNavbar {
            background-color: var(--theme-bg) !important;
            background: var(--theme-bg) !important;
        }
        
        /* Ensure body background matches dark theme */
        html[data-theme="dark"] body {
            background-color: var(--theme-bg) !important;
            background: var(--theme-bg) !important;
        }

        /* Product Cards Dark Theme */
        html[data-theme="dark"] .product-card,
        html[data-theme="dark"] .product-card .card-body,
        html[data-theme="dark"] .product-card .card-title,
        html[data-theme="dark"] .product-card .product-title,
        html[data-theme="dark"] .product-card .product-desc,
        html[data-theme="dark"] .product-card .product-price,
        html[data-theme="dark"] .product-card .product-meta,
        html[data-theme="dark"] .product-card .product-meta small {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: var(--theme-text) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        html[data-theme="dark"] .product-card:hover {
            background-color: rgba(255, 255, 255, 0.12) !important;
            border-color: var(--theme-primary) !important;
        }

        /* Category Cards Dark Theme */
        html[data-theme="dark"] .category-card,
        html[data-theme="dark"] .category-card .card-body,
        html[data-theme="dark"] .category-card .category-title {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: var(--theme-text) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
        }

        html[data-theme="dark"] .category-card:hover {
            background-color: rgba(255, 255, 255, 0.12) !important;
        }

        /* Product Image Box Dark Theme */
        html[data-theme="dark"] .product-image-box,
        html[data-theme="dark"] .category-image-box {
            background: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        /* Sections Background Dark Theme */
        html[data-theme="dark"] .featured-products,
        html[data-theme="dark"] .featured-categories,
        html[data-theme="dark"] .brands-showcase,
        html[data-theme="dark"] section[data-theme-aware] {
            background: transparent !important;
        }

        /* Override inline background styles in dark mode */
        html[data-theme="dark"] [style*="background"] {
            background: transparent !important;
        }

        /* But keep specific overrides for cards */
        html[data-theme="dark"] .product-card[style*="background"],
        html[data-theme="dark"] .category-card[style*="background"] {
            background: rgba(255, 255, 255, 0.08) !important;
        }

        /* All text elements in dark mode */
        html[data-theme="dark"] h1,
        html[data-theme="dark"] h2,
        html[data-theme="dark"] h3,
        html[data-theme="dark"] h4,
        html[data-theme="dark"] h5,
        html[data-theme="dark"] h6,
        html[data-theme="dark"] p,
        html[data-theme="dark"] span,
        html[data-theme="dark"] div,
        html[data-theme="dark"] .section-title {
            color: var(--theme-text) !important;
        }

        /* Links in dark mode */
        html[data-theme="dark"] a:not(.btn) {
            color: var(--theme-primary) !important;
        }

        html[data-theme="dark"] a:not(.btn):hover {
            color: var(--theme-secondary) !important;
        }

        html[data-theme="dark"] .nav-link,
        html[data-theme="dark"] .dropdown-item,
        html[data-theme="dark"] .navbar-brand,
        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select,
        html[data-theme="dark"] .input-group-text,
        html[data-theme="dark"] .btn,
        html[data-theme="dark"] .table,
        html[data-theme="dark"] .table td,
        html[data-theme="dark"] .table th {
            color: var(--theme-text) !important;
        }

        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .form-select,
        html[data-theme="dark"] .input-group-text {
            background-color: rgba(255, 255, 255, 0.06) !important;
            border-color: rgba(255, 255, 255, 0.18) !important;
        }

        html[data-theme="dark"] .dropdown-item:hover,
        html[data-theme="dark"] .dropdown-item:focus {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        html[data-theme="dark"] .text-dark,
        html[data-theme="dark"] .text-muted,
        html[data-theme="dark"] .text-secondary {
            color: var(--theme-text) !important;
        }

        html[data-theme="dark"] .bg-white,
        html[data-theme="dark"] .bg-light {
            background-color: var(--theme-bg) !important;
        }

        html[data-theme="light"] body,
        html[data-theme="light"] h1,
        html[data-theme="light"] h2,
        html[data-theme="light"] h3,
        html[data-theme="light"] h4,
        html[data-theme="light"] h5,
        html[data-theme="light"] h6,
        html[data-theme="light"] p,
        html[data-theme="light"] span,
        html[data-theme="light"] small,
        html[data-theme="light"] li,
        html[data-theme="light"] label,
        html[data-theme="light"] .nav-link,
        html[data-theme="light"] .navbar,
        html[data-theme="light"] .dropdown-item {
            color: var(--theme-text) !important;
        }

        html[data-theme="light"] .text-dark,
        html[data-theme="light"] .text-muted,
        html[data-theme="light"] .text-secondary {
            color: var(--theme-text) !important;
        }

        a {
            color: var(--theme-primary);
        }

        .theme-toggle-btn {
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: rgba(0, 0, 0, 0.08);
            color: inherit;
        }

        html[data-theme="dark"] .theme-toggle-btn {
            background: rgba(255, 255, 255, 0.12);
        }

        .btn-primary {
            background-color: var(--theme-primary) !important;
            border-color: var(--theme-primary) !important;
        }

        .btn-secondary {
            background-color: var(--theme-secondary) !important;
            border-color: var(--theme-secondary) !important;
        }

        .text-primary { color: var(--theme-primary) !important; }
        .text-secondary { color: var(--theme-secondary) !important; }
        .bg-primary { background-color: var(--theme-primary) !important; }
        .bg-secondary { background-color: var(--theme-secondary) !important; }
    </style>
    
    <!-- Mobile Header -->
    <style>
        /* Mobile Navigation Styles */
        @media (max-width: 767.98px) {
            /* Add padding to body to account for fixed navigation bars */
            body.mobile-nav-fixed {
                padding-top: 60px !important;
                padding-bottom: 70px !important;
                min-height: 100vh;
            }
            
            /* Fixed bottom navigation */
            .mobile-bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 1050;
                background: #fff;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
                padding: 8px 0;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            
            /* Fixed top bar */
            .mobile-top-bar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1060;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
            
            /* Adjust main content for mobile */
            .main-content {
                margin-top: 20px;
                margin-bottom: 20px;
            }
        }
    </style>
    
    <!-- Mobile Top Bar -->
    <div class="d-md-none border-bottom mobile-top-bar" style="background: <?php echo htmlspecialchars($headerBgColor); ?>;">
        <!-- Top Bar -->
        <div class="<?php echo $headerContainerClass; ?> py-3">
            <div class="d-flex align-items-center justify-content-between">
                <a href="<?php echo BASE_URL; ?>" class="text-decoration-none">
                    <?php if(!empty($siteLogo) && file_exists(UPLOAD_PATH . $siteLogo)): ?>
                        <img src="<?php echo BASE_URL . 'uploads/' . htmlspecialchars($siteLogo); ?>" 
                             alt="<?php echo htmlspecialchars($siteName); ?>" 
                             style="max-height: 42px;">
                    <?php else: ?>
                        <span class="h5 mb-0 fw-bold text-dark"><?php echo htmlspecialchars($siteName); ?></span>
                    <?php endif; ?>
                </a>
                <div class="d-flex align-items-center">
                    <div class="d-flex align-items-center">
                        <a href="<?php echo BASE_URL; ?>?controller=cart" class="text-dark me-3 position-relative">
                            <i class="fas fa-shopping-cart fs-5"></i>
                            <?php if(isLoggedIn()): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                                <?php 
                                    $cartModel = new Cart();
                                    echo $cartModel->getCartCount($_SESSION['user_id']);
                                ?>
                            </span>
                            <?php endif; ?>
                        </a>
                        
                        <?php if(!isLoggedIn()): ?>
                        <div class="d-flex align-items-center">
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-sm btn-outline-success me-2 d-none d-sm-inline-flex align-items-center">
                                <i class="fas fa-sign-in-alt me-1"></i>Login
                            </a>
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=register" class="btn btn-sm btn-primary d-none d-sm-inline-flex align-items-center">
                                <i class="fas fa-user-plus me-1"></i>Register
                            </a>
                            <div class="dropdown d-sm-none">
                                <button class="btn p-0 border-0" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle fs-4"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=login"><i class="fas fa-sign-in-alt me-2"></i>Login</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=register"><i class="fas fa-user-plus me-2"></i>Register</a></li>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>

                        <button class="btn p-0 border-0 ms-2 theme-toggle-btn theme-toggle" type="button" aria-label="Toggle dark mode">
                            <i class="fas fa-moon"></i>
                        </button>
                        
                        <button class="btn p-0 border-0 ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileNavbar" aria-expanded="false" aria-controls="mobileNavbar">
                            <i class="fas fa-bars fs-4"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Search Bar -->
            <form action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET" class="mt-2">
                <div class="input-group">
                    <input type="text" 
                           class="form-control bg-light border-0 ps-3" 
                           name="keyword" 
                           placeholder="Search products..."
                           style="height: 36px; border-radius: 18px; font-size: 0.9rem;">
                    <button class="btn btn-dark position-absolute end-0 h-100 rounded-end" type="submit" style="width: 40px;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Mobile Navigation -->
        <div class="collapse" id="mobileNavbar" style="background: <?php echo htmlspecialchars($headerBgColor); ?>;">
            <div class="<?php echo $headerContainerClass; ?> py-2">
                <ul class="navbar-nav">
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=product&action=index">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-box me-2 text-primary" style="width: 24px; text-align: center;"></i>
                                <span>All Products</span>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=about&action=index">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2 text-secondary" style="width: 24px; text-align: center;"></i>
                                <span>About Our Store</span>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <!-- Categories Dropdown for Mobile -->
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" data-bs-toggle="collapse" href="#categoriesCollapse" role="button" aria-expanded="false" aria-controls="categoriesCollapse">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-list me-2 text-info" style="width: 24px; text-align: center;"></i>
                                <span>Categories</span>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                        <div class="collapse" id="categoriesCollapse">
                            <ul class="nav flex-column ps-5">
                                <?php 
                                // Get active categories
                                $categoryModel = new Category();
                                $categories = $categoryModel->getActiveCategories();
                                
                                if(!empty($categories)) :
                                    foreach($categories as $category) :
                                        $categoryImage = !empty($category['image']) ? 
                                            (strpos($category['image'], 'uploads/') === 0 ? 
                                                BASE_URL . $category['image'] : 
                                                BASE_URL . 'uploads/categories/' . $category['image']) : 
                                            BASE_URL . 'assets/img/no-image.png';
                                ?>
                                    <li class="nav-item border-bottom">
                                        <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=category&action=show&param=<?php echo $category['id']; ?>">
                                            <img src="<?php echo $categoryImage; ?>" 
                                                 alt="<?php echo htmlspecialchars($category['name']); ?>" 
                                                 class="me-2" 
                                                 style="width: 20px; height: 20px; object-fit: cover; border-radius: 2px; border: 1px solid #dee2e6;">
                                            <span style="font-size: 0.9rem;"><?php echo htmlspecialchars($category['name']); ?></span>
                                        </a>
                                    </li>
                                <?php 
                                    endforeach;
                                endif; 
                                ?>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="#">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-flag me-2 text-info" style="width: 24px; text-align: center;"></i>
                                <span>Country of Origin</span>
                            </div>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=brand&action=index">
                            <span><i class="fas fa-tags me-2 text-warning"></i> All Brands</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                    <li class="nav-item border-bottom">
                        <a class="nav-link d-flex align-items-center justify-content-between py-3" href="<?php echo BASE_URL; ?>?controller=country&action=index">
                            <span><i class="fas fa-globe-americas me-2 text-success"></i> Countries</span>
                            <i class="fas fa-chevron-right text-muted small"></i>
                        </a>
                    </li>
                </ul>
                
                <!-- Account Section -->
                <div class="border-top mt-2 pt-2">
                    <div class="px-3 mb-2 small fw-bold text-uppercase text-muted">My Account</div>
                    <?php if(isLoggedIn()): ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=user&action=profile">
                                    <i class="fas fa-tachometer-alt me-3 text-primary" style="width: 24px; text-align: center;"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=order">
                                    <i class="fas fa-shopping-bag me-3 text-primary" style="width: 24px; text-align: center;"></i>
                                    <span>My Orders</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=invoice">
                                    <i class="fas fa-file-invoice me-3 text-primary" style="width: 24px; text-align: center;"></i>
                                    <span>Invoices</span>
                                </a>
                            </li>
                            <?php if(isAdmin() || isStaff()): ?>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center py-2" href="<?php echo isAdmin() ? BASE_URL.'?controller=home&action=admin' : BASE_URL.'?controller=pos' ?>">
                                        <i class="fas <?php echo isAdmin() ? 'fa-tachometer-alt' : 'fa-cash-register'; ?> me-3 text-primary" style="width: 24px; text-align: center;"></i>
                                        <span><?php echo isAdmin() ? 'Admin Dashboard' : 'POS System'; ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2 text-danger" href="<?php echo BASE_URL; ?>?controller=user&action=logout">
                                    <i class="fas fa-sign-out-alt me-3" style="width: 24px; text-align: center;"></i>
                                    <span>Logout</span>
                                </a>
                            </li>
                        </ul>
                    <?php else: ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=user&action=login">
                                    <i class="fas fa-sign-in-alt me-3 text-primary" style="width: 24px; text-align: center;"></i>
                                    <span>Login</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center py-2" href="<?php echo BASE_URL; ?>?controller=user&action=register">
                                <i class="fas fa-user-plus me-3 text-primary" style="width: 24px; text-align: center;"></i>
                                <span>Register</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Bottom Navigation -->
    <div class="d-md-none mobile-bottom-nav">
        <style>
            .mobile-bottom-nav a {
                color: #666;
                text-decoration: none;
                padding: 5px 0;
                transition: all 0.2s ease;
            }
            .mobile-bottom-nav a.active,
            .mobile-bottom-nav a:active {
                color: #0d6efd;
                transform: translateY(-2px);
            }
            .mobile-bottom-nav i {
                margin-bottom: 3px;
                font-size: 1.2rem;
            }
            .mobile-bottom-nav .badge {
                font-size: 9px;
                padding: 2px 5px;
                top: -5px;
                right: -5px;
            }
        </style>
        <div class="container">
            <div class="d-flex justify-content-around align-items-center">
                <a href="<?php echo BASE_URL; ?>" class="text-center text-decoration-none text-dark">
                    <i class="fas fa-home fs-5 d-block"></i>
                    <span class="small d-block">Home</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?controller=category" class="text-center text-decoration-none text-dark">
                    <i class="fas fa-th-large fs-5 d-block"></i>
                    <span class="small d-block">Categories</span>
                </a>
                <a href="<?php echo BASE_URL; ?>?controller=cart" class="text-center text-decoration-none text-dark position-relative">
                    <i class="fas fa-shopping-cart fs-5 d-block"></i>
                    <span class="small d-block">Cart</span>
                    <?php if(isLoggedIn()): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px;">
                        <?php 
                            $cartModel = new Cart();
                            echo $cartModel->getCartCount($_SESSION['user_id']);
                        ?>
                    </span>
                    <?php endif; ?>
                </a>
                <?php if(isLoggedIn()): ?>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=dashboard" class="text-center text-decoration-none text-dark">
                        <i class="fas fa-user fs-5 d-block"></i>
                        <span class="small d-block">Account</span>
                    </a>
                <?php else: ?>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="text-center text-decoration-none text-dark">
                        <i class="fas fa-sign-in-alt fs-5 d-block"></i>
                        <span class="small d-block">Login</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Desktop Navigation -->
    <style>
        /* Add padding to body to account for fixed navbar */
        body {
            padding-top: 90px !important;
            overflow-x: hidden;
        }
        
        /* Make navbar fixed at the top */
        .navbar {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            width: 100%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        /* Ensure dropdowns appear above other content */
        .dropdown-menu {
            z-index: 1050 !important;
        }
        
        /* Add smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Adjust main content margin to prevent content from being hidden behind navbar */
        main {
            margin-top: 24px;
        }
        
        /* Desktop navbar: responsive when visible (md and up) */
        @media (min-width: 768px) {
            .navbar .navbar-collapse {
                flex-wrap: wrap;
                max-width: 100%;
            }
            .navbar .navbar-nav.me-auto {
                flex-wrap: wrap;
                gap: 0.25rem;
            }
            .navbar .navbar-nav .nav-item .nav-link {
                white-space: nowrap;
            }
        }
        @media (min-width: 768px) and (max-width: 991.98px) {
            .navbar .container,
            .navbar .container-fluid {
                flex-wrap: wrap;
            }
            .navbar .navbar-collapse {
                width: 100%;
                overflow-x: hidden;
            }
            .navbar .d-lg-flex form {
                width: 100%;
                max-width: 100%;
            }
            .navbar .d-lg-flex form .form-control {
                min-width: 0;
                flex: 1;
            }
        }
        @media (min-width: 992px) and (max-width: 1199.98px) {
            .navbar .navbar-brand {
                margin-right: 0.5rem;
            }
            .navbar .navbar-nav .nav-link {
                padding: 0.5rem 0.6rem !important;
                font-size: 0.9rem;
            }
            .navbar .navbar-nav .nav-item .btn {
                padding: 0.35rem 0.65rem !important;
                font-size: 0.85rem;
            }
            .navbar .d-lg-flex form.desktop-nav-search {
                max-width: 220px;
                min-width: 0;
            }
            .navbar .d-lg-flex form .form-control {
                min-width: 0;
                width: 100%;
            }
            .navbar .d-lg-flex form .btn {
                padding: 0.35rem 0.5rem;
                font-size: 0.85rem;
            }
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-light border-bottom d-none d-md-block" style="background: <?php echo htmlspecialchars($headerBgColor); ?>;">
        <div class="<?php echo $headerContainerClass; ?>">
            <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>">
                <?php if(!empty($siteLogo) && file_exists(UPLOAD_PATH . $siteLogo)): ?>
                    <img src="<?php echo BASE_URL . 'uploads/' . htmlspecialchars($siteLogo); ?>" alt="<?php echo htmlspecialchars($siteName); ?>" style="max-height: 52px;">
                <?php else: ?>
                    <?php echo htmlspecialchars($siteName); ?>
                <?php endif; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                 
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            All products
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=product&action=index">All Products</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php 
                            // Get active categories
                            $categoryModel = new Category();
                            $categories = $categoryModel->getActiveCategories();
                            
                            if(!empty($categories)) :
                                foreach($categories as $category) :
                                    $categoryImage = !empty($category['image']) ? 
                                        (strpos($category['image'], 'uploads/') === 0 ? 
                                            BASE_URL . $category['image'] : 
                                            BASE_URL . 'uploads/categories/' . $category['image']) : 
                                        BASE_URL . 'assets/img/no-image.png';
                            ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="<?php echo BASE_URL; ?>?controller=category&action=show&param=<?php echo $category['id']; ?>">
                                        <img src="<?php echo $categoryImage; ?>" 
                                             alt="<?php echo htmlspecialchars($category['name']); ?>" 
                                             class="me-2" 
                                             style="width: 24px; height: 24px; object-fit: cover; border-radius: 2px; border: 1px solid #dee2e6;">
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </a>
                                </li>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="countryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Country of origin
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="countryDropdown">
                            <li>
                                <a class="dropdown-item d-flex align-items-center" href="<?php echo BASE_URL; ?>?controller=country&action=index">
                                    <div class="me-2" style="width: 24px; height: 18px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-globe-americas"></i>
                                    </div>
                                    All Countries
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <?php 
                            // Get active countries
                            $countryModel = new Country();
                            $countries = $countryModel->getActiveCountries();
                            
                            if(!empty($countries)) :
                                foreach($countries as $countryItem) :
                            ?>
                                <?php 
                                    $countryCode = strtolower(substr($countryItem['name'], 0, 2));
                                    $flagImage = !empty($countryItem['flag_image']) ? 
                                        BASE_URL . 'uploads/flags/' . $countryItem['flag_image'] : 
                                        'https://flagcdn.com/24x18/' . $countryCode . '.png';
                                ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="<?php echo BASE_URL; ?>?controller=country&action=show&id=<?php echo (int)$countryItem['id']; ?>">
                                        <img src="<?php echo $flagImage; ?>" 
                                             alt="<?php echo htmlspecialchars($countryItem['name']); ?>" 
                                             class="me-2" 
                                             style="width: 24px; height: 18px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 2px;">
                                        <?php echo htmlspecialchars($countryItem['name']); ?>
                                    </a>
                                </li>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="brandDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            All brands
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="brandDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=brand&action=index">All Brands</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php 
                            // Get active brands
                            $brandModel = new Brand();
                            $brands = $brandModel->getActiveBrands();
                            
                            if(!empty($brands)) :
                                foreach($brands as $brand) :
                                    $brandImage = !empty($brand['logo']) ? 
                                        $brand['logo'] : 
                                        BASE_URL . 'assets/img/no-image.png';
                            ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="<?php echo BASE_URL; ?>?controller=brand&action=show&param=<?php echo $brand['id']; ?>">
                                        <img src="<?php echo $brandImage; ?>" 
                                             alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                             class="me-2" 
                                             style="width: 24px; height: 24px; object-fit: cover; border-radius: 2px; border: 1px solid #dee2e6;">
                                        <?php echo htmlspecialchars($brand['name']); ?>
                                    </a>
                                </li>
                            <?php 
                                endforeach;
                            endif; 
                            ?>
                        </ul>
                    </li>
                   
                    <li class="nav-item ms-2">
                        <a href="#" class="btn btn-success px-3 fw-medium" style="background-color: #28a745; border-color: #28a745;">New </a>
                    </li>
                    <li class="nav-item ms-2">
                        <a href="#" class="btn btn-success px-3 fw-medium" style="background-color:rgb(172, 104, 26); border-color:rgb(167, 116, 40);">Sale</a>
                    </li>
                </ul>
                <div class="d-lg-flex flex-column flex-lg-row align-items-start align-items-lg-center mt-3 mt-lg-0">
                    <!-- Desktop Search -->
                    <form class="d-none d-md-flex flex-grow-1 flex-lg-grow-0 mb-3 mb-lg-0 me-lg-2 desktop-nav-search" action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET" style="min-width: 0; max-width: 280px;">
                        <input type="hidden" name="controller" value="product">
                        <input type="hidden" name="action" value="search">
                        <input class="form-control me-2" type="search" name="keyword" placeholder="Search" aria-label="Search" style="min-width: 0;">
                        <button class="btn btn-outline-dark flex-shrink-0" type="submit">Search</button>
                    </form>
                    <ul class="navbar-nav ms-lg-2">
                        <li class="nav-item d-flex align-items-center me-2">
                            <button class="btn p-0 border-0 theme-toggle-btn theme-toggle" type="button" aria-label="Toggle dark mode">
                                <i class="fas fa-moon"></i>
                            </button>
                        </li>
                        <?php if(isLoggedIn()) : ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle d-lg-none me-2"></i>
                                    <span><?php echo $_SESSION['user_name']; ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li class="dropdown-header fw-bold">My Account</li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=profile"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=order"><i class="fas fa-shopping-bag me-2"></i>My Orders</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=invoice"><i class="fas fa-file-invoice me-2"></i>Invoices</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=order&action=templates"><i class="fas fa-clipboard-list me-2"></i>Order Templates</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=order&action=speed"><i class="fas fa-bolt me-2"></i>Speed Order</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=address"><i class="fas fa-address-book me-2"></i>Addresses</a></li>
                                    <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=settings"><i class="fas fa-user-cog me-2"></i>Personal Settings</a></li>
                                    <?php if(isAdmin()) : ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=home&action=admin"><i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard</a></li>
                                    <?php elseif(isStaff()) : ?>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=pos"><i class="fas fa-cash-register me-2"></i>POS System</a></li>
                                    <?php endif; ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>?controller=user&action=logout"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                                </ul>
                            </li>
                        <?php else : ?>
                            <li class="nav-item d-flex align-items-center">
                                <a class="nav-link" href="<?php echo BASE_URL; ?>?controller=user&action=login">
                                    <i class="fas fa-sign-in-alt d-lg-none me-1"></i>Login
                                </a>
                            </li>
                            <li class="nav-item d-flex align-items-center">
                                <a class="nav-link" href="<?php echo BASE_URL; ?>?controller=user&action=register">
                                    <i class="fas fa-user-plus d-lg-none me-1"></i>Register
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="<?php echo BASE_URL; ?>?controller=cart">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="d-lg-none ms-2 me-1">Cart</span>
                                <?php if(isLoggedIn()) : ?>
                                    <span class="badge bg-danger cart-count">
                                        <?php 
                                            $cartModel = new Cart();
                                            echo $cartModel->getCartCount($_SESSION['user_id']);
                                        ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        <?php flash('register_success'); ?>
        <?php flash('login_success'); ?>
        <?php flash('user_error'); ?>
        <?php flash('cart_success'); ?>
        <?php flash('cart_error'); ?>
        <?php flash('order_success'); ?>
        <?php flash('order_error'); ?>
        <?php flash('product_success'); ?>
        <?php flash('product_error'); ?>
        <?php flash('profile_success'); ?>
        <?php flash('password_success'); ?>
        <?php flash('contact_success'); ?>
        <?php flash('pos_success'); ?>
        <?php flash('pos_error'); ?>
        <?php flash('newsletter_success'); ?>
        <?php flash('newsletter_error'); ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var root = document.documentElement;
            if (!root.getAttribute('data-theme')) {
                root.setAttribute('data-theme', '<?php echo htmlspecialchars($themeDefaultMode); ?>');
            }

            function setIcon(mode) {
                var buttons = document.querySelectorAll('.theme-toggle');
                if (!buttons || !buttons.length) return;
                buttons.forEach(function(btn) {
                    var icon = btn.querySelector('i');
                    if (!icon) return;
                    icon.className = (mode === 'dark') ? 'fas fa-sun' : 'fas fa-moon';
                });
            }

            function setMode(mode) {
                root.setAttribute('data-theme', mode);
                try { localStorage.setItem('theme_mode', mode); } catch (e) {}
                setIcon(mode);
                
                // Force update navbar backgrounds for dark theme
                const navbarElements = document.querySelectorAll('.navbar, .mobile-top-bar, #mobileNavbar, .mobile-bottom-nav');
                navbarElements.forEach(element => {
                    if (mode === 'dark') {
                        // Remove inline background style to let CSS dark mode rules take over
                        element.style.removeProperty('background');
                        element.style.removeProperty('background-color');
                    } else {
                        // Restore light theme background if needed
                        const bgColor = '<?php echo htmlspecialchars($headerBgColor); ?>';
                        if (bgColor && element.classList.contains('navbar')) {
                            element.style.background = bgColor;
                            element.style.backgroundColor = bgColor;
                        }
                    }
                });
                
                // Force update all cards and sections for dark theme
                if (mode === 'dark') {
                    document.querySelectorAll('.product-card, .category-card, .card').forEach(card => {
                        card.style.backgroundColor = '';
                        card.style.color = '';
                    });
                }
            }

            setIcon(root.getAttribute('data-theme'));

            var toggles = document.querySelectorAll('.theme-toggle');
            if (toggles && toggles.length) {
                toggles.forEach(function(toggle) {
                    toggle.addEventListener('click', function() {
                        var current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                        setMode(current === 'dark' ? 'light' : 'dark');
                    });
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var brandsSection = document.querySelector('#brands');
            if (!brandsSection) return;

            // Find current grid row inside brands section
            var gridRow = brandsSection.querySelector('.row.g-2.g-md-3.justify-content-center');
            if (!gridRow) return;

            // Collect brand item columns
            var items = Array.prototype.slice.call(gridRow.children).filter(function (el) {
                return el.classList && el.classList.contains('col-4');
            });

            if (!items.length) return;

            // Build slider container + track
            var slider = document.createElement('div');
            slider.id = 'brandsSlider';
            slider.style.display = 'flex';
            slider.style.justifyContent = items.length <= 8 ? 'center' : 'flex-start';
            slider.style.overflowX = 'auto';
            slider.style.scrollBehavior = 'smooth';
            slider.style.gap = '10px';
            slider.style.padding = '10px 30px';
            slider.style.cursor = 'grab';
            slider.style.msOverflowStyle = 'none';
            slider.style.scrollbarWidth = 'none';

            // Hide scrollbar (webkit)
            var styleEl = document.createElement('style');
            styleEl.textContent = '#brandsSlider::-webkit-scrollbar{display:none;}';
            brandsSection.appendChild(styleEl);

            var track = document.createElement('div');
            track.id = 'brandsTrack';
            track.style.display = 'flex';
            track.style.gap = '10px';

            // Move items into track and normalize tile width/background
            items.forEach(function (col) {
                var tile = document.createElement('div');
                tile.style.flex = '0 0 auto';
                tile.style.width = '160px';

                // Find inner link/tile
                var link = col.querySelector('a');
                if (link) {
                    // Remove grid column padding/structure by moving the existing link
                    tile.appendChild(link);

                    var card = link.querySelector('.brand-card');
                    if (card) {
                        card.style.background = 'transparent';
                    }

                    var logoBox = link.querySelector('.brand-logo-container');
                    if (logoBox) {
                        logoBox.style.width = '100%';
                        logoBox.style.height = '80px';
                        logoBox.style.display = 'flex';
                        logoBox.style.alignItems = 'center';
                        logoBox.style.justifyContent = 'center';
                    }
                }

                track.appendChild(tile);
            });

            // Replace grid with slider
            gridRow.parentNode.replaceChild(slider, gridRow);
            slider.appendChild(track);

            // Auto-scroll behavior with overflow duplication
            var isPaused = false;
            var rafId = null;
            var speedPxPerFrame = 0.6;

            function ensureOverflow() {
                var maxLoops = 3;
                var loops = 0;
                while (slider.scrollWidth <= slider.clientWidth + 1 && track.children.length > 0 && loops < maxLoops) {
                    var children = Array.prototype.slice.call(track.children);
                    children.forEach(function (node) {
                        track.appendChild(node.cloneNode(true));
                    });
                    loops++;
                }

                if (slider.scrollWidth > slider.clientWidth + 1) {
                    slider.style.justifyContent = 'flex-start';
                }
            }

            function maxScrollLeft() {
                return Math.max(0, slider.scrollWidth - slider.clientWidth);
            }

            function tick() {
                if (!isPaused) {
                    var max = maxScrollLeft();
                    if (max > 0) {
                        slider.scrollLeft += speedPxPerFrame;
                        if (slider.scrollLeft >= max - 1) {
                            slider.scrollLeft = 0;
                        }
                    }
                }
                rafId = window.requestAnimationFrame(tick);
            }

            function pause() { isPaused = true; }
            function resume() { isPaused = false; }

            ensureOverflow();
            slider.addEventListener('mouseenter', pause);
            slider.addEventListener('mouseleave', resume);
            slider.addEventListener('touchstart', pause, { passive: true });
            slider.addEventListener('touchend', resume, { passive: true });
            slider.addEventListener('touchcancel', resume, { passive: true });

            window.addEventListener('beforeunload', function () {
                if (rafId) window.cancelAnimationFrame(rafId);
            });

            tick();
        });
    </script>

    <!-- Main Content -->
    <main class="container py-4">
