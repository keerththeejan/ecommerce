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
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $_SESSION['last_activity'] = date('Y-m-d H:i:s');
    
    try {
        $userModel = new User();
        $userModel->updateActivity($_SESSION['user_id'], $ip, $userAgent);
    } catch (Exception $e) {
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
    
    <!-- Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- Modern Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Modern Admin CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin-modern.css?v=<?php echo time(); ?>">
    <!-- Modern Sidebar 2026 CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/admin-sidebar-2026.css?v=<?php echo time(); ?>">
    
    <!-- Chart.js for Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin Dashboard'; ?> - POS System</title>
    
    <style>
        /* Additional custom styles for specific components */
        .metric-card {
            background: linear-gradient(135deg, var(--surface-0), var(--surface-50));
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-2xl);
            padding: var(--space-6);
            position: relative;
            overflow: hidden;
            transition: all var(--transition-base);
        }
        
        .metric-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.05) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }
        
        .metric-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-2xl);
            border-color: var(--primary-200);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin: var(--space-4) 0;
        }
        
        .activity-timeline {
            position: relative;
            padding-left: var(--space-6);
        }
        
        .activity-timeline::before {
            content: '';
            position: absolute;
            left: 12px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(180deg, var(--primary-200), var(--primary-100));
        }
        
        .activity-item {
            position: relative;
            padding-bottom: var(--space-4);
        }
        
        .activity-item::before {
            content: '';
            position: absolute;
            left: -22px;
            top: 6px;
            width: 12px;
            height: 12px;
            border-radius: var(--radius-full);
            background: var(--primary-500);
            border: 3px solid var(--surface-0);
            box-shadow: var(--shadow-md);
        }
        
        .quick-action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-4);
        }
        
        .quick-action-card {
            background: var(--surface-0);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: var(--space-5);
            text-align: center;
            transition: all var(--transition-base);
            cursor: pointer;
            text-decoration: none;
            color: inherit;
        }
        
        .quick-action-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-200);
            color: inherit;
            text-decoration: none;
        }
        
        .quick-action-icon {
            width: 48px;
            height: 48px;
            margin: 0 auto var(--space-3);
            background: linear-gradient(135deg, var(--primary-100), var(--primary-200));
            border-radius: var(--radius-xl);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--primary-600);
        }
        
        .quick-action-card:hover .quick-action-icon {
            background: linear-gradient(135deg, var(--primary-500), var(--primary-600));
            color: white;
        }
    </style>
