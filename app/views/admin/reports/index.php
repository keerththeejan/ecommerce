<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Reports Dashboard</h2>
        </div>
    </div>
    
    <!-- Sales Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Sales</h5>
                    <h2 class="card-text"><?php echo formatCurrency($salesSummary['total_sales']); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Orders</h5>
                    <h2 class="card-text"><?php echo $salesSummary['total_orders']; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Avg. Order Value</h5>
                    <h2 class="card-text"><?php echo formatCurrency($salesSummary['avg_order_value']); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Orders</h5>
                    <h2 class="card-text"><?php echo $salesSummary['pending_orders']; ?></h2>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Top Selling Products -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top Selling Products</h5>
                    <a href="<?php echo BASE_URL; ?>?controller=report&action=products" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="card-body">
                    <?php if(empty($topProducts)): ?>
                        <div class="alert alert-info">No product sales data available.</div>
                    <?php else: ?>
                        <style>
                            /* Mobile-first responsive table styling for reports */
                            @media (max-width: 576.98px) {
                                table.responsive-table thead { display: none; }
                                table.responsive-table,
                                table.responsive-table tbody,
                                table.responsive-table tr,
                                table.responsive-table td { display: block; width: 100%; }
                                table.responsive-table tr {
                                    margin-bottom: 1rem;
                                    border: 1px solid rgba(0,0,0,.075);
                                    border-radius: .5rem;
                                    overflow: hidden;
                                    background: var(--bg-color, #fff);
                                }
                                table.responsive-table td {
                                    padding: .5rem .75rem;
                                    border: none;
                                    border-bottom: 1px solid rgba(0,0,0,.05);
                                }
                                table.responsive-table td:last-child { border-bottom: 0; }
                                table.responsive-table td::before {
                                    content: attr(data-label);
                                    font-weight: 600;
                                    display: block;
                                    margin-bottom: .25rem;
                                    opacity: .8;
                                }
                            }
                        </style>
                        <div class="table-responsive">
                            <table class="table table-striped responsive-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Sold</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($topProducts as $product): ?>
                                        <tr>
                                            <td data-label="Product"><?php echo $product['name']; ?></td>
                                            <td data-label="Category"><?php echo $product['category_name']; ?></td>
                                            <td data-label="Price"><?php echo formatCurrency($product['price']); ?></td>
                                            <td data-label="Sold"><?php echo $product['total_sold'] ?? 0; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-sm btn-light">View All</a>
                </div>
                <div class="card-body">
                    <?php if(empty($recentOrders)): ?>
                        <div class="alert alert-info">No recent orders.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped responsive-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recentOrders as $order): ?>
                                        <tr>
                                            <td data-label="Order #">
                                                <a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $order['id']; ?>">
                                                    #<?php echo $order['id']; ?>
                                                </a>
                                            </td>
                                            <td data-label="Customer"><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                                            <td data-label="Amount"><?php echo formatCurrency($order['total_amount']); ?></td>
                                            <td data-label="Status">
                                                <?php
                                                $statusClass = '';
                                                switch($order['status']) {
                                                    case 'pending':
                                                        $statusClass = 'bg-warning';
                                                        break;
                                                    case 'processing':
                                                        $statusClass = 'bg-info';
                                                        break;
                                                    case 'completed':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                    default:
                                                        $statusClass = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($order['status']); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Customer Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Customer Statistics</h5>
                    <a href="<?php echo BASE_URL; ?>?controller=report&action=customers" class="btn btn-sm btn-light">View Details</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <h3><?php echo $customerStats['total_customers']; ?></h3>
                            <p>Total Customers</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h3><?php echo $customerStats['new_customers']; ?></h3>
                            <p>New (30 days)</p>
                        </div>
                        <div class="col-md-4 text-center">
                            <h3><?php echo $customerStats['active_customers']; ?></h3>
                            <p>Active Customers</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Status -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">Order Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h3><?php echo $salesSummary['pending_orders']; ?></h3>
                            <p>Pending</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3><?php echo $salesSummary['processing_orders'] ?? 0; ?></h3>
                            <p>Processing</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3><?php echo $salesSummary['completed_orders']; ?></h3>
                            <p>Completed</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h3><?php echo $salesSummary['cancelled_orders']; ?></h3>
                            <p>Cancelled</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Report Links -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Detailed Reports</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo BASE_URL; ?>?controller=report&action=sales" class="btn btn-primary btn-lg btn-block w-100">
                                <i class="fas fa-chart-line mr-2"></i> Sales Report
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo BASE_URL; ?>?controller=report&action=products" class="btn btn-success btn-lg btn-block w-100">
                                <i class="fas fa-box mr-2"></i> Product Report
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="<?php echo BASE_URL; ?>?controller=report&action=customers" class="btn btn-info btn-lg btn-block w-100">
                                <i class="fas fa-users mr-2"></i> Customer Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
