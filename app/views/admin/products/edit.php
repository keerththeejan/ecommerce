<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Edit product form: standard order, responsive */
.edit-product-form .form-label { font-weight: 600; font-size: 0.84rem; margin-bottom: 0.35rem; }
.edit-product-form .form-control,
.edit-product-form .form-select { border-radius: 10px; }
.edit-product-form .input-group-text { border-radius: 10px; }
.edit-product-form .input-group .form-select { flex: 1 1 auto; min-width: 0; }
.edit-product-form .card { border-radius: 14px; border: 1px solid rgba(17,24,39,.08); }
.edit-product-form .card-header { padding: 0.65rem 0.9rem; }
.edit-product-form .card-body { padding: 0.9rem; }
.edit-product-form .form-text { font-size: 0.78rem; }
.edit-product-form .input-group-sm > .form-control,
.edit-product-form .input-group-sm > .input-group-text,
.edit-product-form .input-group-sm > .form-select { border-radius: 10px; }
.edit-product-form .section-title { font-size: 0.82rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; color: rgba(17,24,39,.62); }
.edit-product-form .field-grid { row-gap: 0.75rem; }
.edit-product-form .is-required::after { content: "*"; margin-left: 4px; color: #dc3545; }
.edit-product-form .summary-kpi { font-variant-numeric: tabular-nums; }
.edit-product-form .summary-label { font-size: 0.72rem; color: rgba(17,24,39,.62); }
.edit-product-form .summary-value { font-size: 1.05rem; font-weight: 800; }

.media-drop {
    border: 1px dashed rgba(17,24,39,.18);
    border-radius: 14px;
    padding: 0.9rem;
    background: rgba(17,24,39,.02);
    cursor: pointer;
}
.media-drop.is-dragover { background: rgba(13, 110, 253, 0.06); border-color: rgba(13, 110, 253, 0.35); }
.media-preview {
    width: 100%;
    height: 220px;
    border-radius: 12px;
    object-fit: contain;
    background: #fff;
    border: 1px solid rgba(17,24,39,.08);
}
.media-actions .btn { border-radius: 10px; }

.pm-actionbar {
    position: sticky;
    bottom: 0;
    z-index: 10;
    background: #fff;
    border-top: 1px solid rgba(0,0,0,.125);
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    margin-top: 1rem;
}

@media (max-width: 767.98px) {
    .edit-product-form .input-group > .btn { margin-top: 0.25rem; width: 100%; }
    .edit-product-form .row .col-6 { margin-bottom: 0.5rem; }
    .media-preview { height: 200px; }
}
.pm-select-add {
    display: flex;
    gap: 0.25rem;
    align-items: stretch;
    flex-wrap: nowrap;
}
.pm-select-add .select2-container {
    flex: 1 1 auto;
    min-width: 0;
    width: auto !important;
}
.pm-select-add > .btn {
    flex: 0 0 auto;
}
@media (max-width: 575.98px) {
    .pm-select-add {
        flex-wrap: wrap;
    }
    .pm-select-add .select2-container {
        flex: 1 1 100%;
    }
    .pm-select-add > .btn {
        width: 100%;
    }
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
                    <span class="badge badge-light" style="border-radius: 999px; padding: 0.35rem 0.6rem;" id="statusPill">
                        <span class="status-dot" style="display:inline-block;width:8px;height:8px;border-radius:999px;margin-right:6px;vertical-align:middle;"></span>
                        <span class="status-text"></span>
                    </span>
                </div>
                <div class="card-body">
                    <form id="editProductForm" class="edit-product-form" action="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo $product['id']; ?>" method="POST" enctype="multipart/form-data" novalidate>
                        <!-- Standard order: 1.Name 2.Description 3.Image 4.SKU 5.Category 6.Brand 7.Country 8.Prices 9.Stock 10.Expiry 11.Supplier 12.Batch 13.Status -->
                        <input type="hidden" name="status" id="statusInput" value="<?php echo $product['status']; ?>">
                        <div class="row g-3 g-lg-4">
                            <div class="col-12 col-lg-8">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-header bg-white">
                                        <div class="section-title">Product Information</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row field-grid">
                                            <div class="col-12">
                                                <label for="name" class="form-label is-required">Product Name</label>
                                                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                                                <?php if(isset($errors['name'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12">
                                                <label for="description" class="form-label">Description</label>
                                                <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                                                <?php if(isset($errors['description'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['description']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label for="category_id" class="form-label is-required">Category</label>
                                                <select class="form-select select2 <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required>
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
                                            <div class="col-12 col-md-6">
                                                <label for="brand_id" class="form-label">Brand</label>
                                                <div class="pm-select-add">
                                                    <select class="form-select select2 flex-grow-1 <?php echo isset($errors['brand_id']) ? 'is-invalid' : ''; ?>" id="brand_id" name="brand_id" style="min-width: 0;">
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
                                            <div class="col-12 col-md-6">
                                                <label for="sku" class="form-label is-required">SKU</label>
                                                <input type="text" class="form-control <?php echo isset($errors['sku']) ? 'is-invalid' : ''; ?>" id="sku" name="sku" value="<?php echo htmlspecialchars($product['sku']); ?>" required>
                                                <?php if(isset($errors['sku'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['sku']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label for="hsn_code" class="form-label">HSN Code</label>
                                                <input type="text" class="form-control <?php echo isset($errors['hsn_code']) ? 'is-invalid' : ''; ?>" id="hsn_code" name="hsn_code" value="<?php echo isset($product['hsn_code']) ? htmlspecialchars($product['hsn_code']) : ''; ?>" maxlength="50">
                                                <?php if(isset($errors['hsn_code'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['hsn_code']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label for="country_id" class="form-label">Country of Origin</label>
                                                <div class="pm-select-add">
                                                    <select class="form-select select2 flex-grow-1 <?php echo isset($errors['country_id']) ? 'is-invalid' : ''; ?>" id="country_id" name="country_id" style="min-width: 0;">
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
                                </div>

                                <div class="card shadow-sm mb-3">
                                    <div class="card-header bg-white">
                                        <div class="section-title">Pricing</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row field-grid">
                                            <div class="col-12 col-md-4">
                                                <label for="price" class="form-label">Buying Price</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">CHF</span>
                                                    <input type="text" class="form-control form-control-sm <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" id="price" name="price" value="<?php echo $product['price']; ?>" inputmode="decimal" autocomplete="off">
                                                </div>
                                                <?php if(isset($errors['price'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label for="sale_price" class="form-label">Including Tax Price</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">CHF</span>
                                                    <input type="text" class="form-control form-control-sm" id="sale_price" name="sale_price" value="<?php echo $product['sale_price'] ?? ''; ?>" inputmode="decimal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label for="price2" class="form-label">Sales Price</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">CHF</span>
                                                    <input type="text" class="form-control form-control-sm" id="price2" name="price2" value="<?php echo $product['price2'] ?? $product['price']; ?>" inputmode="decimal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label for="price3" class="form-label">Wholesale Price</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">CHF</span>
                                                    <input type="text" class="form-control form-control-sm" id="price3" name="price3" value="<?php echo $product['price3'] ?? $product['price']; ?>" inputmode="decimal" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label for="customs_charge" class="form-label">Customs Charge</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">CHF</span>
                                                    <input type="text" class="form-control form-control-sm <?php echo isset($errors['customs_charge']) ? 'is-invalid' : ''; ?>" id="customs_charge" name="customs_charge" value="<?php echo isset($product['customs_charge']) ? htmlspecialchars($product['customs_charge']) : ''; ?>" inputmode="decimal" autocomplete="off">
                                                </div>
                                                <?php if(isset($errors['customs_charge'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['customs_charge']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label for="transport_charge" class="form-label">Transport Charge</label>
                                                <div class="input-group input-group-sm">
                                                    <span class="input-group-text">CHF</span>
                                                    <input type="text" class="form-control form-control-sm <?php echo isset($errors['transport_charge']) ? 'is-invalid' : ''; ?>" id="transport_charge" name="transport_charge" value="<?php echo isset($product['transport_charge']) ? htmlspecialchars($product['transport_charge']) : ''; ?>" inputmode="decimal" autocomplete="off">
                                                </div>
                                                <?php if(isset($errors['transport_charge'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['transport_charge']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12">
                                                <label for="tax_id" class="form-label">Tax Rate</label>
                                                <select class="form-select select2 <?php echo isset($errors['tax_id']) ? 'is-invalid' : ''; ?>" id="tax_id" name="tax_id">
                                                    <option value="" data-rate="0">Use category tax / None</option>
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
                                                        <option value="<?php echo htmlspecialchars($tid); ?>" data-rate="<?php echo htmlspecialchars((string)$trate); ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($label); ?></option>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </select>
                                                <div class="form-text">Leave empty to use category tax.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card shadow-sm mb-0">
                                    <div class="card-header bg-white">
                                        <div class="section-title">Inventory</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row field-grid">
                                            <div class="col-12 col-md-6">
                                                <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                                <input type="number" class="form-control <?php echo isset($errors['stock_quantity']) ? 'is-invalid' : ''; ?>" id="stock_quantity" name="stock_quantity" value="<?php echo (int)$product['stock_quantity']; ?>" min="0">
                                                <?php if(isset($errors['stock_quantity'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['stock_quantity']; ?></div>
                                                <?php endif; ?>
                                                <div class="form-text">Use whole numbers. Minimum: 0.</div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label for="supplier" class="form-label">Supplier</label>
                                                <select class="form-select select2 <?php echo isset($errors['supplier']) ? 'is-invalid' : ''; ?>" id="supplier" name="supplier">
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
                                            <div class="col-12 col-md-6">
                                                <label for="batch_number" class="form-label">Batch Number</label>
                                                <input type="text" class="form-control <?php echo isset($errors['batch_number']) ? 'is-invalid' : ''; ?>" id="batch_number" name="batch_number" value="<?php echo isset($product['batch_number']) ? htmlspecialchars($product['batch_number']) : ''; ?>" maxlength="100">
                                                <?php if(isset($errors['batch_number'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['batch_number']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                                <input type="date" class="form-control <?php echo isset($errors['expiry_date']) ? 'is-invalid' : ''; ?>" id="expiry_date" name="expiry_date" value="<?php echo isset($product['expiry_date']) ? htmlspecialchars($product['expiry_date']) : ''; ?>">
                                                <?php if(isset($errors['expiry_date'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['expiry_date']; ?></div>
                                                <?php endif; ?>
                                                <div class="form-text">Set only if the product has an expiry date.</div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Status</label>
                                                <div class="d-flex align-items-center" style="gap: 0.75rem;">
                                                    <div class="form-check form-switch mb-0">
                                                        <input class="form-check-input" type="checkbox" id="statusToggle" <?php echo $product['status'] === 'active' ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="statusToggle" id="statusLabel"><?php echo $product['status'] === 'active' ? 'Active' : 'Inactive'; ?></label>
                                                    </div>
                                                    <select class="form-select" id="status" name="status" style="max-width: 220px;">
                                                        <option value="active" <?php echo ($product['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                                        <option value="inactive" <?php echo ($product['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-header bg-white">
                                        <div class="section-title">Media</div>
                                    </div>
                                    <div class="card-body">
                                        <div id="mediaDrop" class="media-drop" tabindex="0">
                                            <div class="mb-2">
                                                <img
                                                    id="imagePreview"
                                                    class="media-preview"
                                                    src="<?php echo !empty($product['image']) ? (BASE_URL . $product['image']) : ''; ?>"
                                                    alt="Product Image"
                                                    style="<?php echo !empty($product['image']) ? '' : 'display:none;'; ?>"
                                                />
                                                <div id="imagePreviewIcon" class="text-center text-muted" style="padding: 2.25rem 0; <?php echo !empty($product['image']) ? 'display:none;' : ''; ?>">
                                                    <i class="fas fa-image" style="font-size: 1.75rem;"></i>
                                                    <div class="mt-2">Drop image here or click to upload</div>
                                                </div>
                                            </div>
                                            <div class="d-flex media-actions" style="gap: 0.5rem;">
                                                <button type="button" class="btn btn-outline-primary btn-sm" id="chooseImageBtn"><i class="fas fa-upload mr-1"></i>Choose</button>
                                                <button type="button" class="btn btn-outline-secondary btn-sm" id="removeImageBtn" <?php echo empty($product['image']) ? 'disabled' : ''; ?>><i class="fas fa-times mr-1"></i>Remove</button>
                                            </div>
                                            <input type="file" class="d-none <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image" accept="image/*">
                                            <?php if(isset($errors['image'])): ?>
                                                <div class="invalid-feedback d-block"><?php echo $errors['image']; ?></div>
                                            <?php endif; ?>
                                            <div class="form-text mt-2">Recommended: 230×250 px. Leave empty to keep current image.</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card shadow-sm mb-0">
                                    <div class="card-header bg-white">
                                        <div class="section-title">Pricing Summary</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 mb-2">
                                                <div class="summary-label">Total Cost</div>
                                                <div class="summary-value summary-kpi" id="sumTotalCost">CHF 0.00</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="summary-label">Profit</div>
                                                <div class="summary-value summary-kpi" id="sumProfit">CHF 0.00</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="summary-label">Margin</div>
                                                <div class="summary-value summary-kpi" id="sumMargin">0%</div>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="form-text">Calculated from Buying Price + Tax + Customs + Transport. Profit uses Sales Price.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="pm-actionbar">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2">
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-secondary order-sm-1">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Products
                                </a>
                                <div class="d-flex flex-wrap gap-2 order-sm-2">
                                    <button type="button" id="cancelChangesBtn" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i> Cancel
                                    </button>
                                    <button type="submit" id="submitBtn" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update Product
                                    </button>
                                </div>
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
    const imagePreview = document.getElementById('imagePreview');
    const mediaDrop = document.getElementById('mediaDrop');
    const chooseImageBtn = document.getElementById('chooseImageBtn');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const imagePreviewIcon = document.getElementById('imagePreviewIcon');
    const originalFormData = form ? new FormData(form) : null;
    
    // Toggle product status
    if (statusToggle) {
        statusToggle.addEventListener('change', function() {
            const status = this.checked ? 'active' : 'inactive';
            statusInput.value = status;
            statusLabel.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            const statusSelect = document.getElementById('status');
            if (statusSelect) statusSelect.value = status;
        });
    }

    function setStatusPill(isActive) {
        const pill = document.getElementById('statusPill');
        if (!pill) return;
        const dot = pill.querySelector('.status-dot');
        const textEl = pill.querySelector('.status-text');
        if (dot) dot.style.background = isActive ? '#10b981' : '#f59e0b';
        if (textEl) textEl.textContent = isActive ? 'Active' : 'Inactive';
        pill.style.background = isActive ? 'rgba(16,185,129,0.10)' : 'rgba(245,158,11,0.12)';
        pill.style.borderColor = isActive ? 'rgba(16,185,129,0.18)' : 'rgba(245,158,11,0.20)';
        pill.style.color = isActive ? '#065f46' : '#92400e';
    }

    if (statusToggle) {
        setStatusPill(!!statusToggle.checked);
        statusToggle.addEventListener('change', function() {
            setStatusPill(!!statusToggle.checked);
        });
    }

    const statusSelect = document.getElementById('status');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const val = this.value === 'active';
            if (statusToggle) statusToggle.checked = val;
            if (statusInput) statusInput.value = val ? 'active' : 'inactive';
            if (statusLabel) statusLabel.textContent = val ? 'Active' : 'Inactive';
            setStatusPill(val);
        });
    }
    
    function setImagePreview(file) {
        if (!file) {
            if (imagePreview) {
                imagePreview.src = '';
                imagePreview.style.display = 'none';
            }
            if (imagePreviewIcon) imagePreviewIcon.style.display = '';
            if (removeImageBtn) removeImageBtn.disabled = true;
            return;
        }

        const url = URL.createObjectURL(file);
        if (imagePreview) {
            imagePreview.src = url;
            imagePreview.style.display = '';
        }
        if (imagePreviewIcon) imagePreviewIcon.style.display = 'none';
        if (removeImageBtn) removeImageBtn.disabled = false;
    }

    function resetImageUI() {
        if (imageInput) imageInput.value = '';
        setImagePreview(null);
    }

    function openImagePicker() {
        if (imageInput) imageInput.click();
    }

    if (mediaDrop) {
        mediaDrop.addEventListener('click', function(e) {
            if (e.target && (e.target.id === 'removeImageBtn' || (e.target.closest && e.target.closest('#removeImageBtn')))) {
                return;
            }
            openImagePicker();
        });
        mediaDrop.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openImagePicker();
            }
        });

        ['dragenter', 'dragover'].forEach(evt => {
            mediaDrop.addEventListener(evt, function(e) {
                e.preventDefault();
                e.stopPropagation();
                mediaDrop.classList.add('is-dragover');
            });
        });
        ['dragleave', 'drop'].forEach(evt => {
            mediaDrop.addEventListener(evt, function(e) {
                e.preventDefault();
                e.stopPropagation();
                mediaDrop.classList.remove('is-dragover');
            });
        });
        mediaDrop.addEventListener('drop', function(e) {
            const file = e.dataTransfer && e.dataTransfer.files ? e.dataTransfer.files[0] : null;
            if (!file) return;
            if (imageInput) {
                imageInput.files = e.dataTransfer.files;
            }
            setImagePreview(file);
        });
    }

    if (chooseImageBtn) chooseImageBtn.addEventListener('click', openImagePicker);
    if (removeImageBtn) removeImageBtn.addEventListener('click', function(e) {
        e.preventDefault();
        resetImageUI();
    });
    if (imageInput) imageInput.addEventListener('change', function() {
        const file = imageInput.files && imageInput.files[0] ? imageInput.files[0] : null;
        setImagePreview(file);
    });
    
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
                    imagePreview.style.display = '<?php echo !empty($product['image']) ? '' : 'none'; ?>';
                }
                if (imagePreviewIcon) imagePreviewIcon.style.display = '<?php echo !empty($product['image']) ? 'none' : ''; ?>';

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
                const text = await response.text();
                let data = null;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    const preview = (text || '').substring(0, 220).replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
                    if (!response.ok) {
                        throw new Error(preview ? `Request failed (${response.status}): ${preview}` : `Request failed (${response.status})`);
                    }
                    throw new Error(preview ? `Server returned an invalid response: ${preview}` : 'Server returned an invalid response');
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
                        imagePreview.style.display = '';
                        if (imagePreviewIcon) imagePreviewIcon.style.display = 'none';
                        if (removeImageBtn) removeImageBtn.disabled = false;
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

                    const customsEl = document.getElementById('customs_charge');
                    const transportEl = document.getElementById('transport_charge');

                    if (nameEl) nameEl.value = data.data.name ?? nameEl.value;
                    if (descEl) descEl.value = data.data.description ?? descEl.value;
                    if (priceEl) priceEl.value = data.data.price ?? priceEl.value;
                    if (saleEl) saleEl.value = data.data.sale_price ?? '';
                    if (price2El) price2El.value = (data.data.price2 ?? data.data.price) ?? price2El.value;
                    if (price3El) price3El.value = (data.data.price3 ?? data.data.price) ?? price3El.value;
                    if (stockEl) stockEl.value = data.data.stock_quantity ?? stockEl.value;
                    if (skuEl && data.data.sku) skuEl.value = data.data.sku;
                    if (categoryEl && data.data.category_id) categoryEl.value = data.data.category_id;

                    if (customsEl) customsEl.value = (data.data.customs_charge ?? '') === null ? '' : (data.data.customs_charge ?? customsEl.value);
                    if (transportEl) transportEl.value = (data.data.transport_charge ?? '') === null ? '' : (data.data.transport_charge ?? transportEl.value);

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

    function toNum(v) {
        const s = (v ?? '').toString().replace(/[^0-9.\-]/g, '');
        const n = parseFloat(s);
        return isNaN(n) ? 0 : n;
    }

    function fmtCHF(n) {
        const x = (isNaN(n) ? 0 : n);
        return 'CHF ' + x.toFixed(2);
    }

    function getTaxRate() {
        const tax = document.getElementById('tax_id');
        if (!tax) return 0;
        const opt = tax.options && tax.selectedIndex >= 0 ? tax.options[tax.selectedIndex] : null;
        const rateRaw = opt ? (opt.getAttribute('data-rate') || '0') : '0';
        const r = parseFloat(rateRaw);
        return isNaN(r) ? 0 : r;
    }

    function updateSummary() {
        const buying = toNum(document.getElementById('price') ? document.getElementById('price').value : 0);
        const customs = toNum(document.getElementById('customs_charge') ? document.getElementById('customs_charge').value : 0);
        const transport = toNum(document.getElementById('transport_charge') ? document.getElementById('transport_charge').value : 0);
        const sales = toNum(document.getElementById('price2') ? document.getElementById('price2').value : 0);
        const rate = getTaxRate();
        const taxAmount = buying * (rate / 100);
        const totalCost = buying + customs + transport + taxAmount;
        const profit = sales - totalCost;
        const margin = sales > 0 ? (profit / sales) * 100 : 0;

        const totalEl = document.getElementById('sumTotalCost');
        const profitEl = document.getElementById('sumProfit');
        const marginEl = document.getElementById('sumMargin');
        if (totalEl) totalEl.textContent = fmtCHF(totalCost);
        if (profitEl) profitEl.textContent = fmtCHF(profit);
        if (marginEl) marginEl.textContent = (isNaN(margin) ? '0%' : (margin.toFixed(1) + '%'));
    }

    ['price','customs_charge','transport_charge','price2','tax_id'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', updateSummary);
            el.addEventListener('change', updateSummary);
        }
    });
    updateSummary();

    try {
        if (window.jQuery && $.fn && $.fn.select2) {
            $('.select2').select2({ width: '100%' });
        }
    } catch (e) {
        // ignore
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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        `;
        
        // Add to container
        alertMessages.insertBefore(alertDiv, alertMessages.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            try { $(alertDiv).alert('close'); } catch (e) { /* ignore */ }
        }, 5000);
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
