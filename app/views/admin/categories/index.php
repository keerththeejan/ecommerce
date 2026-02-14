<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
    /* Inside scrollbar: table scrolls within this container */
    .categories-table-scroll {
        max-height: 65vh;
        overflow: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 10px;
        border: 1px solid rgba(0,0,0,.08);
    }
    .categories-table-scroll .table { margin-bottom: 0; }
    .categories-table-scroll thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background: var(--bs-body-bg, #fff);
        color: var(--bs-body-color, #212529);
        box-shadow: 0 1px 0 0 var(--bs-border-color, #dee2e6);
        padding: 0.75rem;
    }
    @media (max-width: 575.98px) {
        .categories-table-scroll { max-height: 55vh; }
    }
    /* Responsive tweaks for header action buttons */
    @media (max-width: 576px) {
        .card-header {
            flex-wrap: nowrap;          /* keep everything on one line */
            gap: .25rem .25rem;
        }
        .card-header .card-title {
            flex: 1 1 auto;             /* title can shrink */
            min-width: 0;               /* allow ellipsis */
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: .95rem;          /* smaller title saves space */
            margin-bottom: 0;
        }
        .admin-actions {
            flex-wrap: nowrap;           /* keep buttons on one line */
            overflow: hidden;            /* no scrollbar */
            gap: .25rem;                 /* tighter spacing */
            width: auto;                 /* stay inline with title */
            justify-content: flex-end;   /* align buttons to right */
        }
        .admin-actions .btn {
            white-space: nowrap;
            padding: .2rem .35rem;      /* smaller padding */
            font-size: .75rem;          /* smaller text */
            border-radius: .3rem;
        }
        .admin-actions .btn i {
            font-size: .8em;            /* smaller icon */
            margin-right: .25rem;       /* compact spacing */
        }

        /* Categories table -> stacked cards on mobile */
        #categoriesTable thead {
            display: none;
        }
        #categoriesTable tbody tr {
            display: block;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: .5rem;
            margin-bottom: .75rem;
        }
        #categoriesTable tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
            padding: .5rem .75rem;
            border: 0 !important;
        }
        #categoriesTable tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
        }
        #categoriesTable tbody td[data-label="Actions"] {
            justify-content: flex-start;
            flex-wrap: wrap;
            gap: .5rem;
        }
        #categoriesTable tbody td[data-label="Actions"] .btn {
            padding: .25rem .5rem;
            font-size: .8rem;
        }
        #categoriesTable tbody td img {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 4px;
        }
    }
    /* Extra-tight sizes to avoid horizontal scroll on very small devices */
    @media (max-width: 420px) {
        .admin-actions { gap: .2rem; }
        .admin-actions .btn { padding: .18rem .3rem; font-size: .72rem; }
        .admin-actions .btn i { font-size: .75em; margin-right: .22rem; }
    }
    @media (max-width: 360px) {
        .admin-actions { gap: .15rem; }
        .admin-actions .btn { padding: .16rem .26rem; font-size: .68rem; }
        .admin-actions .btn i { font-size: .7em; margin-right: .2rem; }
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Categories</h3>
                    <div class="admin-actions d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i>
                            <span class="d-none d-sm-inline">Back to Product</span>
                            <span class="d-inline d-sm-none">Back</span>
                        </a>
                        <a href="<?php echo BASE_URL; ?>?controller=category&action=create" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i>
                            <span class="d-none d-sm-inline">Add New Category</span>
                            <span class="d-inline d-sm-none">Add</span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="alert-messages">
                        <?php flash('category_success'); ?>
                        <?php flash('category_error', '', 'alert alert-danger'); ?>
                    </div>
                    
                    <!-- Search -->
                    <form method="GET" action="<?php echo BASE_URL; ?>" class="mb-4">
                        <input type="hidden" name="controller" value="category">
                        <input type="hidden" name="action" value="adminIndex">
                        <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($categories['per_page_param'] ?? '20'); ?>">
                        <div class="row g-2 align-items-center">
                            <div class="col-12 col-md-8 col-lg-6">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Search by name, description, parent, tax rate..." 
                                           value="<?php echo htmlspecialchars($categories['search'] ?? ''); ?>">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <?php if(!empty($categories['search'])): ?>
                                    <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex&per_page=<?php echo htmlspecialchars($categories['per_page_param'] ?? '20'); ?>" class="btn btn-outline-secondary">Clear</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <?php if(empty($categories['data'])): ?>
                        <div class="alert alert-info">
                            <?php if(!empty($categories['search'])): ?>
                                No categories found for "<?php echo htmlspecialchars($categories['search']); ?>". <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex&per_page=<?php echo htmlspecialchars($categories['per_page_param'] ?? '20'); ?>">Show all</a>
                            <?php else: ?>
                                No categories found.
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="categories-table-scroll table-responsive">
                            <table id="categoriesTable" class="table table-striped table-hover">
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
                                                <img src="<?php echo $thumb; ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" style="width:40px;height:40px;object-fit:cover;border-radius:4px;border:1px solid #dee2e6;">
                                            </td>
                                            <td data-label="Name"><?php echo htmlspecialchars($category['name']); ?></td>
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
                                                <?php if($category['status'] == 1): ?>
                                                    <span class="badge bg-success">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Actions">
                                                <a href="<?php echo BASE_URL; ?>?controller=category&action=edit&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger delete-category" 
                                                        data-id="<?php echo $category['id']; ?>"
                                                        data-name="<?php echo htmlspecialchars($category['name']); ?>">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Show per page: 20 / 50 / 100 / All -->
                        <div class="mt-3 d-flex align-items-center gap-2 flex-wrap">
                            <label for="perPageFilter" class="form-label mb-0 small text-muted">Show:</label>
                            <select id="perPageFilter" class="form-select form-select-sm" style="width: auto;">
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
                            <span class="small text-muted">(<?php echo count($categories['data']); ?> shown)</span>
                        </div>
                        <script>
                        document.getElementById('perPageFilter').addEventListener('change', function() {
                            window.location.href = this.value;
                        });
                        </script>
                    <?php endif; ?>
                </div>
            </div>
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
