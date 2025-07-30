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
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=changePassword" class="list-group-item list-group-item-action active">
                        <i class="fas fa-key me-2"></i> Change Password
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=history" class="list-group-item list-group-item-action">
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
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Change Password</h4>
                </div>
                <div class="card-body">
                    <?php flash('password_success'); ?>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=user&action=changePassword" method="POST">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['current_password']) ? 'is-invalid' : ''; ?>" id="current_password" name="current_password" required>
                            <?php if(isset($errors['current_password'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['current_password']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['new_password']) ? 'is-invalid' : ''; ?>" id="new_password" name="new_password" required>
                            <?php if(isset($errors['new_password'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['new_password']; ?></div>
                            <?php else: ?>
                                <div class="form-text">Password must be at least 6 characters long.</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
                            <?php if(isset($errors['confirm_password'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=profile" class="btn btn-secondary">Back to Profile</a>
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
