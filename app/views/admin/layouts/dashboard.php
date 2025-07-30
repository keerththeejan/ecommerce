<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Sidebar styles */
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: #fff;
            transition: all 0.3s;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        .main-content {
            padding: 20px;
        }
        .navbar-brand {
            padding: 0.5rem 1rem;
        }
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="d-flex flex-column p-3">
                    <a href="<?php echo BASE_URL; ?>admin" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-4">Admin Panel</span>
                    </a>
                    <hr>
                    <ul class="nav nav-pills flex-column mb-auto">
                        <li class="nav-item">
                            <a href="<?php echo BASE_URL; ?>admin" class="nav-link <?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/banners" class="nav-link <?php echo $current_page == 'banners' ? 'active' : ''; ?>">
                                <i class="fas fa-images"></i> Banners
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/products" class="nav-link <?php echo $current_page == 'products' ? 'active' : ''; ?>">
                                <i class="fas fa-box"></i> Products
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/categories" class="nav-link <?php echo $current_page == 'categories' ? 'active' : ''; ?>">
                                <i class="fas fa-list"></i> Categories
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/orders" class="nav-link <?php echo $current_page == 'orders' ? 'active' : ''; ?>">
                                <i class="fas fa-shopping-cart"></i> Orders
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/users" class="nav-link <?php echo $current_page == 'users' ? 'active' : ''; ?>">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo BASE_URL; ?>admin/settings" class="nav-link <?php echo $current_page == 'settings' ? 'active' : ''; ?>">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                    </ul>
                    <hr>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fs-4 me-2"></i>
                            <strong>Admin</strong>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>logout">Sign out</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-0">
                <!-- Top navigation -->
                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                    <div class="container-fluid">
                        <button class="btn btn-link" id="sidebarToggle">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="d-flex align-items-center">
                            <span class="me-3">Welcome, Admin</span>
                            <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Profile">
                        </div>
                    </div>
                </nav>

                <!-- Page content -->
                <main class="main-content">
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php endif; ?>
                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    
                    <?php echo $content; ?>
                </main>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('d-none');
        });

        // Handle active menu items
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocation = location.href;
            const menuItems = document.querySelectorAll('.nav-link');
            
            menuItems.forEach(item => {
                if (item.href === currentLocation) {
                    item.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
