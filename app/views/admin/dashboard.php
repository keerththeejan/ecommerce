<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>
<style>
/* Admin Dashboard - Trending UI */
.admin-dash { min-height: 100vh; }
.admin-dash .page-hero {
  background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
  border-radius: 16px;
  padding: 1.5rem 2rem;
  margin-bottom: 2rem;
  color: #fff;
  box-shadow: 0 10px 40px rgba(79, 70, 229, 0.3);
}
.admin-dash .page-hero h1 { font-size: 1.75rem; font-weight: 700; margin: 0; }
.admin-dash .page-hero p { margin: 0.25rem 0 0; opacity: .9; font-size: 0.95rem; }

.dashboard-stats .card {
  min-height: 1px;
  border: none;
  border-radius: 12px;
  overflow: hidden;
  transition: transform .25s ease, box-shadow .25s ease;
}
.dashboard-stats .card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 28px rgba(0,0,0,.15) !important;
}
.dashboard-stats .card .card-body { padding: 1.25rem; }
.dashboard-stats .card .card-footer {
  padding: 0.6rem 1rem;
  background: rgba(0,0,0,.08);
  border: none;
}
.dashboard-stats .card .card-footer a { transition: opacity .2s; }
.dashboard-stats .card:hover .card-footer a { opacity: 1; }
.dashboard-stats h2 { font-size: clamp(1.5rem, 4vw, 2rem); font-weight: 700; }
.dashboard-stats h6 { font-size: 0.75rem; letter-spacing: 0.05em; }
.dashboard-stats .fa-2x { opacity: .85; transition: transform .3s; }
.dashboard-stats .card:hover .fa-2x { transform: scale(1.1); }
@media (max-width: 575.98px) {
  .dashboard-stats .card-body { padding: 1rem; }
  .dashboard-stats .card-footer a { font-size: 0.875rem; }
  .admin-dash .page-hero { padding: 1rem 1.25rem; }
}

