<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Add New Product</h3>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Back to Products
                    </a>
                </div>
                <div class="card-body">
                    <div id="alert-messages">
                        <?php flash('product_success', '', 'alert alert-success'); ?>
                        <?php flash('product_error', '', 'alert alert-danger'); ?>
                    </div>
                    
                    <form id="productForm" action="<?php echo BASE_URL; ?>?controller=product&action=create" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $data['name'] ?? ''; ?>" required>
                                    <?php if(isset($errors['name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="5"><?php echo $data['description'] ?? ''; ?></textarea>
                                    <?php if(isset($errors['description'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['description']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Country Selection -->
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">Country of Origin</label>
                                    <select class="form-select select2 <?php echo isset($errors['country_id']) ? 'is-invalid' : ''; ?>" id="country_id" name="country_id" required>
                                        <option value="">Select Country</option>
                                        <?php 
                                        // Get active countries
                                        $countryModel = new Country();
                                        $countries = $countryModel->getActiveCountries();
                                        
                                        if(!empty($countries)) :
                                            foreach($countries as $country) :
                                                $selected = (isset($data['country_id']) && $data['country_id'] == $country['id']) ? 'selected' : '';
                                                $countryCode = strtolower(substr($country['name'], 0, 2));
                                                $flagImage = !empty($country['flag_image']) ? 
                                                    BASE_URL . 'uploads/flags/' . $country['flag_image'] : 
                                                    'https://flagcdn.com/24x18/' . $countryCode . '.png';
                                        ?>
                                            <option value="<?php echo $country['id']; ?>" 
                                                data-flag-image="<?php echo $flagImage; ?>"
                                                <?php echo $selected; ?>>
                                                <?php echo $country['name']; ?>
                                            </option>
                                        <?php 
                                            endforeach;
                                        endif; 
                                        ?>
                                    </select>
                                    <?php if(isset($errors['country_id'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['country_id']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <!-- Brand Selection -->
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-select <?php echo isset($errors['brand_id']) ? 'is-invalid' : ''; ?>" id="brand_id" name="brand_id" required>
                                        <option value="">Select Brand</option>
                                        <?php 
                                        // Get active brands
                                        $brandModel = new Brand();
                                        $brands = $brandModel->getActiveBrands();
                                        
                                        if(!empty($brands)) :
                                            foreach($brands as $brand) :
                                                $selected = (isset($data['brand_id']) && $data['brand_id'] == $brand['id']) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo $brand['id']; ?>" <?php echo $selected; ?>><?php echo $brand['name']; ?></option>
                                        <?php 
                                            endforeach;
                                        endif; 
                                        ?>
                                    </select>
                                    <?php if(isset($errors['brand_id'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['brand_id']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">CHF</span>
                                                <input type="number" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" id="price" name="price" value="<?php echo $data['price'] ?? ''; ?>" step="0.01" min="0" required>
                                                <?php if(isset($errors['price'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sale_price" class="form-label">Sale Price (Optional)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">CHF</span>
                                                <input type="number" class="form-control <?php echo isset($errors['sale_price']) ? 'is-invalid' : ''; ?>" id="sale_price" name="sale_price" value="<?php echo $data['sale_price'] ?? ''; ?>" step="0.01" min="0">
                                                <?php if(isset($errors['sale_price'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['sale_price']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <input type="file" class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image">
                                    <?php if(isset($errors['image'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['image']; ?></div>
                                    <?php endif; ?>
                                    <div class="form-text">Recommended size: 230x250 pixels</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control <?php echo isset($errors['sku']) ? 'is-invalid' : ''; ?>" id="sku" name="sku" value="<?php echo $data['sku'] ?? ''; ?>" required>
                                    <?php if(isset($errors['sku'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['sku']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control <?php echo isset($errors['stock_quantity']) ? 'is-invalid' : ''; ?>" id="stock_quantity" name="stock_quantity" value="<?php echo $data['stock_quantity'] ?? ''; ?>" min="0" required>
                                    <?php if(isset($errors['stock_quantity'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['stock_quantity']; ?></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo (isset($data['category_id']) && $data['category_id'] == $category['id']) ? 'selected' : ''; ?>>
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
                                        <option value="active" <?php echo (isset($data['status']) && $data['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo (isset($data['status']) && $data['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i> Create Product
                                </button>
                                <button type="button" class="btn btn-success d-none" id="addAnotherBtn" style="display: none;">
                                    <i class="fas fa-plus me-1"></i> Add Another
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Style for flag images in dropdown */
.select2-container--bootstrap-5 .select2-selection--single {
    height: 38px;
    padding: 0.375rem 0.75rem;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    padding-left: 0;
    line-height: 1.5;
}

.select2-container--bootstrap-5 .select2-results__option {
    padding: 6px 12px;
    display: flex;
    align-items: center;
}

.select2-container--bootstrap-5 .select2-results__option img {
    width: 24px;
    height: 18px;
    object-fit: cover;
    border: 1px solid #dee2e6;
    margin-right: 10px;
    border-radius: 2px;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    display: flex;
    align-items: center;
}
</style>

<script>
// Format country with flag
function formatCountry(country) {
    if (!country.id) { return country.text; }
    
    var $country = $(
        '<div class="d-flex align-items-center">' +
        '<img src="' + $(country.element).data('flag-image') + '" class="me-2" style="width: 24px; height: 18px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 2px;">' +
        '<span>' + country.text + '</span>' +
        '</div>'
    );
    return $country;
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Select2 for country dropdown
    $('.select2').select2({
        theme: 'bootstrap-5',
        templateResult: formatCountry,
        templateSelection: formatCountry,
        escapeMarkup: function(m) { return m; }
    });
    
    const form = document.getElementById('productForm');
    const submitBtn = document.getElementById('submitBtn');
    const addAnotherBtn = document.getElementById('addAnotherBtn');
    const alertMessages = document.getElementById('alert-messages');
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating...';
        
        // Create FormData object
        const formData = new FormData(form);
        
        // Add AJAX header
        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        
        xhr.onload = function() {
            // Reset button state
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            
            if (xhr.status >= 200 && xhr.status < 300) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    
                    if (response.success) {
                        // Show success message
                        showAlert('Product created successfully!', 'success');
                        
                        // Show "Add Another" button
                        submitBtn.classList.add('d-none');
                        addAnotherBtn.classList.remove('d-none');
                        
                        // Reset form
                        form.reset();
                        
                        // Clear file input
                        const fileInput = document.querySelector('input[type="file"]');
                        if (fileInput) fileInput.value = '';
                        
                        // Clear any validation errors
                        clearValidationErrors();
                        
                        // Scroll to top
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        showAlert(response.message || 'Failed to create product', 'danger');
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                    showAlert('An error occurred. Please try again.', 'danger');
                }
            } else {
                showAlert('An error occurred. Please try again.', 'danger');
            }
        };
        
        xhr.onerror = function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            showAlert('Network error. Please check your connection and try again.', 'danger');
        };
        
        xhr.send(formData);
    });
    
    // Handle "Add Another" button click
    addAnotherBtn.addEventListener('click', function() {
        // Hide "Add Another" button and show submit button
        addAnotherBtn.classList.add('d-none');
        submitBtn.classList.remove('d-none');
        
        // Clear any success messages
        const alerts = alertMessages.getElementsByClassName('alert');
        while (alerts[0]) {
            alerts[0].parentNode.removeChild(alerts[0]);
        }
    });
    
    // Function to show alert messages
    function showAlert(message, type = 'success') {
        // Clear previous alerts
        alertMessages.innerHTML = '';
        
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        alertMessages.appendChild(alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alertDiv);
            bsAlert.close();
        }, 5000);
    }
    
    // Function to clear validation errors
    function clearValidationErrors() {
        // Remove error classes from inputs
        const invalidInputs = form.querySelectorAll('.is-invalid');
        invalidInputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
        
        // Remove error messages
        const errorMessages = form.querySelectorAll('.invalid-feedback');
        errorMessages.forEach(msg => {
            msg.remove();
        });
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
