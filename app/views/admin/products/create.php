<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Create product form – responsive */
.admin-page-shell {
    background: var(--bg-color);
    min-height: calc(100vh - 56px);
}

.admin-page-header {
    position: sticky;
    top: 0;
    z-index: 50;
    background: color-mix(in srgb, var(--bg-color) 86%, transparent);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid var(--border-color);
}

.admin-page-header__inner {
    padding: 16px 0;
}

.admin-page-title {
    font-size: 18px;
    font-weight: 700;
    letter-spacing: -0.01em;
    margin: 0;
    color: var(--text-color);
}

.admin-page-subtitle {
    margin: 2px 0 0;
    color: var(--muted-color);
    font-size: 13px;
}

.admin-card {
    background: var(--surface-color);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    box-shadow: 0 1px 2px rgba(16,24,40,0.04);
}

.admin-card__header {
    padding: 12px 12px 0;
}

.admin-card__title {
    margin: 0;
    font-size: 14px;
    font-weight: 700;
    letter-spacing: -0.01em;
    color: var(--text-color);
}

.admin-card__hint {
    margin: 6px 0 0;
    font-size: 12px;
    color: var(--muted-color);
}

.admin-card__body {
    padding: 12px;
}

.create-product-form .form-label {
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 4px;
    font-size: 12px;
}

.create-product-form .form-group {
    margin-bottom: 12px;
}

.create-product-form .form-control,
.create-product-form .form-select {
    min-height: 40px;
    border-radius: 10px;
    border-color: var(--border-color);
}

@media (max-width: 767.98px) {
    .create-product-form .form-control,
    .create-product-form .form-select {
        min-height: 44px;
    }
}

.create-product-form textarea.form-control {
    min-height: 84px;
    padding-top: 12px;
    padding-bottom: 12px;
}

.create-product-form .input-group-text {
    border-radius: 10px;
    border-color: var(--border-color);
    background: var(--surface-muted);
    color: var(--muted-color);
    font-weight: 600;
}

.create-product-form .form-control:focus,
.create-product-form .form-select:focus {
    border-color: rgba(37,99,235,0.55);
    box-shadow: 0 0 0 0.2rem rgba(37,99,235,0.15);
}

.create-product-form .form-text,
.create-product-form .invalid-feedback {
    margin-top: 6px;
}

.create-product-form .required-asterisk {
    color: #dc2626;
    margin-left: 2px;
}

.create-product-form select[style*="width"] { min-width: 0 !important; max-width: 100%; }

.admin-btn-primary {
    background: #2563eb;
    border-color: #2563eb;
}

.admin-btn-primary:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
}

.admin-btn-soft {
    background: rgba(37,99,235,0.10);
    border-color: rgba(37,99,235,0.18);
    color: #1d4ed8;
}

.admin-btn-soft:hover {
    background: rgba(37,99,235,0.14);
    border-color: rgba(37,99,235,0.22);
    color: #1d4ed8;
}

.pm-select-add {
    display: flex;
    gap: 0.5rem;
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
    min-height: 40px;
    border-radius: 10px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
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
        justify-content: center;
    }
}

.pm-actionbar {
    position: sticky;
    bottom: 0;
    z-index: 40;
    background: color-mix(in srgb, var(--surface-color) 92%, transparent);
    backdrop-filter: blur(10px);
    border-top: 1px solid var(--border-color);
    padding: 10px 0;
    margin-top: 12px;
}

@media (max-width: 767.98px) {
    .pm-actionbar {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        margin-top: 0;
        padding-bottom: calc(12px + env(safe-area-inset-bottom));
    }
    .admin-page-shell {
        padding-bottom: 88px;
    }
}

.pm-actionbar .btn {
    min-height: 40px;
    border-radius: 10px;
    font-weight: 700;
}

.pm-actionbar .btn-link {
    font-weight: 700;
    color: var(--muted-color);
    text-decoration: none;
}

.pm-actionbar .btn-link:hover {
    color: var(--text-color);
    text-decoration: none;
}

.pm-actionbar .btn.btn-secondary {
    background: transparent;
    color: var(--text-color);
    border-color: var(--border-color);
}

