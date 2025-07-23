<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Forgot Password</h3>
                </div>
                <div class="card-body">
                    <p class="card-text mb-4">Enter your email address and we'll send you a link to reset your password.</p>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=user&action=forgotPassword" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $data['email']; ?>">
                            <?php if(isset($errors['email'])) : ?>
                                <div class="invalid-feedback">
                                    <?php echo $errors['email']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-outline-secondary">Back to Login</a>
                        <a href="<?php echo BASE_URL; ?>?controller=user&action=register" class="btn btn-outline-primary">Create Account</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
