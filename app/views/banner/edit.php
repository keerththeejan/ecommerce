<?php
require_once __DIR__ . '/../customer/layouts/header.php';
?>

<div class="container mt-4">
    <h2>Edit Banner</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
            echo htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="?controller=banner&action=update&id=<?php echo $banner['id']; ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($banner['title']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($banner['description']); ?></textarea>
        </div>
        
        <div class="mb-3">
            <label for="current_image" class="form-label">Current Image</label>
            <div class="d-flex align-items-center">
                <img src="<?php echo BASE_URL . htmlspecialchars($banner['image_url']); ?>" alt="Current Banner" style="max-width: 150px; margin-right: 15px;">
                <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($banner['image_url']); ?>">
            </div>
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">New Image (Optional)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
        </div>
        
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="active" <?php echo $banner['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                <option value="inactive" <?php echo $banner['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Banner</button>
        <a href="?controller=banner" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
require_once __DIR__ . '/../customer/layouts/footer.php';
?>
