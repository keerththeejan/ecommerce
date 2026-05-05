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

.low-stock-card {
  background: #fff;
  border: 1px solid rgba(15, 23, 42, 0.08);
  border-radius: 12px;
  box-shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
  overflow: hidden;
}
.low-stock-card .card-header {
  background: #fff;
  padding: 1rem 1.25rem;
}
.low-stock-toolbar {
  display: grid;
  grid-template-columns: minmax(220px, 1fr) minmax(150px, 190px) minmax(140px, 170px) auto;
  gap: 0.75rem;
  padding: 1rem 1.25rem;
  background: #f8fafc;
  border-bottom: 1px solid #e5e7eb;
}
.low-stock-control {
  position: relative;
}
.low-stock-control i {
  position: absolute;
  left: 0.8rem;
  top: 50%;
  transform: translateY(-50%);
  color: #64748b;
  pointer-events: none;
}
.low-stock-control .form-control,
.low-stock-toolbar .form-select {
  border-color: #dbe3ef;
  border-radius: 10px;
  min-height: 42px;
  font-size: 0.9rem;
}
.low-stock-control .form-control {
  padding-left: 2.25rem;
}
.low-stock-scroll {
  max-height: 400px;
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
}
.low-stock-scroll table {
  min-width: 920px;
  margin-bottom: 0;
}
.low-stock-scroll thead th {
  position: sticky;
  top: 0;
  z-index: 2;
  background: #f8fafc !important;
  box-shadow: inset 0 -1px 0 #e5e7eb;
}
.low-stock-scroll tbody tr {
  animation: dashFadeIn .28s ease both;
}
.stock-product-name {
  max-width: 260px;
}
.stock-progress {
  width: 120px;
  height: 8px;
  border-radius: 999px;
  background: #edf2f7;
  overflow: hidden;
}
.stock-progress > span {
  display: block;
  height: 100%;
  border-radius: inherit;
}
.stock-progress-critical { background: linear-gradient(90deg, #dc2626, #ef4444); }
.stock-progress-low { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
.stock-status {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  padding: 0.38rem 0.62rem;
  border-radius: 999px;
  font-size: 0.78rem;
  font-weight: 700;
}
.stock-status-critical {
  background: #fee2e2;
  color: #991b1b;
}
.stock-status-low {
  background: #fef3c7;
  color: #92400e;
}
.low-stock-actions {
  display: inline-flex;
  align-items: center;
  gap: 0.45rem;
  white-space: nowrap;
}
.low-stock-empty {
  padding: 2.25rem 1rem;
}
.low-stock-pagination {
  padding: 0.9rem 1.25rem;
  background: #fff;
  border-top: 1px solid #e5e7eb;
}
@media (max-width: 991.98px) {
  .low-stock-toolbar {
    grid-template-columns: 1fr 1fr;
  }
}
@media (max-width: 575.98px) {
  .low-stock-toolbar {
    grid-template-columns: 1fr;
    padding: 0.9rem;
  }
  .low-stock-card .card-header {
    align-items: flex-start !important;
    gap: 0.75rem;
  }
}

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
        <div class="col-12 col-lg-8 mb-4 mb-lg-0">
            <div class="card low-stock-card h-100">
                <div class="card-header d-flex flex-column flex-xl-row justify-content-between align-items-xl-center">
                    <div>
                        <h5 class="card-title mb-1"><i class="fas fa-triangle-exclamation text-danger mr-2"></i>Low Stock Alert</h5>
                        <small class="text-muted">Critical items are sorted first for faster restocking.</small>
                    </div>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-primary btn-sm mt-2 mt-xl-0">
                        View All <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <?php
                $lowStockProducts = isset($lowStockProducts) && is_array($lowStockProducts) ? $lowStockProducts : [];
                $lowStockCategories = isset($lowStockCategories) && is_array($lowStockCategories) ? $lowStockCategories : [];
                $lowStockThreshold = isset($lowStockThreshold) && is_numeric($lowStockThreshold) ? (int)$lowStockThreshold : 5;
                $lowStockPerPage = isset($lowStockPerPage) && is_numeric($lowStockPerPage) ? (int)$lowStockPerPage : 8;
                $lowStockTotal = isset($lowStockTotal) && is_numeric($lowStockTotal) ? (int)$lowStockTotal : count($lowStockProducts);
                $categoryOptions = [];
                foreach ($lowStockCategories as $categoryRow) {
                    $categoryName = trim((string)($categoryRow['category_name'] ?? 'Uncategorized'));
                    $categoryOptions[$categoryName !== '' ? $categoryName : 'Uncategorized'] = true;
                }
                ksort($categoryOptions);
                ?>
                <div class="low-stock-toolbar" data-low-stock-toolbar>
                    <div class="low-stock-control">
                        <i class="fas fa-search"></i>
                        <input type="search" id="lowStockSearch" class="form-control" placeholder="Search product or SKU" aria-label="Search low stock products">
                    </div>
                    <select id="lowStockCategory" class="form-select" aria-label="Filter by category">
                        <option value="">All categories</option>
                        <?php foreach (array_keys($categoryOptions) as $categoryName): ?>
                            <option value="<?php echo htmlspecialchars(strtolower($categoryName)); ?>"><?php echo htmlspecialchars($categoryName); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select id="lowStockStatus" class="form-select" aria-label="Filter by status">
                        <option value="">All statuses</option>
                        <option value="critical">Critical</option>
                        <option value="low">Low</option>
                    </select>
                    <button type="button" class="btn btn-light border" id="lowStockReset" title="Clear filters" data-bs-toggle="tooltip">
                        <i class="fas fa-rotate-left"></i>
                    </button>
                </div>
                <?php if (!empty($lowStockProducts)): ?>
                    <div class="low-stock-scroll">
                        <table id="dashboardLowStock" class="table align-middle no-stack">
                            <thead>
                                <tr>
                                    <th data-label="Product Name">Product Name</th>
                                    <th data-label="SKU / Code">SKU / Code</th>
                                    <th data-label="Category">Category</th>
                                    <th data-label="Current Stock">Current Stock</th>
                                    <th data-label="Minimum Stock">Minimum Stock</th>
                                    <th data-label="Status">Status</th>
                                    <th data-label="Action">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lowStockProducts as $product): ?>
                                    <?php
                                    $productId = (int)($product['id'] ?? 0);
                                    $stock = (int)($product['stock_quantity'] ?? 0);
                                    $minStock = $lowStockThreshold;
                                    $percentage = $minStock > 0 ? max(0, min(100, (int)round(($stock / $minStock) * 100))) : 0;
                                    $isCritical = $stock <= max(1, (int)floor($minStock / 2));
                                    $status = $isCritical ? 'critical' : 'low';
                                    $categoryName = trim((string)($product['category_name'] ?? 'Uncategorized'));
                                    $categoryName = $categoryName !== '' ? $categoryName : 'Uncategorized';
                                    $sku = trim((string)($product['sku'] ?? ''));
                                    ?>
                                    <tr data-low-stock-row
                                        data-search="<?php echo htmlspecialchars(strtolower(($product['name'] ?? '') . ' ' . $sku)); ?>"
                                        data-category="<?php echo htmlspecialchars(strtolower($categoryName)); ?>"
                                        data-status="<?php echo $status; ?>">
                                        <td data-label="Product Name">
                                            <div class="stock-product-name text-truncate" title="<?php echo htmlspecialchars($product['name'] ?? 'Product'); ?>">
                                                <strong><?php echo htmlspecialchars($product['name'] ?? 'Product'); ?></strong>
                                            </div>
                                        </td>
                                        <td data-label="SKU / Code">
                                            <span class="text-muted"><?php echo $sku !== '' ? htmlspecialchars($sku) : '-'; ?></span>
                                        </td>
                                        <td data-label="Category"><?php echo htmlspecialchars($categoryName); ?></td>
                                        <td data-label="Current Stock">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold"><?php echo $stock; ?></span>
                                                <div class="stock-progress" title="<?php echo $percentage; ?>% of minimum stock" data-bs-toggle="tooltip">
                                                    <span class="<?php echo $isCritical ? 'stock-progress-critical' : 'stock-progress-low'; ?>" style="width: <?php echo $percentage; ?>%;"></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Minimum Stock"><?php echo $minStock; ?></td>
                                        <td data-label="Status">
                                            <span class="stock-status <?php echo $isCritical ? 'stock-status-critical' : 'stock-status-low'; ?>" title="<?php echo $isCritical ? 'Critical stock: restock immediately' : 'Low stock: plan replenishment'; ?>" data-bs-toggle="tooltip">
                                                <i class="fas <?php echo $isCritical ? 'fa-circle-exclamation' : 'fa-triangle-exclamation'; ?>"></i>
                                                <?php echo $isCritical ? 'Critical' : 'Low'; ?>
                                            </span>
                                        </td>
                                        <td data-label="Action">
                                            <div class="low-stock-actions">
                                                <a href="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo $productId; ?>" class="btn btn-sm btn-light border" title="View product" data-bs-toggle="tooltip" aria-label="View product">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>?controller=stock&action=adjust&id=<?php echo $productId; ?>" class="btn btn-sm btn-primary" title="Restock product" data-bs-toggle="tooltip" aria-label="Restock product">
                                                    <i class="fas fa-boxes-stacked mr-1"></i>Restock
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="low-stock-pagination d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
                        <small class="text-muted" id="lowStockCount"></small>
                        <div class="btn-group btn-group-sm" role="group" aria-label="Low stock pagination">
                            <button type="button" class="btn btn-outline-secondary" id="lowStockPrev"><i class="fas fa-chevron-left"></i></button>
                            <button type="button" class="btn btn-outline-secondary disabled" id="lowStockPage">1</button>
                            <button type="button" class="btn btn-outline-secondary" id="lowStockNext"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="low-stock-empty text-center d-none" id="lowStockEmpty">
                        <i class="fas fa-filter-circle-xmark fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No products match the selected filters.</p>
                    </div>
                <?php else: ?>
                    <div class="low-stock-empty text-center">
                        <i class="fas fa-circle-check fa-2x text-success mb-2"></i>
                        <p class="text-muted mb-0">No low stock products.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-12 col-lg-4">
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

(function() {
    'use strict';
    
    document.addEventListener('DOMContentLoaded', function() {
        var table = document.getElementById('dashboardLowStock');
        if (!table) return;
        
        // Cache DOM elements for better performance
        var tbody = table.querySelector('tbody');
        var search = document.getElementById('lowStockSearch');
        var category = document.getElementById('lowStockCategory');
        var status = document.getElementById('lowStockStatus');
        var reset = document.getElementById('lowStockReset');
        var toolbar = document.querySelector('[data-low-stock-toolbar]');
        var prev = document.getElementById('lowStockPrev');
        var next = document.getElementById('lowStockNext');
        var pageLabel = document.getElementById('lowStockPage');
        var countLabel = document.getElementById('lowStockCount');
        var empty = document.getElementById('lowStockEmpty');
        
        // Configuration
        var endpoint = '<?php echo BASE_URL; ?>?controller=admin&action=lowStockAlerts';
        var perPage = <?php echo (int)$lowStockPerPage; ?>;
        var currentPage = 1;
        var totalRows = <?php echo (int)$lowStockTotal; ?>;
        var totalPages = Math.max(1, Math.ceil(totalRows / perPage));
        var activeRequest = null;
        var debounceTimer = null;
        var pendingPage = null;
        var isLoading = false;
        
        // Pre-compile escape map for better performance
        var escapeMap = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
        
        function escapeHtml(value) {
            return String(value == null ? '' : value).replace(/[&<>"']/g, function(ch) {
                return escapeMap[ch];
            });
        }
        
        // Use array for efficient string building
        function buildRowHtml(row, index) {
            var isCritical = row.status === 'critical';
            var productUrl = '<?php echo BASE_URL; ?>?controller=product&action=edit&id=' + encodeURIComponent(row.id);
            var restockUrl = '<?php echo BASE_URL; ?>?controller=stock&action=adjust&id=' + encodeURIComponent(row.id);
            var searchValue = (row.name + ' ' + row.sku).toLowerCase();
            var categoryValue = String(row.category_name || 'Uncategorized').toLowerCase();
            var statusTitle = isCritical ? 'Critical stock: restock immediately' : 'Low stock: plan replenishment';
            
            var html = [];
            html.push('<tr data-low-stock-row data-search="', escapeHtml(searchValue), '" data-category="', escapeHtml(categoryValue), '" data-status="', escapeHtml(row.status), '" style="animation-delay: ', (index * 25), 'ms;">');
            html.push('<td data-label="Product Name"><div class="stock-product-name text-truncate" title="', escapeHtml(row.name), '"><strong>', escapeHtml(row.name), '</strong></div></td>');
            html.push('<td data-label="SKU / Code"><span class="text-muted">', (row.sku ? escapeHtml(row.sku) : '-'), '</span></td>');
            html.push('<td data-label="Category">', escapeHtml(row.category_name || 'Uncategorized'), '</td>');
            html.push('<td data-label="Current Stock"><div class="d-flex align-items-center gap-2"><span class="fw-bold">', escapeHtml(row.stock_quantity), '</span><div class="stock-progress" title="', escapeHtml(row.percentage), '% of minimum stock" data-bs-toggle="tooltip"><span class="', (isCritical ? 'stock-progress-critical' : 'stock-progress-low'), '" style="width: ', escapeHtml(row.percentage), '%;"></span></div></div></td>');
            html.push('<td data-label="Minimum Stock">', escapeHtml(row.minimum_stock), '</td>');
            html.push('<td data-label="Status"><span class="stock-status ', (isCritical ? 'stock-status-critical' : 'stock-status-low'), '" title="', statusTitle, '" data-bs-toggle="tooltip"><i class="fas ', (isCritical ? 'fa-circle-exclamation' : 'fa-triangle-exclamation'), '"></i>', escapeHtml(row.status_label), '</span></td>');
            html.push('<td data-label="Action"><div class="low-stock-actions"><a href="', productUrl, '" class="btn btn-sm btn-light border" title="View product" data-bs-toggle="tooltip" aria-label="View product"><i class="fas fa-eye"></i></a><a href="', restockUrl, '" class="btn btn-sm btn-primary" title="Restock product" data-bs-toggle="tooltip" aria-label="Restock product"><i class="fas fa-boxes-stacked mr-1"></i>Restock</a></div></td>');
            html.push('</tr>');
            return html.join('');
        }
        
        // Batch DOM updates using DocumentFragment
        function updateTable(rows) {
            if (!tbody) return;
            
            // Create fragment for batch update
            var fragment = document.createDocumentFragment();
            var tempDiv = document.createElement('div');
            
            // Build all HTML at once
            var html = [];
            for (var i = 0; i < rows.length; i++) {
                html.push(buildRowHtml(rows[i], i));
            }
            
            tempDiv.innerHTML = '<table><tbody>' + html.join('') + '</tbody></table>';
            
            // Move rows to fragment
            var newRows = tempDiv.querySelectorAll('tr');
            for (var j = 0; j < newRows.length; j++) {
                fragment.appendChild(newRows[j]);
            }
            
            // Single DOM write
            tbody.innerHTML = '';
            tbody.appendChild(fragment);
        }

        function updatePagination() {
            var start = (currentPage - 1) * perPage;
            var end = Math.min(start + perPage, totalRows);
            
            if (pageLabel) pageLabel.textContent = currentPage + ' / ' + totalPages;
            if (countLabel) {
                countLabel.textContent = totalRows
                    ? 'Showing ' + (start + 1) + '-' + end + ' of ' + totalRows + ' low stock products'
                    : 'No low stock products found';
            }
            if (prev) prev.disabled = currentPage <= 1 || isLoading;
            if (next) next.disabled = currentPage >= totalPages || isLoading;
            if (empty) empty.classList.toggle('d-none', totalRows !== 0);
        }

        function refreshTooltips() {
            if (!window.bootstrap || !bootstrap.Tooltip) return;
            // Use requestIdleCallback for non-critical tooltip initialization
            var initTooltips = function() {
                document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function(el) {
                    bootstrap.Tooltip.getOrCreateInstance(el);
                });
            };
            
            if ('requestIdleCallback' in window) {
                requestIdleCallback(initTooltips, { timeout: 100 });
            } else {
                setTimeout(initTooltips, 50);
            }
        }

        function loadPage(page) {
            if (isLoading) {
                // Queue the page change
                pendingPage = page;
                return;
            }
            
            currentPage = Math.max(1, page || 1);
            isLoading = true;
            
            // Update UI to show loading state
            if (prev) prev.disabled = true;
            if (next) next.disabled = true;
            
            var params = new URLSearchParams();
            params.set('page', currentPage);
            params.set('per_page', perPage);
            params.set('search', search && search.value ? search.value.trim() : '');
            params.set('category', category && category.value ? category.value : '');
            params.set('status', status && status.value ? status.value : '');

            // Cancel previous request
            if (activeRequest && activeRequest.abort) {
                activeRequest.abort();
            }
            activeRequest = window.AbortController ? new AbortController() : null;

            fetch(endpoint + '&' + params.toString(), {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                signal: activeRequest ? activeRequest.signal : undefined
            })
            .then(function(response) { 
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json(); 
            })
            .then(function(payload) {
                isLoading = false;
                if (!payload || !payload.success) return;
                
                var rows = Array.isArray(payload.rows) ? payload.rows : [];
                var pagination = payload.pagination || {};
                totalRows = parseInt(pagination.total || 0, 10);
                totalPages = Math.max(1, parseInt(pagination.total_pages || 1, 10));
                currentPage = Math.min(Math.max(1, parseInt(pagination.page || currentPage, 10)), totalPages);
                
                // Use optimized batch update
                updateTable(rows);
                updatePagination();
                refreshTooltips();
                
                // Handle any pending page change
                if (pendingPage !== null) {
                    var nextPage = pendingPage;
                    pendingPage = null;
                    loadPage(nextPage);
                }
            })
            .catch(function(error) {
                isLoading = false;
                updatePagination(); // Re-enable buttons
                if (error && error.name === 'AbortError') return;
                console.error('Low stock load error:', error);
            });
        }

        function debouncedReload() {
            window.clearTimeout(debounceTimer);
            debounceTimer = window.setTimeout(function() { loadPage(1); }, 180); // Reduced from 220ms
        }

        // Use event delegation for better performance with large tables
        if (toolbar) {
            // Passive listener for input (scrolling performance)
            toolbar.addEventListener('input', function(event) {
                if (event.target === search) debouncedReload();
            }, { passive: true });
            
            toolbar.addEventListener('change', function(event) {
                if (event.target === category || event.target === status) loadPage(1);
            });
        }
        
        if (reset) {
            reset.addEventListener('click', function() {
                if (search) search.value = '';
                if (category) category.value = '';
                if (status) status.value = '';
                loadPage(1);
            });
        }
        
        // Pagination with click debouncing
        var clickDebounceTimer = null;
        
        if (prev) {
            prev.addEventListener('click', function() {
                if (currentPage > 1 && !isLoading) {
                    window.clearTimeout(clickDebounceTimer);
                    clickDebounceTimer = window.setTimeout(function() {
                        loadPage(currentPage - 1);
                    }, 50);
                }
            });
        }
        
        if (next) {
            next.addEventListener('click', function() {
                if (currentPage < totalPages && !isLoading) {
                    window.clearTimeout(clickDebounceTimer);
                    clickDebounceTimer = window.setTimeout(function() {
                        loadPage(currentPage + 1);
                    }, 50);
                }
            });
        }

        updatePagination();
        refreshTooltips();
    });
})();
</script>
<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
