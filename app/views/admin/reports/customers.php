<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="<?php echo BASE_URL; ?>?controller=report&action=index" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Reports
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">Customer Report</h3>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form action="<?php echo BASE_URL; ?>?controller=report&action=customers" method="GET" class="mb-4">
                        <input type="hidden" name="controller" value="report">
                        <input type="hidden" name="action" value="customers">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="sort_by">Sort By</label>
                                    <select class="form-control" id="sort_by" name="sort_by">
                                        <option value="orders" <?php echo $sortBy == 'orders' ? 'selected' : ''; ?>>Orders (Highest First)</option>
                                        <option value="spent" <?php echo $sortBy == 'spent' ? 'selected' : ''; ?>>Amount Spent (Highest First)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                                <a href="<?php echo BASE_URL; ?>?controller=report&action=customers" class="btn btn-secondary ml-2">Reset</a>
                            </div>
                        </div>
                    </form>
                    
                    <?php if(empty($customerData)): ?>
                        <div class="alert alert-info">No customer data available.</div>
                    <?php else: ?>
                        <!-- Customer Data Table -->
                        <div class="table-responsive">
                            <table id="reportCustomersTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Orders</th>
                                        <th>Total Spent</th>
                                        <th>Avg. Order Value</th>
                                        <th>Last Order</th>
                                        <th>Registered</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($customerData as $customer): ?>
                                        <?php 
                                        $avgOrderValue = 0;
                                        if($customer['order_count'] > 0) {
                                            $avgOrderValue = $customer['total_spent'] / $customer['order_count'];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $customer['id']; ?></td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>?controller=user&action=adminEdit&id=<?php echo $customer['id']; ?>">
                                                    <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?>
                                                </a>
                                            </td>
                                            <td><?php echo $customer['email']; ?></td>
                                            <td><?php echo $customer['order_count'] ?? 0; ?></td>
                                            <td><?php echo formatCurrency($customer['total_spent'] ?? 0); ?></td>
                                            <td><?php echo formatCurrency($avgOrderValue); ?></td>
                                            <td>
                                                <?php if(!empty($customer['last_order_date'])): ?>
                                                    <?php echo date('M d, Y', strtotime($customer['last_order_date'])); ?>
                                                <?php else: ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
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
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
