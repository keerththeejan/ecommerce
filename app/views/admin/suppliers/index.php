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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage Suppliers</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                        <i class="fas fa-plus me-1"></i> Add New Supplier
                    </button>
                </div>
            </div>
            
            <?php flash('supplier_success'); ?>
            
            <!-- Add Supplier Form -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Add New Supplier</h5>
                </div>
                <div class="card-body">
                    <form id="addSupplierForm" action="<?php echo BASE_URL; ?>?controller=supplier&action=index" method="post">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" 
                                       id="name" name="name" value="<?php echo $data['name'] ?? ''; ?>">
                                <span class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></span>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                       id="email" name="email" value="<?php echo $data['email'] ?? ''; ?>">
                                <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" class="form-control <?php echo (!empty($data['phone_err'])) ? 'is-invalid' : ''; ?>" 
                                       id="phone" name="phone" value="<?php echo $data['phone'] ?? ''; ?>">
                                <span class="invalid-feedback"><?php echo $data['phone_err'] ?? ''; ?></span>
                            </div>
                            <div class="col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2"><?php echo $data['address'] ?? ''; ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name" name="product_name" 
                                       value="<?php echo $data['product_name'] ?? ''; ?>">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    Add Supplier
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Suppliers Table -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">All Suppliers</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Supplier Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Products</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="suppliersTableBody">
                                <?php if (!empty($data['suppliers'])): ?>
                                    <?php 
                                    $suppliersData = [];
                                    foreach ($data['suppliers'] as $index => $supplier): 
                                        $suppliersData[] = [
                                            'id' => $supplier->id,
                                            'name' => htmlspecialchars($supplier->name),
                                            'email' => htmlspecialchars($supplier->email),
                                            'phone' => htmlspecialchars($supplier->phone),
                                            'address' => $supplier->address ?? '',
                                            'product_name' => $supplier->products ?? ''
                                        ];
                                    endforeach; 
                                    ?>
                                    <script>
