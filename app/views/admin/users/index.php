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
                    <style>
                        /* Widen status elements */
                        .status-actions .btn {
                            width: 34px;
                            height: 34px;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            padding: 0 !important;
                            border-radius: 6px;
                        }
                        .status-actions .badge {
                            min-width: 90px;
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            padding: 0.4rem 0.6rem;
                            font-weight: 600;
                        }
                    </style>
                    <?php flash('user_success'); ?>
                    <?php flash('user_error', '', 'alert alert-danger'); ?>
                    
                    <?php if(empty($users['data'])): ?>
                        <div class="alert alert-info">No users found.</div>
                    <?php else: ?>
                        <style>
                        /* Mobile-first responsive table styling */
                        @media (max-width: 576.98px) {
                            table.responsive-table thead { display: none; }
                            table.responsive-table,
                            table.responsive-table tbody,
                            table.responsive-table tr,
                            table.responsive-table td { display: block; width: 100%; }
                            table.responsive-table tr {
                                margin-bottom: 1rem;
                                border: 1px solid rgba(0,0,0,.075);
                                border-radius: .5rem;
                                overflow: hidden;
                                background: var(--bg-color, #fff);
                            }
                            table.responsive-table td {
                                padding: .5rem .75rem;
                                border: none;
                                border-bottom: 1px solid rgba(0,0,0,.05);
                            }
                            table.responsive-table td:last-child { border-bottom: 0; }
                            table.responsive-table td::before {
                                content: attr(data-label);
                                font-weight: 600;
                                display: block;
                                margin-bottom: .25rem;
                                opacity: .8;
                            }
                            .user-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
                        }
                        </style>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover responsive-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($users['data'] as $user): ?>
                                        <tr>
                                            <td data-label="ID"><?php echo $user['id']; ?></td>
                                            <td data-label="Username"><?php echo $user['username']; ?></td>
                                            <td data-label="Email"><?php echo $user['email']; ?></td>
                                            <td data-label="Name"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
                                            <td data-label="Role">
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
                                            <td data-label="Status">
                                                <?php 
                                                    $status = $user['status'] ?? '';
                                                    $badgeClass = 'bg-secondary';
                                                    $label = '—';
                                                    $s = strtolower((string)$status);
                                                    if ($status) {
                                                        if ($s === 'accepted' || $s === 'approved') { $badgeClass = 'bg-success'; $label = 'Accepted'; }
                                                        elseif ($s === 'pending') { $badgeClass = 'bg-warning text-dark'; $label = 'Pending'; }
                                                        elseif ($s === 'rejected') { $badgeClass = 'bg-danger'; $label = 'Rejected'; }
                                                        else { $label = ucfirst($status); }
                                                    }
                                                ?>
                                                <div class="d-flex align-items-center gap-2 status-actions">
                                                    <?php 
                                                        $canModerate = ($s === '' || $s === 'pending');
                                                    ?>
                                                    <?php if (!$canModerate): ?>
                                                        <span class="badge <?php echo $badgeClass; ?>"><?php echo $label; ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($canModerate && ($user['id'] ?? 0) != ($_SESSION['user_id'] ?? 0)): ?>
                                                        <a title="Accept" href="<?php echo BASE_URL; ?>?controller=user&action=adminApprove&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-success p-1">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a title="Reject" href="<?php echo BASE_URL; ?>?controller=user&action=adminReject&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger p-1">
                                                            <i class="fas fa-times"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td data-label="Created"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                            <td data-label="Actions">
                                                <div class="user-actions">
                                                    <a href="<?php echo BASE_URL; ?>?controller=user&action=adminEdit&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                                    <?php if($user['id'] != $_SESSION['user_id']): ?>
                                                        <a href="<?php echo BASE_URL; ?>?controller=user&action=adminDelete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                                                    <?php endif; ?>
                                                </div>
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
