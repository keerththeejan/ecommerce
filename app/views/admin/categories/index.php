<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
    .page-shell {
        width: 100%;
        max-width: none;
        margin: 0;
    }

    .page-title {
        font-weight: 600;
        letter-spacing: -0.02em;
        margin-bottom: 0;
    }
    .page-subtitle {
        color: var(--muted-color);
        font-size: 0.9rem;
        margin-top: 0.25rem;
        margin-bottom: 0;
    }

    .categories-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding: 1rem 1rem 0.5rem 1rem;
    }
    .categories-toolbar__left {
        min-width: 260px;
        flex: 1 1 420px;
    }
    .categories-toolbar__right {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 0 0 auto;
        flex-wrap: wrap;
    }

    .categories-table-scroll {
        max-height: 65vh;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
        border-top: 1px solid var(--border-color);
    }

    .categories-table {
        margin-bottom: 0;
    }

    .categories-table thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: var(--surface-color);
        box-shadow: 0 1px 0 0 var(--border-color);
        border-top: 0;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.02em;
        color: var(--muted-color);
        text-transform: uppercase;
        padding: 0.75rem 0.9rem;
        white-space: nowrap;
    }

    .categories-table tbody td {
        padding: 0.8rem 0.9rem;
        vertical-align: middle;
    }

    .categories-table tbody tr {
        background: transparent;
    }

    .categories-table tbody tr:nth-child(even) {
        background: rgba(17, 24, 39, 0.02);
    }

    [data-theme="dark"] .categories-table tbody tr:nth-child(even) {
        background: rgba(255, 255, 255, 0.04);
    }

    .categories-table tbody tr:hover {
        background: rgba(59, 130, 246, 0.06);
    }

    .cat-thumb {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid var(--border-color);
        background: var(--surface-muted);
    }

    .cat-name {
        font-weight: 600;
        line-height: 1.1;
    }

    .cat-meta {
        color: var(--muted-color);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .badge-status {
        padding: 0.35rem 0.55rem;
        font-weight: 600;
        border-radius: 999px;
        border: 1px solid transparent;
        font-size: 0.78rem;
        letter-spacing: 0.01em;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .badge-status--active {
        color: #146c43;
        background: rgba(25, 135, 84, 0.12);
        border-color: rgba(25, 135, 84, 0.22);
    }
    .badge-status--inactive {
        color: #5c636a;
        background: rgba(108, 117, 125, 0.12);
        border-color: rgba(108, 117, 125, 0.22);
    }

    [data-theme="dark"] .badge-status--active {
        color: #7ee2b8;
        background: rgba(25, 135, 84, 0.20);
        border-color: rgba(25, 135, 84, 0.35);
    }
    [data-theme="dark"] .badge-status--inactive {
        color: rgba(248,249,250,0.82);
        background: rgba(108, 117, 125, 0.18);
        border-color: rgba(108, 117, 125, 0.35);
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        border-radius: 10px;
        font-weight: 600;
    }

    .table-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        flex-wrap: wrap;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--border-color);
    }
    .table-topbar .form-label { margin-bottom: 0; }

    /* Mobile: stacked cards */
    @media (max-width: 575.98px) {
        .categories-table-scroll { max-height: 60vh; }
        #categoriesTable thead { display: none; }
        #categoriesTable tbody tr {
            display: block;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            margin: 0.75rem;
            background: var(--surface-color);
            overflow: hidden;
        }
        #categoriesTable tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            padding: 0.7rem 0.85rem;
            border: 0;
            border-bottom: 1px solid var(--border-color);
        }
        #categoriesTable tbody td:last-child { border-bottom: 0; }
        #categoriesTable tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--muted-color);
        }
        #categoriesTable tbody td[data-label="Actions"] {
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .btn-action {
            padding: 0.35rem 0.55rem;
        }
    }

    .style-guide {
        border-top: 1px dashed var(--border-color);
        margin-top: 1rem;
        padding-top: 1rem;
    }
    .sg-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.35rem 0.55rem;
        border: 1px solid var(--border-color);
        border-radius: 999px;
        background: var(--surface-muted);
        font-size: 0.85rem;
        color: var(--text-color);
        margin-right: 0.4rem;
        margin-bottom: 0.4rem;
        white-space: nowrap;
    }
    .sg-swatch {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        border: 1px solid var(--border-color);
        flex: 0 0 auto;
    }
</style>

