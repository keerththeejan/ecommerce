<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <!-- Dashboard Stats -->
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Orders</h6>
                            <h2 class="mb-0">
                                <?php 
                                    $orderModel = new Order();
                                    echo $orderModel->count(); 
                                ?>
                            </h2>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="text-white">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Products</h6>
                            <h2 class="mb-0">
                                <?php 
                                    $productModel = new Product();
                                    echo $productModel->count(); 
                                ?>
                            </h2>
                        </div>
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="text-white">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Customers</h6>
                            <h2 class="mb-0">
                                <?php 
                                    $userModel = new User();
                                    echo count($userModel->getCustomers()); 
                                ?>
                            </h2>
                        </div>
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex" class="text-white">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-4">
            <div class="card bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase">Total Revenue</h6>
                            <h2 class="mb-0">
                                <?php 
                                    $db = new Database();
                                    $db->query("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'");
                                    $result = $db->single();
                                    echo formatPrice($result['total'] ?? 0); 
                                ?>
                            </h2>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <a href="<?php echo BASE_URL; ?>?controller=report&action=index" class="text-white">View Details</a>
                    <i class="fas fa-angle-right text-white"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock Management and Purchases -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Stock & Purchases</h5>
                    <div>
                        <a href="<?php echo BASE_URL; ?>?controller=purchase&action=create" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> New Purchase
                        </a>
                        <a href="<?php echo BASE_URL; ?>?controller=stock&action=index" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-boxes me-1"></i> Manage Stock
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Low Stock Products -->
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Low Stock Alert</h6>
                            <?php 
                            $lowStockProducts = [];
                            $hasProductsTable = false;
                            try {
                                $db = new Database();
                                $db->query("SHOW TABLES LIKE 'products'");
                                if ($db->single()) {
                                    $hasProductsTable = true;
                                    $productModel = new Product();
                                    $lowStockProducts = $productModel->getLowStockProducts(5); // Get top 5 low stock products
                                }
                            } catch (Exception $e) {
                                // Log error but don't show it to users
                                error_log('Error getting low stock products: ' . $e->getMessage());
                            }
                            
                            if (!$hasProductsTable): ?>
                                <div class="alert alert-warning mb-0">Products table not found. Please run the database setup.</div>
                            <?php elseif (!empty($lowStockProducts)): ?>
                                <div class="list-group">
                                    <?php foreach($lowStockProducts as $product): ?>
                                        <a href="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo $product['id']; ?>" 
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <span><?php echo htmlspecialchars($product['name']); ?></span>
                                            <span class="badge bg-<?php echo $product['stock_quantity'] <= 0 ? 'danger' : 'warning'; ?> rounded-pill">
                                                <?php echo $product['stock_quantity']; ?> in stock
                                            </span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-0">No low stock products found.</div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Recent Purchases -->
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Recent Purchases</h6>
                            <?php 
                            $recentPurchases = [];
                            try {
                                $purchaseModel = new Purchase();
                                $recentPurchases = $purchaseModel->getRecentPurchases(5);
                            } catch (Exception $e) {
                                // Log error but don't show it to users
                                error_log('Purchase table error: ' . $e->getMessage());
                            }
                            
                            if (!empty($recentPurchases)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Supplier</th>
                                                <th>Items</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($recentPurchases as $purchase): ?>
                                                <tr>
                                                    <td><a href="<?php echo BASE_URL; ?>?controller=purchase&action=view&id=<?php echo $purchase['id']; ?>">#<?php echo $purchase['id']; ?></a></td>
                                                    <td><?php echo date('M d, Y', strtotime($purchase['purchase_date'])); ?></td>
                                                    <td><?php echo htmlspecialchars($purchase['supplier_name']); ?></td>
                                                    <td><?php echo $purchase['total_items']; ?></td>
                                                    <td><?php echo formatPrice($purchase['total_amount']); ?></td>
                                                    <td><span class="badge bg-<?php echo $purchase['status'] === 'received' ? 'success' : 'warning'; ?>">
                                                        <?php echo ucfirst($purchase['status']); ?>
                                                    </span></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-0">No recent purchases found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Billing & Invoices -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Billing & Invoices</h5>
                    <div>
                        <a href="<?php echo BASE_URL; ?>?controller=invoice&action=create" class="btn btn-primary btn-sm">
                            <i class="fas fa-file-invoice me-1"></i> Create Invoice
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Recent Invoices</h6>
                            <?php 
                            $recentInvoices = [];
                            try {
                                $invoiceModel = new Invoice();
                                $recentInvoices = $invoiceModel->getRecentInvoices(5);
                            } catch (Exception $e) {
                                // Log error but don't show it to users
                                error_log('Invoices table error: ' . $e->getMessage());
                            }
                            
                            if (!empty($recentInvoices)): ?>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($recentInvoices as $invoice): ?>
                                                <tr>
                                                    <td><a href="<?php echo BASE_URL; ?>?controller=invoice&action=view&id=<?php echo $invoice['id']; ?>">#<?php echo $invoice['invoice_number']; ?></a></td>
                                                    <td><?php echo date('M d, Y', strtotime($invoice['invoice_date'])); ?></td>
                                                    <td><?php echo htmlspecialchars($invoice['customer_name']); ?></td>
                                                    <td><?php echo formatPrice($invoice['total_amount']); ?></td>
                                                    <td>
                                                        <span class="badge bg-<?php 
                                                            echo $invoice['status'] === 'paid' ? 'success' : 
                                                                ($invoice['status'] === 'overdue' ? 'danger' : 'warning'); 
                                                        ?>">
                                                            <?php echo ucfirst($invoice['status']); ?>
                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mb-0">No recent invoices found.</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted mb-3">Quick Stats</h6>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-1">This Month's Sales</h6>
                                            <h4 class="mb-0"><?php 
                                                try {
                                                    $db = new Database();
                                                    $db->query("SHOW TABLES LIKE 'orders'");
                                                    if ($db->single()) {
                                                        $db->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders 
                                                                   WHERE payment_status = 'paid' 
                                                                   AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
                                                                   AND YEAR(created_at) = YEAR(CURRENT_DATE())");
                                                        $result = $db->single();
                                                        echo formatPrice($result['total'] ?? 0);
                                                    } else {
                                                        echo formatPrice(0);
                                                    }
                                                } catch (Exception $e) {
                                                    echo formatPrice(0);
                                                    error_log('Error calculating monthly sales: ' . $e->getMessage());
                                                }
                                            ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-1">Outstanding Invoices</h6>
                                            <h4 class="mb-0"><?php 
                                                try {
                                                    $db->query("SELECT COUNT(*) as count FROM invoices WHERE status != 'paid'");
                                                    $result = $db->single();
                                                    echo $result['count'] ?? 0;
                                                } catch (Exception $e) {
                                                    echo '0';
                                                    error_log('Error counting outstanding invoices: ' . $e->getMessage());
                                                }
                                            ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-1">This Month's Purchases</h6>
                                            <h4 class="mb-0"><?php 
                                                try {
                                                    $db->query("SHOW TABLES LIKE 'purchases'");
                                                    if ($db->single()) {
                                                        $db->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM purchases 
                                                                   WHERE MONTH(purchase_date) = MONTH(CURRENT_DATE()) 
                                                                   AND YEAR(purchase_date) = YEAR(CURRENT_DATE())");
                                                        $result = $db->single();
                                                        echo formatPrice($result['total'] ?? 0);
                                                    } else {
                                                        echo formatPrice(0);
                                                    }
                                                } catch (Exception $e) {
                                                    echo formatPrice(0);
                                                    error_log('Error calculating monthly purchases: ' . $e->getMessage());
                                                }
                                            ?></h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-1">Pending Purchases</h6>
                                            <h4 class="mb-0"><?php 
                                                try {
                                                    $db->query("SHOW TABLES LIKE 'purchases'");
                                                    if ($db->single()) {
                                                        $db->query("SELECT COUNT(*) as count FROM purchases WHERE status != 'received'");
                                                        $result = $db->single();
                                                        echo $result['count'] ?? 0;
                                                    } else {
                                                        echo '0';
                                                    }
                                                } catch (Exception $e) {
                                                    echo '0';
                                                    error_log('Error counting pending purchases: ' . $e->getMessage());
                                                }
                                            ?></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Orders and Sales Chart -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sales Statistics</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Orders</h5>
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
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recentOrders as $order) : ?>
                                        <tr>
                                            <td><?php echo $order['id']; ?></td>
                                            <td><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                                            <td><?php echo formatPrice($order['total_amount']); ?></td>
                                            <td>
                                                <?php if($order['status'] == 'pending') : ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php elseif($order['status'] == 'processing') : ?>
                                                    <span class="badge bg-info">Processing</span>
                                                <?php elseif($order['status'] == 'shipped') : ?>
                                                    <span class="badge bg-primary">Shipped</span>
                                                <?php elseif($order['status'] == 'delivered') : ?>
                                                    <span class="badge bg-success">Delivered</span>
                                                <?php elseif($order['status'] == 'cancelled') : ?>
                                                    <span class="badge bg-danger">Cancelled</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-primary btn-sm">View All Orders</a>
                        </div>
                    <?php else : ?>
                        <p class="text-center">No recent orders found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Products -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Low Stock Products</h5>
                </div>
                <div class="card-body">
                    <?php 
                    $hasProductsTable = true;
                    try {
                        $db = new Database();
                        $db->query("SHOW TABLES LIKE 'products'");
                        $hasProductsTable = (bool)$db->single();
                    } catch (Exception $e) {
                        $hasProductsTable = false;
                        error_log('Error checking products table: ' . $e->getMessage());
                    }
                    
                    if ($hasProductsTable && !empty($lowStockProducts)) : ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>SKU</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Stock</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($lowStockProducts as $product) : ?>
                                        <tr>
                                            <td><?php echo $product['id']; ?></td>
                                            <td><?php echo $product['name']; ?></td>
                                            <td><?php echo $product['sku']; ?></td>
                                            <td><?php echo $product['category_name']; ?></td>
                                            <td><?php echo formatPrice($product['price']); ?></td>
                                            <td>
                                                <span class="badge bg-danger"><?php echo $product['stock_quantity']; ?></span>
                                            </td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>?controller=product&action=edit&param=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-primary btn-sm">View All Products</a>
                        </div>
                    <?php elseif ($hasProductsTable) : ?>
                        <p class="text-center">No low stock products found.</p>
                    <?php else : ?>
                        <p class="text-center text-muted">Products table not found. Please run the database setup.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Sales Chart
    document.addEventListener('DOMContentLoaded', function() {
        <?php if(!empty($salesStats)) : ?>
            var ctx = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [
                        <?php 
                            foreach($salesStats as $stat) {
                                echo "'" . $stat['period'] . "',";
                            }
                        ?>
                    ],
                    datasets: [{
                        label: 'Sales',
                        data: [
                            <?php 
                                foreach($salesStats as $stat) {
                                    echo $stat['total_sales'] . ",";
                                }
                            ?>
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        <?php endif; ?>
    });
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
