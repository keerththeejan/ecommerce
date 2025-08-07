<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Alert Messages -->
            <div id="alert-messages">
                <?php flash('product_success', '', 'alert alert-success alert-dismissible fade show'); ?>
                <?php flash('product_error', '', 'alert alert-danger alert-dismissible fade show'); ?>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-box me-2"></i>Edit Product
                    </h3>
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="statusToggle" <?php echo $product['status'] === 'active' ? 'checked' : ''; ?>>
                        <label class="form-check-label text-white" for="statusToggle" id="statusLabel">
                            <?php echo $product['status'] === 'active' ? 'Active' : 'Inactive'; ?>
                        </label>
                        <input type="hidden" name="status" id="statusInput" value="<?php echo $product['status']; ?>">
                    </div>
                </div>
                <div class="card-body">
                    <form id="editProductForm" action="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $product['name']; ?>" required>
                                    <?php if(isset($errors['name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="5"><?php echo $product['description']; ?></textarea>
                                    <?php if(isset($errors['description'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['description']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Buying Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" id="price" name="price" value="<?php echo $product['price']; ?>" step="0.01" min="0" required>
                                                <?php if(isset($errors['price'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sale_price" class="form-label">Including Tax Price (Optional)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control <?php echo isset($errors['sale_price']) ? 'is-invalid' : ''; ?>" id="sale_price" name="sale_price" value="<?php echo $product['sale_price']; ?>" step="0.01" min="0">
                                                <?php if(isset($errors['sale_price'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['sale_price']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price2" class="form-label">Sales Price (Optional)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control" id="price2" name="price2" value="<?php echo $product['price2'] ?? $product['price']; ?>" step="0.01" min="0">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price3" class="form-label">Wholesale Price (SP) (Optional)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">₹</span>
                                                <input type="number" class="form-control" id="price3" name="price3" value="<?php echo $product['price3'] ?? $product['price']; ?>" step="0.01" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <?php if(!empty($product['image'])): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo BASE_URL . $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image">
                                    <?php if(isset($errors['image'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['image']; ?></div>
                                    <?php endif; ?>
                                    <div class="form-text">Leave empty to keep current image. Recommended size: 230x250 pixels</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control <?php echo isset($errors['sku']) ? 'is-invalid' : ''; ?>" id="sku" name="sku" value="<?php echo $product['sku']; ?>" required>
                                    <?php if(isset($errors['sku'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['sku']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control <?php echo isset($errors['stock_quantity']) ? 'is-invalid' : ''; ?>" id="stock_quantity" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>" min="0" required>
                                    <?php if(isset($errors['stock_quantity'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['stock_quantity']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo $category['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if(isset($errors['category_id'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['category_id']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" <?php echo ($product['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo ($product['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Products
                            </a>
                            <div>
                                <button type="button" id="cancelChangesBtn" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-1"></i> Cancel
                                </button>
                                <button type="submit" id="submitBtn" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Product
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form elements
    const form = document.getElementById('editProductForm');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelChangesBtn');
    const statusToggle = document.getElementById('statusToggle');
    const statusInput = document.getElementById('statusInput');
    const statusLabel = document.getElementById('statusLabel');
    const imageInput = document.getElementById('image');
    const imagePreview = document.querySelector('.img-thumbnail');
    const originalFormData = form ? new FormData(form) : null;
    
    // Toggle product status
    if (statusToggle) {
        statusToggle.addEventListener('change', function() {
            const status = this.checked ? 'active' : 'inactive';
            statusInput.value = status;
            statusLabel.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        });
    }
    
    // Handle image preview
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    if (imagePreview) {
                        imagePreview.src = e.target.result;
                    } else {
                        const previewDiv = document.querySelector('.mb-3:has(#image)');
                        if (previewDiv) {
                            const previewImg = document.createElement('img');
                            previewImg.src = e.target.result;
                            previewImg.alt = 'New product image';
                            previewImg.className = 'img-thumbnail mt-2';
                            previewImg.style.maxHeight = '150px';
                            previewDiv.appendChild(previewImg);
                        }
                    }
                };
                
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Cancel changes
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            // Reset form to original state
            if (form) {
                form.reset();
                statusInput.value = '<?php echo $product['status']; ?>';
                if (statusToggle) {
                    statusToggle.checked = statusInput.value === 'active';
                    statusLabel.textContent = statusInput.value.charAt(0).toUpperCase() + statusInput.value.slice(1);
                }
                
                // Reset image preview
                if (imagePreview) {
                    imagePreview.src = '<?php echo !empty($product['image']) ? BASE_URL . $product['image'] : ''; ?>';
                }
                
                // Clear file input
                if (imageInput) {
                    imageInput.value = '';
                }
                
                showAlert('Changes discarded', 'info');
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
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Updating...';
            
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
                    
                    // Update image preview if it was changed
                    if (data.data.image && imagePreview) {
                        imagePreview.src = data.data.image.startsWith('http') ? 
                            data.data.image : 
                            `<?php echo BASE_URL; ?>${data.data.image}`;
                    }
                    
                    // Update form data
                    document.getElementById('name').value = data.data.name;
                    document.getElementById('description').value = '<?php echo addslashes($product['description']); ?>';
                    document.getElementById('price').value = data.data.price;
                    document.getElementById('sale_price').value = data.data.sale_price || '';
                    document.getElementById('stock_quantity').value = data.data.stock_quantity;
                    document.getElementById('sku').value = '<?php echo $product['sku']; ?>';
                    
                    // Update status toggle
                    if (statusToggle) {
                        statusToggle.checked = data.data.status === 'active';
                        statusInput.value = data.data.status;
                        statusLabel.textContent = data.data.status.charAt(0).toUpperCase() + data.data.status.slice(1);
                    }
                    
                    // Clear file input
                    if (imageInput) {
                        imageInput.value = '';
                    }
                    
                    // Scroll to top to show the success message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    throw new Error(data.message || 'Failed to update product');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(error.message || 'An error occurred while updating the product', 'danger');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save me-1"></i> Update Product';
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
