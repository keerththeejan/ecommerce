<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Categories</h3>
                    <div class="d-flex gap-2">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Back to Product
                        </a>
                        <a href="<?php echo BASE_URL; ?>?controller=category&action=create" class="btn btn-light">
                            <i class="fas fa-plus me-1"></i> Add New Category
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="alert-messages">
                        <?php flash('category_success'); ?>
                        <?php flash('category_error', '', 'alert alert-danger'); ?>
                    </div>
                    
                    <?php if(empty($categories['data'])): ?>
                        <div class="alert alert-info">No categories found.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table id="categoriesTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Parent</th>
                                        <th>Tax Rate</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="categories-table-body">
                                    <?php foreach($categories['data'] as $category): ?>
                                        <tr id="category-row-<?php echo $category['id']; ?>">
                                            <td data-label="ID"><?php echo $category['id']; ?></td>
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
                        
                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php echo getPaginationLinks($categories['current_page'], $categories['total_pages'], BASE_URL . '?controller=category&action=adminIndex'); ?>
                        </div>
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