.pm-actionbar .btn.btn-secondary:hover {
    background: var(--surface-muted);
}

.pm-media-drop {
    border: 1px dashed rgba(148,163,184,0.85);
    border-radius: 12px;
    background: var(--surface-muted);
    padding: 14px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    cursor: pointer;
}

.pm-media-drop:focus-within,
.pm-media-drop.is-dragover {
    border-color: rgba(37,99,235,0.70);
    box-shadow: 0 0 0 0.2rem rgba(37,99,235,0.12);
}

.pm-media-preview {
    width: 120px;
    height: 120px;
    border-radius: 12px;
    background: rgba(148,163,184,0.25);
    border: 1px solid rgba(148,163,184,0.35);
    overflow: hidden;
    flex: 0 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
}

.pm-media-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.pm-media-meta {
    flex: 1 1 auto;
    min-width: 0;
}

.pm-media-title {
    margin: 0;
    font-weight: 800;
    font-size: 13px;
    color: var(--text-color);
}

.pm-media-text {
    margin: 4px 0 0;
    color: var(--muted-color);
    font-size: 12px;
}

.pm-media-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 10px;
}

.pm-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(16,185,129,0.10);
    border: 1px solid rgba(16,185,129,0.18);
    color: #065f46;
    font-weight: 700;
    font-size: 12px;
}

@media (max-width: 767.98px) {
    .pm-media-preview { width: 110px; height: 110px; border-radius: 10px; }
}

/* Select2 (match 48px input height) */
.create-product-form .select2-container--default .select2-selection--single {
    height: 40px;
    border-radius: 10px;
    border-color: var(--border-color);
    display: flex;
    align-items: center;
    padding: 0 12px;
}
.create-product-form .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.2;
    padding-left: 0;
}
.create-product-form .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px;
}

@media (max-width: 767.98px) {
    .create-product-form .select2-container--default .select2-selection--single { height: 44px; }
    .create-product-form .select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px; }
}

.pm-section-title {
    font-size: 12px;
    font-weight: 800;
    letter-spacing: -0.01em;
    color: var(--text-color);
    margin: 0;
}

.pm-section-subtitle {
    font-size: 12px;
    color: var(--muted-color);
    margin: 4px 0 0;
}

.pm-divider {
    height: 1px;
    background: var(--border-color);
    margin: 10px 0;
}
</style>

