<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Change Password</h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>?controller=user&action=changePassword" method="POST">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['current_password']) ? 'is-invalid' : ''; ?>" id="current_password" name="current_password">
                            <?php if(isset($errors['current_password'])) : ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['current_password']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['new_password']) ? 'is-invalid' : ''; ?>" id="new_password" name="new_password">
                            <?php if(isset($errors['new_password'])) : ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['new_password']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password">
                            <?php if(isset($errors['confirm_password'])) : ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['confirm_password']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=profile" class="btn btn-outline-secondary">Back to Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
