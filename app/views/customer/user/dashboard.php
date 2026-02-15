<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">My Account</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=dashboard" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=profile" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=changePassword" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i> Change Password
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=history" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-bag me-2"></i> Order History
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=settings" class="list-group-item list-group-item-action">
                        <i class="fas fa-cog me-2"></i> Settings
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=address" class="list-group-item list-group-item-action">
                        <i class="fas fa-map-marker-alt me-2"></i> Addresses
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=logout" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <?php flash('profile_success'); ?>
            <?php flash('password_success'); ?>
            <?php flash('address_success'); ?>
            <?php flash('address_error', '', 'alert alert-danger'); ?>

            <h1 class="fs-3 mb-4">Welcome, <?php echo htmlspecialchars($user['first_name'] ?? $user['username'] ?? 'User'); ?>!</h1>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-shopping-bag text-primary me-2"></i>Orders</h5>
                            <p class="card-text text-muted small">View and manage your orders.</p>
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=history" class="btn btn-outline-primary">Order History</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-user text-primary me-2"></i>Profile</h5>
                            <p class="card-text text-muted small">Update your personal information.</p>
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=profile" class="btn btn-outline-primary">Manage Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-map-marker-alt text-primary me-2"></i>Addresses</h5>
                            <p class="card-text text-muted small">Manage shipping and billing addresses.</p>
                            <a href="<?php echo BASE_URL; ?>?controller=address" class="btn btn-outline-primary">Manage Addresses</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Orders</h5>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=history" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body">
                    <?php if (empty($recentOrders)): ?>
                        <p class="text-muted mb-0">You haven't placed any orders yet.</p>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="btn btn-primary mt-3">Start Shopping</a>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentOrders as $order): ?>
                                        <tr>
                                            <td><?php echo (int)$order['id']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                            <td><?php echo formatCurrency($order['total_amount']); ?></td>
                                            <td>
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                switch ($order['status']) {
                                                    case 'pending': $statusClass = 'bg-warning'; break;
                                                    case 'processing': $statusClass = 'bg-info'; break;
                                                    case 'shipped': $statusClass = 'bg-primary'; break;
                                                    case 'delivered': $statusClass = 'bg-success'; break;
                                                    case 'cancelled': $statusClass = 'bg-danger'; break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></span>
                                            </td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>?controller=order&action=show&id=<?php echo (int)$order['id']; ?>" class="btn btn-sm btn-primary">View</a>
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
