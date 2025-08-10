<?php 
require_once APP_PATH . 'views/admin/layouts/header.php'; 
?>
<style>
    /* Ensure consistent sidebar behavior */
    #supplierSidebar {
        transition: all 0.3s ease;
    }
    @media (max-width: 767.98px) {
        #supplierSidebar {
            position: fixed;
            top: 0;
            right: -100%;
            height: 100%;
            z-index: 1040;
            overflow-y: auto;
        }
        #supplierSidebar.show {
            right: 0;
        }
        .sidebar-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1039;
            display: none;
        }
    }
</style>
<div class="sidebar-backdrop d-md-none" id="sidebarBackdrop"></div>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Suppliers</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                        <i class="fas fa-plus me-1"></i> Add New Supplier
                    </a>
                </div>
            </div>

            <!-- Suppliers Table -->
            <div class="card">
                <div class="card-body">
                    <?php flash('supplier_success'); ?>
                    <?php flash('supplier_error'); ?>
                    
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Supplier Name</th>
                                    <th>Product Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($data['suppliers'])): ?>
                                    <?php foreach ($data['suppliers'] as $supplier): ?>
                                        <tr style="cursor: pointer;" onclick="loadSupplierDetails('<?php echo BASE_URL; ?>?controller=supplier&action=details&id=<?php echo $supplier['id']; ?>')">
                                            <td><?php echo $supplier['id']; ?></td>
                                            <td><?php echo htmlspecialchars($supplier['name']); ?></td>
                                            <td><?php echo !empty($supplier['product_name']) ? htmlspecialchars($supplier['product_name']) : '-'; ?></td>
                                            <td><?php echo $supplier['email'] ? htmlspecialchars($supplier['email']) : '-'; ?></td>
                                            <td><?php echo $supplier['phone'] ? htmlspecialchars($supplier['phone']) : '-'; ?></td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Supplier Actions">
                                                    <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSupplierModal" data-id="<?php echo $supplier['id']; ?>">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this supplier?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No suppliers found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" id="supplierSidebar">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <h5>Suppliers Details</h5>
                </div>
                <div id="supplierDetails">
                    <div class="text-center text-muted">
                        <i class="fas fa-truck fa-4x mb-3"></i>
                        <p>Select a supplier to view details</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSupplierForm" method="POST" action="<?php echo BASE_URL; ?>?controller=supplier&action=create">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php echo !empty($data['name_err']) ? 'is-invalid' : ''; ?>" 
                            id="name" name="name" required 
                            value="<?php echo isset($data['name']) ? htmlspecialchars($data['name']) : ''; ?>">
                        <div class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" 
                            id="product_name" name="product_name"
                            value="<?php echo isset($data['product_name']) ? htmlspecialchars($data['product_name']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?php echo !empty($data['email_err']) ? 'is-invalid' : ''; ?>" 
                            id="email" name="email"
                            value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>">
                        <div class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?php echo isset($data['phone']) ? htmlspecialchars($data['phone']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php 
                            echo isset($data['address']) ? htmlspecialchars($data['address']) : ''; 
                        ?></textarea>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-outline-secondary me-md-2" id="resetSupplierForm">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary" id="saveSupplierBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="fas fa-save me-1"></i> Save Supplier
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <!-- Success Toast -->
    <div class="toast align-items-center text-white bg-success border-0" id="successToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    
    <!-- Error Toast -->
    <div class="toast align-items-center text-white bg-danger border-0" id="errorToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="errorToastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
// Base URL for building links in JS
const BASE_URL = '<?php echo BASE_URL; ?>';
// Helper function to escape HTML
function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe
        .toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

// Function to show validation errors
function showFormErrors(errors) {
    // Reset all error states
    document.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
    });
    
    // Show new errors
    if (errors) {
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            const feedback = input ? input.nextElementSibling : null;
            
            if (input && feedback && feedback.classList.contains('invalid-feedback')) {
                input.classList.add('is-invalid');
                feedback.textContent = errors[field];
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Close sidebar when clicking on backdrop
    const backdrop = document.getElementById('sidebarBackdrop');
    if (backdrop) {
        backdrop.addEventListener('click', function() {
            const sidebar = document.getElementById('supplierSidebar');
            if (sidebar) {
                sidebar.classList.remove('show');
                this.style.display = 'none';
            }
        });
    }
    
    // Handle form reset
    const resetSupplierForm = document.getElementById('resetSupplierForm');
    if (resetSupplierForm) {
        resetSupplierForm.addEventListener('click', function() {
            const form = document.getElementById('addSupplierForm');
            if (form) {
                form.reset();
                // Clear validation errors
                showFormErrors({});
            }
        });
    }

    // Handle form submission
    const addSupplierForm = document.getElementById('addSupplierForm');
    if (addSupplierForm) {
        addSupplierForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const saveBtn = document.getElementById('saveSupplierBtn');
            const spinner = saveBtn.querySelector('.spinner-border');
            
            // Show loading state
            saveBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            // Submit form via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                } else {
                    throw new Error(data.message || 'Failed to load supplier details');
                }
            })
            .catch(err => {
                const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                document.getElementById('errorToastMessage').textContent = (err && err.message) ? err.message : 'Failed to load supplier details';
                errorToast.show();
            })
            .then(async (response) => {
                const text = await response.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    throw new Error(text && text.trim().length ? text : 'Invalid server response');
                }
                return { ok: response.ok, status: response.status, data };
            })
            .then(({ ok, status, data }) => {
                if (data.success) {
                    // Show success message
                    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                    document.getElementById('toastMessage').textContent = data.message;
                    successToast.show();
                    
                    // Close modal
                    const addSupplierModal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                    if (addSupplierModal) {
                        addSupplierModal.hide();
                    }
                    
                    // Create new row for the table
                    const tbody = document.querySelector('table tbody');
                    const newRow = document.createElement('tr');
                    newRow.style.cursor = 'pointer';
                    newRow.onclick = function() {
                        loadSupplierDetails(`${BASE_URL}?controller=supplier&action=details&id=${data.supplier.id}`);
                    };
                    
                    newRow.innerHTML = `
                        <td>${data.supplier.id}</td>
                        <td>${escapeHtml(data.supplier.name)}</td>
                        <td>${data.supplier.product_name ? escapeHtml(data.supplier.product_name) : '-'}</td>
                        <td>${data.supplier.email || '-'}</td>
                        <td>${data.supplier.phone || '-'}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Supplier Actions">
                                <a href="#" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSupplierModal" data-id="${data.supplier.id}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this supplier?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    `;
                    
                    // Remove placeholder row if present
                    const placeholder = tbody.querySelector('td[colspan]');
                    if (placeholder) placeholder.closest('tr').remove();
                    tbody.insertBefore(newRow, tbody.firstChild);
                    
                    // Reset the form
                    this.reset();
                    
                } else if (data.errors) {
                    // Show validation errors
                    showFormErrors(data.errors);
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                document.getElementById('errorToastMessage').textContent = error.message || 'Failed to save supplier. Please try again.';
                errorToast.show();
            })
            .finally(() => {
                // Reset loading state
                saveBtn.disabled = false;
                spinner.classList.add('d-none');
            });
        });
    }
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle supplier row click to load details in sidebar
    const supplierRows = document.querySelectorAll('tbody tr');
    supplierRows.forEach(row => {
        row.addEventListener('click', function(e) {
            // Don't trigger if clicking on action buttons
            if (e.target.closest('a, button, input, select')) {
                return;
            }
            
            const link = this.querySelector('a[href*="details"]');
            if (link) {
                e.preventDefault();
                loadSupplierDetails(link.getAttribute('href'));
            }
        });
    });
    
    // Function to load supplier details in sidebar
    function loadSupplierDetails(url) {
        // Show loading state
        const sidebar = document.getElementById('supplierSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        
        // Show sidebar and backdrop on mobile
        if (window.innerWidth < 768) {
            sidebar.classList.add('show');
            backdrop.style.display = 'block';
        }
        fetch(url + '&ajax=1')
            .then(async (response) => {
                const text = await response.text();
                let data;
                try { data = JSON.parse(text); } catch (e) {
                    throw new Error(text && text.trim().length ? text : 'Invalid server response');
                }
                return { ok: response.ok, status: response.status, data };
            })
            .then(({ ok, status, data }) => {
                if (data.success) {
                    const supplierDetails = `
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">${data.supplier.name}</h5>
                            </div>
                            <div class="card-body">
                                ${data.supplier.product_name ? `<p class="mb-1"><strong>Product:</strong> ${data.supplier.product_name}</p>` : ''}
                                <p class="mb-1"><strong>Email:</strong> ${data.supplier.email || 'N/A'}</p>
                                <p class="mb-1"><strong>Phone:</strong> ${data.supplier.phone || 'N/A'}</p>
                                <p class="mb-0"><strong>Address:</strong> ${data.supplier.address || 'N/A'}</p>
                            </div>
                        </div>`;
                    
                    document.getElementById('supplierDetails').innerHTML = supplierDetails;
                    
                    // Show the sidebar on mobile
                    if (window.innerWidth < 768) {
                        const sidebar = document.getElementById('supplierSidebar');
                        const backdrop = document.getElementById('sidebarBackdrop');
                        sidebar.classList.add('show');
                        backdrop.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                console.error('Error loading supplier details:', error);
            });
    }
    
    // Handle edit button click
    document.querySelectorAll('.edit-supplier').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const supplierId = this.getAttribute('data-id');
            // Implement edit functionality here
            alert('Edit supplier ' + supplierId);
        });
    });
    
    // Handle delete button click
    document.querySelectorAll('.delete-supplier').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this supplier?')) {
                const supplierId = this.getAttribute('data-id');
                // Implement delete functionality here
                alert('Delete supplier ' + supplierId);
            }
        });
    });
    
    // Handle form submission
    document.getElementById('addSupplierForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        // Implement form submission here
        alert('Add new supplier');
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
