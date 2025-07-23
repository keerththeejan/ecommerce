<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">My Orders</h1>
            
            <?php if(!empty($orders)) : ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order) : 
                                // Determine status badge class
                                $statusClass = 'secondary';
                                if($order['status'] == 'completed') $statusClass = 'success';
                                if($order['status'] == 'processing') $statusClass = 'primary';
                                if($order['status'] == 'cancelled') $statusClass = 'danger';
                                if($order['status'] == 'shipped') $statusClass = 'info';
                            ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $statusClass; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end"><?php echo formatCurrency($order['total_amount']); ?></td>
                                    <td class="text-center">
                                        <a href="<?php echo BASE_URL; ?>?controller=order&action=show&param=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else : ?>
                <div class="alert alert-info">
                    <p class="mb-0">You haven't placed any orders yet.</p>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="btn btn-primary mt-3">
                        Start Shopping
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