// Defer script execution until after the page has loaded
window.addEventListener('load', function() {
    const suppliersData = <?php echo json_encode($suppliersData); ?>;
    const tbody = document.getElementById('suppliersTableBody');
    
    // Initialize the table with the initial data
    if (suppliersData && suppliersData.length > 0) {
        updateSuppliersTable(suppliersData);
    }
        
        // Initialize tooltips using event delegation
        const initTooltips = function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [data-bs-tooltip="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        };
        
        // Initialize tooltips with a small delay to ensure DOM is ready
        setTimeout(initTooltips, 100);
        
        // Event delegation for dynamic elements
        tbody.addEventListener('click', function(e) {
            // Handle view details click
            if (e.target.closest('.view-details')) {
                e.preventDefault();
                const supplierId = e.target.closest('.view-details').dataset.id;
                loadSupplierDetails(`<?php echo BASE_URL; ?>?controller=supplier&action=details&id=${supplierId}`);
            }
            
            // Handle delete click
            if (e.target.closest('.delete-supplier')) {
                e.preventDefault();
                const supplierId = e.target.closest('.delete-supplier').dataset.id;
                if (confirm('Are you sure you want to delete this supplier?')) {
                    deleteSupplier(supplierId);
                }
            }
        });
    });
});
</script>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No suppliers found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
            <form id="addSupplierForm" action="<?php echo BASE_URL; ?>?controller=supplier&action=create" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to update the suppliers table
    function updateSuppliersTable(suppliers) {
        console.log('Updating suppliers table with data:', suppliers);
        const tbody = document.getElementById('suppliersTableBody');
        if (!tbody) {
            console.error('Table body not found');
            return;
        }
        
        // Create a document fragment for better performance
        const fragment = document.createDocumentFragment();
        
        if (!suppliers || suppliers.length === 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td colspan="7" class="text-center">No suppliers found</td>
            `;
            fragment.appendChild(row);
        } else {
            // Add new rows
            suppliers.forEach((supplier, index) => {
                if (!supplier) return;
                
                const row = document.createElement('tr');
                row.setAttribute('data-id', supplier.id);
                
                // Ensure we have valid values for all fields
                const supplierName = supplier.name || supplier.supplier_name || 'N/A';
                const email = supplier.email || '';
                const phone = supplier.phone || '';
                const address = supplier.address ? 
                    (supplier.address.length > 30 ? 
                        supplier.address.substring(0, 30) + '...' : 
                        supplier.address) : 'N/A';
                
                // Handle products data which might be a string or an array
                let productDisplay = '<span class="text-muted">No products</span>';
                if (supplier.products) {
                    if (Array.isArray(supplier.products) && supplier.products.length > 0) {
                        productDisplay = supplier.products.map(p => 
                            `<span class="badge bg-info me-1">${p.name || p}</span>`
                        ).join('');
                    } else if (typeof supplier.products === 'string') {
                        productDisplay = `<span class="badge bg-info">${supplier.products}</span>`;
                    }
                }
                
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>
                        <a href="#" class="text-primary view-details" 
                           data-id="${supplier.id}">
                            ${supplierName}
                        </a>
                    </td>
                    <td>${email}</td>
                    <td>${phone}</td>
                    <td>${address}</td>
                    <td>${productDisplay}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="${BASE_URL}/?controller=supplier&action=details&id=${supplier.id}" 
                           class="btn btn-sm btn-outline-primary" 
                           data-bs-toggle="tooltip" 
                           title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary edit-supplier" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editSupplierModal" 
                                data-id="${supplier.id}"
                                data-bs-tooltip="tooltip" 
                                title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-supplier" 
                                data-id="${supplier.id}"
                                data-bs-tooltip="tooltip" 
                                title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
        
        // Reinitialize tooltips for the new elements
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [data-bs-tooltip="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Close sidebar when clicking on backdrop
    const backdrop = document.getElementById('sidebarBackdrop');
    if (backdrop) {
        backdrop.addEventListener('click', function() {
            closeSidebar();
        });
    }
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [data-bs-tooltip="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Handle form submission for both inline form and modal form
    function handleSupplierFormSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        const spinner = submitBtn ? submitBtn.querySelector('.spinner-border') : null;
        const formData = new FormData(form);
        
        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
        }
        
        // Reset previous errors and success messages
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        
        // Remove any existing alerts
        const existingAlerts = form.closest('.card-body')?.querySelectorAll('.alert') || [];
        existingAlerts.forEach(alert => alert.remove());
        
        // Validate required fields
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            const fieldValue = field.value.trim();
            const errorElement = field.nextElementSibling;
            
            // Check for empty required fields
            if (!fieldValue) {
                field.classList.add('is-invalid');
                if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                    errorElement.textContent = 'This field is required';
                }
                isValid = false;
                return;
            }
            
            // Validate email format if field is an email
            if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fieldValue)) {
                field.classList.add('is-invalid');
                if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                    errorElement.textContent = 'Please enter a valid email address';
                }
                isValid = false;
                return;
            }
            
            // Validate phone number format if field is a phone
            if (field.id === 'phone' && !/^[0-9+\-\s()]+$/.test(fieldValue)) {
                field.classList.add('is-invalid');
                if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                    errorElement.textContent = 'Please enter a valid phone number';
                }
                isValid = false;
            }
        });
        
        if (!isValid) {
            if (submitBtn) {
                submitBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            }
            return false;
        }
            
        // Submit form via AJAX
        console.log('Submitting form data:', formData);
        fetch('<?php echo BASE_URL; ?>/supplier/add', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Raw response status:', response.status);
            if (!response.ok) {
                return response.json().catch(() => {
                    throw new Error('Network response was not ok');
                }).then(err => {
                    console.error('Error response:', err);
                    throw new Error(err.message || 'Network response was not ok');
                });
            }
            return response.json().then(data => {
                console.log('Response data:', data);
                return data;
            });
        })
        .then(data => {
            if (data.success) {
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.innerHTML = `
                    ${data.message || 'Supplier added successfully'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                // Insert alert before the form
                const formContainer = form.closest('.card-body') || form.parentNode;
                formContainer.insertBefore(alertDiv, formContainer.firstChild);
                
                // Reset form and clear validation
                form.reset();
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                
                console.log('Processing successful response:', data);
                
                // If we have the full list of suppliers, use that
                if (data.suppliers && Array.isArray(data.suppliers)) {
                    console.log('Updating table with full suppliers list');
                    updateSuppliersTable(data.suppliers);
                } 
                // Otherwise, if we just have the new supplier, add it to the existing list
                else if (data.supplier) {
                    console.log('Adding new supplier to existing list');
                    
                    // Get current suppliers from the table
                    const tbody = document.getElementById('suppliersTableBody');
                    const currentSuppliers = [];
                    
                    // If we have existing rows, get their data
                    if (tbody && tbody.rows.length > 0) {
                        Array.from(tbody.rows).forEach(row => {
                            const id = row.dataset.id;
                            // Skip if this is the "no suppliers" row
                            if (!id) return;
                            
                            const name = row.cells[1]?.querySelector('a')?.textContent.trim() || '';
                            const email = row.cells[2]?.textContent.trim() || '';
                            const phone = row.cells[3]?.textContent.trim() || '';
                            const address = row.cells[4]?.textContent.trim() || '';
                            const products = row.cells[5]?.innerHTML || '';
                            
                            currentSuppliers.push({ id, name, email, phone, address, products });
                        });
                    }
                    
                    // Add the new supplier to the beginning of the list
                    const newSupplier = {
                        id: data.supplier.id,
                        name: data.supplier.name || data.supplier.supplier_name || 'New Supplier',
                        email: data.supplier.email || '',
                        phone: data.supplier.phone || '',
                        address: data.supplier.address || '',
                        products: Array.isArray(data.supplier.products) && data.supplier.products.length > 0
                            ? data.supplier.products.map(p => p.name).join(', ')
                            : (data.supplier.product_name || '')
                    };
                    
                    currentSuppliers.unshift(newSupplier);
                    updateSuppliersTable(currentSuppliers);
                }
                
                // Close the modal if open
                const modalElement = document.getElementById('addSupplierModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        // Reset the modal form when hidden
                        modal._element.addEventListener('hidden.bs.modal', function onModalHidden() {
                            const modalForm = document.getElementById('addSupplierModalForm');
                            if (modalForm) {
                                modalForm.reset();
                                modalForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                                modalForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                            }
                            modal._element.removeEventListener('hidden.bs.modal', onModalHidden);
                        }, { once: true });
                        
                        modal.hide();
                    }
                }
                
                // Scroll to the top of the table
                const table = document.querySelector('.table-responsive');
                if (table) {
                    setTimeout(() => {
                        table.scrollIntoView({ behavior: 'smooth' });
                    }, 100);
                }
                
                // Reinitialize tooltips after table update
                initializeTooltips();
            } else {
                // Show error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
                    ${data.message || 'An error occurred while processing your request. Please try again.'}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                // Insert alert before the form
                const formContainer = form.closest('.card-body') || form.parentNode;
                formContainer.insertBefore(alertDiv, formContainer.firstChild);
                
                // Show field errors if any
                if (data.errors) {
                    Object.entries(data.errors).forEach(([field, message]) => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const errorElement = input.nextElementSibling;
                            if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                                errorElement.textContent = message;
                            }
                        }
                    });
                }
                
                // Scroll to the first error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Show error message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger alert-dismissible fade show';
            alertDiv.innerHTML = `
                ${error.message || 'An error occurred while processing your request. Please try again.'}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert alert before the form
            const formContainer = form.closest('.card-body') || form.parentNode;
            formContainer.insertBefore(alertDiv, formContainer.firstChild);
            
            // Scroll to the error message
            setTimeout(() => {
                alertDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100);
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            }
        });
        
    // Function to fetch suppliers and update the table
    function fetchSuppliersAndUpdateTable() {
        fetch('<?php echo BASE_URL; ?>?controller=supplier&action=index&ajax=1')
            .then(response => response.json())
            .then(data => {
                if (data && data.suppliers) {
                    const formattedSuppliers = data.suppliers.map(supplier => {
                        const productName = Array.isArray(supplier.products) && supplier.products.length > 0 
                            ? supplier.products[0].name 
                            : (supplier.product_name || '');
                            
                        return {
                            id: supplier.id,
                            name: supplier.name || supplier.supplier_name || '',
                            email: supplier.email || '',
                            phone: supplier.phone || '',
                            address: supplier.address || '',
                            products: productName
                        };
                    });
                    
                    updateSuppliersTable(formattedSuppliers);
                }
            })
            .catch(error => {
                console.error('Error fetching suppliers:', error);
            });
    }
    
    // Helper function to initialize tooltips
    function initializeTooltips() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [data-bs-tooltip="tooltip"]'));
        tooltipTriggerList.forEach(tooltipTriggerEl => {
            // Destroy existing tooltip if it exists
            const existingTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
            if (existingTooltip) {
                existingTooltip.dispose();
            }
            // Initialize new tooltip
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
        
    }
    
    // Initialize the application when the DOM is fully loaded
    function initializeApp() {
        // Add event listeners for both forms
        const addSupplierForm = document.getElementById('addSupplierForm');
        if (addSupplierForm) {
            // Remove any existing listeners by cloning the form
            const newForm = addSupplierForm.cloneNode(true);
            addSupplierForm.parentNode.replaceChild(newForm, addSupplierForm);
            
            // Add new submit listener
            newForm.addEventListener('submit', function(e) {
                handleSupplierFormSubmit(e);
            });
        }
        
        // Handle modal form if it exists
        const addSupplierModalForm = document.getElementById('addSupplierModalForm');
        if (addSupplierModalForm) {
            // Remove any existing listeners by cloning the form
            const newModalForm = addSupplierModalForm.cloneNode(true);
            addSupplierModalForm.parentNode.replaceChild(newModalForm, addSupplierModalForm);
            
            // Add new submit listener
            newModalForm.addEventListener('submit', function(e) {
                handleSupplierFormSubmit(e);
            });
            
            // Also handle the modal's show event to reset the form when opened
            const modalElement = document.getElementById('addSupplierModal');
            if (modalElement) {
                modalElement.addEventListener('show.bs.modal', function() {
                    const form = document.getElementById('addSupplierModalForm');
                    if (form) {
                        form.reset();
                        // Clear validation errors
                        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
                    }
                });
            }
        }
        
        // Initialize delete buttons
        document.querySelectorAll('.delete-supplier').forEach(btn => {
            // Remove any existing listeners
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            // Add new click listener
            newBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Are you sure you want to delete this supplier?')) {
                    const supplierId = this.getAttribute('data-id');
                    deleteSupplier(supplierId);
                }
            });
        });
        
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [data-bs-tooltip="tooltip"]'));
        tooltipTriggerList.forEach(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    // Run initialization when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeApp);
    } else {
        initializeApp();
    }
    
    // Delete supplier function
    window.deleteSupplier = function(supplierId) {
        if (confirm('Are you sure you want to delete this supplier? This action cannot be undone.')) {
            fetch(`<?php echo BASE_URL; ?>?controller=supplier&action=delete&id=${supplierId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `csrf_token=<?php echo $_SESSION['csrf_token']; ?>`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error deleting supplier: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting supplier. Please try again.');
            });
        }
    };
    
    // Function to load supplier details in sidebar
    window.loadSupplierDetails = function(url) {
        // Show loading state
        const sidebar = document.getElementById('supplierSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const sidebarContent = document.getElementById('supplierDetailsContent');
        
        // Show loading state
        sidebarContent.innerHTML = `
            <div class="d-flex justify-content-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        // Show sidebar and backdrop on mobile
        if (window.innerWidth < 768) {
            sidebar.classList.add('show');
            backdrop.style.display = 'block';
        }
        
        fetch(url + '&ajax=1')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const supplier = data.supplier;
                    
                    sidebarContent.innerHTML = `
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">${supplier.name || 'Supplier Details'}</h5>
                                <button type="button" class="btn-close" onclick="closeSidebar()"></button>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="text-muted">Contact Information</h6>
                                    <p class="mb-1"><i class="fas fa-envelope me-2"></i> ${supplier.email || 'N/A'}</p>
                                    <p class="mb-1"><i class="fas fa-phone me-2"></i> ${supplier.phone || 'N/A'}</p>
                                    <p class="mb-1"><i class="fas fa-mobile-alt me-2"></i> ${supplier.mobile || 'N/A'}</p>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-muted">Address</h6>
                                    <p>${supplier.address ? supplier.address.replace(/\n/g, '<br>') : 'N/A'}</p>
                                </div>
                                <div class="border-top pt-3 mt-3">
                                    <h6 class="text-muted">Products</h6>
                                    <div class="d-flex flex-wrap gap-2" id="supplierProducts">
                                        ${supplier.products ? 
                                            supplier.products.split(',').map(p => `
                                                <span class="badge bg-info">${p.trim()}</span>
                                            `).join('') : 
                                            '<span class="text-muted">No products</span>'
                                        }
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" onclick="window.location.href='${url.replace('&ajax=1', '')}'">
                                        <i class="fas fa-external-link-alt me-1"></i> View Full Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    throw new Error(data.message || 'Failed to load supplier details');
                }
            })
            .catch(error => {
                console.error('Error loading supplier details:', error);
                sidebarContent.innerHTML = `
                    <div class="alert alert-danger m-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ${error.message || 'Error loading supplier details. Please try again.'}
                    </div>
                `;
            });
    };
    
    // Handle edit button click
    document.querySelectorAll('.edit-supplier').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const supplierId = this.getAttribute('data-id');
            // Implement edit functionality here
            alert('Edit supplier ' + supplierId);
        });
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
