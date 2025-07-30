<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">My Account</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=profile" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=changePassword" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i> Change Password
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=history" class="list-group-item list-group-item-action active">
                        <i class="fas fa-shopping-bag me-2"></i> Order History
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=logout" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">My Orders</h4>
                </div>
                <div class="card-body">
                    <?php flash('order_success'); ?>
                    <?php flash('order_error', '', 'alert alert-danger'); ?>
                    
                    <?php if(empty($orders)): ?>
                        <div class="alert alert-info">
                            <p class="mb-0">You haven't placed any orders yet.</p>
                        </div>
                        <div class="text-center mt-4">
                            <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="btn btn-primary">
                                <i class="fas fa-shopping-cart me-2"></i> Start Shopping
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders as $order): ?>
                                        <tr>
                                            <td><?php echo $order['id']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                            <td><?php echo formatCurrency($order['total_amount']); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = '';
                                                switch($order['status']) {
                                                    case 'pending':
                                                        $statusClass = 'bg-warning';
                                                        break;
                                                    case 'processing':
                                                        $statusClass = 'bg-info';
                                                        break;
                                                    case 'shipped':
                                                        $statusClass = 'bg-primary';
                                                        break;
                                                    case 'delivered':
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
                                            <td>
                                                <?php
                                                $paymentClass = '';
                                                switch($order['payment_status']) {
                                                    case 'paid':
                                                        $paymentClass = 'bg-success';
                                                        break;
                                                    case 'pending':
                                                        $paymentClass = 'bg-warning';
                                                        break;
                                                    case 'failed':
                                                        $paymentClass = 'bg-danger';
                                                        break;
                                                    default:
                                                        $paymentClass = 'bg-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $paymentClass; ?>"><?php echo ucfirst($order['payment_status']); ?></span>
                                            </td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>?controller=order&action=show&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                
                                                <?php if($order['status'] == 'pending' && $order['payment_status'] == 'pending'): ?>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=cancel&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-times"></i> Cancel
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <?php if($order['payment_status'] == 'pending' && $order['payment_method'] != 'cod'): ?>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=payment&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-success">
                                                        <i class="fas fa-credit-card"></i> Pay
                                                    </a>
                                                <?php endif; ?>
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
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
