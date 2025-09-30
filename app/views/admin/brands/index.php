<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Manage Brands</h1>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Product
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Add New Brand
            </a>
        </div>
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
                    <table id="brandsTable" class="table table-hover align-middle">
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
                                    <td data-label="ID"><?php echo $brand['id']; ?></td>
                                    <td class="align-middle" data-label="Logo">
                                        <div class="brand-logo-container" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                            <?php 
                                            $logoUrl = !empty($brand['logo']) ? $brand['logo'] : '';
                                            $defaultLogo = BASE_URL . 'public/images/default-brand.png';
                                            ?>
                                            <img src="<?php echo htmlspecialchars($logoUrl); ?>" 
                                                 alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                                 class="img-fluid"
                                                 style="max-width: 100%; max-height: 100%; object-fit: contain;"
                                                 onerror="this.onerror=null; this.src='<?php echo $defaultLogo; ?>';">
                                        </div>
                                    </td>
                                    <td data-label="Name"><?php echo $brand['name']; ?></td>
                                    <td data-label="Slug"><?php echo $brand['slug']; ?></td>
                                    <td data-label="Status">
                                        <?php if($brand['status'] == 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Created"><?php echo date('M d, Y', strtotime($brand['created_at'])); ?></td>
                                    <td data-label="Actions">
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
<script>
// Delete brand functionality
document.addEventListener('DOMContentLoaded', function() {
    // Function to get CSRF token
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    // Event delegation for delete buttons
    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-brand');
        if (!deleteBtn) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        const brandId = deleteBtn.getAttribute('data-id');
        const brandName = deleteBtn.getAttribute('data-name');
        const row = deleteBtn.closest('tr');
        
        // Show confirmation dialog
        if (confirm(`Are you sure you want to delete the brand "${brandName}"? This action cannot be undone.`)) {
            deleteBrand(brandId, brandName, row);
        }
    });
    
    function deleteBrand(brandId, brandName, row) {
        if (!brandId) {
            console.error('Missing brand ID');
            showAlert('Error: Missing brand information', 'danger');
            return;
        }
        
        const deleteBtn = row?.querySelector('.delete-brand');
        const originalHtml = deleteBtn?.innerHTML || '';
        
        // Show loading state
        if (deleteBtn) {
            deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
            deleteBtn.disabled = true;
        }
        
        // Get CSRF token
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            console.error('CSRF token not found');
            resetButton(deleteBtn, originalHtml);
            showAlert('Security error: Please refresh the page and try again', 'danger');
            return;
        }
        
        // Make the request
        fetch(`?controller=brand&action=delete&id=${brandId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `_method=DELETE&csrf_token=${encodeURIComponent(csrfToken)}`
        })
        .then(async response => {
            const data = await response.json().catch(() => ({}));
            
            if (!response.ok) {
                const error = new Error(data.message || `HTTP error! status: ${response.status}`);
                error.response = response;
                error.data = data;
                throw error;
            }
            
            return data;
        })
        .then(data => {
            if (data.success) {
                // Fade out and remove row
                if (row) {
                    row.style.transition = 'opacity 0.3s';
                    row.style.opacity = '0';
                    
                    setTimeout(() => {
                        row.remove();
                        checkIfTableEmpty();
                    }, 300);
                }
                
                showAlert(data.message || 'Brand deleted successfully', 'success');
            } else {
                throw new Error(data.message || 'Failed to delete brand');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            const errorMessage = error.data?.message || error.message || 'An error occurred while deleting the brand';
            showAlert(errorMessage, 'danger');
            
            // If it's an authentication error, redirect to login
            if (error.response?.status === 401) {
                setTimeout(() => {
                    window.location.href = '?controller=user&action=login';
                }, 2000);
            }
        })
        .finally(() => {
            if (deleteBtn && originalHtml) {
                resetButton(deleteBtn, originalHtml);
            }
        });
    }
    
    function checkIfTableEmpty() {
        const tbody = document.querySelector('table tbody');
        if (tbody && tbody.children.length === 0) {
            const noResults = document.createElement('tr');
            noResults.innerHTML = `
                <td colspan="7" class="text-center py-4">
                    <div class="alert alert-info mb-0">No brands found.</div>
                </td>`;
            tbody.appendChild(noResults);
        }
    }
    
    function showAlert(message, type = 'success') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert-dismissible');
        existingAlerts.forEach(alert => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        });
        
        // Create alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            <i class="${type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Add to page
        const container = document.querySelector('.container-fluid > .row > .col-md-12');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
            
            // Auto-dismiss
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                    bsAlert.close();
                }
            }, 5000);
        } else {
            alert(message);
        }
    }
    
    function resetButton(button, originalHtml) {
        if (button) {
            button.innerHTML = originalHtml;
            button.disabled = false;
            
            // Re-enable any form elements that might be disabled
            const form = button.closest('form');
            if (form) {
                const formElements = form.elements;
                for (let i = 0; i < formElements.length; i++) {
                    formElements[i].disabled = false;
                }
            }
        }
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
