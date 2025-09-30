<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <?php 
                $return = isset($return) ? trim($return) : '';
                $backHref = BASE_URL . '?controller=user&action=adminIndex';
                $backText = 'Back to Users';
                if (strtolower($return) === 'customers') {
                    $backHref = BASE_URL . '?controller=user&action=customers';
                    $backText = 'Back to Customers';
                }
            ?>
            <a href="<?php echo $backHref; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> <?php echo $backText; ?>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Reset Password</h3>
                </div>
                <div class="card-body">
                    <?php flash('user_error', null, 'alert alert-danger'); ?>
                    <?php flash('user_success'); ?>

                    <div class="mb-3">
                        <strong>User:</strong>
                        <div><?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?> (<?php echo htmlspecialchars($user['email'] ?? ''); ?>)</div>
                    </div>

                    <form method="POST" action="<?php echo BASE_URL; ?>?controller=user&action=adminResetPassword&id=<?php echo urlencode($user['id']); ?><?php echo ($return ? '&return=' . urlencode($return) : ''); ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control" minlength="6" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" minlength="6" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo $backHref; ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
