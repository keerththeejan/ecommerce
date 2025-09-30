<?php
require_once __DIR__ . '/../customer/layouts/header.php';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2>Banner Details</h2>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Banner Information</h4>
                            <div class="mb-3">
                                <strong>Title:</strong> <?php echo htmlspecialchars($banner['title']); ?>
                            </div>
                            <div class="mb-3">
                                <strong>Description:</strong> <?php echo nl2br(htmlspecialchars($banner['description'])); ?>
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong> <?php echo htmlspecialchars($banner['status']); ?>
                            </div>
                            <div class="mt-4">
                                <a href="?controller=banner&action=edit&id=<?php echo $banner['id']; ?>" class="btn btn-primary">Edit Banner</a>
                                <a href="?controller=banner" class="btn btn-secondary">Back to List</a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Banner Preview</h4>
                            <div class="text-center">
                                <img src="<?php echo BASE_URL . htmlspecialchars($banner['image_url']); ?>" 
                                     alt="Banner Preview" 
                                     class="img-fluid rounded" 
                                     style="max-height: 500px; object-fit: contain;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../customer/layouts/footer.php';
?>
