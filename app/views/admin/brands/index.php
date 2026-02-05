<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Admin brands – responsive */
.brands-table-scroll {
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,.08);
  box-shadow: inset 0 1px 3px rgba(0,0,0,.05);
}
.brands-table-scroll .table { margin-bottom: 0; border-radius: 12px; }
.brands-table-scroll thead th {
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
.brands-table-scroll tbody td { padding: 0.65rem 0.75rem; vertical-align: middle; }

@media (max-width: 575.98px) {
  .brands-table-scroll { max-height: 55vh; }
}
@media (min-width: 576px) and (max-width: 991.98px) {
  .brands-table-scroll { max-height: 60vh; }
}
@media (min-width: 992px) {
  .brands-table-scroll { max-height: 70vh; }
}

/* Tablet: hide Slug column */
@media (min-width: 576px) and (max-width: 991.98px) {
  #brandsTable th:nth-child(4),
  #brandsTable td:nth-child(4) { display: none !important; }
}

/* Mobile: card-style rows */
@media (max-width: 575.98px) {
  #brandsTable thead { display: none; }
  #brandsTable tbody tr {
    display: block;
    margin-bottom: 1rem;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg, #fff);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  #brandsTable tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid rgba(0,0,0,.06);
  }
  #brandsTable tbody td:last-child { border-bottom: 0; }
  #brandsTable tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--bs-secondary, #6c757d);
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  #brandsTable tbody td[data-label="Logo"] { display: block; padding: 0; }
  #brandsTable tbody td[data-label="Logo"]::before { content: none; }
  #brandsTable tbody td[data-label="Logo"] .brand-logo-container {
    width: 100% !important;
    max-height: 120px;
    margin: 0 auto;
  }
  #brandsTable tbody td[data-label="Actions"] { flex-wrap: wrap; gap: 0.25rem; }
  #brandsTable tbody td[data-label="Actions"] .btn-group { width: 100%; justify-content: flex-end; flex-wrap: wrap; }
}
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 mb-4">
        <h1 class="h3 mb-0">Manage Brands</h1>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Product
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus-circle me-1"></i> Add New Brand
            </a>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <div id="alert-messages">
        <?php flash('brand_success', '', 'alert alert-success alert-dismissible fade show'); ?>
        <?php flash('brand_error', '', 'alert alert-danger alert-dismissible fade show'); ?>
    </div>
    
    <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center g-2">
                <div class="col-12 col-md-6">
                    <h5 class="mb-0">All Brands</h5>
                </div>
                <div class="col-12 col-md-6">
                    <div class="d-flex flex-column flex-sm-row flex-md-wrap align-items-stretch align-items-sm-center gap-2 justify-content-md-end">
                        <form action="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" method="GET" class="d-flex flex-wrap align-items-center gap-2 flex-grow-1 flex-sm-grow-0">
                            <input type="hidden" name="controller" value="brand">
                            <input type="hidden" name="action" value="adminIndex">
                            <?php $currentPerPage = $brands['per_page_param'] ?? '20'; if ($currentPerPage !== '20'): ?>
                            <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($currentPerPage); ?>">
                            <?php endif; ?>
                            <input type="text" name="search" class="form-control form-control-sm flex-grow-1" style="min-width: 120px; max-width: 200px;" placeholder="Search brands..." value="<?php echo htmlspecialchars($search); ?>">
                            <button type="submit" class="btn btn-outline-primary btn-sm">Search</button>
                            <?php if(!empty($search)): ?>
                                <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex<?php echo $currentPerPage !== '20' ? '&per_page=' . urlencode($currentPerPage) : ''; ?>" class="btn btn-outline-secondary btn-sm">Clear</a>
                            <?php endif; ?>
                        </form>
                        <div class="d-flex align-items-center gap-1">
                            <label for="brandPerPageFilter" class="form-label mb-0 small text-muted">Show:</label>
                            <select id="brandPerPageFilter" class="form-select form-select-sm" style="width: auto; min-width: 4rem;">
                            <?php
                            $baseUrl = BASE_URL . '?controller=brand&action=adminIndex';
                            if (!empty($search)) $baseUrl .= '&search=' . urlencode($search);
                            foreach (['20', '50', '100', 'all'] as $opt):
                                $url = $baseUrl . (strpos($baseUrl, '?') !== false ? '&' : '?') . 'per_page=' . $opt;
                                $sel = (isset($currentPerPage) && $currentPerPage === $opt) ? ' selected' : '';
                            ?>
                                <option value="<?php echo htmlspecialchars($url); ?>"<?php echo $sel; ?>><?php echo $opt === 'all' ? 'All' : $opt; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <script>
        (function() {
            var el = document.getElementById('brandPerPageFilter');
            if (el) el.addEventListener('change', function() { window.location.href = this.value; });
        })();
        </script>
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
                <div class="brands-table-scroll table-responsive">
                    <table id="brandsTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th style="width: 100px;">Logo</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $page = isset($brands['current_page']) ? (int)$brands['current_page'] : 1;
                            $perPage = isset($brands['per_page']) ? (int)$brands['per_page'] : 20;
                            foreach($brands['data'] as $idx => $brand):
                                $rowNum = ($page - 1) * $perPage + $idx + 1;
                            ?>
                                <tr>
                                    <td data-label="#"><?php echo $rowNum; ?></td>
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
                                        <div class="btn-group btn-group-sm flex-wrap">
                                            <a href="<?php echo BASE_URL; ?>?controller=brand&action=edit&id=<?php echo $brand['id']; ?>" class="btn btn-outline-primary">
                                                <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Edit</span>
                                            </a>
                                            <button type="button" class="btn btn-outline-danger delete-brand" data-id="<?php echo $brand['id']; ?>" data-name="<?php echo htmlspecialchars($brand['name']); ?>">
                                                <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
