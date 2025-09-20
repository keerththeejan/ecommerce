<?php
// Set the current page for the active menu highlighting
$current_page = 'banners';
$page_title = 'Banner Management';
// Use the admin layout header
require_once APP_PATH . 'views/admin/layouts/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">Banner Management</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo BASE_URL; ?>?controller=banner&action=create" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add New Banner
        </a>
    </div>
</div>

<style>
  /* Mobile-first responsive table that stacks rows on small screens */
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
    /* Image size on mobile */
    .banner-thumb { width: 80px !important; height: 48px !important; }
    /* Actions wrap nicely */
    .actions-wrap { display: flex; gap: .5rem; flex-wrap: wrap; }
  }
</style>

<div class="table-responsive">
    <table class="table table-striped table-hover responsive-table">
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
                        <td data-label="#"><?php echo $index + 1; ?></td>
                        <td data-label="Image">
                            <img class="banner-thumb" src="<?php echo BASE_URL . ltrim($banner['image_url'], '/'); ?>" 
                                 alt="<?php echo htmlspecialchars($banner['title']); ?>" 
                                 style="width: 100px; height: 60px; object-fit: cover; border-radius: 4px;">
                        </td>
                        <td data-label="Title"><?php echo htmlspecialchars($banner['title']); ?></td>
                        <td data-label="Description"><?php echo htmlspecialchars(substr($banner['description'], 0, 50)) . '...'; ?></td>
                        <td data-label="Status">
                            <span class="badge bg-<?php echo $banner['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                <?php echo ucfirst($banner['status']); ?>
                            </span>
                        </td>
                        <td data-label="Actions">
                            <div class="actions-wrap">
                                <a href="<?php echo BASE_URL; ?>?controller=banner&action=edit&id=<?php echo $banner['id']; ?>" 
                                   class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger delete-banner" 
                                        data-id="<?php echo $banner['id']; ?>"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No banners found. <a href="<?php echo BASE_URL; ?>?controller=banner&action=create">Add your first banner</a></td>
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
            window.location.href = `<?php echo BASE_URL; ?>?controller=banner&action=delete&id=${bannerIdToDelete}`;
        }
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