</head>
<body>
    <div class="admin-layout" id="adminLayout">
        <!-- Modern Sidebar 2026 -->
        <?php include APP_PATH . 'views/admin/layouts/sidebar-2026.php'; ?>
        
        <!-- Mobile Overlay -->
        <div class="mobile-overlay" id="mobileOverlay"></div>
        
        <!-- Modern Top Navbar -->
        <header class="modern-navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle d-lg-none" id="mobileSidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <nav class="breadcrumb-nav">
                    <a href="<?php echo BASE_URL; ?>?controller=admin&action=dashboard" class="breadcrumb-item">Home</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-item active"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?></span>
                </nav>
            </div>
            
            <div class="navbar-center">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="search" class="search-input" placeholder="Search products, orders, customers..." id="globalSearch">
                </div>
            </div>
            
            <div class="navbar-right">
                <div class="navbar-actions">
                    <button class="action-button" id="notificationBtn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge"></span>
                    </button>
                    <button class="action-button" id="themeToggle">
                        <i class="fas fa-moon"></i>
                    </button>
                    <div class="user-menu" id="userMenu">
                        <div class="user-avatar">
                            <?php echo strtoupper(substr(isset($_SESSION['username']) ? $_SESSION['username'] : 'A', 0, 2)); ?>
                        </div>
                        <div class="user-info">
                            <div class="user-name"><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?></div>
                            <div class="user-role"><?php echo isset($_SESSION['role']) ? htmlspecialchars(ucfirst($_SESSION['role'])) : 'Administrator'; ?></div>
                        </div>
                        <i class="fas fa-chevron-down" style="color: var(--gray-400); font-size: 0.75rem;"></i>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main Content Area -->
        <main class="main-content" id="mainContent">
            <?php include $content; ?>
        </main>
    </div>
    
    <!-- User Dropdown Menu -->
    <div class="dropdown-menu dropdown-menu-end" id="userDropdown" style="display: none; position: absolute; right: 2rem; top: 5rem; z-index: 1000;">
        <div class="dropdown-header">
            <strong><?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?></strong>
            <small><?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'admin@example.com'; ?></small>
        </div>
        <hr class="dropdown-divider">
        <a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=user&action=profile">
            <i class="fas fa-user me-2"></i> Profile
        </a>
        <a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=setting&action=index">
            <i class="fas fa-cog me-2"></i> Settings
        </a>
        <hr class="dropdown-divider">
        <a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>?controller=user&action=logout">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Modern Admin JavaScript -->
    <script>
        // Modern Admin Dashboard JavaScript
        class ModernAdmin {
            constructor() {
                this.sidebar = document.getElementById('modernSidebar');
                this.layout = document.getElementById('adminLayout');
                this.mobileOverlay = document.getElementById('mobileOverlay');
                this.sidebarToggle = document.getElementById('sidebarToggle');
                this.mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
                this.userMenu = document.getElementById('userMenu');
                this.userDropdown = document.getElementById('userDropdown');
                this.themeToggle = document.getElementById('themeToggle');
                this.globalSearch = document.getElementById('globalSearch');
                
                this.init();
            }
            
            init() {
                this.setupSidebar();
                this.setupUserMenu();
                this.setupTheme();
                this.setupSearch();
                this.setupNotifications();
                this.loadDashboardData();
                this.setupAnimations();
            }
            
            setupSidebar() {
                // Desktop sidebar toggle
                this.sidebarToggle?.addEventListener('click', () => {
                    this.layout.classList.toggle('collapsed');
                    this.updateSidebarIcon();
                });
                
                // Mobile sidebar toggle
                this.mobileSidebarToggle?.addEventListener('click', () => {
                    this.layout.classList.add('mobile-open');
                });
                
                // Mobile overlay click
                this.mobileOverlay?.addEventListener('click', () => {
                    this.layout.classList.remove('mobile-open');
                });
                
                // Handle responsive behavior
                this.handleResponsive();
                window.addEventListener('resize', () => this.handleResponsive());
            }
            
            handleResponsive() {
                if (window.innerWidth > 768) {
                    this.layout.classList.remove('mobile-open');
                }
            }
            
            updateSidebarIcon() {
                const icon = this.sidebarToggle?.querySelector('i');
                if (icon) {
                    icon.className = this.layout.classList.contains('collapsed') ? 'fas fa-chevron-right' : 'fas fa-bars';
                }
            }
            
            setupUserMenu() {
                this.userMenu?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this.userDropdown.style.display = this.userDropdown.style.display === 'block' ? 'none' : 'block';
                });
                
                // Close dropdown when clicking outside
                document.addEventListener('click', () => {
                    this.userDropdown.style.display = 'none';
                });
            }
            
            setupTheme() {
                const savedTheme = localStorage.getItem('admin-theme') || 'light';
                this.applyTheme(savedTheme);
                
                this.themeToggle?.addEventListener('click', () => {
                    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    this.applyTheme(newTheme);
                    localStorage.setItem('admin-theme', newTheme);
                });
            }
            
            applyTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                const icon = this.themeToggle?.querySelector('i');
                if (icon) {
                    icon.className = theme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
                }
            }
            
            setupSearch() {
                this.globalSearch?.addEventListener('input', (e) => {
                    const query = e.target.value.trim();
                    if (query.length > 2) {
                        this.performSearch(query);
                    }
                });
                
                this.globalSearch?.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const query = e.target.value.trim();
                        if (query) {
                            window.location.href = `${BASE_URL}?controller=search&q=${encodeURIComponent(query)}`;
                        }
                    }
                });
            }
            
            performSearch(query) {
                // Implement live search functionality
                console.log('Searching for:', query);
            }
            
            setupNotifications() {
                const notificationBtn = document.getElementById('notificationBtn');
                notificationBtn?.addEventListener('click', () => {
                    // Show notifications panel
                    console.log('Show notifications');
                });
            }
            
            async loadDashboardData() {
                try {
                    // Load dashboard statistics
                    await this.loadStats();
                    
                    // Load recent activities
                    await this.loadActivities();
                    
                    // Load charts
                    this.loadCharts();
                } catch (error) {
                    console.error('Error loading dashboard data:', error);
                }
            }
            
            async loadStats() {
                try {
                    const response = await fetch(`${BASE_URL}?controller=admin&action=getStats`);
                    const stats = await response.json();
                    
                    // Update stat cards
                    this.updateStatCard('orderCount', stats.orders || 0);
                    this.updateStatCard('productCount', stats.products || 0);
                    this.updateStatCard('lowStockCount', stats.lowStock || 0);
                    this.updateStatCard('customerCount', stats.customers || 0);
                } catch (error) {
                    console.error('Error loading stats:', error);
                }
            }
            
            updateStatCard(elementId, value) {
                const element = document.getElementById(elementId);
                if (element) {
                    element.textContent = value;
                }
            }
            
            async loadActivities() {
                // Load recent activities
                console.log('Loading activities...');
            }
            
            loadCharts() {
                // Initialize charts
                this.initSalesChart();
                this.initRevenueChart();
            }
            
            initSalesChart() {
                const ctx = document.getElementById('salesChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            datasets: [{
                                label: 'Sales',
                                data: [12, 19, 3, 5, 2, 3],
                                borderColor: 'rgb(99, 102, 241)',
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                tension: 0.4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }
            
            initRevenueChart() {
                const ctx = document.getElementById('revenueChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                            datasets: [{
                                label: 'Revenue',
                                data: [12000, 19000, 3000, 5000, 2000, 3000],
                                backgroundColor: 'rgba(16, 185, 129, 0.8)'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            }
            
            setupAnimations() {
                // Animate elements on scroll
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-fade-in-up');
                        }
                    });
                });
                
                document.querySelectorAll('.stat-card, .modern-card').forEach(el => {
                    observer.observe(el);
                });
            }
        }
        
        // Initialize the modern admin when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            new ModernAdmin();
        });
        
        // Utility functions
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(amount);
        }
        
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            notification.textContent = message;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>
</html>
