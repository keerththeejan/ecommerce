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
                                            <td class="text-nowrap">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="Supplier Actions" onclick="event.stopPropagation();">
                                                    <button type="button" 
                                                            class="btn btn-outline-primary edit-supplier" 
                                                            data-id="<?php echo $supplier['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($supplier['name']); ?>"
                                                            data-product_name="<?php echo htmlspecialchars($supplier['product_name'] ?? ''); ?>"
                                                            data-email="<?php echo htmlspecialchars($supplier['email'] ?? ''); ?>"
                                                            data-phone="<?php echo htmlspecialchars($supplier['phone'] ?? ''); ?>"
                                                            data-address="<?php echo htmlspecialchars($supplier['address'] ?? ''); ?>"
                                                            onclick="handleEditClick(event); return false;">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger delete-supplier" 
                                                            data-id="<?php echo $supplier['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($supplier['name']); ?>"
                                                            onclick="handleDeleteClick(event); return false;">
                                                        <i class="fas fa-trash"></i> <span class="button-text">Delete</span>
                                                    </button>
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

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSupplierForm" method="POST" action="">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_product_name" name="product_name">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address</label>
                        <textarea class="form-control" id="edit_address" name="address" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="updateSupplierBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteSupplierModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteSupplierName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="button-text">Delete</span>
                </button>
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

// Function to show alert messages
function showAlert(message, type = 'success') {
    // Remove any existing alerts
    const existingAlert = document.querySelector('.alert-dismissible');
    if (existingAlert) {
        existingAlert.remove();
    }

    // Create alert element
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    // Add to DOM
    document.body.appendChild(alertDiv);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
        alert.close();
    }, 5000);
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

// Handle Edit Button Click
document.addEventListener('click', function(e) {
    const editBtn = e.target.closest('.edit-supplier');
    if (editBtn) {
        e.preventDefault();
        e.stopPropagation();
        
        // Get the modal element
        const modalElement = document.getElementById('editSupplierModal');
        if (!modalElement) {
            console.error('Edit modal not found');
            return;
        }
        
        const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
        
        // Populate form fields
        const form = modalElement.querySelector('form');
        if (form) {
            form.reset();
            form.action = `${BASE_URL}?controller=supplier&action=update`;
            document.getElementById('edit_id').value = editBtn.getAttribute('data-id');
            document.getElementById('edit_name').value = editBtn.getAttribute('data-name') || '';
            document.getElementById('edit_product_name').value = editBtn.getAttribute('data-product_name') || '';
            document.getElementById('edit_email').value = editBtn.getAttribute('data-email') || '';
            document.getElementById('edit_phone').value = editBtn.getAttribute('data-phone') || '';
            document.getElementById('edit_address').value = editBtn.getAttribute('data-address') || '';
            
            // Clear any previous errors
            const formInputs = form.querySelectorAll('.is-invalid');
            formInputs.forEach(input => input.classList.remove('is-invalid'));
            
            // Show the modal
            modal.show();
        } else {
            console.error('Edit form not found in modal');
        }
    }
});

// Handle Delete Button Click
document.addEventListener('click', function(e) {
    const deleteBtn = e.target.closest('.delete-supplier');
    if (deleteBtn) {
        e.preventDefault();
        e.stopPropagation();
        
        const supplierId = deleteBtn.getAttribute('data-id');
        const supplierName = deleteBtn.getAttribute('data-name');
        
        // Set the supplier name in the confirmation modal
        document.getElementById('deleteSupplierName').textContent = supplierName;
        
        // Store the ID in the confirm button
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        confirmBtn.setAttribute('data-id', supplierId);
        
        // Show the modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteSupplierModal'));
        deleteModal.show();
    }
});

