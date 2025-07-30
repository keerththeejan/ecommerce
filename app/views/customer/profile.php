<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">My Account</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=profile" class="list-group-item list-group-item-action active">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=changePassword" class="list-group-item list-group-item-action">
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
                    <h4 class="mb-0">My Profile</h4>
                </div>
                <div class="card-body">
                    <?php flash('profile_success'); ?>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=user&action=profile" method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" required>
                                <?php if(isset($errors['first_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" required>
                                <?php if(isset($errors['last_name'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo $user['email']; ?>" required>
                            <?php if(isset($errors['email'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" value="<?php echo $user['username']; ?>" disabled>
                            <div class="form-text">Username cannot be changed.</div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Account Created</label>
                                <p class="form-control-static"><?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Updated</label>
                                <p class="form-control-static"><?php echo date('F j, Y', strtotime($user['updated_at'])); ?></p>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=changePassword" class="btn btn-secondary">Change Password</a>
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
