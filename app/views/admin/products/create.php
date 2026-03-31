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

html[data-theme="light"] .admin-page-shell {
    background: #f7f8fb;
}

html[data-theme="light"] .admin-page-header {
    background: rgba(247, 248, 251, 0.82);
    border-bottom-color: rgba(17, 24, 39, 0.08);
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
    border-radius: 14px;
    box-shadow: 0 10px 28px rgba(17,24,39,0.06), 0 1px 2px rgba(17,24,39,0.05);
}

html[data-theme="light"] .admin-card {
    background: #ffffff;
    border-color: rgba(17, 24, 39, 0.08);
}

.admin-card__header {
    padding: 14px 14px 0;
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
    padding: 14px;
}

@media (min-width: 992px) {
    .admin-card__body { padding: 16px; }
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

@media (min-width: 992px) {
    .create-product-form .form-group { margin-bottom: 14px; }
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

/* Theme-aware form controls - Light mode only to avoid breaking dark mode */
html[data-theme="light"] .create-product-form .form-control,
html[data-theme="light"] .create-product-form .form-select {
    background-color: var(--surface-color) !important;
    color: var(--text-color) !important;
    border-color: var(--border-color);
}

/* Dark mode form controls - ensure light text on dark background */
html[data-theme="dark"] .create-product-form .form-control,
html[data-theme="dark"] .create-product-form .form-select {
    background-color: #2c3034 !important;
    color: #f8f9fa !important;
    border-color: #495057;
}

html[data-theme="light"] .create-product-form .form-control::placeholder {
    color: var(--muted-color) !important;
}

html[data-theme="dark"] .create-product-form .form-control::placeholder {
    color: #adb5bd !important;
}

/* Input group text theming */
html[data-theme="light"] .create-product-form .input-group-text {
    background-color: var(--surface-muted) !important;
    color: var(--muted-color) !important;
    border-color: var(--border-color);
}

html[data-theme="dark"] .create-product-form .input-group-text {
    background-color: #343a40 !important;
    color: #adb5bd !important;
    border-color: #495057;
}

/* Ensure labels are visible in both modes */
.create-product-form .form-label {
    color: var(--text-color) !important;
}

/* Form text/help text */
.create-product-form .form-text {
    color: var(--muted-color) !important;
}

/* Invalid feedback */
.create-product-form .invalid-feedback {
    color: #dc2626 !important;
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

.admin-page-header .btn {
    box-shadow: 0 1px 0 rgba(17,24,39,0.02);
}

html[data-theme="light"] .admin-page-header .btn.btn-outline-secondary {
    background: rgba(255,255,255,0.75);
    border-color: rgba(17, 24, 39, 0.10);
}

html[data-theme="light"] .admin-page-header .btn.btn-outline-secondary:hover {
    background: rgba(255,255,255,0.95);
    border-color: rgba(17, 24, 39, 0.14);
}

html[data-theme="light"] .admin-page-header .btn.btn-outline-primary {
    background: rgba(37,99,235,0.06);
    border-color: rgba(37,99,235,0.22);
}

html[data-theme="light"] .admin-page-header .btn.btn-outline-primary:hover {
    background: rgba(37,99,235,0.10);
    border-color: rgba(37,99,235,0.28);
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
    background-color: var(--surface-color);
}

.create-product-form .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.2;
    padding-left: 0;
    color: var(--text-color);
}

.create-product-form .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: var(--muted-color);
}

.create-product-form .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 38px;
}

/* Select2 dropdown theming */
.select2-dropdown {
    background-color: var(--surface-color);
    border-color: var(--border-color);
}

.select2-container--default .select2-results__option {
    color: var(--text-color);
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: rgba(37,99,235,0.15);
    color: var(--text-color);
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    background-color: var(--surface-color);
    color: var(--text-color);
    border-color: var(--border-color);
}

/* Dark mode Select2 specific overrides */
html[data-theme="dark"] .select2-dropdown {
    background-color: #2c3034 !important;
    border-color: #495057 !important;
}

html[data-theme="dark"] .select2-container--default .select2-results__option {
    color: #f8f9fa !important;
}