// Handle Edit Form Submission
document.addEventListener('submit', function(e) {
    if (e.target && e.target.id === 'editSupplierForm') {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const updateBtn = form.querySelector('button[type="submit"]');
        const spinner = updateBtn.querySelector('.spinner-border');
        
        // Show loading state
        updateBtn.disabled = true;
        if (spinner) spinner.classList.remove('d-none');
        
        // Submit form via AJAX
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(async (response) => {
            const text = await response.text();
            try {
                return { ok: response.ok, data: JSON.parse(text) };
            } catch (e) {
                console.error('Failed to parse response:', text);
                throw new Error('Invalid server response');
            }
        })
        .then(({ ok, data }) => {
            if (ok && data.success) {
                // Close the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
                if (modal) modal.hide();
                
                // Show success message and reload
                showAlert(data.message || 'Supplier updated successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else if (data.errors) {
                // Show validation errors
                showFormErrors(data.errors);
            } else {
                throw new Error(data.message || 'Failed to update supplier');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert(error.message || 'Failed to update supplier. Please try again.', 'danger');
        })
        .finally(() => {
            if (updateBtn) {
                updateBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            }
        });
    }
});

// Handle Delete Confirmation
// Initialize delete confirmation handler
document.addEventListener('click', function(e) {
    const confirmBtn = e.target.closest('#confirmDeleteBtn');
    if (!confirmBtn) return;
    
    const supplierId = confirmBtn.getAttribute('data-id');
    if (!supplierId) {
        console.error('No supplier ID found for deletion');
        return;
    }
    
    const spinner = confirmBtn.querySelector('.spinner-border');
    
    // Show loading state
    confirmBtn.disabled = true;
    if (spinner) spinner.classList.remove('d-none');
    
    // Send delete request
    fetch(`${BASE_URL}?controller=supplier&action=delete`, {
        method: 'POST',
        body: new URLSearchParams({ id: supplierId }),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async (response) => {
        const text = await response.text();
        try {
            return { ok: response.ok, data: JSON.parse(text) };
        } catch (e) {
            console.error('Failed to parse response:', text);
            throw new Error('Invalid server response');
        }
    })
    .then(({ ok, data }) => {
        if (ok && data && data.success) {
            // Close the modal
            const modalElement = document.getElementById('deleteSupplierModal');
            if (modalElement) {
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) modal.hide();
            }
            
            // Show success message and reload
            showAlert(data.message || 'Supplier deleted successfully');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(data?.message || 'Failed to delete supplier');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(error.message || 'An error occurred while deleting the supplier', 'danger');
    })
    .finally(() => {
        confirmBtn.disabled = false;
        if (spinner) spinner.classList.add('d-none');
    });
});



// Handle edit form submission
function handleEditFormSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const formData = new FormData(form);
    const updateBtn = form.querySelector('button[type="submit"]');
    const spinner = updateBtn.querySelector('.spinner-border');
    
    // Show loading state
    updateBtn.disabled = true;
    spinner.classList.remove('d-none');
    
    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async (response) => {
        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error('Failed to parse response:', text);
            throw new Error('Invalid server response');
        }
        return { ok: response.ok, data };
    })
    .then(({ ok, data }) => {
        if (ok && data.success) {
            // Close modal first to prevent issues
            const modalElement = document.getElementById('editSupplierModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
            
            // Show success message
            showAlert(data.message || 'Supplier updated successfully');
            
            // Reload the page to reflect changes
            setTimeout(() => window.location.reload(), 1000);
        } else if (data && data.errors) {
            // Show validation errors
            showFormErrors(data.errors);
        } else {
            throw new Error(data && data.message ? data.message : 'Failed to update supplier');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(error.message || 'Failed to update supplier. Please try again.', 'danger');
    })
    .finally(() => {
        if (updateBtn) {
            updateBtn.disabled = false;
            if (spinner) spinner.classList.add('d-none');
        }
    });
}

// Handle delete supplier button click
function handleDeleteSupplier(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const deleteBtn = e.target.closest('.delete-supplier');
    if (!deleteBtn) return;
    
    const supplierId = deleteBtn.getAttribute('data-id');
    const supplierName = deleteBtn.getAttribute('data-name');
    
    // Set supplier name in confirmation modal
    document.getElementById('deleteSupplierName').textContent = supplierName;
    
    // Store the ID in the confirm button
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    confirmBtn.setAttribute('data-id', supplierId);
    
    // Show the modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteSupplierModal'));
    deleteModal.show();
    
    // Stop the row click event from triggering
    return false;
}

// Handle delete confirmation
function handleDeleteConfirm(e) {
    e.preventDefault();
    
    const supplierId = this.getAttribute('data-id');
    const spinner = this.querySelector('.spinner-border');
    
    // Show loading state
    this.disabled = true;
    if (spinner) spinner.classList.remove('d-none');
    
    // Create form data
    const formData = new FormData();
    formData.append('id', supplierId);
    
    // Send delete request
    fetch(BASE_URL + '?controller=supplier&action=delete', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async (response) => {
        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error('Failed to parse response:', text);
            throw new Error('Invalid server response');
        }
        return { ok: response.ok, data };
    })
    .then(({ ok, data }) => {
        if (ok && data && data.success) {
            // Close the modal first to prevent issues
            const modalElement = document.getElementById('deleteSupplierModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
            
            // Show success message
            showAlert(data.message || 'Supplier deleted successfully');
            
            // Reload the page to reflect changes
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(data && data.message ? data.message : 'Failed to delete supplier');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(error.message || 'An error occurred while deleting the supplier', 'danger');
    })
    .finally(() => {
        this.disabled = false;
        if (spinner) spinner.classList.add('d-none');
    });
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
    
        // Add event delegation for edit and delete buttons
    document.addEventListener('click', function(e) {
        // Handle edit button
        let editBtn = e.target.closest('.edit-supplier');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            handleEditSupplier(e);
            return false;
        }
        
        // Handle delete button
        let deleteBtn = e.target.closest('.delete-supplier');
        if (deleteBtn) {
            e.preventDefault();
            e.stopPropagation();
            handleDeleteSupplier(e);
            return false;
        }
    });
    
    // Add event listener for delete confirmation
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            handleDeleteConfirm.call(this, e);
        });
    }
    
    // Initialize edit form submission
    const editForm = document.getElementById('editSupplierForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            handleEditFormSubmit(e);
        });
    }

    // Handle Add Supplier form submission (AJAX)
    const addSupplierForm = document.getElementById('addSupplierForm');
    if (addSupplierForm) {
        addSupplierForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const saveBtn = document.getElementById('saveSupplierBtn');
            const spinner = saveBtn.querySelector('.spinner-border');
            
            // Loading state
            saveBtn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(async (response) => {
                const text = await response.text();
                let data;
                try { data = JSON.parse(text); } catch (_) { throw new Error(text && text.trim() ? text : 'Invalid server response'); }
                return { ok: response.ok, data };
            })
            .then(({ ok, data }) => {
                if (ok && data.success) {
                    // Toast
                    const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                    document.getElementById('toastMessage').textContent = data.message || 'Supplier added successfully';
                    successToast.show();
                    
                    // Close modal
                    const addModal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                    if (addModal) addModal.hide();
                    
                    // Add row to table
                    const s = data.supplier;
                    const tbody = document.querySelector('table tbody');
                    if (tbody && s) {
                        const tr = document.createElement('tr');
                        tr.style.cursor = 'pointer';
                        tr.setAttribute('onclick', `loadSupplierDetails('${BASE_URL}?controller=supplier&action=details&id=${s.id}')`);
                        tr.innerHTML = `
                            <td>${s.id}</td>
                            <td>${escapeHtml(s.name)}</td>
                            <td>${s.product_name ? escapeHtml(s.product_name) : '-'}</td>
                            <td>${s.email ? escapeHtml(s.email) : '-'}</td>
                            <td>${s.phone ? escapeHtml(s.phone) : '-'}</td>
                            <td class="text-nowrap">
                                <div class="btn-group btn-group-sm" role="group" aria-label="Supplier Actions">
                                    <button type="button" class="btn btn-outline-primary edit-supplier"
                                            data-id="${s.id}"
                                            data-name="${escapeHtml(s.name)}"
                                            data-product_name="${escapeHtml(s.product_name || '')}"
                                            data-email="${escapeHtml(s.email || '')}"
                                            data-phone="${escapeHtml(s.phone || '')}"
                                            data-address="${escapeHtml(s.address || '')}"
                                            onclick="event.stopPropagation();">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-outline-danger delete-supplier"
                                            data-id="${s.id}"
                                            data-name="${escapeHtml(s.name)}"
                                            onclick="event.stopPropagation();">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </div>
                            </td>`;
                        const placeholder = tbody.querySelector('td[colspan]');
                        if (placeholder) placeholder.closest('tr').remove();
                        tbody.prepend(tr);
                    }
                    
                    // Reset form
                    form.reset();
                } else if (data.errors) {
                    showFormErrors(data.errors);
                } else {
                    throw new Error(data.message || 'Failed to add supplier');
                }
            })
            .catch(err => {
                const errorToast = new bootstrap.Toast(document.getElementById('errorToast'));
                document.getElementById('errorToastMessage').textContent = err.message || 'Failed to save supplier.';
                errorToast.show();
            })
            .finally(() => {
                saveBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            });
        });
    }
        // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Remove duplicate, unused handlers defined below (cleaned up)
    // Add new event listeners
    document.addEventListener('click', handleEditClick);
    document.addEventListener('click', handleDeleteClick);
    
    // Initialize delete confirmation handler
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.onclick = function() {
            const supplierId = this.getAttribute('data-id');
            if (!supplierId) {
                console.error('No supplier ID found for deletion');
                return;
            }
            
            console.log(`Confirming delete for supplier ID: ${supplierId}`);
            
            const spinner = this.querySelector('.spinner-border');
            const originalText = this.innerHTML;
            
            // Show loading state
            this.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            
            // Send delete request
            fetch(`${BASE_URL}?controller=supplier&action=delete`, {
                method: 'POST',
                body: new URLSearchParams({ id: supplierId }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Supplier deleted successfully');
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteSupplierModal'));
                    if (modal) modal.hide();
                    
                    // Show success message and reload
                    showAlert('Supplier deleted successfully');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to delete supplier');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(error.message || 'Failed to delete supplier. Please try again.', 'danger');
            })
            .finally(() => {
                this.disabled = false;
                if (spinner) spinner.classList.add('d-none');
                this.innerHTML = originalText;
            });
        };
    }
    
    // Initialize edit form submission
    const editForm = document.getElementById('editSupplierForm');
    if (editForm) {
        editForm.onsubmit = function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            
            // Submit the form
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Supplier updated successfully');
                    // Close the modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
                    if (modal) modal.hide();
                    
                    // Show success message and reload
                    showAlert('Supplier updated successfully');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Failed to update supplier');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(error.message || 'Failed to update supplier. Please try again.', 'danger');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        };
    }
                
                const modal = new bootstrap.Modal(modalElement);
                
                // Populate form fields
                const form = modalElement.querySelector('form');
                if (form) {
                    form.reset();
                    form.action = `${BASE_URL}?controller=supplier&action=update`;
                    document.getElementById('edit_id').value = editBtn.getAttribute('data-id');
                    document.getElementById('edit_name').value = editBtn.getAttribute('data-name') || '';
                    document.getElementById('edit_product_name').value = editBtn.getAttribute('data-product_name') || '';
                    document.getElementById('edit_email').value = editBtn.getAttribute('data-email') || '';
                    document.getElementById('edit_phone').value = editBtn.getAttribute('data-phone') || '';
                    document.getElementById('edit_address').value = editBtn.getAttribute('data-address') || '';
                    
                    // Clear any previous errors
                    const formInputs = form.querySelectorAll('.is-invalid');
                    formInputs.forEach(input => input.classList.remove('is-invalid'));
                    
                    // Show the modal
                    modal.show();
                } else {
                    console.error('Edit form not found in modal');
                }
                return false;
            }
            
            // Delete button click
            const deleteBtn = e.target.closest('.delete-supplier');
            if (deleteBtn) {
                console.log('Delete button clicked');
                e.preventDefault();
                e.stopPropagation();
                
                const supplierId = deleteBtn.getAttribute('data-id');
                const supplierName = deleteBtn.getAttribute('data-name');
                
                if (!supplierId) {
                    console.error('No supplier ID found for deletion');
                    return;
                }
                
                // Update the confirmation modal
                const deleteSupplierName = document.getElementById('deleteSupplierName');
                if (deleteSupplierName) {
                    deleteSupplierName.textContent = supplierName;
                }
                
                // Store the ID in the confirm button
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                if (confirmBtn) {
                    confirmBtn.setAttribute('data-id', supplierId);
                }
                
                // Show the modal
                const modalElement = document.getElementById('deleteSupplierModal');
                if (modalElement) {
                    const deleteModal = new bootstrap.Modal(modalElement);
                    deleteModal.show();
                } else {
                    console.error('Delete confirmation modal not found');
                }
                
                return false;
            }
        });
        
        // Handle edit form submission
        const editForm = document.getElementById('editSupplierForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn.innerHTML;
                
                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
                
                // Submit the form
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(async (response) => {
                    const text = await response.text();
                    try {
                        return { ok: response.ok, data: JSON.parse(text) };
                    } catch (e) {
                        console.error('Failed to parse response:', text);
                        throw new Error('Invalid server response');
                    }
                })
                .then(({ ok, data }) => {
                    if (ok && data.success) {
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
                        if (modal) modal.hide();
                        
                        // Show success message and reload
                        showAlert(data.message || 'Supplier updated successfully');
                        setTimeout(() => window.location.reload(), 1000);
                    } else if (data && data.errors) {
                        // Show validation errors
                        showFormErrors(data.errors);
                    } else {
                        throw new Error(data?.message || 'Failed to update supplier');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert(error.message || 'Failed to update supplier. Please try again.', 'danger');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                });
            });
        }
        
        // Handle delete confirmation
        document.addEventListener('click', function(e) {
            const confirmBtn = e.target.closest('#confirmDeleteBtn');
            if (!confirmBtn) return;
            
            e.preventDefault();
            e.stopPropagation();
            
            const supplierId = confirmBtn.getAttribute('data-id');
            if (!supplierId) {
                console.error('No supplier ID found for deletion');
                return;
            }
            
            const spinner = confirmBtn.querySelector('.spinner-border');
            
            // Show loading state
            confirmBtn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            
            // Send delete request
            fetch(`${BASE_URL}?controller=supplier&action=delete`, {
                method: 'POST',
                body: new URLSearchParams({ id: supplierId }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async (response) => {
                const text = await response.text();
                try {
                    return { ok: response.ok, data: JSON.parse(text) };
                } catch (e) {
                    console.error('Failed to parse response:', text);
                    throw new Error('Invalid server response');
                }
            })
            .then(({ ok, data }) => {
                if (ok && data && data.success) {
                    // Close the modal
                    const modalElement = document.getElementById('deleteSupplierModal');
                    if (modalElement) {
                        const modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) modal.hide();
                    }
                    
                    // Show success message and reload
                    showAlert(data.message || 'Supplier deleted successfully');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    throw new Error(data?.message || 'Failed to delete supplier');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(error.message || 'Failed to delete supplier. Please try again.', 'danger');
                
                // Reset button state
                confirmBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            });
        });
        
        // Helper function to show alerts
        function showAlert(message, type = 'success') {
            console.log(`Showing alert: ${message} (${type})`);
            // Remove any existing alerts
            const existingAlert = document.querySelector('.alert-dismissible');
            if (existingAlert) {
                existingAlert.remove();
            }

            // Create alert element
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

            // Add to DOM
            document.body.appendChild(alertDiv);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                alert.close();
            }, 5000);
        }
        
        // Helper function to show form errors
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
    });

    // Function to handle edit button clicks
    function handleEditClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const editBtn = e.currentTarget;
        console.log('Edit button clicked', editBtn);
        
        // Get all data attributes
        const supplierData = {
            id: editBtn.getAttribute('data-id'),
            name: editBtn.getAttribute('data-name') || '',
            product_name: editBtn.getAttribute('data-product_name') || '',
            email: editBtn.getAttribute('data-email') || '',
            phone: editBtn.getAttribute('data-phone') || '',
            address: editBtn.getAttribute('data-address') || ''
        };
        
        console.log('Supplier data:', supplierData);
        
        // Set form values
        document.getElementById('edit_id').value = supplierData.id;
        document.getElementById('edit_name').value = supplierData.name;
        document.getElementById('edit_product_name').value = supplierData.product_name;
        document.getElementById('edit_email').value = supplierData.email;
        document.getElementById('edit_phone').value = supplierData.phone;
        document.getElementById('edit_address').value = supplierData.address;
        
        // Set form action and clear errors
        const form = document.getElementById('editSupplierForm');
        if (form) {
            form.action = `${BASE_URL}?controller=supplier&action=update`;
            form.querySelectorAll('.is-invalid').forEach(input => input.classList.remove('is-invalid'));
        }
        
        // Show the modal
        const modal = new bootstrap.Modal(document.getElementById('editSupplierModal'));
        modal.show();
        
        return false;
    }
    
    // Function to handle delete button clicks
    function handleDeleteClick(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const deleteBtn = e.currentTarget;
        if (!deleteBtn) return;
        
        console.log('Delete button clicked', deleteBtn);
        
        const supplierId = deleteBtn.getAttribute('data-id');
        const supplierName = deleteBtn.getAttribute('data-name');
        
        if (!supplierId) {
            console.error('No supplier ID found for deletion');
            return;
        }
        
        // Update the confirmation modal
        const deleteSupplierName = document.getElementById('deleteSupplierName');
        if (deleteSupplierName) {
            deleteSupplierName.textContent = supplierName || 'this supplier';
        }
        
        // Store the ID in the confirm button
        const confirmBtn = document.getElementById('confirmDeleteBtn');
        if (confirmBtn) {
            confirmBtn.setAttribute('data-id', supplierId);
            
            // Remove any existing click handlers to prevent duplicates
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
            
            // Add click handler to the new button
            newConfirmBtn.addEventListener('click', function() {
                const supplierId = this.getAttribute('data-id');
                if (!supplierId) {
                    console.error('No supplier ID found for deletion');
                    return;
                }
                
                // Show loading state
                const spinner = this.querySelector('.spinner-border');
                const buttonText = this.querySelector('.button-text');
                const originalText = buttonText ? buttonText.textContent : 'Delete';
                
                if (buttonText) buttonText.textContent = 'Deleting...';
                if (spinner) spinner.classList.remove('d-none');
                this.disabled = true;
                
                // Create form data for the delete request
                const formData = new FormData();
                formData.append('id', supplierId);
                
                // Send delete request
                fetch(`${BASE_URL}?controller=supplier&action=delete`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: new URLSearchParams(formData).toString()
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('deleteSupplierModal'));
                        if (modal) modal.hide();
                        
                        // Show success message
                        showToast('success', data.message || 'Supplier deleted successfully');
                        
                        // Remove the deleted row from the table
                        const row = deleteBtn.closest('tr');
                        if (row) {
                            row.remove();
                        }
                        
                        // Reload the page after a short delay
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showToast('error', data.message || 'Failed to delete supplier');
                        
                        // Reset button state
                        if (buttonText) buttonText.textContent = originalText;
                        if (spinner) spinner.classList.add('d-none');
                        this.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('error', 'An error occurred while deleting the supplier');
                    
                    // Reset button state
                    if (buttonText) buttonText.textContent = originalText;
                    if (spinner) spinner.classList.add('d-none');
                    this.disabled = false;
                });
            });
        }
        
        // Show the modal
        const modalElement = document.getElementById('deleteSupplierModal');
        if (modalElement) {
            const deleteModal = new bootstrap.Modal(modalElement);
            deleteModal.show();
        } else {
            console.error('Delete confirmation modal not found');
        }
    }
    
    // Make functions globally available
    window.handleEditClick = handleEditClick;
    window.handleDeleteClick = handleDeleteClick;
    
    // Initialize all event listeners when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM fully loaded');
        
        // Initialize other event listeners
        initializeEventListeners();
    });
    
    // ... (rest of the code remains the same)
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
    
    // Handle add supplier form submission
    const addSupplierForm = document.getElementById('addSupplierForm');
    if (addSupplierForm) {
        addSupplierForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const spinner = submitBtn.querySelector('.spinner-border');
            
            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            // Clear previous errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            
            // Convert form data to URL-encoded format
            const formDataObj = {};
            formData.forEach((value, key) => {
                formDataObj[key] = value;
            });
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(formDataObj).toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                    if (modal) modal.hide();
                    
                    // Show success message
                    showToast('success', data.message || 'Supplier added successfully');
                    
                    // Reset form
                    form.reset();
                    
                    // Reload the page to show new supplier
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    // Show validation errors
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, message]) => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = message;
                                } else {
                                    // Create feedback element if it doesn't exist
                                    const newFeedback = document.createElement('div');
                                    newFeedback.className = 'invalid-feedback';
                                    newFeedback.textContent = message;
                                    input.parentNode.insertBefore(newFeedback, input.nextSibling);
                                }
                            }
                        });
                    } else {
                        showToast('error', data.message || 'Failed to add supplier');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while adding the supplier');
            })
            .finally(() => {
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            });
        });
    }
    
    // Handle edit form submission
    const editForm = document.getElementById('editSupplierForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const spinner = submitBtn.querySelector('.spinner-border');
            
            // Show loading state
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            // Clear previous errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            
            // Convert form data to URL-encoded format
            const formDataObj = {};
            formData.forEach((value, key) => {
                formDataObj[key] = value;
            });
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new URLSearchParams(formDataObj).toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
                    if (modal) modal.hide();
                    
                    // Show success message
                    showToast('success', data.message || 'Supplier updated successfully');
                    
                    // Reload the page to show updated data
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    // Show validation errors
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, message]) => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = message;
                                } else {
                                    // Create feedback element if it doesn't exist
                                    const newFeedback = document.createElement('div');
                                    newFeedback.className = 'invalid-feedback';
                                    newFeedback.textContent = message;
                                    input.parentNode.insertBefore(newFeedback, input.nextSibling);
                                }
                            }
                        });
                    } else {
                        showToast('error', data.message || 'Failed to update supplier');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred while updating the supplier');
            })
            .finally(() => {
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
            });
        });
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
