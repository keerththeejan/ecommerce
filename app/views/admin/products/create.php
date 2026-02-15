<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Create product form – responsive */
.create-product-form .form-label { font-weight: 500; }
.create-product-form .input-group select.form-control { flex: 1 1 auto; min-width: 0; }
@media (max-width: 767.98px) {
    .create-product-form .input-group > .btn { margin-top: 0.25rem; width: 100%; }
    .create-product-form .row .col-6 { margin-bottom: 0.5rem; }
}
.create-product-form select[style*="width"] { min-width: 0 !important; max-width: 100%; }
</style>

<div class="container-fluid px-2 px-sm-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 py-3">
                    <h3 class="card-title mb-0 h5">Add New Product</h3>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-light btn-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Products
                    </a>
                </div>
                <div class="card-body p-3 p-md-4">
                    <div id="alert-messages">
                        <?php if(isset($success)): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($errors['db_error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $errors['db_error']; ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <form id="productForm" class="create-product-form" action="<?php echo BASE_URL; ?>?controller=product&action=create" method="POST" enctype="multipart/form-data" novalidate>
                        <div class="row">
                            <div class="col-12 col-lg-8">
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
                                    <div class="d-flex flex-wrap gap-1">
                                        <select class="form-control select2 flex-grow-1 <?php echo isset($errors['country_id']) ? 'is-invalid' : ''; ?>" id="country_id" name="country_id" required style="min-width: 0;">
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
                                        <a href="<?php echo BASE_URL; ?>?controller=country&action=adminIndex" class="btn btn-outline-primary" type="button"><i class="fas fa-plus"></i></a>
                                        <?php if(isset($errors['country_id'])): ?>
                                            <div class="invalid-feedback d-block w-100"><?php echo $errors['country_id']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Brand Selection -->
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <div class="d-flex flex-wrap gap-1">
                                        <select class="form-control select2 flex-grow-1 <?php echo isset($errors['brand_id']) ? 'is-invalid' : ''; ?>" id="brand_id" name="brand_id" required style="min-width: 0;">
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
                                        <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" class="btn btn-outline-primary" type="button"><i class="fas fa-plus"></i></a>
                                        <?php if(isset($errors['brand_id'])): ?>
                                            <div class="invalid-feedback d-block w-100"><?php echo $errors['brand_id']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Buying Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">CHF</span>
                                                <input type="number" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" id="price" name="price" value="<?php echo $data['price'] ?? ''; ?>" step="0.01" min="0" required>
                                                <?php if(isset($errors['price'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="price2" class="form-label">Sales Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">CHF</span>
                                                <input type="number" class="form-control <?php echo isset($errors['price2']) ? 'is-invalid' : ''; ?>" id="price2" name="price2" value="<?php echo $data['price2'] ?? ''; ?>" step="0.01" min="0" required>
                                                <?php if(isset($errors['price2'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['price2']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="sale_price" class="form-label">Including Tax Price (Optional)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">CHF</span>
                                                <input type="number" class="form-control <?php echo isset($errors['sale_price']) ? 'is-invalid' : ''; ?>" id="sale_price" name="sale_price" value="<?php echo $data['sale_price'] ?? ''; ?>" step="0.01" min="0">
                                                <?php if(isset($errors['sale_price'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['sale_price']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="mb-3">
                                            <label for="price3" class="form-label">Wholesale Price (SP) (Optional)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">CHF</span>
                                                <input type="number" class="form-control <?php echo isset($errors['price3']) ? 'is-invalid' : ''; ?>" id="price3" name="price3" value="<?php echo $data['price3'] ?? ''; ?>" step="0.01" min="0">
                                                <?php if(isset($errors['price3'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['price3']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12 col-lg-4">
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
                                    <div class="input-group">
                                        <input type="text" class="form-control <?php echo isset($errors['sku']) ? 'is-invalid' : ''; ?>" id="sku" name="sku" value="<?php echo $data['sku'] ?? ''; ?>" placeholder="Leave blank to auto-generate on save">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary" id="generateSkuBtn" title="Generate SKU from product name">
                                                <i class="fas fa-magic mr-1"></i> Generate
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-text">Leave blank to auto-generate on save, or enter your own. Use "Generate" to preview one from the product name.</div>
                                    <?php if(isset($errors['sku'])): ?>
                                        <div class="invalid-feedback d-block"><?php echo $errors['sku']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control <?php echo isset($errors['expiry_date']) ? 'is-invalid' : ''; ?>" id="expiry_date" name="expiry_date" value="<?php echo $data['expiry_date'] ?? ''; ?>">
                                    <?php if(isset($errors['expiry_date'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['expiry_date']; ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-3">
                                    <label for="supplier" class="form-label">Supplier</label>
                                    <div class="d-flex flex-wrap gap-1">
                                        <select class="form-control select2 flex-grow-1 <?php echo isset($errors['supplier']) ? 'is-invalid' : ''; ?>" id="supplier" name="supplier" style="min-width: 0;">
                                            <option value="">Select Supplier</option>
                                            <?php if(!empty($suppliers)): ?>
                                                <?php foreach($suppliers as $supplier): ?>
                                                    <?php 
                                                        $value = htmlspecialchars($supplier['name']);
                                                        $selected = (isset($data['supplier']) && $data['supplier'] === $supplier['name']) ? 'selected' : '';
                                                    ?>
                                                    <option value="<?php echo $value; ?>" <?php echo $selected; ?>>
                                                        <?php echo htmlspecialchars($supplier['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <a href="<?php echo BASE_URL; ?>?controller=supplier&action=index" class="btn btn-outline-primary" type="button"><i class="fas fa-plus"></i></a>
                                        <?php if(isset($errors['supplier'])): ?>
                                            <div class="invalid-feedback d-block w-100"><?php echo $errors['supplier']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Category Selection -->
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <div class="d-flex flex-wrap gap-1">
                                        <select class="form-control select2 flex-grow-1 <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required style="min-width: 0;">
                                            <option value="">Select Category</option>
                                            <?php 
                                            // Get active categories
                                            $categoryModel = new Category();
                                            $categories = $categoryModel->getActiveCategories();
                                            
                                            if(!empty($categories)) :
                                                foreach($categories as $category) :
                                                    $selected = (isset($data['category_id']) && $data['category_id'] == $category['id']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $category['id']; ?>" <?php echo $selected; ?>><?php echo $category['name']; ?></option>
                                            <?php 
                                                endforeach;
                                            endif; 
                                            ?>
                                        </select>
                                        <a href="<?php echo BASE_URL; ?>?controller=category&action=create" class="btn btn-outline-primary" type="button"><i class="fas fa-plus"></i></a>
                                        <?php if(isset($errors['category_id'])): ?>
                                            <div class="invalid-feedback d-block w-100"><?php echo $errors['category_id']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Tax Rate Selection -->
                                <div class="mb-3">
                                    <label for="tax_id" class="form-label">Tax Rate (Optional)</label>
                                    <div class="d-flex flex-wrap gap-1">
                                        <select class="form-control select2 flex-grow-1 <?php echo isset($errors['tax_id']) ? 'is-invalid' : ''; ?>" id="tax_id" name="tax_id" style="min-width: 0;">
                                            <option value="">Use category tax / None</option>
                                            <?php
                                            $taxModel = new TaxModel();
                                            $taxRates = $taxModel->getTaxRates(true);
                                            if (!empty($taxRates)):
                                                foreach ($taxRates as $t):
                                                    $tid = is_object($t) ? $t->id : (isset($t['id']) ? $t['id'] : null);
                                                    $tname = is_object($t) ? $t->name : (isset($t['name']) ? $t['name'] : '');
                                                    $trate = is_object($t) ? $t->rate : (isset($t['rate']) ? $t['rate'] : '');
                                                    $label = trim($tname . ' (' . $trate . '%)');
                                                    $selected = (isset($data['tax_id']) && (string)($data['tax_id'] ?? '') === (string)$tid) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo htmlspecialchars($tid); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($label); ?></option>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                        <a href="<?php echo BASE_URL; ?>?controller=tax&action=index" class="btn btn-outline-primary" type="button"><i class="fas fa-plus"></i></a>
                                        <?php if(isset($errors['tax_id'])): ?>
                                            <div class="invalid-feedback d-block w-100"><?php echo $errors['tax_id']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-text small">Override category tax. Leave empty to use category's tax.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="batch_number" class="form-label">Batch Number</label>
                                    <input type="text" class="form-control <?php echo isset($errors['batch_number']) ? 'is-invalid' : ''; ?>" id="batch_number" name="batch_number" value="<?php echo $data['batch_number'] ?? ''; ?>" maxlength="100">
                                    <?php if(isset($errors['batch_number'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['batch_number']; ?></div>
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
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="active" <?php echo (isset($data['status']) && $data['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="inactive" <?php echo (isset($data['status']) && $data['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2 mt-4 pt-3 border-top">
                            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-secondary btn-sm order-sm-1">
                                <i class="fas fa-times mr-1"></i> Cancel
                            </a>
                            <div class="d-flex flex-wrap gap-2 order-sm-2">
                                <button type="submit" class="btn btn-primary btn-sm" id="submitBtn">
                                    <i class="fas fa-save mr-1"></i> Create Product
                                </button>
                                <button type="button" class="btn btn-success btn-sm d-none" id="addAnotherBtn" style="display: none;">
                                    <i class="fas fa-plus mr-1"></i> Add Another
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Style for flag images in dropdown */
.select2-container--default .select2-selection--single {
    height: 38px;
    padding: 0.375rem 0.75rem;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-left: 0;
    line-height: 1.5;
}

.select2-container--default .select2-results__option {
    padding: 6px 12px;
    display: flex;
    align-items: center;
}

.select2-container--default .select2-results__option img {
    width: 24px;
    height: 18px;
    object-fit: cover;
    border: 1px solid #dee2e6;
    margin-right: 10px;
    border-radius: 2px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    display: flex;
    align-items: center;
}
</style>

<script>
// Format country with flag (only for country dropdown options that have flag-image)
function formatOption(option) {
    if (!option.id) { return option.text; }
    var flagImg = $(option.element).data('flag-image');
    if (flagImg) {
        return $('<div class="d-flex align-items-center">' +
            '<img src="' + flagImg + '" class="mr-2" style="width: 24px; height: 18px; object-fit: cover; border: 1px solid #dee2e6; border-radius: 2px;">' +
            '<span>' + option.text + '</span></div>');
    }
    return option.text;
}

document.addEventListener('DOMContentLoaded', function() {
    // Generate SKU button: build SKU from product name (same logic as server)
    document.getElementById('generateSkuBtn').addEventListener('click', function() {
        var name = (document.getElementById('name').value || '').trim();
        var base = name ? name.replace(/[^A-Za-z0-9]/g, '').toUpperCase().substring(0, 10) : 'SKU';
        if (!base) base = 'SKU';
        var suffix = (Date.now() % 100000).toString();
        document.getElementById('sku').value = base + suffix;
    });
    
    // Initialize Select2 for ALL dropdowns (Country, Brand, Category, Supplier, Status) - searchable
    $('.select2').select2({
        theme: 'default',
        placeholder: 'Search...',
        allowClear: false,
        templateResult: formatOption,
        templateSelection: formatOption,
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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        `;
        
        alertMessages.appendChild(alertDiv);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            $(alertDiv).alert('close');
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

<!-- Add Country Modal -->
<div class="modal fade" id="addCountryModal" tabindex="-1" aria-labelledby="addCountryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addCountryModalLabel">Add New Country</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="addCountryForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="country_name" class="form-label">Country Name</label>
                            <input type="text" class="form-control" id="country_name" name="name" required>
                        </div>
                        <div class="col-12">
                            <label for="flag_image" class="form-label">Flag Image</label>
                            <div class="input-group">
                                <input type="file" class="form-control" id="flag_image" name="flag_image" accept="image/*" onchange="previewFlagImage(this)">
                                <div class="input-group-text p-0 overflow-hidden" style="width: 40px;">
                                    <img src="https://flagcdn.com/24x18/xx.png" 
                                         alt="No Flag" 
                                         id="flagPreview"
                                         class="img-fluid"
                                         style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <button type="button" class="btn btn-outline-secondary" 
                                        onclick="document.getElementById('flag_image').value = ''; document.getElementById('flagPreview').src = 'https://flagcdn.com/24x18/xx.png';"
                                        title="Remove Flag">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="form-text">Upload a flag image or leave blank to use default flag</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Save Country
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBrandModalLabel">Add New Brand</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addBrandForm">
                    <div class="mb-3">
                        <label for="brand_name" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="brand_description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="brand_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Brand</button>
                    </div>
                </form>
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
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addSupplierForm">
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label">Supplier Name</label>
                        <input type="text" class="form-control" id="supplier_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_contact" class="form-label">Contact Person (Optional)</label>
                        <input type="text" class="form-control" id="supplier_contact" name="contact_person">
                    </div>
                    <div class="mb-3">
                        <label for="supplier_email" class="form-label">Email (Optional)</label>
                        <input type="email" class="form-control" id="supplier_email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="supplier_phone" class="form-label">Phone (Optional)</label>
                        <input type="tel" class="form-control" id="supplier_phone" name="phone">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="parent_category" class="form-label">Parent Category (Optional)</label>
                        <select class="form-control" id="parent_category" name="parent_id">
                            <option value="">No Parent (Top Level)</option>
                            <?php 
                            $categoryModel = new Category();
                            $categories = $categoryModel->getAllCategories();
                            
                            if(!empty($categories)) :
                                foreach($categories as $category) :
                                    echo '<option value="' . $category['id'] . '">' . htmlspecialchars($category['name']) . '</option>';
                                endforeach;
                            endif; 
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="category_description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control" id="category_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// AJAX handling for adding new country
$('#addCountryForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>?controller=country&action=create',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success) {
                    // Add new option to select
                    var newOption = new Option(data.name, data.id, true, true);
                    $('#country_id').append(newOption).trigger('change');
                    
                    // Close modal and reset form
                    $('#addCountryModal').modal('hide');
                    $('#addCountryForm')[0].reset();
                    
                    // Show success message
                    showAlert('success', 'Country added successfully!');
                } else {
                    showAlert('danger', data.message || 'Failed to add country');
                }
            } catch (e) {
                showAlert('danger', 'Error processing response');
            }
        },
        error: function() {
            showAlert('danger', 'An error occurred while adding the country');
        }
    });
});

// AJAX handling for adding new brand
$('#addBrandForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>?controller=brand&action=create',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success) {
                    // Add new option to select
                    var newOption = new Option(data.name, data.id, true, true);
                    $('#brand_id').append(newOption).trigger('change');
                    
                    // Close modal and reset form
                    $('#addBrandModal').modal('hide');
                    $('#addBrandForm')[0].reset();
                    
                    // Show success message
                    showAlert('success', 'Brand added successfully!');
                } else {
                    showAlert('danger', data.message || 'Failed to add brand');
                }
            } catch (e) {
                showAlert('danger', 'Error processing response');
            }
        },
        error: function() {
            showAlert('danger', 'An error occurred while adding the brand');
        }
    });
});

// AJAX handling for adding new supplier
$('#addSupplierForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>?controller=supplier&action=create',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success) {
                    // Add new option to select
                    var newOption = new Option(data.name, data.name, true, true);
                    $('#supplier').append(newOption).trigger('change');
                    
                    // Close modal and reset form
                    $('#addSupplierModal').modal('hide');
                    $('#addSupplierForm')[0].reset();
                    
                    // Show success message
                    showAlert('success', 'Supplier added successfully!');
                } else {
                    showAlert('danger', data.message || 'Failed to add supplier');
                }
            } catch (e) {
                showAlert('danger', 'Error processing response');
            }
        },
        error: function() {
            showAlert('danger', 'An error occurred while adding the supplier');
        }
    });
});

// AJAX handling for adding new category
$('#addCategoryForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: '<?php echo BASE_URL; ?>?controller=category&action=create',
        type: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if (data.success) {
                    // Add new option to select if it's a top-level category
                    if (!data.parent_id) {
                        var newOption = new Option(data.name, data.id, true, true);
                        $('#category_id').append(newOption).trigger('change');
                    }
                    
                    // Close modal and reset form
                    $('#addCategoryModal').modal('hide');
                    $('#addCategoryForm')[0].reset();
                    
                    // Show success message
                    showAlert('success', 'Category added successfully!');
                } else {
                    showAlert('danger', data.message || 'Failed to add category');
                }
            } catch (e) {
                showAlert('danger', 'Error processing response');
            }
        },
        error: function() {
            showAlert('danger', 'An error occurred while adding the category');
        }
    });
});

// Helper function to show alerts
function showAlert(type, message) {
    var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                    '<i class="fas ' + (type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle') + ' mr-2"></i> ' + message +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '</div>';
    
    $('#alert-messages').append(alertHtml);
    
    // Auto-remove alert after 5 seconds
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
}
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
