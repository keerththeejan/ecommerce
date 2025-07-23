<?php
require_once __DIR__ . '/../customer/layouts/header.php';
?>

<div class="container mt-4">
    <h2>Banners Management</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="?controller=banner&action=create" class="btn btn-primary">Add New Banner</a>
    </div>

    
    <div class="row">
        <?php foreach ($banners as $banner): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?php echo htmlspecialchars($banner['image_url']); ?>" class="card-img-top" alt="Banner Image" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($banner['title']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($banner['description']); ?></p>
                    <p class="card-text"><small class="text-muted">ID: <?php echo htmlspecialchars($banner['id']); ?></small></p>
                    <p class="card-text">
                        Status: <span class="badge bg-<?php echo $banner['status'] === 'active' ? 'success' : 'secondary'; ?>">
                            <?php echo htmlspecialchars(ucfirst($banner['status'])); ?>
                        </span>
                    </p>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="?controller=banner&action=show&id=<?php echo $banner['id']; ?>" class="btn btn-sm btn-info me-1">View</a>
                    <a href="?controller=banner&action=edit&id=<?php echo $banner['id']; ?>" class="btn btn-sm btn-primary me-1">Edit</a>
                    <a href="?controller=banner&action=delete&id=<?php echo $banner['id']; ?>" class="btn btn-sm btn-danger" 
                       onclick="return confirm('Are you sure you want to delete this banner?')">
                        Delete
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
require_once __DIR__ . '/../customer/layouts/footer.php';
?>