html[data-theme="dark"] .select2-container--default .select2-selection--single {
    background-color: #2c3034 !important;
    border-color: #495057 !important;
}

html[data-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #f8f9fa !important;
}

html[data-theme="dark"] .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #adb5bd !important;
}

html[data-theme="dark"] .select2-container--default .select2-search--dropdown .select2-search__field {
    background-color: #343a40 !important;
    color: #f8f9fa !important;
    border-color: #495057 !important;
}

/* Light mode Select2 specific overrides */
html[data-theme="light"] .select2-dropdown {
    background-color: #ffffff !important;
    border-color: rgba(17, 24, 39, 0.10) !important;
}

html[data-theme="light"] .select2-container--default .select2-results__option {
    color: #111827 !important;
}

html[data-theme="light"] .create-product-form .select2-container--default .select2-selection--single {
    background-color: #ffffff !important;
    border-color: rgba(17, 24, 39, 0.10) !important;
}

html[data-theme="light"] .create-product-form .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #111827 !important;
}

html[data-theme="light"] .create-product-form .select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #6b7280 !important;
}

html[data-theme="light"] .select2-container--default .select2-search--dropdown .select2-search__field {
    background-color: #ffffff !important;
    color: #111827 !important;
    border-color: rgba(17, 24, 39, 0.10) !important;
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

/* Add Unit modal */
.add-unit-modal .modal-content {
    border: 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 18px 44px rgba(15, 23, 42, 0.16);
}

.add-unit-modal .modal-body { padding: 1rem; }
@media (min-width: 768px) { .add-unit-modal .modal-body { padding: 1.25rem; } }

.add-unit-card {
    border-radius: 14px;
    border: 1px solid var(--border-color);
    background: var(--surface-color);
    padding: 0.85rem;
}
@media (min-width: 768px) { .add-unit-card { padding: 1rem; } }

.add-unit-modal .required-asterisk { color: #dc2626; }

.add-unit-modal .form-control,
.add-unit-modal .form-select {
    border-radius: 10px;
    min-height: 42px;
    transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
}

.add-unit-modal .form-control:hover,
.add-unit-modal .form-select:hover { border-color: rgba(37, 99, 235, 0.45); }

.add-unit-modal .form-control:focus,
.add-unit-modal .form-select:focus {
    border-color: rgba(37, 99, 235, 0.65);
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
}

.add-unit-modal .btn {
    min-height: 40px;
    border-radius: 10px;
    transition: transform .15s ease, box-shadow .15s ease;
}

.add-unit-modal .btn:hover { transform: translateY(-1px); }
.add-unit-modal .btn-primary:hover { box-shadow: 0 6px 16px rgba(37, 99, 235, 0.28); }
</style>

<div class="admin-page-shell">
    <div class="admin-page-header">
        <div class="container-fluid px-3 px-lg-4">
            <div class="admin-page-header__inner d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
                <div>
                    <h1 class="admin-page-title">Add Product</h1>
                    <p class="admin-page-subtitle">Create a new product with pricing, inventory and media.</p>
                </div>
                <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary" style="min-height: 40px; border-radius: 10px;">
                        <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                    <button type="button" class="btn btn-outline-secondary" id="previewBtn" style="min-height: 40px; border-radius: 10px;" aria-label="Preview product">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="saveAddAnotherBtn" style="min-height: 40px; border-radius: 10px;" aria-label="Save and add another product">
                        <i class="fas fa-plus mr-2"></i>Save & Add Another
                    </button>
                    <button type="button" class="btn admin-btn-primary text-white" id="saveHeaderBtn" style="min-height: 40px; border-radius: 10px; padding: 0 14px;" aria-label="Save product">
                        <i class="fas fa-save mr-2"></i>Save
                    </button>
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
                                            <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo $data['name'] ?? ''; ?>" required aria-required="true" list="productSuggestions" autocomplete="off">
                                            <datalist id="productSuggestions"></datalist>
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
                                                    $categories = method_exists($categoryModel, 'getActiveCategoriesWithTaxRate')
                                                        ? $categoryModel->getActiveCategoriesWithTaxRate()
                                                        : $categoryModel->getActiveCategories();
                                                    if(!empty($categories)) :
                                                        foreach($categories as $category) :
                                                            $selected = (isset($data['category_id']) && $data['category_id'] == $category['id']) ? 'selected' : '';
                                                            $catTaxRate = isset($category['tax_rate']) ? $category['tax_rate'] : '';
                                                    ?>
                                                        <option value="<?php echo $category['id']; ?>" data-tax-rate="<?php echo htmlspecialchars((string)$catTaxRate); ?>" <?php echo $selected; ?>><?php echo $category['name']; ?></option>
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

                                        <div class="row" style="row-gap: 12px;">
                                            <div class="col-12 col-md-4">
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
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <div class="d-flex align-items-center justify-content-between" style="gap: 12px; margin-bottom: 4px;">
                                                        <label for="sale_price" class="form-label" style="margin-bottom: 0;">Including Tax Price</label>
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

                                            <div class="col-12 col-md-4">
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
                                            </div>

                                            <div class="col-12 col-md-4">
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
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="customs_charge" class="form-label">Customs Charge</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">CHF</span></div>
                                                        <input type="text" class="form-control <?php echo isset($errors['customs_charge']) ? 'is-invalid' : ''; ?>" id="customs_charge" name="customs_charge" value="<?php echo $data['customs_charge'] ?? ''; ?>" inputmode="decimal" autocomplete="off" aria-label="Customs charge">
                                                        <?php if(isset($errors['customs_charge'])): ?>
                                                            <div class="invalid-feedback"><?php echo $errors['customs_charge']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="transport_charge" class="form-label">Transport Charge</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">CHF</span></div>
                                                        <input type="text" class="form-control <?php echo isset($errors['transport_charge']) ? 'is-invalid' : ''; ?>" id="transport_charge" name="transport_charge" value="<?php echo $data['transport_charge'] ?? ''; ?>" inputmode="decimal" autocomplete="off" aria-label="Transport charge">
                                                        <?php if(isset($errors['transport_charge'])): ?>
                                                            <div class="invalid-feedback"><?php echo $errors['transport_charge']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
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

                                        <div class="form-group" style="margin-top: 10px; margin-bottom: 0;">
                                            <label for="category_tax_rate" class="form-label">Category Tax Rate</label>
                                            <input type="text" class="form-control" id="category_tax_rate" value="" readonly aria-readonly="true" tabindex="-1">
                                            <div class="form-text" style="font-size: 12px;">Auto-filled from the selected category. Read-only for accuracy.</div>
                                        </div>

                                        <div class="pm-divider"></div>
                                        <div class="row" style="row-gap: 12px;">
                                            <div class="col-6">
                                                <div class="form-text" style="margin: 0; font-weight: 700; color: var(--text-color);">Profit</div>
                                                <div id="kpiProfit" style="font-weight: 800; font-variant-numeric: tabular-nums;">CHF 0.00</div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-text" style="margin: 0; font-weight: 700; color: var(--text-color);">Margin</div>
                                                <div id="kpiMargin" style="font-weight: 800; font-variant-numeric: tabular-nums;">0%</div>
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

                                        <div class="form-group mb-2">
                                            <label for="unit_id" class="form-label">Unit</label>
                                            <div class="pm-select-add">
                                                <select class="form-select select2 flex-grow-1" id="unit_id" name="unit_id" style="min-width: 0;" aria-label="Product unit">
                                                    <option value="">Select Unit</option>
                                                    <?php if (!empty($units)): ?>
                                                        <?php foreach ($units as $unit): ?>
                                                            <?php
                                                            $unitId = is_array($unit) ? ($unit['id'] ?? null) : ($unit->id ?? null);
                                                            $unitName = is_array($unit) ? ($unit['name'] ?? '') : ($unit->name ?? '');
                                                            $unitShort = is_array($unit) ? ($unit['short_name'] ?? '') : ($unit->short_name ?? '');
                                                            $selected = (isset($data['unit_id']) && (string)$data['unit_id'] === (string)$unitId) ? 'selected' : '';
                                                            ?>
                                                            <option value="<?php echo htmlspecialchars((string)$unitId); ?>" <?php echo $selected; ?>>
                                                                <?php echo htmlspecialchars(trim($unitName . ($unitShort ? ' (' . $unitShort . ')' : ''))); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addUnitModal" data-toggle="modal" data-target="#addUnitModal" aria-label="Open Add Unit modal">
                                                    <i class="fas fa-plus"></i>
                                                    <span class="d-none d-sm-inline">Add New</span>
                                                </button>
                                            </div>
                                            <div class="form-text mt-1">Manage units like category and quickly add a new one.</div>
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

                                        <div class="pm-divider"></div>
                                        <div style="margin-top: 10px;">
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
                                                </div>
                                            </div>

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

                                            <div class="row" style="row-gap: 12px;">
                                                <div class="col-12 col-sm-6">
                                                    <div class="form-group">
                                                        <label for="hsn_code" class="form-label">HSS Code</label>
                                                        <input type="text" class="form-control <?php echo isset($errors['hsn_code']) ? 'is-invalid' : ''; ?>" id="hsn_code" name="hsn_code" value="<?php echo $data['hsn_code'] ?? ''; ?>" maxlength="50" aria-label="HSN code">
                                                        <?php if(isset($errors['hsn_code'])): ?>
                                                            <div class="invalid-feedback"><?php echo $errors['hsn_code']; ?></div>
                                                        <?php endif; ?>
                                                    </div>
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
    
    // Initialize Select2 for ALL dropdowns (Country, Brand, Category, Supplier, Status) - live search
    $('.select2').select2({
        theme: 'default',
        width: '100%',
        placeholder: 'Search...',
        allowClear: false,
        minimumResultsForSearch: 0,
        templateResult: formatOption,
        templateSelection: formatOption,
        escapeMarkup: function(m) { return m; }
    });

    // Focus the Select2 search field on open (instant typing filters results)
    $(document).on('select2:open', function() {
        setTimeout(function() {
            const field = document.querySelector('.select2-container--open .select2-search__field');
            try { field && field.focus(); } catch (e) { /* ignore */ }
        }, 0);
    });
    
    const form = document.getElementById('productForm');
    const submitBtn = document.getElementById('submitBtn');
    const alertMessages = document.getElementById('alert-messages');
    const saveHeaderBtn = document.getElementById('saveHeaderBtn');
    const saveAddAnotherBtn = document.getElementById('saveAddAnotherBtn');
    const previewBtn = document.getElementById('previewBtn');
    const postSaveModalEl = document.getElementById('postSaveModal');
    const postSaveGoListBtn = document.getElementById('postSaveGoList');
    const postSaveContinueBtn = document.getElementById('postSaveContinue');
    const postSaveAddAnotherBtn = document.getElementById('postSaveAddAnother');
    const previewModalEl = document.getElementById('productPreviewModal');
    const previewName = document.getElementById('previewName');
    const previewMeta = document.getElementById('previewMeta');
    const previewPricing = document.getElementById('previewPricing');
    const previewInventory = document.getElementById('previewInventory');
    const previewImg = document.getElementById('previewImg');
    const previewDescription = document.getElementById('previewDescription');

    let pendingAfterSaveAction = 'continue';
    
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
                clearValidationErrors();
                window.scrollTo({ top: 0, behavior: 'smooth' });

                if (postSaveModalEl) {
                    try { $(postSaveModalEl).modal('show'); } catch (e) { /* ignore */ }
                }
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
    
    function setPendingAfterSaveAction(action) {
        pendingAfterSaveAction = action;
    }

    function getSelectedValue(id) {
        const el = document.getElementById(id);
        return el ? (el.value ?? '') : '';
    }

    function setSelectedValue(id, val) {
        const el = document.getElementById(id);
        if (!el) return;
        el.value = val;
        try { if (window.jQuery && $(el).hasClass('select2-hidden-accessible')) { $(el).trigger('change.select2'); } } catch (e) { /* ignore */ }
    }

    function resetFormKeep(categoryVal, brandVal) {
        form.reset();
        var fileInput = document.querySelector('input[type="file"]');
        if (fileInput) fileInput.value = '';
        resetImageUI();
        if (includingTaxToggle) setIncludingTaxEnabled(false);
        if (statusToggle) { statusToggle.checked = true; setStatusUI(true); }
        setSelectedValue('category_id', categoryVal);
        setSelectedValue('brand_id', brandVal);
        clearValidationErrors();
        updatePricingKpis();
        try { document.getElementById('name') && document.getElementById('name').focus(); } catch (e) { /* ignore */ }
    }

    if (saveHeaderBtn) {
        saveHeaderBtn.addEventListener('click', function() {
            setPendingAfterSaveAction('continue');
            submitBtn && submitBtn.click();
        });
    }

    if (saveAddAnotherBtn) {
        saveAddAnotherBtn.addEventListener('click', function() {
            setPendingAfterSaveAction('add_another');
            submitBtn && submitBtn.click();
        });
    }

    if (postSaveGoListBtn) {
        postSaveGoListBtn.addEventListener('click', function() {
            try { $(postSaveModalEl).modal('hide'); } catch (e) { /* ignore */ }
            window.location.href = '<?php echo BASE_URL; ?>?controller=product&action=adminIndex';
        });
    }

    if (postSaveContinueBtn) {
        postSaveContinueBtn.addEventListener('click', function() {
            try { $(postSaveModalEl).modal('hide'); } catch (e) { /* ignore */ }
        });
    }

    if (postSaveAddAnotherBtn) {
        postSaveAddAnotherBtn.addEventListener('click', function() {
            const cat = getSelectedValue('category_id');
            const br = getSelectedValue('brand_id');
            try { $(postSaveModalEl).modal('hide'); } catch (e) { /* ignore */ }
            resetFormKeep(cat, br);
        });
    }

    if (postSaveModalEl) {
        $(postSaveModalEl).on('shown.bs.modal', function() {
            if (pendingAfterSaveAction === 'add_another') {
                const cat = getSelectedValue('category_id');
                const br = getSelectedValue('brand_id');
                try { $(postSaveModalEl).modal('hide'); } catch (e) { /* ignore */ }
                resetFormKeep(cat, br);
            }
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

    function updatePricingKpis() {
        const buying = toNum(document.getElementById('price') ? document.getElementById('price').value : 0);
        const selling = toNum(document.getElementById('price2') ? document.getElementById('price2').value : 0);
        const profit = selling - buying;
        const margin = selling > 0 ? (profit / selling) * 100 : 0;
        const p = document.getElementById('kpiProfit');
        const m = document.getElementById('kpiMargin');
        if (p) p.textContent = fmtCHF(profit);
        if (m) m.textContent = (isNaN(margin) ? '0%' : margin.toFixed(1) + '%');
    }

    function formatCurrencyOnBlur(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('blur', function() {
            const n = toNum(el.value);
            if (el.value === '') return;
            el.value = n.toFixed(2);
        });
    }

    ['price','price2','price3','customs_charge','transport_charge','sale_price'].forEach(function(id) {
        formatCurrencyOnBlur(id);
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', function() { updatePricingKpis(); });
            el.addEventListener('change', function() { updatePricingKpis(); });
        }
    });
    updatePricingKpis();

    function debounce(fn, wait) {
        let t = null;
        return function() {
            const ctx = this;
            const args = arguments;
            clearTimeout(t);
            t = setTimeout(function() { fn.apply(ctx, args); }, wait);
        };
    }

    const nameInput = document.getElementById('name');
    const datalist = document.getElementById('productSuggestions');
    const suggestUrlBase = '<?php echo BASE_URL; ?>?controller=product&action=suggest';
    const fetchSuggestions = debounce(function() {
        if (!nameInput || !datalist) return;
        const q = (nameInput.value || '').trim();
        if (q.length < 2) {
            datalist.innerHTML = '';
            return;
        }
        fetch(suggestUrlBase + '&q=' + encodeURIComponent(q) + '&limit=8', { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
            .then(r => r.json())
            .then(json => {
                if (!json || !json.success || !Array.isArray(json.data)) return;
                datalist.innerHTML = '';
                json.data.forEach(function(item) {
                    const opt = document.createElement('option');
                    opt.value = item.name || '';
                    datalist.appendChild(opt);
                });
            })
            .catch(() => { /* ignore */ });
    }, 250);

    if (nameInput) nameInput.addEventListener('input', fetchSuggestions);

    function getSelectText(id) {
        const el = document.getElementById(id);
        if (!el) return '';
        const opt = el.options && el.selectedIndex >= 0 ? el.options[el.selectedIndex] : null;
        return opt ? (opt.textContent || '') : '';
    }

    function openPreview() {
        if (!previewModalEl) return;
        const n = document.getElementById('name') ? document.getElementById('name').value : '';
        const d = document.getElementById('description') ? document.getElementById('description').value : '';
        const cat = getSelectText('category_id');
        const br = getSelectText('brand_id');
        const sku = document.getElementById('sku') ? document.getElementById('sku').value : '';
        const buy = document.getElementById('price') ? document.getElementById('price').value : '';
        const sell = document.getElementById('price2') ? document.getElementById('price2').value : '';
        const whole = document.getElementById('price3') ? document.getElementById('price3').value : '';
        const stock = document.getElementById('stock_quantity') ? document.getElementById('stock_quantity').value : '';
        const exp = document.getElementById('expiry_date') ? document.getElementById('expiry_date').value : '';
        if (previewName) previewName.textContent = n || 'New Product';
        if (previewMeta) previewMeta.textContent = [cat ? ('Category: ' + cat) : '', br ? ('Brand: ' + br) : '', sku ? ('SKU: ' + sku) : ''].filter(Boolean).join(' • ');
        if (previewPricing) previewPricing.textContent = [buy ? ('Buying: CHF ' + buy) : '', sell ? ('Sales: CHF ' + sell) : '', whole ? ('Wholesale: CHF ' + whole) : ''].filter(Boolean).join(' | ');
        if (previewInventory) previewInventory.textContent = [stock ? ('Stock: ' + stock) : '', exp ? ('Expiry: ' + exp) : ''].filter(Boolean).join(' • ');
        if (previewDescription) previewDescription.textContent = d || '';
        if (previewImg) {
            const shown = imagePreview && imagePreview.style.display !== 'none' && imagePreview.src;
            if (shown) {
                previewImg.src = imagePreview.src;
                previewImg.style.display = '';
            } else {
                previewImg.src = '';
                previewImg.style.display = 'none';
            }
        }
        try { $(previewModalEl).modal('show'); } catch (e) { /* ignore */ }
    }

    if (previewBtn) previewBtn.addEventListener('click', openPreview);
    
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

    function updateCategoryTaxRateUI() {
        var categorySelect = document.getElementById('category_id');
        var out = document.getElementById('category_tax_rate');
        if (!categorySelect || !out) return;
        var opt = categorySelect.options && categorySelect.selectedIndex >= 0 ? categorySelect.options[categorySelect.selectedIndex] : null;
        var rate = opt ? opt.getAttribute('data-tax-rate') : '';
        rate = (rate === null || rate === undefined) ? '' : String(rate);
        out.value = rate && rate.trim() !== '' ? (rate.trim() + '%') : '—';
    }

    var categorySelectEl = document.getElementById('category_id');
    if (categorySelectEl) {
        categorySelectEl.addEventListener('change', function() {
            updateCategoryTaxRateUI();
        });
        try {
            if (window.jQuery && $(categorySelectEl).hasClass('select2-hidden-accessible')) {
                $(categorySelectEl).on('change.select2', function() {
                    updateCategoryTaxRateUI();
                });
            }
        } catch (e) { /* ignore */ }
    }

    // Add Unit modal UX
    const addUnitForm = document.getElementById('addUnitForm');
    const addUnitModal = document.getElementById('addUnitModal');
    const isMultipleUnit = document.getElementById('isMultipleUnit');
    const conversionWrap = document.getElementById('conversionWrap');
    const unitMultiplier = document.getElementById('unitMultiplier');
    const baseUnit = document.getElementById('baseUnit');
    const productUnitSelect = document.getElementById('unit_id');

    function toggleConversionFields() {
        const enabled = !!(isMultipleUnit && isMultipleUnit.checked);
        if (!conversionWrap || !unitMultiplier || !baseUnit) return;
        conversionWrap.classList.toggle('d-none', !enabled);
        unitMultiplier.required = enabled;
        baseUnit.required = enabled;
        unitMultiplier.setAttribute('aria-required', enabled ? 'true' : 'false');
        baseUnit.setAttribute('aria-required', enabled ? 'true' : 'false');
        if (!enabled) {
            unitMultiplier.value = '';
            baseUnit.value = '';
            unitMultiplier.classList.remove('is-invalid');
            baseUnit.classList.remove('is-invalid');
        }
    }

    function validateAddUnitForm() {
        if (!addUnitForm) return false;
        let valid = true;
        const requiredInputs = addUnitForm.querySelectorAll('[required]');
        requiredInputs.forEach(function(input) {
            const value = (input.value || '').trim();
            const isEmpty = value === '';
            input.classList.toggle('is-invalid', isEmpty);
            if (isEmpty) valid = false;
        });

        if (isMultipleUnit && isMultipleUnit.checked && unitMultiplier) {
            const multiplier = parseFloat(unitMultiplier.value || '0');
            const badMultiplier = isNaN(multiplier) || multiplier <= 0;
            unitMultiplier.classList.toggle('is-invalid', badMultiplier);
            if (badMultiplier) valid = false;
        }

        return valid;
    }

    if (isMultipleUnit) {
        isMultipleUnit.addEventListener('change', toggleConversionFields);
        toggleConversionFields();
    }

    if (addUnitForm) {
        addUnitForm.addEventListener('input', function(e) {
            if (e.target && e.target.classList.contains('is-invalid')) {
                e.target.classList.remove('is-invalid');
            }
        });

        addUnitForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!validateAddUnitForm()) return;

            const submitBtn = addUnitForm.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span>Saving...';
            }

            const payload = new FormData(addUnitForm);
            payload.set('is_multiple', (isMultipleUnit && isMultipleUnit.checked) ? '1' : '0');

            fetch('<?php echo BASE_URL; ?>?controller=unit&action=create', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
                body: payload
            })
            .then(function(response) { return response.json(); })
            .then(function(result) {
                if (!result || !result.success) {
                    throw new Error((result && result.message) ? result.message : 'Failed to add unit');
                }

                const optionLabel = (result.name || '') + ((result.short_name || '') ? (' (' + result.short_name + ')') : '');
                if (productUnitSelect) {
                    const newOption = new Option(optionLabel.trim(), String(result.id), true, true);
                    productUnitSelect.appendChild(newOption);
                    try {
                        if (window.jQuery && $(productUnitSelect).hasClass('select2-hidden-accessible')) {
                            $(productUnitSelect).trigger('change.select2');
                        }
                    } catch (e2) { /* ignore */ }
                }

                if (baseUnit) {
                    const baseOption = new Option(optionLabel.trim(), String(result.id), false, false);
                    baseUnit.appendChild(baseOption);
                }

                try { $(addUnitModal).modal('hide'); } catch (err) { /* ignore */ }
                addUnitForm.reset();
                toggleConversionFields();
            })
            .catch(function(error) {
                alert(error.message || 'Unable to save unit');
            })
            .finally(function() {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        });
    }

    if (window.jQuery && $.fn.tooltip) {
        $('[data-toggle="tooltip"], [data-bs-toggle="tooltip"]').tooltip({ container: 'body' });
    }

    updateCategoryTaxRateUI();
});
</script>

<div class="modal fade" id="postSaveModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title" style="margin: 0;">Saved</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div style="font-weight: 700; color: var(--text-color);">What would you like to do next?</div>
                <div class="form-text">Choose the next step to keep your workflow fast.</div>
            </div>
            <div class="modal-footer" style="gap: 8px;">
                <button type="button" class="btn btn-outline-secondary" id="postSaveContinue">Save & Continue Editing</button>
                <button type="button" class="btn btn-outline-primary" id="postSaveGoList">Save & Go to Product List</button>
                <button type="button" class="btn admin-btn-primary text-white" id="postSaveAddAnother">Save & Add Another</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <h5 class="modal-title" style="margin: 0;">Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row" style="row-gap: 12px;">
                    <div class="col-12 col-md-5">
                        <img id="previewImg" src="" alt="" style="width: 100%; border-radius: 12px; border: 1px solid var(--border-color); background: var(--surface-muted); display:none;">
                        <div class="form-text" style="margin-top: 8px;">Preview shows the content entered so far.</div>
                    </div>
                    <div class="col-12 col-md-7">
                        <div style="font-size: 18px; font-weight: 800; letter-spacing: -0.01em;" id="previewName">New Product</div>
                        <div class="form-text" id="previewMeta"></div>
                        <div class="pm-divider"></div>
                        <div class="form-text" style="font-weight: 700; color: var(--text-color);">Pricing</div>
                        <div id="previewPricing" style="font-variant-numeric: tabular-nums;"></div>
                        <div class="pm-divider"></div>
                        <div class="form-text" style="font-weight: 700; color: var(--text-color);">Inventory</div>
                        <div id="previewInventory"></div>
                        <div class="pm-divider"></div>
                        <div class="form-text" style="font-weight: 700; color: var(--text-color);">Description</div>
                        <div id="previewDescription" style="white-space: pre-wrap;"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade add-unit-modal" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="addUnitModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title" id="addUnitModalLabel">Add Unit</h5>
                    <p class="form-text mb-0">Create a clean unit setup for stock and POS workflows.</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body pt-3">
                <div class="add-unit-card">
                    <form id="addUnitForm" novalidate>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="unitName" class="form-label">Unit Name <span class="required-asterisk">*</span></label>
                                <input type="text" class="form-control" id="unitName" name="unit_name" required aria-required="true" maxlength="60" placeholder="e.g., Kilogram">
                                <div class="invalid-feedback">Unit Name is required.</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="unitShortName" class="form-label">Short Name <span class="required-asterisk">*</span></label>
                                <input type="text" class="form-control" id="unitShortName" name="short_name" required aria-required="true" maxlength="20" placeholder="e.g., kg">
                                <div class="invalid-feedback">Short Name is required.</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="allowDecimal" class="form-label">Allow Decimal <span class="required-asterisk">*</span></label>
                                <select class="form-select" id="allowDecimal" name="allow_decimal" required aria-required="true">
                                    <option value="">Select option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                <div class="invalid-feedback">Please select if decimals are allowed.</div>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch mt-1">
                                    <input class="form-check-input" type="checkbox" role="switch" id="isMultipleUnit" name="is_multiple">
                                    <label class="form-check-label d-flex align-items-center" for="isMultipleUnit" style="gap: 8px;">
                                        <span>Add as multiple of another unit</span>
                                        <i class="fas fa-info-circle text-muted" tabindex="0" role="button" data-bs-toggle="tooltip" data-toggle="tooltip" title="Use this for derived units, like 1 Box = 12 Pieces."></i>
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 d-none" id="conversionWrap" aria-live="polite">
                                <div class="row g-2 g-md-3 align-items-end">
                                    <div class="col-12 col-md-6">
                                        <label for="unitMultiplier" class="form-label">1 Unit = [value] x Base Unit <span class="required-asterisk">*</span></label>
                                        <input type="number" class="form-control" id="unitMultiplier" name="multiplier" min="0.0001" step="0.0001" placeholder="e.g., 12">
                                        <div class="invalid-feedback">Enter a valid conversion value greater than 0.</div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="baseUnit" class="form-label">Select Base Unit <span class="required-asterisk">*</span></label>
                                        <select class="form-select" id="baseUnit" name="base_unit">
                                            <option value="">Choose base unit</option>
                                            <?php if (!empty($units)): ?>
                                                <?php foreach ($units as $unit): ?>
                                                    <?php
                                                    $baseUnitId = is_array($unit) ? ($unit['id'] ?? null) : ($unit->id ?? null);
                                                    $baseUnitName = is_array($unit) ? ($unit['name'] ?? '') : ($unit->name ?? '');
                                                    $baseUnitShort = is_array($unit) ? ($unit['short_name'] ?? '') : ($unit->short_name ?? '');
                                                    ?>
                                                    <option value="<?php echo htmlspecialchars((string)$baseUnitId); ?>">
                                                        <?php echo htmlspecialchars(trim($baseUnitName . ($baseUnitShort ? ' (' . $baseUnitShort . ')' : ''))); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <div class="invalid-feedback">Please select a base unit.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-dark" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

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
