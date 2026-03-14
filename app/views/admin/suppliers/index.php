<?php
$suppliers = $suppliers ?? [];
require_once APP_PATH . 'views/admin/layouts/header.php';
?>
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

    .suppliers-admin .card-body { padding: 1rem; }
    @media (min-width: 768px) { .suppliers-admin .card-body { padding: 1.25rem; } }

    .suppliers-table-scroll {
      max-height: 65vh;
      overflow: auto;
      -webkit-overflow-scrolling: touch;
      border-top: 1px solid var(--border-color);
    }

    .suppliers-table-scroll .table { margin-bottom: 0; }

    .suppliers-table-scroll thead th {
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

    .suppliers-table-scroll tbody td { padding: 0.8rem 0.9rem; vertical-align: middle; }

    #suppliersTable tbody tr:nth-child(even) { background: rgba(17, 24, 39, 0.02); }
    [data-theme="dark"] #suppliersTable tbody tr:nth-child(even) { background: rgba(255, 255, 255, 0.04); }
    #suppliersTable tbody tr:hover { background: rgba(59, 130, 246, 0.06) !important; }

    .btn-action {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      border-radius: 10px;
      font-weight: 600;
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

    @media (max-width: 575.98px) { .suppliers-table-scroll { max-height: 60vh; } }
    @media (min-width: 576px) and (max-width: 991.98px) { .suppliers-table-scroll { max-height: 60vh; } }
    @media (min-width: 992px) { .suppliers-table-scroll { max-height: 70vh; } }
@media (min-width: 576px) and (max-width: 991.98px) {
  #suppliersTable th:nth-child(4), #suppliersTable td:nth-child(4),
  #suppliersTable th:nth-child(5), #suppliersTable td:nth-child(5) { display: none !important; }
}
@media (max-width: 575.98px) {
  #suppliersTable thead { display: none; }
  #suppliersTable tbody tr {
    display: block;
    margin-bottom: 1rem;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg, #fff);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  #suppliersTable tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid rgba(0,0,0,.06);
  }
  #suppliersTable tbody td:last-child { border-bottom: 0; }
  #suppliersTable tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--bs-secondary, #6c757d);
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  #suppliersTable tbody td[data-label="Actions"] { flex-wrap: wrap; gap: 0.25rem; }
  #suppliersTable tbody td[data-label="Actions"] .supplier-actions { width: 100%; justify-content: flex-end; flex-wrap: wrap; }
  #suppliersTable tbody tr[onclick] { cursor: pointer; }
}
#supplierSidebar { transition: all 0.3s ease; }
@media (max-width: 767.98px) {
  #supplierSidebar { position: fixed; top: 0; right: -100%; height: 100%; z-index: 1040; overflow-y: auto; }
  #supplierSidebar.show { right: 0; }
  .sidebar-backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1039; display: none; }
}
</style>
<div class="sidebar-backdrop d-md-none" id="sidebarBackdrop"></div>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 suppliers-admin page-shell">
    <div class="d-flex align-items-start justify-content-between flex-wrap" style="gap: 0.75rem; margin-bottom: 0.75rem;">
        <div>
            <h1 class="page-title">Manage Suppliers</h1>
            <p class="page-subtitle">Maintain supplier contacts and quickly view details.</p>
        </div>
        <div class="d-flex align-items-center" style="gap: 0.5rem; flex-wrap: wrap;">
            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary btn-action">
                <i class="fas fa-arrow-left"></i>
                <span class="ml-1">Products</span>
            </a>
            <a href="#" class="btn btn-primary btn-action" data-toggle="modal" data-target="#addSupplierModal">
                <i class="fas fa-plus"></i>
                <span class="ml-1">Add Supplier</span>
            </a>
        </div>
    </div>

    <div class="row g-3">
        <!-- Main Content -->
        <div class="col-12 col-lg-9">
            <div class="card shadow-sm border-0">
                <div class="card-body pt-2">
                    <?php flash('supplier_success'); ?>
                    <?php flash('supplier_error'); ?>

                    <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 0.75rem; padding: 0.75rem 0; border-bottom: 1px solid var(--border-color);">
                        <div class="d-flex align-items-center" style="gap: 0.5rem; flex-wrap: wrap; flex: 1 1 360px;">
                            <div class="input-group input-group-sm" style="max-width: 420px;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" class="form-control" id="supplierSearch" placeholder="Search suppliers..." aria-label="Search suppliers">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="supplierSearchClear">Clear</button>
                                </div>
                            </div>
                            <span class="small text-muted" id="supplierCount"></span>
                        </div>
                        <div class="d-flex align-items-center" style="gap: 0.5rem; flex-wrap: wrap;">
                            <label for="supplierPerPage" class="form-label small text-muted">Rows</label>
                            <select id="supplierPerPage" class="custom-select custom-select-sm" style="width: auto;">
                                <option value="10">10</option>
                                <option value="20" selected>20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="supplierPrevBtn" aria-label="Previous page">Prev</button>
                            <span class="small text-muted" id="supplierPageInfo">Page 1 / 1</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="supplierNextBtn" aria-label="Next page">Next</button>
                        </div>
                    </div>

                    <div class="suppliers-table-scroll" role="region" aria-label="Suppliers table" tabindex="0">
                        <table id="suppliersTable" class="table align-middle mb-0" aria-describedby="suppliers-helptext">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">#</th>
                                    <th>Supplier Name</th>
                                    <th>Product</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th style="width: 160px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($suppliers)): ?>
                                    <?php foreach ($suppliers as $idx => $supplier): $rowNum = $idx + 1; ?>
                                        <tr style="cursor: pointer;" onclick="loadSupplierDetails('<?php echo BASE_URL; ?>?controller=supplier&action=details&id=<?php echo $supplier['id']; ?>')">
                                            <td data-label="#"><?php echo $rowNum; ?></td>
                                            <td data-label="Supplier Name"><?php echo htmlspecialchars($supplier['name'] ?? ''); ?></td>
                                            <td data-label="Product"><?php echo !empty($supplier['product_name']) ? htmlspecialchars($supplier['product_name']) : '—'; ?></td>
                                            <td data-label="Email"><?php echo !empty($supplier['email']) ? htmlspecialchars($supplier['email']) : '—'; ?></td>
                                            <td data-label="Phone"><?php echo !empty($supplier['phone']) ? htmlspecialchars($supplier['phone']) : '—'; ?></td>
                                            <td data-label="Actions" onclick="event.stopPropagation();">
                                                <div class="btn-group btn-group-sm supplier-actions" role="group" aria-label="Supplier Actions">
                                                    <button type="button" class="btn btn-outline-primary edit-supplier"
                                                            data-id="<?php echo $supplier['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($supplier['name'] ?? ''); ?>"
                                                            data-product_name="<?php echo htmlspecialchars($supplier['product_name'] ?? ''); ?>"
                                                            data-email="<?php echo htmlspecialchars($supplier['email'] ?? ''); ?>"
                                                            data-phone="<?php echo htmlspecialchars($supplier['phone'] ?? ''); ?>"
                                                            data-address="<?php echo htmlspecialchars($supplier['address'] ?? ''); ?>">
                                                        <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Edit</span>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger delete-supplier"
                                                            data-id="<?php echo $supplier['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($supplier['name'] ?? ''); ?>">
                                                        <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">No suppliers found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="suppliers-helptext" class="sr-only">Select a row to view supplier details. Use the edit and delete buttons in the actions column to manage suppliers.</div>

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
                            Font: Inter (400/500/600). Spacing: 8px grid. Corners: 10–14px. Actions: clear hover + focus ring.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-12 col-lg-3">
            <div class="card shadow-sm bg-light border-0 position-sticky pt-3" id="supplierSidebar">
                <div class="card-body">
                    <h5 class="text-center mb-3">Supplier Details</h5>
                    <div id="supplierDetails">
                        <div class="text-center text-muted small">
                            <i class="fas fa-truck fa-3x mb-2"></i>
                            <p class="mb-0">Select a supplier to view details</p>
                        </div>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteSupplierName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            <button type="button" class="close text-white mr-2 m-auto" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
    </div>
    
    <!-- Error Toast -->
    <div class="toast align-items-center text-white bg-danger border-0" id="errorToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="errorToastMessage"></span>
            </div>
            <button type="button" class="close text-white mr-2 m-auto" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button>
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
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    `;

    // Add to DOM
    document.body.appendChild(alertDiv);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        try { $(alertDiv).alert('close'); } catch (e) { /* ignore */ }
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
        
        const modal = $(modalElement).modal();
        
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
            $(modalElement).modal('show');
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
        $(document.getElementById('deleteSupplierModal')).modal('show');
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
                $(document.getElementById('editSupplierModal')).modal('hide');
                
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
            $(document.getElementById('deleteSupplierModal')).modal('hide');
            
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
                document.getElementById('toastMessage').textContent = data.message || 'Supplier added successfully';
                $(document.getElementById('successToast')).toast('show');
                
                // Close modal
                $(document.getElementById('addSupplierModal')).modal('hide');
                
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
            document.getElementById('errorToastMessage').textContent = err.message || 'Failed to save supplier.';
            $(document.getElementById('errorToast')).toast('show');
        })
        .finally(() => {
            saveBtn.disabled = false;
            if (spinner) spinner.classList.add('d-none');
        });
    });
}

// Client-side search + pagination for suppliers table
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('suppliersTable');
    const tbody = table ? table.querySelector('tbody') : null;
    const searchInput = document.getElementById('supplierSearch');
    const clearBtn = document.getElementById('supplierSearchClear');
    const perPageSel = document.getElementById('supplierPerPage');
    const prevBtn = document.getElementById('supplierPrevBtn');
    const nextBtn = document.getElementById('supplierNextBtn');
    const pageInfo = document.getElementById('supplierPageInfo');
    const countEl = document.getElementById('supplierCount');
    if (!tbody || !searchInput || !perPageSel || !prevBtn || !nextBtn || !pageInfo || !countEl) return;

    const allRows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.querySelector('td'));
    let state = { q: '', page: 1, per: parseInt(perPageSel.value, 10) || 20 };

    function rowText(r) { return (r.innerText || '').toLowerCase(); }
    function filteredRows() {
        const q = (state.q || '').trim().toLowerCase();
        if (!q) return allRows;
        return allRows.filter(r => rowText(r).includes(q));
    }
    function render() {
        const rows = filteredRows();
        const total = rows.length;
        const per = Math.max(1, state.per);
        const pages = Math.max(1, Math.ceil(total / per));
        state.page = Math.min(Math.max(1, state.page), pages);
        const start = (state.page - 1) * per;
        const end = start + per;

        allRows.forEach(r => { r.style.display = 'none'; });
        rows.slice(start, end).forEach(r => { r.style.display = ''; });
        pageInfo.textContent = 'Page ' + state.page + ' / ' + pages;
        countEl.textContent = total + ' total';
        prevBtn.disabled = state.page <= 1;
        nextBtn.disabled = state.page >= pages;
    }

    searchInput.addEventListener('input', function() { state.q = searchInput.value || ''; state.page = 1; render(); });
    if (clearBtn) clearBtn.addEventListener('click', function() { searchInput.value=''; state.q=''; state.page=1; render(); try{searchInput.focus();}catch(e){} });
    perPageSel.addEventListener('change', function() { state.per = parseInt(perPageSel.value, 10) || 20; state.page = 1; render(); });
    prevBtn.addEventListener('click', function() { state.page = Math.max(1, state.page - 1); render(); });
    nextBtn.addEventListener('click', function() { state.page = state.page + 1; render(); });

    render();
});
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
