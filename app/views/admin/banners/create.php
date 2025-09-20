<?php
// Admin layout header
require_once APP_PATH . 'views/admin/layouts/header.php';
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Add New Banner</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=banner">Banners</a></li>
        <li class="breadcrumb-item active">Add New</li>
    </ol>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus me-1"></i>
            Create New Banner
        </div>
        <div class="card-body">
            <!-- Keep field names matching BannerController::create() -->
            <form method="POST" action="<?php echo BASE_URL; ?>?controller=banner&action=create" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Banner Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                    <small class="text-muted">Recommended size: 1920x800 pixels.</small>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Create Banner</button>
                    <a href="<?php echo BASE_URL; ?>?controller=banner" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Admin layout footer
require_once APP_PATH . 'views/admin/layouts/footer.php';
?>
