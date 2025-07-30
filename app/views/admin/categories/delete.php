<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0">Delete Category</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h4 class="alert-heading">Warning!</h4>
                        <p>Are you sure you want to delete this category? This action cannot be undone.</p>
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h4><?php echo $category['name']; ?></h4>
                            <?php if(!empty($category['description'])): ?>
                                <p><?php echo $category['description']; ?></p>
                            <?php else: ?>
                                <p class="text-muted">No description</p>
                            <?php endif; ?>
                            
                            <p><strong>Status:</strong> 
                                <?php if($category['status'] == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=category&action=delete&id=<?php echo $category['id']; ?>" method="POST">
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-danger">Delete Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
