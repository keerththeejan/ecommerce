<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Edit Brand</h1>
        <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i> Back to Brands
        </a>
    </div>
    
    <div id="alert-messages">
        <?php flash('brand_success', '', 'alert alert-success'); ?>
        <?php flash('brand_error', '', 'alert alert-danger'); ?>
    </div>
    
    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Brand Information</h5>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="toggleStatus" <?php echo $data['status'] == 'active' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="toggleStatus" id="statusLabel">
                    <?php echo $data['status'] == 'active' ? 'Active' : 'Inactive'; ?>
                </label>
                <input type="hidden" name="status" id="statusInput" value="<?php echo $data['status']; ?>">
            </div>
        </div>
        <div class="card-body">
            <form id="editBrandForm" action="<?php echo BASE_URL; ?>?controller=brand&action=edit&id=<?php echo $brand['id']; ?>" method="POST" enctype="multipart/form-data" novalidate>
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
                                    <?php if(!empty($brand['logo'])): ?>
                                        <img src="<?php echo BASE_URL . $brand['logo']; ?>" alt="<?php echo $brand['name']; ?>" style="max-width: 100%; max-height: 100%;">
                                    <?php else: ?>
                                        <i class="fas fa-building text-muted fa-4x"></i>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($brand['logo'])): ?>
                                    <div class="form-text mt-2">Current logo. Upload a new one to replace it.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="d-flex justify-content-end">
                    <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-1"></i> Update Brand
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle status switch
    const toggleStatus = document.getElementById('toggleStatus');
    const statusLabel = document.getElementById('statusLabel');
    const statusInput = document.getElementById('statusInput');
    
    if (toggleStatus) {
        // Initialize status from the radio buttons
        const activeRadio = document.querySelector('input[name="status"][value="active"]');
        if (activeRadio) {
            toggleStatus.checked = activeRadio.checked;
            statusInput.value = activeRadio.checked ? 'active' : 'inactive';
            statusLabel.textContent = activeRadio.checked ? 'Active' : 'Inactive';
        }
        
        // Update radio buttons when toggle changes
        toggleStatus.addEventListener('change', function() {
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
                toggleStatus.checked = (this.value === 'active');
                statusInput.value = this.value;
                statusLabel.textContent = this.value.charAt(0).toUpperCase() + this.value.slice(1);
            });
        });
    }
    
    // Logo preview
    const logoInput = document.getElementById('logo');
    const logoPreview = document.querySelector('.logo-preview');
    
    if (logoInput) {
        logoInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    logoPreview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="img-fluid">`;
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Form submission
    const form = document.getElementById('editBrandForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
            
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
                    showAlert('Brand updated successfully!', 'success');
                    
                    // Update logo preview if it was changed
                    if (data.data.logo) {
                        const logoPreviewImg = logoPreview.querySelector('img');
                        if (logoPreviewImg) {
                            logoPreviewImg.src = data.data.logo.startsWith('http') ? 
                                data.data.logo : 
                                `<?php echo BASE_URL; ?>${data.data.logo}`;
                        } else {
                            logoPreview.innerHTML = `<img src="<?php echo BASE_URL; ?>${data.data.logo}" alt="Logo Preview" class="img-fluid">`;
                        }
                    }
                    
                    // Update status toggle if needed
                    if (toggleStatus) {
                        toggleStatus.checked = data.data.status === 'active';
                        statusLabel.textContent = data.data.status === 'active' ? 'Active' : 'Inactive';
                        statusInput.value = data.data.status;
                    }
                    
                    // Update form data
                    document.getElementById('name').value = data.data.name;
                    document.getElementById('description').value = data.data.description || '';
                    
                    // Clear file input
                    if (logoInput) {
                        logoInput.value = '';
                    }
                    
                    // Scroll to top to show the success message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    throw new Error(data.message || 'Failed to update brand');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(error.message || 'An error occurred while updating the brand', 'danger');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
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
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Add to container
        alertMessages.appendChild(alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }, 5000);
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
