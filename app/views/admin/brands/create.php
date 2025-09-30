<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Add New Brand</h1>
        <div class="d-flex gap-2">
            <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Product
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Back to Brands
            </a>
        </div>
    </div>
    
    <!-- Alert Messages -->
    <div id="alert-messages">
        <?php flash('brand_success', '', 'alert alert-success alert-dismissible fade show'); ?>
        <?php flash('brand_error', '', 'alert alert-danger alert-dismissible fade show'); ?>
    </div>
    
    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-plus-circle me-2"></i>Brand Information
            </h5>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" id="statusToggle" checked>
                <label class="form-check-label" for="statusToggle" id="statusLabel">Active</label>
                <input type="hidden" name="status" id="statusInput" value="active">
            </div>
        </div>
        <div class="card-body">
            <form id="createBrandForm" action="<?php echo BASE_URL; ?>?controller=brand&action=create" method="POST" enctype="multipart/form-data" novalidate>
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Brand Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $data['name']; ?>" required>
                            <?php if(isset($errors['name'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="4"><?php echo $data['description']; ?></textarea>
                            <?php if(isset($errors['description'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['description']; ?></div>
                            <?php endif; ?>
                            <div class="form-text">Provide a brief description of the brand.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_active" value="active" <?php echo $data['status'] == 'active' ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive" <?php echo $data['status'] == 'inactive' ? 'checked' : ''; ?> required>
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>
                            <?php if(isset($errors['status'])): ?>
                                <div class="text-danger mt-1"><?php echo $errors['status']; ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="logo" class="form-label">Brand Logo</label>
                            <input type="file" class="form-control <?php echo isset($errors['logo']) ? 'is-invalid' : ''; ?>" id="logo" name="logo" accept="image/*">
                            <?php if(isset($errors['logo'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['logo']; ?></div>
                            <?php endif; ?>
                            <div class="form-text">Recommended size: 200x200 pixels. Max file size: 2MB.</div>
                            
                            <div class="mt-3 text-center">
                                <div class="logo-preview bg-light d-flex align-items-center justify-content-center" style="width: 200px; height: 200px; margin: 0 auto; border: 1px dashed #ccc;">
                                    <i class="fas fa-building text-muted fa-4x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-undo me-1"></i> Reset
                    </button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Create Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const form = document.getElementById('createBrandForm');
    const submitBtn = document.getElementById('submitBtn');
    const statusToggle = document.getElementById('statusToggle');
    const statusInput = document.getElementById('statusInput');
    const statusLabel = document.getElementById('statusLabel');
    const logoInput = document.getElementById('logo');
    const logoPreview = document.querySelector('.logo-preview');
    
    // Toggle brand status
    if (statusToggle) {
        // Initialize status from the radio buttons
        const activeRadio = document.querySelector('input[name="status"][value="active"]');
        if (activeRadio) {
            statusToggle.checked = activeRadio.checked;
            statusInput.value = activeRadio.checked ? 'active' : 'inactive';
            statusLabel.textContent = activeRadio.checked ? 'Active' : 'Inactive';
        }
        
        // Update radio buttons when toggle changes
        statusToggle.addEventListener('change', function() {
            const status = this.checked ? 'active' : 'inactive';
            statusInput.value = status;
            statusLabel.textContent = this.checked ? 'Active' : 'Inactive';
            
            // Update radio buttons
            document.querySelectorAll('input[name="status"]').forEach(radio => {
                radio.checked = (radio.value === status);
            });
        });
        
        // Update toggle when radio buttons change
        document.querySelectorAll('input[name="status"]').forEach(radio => {
            radio.addEventListener('change', function() {
                statusToggle.checked = (this.value === 'active');
                statusInput.value = this.value;
                statusLabel.textContent = this.value.charAt(0).toUpperCase() + this.value.slice(1);
            });
        });
    }
    
    // Handle logo preview
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    logoPreview.innerHTML = `
                        <div class="position-relative" style="width: 100%; height: 100%;">
                            <img src="${e.target.result}" alt="Logo Preview" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" id="removeLogoBtn" title="Remove logo">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>`;
                        
                    // Add event listener to remove button
                    const removeBtn = document.getElementById('removeLogoBtn');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            logoInput.value = '';
                            logoPreview.innerHTML = '<i class="fas fa-building text-muted fa-4x"></i>';
                        });
                    }
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Handle form submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Creating...';
            
            // Create FormData object
            const formData = new FormData(this);
            
            // Add status from the toggle switch
            formData.set('status', statusInput.value);
            
            // Send AJAX request
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert(data.message, 'success');
                    
                    // Reset form
                    form.reset();
                    
                    // Reset logo preview
                    if (logoPreview) {
                        logoPreview.innerHTML = '<i class="fas fa-building text-muted fa-4x"></i>';
                    }
                    
                    // Reset status toggle
                    if (statusToggle) {
                        statusToggle.checked = true;
                        statusInput.value = 'active';
                        statusLabel.textContent = 'Active';
                    }
                    
                    // Scroll to top to show the success message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    
                    // Optionally, redirect after a delay
                    // setTimeout(() => {
                    //     window.location.href = '<?php echo BASE_URL; ?>?controller=brand&action=adminIndex';
                    // }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to create brand');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(error.message || 'An error occurred while creating the brand', 'danger');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-plus-circle me-1"></i> Create Brand';
            });
        });
    }
    
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