<div class="container-fluid page-shell">
    <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap: 0.75rem; margin-bottom: 0.75rem;">
        <div>
            <h1 class="page-title">Category Management</h1>
            <p class="page-subtitle">Organize your catalog with parent categories, tax rules, and visibility status.</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 0.5rem; flex-wrap: wrap;">
            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i>
                <span class="ml-1">Products</span>
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=category&action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                <span class="ml-1">Add Category</span>
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="categories-toolbar">
            <div class="categories-toolbar__left">
                <form method="GET" action="<?php echo BASE_URL; ?>" class="mb-0">
                    <input type="hidden" name="controller" value="category">
                    <input type="hidden" name="action" value="adminIndex">
                    <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($categories['per_page_param'] ?? '20'); ?>">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control" placeholder="Search categories..." value="<?php echo htmlspecialchars($categories['search'] ?? ''); ?>" aria-label="Search categories">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-outline-primary">Search</button>
                            <?php if(!empty($categories['search'])): ?>
                                <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex&per_page=<?php echo htmlspecialchars($categories['per_page_param'] ?? '20'); ?>" class="btn btn-outline-secondary">Clear</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="categories-toolbar__right">
                <a href="<?php echo BASE_URL; ?>?controller=category&action=create" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i>
                    <span class="ml-1">New</span>
                </a>
            </div>
        </div>

        <div class="card-body pt-2">
                    <div id="alert-messages">
                        <?php flash('category_success'); ?>
                        <?php flash('category_error', '', 'alert alert-danger'); ?>
                    </div>
                    
                    <?php if(empty($categories['data'])): ?>
                        <div class="alert alert-info">
                            <?php if(!empty($categories['search'])): ?>
                                No categories found for "<?php echo htmlspecialchars($categories['search']); ?>". <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex&per_page=<?php echo htmlspecialchars($categories['per_page_param'] ?? '20'); ?>">Show all</a>
                            <?php else: ?>
                                No categories found.
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <?php
                            $cur = (int)($categories['current_page'] ?? 1);
                            $last = (int)($categories['last_page'] ?? 1);
                            $perParam = $categories['per_page_param'] ?? '20';
                            $s = trim((string)($categories['search'] ?? ''));
                            $pageBase = BASE_URL . '?controller=category&action=adminIndex&per_page=' . urlencode((string)$perParam);
                            if ($s !== '') $pageBase .= '&search=' . urlencode($s);
                        ?>

                        <div class="table-topbar">
                            <div class="d-flex align-items-center" style="gap: 0.5rem; flex-wrap: wrap;">
                                <label for="perPageFilter" class="form-label small text-muted">Rows</label>
                                <select id="perPageFilter" class="custom-select custom-select-sm" style="width: auto;">
                                <?php 
                                $currentPerPage = $categories['per_page_param'] ?? '20';
                                $currentSearch = $categories['search'] ?? '';
                                $baseUrl = BASE_URL . '?controller=category&action=adminIndex';
                                foreach (['20', '50', '100', 'all'] as $opt): 
                                    $sel = ($currentPerPage === $opt) ? ' selected' : '';
                                    $url = $baseUrl . '&per_page=' . $opt;
                                    if ($currentSearch !== '') {
                                        $url .= '&search=' . urlencode($currentSearch);
                                    }
                                ?>
                                    <option value="<?php echo htmlspecialchars($url); ?>"<?php echo $sel; ?>><?php echo $opt === 'all' ? 'All' : $opt; ?></option>
                                <?php endforeach; ?>
                                </select>
                                <span class="small text-muted"><?php echo (int)($categories['total'] ?? 0); ?> total</span>
                            </div>

                            <div class="d-flex align-items-center" style="gap: 0.5rem; flex-wrap: wrap;">
                                <a class="btn btn-sm btn-outline-secondary <?php echo ($cur <= 1) ? 'disabled' : ''; ?>" href="<?php echo $pageBase . '&page=' . max(1, $cur - 1); ?>" aria-label="Previous page">Prev</a>
                                <span class="small text-muted">Page <?php echo $cur; ?> / <?php echo $last; ?></span>
                                <a class="btn btn-sm btn-outline-secondary <?php echo ($cur >= $last) ? 'disabled' : ''; ?>" href="<?php echo $pageBase . '&page=' . min($last, $cur + 1); ?>" aria-label="Next page">Next</a>
                            </div>
                        </div>

                        <script>
                        document.getElementById('perPageFilter').addEventListener('change', function() {
                            window.location.href = this.value;
                        });
                        </script>

                        <div class="categories-table-scroll" role="region" aria-label="Categories table" tabindex="0">
                            <table id="categoriesTable" class="table categories-table" aria-describedby="categories-helptext">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Parent</th>
                                        <th>Tax Rate</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="categories-table-body">
                                    <?php 
                                    $catPage = isset($categories['current_page']) ? (int)$categories['current_page'] : 1;
                                    $catPerPage = isset($categories['per_page']) ? (int)$categories['per_page'] : 20;
                                    foreach($categories['data'] as $idx => $category): 
                                        $rowNum = ($catPage - 1) * $catPerPage + $idx + 1;
                                    ?>
                                        <tr id="category-row-<?php echo $category['id']; ?>">
                                            <td data-label="#"><?php echo $rowNum; ?></td>
                                            <td data-label="Image">
                                                <?php 
                                                    $thumb = !empty($category['image']) 
                                                        ? (strpos($category['image'], 'uploads/') === 0 
                                                            ? BASE_URL . $category['image'] 
                                                            : BASE_URL . 'uploads/categories/' . $category['image']) 
                                                        : BASE_URL . 'assets/img/no-image.png';
                                                ?>
                                                <img src="<?php echo $thumb; ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="cat-thumb" loading="lazy">
                                            </td>
                                            <td data-label="Name">
                                                <div class="cat-name"><?php echo htmlspecialchars($category['name']); ?></div>
                                                <div class="cat-meta">ID: <?php echo (int)$category['id']; ?></div>
                                            </td>
                                            <td data-label="Parent">
                                                <?php if(!empty($category['parent_name'])): ?>
                                                    <?php echo htmlspecialchars($category['parent_name']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">None</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Tax Rate">
                                                <?php if(!empty($category['tax_name'])): ?>
                                                    <?php echo htmlspecialchars($category['tax_name'] . ' (' . $category['tax_rate'] . '%)'); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not set</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Status">
                                                <?php if((int)($category['status'] ?? 0) === 1): ?>
                                                    <span class="badge-status badge-status--active"><span style="width:6px;height:6px;border-radius:999px;background:#198754;display:inline-block;"></span>Active</span>
                                                <?php else: ?>
                                                    <span class="badge-status badge-status--inactive"><span style="width:6px;height:6px;border-radius:999px;background:#6c757d;display:inline-block;"></span>Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Actions">
                                                <a href="<?php echo BASE_URL; ?>?controller=category&action=edit&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-outline-primary btn-action" aria-label="Edit category <?php echo htmlspecialchars($category['name']); ?>">
                                                    <i class="fas fa-pen"></i>
                                                    <span class="d-none d-sm-inline">Edit</span>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-category" data-id="<?php echo $category['id']; ?>" data-name="<?php echo htmlspecialchars($category['name']); ?>" aria-label="Delete category <?php echo htmlspecialchars($category['name']); ?>">
                                                    <i class="fas fa-trash"></i>
                                                    <span class="d-none d-sm-inline">Delete</span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="categories-helptext" class="sr-only">Use search to filter categories. Use edit and delete buttons in the actions column to manage a category.</div>

                        <div class="style-guide">
                            <h6 class="mb-2">Style Guide</h6>
                            <div class="mb-2">
                                <span class="sg-chip"><span class="sg-swatch" style="background:#3b82f6"></span>Primary</span>
                                <span class="sg-chip"><span class="sg-swatch" style="background:#0f172a"></span>Sidebar</span>
                                <span class="sg-chip"><span class="sg-swatch" style="background:#198754"></span>Success</span>
                                <span class="sg-chip"><span class="sg-swatch" style="background:#dc3545"></span>Danger</span>
                                <span class="sg-chip"><span class="sg-swatch" style="background:#6b7280"></span>Muted</span>
                            </div>
                            <div class="small text-muted">
                                Font: Inter (400/500/600). Spacing: 8px grid (8, 16, 24). Corners: 10–14px. Focus: preserve keyboard navigation and high contrast.
                            </div>
                        </div>
                    <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category "<span id="category-name"></span>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let categoryToDelete = null;
    const deleteButtons = document.querySelectorAll('.delete-category');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteCategoryModal'));
    const categoryNameSpan = document.getElementById('category-name');
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    const alertMessages = document.getElementById('alert-messages');

    // Handle delete button click
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-id');
            const categoryName = this.getAttribute('data-name');
            
            categoryToDelete = categoryId;
            categoryNameSpan.textContent = categoryName;
            deleteModal.show();
        });
    });

    // Handle confirm delete
    confirmDeleteBtn.addEventListener('click', function() {
        if (!categoryToDelete) return;
        
        const button = this;
        const originalText = button.innerHTML;
        
        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
        
        // Send AJAX request
        fetch(`<?php echo BASE_URL; ?>?controller=category&action=delete&id=${categoryToDelete}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                const row = document.getElementById(`category-row-${categoryToDelete}`);
                if (row) row.remove();
                
                // Show success message
                showAlert('Category deleted successfully', 'success');
                
                // If no more rows, show a message
                if (document.querySelectorAll('#categories-table-body tr').length === 0) {
                    const tbody = document.getElementById('categories-table-body');
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center">No categories found.</td></tr>';
                }
            } else {
                showAlert(data.message || 'Failed to delete category', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while deleting the category', 'danger');
        })
        .finally(() => {
            // Reset button state
            button.disabled = false;
            button.innerHTML = originalText;
            // Hide modal
            deleteModal.hide();
            // Reset category to delete
            categoryToDelete = null;
        });
    });
    
    // Function to show alert messages
    function showAlert(message, type = 'success') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Clear previous alerts
        alertMessages.innerHTML = '';
        // Add new alert
        alertMessages.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
            if (alert) alert.close();
        }, 5000);
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
