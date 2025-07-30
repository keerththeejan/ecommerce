<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Users</h3>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=adminCreate" class="btn btn-light">Add New User</a>
                </div>
                <div class="card-body">
                    <?php flash('user_success'); ?>
                    <?php flash('user_error', '', 'alert alert-danger'); ?>
                    
                    <?php if(empty($users['data'])): ?>
                        <div class="alert alert-info">No users found.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users['data'] as $user): ?>
                                        <tr>
                                            <td><?php echo $user['id']; ?></td>
                                            <td><?php echo $user['username']; ?></td>
                                            <td><?php echo $user['email']; ?></td>
                                            <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                                            <td>
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
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                            <td>
                                                <a href="<?php echo BASE_URL; ?>?controller=user&action=adminEdit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                <?php if($user['id'] != $_SESSION['user_id']): ?>
                                                    <a href="<?php echo BASE_URL; ?>?controller=user&action=adminDelete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php echo getPaginationLinks($users['current_page'], $users['total_pages'], BASE_URL . '?controller=user&action=adminIndex'); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
