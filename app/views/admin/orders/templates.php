<?php 
// Check if admin
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ' . URLROOT . '/user/login');
    exit();
}

// Set page title
$pageTitle = 'Order Templates';

// Include header
require_once APPROOT . '/app/views/admin/layouts/header.php';
?>

<!-- Main content section -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo $pageTitle; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo URLROOT; ?>/order/templates/create" class="btn btn-sm btn-outline-primary">
            <i class="fas fa-plus"></i> Create Template
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <?php flash('template_success'); ?>
            <?php flash('template_error', 'alert alert-danger'); ?>

            <?php if (empty($data['templates'])) : ?>
                <div class="alert alert-info">No order templates found.</div>
            <?php else : ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($data['templates'] as $template) : ?>
                                        <tr>
                                            <td><?php echo $template['id']; ?></td>
                                            <td><?php echo htmlspecialchars($template['name']); ?></td>
                                            <td><?php echo htmlspecialchars($template['description']); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($template['created_at'])); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo URLROOT; ?>/order/templates/edit/<?php echo $template['id']; ?>" 
                                                       class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/order/templates/delete/<?php echo $template['id']; ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Are you sure you want to delete this template?');" 
                                                       title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <a href="<?php echo URLROOT; ?>/order/templates/use/<?php echo $template['id']; ?>" 
                                                       class="btn btn-sm btn-success" 
                                                       title="Use Template">
                                                        <i class="fas fa-check"></i> Use
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
// Include footer
require_once APPROOT . '/app/views/admin/layouts/footer.php'; 
?>
