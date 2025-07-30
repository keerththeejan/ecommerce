<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Brands</h1>
        <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Add New Brand
        </a>
    </div>
    
    <!-- Alert Messages -->
    <div id="alert-messages">
        <?php flash('brand_success', '', 'alert alert-success alert-dismissible fade show'); ?>
        <?php flash('brand_error', '', 'alert alert-danger alert-dismissible fade show'); ?>
    </div>
    
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">All Brands</h5>
                </div>
                <div class="col-md-6">
                    <form action="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" method="GET" class="d-flex">
                        <input type="hidden" name="controller" value="brand">
                        <input type="hidden" name="action" value="adminIndex">
                        <input type="text" name="search" class="form-control" placeholder="Search brands..." value="<?php echo $search; ?>">
                        <button type="submit" class="btn btn-outline-primary ms-2">Search</button>
                        <?php if(!empty($search)): ?>
                            <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" class="btn btn-outline-secondary ms-2">Clear</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if(empty($brands['data'])): ?>
                <div class="alert alert-info">
                    <?php if(!empty($search)): ?>
                        No brands found matching "<?php echo $search; ?>".
                    <?php else: ?>
                        No brands found. Create your first brand!
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th style="width: 100px;">Logo</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($brands['data'] as $brand): ?>
                                <tr>
                                    <td><?php echo $brand['id']; ?></td>
                                    <td>
                                        <?php 
                                        $imagePath = '';
                                        $brandLogo = $brand['logo'] ?? '';
                                        
                                        if (!empty($brandLogo)) {
                                            // Check different possible paths
                                            $possiblePaths = [
                                                'public/uploads/brands/' . $brandLogo,
                                                'uploads/brands/' . $brandLogo,
                                                $brandLogo,
                                                'public/' . $brandLogo,
                                                'uploads/' . $brandLogo
                                            ];
                                            
                                            foreach ($possiblePaths as $path) {
                                                $fullPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT'] . '/ecommerce/' . ltrim($path, '/\\'));
                                                if (file_exists($fullPath)) {
                                                    $imagePath = BASE_URL . ltrim($path, '/\\');
                                                    break;
                                                }
                                            }
                                            
                                            // If still not found, try to find the file by name in the uploads directory
                                            if (empty($imagePath)) {
                                                $uploadsDir = $_SERVER['DOCUMENT_ROOT'] . '/ecommerce/public/uploads/brands/';
                                                $files = glob($uploadsDir . '*' . $brandLogo . '*');
                                                if (!empty($files[0])) {
                                                    $imagePath = str_replace(
                                                        $_SERVER['DOCUMENT_ROOT'] . '/ecommerce',
                                                        '',
                                                        $files[0]
                                                    );
                                                    $imagePath = BASE_URL . ltrim($imagePath, '/');
                                                }
                                            }
                                        }
                                        
                                        if (!empty($imagePath)): 
                                        ?>
                                            <div style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                                                <img src="<?php echo $imagePath; ?>" 
                                                     alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                                     style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                                     onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/img/no-image.jpg';">
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                <i class="fas fa-building text-muted fa-2x"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $brand['name']; ?></td>
                                    <td><?php echo $brand['slug']; ?></td>
                                    <td>
                                        <?php if($brand['status'] == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('M d, Y', strtotime($brand['created_at'])); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo BASE_URL; ?>?controller=brand&action=edit&id=<?php echo $brand['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger delete-brand" data-id="<?php echo $brand['id']; ?>" data-name="<?php echo htmlspecialchars($brand['name']); ?>">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if($brands['last_page'] > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if($brands['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex&page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">First</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex&page=<?php echo $brands['current_page'] - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Previous</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php
                            $startPage = max(1, $brands['current_page'] - 2);
                            $endPage = min($brands['last_page'], $brands['current_page'] + 2);
                            
                            for($i = $startPage; $i <= $endPage; $i++):
                            ?>
                                <li class="page-item <?php echo $i == $brands['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex&page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if($brands['current_page'] < $brands['last_page']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex&page=<?php echo $brands['current_page'] + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Next</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex&page=<?php echo $brands['last_page']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Last</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBrandModal" tabindex="-1" aria-labelledby="deleteBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteBrandModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the brand "<strong id="brandNameToDelete"></strong>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBrand">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let brandIdToDelete = null;
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteBrandModal'));
    
    // Handle delete button click
    document.querySelectorAll('.delete-brand').forEach(button => {
        button.addEventListener('click', function() {
            brandIdToDelete = this.getAttribute('data-id');
            const brandName = this.getAttribute('data-name');
            document.getElementById('brandNameToDelete').textContent = brandName;
            deleteModal.show();
        });
    });
    
    // Handle confirm delete
    document.getElementById('confirmDeleteBrand').addEventListener('click', function() {
        if (!brandIdToDelete) return;
        
        const button = this;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
        
        // Get the row to be removed
        const rowToRemove = document.querySelector(`button.delete-brand[data-id="${brandIdToDelete}"]`).closest('tr');
        
        // Send AJAX request to delete the brand
        fetch(`?controller=brand&action=delete&id=${brandIdToDelete}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            credentials: 'same-origin',
            body: `_method=DELETE`
        })
        .then(async response => {
            const data = await response.json().catch(() => ({}));
            
            if (!response.ok) {
                const error = new Error(data.message || 'Network response was not ok');
                error.response = data;
                error.status = response.status;
                throw error;
            }
            
            return data;
        })
        .then(data => {
            // Show success message
            const successMessage = data.message || 'Brand deleted successfully';
            
            // Create success alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                <i class="fas fa-check-circle me-2"></i> ${successMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            const alertContainer = document.getElementById('alert-messages');
            if (alertContainer) {
                // Clear any existing alerts
                alertContainer.innerHTML = '';
                // Add the new alert
                alertContainer.appendChild(alertDiv);
                
                // Scroll to the top to show the alert
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Auto-dismiss the alert after 5 seconds
                const alertTimeout = setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alertDiv);
                    bsAlert.close();
                }, 5000);
                
                // Clean up the timeout if the alert is closed manually
                alertDiv.addEventListener('closed.bs.alert', () => {
                    clearTimeout(alertTimeout);
                });
            }
            
            // If we have a row to remove, do it
            if (rowToRemove) {
                rowToRemove.style.transition = 'opacity 0.3s';
                rowToRemove.style.opacity = '0';
                
                // Remove the row after the transition
                setTimeout(() => {
                    rowToRemove.remove();
                    
                    // Check if there are no more rows in the table
                    const tbody = document.querySelector('table tbody');
                    if (tbody && tbody.children.length === 0) {
                        // Create a new row with a message
                        const noResults = document.createElement('tr');
                        noResults.innerHTML = `
                            <td colspan="7" class="text-center py-4">
                                <div class="alert alert-info mb-0">No brands found.</div>
                            </td>`;
                        tbody.appendChild(noResults);
                    }
                }, 300);
            }
            
            // Close the modal
            deleteModal.hide();
        })
        .catch(error => {
            console.error('Error:', error);
            let errorMessage = 'An error occurred while deleting the brand';
            
            // Handle different types of errors
            if (error.response && error.response.message) {
                errorMessage = error.response.message;
            } else if (error.message) {
                errorMessage = error.message;
            } else if (error.statusText) {
                errorMessage = error.statusText;
            }
            
            // Check for specific error status codes
            if (error.status === 403) {
                errorMessage = 'You do not have permission to delete this brand.';
            } else if (error.status === 404) {
                errorMessage = 'The brand you are trying to delete was not found.';
            } else if (error.status === 409) {
                errorMessage = 'Cannot delete brand because it is associated with one or more products.';
            } else if (error.status === 500) {
                errorMessage = 'A server error occurred. Please try again later.';
            }
            
            // Show error alert
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                <i class="fas fa-exclamation-circle me-2"></i> ${errorMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            const alertContainer = document.getElementById('alert-messages');
            if (alertContainer) {
                // Clear any existing alerts
                alertContainer.innerHTML = '';
                // Add the new alert
                alertContainer.appendChild(alertDiv);
                
                // Scroll to the top to show the alert
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Auto-dismiss the alert after 10 seconds
                const alertTimeout = setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alertDiv);
                    bsAlert.close();
                }, 10000);
                
                // Clean up the timeout if the alert is closed manually
                alertDiv.addEventListener('closed.bs.alert', () => {
                    clearTimeout(alertTimeout);
                });
            } else {
                // Fallback to simple alert if container not found
                alert(errorMessage);
            }
            
            // Re-enable the button
            button.disabled = false;
            button.innerHTML = originalText;
            
            // Close the modal
            deleteModal.hide();
            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteBrandModal'));
            if (deleteModal) {
                deleteModal.hide();
            }
        })
        .finally(() => {
            brandIdToDelete = null;
        });
    });
    
    // Function to show alert messages
    function showAlert(message, type = 'success') {
        const alertMessages = document.getElementById('alert-messages');
        
        if (!alertMessages) return;
        
        // Remove any existing alerts
        const existingAlerts = alertMessages.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            <i class="${type === 'success' ? 'fas fa-check-circle' : type === 'danger' ? 'fas fa-exclamation-circle' : 'fas fa-info-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Add to container
        alertMessages.insertBefore(alertDiv, alertMessages.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }, 5000);
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
