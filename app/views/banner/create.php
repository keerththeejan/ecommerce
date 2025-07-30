<?php
require_once __DIR__ . '/../customer/layouts/header.php';
?>

<div class="container mt-4">
    <h2>Add New Banner</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
            echo htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="?controller=banner&action=create" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Banner Image</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        </div>
        
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Create Banner</button>
        <a href="?controller=banner" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
require_once __DIR__ . '/../customer/layouts/footer.php';
?>
