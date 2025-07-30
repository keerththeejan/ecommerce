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
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Sales Report</h3>
                </div>
                <div class="card-body">
                    <!-- Date Range Filter -->
                    <form action="<?php echo BASE_URL; ?>?controller=report&action=sales" method="GET" class="mb-4">
                        <input type="hidden" name="controller" value="report">
                        <input type="hidden" name="action" value="sales">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $endDate; ?>">
                                </div>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">Apply Filter</button>
                                <a href="<?php echo BASE_URL; ?>?controller=report&action=sales" class="btn btn-secondary ml-2">Reset</a>
                            </div>
                        </div>
                    </form>
                    
                    <?php if(empty($salesData)): ?>
                        <div class="alert alert-info">No sales data available for the selected period.</div>
                    <?php else: ?>
                        <!-- Sales Summary -->
                        <div class="row mb-4">
                            <?php
                            $totalSales = 0;
                            $totalOrders = 0;
                            $avgOrderValue = 0;
                            
                            foreach($salesData as $data) {
                                $totalSales += $data['total_sales'];
                                $totalOrders += $data['order_count'];
                            }
                            
                            if($totalOrders > 0) {
                                $avgOrderValue = $totalSales / $totalOrders;
                            }
                            ?>
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Total Sales</h5>
                                        <h2 class="card-text"><?php echo formatCurrency($totalSales); ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Total Orders</h5>
                                        <h2 class="card-text"><?php echo $totalOrders; ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">Avg. Order Value</h5>
                                        <h2 class="card-text"><?php echo formatCurrency($avgOrderValue); ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Sales Data Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Orders</th>
                                        <th>Sales</th>
                                        <th>Avg. Order Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($salesData as $data): ?>
                                        <tr>
                                            <td><?php echo date('M d, Y', strtotime($data['date'])); ?></td>
                                            <td><?php echo $data['order_count']; ?></td>
                                            <td><?php echo formatCurrency($data['total_sales']); ?></td>
                                            <td><?php echo formatCurrency($data['avg_order_value']); ?></td>
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
