<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Customers</h3>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>?controller=user&action=adminCreate" class="btn btn-light btn-sm">
                            <i class="fas fa-user-plus me-1"></i> Add Customer
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3 g-3">
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted text-uppercase small">Total Customers</div>
                                <div class="fs-4 fw-bold"><?php echo (int)($stats['total_customers'] ?? 0); ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted text-uppercase small">New (30 days)</div>
                                <div class="fs-4 fw-bold"><?php echo (int)($stats['new_customers'] ?? 0); ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted text-uppercase small">Active (has orders)</div>
                                <div class="fs-4 fw-bold"><?php echo (int)($stats['active_customers'] ?? 0); ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if (empty($customers)): ?>
                        <div class="alert alert-info">No customers found.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Password</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($customers as $c): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($c['id']); ?></td>
                                            <td><?php echo htmlspecialchars(trim(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? ''))); ?></td>
                                            <td><?php echo htmlspecialchars($c['username'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($c['email'] ?? ''); ?></td>
                                            <td>
                                                <?php
                                                    $role = strtolower($c['role'] ?? 'customer');
                                                    $roleClass = 'bg-info';
                                                    if ($role === 'admin') { $roleClass = 'bg-danger'; }
                                                    elseif ($role === 'staff') { $roleClass = 'bg-warning'; }
                                                ?>
                                                <span class="badge <?php echo $roleClass; ?>"><?php echo ucfirst($role); ?></span>
                                            </td>
                                            <td>
                                                <span title="Passwords are securely hashed and cannot be viewed">••••••</span>
                                            </td>
                                            <td><?php echo !empty($c['created_at']) ? date('M d, Y', strtotime($c['created_at'])) : '-'; ?></td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>?controller=user&action=adminEdit&id=<?php echo urlencode($c['id']); ?>" class="btn btn-sm btn-primary">Edit</a>
                                                <a href="<?php echo BASE_URL; ?>?controller=user&action=adminDelete&id=<?php echo urlencode($c['id']); ?>&return=customers" class="btn btn-sm btn-danger">Delete</a>
                                                <a href="<?php echo BASE_URL; ?>?controller=user&action=adminResetPassword&id=<?php echo urlencode($c['id']); ?>&return=customers" class="btn btn-sm btn-warning">Reset Password</a>
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

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