.admin-dash .card.shadow-sm {
  border: none;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0,0,0,.08);
  transition: box-shadow .25s;
}
.admin-dash .card.shadow-sm:hover { box-shadow: 0 8px 30px rgba(0,0,0,.12); }
.admin-dash .card-header {
  background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
  border-bottom: 1px solid #e2e8f0;
  font-weight: 600;
  padding: 1rem 1.25rem;
  border-radius: 12px 12px 0 0;
}
.admin-dash .list-group-item { border-radius: 8px !important; margin-bottom: 0.25rem; }
.admin-dash .list-group-item:hover { background: #f8fafc; }
.admin-dash .table { font-size: 0.9rem; }
.admin-dash .table thead th {
  font-weight: 600;
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #64748b;
  border-bottom: 2px solid #e2e8f0;
}
.admin-dash .table tbody tr { transition: background .15s; }
.admin-dash .table tbody tr:hover { background: rgba(79, 70, 229, 0.04) !important; }
.dashboard-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

/* Staggered fade-in animation */
@keyframes dashFadeIn {
  from { opacity: 0; transform: translateY(12px); }
  to { opacity: 1; transform: translateY(0); }
}
.dashboard-stats .card { animation: dashFadeIn .5s ease forwards; }
.dashboard-stats > div:nth-child(1) .card { animation-delay: .05s; }
.dashboard-stats > div:nth-child(2) .card { animation-delay: .1s; }
.dashboard-stats > div:nth-child(3) .card { animation-delay: .15s; }
.dashboard-stats > div:nth-child(4) .card { animation-delay: .2s; }
.admin-dash .page-hero { animation: dashFadeIn .4s ease; }
.admin-dash .row.mb-4:last-of-type .card { animation: dashFadeIn .5s ease .25s both; }
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 admin-dash">
    <div class="page-hero d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
        <div>
            <h1><i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard</h1>
            <p>Welcome back. Here's what's happening with your store today.</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="<?php echo BASE_URL; ?>?controller=pos&action=index" class="btn btn-success btn-sm mr-2"><i class="fas fa-cash-register mr-1"></i>POS</a>
            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-light btn-sm mr-2"><i class="fas fa-box mr-1"></i>Products</a>
            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-outline-light btn-sm"><i class="fas fa-shopping-cart mr-1"></i>Orders</a>
        </div>
    </div>
    <!-- Dashboard Stats -->
    <div class="row mb-4">
        <div class="col-12 mb-3">
            <h5 class="text-muted font-weight-bold mb-0"><i class="fas fa-chart-bar mr-2"></i>Overview</h5>
        </div>
    </div>
    <div class="row g-3 g-md-4 mb-4 dashboard-stats">
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-xl-0">
            <div class="card bg-primary text-white h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase opacity-90">Total Orders</h6>
                            <h2 class="mb-0">
                                <?php 
                                    try {
                                        $orderModel = class_exists('Order') ? new Order() : null;
                                        echo $orderModel ? $orderModel->count() : 0;
                                    } catch (Exception $e) {
                                        error_log('Dashboard Order count: ' . $e->getMessage());
                                        echo '0';
                                    }
                                ?>
                            </h2>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 d-flex align-items-center justify-content-between">
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="text-white text-decoration-none small">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-xl-0">
            <div class="card bg-success text-white h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase opacity-90">Total Products</h6>
                            <h2 class="mb-0">
                                <?php 
                                    try {
                                        $productModel = class_exists('Product') ? new Product() : null;
                                        echo $productModel ? $productModel->count() : 0;
                                    } catch (Exception $e) {
                                        error_log('Dashboard Product count: ' . $e->getMessage());
                                        echo '0';
                                    }
                                ?>
                            </h2>
                        </div>
                        <i class="fas fa-box fa-2x opacity-75"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 d-flex align-items-center justify-content-between">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="text-white text-decoration-none small">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-xl-0">
            <div class="card bg-warning text-dark h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase opacity-90">Total Customers</h6>
                            <h2 class="mb-0">
                                <?php 
                                    try {
                                        $userModel = class_exists('User') ? new User() : null;
                                        echo $userModel && method_exists($userModel, 'getCustomers') ? count($userModel->getCustomers()) : 0;
                                    } catch (Exception $e) {
                                        error_log('Dashboard User getCustomers: ' . $e->getMessage());
                                        echo '0';
                                    }
                                ?>
                            </h2>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 d-flex align-items-center justify-content-between">
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=customers" class="text-dark text-decoration-none small font-weight-bold">View Details</a>
                    <i class="fas fa-angle-right"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-3 mb-xl-0">
            <div class="card bg-danger text-white h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase opacity-90">Total Revenue</h6>
                            <h2 class="mb-0">
                                <?php 
                                    try {
                                        $dbRev = new Database();
                                        $dbRev->query("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'");
                                        $result = $dbRev->single();
                                        echo function_exists('formatPrice') ? formatPrice($result['total'] ?? 0) : number_format((float)($result['total'] ?? 0), 2);
                                    } catch (Exception $e) {
                                        error_log('Dashboard Revenue: ' . $e->getMessage());
                                        echo function_exists('formatPrice') ? formatPrice(0) : '0.00';
                                    }
                                ?>
                            </h2>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 d-flex align-items-center justify-content-between">
                    <a href="<?php echo BASE_URL; ?>?controller=report&action=index" class="text-white text-decoration-none small">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Low Stock & Recent Orders -->
    <div class="row mb-3">
        <div class="col-12">
            <h5 class="text-muted font-weight-bold mb-0"><i class="fas fa-bell mr-2"></i>Alerts & Activity</h5>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12 col-lg-6 mb-4 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Low Stock Alert</h5>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-primary btn-sm">Manage</a>
                </div>
                <div class="card-body">
                    <?php 
                    $lowStockProducts = [];
                    try {
                        $productModel = new Product();
                        $lowStockProducts = $productModel->getLowStockProducts(5);
                    } catch (Exception $e) {
                        error_log('Low stock: ' . $e->getMessage());
                    }
                    if (!empty($lowStockProducts)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach($lowStockProducts as $product): ?>
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo (int)$product['id']; ?>" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    <span class="text-truncate"><?php echo htmlspecialchars($product['name']); ?></span>
                                    <span class="badge badge-<?php echo $product['stock_quantity'] <= 0 ? 'danger' : 'warning'; ?> rounded-pill ml-2"><?php echo (int)$product['stock_quantity']; ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-muted mb-0">No low stock products.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <?php 
                    $hasOrdersTable = true;
                    try {
                        $db = new Database();
                        $db->query("SHOW TABLES LIKE 'orders'");
                        $hasOrdersTable = (bool)$db->single();
                    } catch (Exception $e) {
                        $hasOrdersTable = false;
                        error_log('Error checking orders table: ' . $e->getMessage());
                    }
                    
                    if ($hasOrdersTable && !empty($recentOrders)) : ?>
                        <div class="table-responsive dashboard-table-wrap">
                            <table id="dashboardRecentOrders" class="table table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th data-label="ID">ID</th>
                                        <th data-label="Customer">Customer</th>
                                        <th data-label="Amount">Amount</th>
                                        <th data-label="Status">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recentOrders as $order) : ?>
                                        <tr>
                                            <td data-label="ID"><?php echo $order['id']; ?></td>
                                            <td data-label="Customer"><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                                            <td data-label="Amount"><?php echo formatPrice($order['total_amount']); ?></td>
                                            <td data-label="Status">
                                                <?php if($order['status'] == 'pending') : ?>
                                                    <span class="badge badge-warning text-dark">Pending</span>
                                                <?php elseif($order['status'] == 'processing') : ?>
                                                    <span class="badge badge-info">Processing</span>
                                                <?php elseif($order['status'] == 'shipped') : ?>
                                                    <span class="badge badge-primary">Shipped</span>
                                                <?php elseif($order['status'] == 'delivered') : ?>
                                                    <span class="badge badge-success">Delivered</span>
                                                <?php elseif($order['status'] == 'cancelled') : ?>
                                                    <span class="badge badge-danger">Cancelled</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <p class="text-center">No recent orders found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    // Count-up animation for stat numbers (Orders, Products, Customers only)
    function animateValue(el, start, end, duration) {
        var startTime = null;
        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var progress = Math.min((timestamp - startTime) / duration, 1);
            var easeOut = 1 - Math.pow(1 - progress, 2);
            el.textContent = Math.floor(easeOut * (end - start) + start);
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }
    document.addEventListener('DOMContentLoaded', function() {
        var statCards = document.querySelectorAll('.dashboard-stats .card h2');
        var skipCount = 0;
        statCards.forEach(function(el) {
            skipCount++;
            if (skipCount === 4) return; // Skip Revenue (has currency formatting)
            var text = el.textContent.trim();
            var num = parseInt(text.replace(/[^0-9]/g, ''), 10);
            if (!isNaN(num) && num <= 99999 && /^\d+$/.test(text)) {
                el.textContent = '0';
                setTimeout(function() { animateValue(el, 0, num, 800); }, 150 + skipCount * 50);
            }
        });
    });
})();
</script>
<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
