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
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0">Delete User</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h4 class="alert-heading">Warning!</h4>
                        <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                    </div>
                    
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h5>
                            <p class="card-text"><strong>Username:</strong> <?php echo $user['username']; ?></p>
                            <p class="card-text"><strong>Email:</strong> <?php echo $user['email']; ?></p>
                            <p class="card-text">
                                <strong>Role:</strong>
                                <?php
                                $roleClass = '';
                                switch($user['role']) {
                                    case 'admin':
                                        $roleClass = 'bg-danger';
                                        break;
                                    case 'staff':
                                        $roleClass = 'bg-warning';
                                        break;
                                    default:
                                        $roleClass = 'bg-info';
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $roleClass; ?>"><?php echo ucfirst($user['role']); ?></span>
                            </p>
                            <p class="card-text"><strong>Created:</strong> <?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
                        </div>
                    </div>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=user&action=adminDelete&id=<?php echo $user['id']; ?>" method="POST">
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=adminIndex" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-danger">Delete User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
