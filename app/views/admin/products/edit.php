<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Edit product form: standard order, responsive */
.edit-product-form .form-label { font-weight: 500; }
.edit-product-form .input-group .form-select { flex: 1 1 auto; min-width: 0; }
@media (max-width: 767.98px) {
    .edit-product-form .input-group > .btn { margin-top: 0.25rem; width: 100%; }
    .edit-product-form .row .col-6 { margin-bottom: 0.5rem; }
}
</style>

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
                    <form id="editProductForm" class="edit-product-form" action="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data" novalidate>
                        <!-- Standard order: 1.Name 2.Description 3.Image 4.SKU 5.Category 6.Brand 7.Country 8.Prices 9.Stock 10.Expiry 11.Supplier 12.Batch 13.Status -->
                        <div class="row">
                            <div class="col-12 col-lg-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                    <?php if(isset($errors['name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                                    <?php if(isset($errors['description'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['description']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-lg-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Product Image</label>
                                    <?php if(!empty($product['image'])): ?>
                                        <div class="mb-2">
                                            <img src="<?php echo BASE_URL . $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image">
                                    <?php if(isset($errors['image'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['image']; ?></div>
                                    <?php endif; ?>
                                    <div class="form-text">Leave empty to keep current image. Recommended: 230×250 px</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control <?php echo isset($errors['sku']) ? 'is-invalid' : ''; ?>" id="sku" name="sku" value="<?php echo htmlspecialchars($product['sku']); ?>" required>
                                    <?php if(isset($errors['sku'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['sku']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select class="form-select <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo ($product['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if(isset($errors['category_id'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['category_id']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <div class="d-flex flex-wrap gap-1">
                                        <select class="form-select flex-grow-1 <?php echo isset($errors['brand_id']) ? 'is-invalid' : ''; ?>" id="brand_id" name="brand_id" required style="min-width: 0;">
                                            <option value="">Select Brand</option>
                                            <?php 
                                            $brandModel = new Brand();
                                            $brands = $brandModel->getActiveBrands();
                                            if(!empty($brands)) :
                                                foreach($brands as $brand) :
                                                    $selected = (isset($product['brand_id']) && (int)$product['brand_id'] === (int)$brand['id']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo (int)$brand['id']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($brand['name']); ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                        <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" class="btn btn-outline-primary" type="button"><i class="fas fa-plus"></i></a>
                                        <?php if(isset($errors['brand_id'])): ?>
                                            <div class="invalid-feedback d-block"><?php echo $errors['brand_id']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="tax_id" class="form-label">Tax Rate (Optional)</label>
                                    <select class="form-select <?php echo isset($errors['tax_id']) ? 'is-invalid' : ''; ?>" id="tax_id" name="tax_id">
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
                                                $selected = (isset($product['tax_id']) && (string)($product['tax_id'] ?? '') === (string)$tid) ? 'selected' : '';
                                        ?>
                                            <option value="<?php echo htmlspecialchars($tid); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($label); ?></option>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <div class="form-text small">Override category tax. Leave empty to use category's tax.</div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">Country of Origin</label>
                                    <div class="d-flex flex-wrap gap-1">
                                        <select class="form-select flex-grow-1 <?php echo isset($errors['country_id']) ? 'is-invalid' : ''; ?>" id="country_id" name="country_id" required style="min-width: 0;">
                                            <option value="">Select Country</option>
                                            <?php 
                                            $countryModel = new Country();
                                            $countries = $countryModel->getActiveCountries();
                                            if(!empty($countries)) :
                                                foreach($countries as $country) :
                                                    $selected = (isset($product['country_id']) && (int)$product['country_id'] === (int)$country['id']) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo (int)$country['id']; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($country['name']); ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                        <a href="<?php echo BASE_URL; ?>?controller=country&action=adminIndex" class="btn btn-outline-primary" type="button"><i class="fas fa-plus"></i></a>
                                        <?php if(isset($errors['country_id'])): ?>
                                            <div class="invalid-feedback d-block"><?php echo $errors['country_id']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 col-md-3">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Buying Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">CHF</span>
                                        <input type="number" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" id="price" name="price" value="<?php echo $product['price']; ?>" step="0.01" min="0" required>
                                    </div>
                                    <?php if(isset($errors['price'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">Incl. Tax (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">CHF</span>
                                        <input type="number" class="form-control" id="sale_price" name="sale_price" value="<?php echo $product['sale_price'] ?? ''; ?>" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="mb-3">
                                    <label for="price2" class="form-label">Sales Price (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">CHF</span>
                                        <input type="number" class="form-control" id="price2" name="price2" value="<?php echo $product['price2'] ?? $product['price']; ?>" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="mb-3">
                                    <label for="price3" class="form-label">Wholesale (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">CHF</span>
                                        <input type="number" class="form-control" id="price3" name="price3" value="<?php echo $product['price3'] ?? $product['price']; ?>" step="0.01" min="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control <?php echo isset($errors['stock_quantity']) ? 'is-invalid' : ''; ?>" id="stock_quantity" name="stock_quantity" value="<?php echo (int)$product['stock_quantity']; ?>" min="0" required>
                                    <?php if(isset($errors['stock_quantity'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['stock_quantity']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label for="expiry_date" class="form-label">Expiry Date</label>
                                    <input type="date" class="form-control <?php echo isset($errors['expiry_date']) ? 'is-invalid' : ''; ?>" id="expiry_date" name="expiry_date" value="<?php echo isset($product['expiry_date']) ? htmlspecialchars($product['expiry_date']) : ''; ?>">
                                    <?php if(isset($errors['expiry_date'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['expiry_date']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label for="supplier" class="form-label">Supplier</label>
                                    <select class="form-select <?php echo isset($errors['supplier']) ? 'is-invalid' : ''; ?>" id="supplier" name="supplier">
                                        <option value="">Select Supplier</option>
                                        <?php if(!empty($suppliers)): ?>
                                            <?php foreach($suppliers as $supplier): ?>
                                                <?php $value = htmlspecialchars($supplier['name']); $selected = (isset($product['supplier']) && $product['supplier'] === $supplier['name']) ? 'selected' : ''; ?>
                                                <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php if(isset($errors['supplier'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['supplier']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <div class="mb-3">
                                    <label for="batch_number" class="form-label">Batch Number</label>
                                    <input type="text" class="form-control <?php echo isset($errors['batch_number']) ? 'is-invalid' : ''; ?>" id="batch_number" name="batch_number" value="<?php echo isset($product['batch_number']) ? htmlspecialchars($product['batch_number']) : ''; ?>" maxlength="100">
                                    <?php if(isset($errors['batch_number'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['batch_number']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
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
            .then(async (response) => {
                const clone = response.clone();
                let data = null;
                try {
                    data = await response.json();
                } catch (_) {
                    // Try to get text body for debugging
                    try {
                        const text = await clone.text();
                        if (!response.ok) {
                            const snippet = text ? ` | Body: ${text.substring(0, 200)}` : '';
                            throw new Error(`Request failed (${response.status})${snippet}`);
                        }
                    } catch (_) {
                        // ignore
                    }
                }
                if (!response.ok) {
                    const message = data && data.message ? data.message : `Request failed (${response.status})`;
                    throw new Error(message);
                }
                return data;
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
                    
                    // Update form data from API response
                    const nameEl = document.getElementById('name');
                    const descEl = document.getElementById('description');
                    const priceEl = document.getElementById('price');
                    const saleEl = document.getElementById('sale_price');
                    const price2El = document.getElementById('price2');
                    const price3El = document.getElementById('price3');
                    const stockEl = document.getElementById('stock_quantity');
                    const skuEl = document.getElementById('sku');
                    const categoryEl = document.getElementById('category_id');

                    if (nameEl) nameEl.value = data.data.name ?? nameEl.value;
                    if (descEl) descEl.value = data.data.description ?? descEl.value;
                    if (priceEl) priceEl.value = data.data.price ?? priceEl.value;
                    if (saleEl) saleEl.value = data.data.sale_price ?? '';
                    if (price2El) price2El.value = (data.data.price2 ?? data.data.price) ?? price2El.value;
                    if (price3El) price3El.value = (data.data.price3 ?? data.data.price) ?? price3El.value;
                    if (stockEl) stockEl.value = data.data.stock_quantity ?? stockEl.value;
                    if (skuEl && data.data.sku) skuEl.value = data.data.sku;
                    if (categoryEl && data.data.category_id) categoryEl.value = data.data.category_id;

                    // New fields
                    const expiryEl = document.getElementById('expiry_date');
                    const supplierEl = document.getElementById('supplier');
                    const batchEl = document.getElementById('batch_number');
                    if (expiryEl) expiryEl.value = data.data.expiry_date || '';
                    if (supplierEl) supplierEl.value = data.data.supplier || '';
                    if (batchEl) batchEl.value = data.data.batch_number || '';
                    
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
