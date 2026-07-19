<?php
// Get dashboard statistics
try {
    // Load required models and classes
    if (!class_exists('Database')) {
        require_once APP_PATH . 'config/Database.php';
    }
    if (!class_exists('Order')) {
        require_once APP_PATH . 'models/Order.php';
    }
    if (!class_exists('Product')) {
        require_once APP_PATH . 'models/Product.php';
    }
    if (!class_exists('User')) {
        require_once APP_PATH . 'models/User.php';
    }
    
    $orderModel = class_exists('Order') ? new Order() : null;
    $productModel = class_exists('Product') ? new Product() : null;
    $userModel = class_exists('User') ? new User() : null;
    
    $totalOrders = $orderModel ? $orderModel->count() : 0;
    $totalProducts = $productModel ? $productModel->count() : 0;
    $totalCustomers = $userModel && method_exists($userModel, 'getCustomers') ? count($userModel->getCustomers()) : 0;
    
    // Get revenue
    $dbRev = new Database();
    $dbRev->query("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'");
    $result = $dbRev->single();
    $totalRevenue = $result['total'] ?? 0;
    
    // Get low stock products
    $lowStockThreshold = 5;
    $dbStock = new Database();
    $dbStock->query("SELECT p.*, c.name as category_name 
                    FROM products p 
                    LEFT JOIN categories c ON p.category_id = c.id 
                    WHERE p.stock_quantity <= :threshold 
                    ORDER BY p.stock_quantity ASC 
                    LIMIT 10");
    $dbStock->bind(':threshold', $lowStockThreshold);
    $lowStockProducts = $dbStock->resultSet();
    
    // Get recent orders
    $dbOrders = new Database();
    $dbOrders->query("SELECT o.*, u.first_name, u.last_name 
                     FROM orders o 
                     LEFT JOIN users u ON o.user_id = u.id 
                     ORDER BY o.created_at DESC 
                     LIMIT 5");
    $recentOrders = $dbOrders->resultSet();
    
    // Get monthly sales data
    $dbSales = new Database();
    $dbSales->query("SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count, SUM(total_amount) as total
                     FROM orders 
                     WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                     GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                     ORDER BY month ASC");
    $monthlySales = $dbSales->resultSet();
    
} catch (Exception $e) {
    error_log('Dashboard data error: ' . $e->getMessage());
    $totalOrders = 0;
    $totalProducts = 0;
    $totalCustomers = 0;
    $totalRevenue = 0;
    $lowStockProducts = [];
    $recentOrders = [];
    $monthlySales = [];
}
?>

<!-- Welcome Header -->
<div class="welcome-header animate-fade-in-up">
    <div class="row align-items-center">
        <div class="col-md-6">
            <h1 class="h2 mb-2">Welcome back, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Admin'; ?>! 👋</h1>
            <p class="text-muted mb-0">Here's what's happening with your store today.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
                <button class="btn btn-modern primary" onclick="window.location.href='<?php echo BASE_URL; ?>?controller=pos&action=index'">
                    <i class="fas fa-cash-register me-2"></i>Start POS
                </button>
                <button class="btn btn-modern" onclick="window.location.href='<?php echo BASE_URL; ?>?controller=product&action=create'">
                    <i class="fas fa-plus me-2"></i>Add Product
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Stats Grid -->
<div class="dashboard-grid animate-fade-in-up">
    <!-- Total Orders Card -->
    <div class="stat-card primary">
        <div class="stat-header">
            <div>
                <h6 class="stat-title">Total Orders</h6>
                <div class="stat-value"><?php echo number_format($totalOrders); ?></div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12% from last month</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
        <div class="stat-footer">
            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="stat-link">
                View all orders <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Total Products Card -->
    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <h6 class="stat-title">Total Products</h6>
                <div class="stat-value"><?php echo number_format($totalProducts); ?></div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>+5% from last month</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
        <div class="stat-footer">
            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="stat-link">
                Manage products <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Total Customers Card -->
    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <h6 class="stat-title">Total Customers</h6>
                <div class="stat-value"><?php echo number_format($totalCustomers); ?></div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8% from last month</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-footer">
            <a href="<?php echo BASE_URL; ?>?controller=customer&action=index" class="stat-link">
                View customers <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    
    <!-- Total Revenue Card -->
    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <h6 class="stat-title">Total Revenue</h6>
                <div class="stat-value"><?php echo function_exists('formatPrice') ? formatPrice($totalRevenue) : '$' . number_format($totalRevenue, 2); ?></div>
                <div class="stat-trend trend-up">
                    <i class="fas fa-arrow-up"></i>
                    <span>+23% from last month</span>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="stat-footer">
            <a href="<?php echo BASE_URL; ?>?controller=report&action=index" class="stat-link">
                View reports <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>

<!-- Analytics Section -->
<div class="row mb-4">
    <div class="col-lg-8 mb-4">
        <div class="modern-card animate-fade-in-up">
            <div class="card-header-modern">
                <div>
                    <h3 class="card-title-modern">
                        <i class="fas fa-chart-line text-primary"></i>
                        Sales Analytics
                    </h3>
                    <p class="card-subtitle-modern">Monthly sales performance</p>
                </div>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-modern active">6M</button>
                    <button class="btn btn-modern">1Y</button>
                    <button class="btn btn-modern">All</button>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="modern-card animate-fade-in-up">
            <div class="card-header-modern">
                <div>
                    <h3 class="card-title-modern">
                        <i class="fas fa-chart-pie text-success"></i>
                        Revenue Breakdown
                    </h3>
                    <p class="card-subtitle-modern">This month's revenue</p>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Activity Timeline -->
<div class="row mb-4">
    <div class="col-lg-4 mb-4">
        <div class="modern-card animate-fade-in-up">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-bolt text-warning"></i>
                    Quick Actions
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="quick-action-grid">
                    <a href="<?php echo BASE_URL; ?>?controller=pos&action=index" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                        <h6 class="mb-1">New Sale</h6>
                        <small class="text-muted">Start POS</small>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=add" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-plus"></i>
                        </div>
                        <h6 class="mb-1">Add Product</h6>
                        <small class="text-muted">New item</small>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h6 class="mb-1">View Orders</h6>
                        <small class="text-muted">Manage</small>
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=customer&action=add" class="quick-action-card">
                        <div class="quick-action-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h6 class="mb-1">Add Customer</h6>
                        <small class="text-muted">New client</small>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-8 mb-4">
        <div class="modern-card animate-fade-in-up">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-clock text-info"></i>
                    Recent Activity
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="activity-timeline">
                    <?php if (!empty($recentOrders)): ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <div class="activity-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h6 class="mb-1">New Order #<?php echo $order['id']; ?></h6>
                                        <p class="text-muted mb-1">
                                            <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?> placed an order
                                        </p>
                                        <small class="text-muted">
                                            <?php echo date('M j, Y H:i', strtotime($order['created_at'])); ?>
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold"><?php echo function_exists('formatPrice') ? formatPrice($order['total_amount']) : '$' . number_format($order['total_amount'], 2); ?></div>
                                        <span class="status-badge <?php echo $order['status']; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert & Recent Orders -->
<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="modern-card animate-fade-in-up">
            <div class="card-header-modern">
                <div>
                    <h3 class="card-title-modern">
                        <i class="fas fa-exclamation-triangle text-danger"></i>
                        Low Stock Alert
                    </h3>
                    <p class="card-subtitle-modern">Products that need restocking</p>
                </div>
                <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-modern">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body-modern p-0">
                <?php if (!empty($lowStockProducts)): ?>
                    <div class="table-responsive">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lowStockProducts as $product): ?>
                                    <?php
                                    $stock = (int)($product['stock_quantity'] ?? 0);
                                    $isCritical = $stock <= 2;
                                    $status = $isCritical ? 'critical' : 'low';
                                    $categoryName = trim($product['category_name'] ?? 'Uncategorized');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="product-avatar me-3">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium"><?php echo htmlspecialchars($product['name']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo htmlspecialchars($product['sku'] ?? 'N/A'); ?></span>
                                        </td>
                                        <td><?php echo htmlspecialchars($categoryName); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold"><?php echo $stock; ?></span>
                                                <div class="progress-modern" style="width: 60px;">
                                                    <div class="progress-bar <?php echo $isCritical ? 'danger' : 'warning'; ?>" 
                                                         style="width: <?php echo min(100, ($stock / $lowStockThreshold) * 100); ?>%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge <?php echo $status; ?>">
                                                <?php echo ucfirst($status); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo $product['id']; ?>" 
                                                   class="btn btn-modern" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>?controller=stock&action=adjust&id=<?php echo $product['id']; ?>" 
                                                   class="btn btn-modern primary" title="Restock">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5 class="text-success">All Stock Levels Good</h5>
                        <p class="text-muted">No products are currently low on stock.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 mb-4">
        <div class="modern-card animate-fade-in-up">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-bar text-primary"></i>
                    Top Products
                </h3>
            </div>
            <div class="card-body-modern">
                <?php
                // Get top selling products
                try {
                    $dbTop = new Database();
                    $dbTop->query("SELECT p.name, SUM(oi.quantity) as sold, SUM(oi.total) as revenue
                                  FROM products p
                                  JOIN order_items oi ON p.id = oi.product_id
                                  JOIN orders o ON oi.order_id = o.id
                                  WHERE o.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                                  GROUP BY p.id, p.name
                                  ORDER BY sold DESC
                                  LIMIT 5");
                    $topProducts = $dbTop->resultSet();
                } catch (Exception $e) {
                    $topProducts = [];
                }
                ?>
                
                <?php if (!empty($topProducts)): ?>
                    <?php foreach ($topProducts as $index => $product): ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                <div class="product-rank me-3">
                                    <span class="badge bg-primary rounded-circle"><?php echo $index + 1; ?></span>
                                </div>
                                <div>
                                    <div class="fw-medium"><?php echo htmlspecialchars($product['name']); ?></div>
                                    <small class="text-muted"><?php echo $product['sold']; ?> sold</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold"><?php echo function_exists('formatPrice') ? formatPrice($product['revenue']) : '$' . number_format($product['revenue'], 2); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-box fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No sales data available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.product-avatar {
    width: 40px;
    height: 40px;
    background: var(--primary-100);
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-600);
}

.product-rank .badge {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: 700;
}

.welcome-header {
    margin-bottom: var(--space-8);
    padding: var(--space-6);
    background: linear-gradient(135deg, var(--primary-50), var(--surface-0));
    border-radius: var(--radius-2xl);
    border: 1px solid var(--primary-100);
}

.btn-group .btn-modern.active {
    background: var(--primary-500);
    color: white;
    border-color: var(--primary-500);
}
</style>
