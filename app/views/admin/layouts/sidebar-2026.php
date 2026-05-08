<?php
// Get current controller and action for active states
$currentController = $_GET['controller'] ?? '';
$currentAction = $_GET['action'] ?? '';
$currentUrl = $_SERVER['REQUEST_URI'] ?? '';

// Helper function to check if menu item is active
function isMenuActive($controller, $action = null) {
    global $currentController, $currentAction;
    if ($controller === $currentController) {
        if ($action === null || $action === $currentAction) {
            return true;
        }
    }
    return false;
}

// Helper function to check if section is expanded
function isSectionExpanded($section) {
    global $currentController;
    $sections = [
        'main' => ['admin'],
        'catalog' => ['product', 'category', 'brand', 'supplier', 'country'],
        'store' => ['order', 'customer', 'review', 'coupon', 'banner', 'homepage', 'inventory'],
        'content' => ['blog', 'page', 'media'],
        'system' => ['report', 'tax', 'payment', 'shipping', 'user', 'setting']
    ];
    
    return in_array($currentController, $sections[$section] ?? []);
}
?>

<!-- Modern Sidebar 2026 -->
<aside class="modern-sidebar-2026" id="modernSidebar2026">
    <!-- Sidebar Header -->
    <div class="sidebar-header-2026">
        <a href="<?php echo BASE_URL; ?>?controller=admin&action=dashboard" class="logo-container-2026">
            <div class="logo-icon-2026">
                <i class="fas fa-store"></i>
            </div>
            <span class="logo-text-2026">POS System</span>
        </a>
        <button class="sidebar-toggle-2026" id="sidebarToggle2026">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav-2026">
        <!-- MAIN SECTION -->
        <div class="menu-section-2026" data-section="main">
            <div class="menu-section-header-2026" onclick="toggleMenuSection('main')">
                <div class="menu-section-title-2026">
                    <i class="fas fa-compass"></i>
                    <span>Main</span>
                </div>
                <button class="menu-section-toggle-2026 <?php echo isSectionExpanded('main') ? '' : 'rotated'; ?>">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="menu-section-items-2026 <?php echo isSectionExpanded('main') ? '' : 'collapsed'; ?>" id="section-main">
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=admin&action=dashboard" 
                       class="menu-link-2026 <?php echo isMenuActive('admin', 'dashboard') ? 'active' : ''; ?>"
                       data-tooltip="Dashboard">
                        <div class="menu-icon-2026">
                            <i class="fas fa-th-large"></i>
                        </div>
                        <span class="menu-text-2026">Dashboard</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=analytics&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('analytics') ? 'active' : ''; ?>"
                       data-tooltip="Analytics">
                        <div class="menu-icon-2026">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="menu-text-2026">Analytics</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=sales&action=overview" 
                       class="menu-link-2026 <?php echo isMenuActive('sales') ? 'active' : ''; ?>"
                       data-tooltip="Sales Overview">
                        <div class="menu-icon-2026">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <span class="menu-text-2026">Sales Overview</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- CATALOG SECTION -->
        <div class="menu-section-2026" data-section="catalog">
            <div class="menu-section-header-2026" onclick="toggleMenuSection('catalog')">
                <div class="menu-section-title-2026">
                    <i class="fas fa-boxes"></i>
                    <span>Catalog</span>
                </div>
                <button class="menu-section-toggle-2026 <?php echo isSectionExpanded('catalog') ? '' : 'rotated'; ?>">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="menu-section-items-2026 <?php echo isSectionExpanded('catalog') ? '' : 'collapsed'; ?>" id="section-catalog">
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" 
                       class="menu-link-2026 <?php echo isMenuActive('product') ? 'active' : ''; ?>"
                       data-tooltip="Products">
                        <div class="menu-icon-2026">
                            <i class="fas fa-box"></i>
                        </div>
                        <span class="menu-text-2026">Products</span>
                        <span class="menu-badge-2026" id="productCountBadge">0</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=category&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('category') ? 'active' : ''; ?>"
                       data-tooltip="Categories">
                        <div class="menu-icon-2026">
                            <i class="fas fa-tags"></i>
                        </div>
                        <span class="menu-text-2026">Categories</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=brand&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('brand') ? 'active' : ''; ?>"
                       data-tooltip="Brands">
                        <div class="menu-icon-2026">
                            <i class="fas fa-trademark"></i>
                        </div>
                        <span class="menu-text-2026">Brands</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=supplier&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('supplier') ? 'active' : ''; ?>"
                       data-tooltip="Suppliers">
                        <div class="menu-icon-2026">
                            <i class="fas fa-truck"></i>
                        </div>
                        <span class="menu-text-2026">Suppliers</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=country&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('country') ? 'active' : ''; ?>"
                       data-tooltip="Countries">
                        <div class="menu-icon-2026">
                            <i class="fas fa-globe"></i>
                        </div>
                        <span class="menu-text-2026">Countries</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- STORE MANAGEMENT SECTION -->
        <div class="menu-section-2026" data-section="store">
            <div class="menu-section-header-2026" onclick="toggleMenuSection('store')">
                <div class="menu-section-title-2026">
                    <i class="fas fa-store-alt"></i>
                    <span>Store Management</span>
                </div>
                <button class="menu-section-toggle-2026 <?php echo isSectionExpanded('store') ? '' : 'rotated'; ?>">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="menu-section-items-2026 <?php echo isSectionExpanded('store') ? '' : 'collapsed'; ?>" id="section-store">
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" 
                       class="menu-link-2026 <?php echo isMenuActive('order') ? 'active' : ''; ?>"
                       data-tooltip="Orders">
                        <div class="menu-icon-2026">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <span class="menu-text-2026">Orders</span>
                        <span class="menu-badge-2026" id="orderCountBadge">0</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=customer&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('customer') ? 'active' : ''; ?>"
                       data-tooltip="Customers">
                        <div class="menu-icon-2026">
                            <i class="fas fa-users"></i>
                        </div>
                        <span class="menu-text-2026">Customers</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=review&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('review') ? 'active' : ''; ?>"
                       data-tooltip="Reviews">
                        <div class="menu-icon-2026">
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="menu-text-2026">Reviews</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=coupon&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('coupon') ? 'active' : ''; ?>"
                       data-tooltip="Coupons">
                        <div class="menu-icon-2026">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <span class="menu-text-2026">Coupons</span>
                    </a>
                </div>
                
                <!-- BANNER MANAGEMENT - HIGHLIGHTED SECTION -->
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=banner&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('banner') ? 'active' : ''; ?>"
                       data-tooltip="Banner Management">
                        <div class="menu-icon-2026">
                            <i class="fas fa-images"></i>
                        </div>
                        <span class="menu-text-2026">Banner Management</span>
                        <span class="menu-badge-2026" id="bannerCountBadge">!</span>
                    </a>
                </div>
                
                <!-- Banner Sub-menu Items -->
                <div class="sub-menu-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=banner&action=index" 
                       class="sub-menu-link-2026 <?php echo isMenuActive('banner', 'index') ? 'active' : ''; ?>">
                        <i class="fas fa-list" style="width: 16px; font-size: 12px;"></i>
                        <span>View All Banners</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=banner&action=add" 
                       class="sub-menu-link-2026 <?php echo isMenuActive('banner', 'add') ? 'active' : ''; ?>">
                        <i class="fas fa-plus" style="width: 16px; font-size: 12px;"></i>
                        <span>Add New Banner</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=banner&action=slider" 
                       class="sub-menu-link-2026 <?php echo isMenuActive('banner', 'slider') ? 'active' : ''; ?>">
                        <i class="fas fa-sliders-h" style="width: 16px; font-size: 12px;"></i>
                        <span>Banner Slider Settings</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=banner&action=hero" 
                       class="sub-menu-link-2026 <?php echo isMenuActive('banner', 'hero') ? 'active' : ''; ?>">
                        <i class="fas fa-desktop" style="width: 16px; font-size: 12px;"></i>
                        <span>Homepage Hero Banner</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=banner&action=mobile" 
                       class="sub-menu-link-2026 <?php echo isMenuActive('banner', 'mobile') ? 'active' : ''; ?>">
                        <i class="fas fa-mobile-alt" style="width: 16px; font-size: 12px;"></i>
                        <span>Mobile Banner Management</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=banner&action=promotional" 
                       class="sub-menu-link-2026 <?php echo isMenuActive('banner', 'promotional') ? 'active' : ''; ?>">
                        <i class="fas fa-bullhorn" style="width: 16px; font-size: 12px;"></i>
                        <span>Promotional Banners</span>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=banner&action=campaigns" 
                       class="sub-menu-link-2026 <?php echo isMenuActive('banner', 'campaigns') ? 'active' : ''; ?>">
                        <i class="fas fa-flag" style="width: 16px; font-size: 12px;"></i>
                        <span>Offer Campaign Banners</span>
                    </a>
                </div>
                
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=homepage&action=sections" 
                       class="menu-link-2026 <?php echo isMenuActive('homepage') ? 'active' : ''; ?>"
                       data-tooltip="Homepage Sections">
                        <div class="menu-icon-2026">
                            <i class="fas fa-home"></i>
                        </div>
                        <span class="menu-text-2026">Homepage Sections</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=inventory&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('inventory') ? 'active' : ''; ?>"
                       data-tooltip="Inventory">
                        <div class="menu-icon-2026">
                            <i class="fas fa-warehouse"></i>
                        </div>
                        <span class="menu-text-2026">Inventory</span>
                        <span class="menu-badge-2026" id="lowStockBadge">0</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- CONTENT SECTION -->
        <div class="menu-section-2026" data-section="content">
            <div class="menu-section-header-2026" onclick="toggleMenuSection('content')">
                <div class="menu-section-title-2026">
                    <i class="fas fa-pen-fancy"></i>
                    <span>Content</span>
                </div>
                <button class="menu-section-toggle-2026 <?php echo isSectionExpanded('content') ? '' : 'rotated'; ?>">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="menu-section-items-2026 <?php echo isSectionExpanded('content') ? '' : 'collapsed'; ?>" id="section-content">
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=blog&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('blog') ? 'active' : ''; ?>"
                       data-tooltip="Blog Management">
                        <div class="menu-icon-2026">
                            <i class="fas fa-blog"></i>
                        </div>
                        <span class="menu-text-2026">Blog Management</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=page&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('page') ? 'active' : ''; ?>"
                       data-tooltip="Pages">
                        <div class="menu-icon-2026">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="menu-text-2026">Pages</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=media&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('media') ? 'active' : ''; ?>"
                       data-tooltip="Media Library">
                        <div class="menu-icon-2026">
                            <i class="fas fa-photo-video"></i>
                        </div>
                        <span class="menu-text-2026">Media Library</span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- SYSTEM SECTION -->
        <div class="menu-section-2026" data-section="system">
            <div class="menu-section-header-2026" onclick="toggleMenuSection('system')">
                <div class="menu-section-title-2026">
                    <i class="fas fa-cogs"></i>
                    <span>System</span>
                </div>
                <button class="menu-section-toggle-2026 <?php echo isSectionExpanded('system') ? '' : 'rotated'; ?>">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="menu-section-items-2026 <?php echo isSectionExpanded('system') ? '' : 'collapsed'; ?>" id="section-system">
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=report&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('report') ? 'active' : ''; ?>"
                       data-tooltip="Reports">
                        <div class="menu-icon-2026">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <span class="menu-text-2026">Reports</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=tax&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('tax') ? 'active' : ''; ?>"
                       data-tooltip="Tax Management">
                        <div class="menu-icon-2026">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <span class="menu-text-2026">Tax Management</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=payment&action=settings" 
                       class="menu-link-2026 <?php echo isMenuActive('payment') ? 'active' : ''; ?>"
                       data-tooltip="Payment Settings">
                        <div class="menu-icon-2026">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <span class="menu-text-2026">Payment Settings</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=shipping&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('shipping') ? 'active' : ''; ?>"
                       data-tooltip="Shipping">
                        <div class="menu-icon-2026">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <span class="menu-text-2026">Shipping</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('user') ? 'active' : ''; ?>"
                       data-tooltip="Users & Roles">
                        <div class="menu-icon-2026">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <span class="menu-text-2026">Users & Roles</span>
                    </a>
                </div>
                <div class="menu-item-2026">
                    <a href="<?php echo BASE_URL; ?>?controller=setting&action=index" 
                       class="menu-link-2026 <?php echo isMenuActive('setting') ? 'active' : ''; ?>"
                       data-tooltip="Settings">
                        <div class="menu-icon-2026">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span class="menu-text-2026">Settings</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</aside>

