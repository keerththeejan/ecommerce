<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sivakamy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
</head>
<body style="background-color: #fff;" class="mobile-nav-fixed d-flex flex-column min-vh-100">
    <?php
    // Get site settings
    $settingModel = new Setting();
    $siteLogo = $settingModel->getSetting('site_logo');
    $siteName = $settingModel->getSetting('site_name') ?: 'Sivakamy';
    ?>
    
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
    <div class="d-md-none border-bottom mobile-top-bar">
        <!-- Top Bar -->
        <div class="container py-2">
            <div class="d-flex align-items-center justify-content-between">
                <a href="<?php echo BASE_URL; ?>" class="text-decoration-none">
                    <?php if(!empty($siteLogo) && file_exists(UPLOAD_PATH . $siteLogo)): ?>
                        <img src="<?php echo BASE_URL . 'public/uploads/' . htmlspecialchars($siteLogo); ?>" 
                             alt="<?php echo htmlspecialchars($siteName); ?>" 
                             style="max-height: 30px;">
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
        <div class="collapse bg-white" id="mobileNavbar">
            <div class="container py-2">
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
            padding-top: 70px !important;
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
            margin-top: 20px;
        }
    </style>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom d-none d-md-block">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>">
                <?php if(!empty($siteLogo) && file_exists(UPLOAD_PATH . $siteLogo)): ?>
                    <img src="<?php echo BASE_URL . 'public/uploads/' . htmlspecialchars($siteLogo); ?>" alt="<?php echo htmlspecialchars($siteName); ?>" style="max-height: 40px;">
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
                                foreach($countries as $country) :
                            ?>
                                <?php 
                                    $countryCode = strtolower(substr($country['name'], 0, 2));
                                    $flagImage = !empty($country['flag_image']) ? 
                                        BASE_URL . 'uploads/flags/' . $country['flag_image'] : 
                                        'https://flagcdn.com/24x18/' . $countryCode . '.png';
                                ?>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="<?php echo BASE_URL; ?>?controller=country&action=show&param=<?php echo $country['id']; ?>">
                                        <img src="<?php echo $flagImage; ?>" 
                                             alt="<?php echo htmlspecialchars($country['name']); ?>" 
                                             class="me-2" 
                                             style="width: 24px; height: 18px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 2px;">
                                        <?php echo htmlspecialchars($country['name']); ?>
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
                            ?>
                                <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=brand&action=show&param=<?php echo $brand['id']; ?>"><?php echo $brand['name']; ?></a></li>
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
                    <form class="d-none d-md-flex w-100 mb-3 mb-lg-0 me-lg-2" action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET">
                        <input type="hidden" name="controller" value="product">
                        <input type="hidden" name="action" value="search">
                        <input class="form-control me-2" type="search" name="keyword" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-dark" type="submit">Search</button>
                    </form>
                    <ul class="navbar-nav ms-lg-2">
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

    <!-- Main Content -->
    <main class="container py-4">

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

    <!-- Main Content -->
    <main class="container py-4">
