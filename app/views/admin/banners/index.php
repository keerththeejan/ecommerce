<?php
$current_page = 'banners';
$page_title = 'Banner Management';
require_once APP_PATH . 'views/admin/layouts/header.php';
?>
<style>
/* Banners admin – trending responsive */
.banners-admin .card-body { padding: 1rem; }
@media (min-width: 768px) { .banners-admin .card-body { padding: 1.25rem; } }
.banners-table-scroll {
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,.08);
  box-shadow: inset 0 1px 3px rgba(0,0,0,.05);
}
.banners-table-scroll .table { margin-bottom: 0; border-radius: 12px; }
.banners-table-scroll thead th {
  position: sticky;
  top: 0;
  z-index: 1;
  white-space: nowrap;
  font-weight: 600;
  font-size: 0.85rem;
  padding: 0.75rem;
  background: var(--bs-body-bg, #fff);
  color: var(--bs-body-color, #212529);
  box-shadow: 0 1px 0 0 var(--bs-border-color, #dee2e6);
}
.banners-table-scroll tbody td { padding: 0.65rem 0.75rem; vertical-align: middle; }
.banner-thumb { width: 80px; height: 50px; object-fit: cover; border-radius: 8px; }
@media (max-width: 575.98px) { .banners-table-scroll { max-height: 55vh; } }
@media (min-width: 576px) and (max-width: 991.98px) { .banners-table-scroll { max-height: 60vh; } }
@media (min-width: 992px) { .banners-table-scroll { max-height: 70vh; } }
@media (min-width: 576px) and (max-width: 991.98px) {
  #bannersTable th:nth-child(4), #bannersTable td:nth-child(4) { display: none !important; }
}
@media (max-width: 575.98px) {
  #bannersTable thead { display: none; }
  #bannersTable tbody tr {
    display: block;
    margin-bottom: 1rem;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg, #fff);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  #bannersTable tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid rgba(0,0,0,.06);
  }
  #bannersTable tbody td:last-child { border-bottom: 0; }
  #bannersTable tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--bs-secondary, #6c757d);
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  #bannersTable tbody td[data-label="Image"] { display: block; padding: 0; }
  #bannersTable tbody td[data-label="Image"]::before { content: none; }
  #bannersTable tbody td[data-label="Image"] .banner-thumb { width: 100% !important; max-height: 140px; object-fit: contain; border-radius: 0; }
  #bannersTable tbody td[data-label="Actions"] { flex-wrap: wrap; gap: 0.25rem; }
  #bannersTable tbody td[data-label="Actions"] .actions-wrap { width: 100%; justify-content: flex-end; flex-wrap: wrap; }
}
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 banners-admin">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 mb-4">
        <h1 class="h3 mb-0">Banner Management</h1>
        <div>
            <a href="<?php echo BASE_URL; ?>?controller=banner&action=create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add New Banner
            </a>
        </div>
    </div>

    <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0">All Banners</h5>
        </div>
        <div class="card-body p-0 p-md-3">
            <div class="banners-table-scroll table-responsive">
                <table id="bannersTable" class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 60px;">#</th>
                            <th style="width: 100px;">Image</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th style="width: 90px;">Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($banners)): ?>
                            <?php foreach ($banners as $index => $banner): 
                                $desc = isset($banner['description']) && $banner['description'] !== '' 
                                    ? (strlen($banner['description']) > 50 ? substr($banner['description'], 0, 50) . '...' : $banner['description']) 
                                    : '—';
                            ?>
                                <tr>
                                    <td data-label="#"><?php echo $index + 1; ?></td>
                                    <td data-label="Image">
                                        <img class="banner-thumb" src="<?php echo BASE_URL . ltrim($banner['image_url'] ?? '', '/'); ?>" 
                                             alt="<?php echo htmlspecialchars($banner['title'] ?? ''); ?>" 
                                             onerror="this.style.display='none'">
                                    </td>
                                    <td data-label="Title"><?php echo htmlspecialchars($banner['title'] ?? ''); ?></td>
                                    <td data-label="Description"><?php echo htmlspecialchars($desc); ?></td>
                                    <td data-label="Status">
                                        <span class="badge bg-<?php echo ($banner['status'] ?? '') === 'active' ? 'success' : 'secondary'; ?>">
                                            <?php echo ucfirst($banner['status'] ?? 'inactive'); ?>
                                        </span>
                                    </td>
                                    <td data-label="Actions">
                                        <div class="actions-wrap d-flex flex-wrap gap-1">
                                            <a href="<?php echo BASE_URL; ?>?controller=banner&action=edit&id=<?php echo $banner['id']; ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger delete-banner" 
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
                                <td colspan="6" class="text-center py-4">No banners found. <a href="<?php echo BASE_URL; ?>?controller=banner&action=create">Add your first banner</a></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
