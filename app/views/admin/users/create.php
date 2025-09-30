<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Add New User</h3>
                </div>
                <div class="card-body">
                    <?php if(isset($errors['db_error'])): ?>
                        <div class="alert alert-danger"><?php echo $errors['db_error']; ?></div>
                    <?php endif; ?>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=user&action=adminCreate" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control <?php echo isset($errors['username']) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?php echo isset($data['username']) ? $data['username'] : ''; ?>" required>
                                    <?php if(isset($errors['username'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['username']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo isset($data['email']) ? $data['email'] : ''; ?>" required>
                                    <?php if(isset($errors['email'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
                                    <?php if(isset($errors['password'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['password']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control <?php echo isset($errors['confirm_password']) ? 'is-invalid' : ''; ?>" id="confirm_password" name="confirm_password" required>
                                    <?php if(isset($errors['confirm_password'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['confirm_password']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control <?php echo isset($errors['first_name']) ? 'is-invalid' : ''; ?>" id="first_name" name="first_name" value="<?php echo isset($data['first_name']) ? $data['first_name'] : ''; ?>" required>
                                    <?php if(isset($errors['first_name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['first_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control <?php echo isset($errors['last_name']) ? 'is-invalid' : ''; ?>" id="last_name" name="last_name" value="<?php echo isset($data['last_name']) ? $data['last_name'] : ''; ?>" required>
                                    <?php if(isset($errors['last_name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['last_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select <?php echo isset($errors['role']) ? 'is-invalid' : ''; ?>" id="role" name="role" required>
                                <option value="customer" <?php echo (isset($data['role']) && $data['role'] == 'customer') ? 'selected' : ''; ?>>Customer</option>
                                <option value="staff" <?php echo (isset($data['role']) && $data['role'] == 'staff') ? 'selected' : ''; ?>>Staff</option>
                                <option value="admin" <?php echo (isset($data['role']) && $data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            </select>
                            <?php if(isset($errors['role'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['role']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