<div class="admin-page-shell">
    <div class="admin-page-header">
        <div class="container-fluid px-3 px-lg-4">
            <div class="admin-page-header__inner d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
                <div>
                    <h1 class="admin-page-title">Add Product</h1>
                    <p class="admin-page-subtitle">Create a new product with pricing, inventory and media.</p>
                </div>
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary" style="min-height: 40px; border-radius: 10px;">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-3 px-lg-4 py-3 py-lg-4">
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

                    <form id="productForm" class="create-product-form" action="<?php echo BASE_URL; ?>?controller=product&action=create" method="POST" enctype="multipart/form-data" novalidate aria-label="Add product form">
                        <div class="row" style="row-gap: 12px;">
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="admin-card">
                                    <div class="admin-card__body">
                                        <h2 class="pm-section-title">Basic Info</h2>
                                        <p class="pm-section-subtitle">Core details customers see.</p>
                                        <div class="pm-divider"></div>
                                        <div class="form-group">
                                            <label for="name" class="form-label">Product Name<span class="required-asterisk">*</span></label>
                                            <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $data['name'] ?? ''; ?>" required aria-required="true">
                                            <?php if(isset($errors['name'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['name']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="3" aria-label="Product description"><?php echo $data['description'] ?? ''; ?></textarea>
                                            <?php if(isset($errors['description'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['description']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="category_id" class="form-label">Category<span class="required-asterisk">*</span></label>
                                            <div class="pm-select-add">
                                                <select class="form-select select2 flex-grow-1 <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required style="min-width: 0;" aria-required="true" aria-label="Category">
                                                    <option value="">Select Category</option>
                                                    <?php 
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
                                                <a href="<?php echo BASE_URL; ?>?controller=category&action=create" class="btn btn-outline-primary" type="button" aria-label="Add new category">
                                                    <i class="fas fa-plus"></i>
                                                    <span class="d-none d-sm-inline">Add New</span>
                                                </a>
                                                <?php if(isset($errors['category_id'])): ?>
                                                    <div class="invalid-feedback d-block w-100"><?php echo $errors['category_id']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label for="brand_id" class="form-label">Brand</label>
                                            <div class="pm-select-add">
                                                <select class="form-select select2 flex-grow-1 <?php echo isset($errors['brand_id']) ? 'is-invalid' : ''; ?>" id="brand_id" name="brand_id" style="min-width: 0;" aria-label="Brand">
                                                    <option value="">Select Brand</option>
                                                    <?php 
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
                                                <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" class="btn btn-outline-primary" type="button" aria-label="Add new brand">
                                                    <i class="fas fa-plus"></i>
                                                    <span class="d-none d-sm-inline">Add New</span>
                                                </a>
                                                <?php if(isset($errors['brand_id'])): ?>
                                                    <div class="invalid-feedback d-block w-100"><?php echo $errors['brand_id']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="admin-card">
                                    <div class="admin-card__body">
                                        <h2 class="pm-section-title">Pricing & Tax</h2>
                                        <p class="pm-section-subtitle">Set your selling price.</p>
                                        <div class="pm-divider"></div>

                                        <div class="form-group">
                                            <label for="price" class="form-label">Buying Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">CHF</span></div>
                                                <input type="text" class="form-control <?php echo isset($errors['price']) ? 'is-invalid' : ''; ?>" id="price" name="price" value="<?php echo $data['price'] ?? ''; ?>" inputmode="decimal" autocomplete="off" aria-label="Buying price">
                                                <?php if(isset($errors['price'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['price']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="price2" class="form-label">Sales Price<span class="required-asterisk">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">CHF</span></div>
                                                <input type="text" class="form-control <?php echo isset($errors['price2']) ? 'is-invalid' : ''; ?>" id="price2" name="price2" value="<?php echo $data['price2'] ?? ''; ?>" inputmode="decimal" autocomplete="off" required aria-required="true" aria-label="Sales price">
                                                <?php if(isset($errors['price2'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['price2']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="form-text" style="font-size: 12px;">Required</div>
                                        </div>

                                        <div class="form-group">
                                            <label for="price3" class="form-label">Wholesale Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend"><span class="input-group-text">CHF</span></div>
                                                <input type="text" class="form-control <?php echo isset($errors['price3']) ? 'is-invalid' : ''; ?>" id="price3" name="price3" value="<?php echo $data['price3'] ?? ''; ?>" inputmode="decimal" autocomplete="off" aria-label="Wholesale price">
                                                <?php if(isset($errors['price3'])): ?>
                                                    <div class="invalid-feedback"><?php echo $errors['price3']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label for="tax_id" class="form-label">Tax Rate</label>
                                            <div class="pm-select-add">
                                                <select class="form-select select2 flex-grow-1 <?php echo isset($errors['tax_id']) ? 'is-invalid' : ''; ?>" id="tax_id" name="tax_id" style="min-width: 0;" aria-label="Tax rate">
                                                    <option value="">None</option>
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
                                                <a href="<?php echo BASE_URL; ?>?controller=tax&action=index" class="btn btn-outline-primary" type="button" aria-label="Add new tax rate">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <?php if(isset($errors['tax_id'])): ?>
                                                    <div class="invalid-feedback d-block w-100"><?php echo $errors['tax_id']; ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-4">
                                <div class="admin-card">
                                    <div class="admin-card__body">
                                        <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
                                            <div>
                                                <h2 class="pm-section-title" style="margin-bottom: 0;">Inventory & Media</h2>
                                                <p class="pm-section-subtitle" style="margin-bottom: 0;">Stock, status, image.</p>
                                            </div>
                                            <span id="statusPill" class="pm-pill" aria-live="polite" style="padding: 6px 10px;">
                                                <span class="status-dot" style="width: 8px; height: 8px; border-radius: 999px; background: #10b981;"></span>
                                                <span class="status-text">Active</span>
                                            </span>
                                        </div>
                                        <div class="pm-divider"></div>

                                        <div class="form-group">
                                            <label for="sku" class="form-label">SKU</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control <?php echo isset($errors['sku']) ? 'is-invalid' : ''; ?>" id="sku" name="sku" value="<?php echo $data['sku'] ?? ''; ?>" placeholder="Auto-generate" aria-label="SKU">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-secondary" id="generateSkuBtn" title="Generate SKU from product name" style="border-radius: 10px; min-height: 40px;">
                                                        <i class="fas fa-wand-magic-sparkles mr-1"></i>Gen
                                                    </button>
                                                </div>
                                            </div>
                                            <?php if(isset($errors['sku'])): ?>
                                                <div class="invalid-feedback d-block"><?php echo $errors['sku']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="row" style="row-gap: 12px;">
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="stock_quantity" class="form-label">Stock Qty</label>
                                                    <input type="text" class="form-control <?php echo isset($errors['stock_quantity']) ? 'is-invalid' : ''; ?>" id="stock_quantity" name="stock_quantity" value="<?php echo $data['stock_quantity'] ?? ''; ?>" inputmode="numeric" autocomplete="off" aria-label="Stock quantity">
                                                    <?php if(isset($errors['stock_quantity'])): ?>
                                                        <div class="invalid-feedback"><?php echo $errors['stock_quantity']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <label for="expiry_date" class="form-label">Expiry</label>
                                                    <input type="date" class="form-control <?php echo isset($errors['expiry_date']) ? 'is-invalid' : ''; ?>" id="expiry_date" name="expiry_date" value="<?php echo $data['expiry_date'] ?? ''; ?>" aria-label="Expiry date">
                                                    <?php if(isset($errors['expiry_date'])): ?>
                                                        <div class="invalid-feedback"><?php echo $errors['expiry_date']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="d-flex align-items-center justify-content-between" style="gap: 12px;">
                                                <label class="form-label" style="margin-bottom: 0;" for="statusToggle">Status</label>
                                                <div class="custom-control custom-switch" style="margin: 0;">
                                                    <input type="checkbox" class="custom-control-input" id="statusToggle" aria-label="Product status" <?php echo (!isset($data['status']) || $data['status'] == 'active') ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label" for="statusToggle"></label>
                                                </div>
                                                <select class="form-select d-none" id="status" name="status" aria-hidden="true" tabindex="-1">
                                                    <option value="active" <?php echo (isset($data['status']) && $data['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                                    <option value="inactive" <?php echo (isset($data['status']) && $data['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group" style="margin-bottom: 0;">
                                            <label class="form-label">Image</label>
                                            <input type="file" class="d-none <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image" accept="image/*" aria-label="Product image">
                                            <div class="d-flex" style="gap: 12px; align-items: flex-start;">
                                                <div class="pm-media-preview" aria-hidden="true">
                                                    <img id="imagePreview" src="" alt="" style="display:none;">
                                                    <i id="imagePreviewIcon" class="fas fa-image" style="color: rgba(100,116,139,0.9); font-size: 22px;"></i>
                                                </div>
                                                <div style="flex: 1 1 auto; min-width: 0;">
                                                    <div class="pm-media-actions" style="margin-top: 0;">
                                                        <button class="btn admin-btn-soft" type="button" id="chooseImageBtn" style="min-height: 40px;">
                                                            <i class="fas fa-upload mr-1"></i>Upload
                                                        </button>
                                                        <button class="btn btn-outline-secondary" type="button" id="removeImageBtn" style="border-radius: 10px; min-height: 40px;" disabled>
                                                            <i class="fas fa-times mr-1"></i>Remove
                                                        </button>
                                                    </div>
                                                    <div class="form-text" style="font-size: 12px;">Square image works best.</div>
                                                    <?php if(isset($errors['image'])): ?>
                                                        <div class="invalid-feedback d-block"><?php echo $errors['image']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pm-divider"></div>
                                        <a class="btn btn-link p-0" data-toggle="collapse" href="#moreOptions" role="button" aria-expanded="false" aria-controls="moreOptions" style="font-size: 12px;">
                                            More options
                                        </a>
                                        <div class="collapse" id="moreOptions" style="margin-top: 10px;">
                                            <div class="form-group">
                                                <label for="country_id" class="form-label">Country</label>
                                                <div class="pm-select-add">
                                                    <select class="form-select select2 flex-grow-1 <?php echo isset($errors['country_id']) ? 'is-invalid' : ''; ?>" id="country_id" name="country_id" style="min-width: 0;" aria-label="Country of origin">
                                                        <option value="">Select Country</option>
                                                        <?php 
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
                                                            <option value="<?php echo $country['id']; ?>" data-flag-image="<?php echo $flagImage; ?>" <?php echo $selected; ?>><?php echo $country['name']; ?></option>
                                                        <?php
                                                            endforeach;
                                                        endif;
                                                        ?>
                                                    </select>
                                                    <a href="<?php echo BASE_URL; ?>?controller=country&action=adminIndex" class="btn btn-outline-primary" type="button" aria-label="Add new country">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                    <?php if(isset($errors['country_id'])): ?>
                                                        <div class="invalid-feedback d-block w-100"><?php echo $errors['country_id']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="supplier" class="form-label">Supplier</label>
                                                <div class="pm-select-add">
                                                    <select class="form-select select2 flex-grow-1 <?php echo isset($errors['supplier']) ? 'is-invalid' : ''; ?>" id="supplier" name="supplier" style="min-width: 0;" aria-label="Supplier">
                                                        <option value="">Select Supplier</option>
                                                        <?php if(!empty($suppliers)): ?>
                                                            <?php foreach($suppliers as $supplier): ?>
                                                                <?php 
                                                                    $value = htmlspecialchars($supplier['name']);
                                                                    $selected = (isset($data['supplier']) && $data['supplier'] === $supplier['name']) ? 'selected' : '';
                                                                ?>
                                                                <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($supplier['name']); ?></option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                    <a href="<?php echo BASE_URL; ?>?controller=supplier&action=index" class="btn btn-outline-primary" type="button" aria-label="Add new supplier">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                    <?php if(isset($errors['supplier'])): ?>
                                                        <div class="invalid-feedback d-block w-100"><?php echo $errors['supplier']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="row" style="row-gap: 12px;">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="batch_number" class="form-label">Batch</label>
                                                        <input type="text" class="form-control <?php echo isset($errors['batch_number']) ? 'is-invalid' : ''; ?>" id="batch_number" name="batch_number" value="<?php echo $data['batch_number'] ?? ''; ?>" maxlength="100" aria-label="Batch number">
                                                        <?php if(isset($errors['batch_number'])): ?>
                                                            <div class="invalid-feedback"><?php echo $errors['batch_number']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group" style="margin-bottom: 0;">
                                                        <div class="d-flex align-items-center justify-content-between" style="gap: 12px; margin-bottom: 4px;">
                                                            <label for="sale_price" class="form-label" style="margin-bottom: 0;">Incl. Tax</label>
                                                            <div class="custom-control custom-switch" style="margin: 0;">
                                                                <input type="checkbox" class="custom-control-input" id="includingTaxToggle" <?php echo (!empty($data['sale_price'])) ? 'checked' : ''; ?> aria-label="Including tax toggle">
                                                                <label class="custom-control-label" for="includingTaxToggle"></label>
                                                            </div>
                                                        </div>
                                                        <div class="input-group" id="salePriceGroup">
                                                            <div class="input-group-prepend"><span class="input-group-text">CHF</span></div>
                                                            <input type="text" class="form-control <?php echo isset($errors['sale_price']) ? 'is-invalid' : ''; ?>" id="sale_price" name="sale_price" value="<?php echo $data['sale_price'] ?? ''; ?>" inputmode="decimal" autocomplete="off" aria-label="Including tax price">
                                                            <?php if(isset($errors['sale_price'])): ?>
                                                                <div class="invalid-feedback"><?php echo $errors['sale_price']; ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        <div class="pm-actionbar">
                            <div class="container-fluid px-3 px-lg-4">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center" style="gap: 12px;">
                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-link order-sm-1" aria-label="Cancel and go back">
                                        Cancel
                                    </a>
                                    <div class="d-flex flex-column flex-sm-row order-sm-2" style="gap: 10px;">
                                        <button type="submit" class="btn admin-btn-primary text-white" id="submitBtn" aria-label="Create product" style="padding: 0 14px;">
                                            <i class="fas fa-save mr-2"></i>Save Product
                                        </button>
                                        <button type="button" class="btn btn-success d-none" id="addAnotherBtn" style="display: none;" aria-label="Add another product">
                                            <i class="fas fa-plus mr-2"></i>Add Another
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
    </div>
</div>

<!-- Add Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Style for flag images in dropdown */
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

    // Media upload (drag & drop + preview)
    const mediaDrop = document.getElementById('mediaDrop');
    const imageInput = document.getElementById('image');
    const chooseImageBtn = document.getElementById('chooseImageBtn');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewIcon = document.getElementById('imagePreviewIcon');

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
            if (e.target && (e.target.id === 'removeImageBtn' || e.target.closest && e.target.closest('#removeImageBtn'))) {
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

    // Including tax toggle
    const includingTaxToggle = document.getElementById('includingTaxToggle');
    const salePriceGroup = document.getElementById('salePriceGroup');
    const salePriceInput = document.getElementById('sale_price');

    function setIncludingTaxEnabled(enabled) {
        if (!salePriceInput) return;
        salePriceInput.disabled = !enabled;
        if (!enabled) salePriceInput.value = '';
        if (salePriceGroup) {
            salePriceGroup.style.opacity = enabled ? '1' : '0.6';
        }
    }
    if (includingTaxToggle) {
        setIncludingTaxEnabled(!!includingTaxToggle.checked);
        includingTaxToggle.addEventListener('change', function() {
            setIncludingTaxEnabled(!!includingTaxToggle.checked);
        });
    }

    // Status toggle (sync hidden select)
    const statusToggle = document.getElementById('statusToggle');
    const statusSelect = document.getElementById('status');
    const statusPill = document.getElementById('statusPill');

    function setStatusUI(isActive) {
        if (statusSelect) statusSelect.value = isActive ? 'active' : 'inactive';
        if (!statusPill) return;
        const dot = statusPill.querySelector('.status-dot');
        const textEl = statusPill.querySelector('.status-text');
        if (dot) dot.style.background = isActive ? '#10b981' : '#f59e0b';
        if (textEl) textEl.textContent = isActive ? 'Active' : 'Inactive';
        statusPill.style.background = isActive ? 'rgba(16,185,129,0.10)' : 'rgba(245,158,11,0.12)';
        statusPill.style.borderColor = isActive ? 'rgba(16,185,129,0.18)' : 'rgba(245,158,11,0.20)';
        statusPill.style.color = isActive ? '#065f46' : '#92400e';
    }

    if (statusToggle) {
        setStatusUI(!!statusToggle.checked);
        statusToggle.addEventListener('change', function() {
            setStatusUI(!!statusToggle.checked);
        });
    }
    
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
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            
            var response, msg;
            try {
                response = JSON.parse(xhr.responseText);
                msg = response.message || (response.errors ? Object.values(response.errors).join(' ') : '') || 'Unknown error';
            } catch (parseErr) {
                response = null;
                if (xhr.status >= 200 && xhr.status < 300) {
                    console.error('Invalid JSON response:', xhr.responseText.substring(0, 200));
                    msg = 'Server returned an invalid response. If you were redirected to login, please log in and try again.';
                } else {
                    var preview = (xhr.responseText || '').substring(0, 150).replace(/<[^>]+>/g, ' ').trim();
                    msg = 'Error ' + xhr.status + (preview ? ': ' + preview : '');
                }
            }
            
            if (xhr.status >= 200 && xhr.status < 300 && response && response.success) {
                showAlert('Product created successfully!', 'success');
                submitBtn.classList.add('d-none');
                addAnotherBtn.classList.remove('d-none');
                form.reset();
                var fileInput = document.querySelector('input[type="file"]');
                if (fileInput) fileInput.value = '';
                resetImageUI();
                if (includingTaxToggle) setIncludingTaxEnabled(!!includingTaxToggle.checked);
                if (statusToggle) setStatusUI(!!statusToggle.checked);
                clearValidationErrors();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                showAlert(msg || 'Error ' + xhr.status + '. Please try again.', 'danger');
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
