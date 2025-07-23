<?php
// Set the current page for the active menu highlighting
$current_page = 'banners';
$page_title = 'Banner Management';
ob_start();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">Banner Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo BASE_URL; ?>admin/banners/create" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Banner
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($banners)): ?>
                <?php foreach ($banners as $index => $banner): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td>
                            <img src="<?php echo BASE_URL . ltrim($banner['image_url'], '/'); ?>" 
                                 alt="<?php echo htmlspecialchars($banner['title']); ?>" 
                                 style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td><?php echo htmlspecialchars($banner['title']); ?></td>
                        <td><?php echo htmlspecialchars(substr($banner['description'], 0, 50)) . '...'; ?></td>
                        <td>
                            <span class="badge bg-<?php echo $banner['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                <?php echo ucfirst($banner['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="<?php echo BASE_URL; ?>admin/banners/edit/<?php echo $banner['id']; ?>" 
                               class="btn btn-sm btn-warning" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-sm btn-danger delete-banner" 
                                    data-id="<?php echo $banner['id']; ?>"
                                    title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No banners found. <a href="<?php echo BASE_URL; ?>admin/banners/create">Add your first banner</a></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this banner? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
// Include the admin layout with sidebar
require_once __DIR__ . '/../../layouts/admin.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete button click
    const deleteButtons = document.querySelectorAll('.delete-banner');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    let bannerIdToDelete = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteBannerModal'));

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            bannerIdToDelete = this.getAttribute('data-id');
            deleteModal.show();
        });
    });

    confirmDeleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (bannerIdToDelete) {
            window.location.href = `<?php echo BASE_URL; ?>admin/banners/delete/${bannerIdToDelete}`;
        }
    });
});
</script>