<!-- JavaScript for Sidebar Functionality -->
<script>
// Modern Sidebar 2026 JavaScript
class ModernSidebar2026 {
    constructor() {
        this.sidebar = document.getElementById('modernSidebar2026');
        this.toggleBtn = document.getElementById('sidebarToggle2026');
        this.isCollapsed = false;
        this.init();
    }
    
    init() {
        // Load saved state
        this.loadState();
        
        // Setup toggle button
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.toggle());
        }
        
        // Setup responsive behavior
        this.setupResponsive();
        
        // Load dashboard data
        this.loadDashboardCounts();
        
        // Setup keyboard shortcuts
        this.setupKeyboardShortcuts();
    }
    
    toggle() {
        this.isCollapsed = !this.isCollapsed;
        this.sidebar.classList.toggle('collapsed');
        this.saveState();
        this.updateToggleIcon();
    }
    
    collapse() {
        this.isCollapsed = true;
        this.sidebar.classList.add('collapsed');
        this.saveState();
        this.updateToggleIcon();
    }
    
    expand() {
        this.isCollapsed = false;
        this.sidebar.classList.remove('collapsed');
        this.saveState();
        this.updateToggleIcon();
    }
    
    updateToggleIcon() {
        const icon = this.toggleBtn?.querySelector('i');
        if (icon) {
            icon.className = this.isCollapsed ? 'fas fa-chevron-right' : 'fas fa-bars';
        }
    }
    
    saveState() {
        localStorage.setItem('sidebar-collapsed', this.isCollapsed.toString());
    }
    
    loadState() {
        const saved = localStorage.getItem('sidebar-collapsed');
        if (saved === 'true') {
            this.isCollapsed = true;
            this.sidebar.classList.add('collapsed');
        }
        this.updateToggleIcon();
    }
    
    setupResponsive() {
        const handleResize = () => {
            if (window.innerWidth <= 768) {
                this.sidebar.classList.remove('collapsed');
            } else if (this.isCollapsed) {
                this.sidebar.classList.add('collapsed');
            }
        };
        
        window.addEventListener('resize', handleResize);
        handleResize();
    }
    
    async loadDashboardCounts() {
        try {
            const response = await fetch(`${BASE_URL}?controller=admin&action=getStats`);
            const stats = await response.json();
            
            // Update badges
            this.updateBadge('productCountBadge', stats.products || 0);
            this.updateBadge('orderCountBadge', stats.orders || 0);
            this.updateBadge('lowStockBadge', stats.lowStock || 0);
            this.updateBadge('bannerCountBadge', stats.banners || 0, true);
            
        } catch (error) {
            console.error('Error loading dashboard counts:', error);
        }
    }
    
    updateBadge(elementId, count, highlight = false) {
        const badge = document.getElementById(elementId);
        if (badge) {
            badge.textContent = count;
            if (highlight && count > 0) {
                badge.style.background = 'linear-gradient(135deg, var(--neon-blue-500), var(--neon-blue-600))';
                badge.style.boxShadow = '0 0 20px rgba(59, 130, 246, 0.5)';
            }
        }
    }
    
    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            // Ctrl/Cmd + B to toggle sidebar
            if ((e.ctrlKey || e.metaKey) && e.key === 'b') {
                e.preventDefault();
                this.toggle();
            }
        });
    }
}

// Menu section toggle function
function toggleMenuSection(sectionId) {
    const section = document.getElementById(`section-${sectionId}`);
    const toggle = document.querySelector(`[data-section="${sectionId}"] .menu-section-toggle-2026`);
    
    if (section && toggle) {
        section.classList.toggle('collapsed');
        toggle.classList.toggle('rotated');
        
        // Save expanded state
        const expandedSections = JSON.parse(localStorage.getItem('expanded-sections') || '{}');
        expandedSections[sectionId] = !section.classList.contains('collapsed');
        localStorage.setItem('expanded-sections', JSON.stringify(expandedSections));
    }
}

// Initialize sidebar when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.modernSidebar = new ModernSidebar2026();
    
    // Restore expanded sections
    const expandedSections = JSON.parse(localStorage.getItem('expanded-sections') || '{}');
    Object.keys(expandedSections).forEach(sectionId => {
        if (!expandedSections[sectionId]) {
            toggleMenuSection(sectionId);
        }
    });
});
</script>
