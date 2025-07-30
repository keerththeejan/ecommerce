<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Delete Brand</h1>
        <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Brands
        </a>
    </div>
    
    <div class="card">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-danger">Confirm Deletion</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i> Are you sure you want to delete this brand? This action cannot be undone.
            </div>
            
            <div class="row mb-4">
                <div class="col-md-8">
                    <h4><?php echo $brand['name']; ?></h4>
                    <?php if(!empty($brand['description'])): ?>
                        <p><?php echo $brand['description']; ?></p>
                    <?php endif; ?>
                    <p>
                        <strong>Status:</strong> 
                        <?php if($brand['status'] == 'active'): ?>
                            <span class="badge bg-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactive</span>
                        <?php endif; ?>
                    </p>
                    <p><strong>Created:</strong> <?php echo date('F j, Y, g:i a', strtotime($brand['created_at'])); ?></p>
                    <p><strong>Last Updated:</strong> <?php echo date('F j, Y, g:i a', strtotime($brand['updated_at'])); ?></p>
                </div>
                <div class="col-md-4 text-center">
                    <?php if(!empty($brand['logo'])): ?>
                        <img src="<?php echo BASE_URL . $brand['logo']; ?>" alt="<?php echo $brand['name']; ?>" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                    <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; margin: 0 auto; border: 1px dashed #ccc;">
                            <i class="fas fa-building text-muted fa-4x"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <form action="<?php echo BASE_URL; ?>?controller=brand&action=delete&id=<?php echo $brand['id']; ?>" method="POST">
                <div class="d-flex justify-content-end">
                    <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-danger">Delete Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
