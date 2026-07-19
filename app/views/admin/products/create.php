<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/product-create.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="admin-page-shell product-create-page pc-compact" id="productCreatePage">
    <div class="pc-toast-host" id="pcToastHost" aria-live="polite" aria-atomic="true"></div>
    <div class="admin-page-header pc-header-bar">
        <div class="container-fluid px-3 px-lg-4">
            <div class="admin-page-header__inner d-flex align-items-start justify-content-between flex-wrap" style="gap: 12px;">
                <div>
                    <nav class="pc-breadcrumb" aria-label="Breadcrumb">
                        <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                        <span class="sep">›</span>
                        <span>Catalog</span>
                        <span class="sep">›</span>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex">Products</a>
                        <span class="sep">›</span>
                        <span aria-current="page">Create Product</span>
                    </nav>
                    <h1 class="admin-page-title pc-title">Product Management</h1>
                    <p class="admin-page-subtitle">Create Product — pricing, inventory, media, and catalog details.</p>
                </div>
                <div class="d-flex align-items-center flex-wrap pc-actions" style="gap: 8px;">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary pc-btn" style="min-height: 40px;">
                        <i class="bi bi-arrow-left"></i><span>Back</span>
                    </a>
                    <button type="button" class="btn btn-outline-secondary pc-btn" id="pcSaveDraftBtn" style="min-height: 40px;" title="Save Draft (UI)">
                        <i class="bi bi-file-earmark"></i><span class="d-none d-lg-inline">Save Draft</span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="previewBtn" style="min-height: 40px; border-radius: 12px;" aria-label="Preview product">
                        <i class="fas fa-eye mr-2"></i><span class="d-none d-md-inline">Live Preview</span>
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="saveAddAnotherBtn" style="min-height: 40px; border-radius: 12px;" aria-label="Save and add another product">
                        <i class="fas fa-plus mr-2"></i><span class="d-none d-lg-inline">Save &amp; New</span>
                    </button>
                    <button type="button" class="btn btn-outline-secondary pc-btn" id="pcDuplicateBtn" style="min-height: 40px;" title="Duplicate (UI)">
                        <i class="bi bi-files"></i><span class="d-none d-xl-inline">Duplicate</span>
                    </button>
                    <button type="button" class="btn admin-btn-primary text-white" id="saveHeaderBtn" style="min-height: 40px; border-radius: 12px; padding: 0 14px;" aria-label="Save product">
                        <i class="fas fa-save mr-2"></i>Save Product
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

                    
        <div class="row g-3 mb-3 pc-kpi-row">
            <div class="col-6 col-md-4 col-xl-3">
                <div class="pc-stat s1"><div class="icon"><i class="bi bi-box-seam"></i></div><div><div class="label">Product Status</div><div class="value" id="pcKpiStatus">Active</div></div></div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="pc-stat s2"><div class="icon"><i class="bi bi-upc"></i></div><div><div class="label">SKU</div><div class="value" id="pcKpiSku">—</div></div></div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="pc-stat s3"><div class="icon"><i class="bi bi-stack"></i></div><div><div class="label">Inventory</div><div class="value" id="pcKpiStock">0</div></div></div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="pc-stat s4"><div class="icon"><i class="bi bi-currency-exchange"></i></div><div><div class="label">Selling Price</div><div class="value" id="pcKpiPrice">CHF 0.00</div></div></div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="pc-stat s5"><div class="icon"><i class="bi bi-graph-up-arrow"></i></div><div><div class="label">Profit Margin</div><div class="value" id="pcKpiMargin">0%</div></div></div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="pc-stat s6"><div class="icon"><i class="bi bi-image"></i></div><div><div class="label">Images</div><div class="value" id="pcKpiImages">0</div></div></div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="pc-stat s7"><div class="icon"><i class="bi bi-tags"></i></div><div><div class="label">Category</div><div class="value" id="pcKpiCategory">—</div></div></div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="pc-stat s8"><div class="icon"><i class="bi bi-search"></i></div><div><div class="label">SEO Score</div><div class="value" id="pcKpiSeo">0</div></div></div>
            </div>
        </div>
<form id="productForm" class="create-product-form" action="<?php echo BASE_URL; ?>?controller=product&action=create" method="POST" enctype="multipart/form-data" novalidate aria-label="Add product form">
                        <div class="row g-2 align-items-start" style="row-gap: 8px;">
                            <div class="col-12 col-xl-9 pc-main-wrap">
                            <div class="row g-2 pc-form-sections align-items-start">

                            <div class="col-12 col-md-6 col-lg-4 pc-col-left">
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

                                        <div class="accordion pc-accordion mb-2" id="pcAdvAcc">
  <div class="accordion-item">
    <h2 class="accordion-header" id="pcDescHead">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pcDescBody" aria-expanded="false" aria-controls="pcDescBody">Description / SEO</button>
    </h2>
    <div id="pcDescBody" class="accordion-collapse collapse" aria-labelledby="pcDescHead" data-bs-parent="#pcAdvAcc">
      <div class="accordion-body">
<div class="form-group">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control <?php echo isset($errors['description']) ? 'is-invalid' : ''; ?>" id="description" name="description" rows="3" aria-label="Product description"><?php echo $data['description'] ?? ''; ?></textarea>
                                            <?php if(isset($errors['description'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['description']; ?></div>
                                            <?php endif; ?>
                                        </div>

                                              </div>
    </div>
  </div>
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

                            <div class="col-12 col-md-6 col-lg-4 pc-col-center">
                                <div class="admin-card">
                                    <div class="admin-card__body">
                                        <h2 class="pm-section-title">Pricing & Inventory</h2>
                                        <p class="pm-section-subtitle">Set your selling price.</p>
                                        <div class="pm-divider"></div>

                                        <div class="row g-2">
                                            <div class="col-6">
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

                                            <div class="col-6">
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

                                            <div class="col-6">
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

                                            <div class="col-6">
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

                                            <div class="col-6">
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

                                            <div class="col-6">
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
                                        <div class="row" class="g-2">
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

                            <div class="col-12 col-lg-4 pc-col-right">
                                <div class="pc-sticky-panel">
                                <div class="admin-card mb-2">
                                    <div class="admin-card__body">
                                        <div class="d-flex align-items-center justify-content-between" style="gap: 8px;">
                                            <div>
                                                <h2 class="pm-section-title" style="margin-bottom: 0;">Image & Status</h2>
                                                <p class="pm-section-subtitle" style="margin-bottom: 0;">Upload and publish.</p>
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

                                        <div class="form-group" style="margin-bottom: 8px;">
                                            <label class="form-label">Image</label>
                                            <input type="file" class="d-none <?php echo isset($errors['image']) ? 'is-invalid' : ''; ?>" id="image" name="image" accept="image/*" aria-label="Product image">
                                            <div id="mediaDrop" class="pm-media-drop d-flex" tabindex="0" role="button" aria-label="Upload product image" style="gap: 12px; align-items: flex-start;">
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

                                        <div class="pc-side-mini">
                                          <div class="pc-sum-row"><span>Completion</span><span id="pcCompletePct">0%</span></div>
                                          <div class="pc-sum-row"><span>SEO Score</span><span id="pcKpiSeoMini">0</span></div>
                                          <div class="pc-seo-meter mt-1"><div class="pc-seo-bar" id="pcSeoBar" style="width:0%"></div></div>
                                          <div class="pc-side-actions">
                                            <button type="button" class="btn admin-btn-primary text-white" onclick="document.getElementById('saveHeaderBtn')?.click()">Save Product</button>
                                            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('saveAddAnotherBtn')?.click()">Save &amp; New</button>
                                            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary">Cancel</a>
                                          </div>
                                        </div>

                                        <div class="accordion pc-accordion mt-2" id="pcMoreAcc">
                                          <div class="accordion-item">
                                            <h2 class="accordion-header" id="pcMoreHead">
                                              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pcMoreBody" aria-expanded="false">Advanced / Specs</button>
                                            </h2>
                                            <div id="pcMoreBody" class="accordion-collapse collapse" data-bs-parent="#pcMoreAcc">
                                              <div class="accordion-body">
                                                <div class="row g-2">
                                                  <div class="col-6">
                                                    <div class="form-group">
                                                      <label for="batch_number" class="form-label">Batch</label>
                                                      <input type="text" class="form-control <?php echo isset($errors['batch_number']) ? 'is-invalid' : ''; ?>" id="batch_number" name="batch_number" value="<?php echo $data['batch_number'] ?? ''; ?>" maxlength="100" aria-label="Batch number">
                                                      <?php if(isset($errors['batch_number'])): ?><div class="invalid-feedback"><?php echo $errors['batch_number']; ?></div><?php endif; ?>
                                                    </div>
                                                  </div>
                                                  <div class="col-6">
                                                    <div class="form-group">
                                                      <label for="supplier" class="form-label">Supplier</label>
                                                      <div class="pm-select-add">
                                                        <select class="form-select select2 flex-grow-1 <?php echo isset($errors['supplier']) ? 'is-invalid' : ''; ?>" id="supplier" name="supplier" style="min-width: 0;" aria-label="Supplier">
                                                          <option value="">Select Supplier</option>
                                                          <?php if(!empty($suppliers)): foreach($suppliers as $supplier):
                                                            $value = htmlspecialchars($supplier['name']);
                                                            $selected = (isset($data['supplier']) && $data['supplier'] === $supplier['name']) ? 'selected' : '';
                                                          ?>
                                                            <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo htmlspecialchars($supplier['name']); ?></option>
                                                          <?php endforeach; endif; ?>
                                                        </select>
                                                        <a href="<?php echo BASE_URL; ?>?controller=supplier&action=index" class="btn btn-outline-primary" type="button" aria-label="Add new supplier"><i class="fas fa-plus"></i></a>
                                                        <?php if(isset($errors['supplier'])): ?><div class="invalid-feedback d-block w-100"><?php echo $errors['supplier']; ?></div><?php endif; ?>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="col-12">
                                                    <div class="form-group">
                                                      <label for="country_id" class="form-label">Country</label>
                                                      <div class="pm-select-add">
                                                        <select class="form-select select2 flex-grow-1 <?php echo isset($errors['country_id']) ? 'is-invalid' : ''; ?>" id="country_id" name="country_id" style="min-width: 0;" aria-label="Country of origin">
                                                          <option value="">Select Country</option>
                                                          <?php
                                                          $countryModel = new Country();
                                                          $countries = $countryModel->getActiveCountries();
                                                          if(!empty($countries)):
                                                            foreach($countries as $country):
                                                              $selected = (isset($data['country_id']) && $data['country_id'] == $country['id']) ? 'selected' : '';
                                                              $countryCode = strtolower(substr($country['name'], 0, 2));
                                                              $flagImage = !empty($country['flag_image']) ? BASE_URL . 'uploads/flags/' . $country['flag_image'] : 'https://flagcdn.com/24x18/' . $countryCode . '.png';
                                                          ?>
                                                            <option value="<?php echo $country['id']; ?>" data-flag-image="<?php echo $flagImage; ?>" <?php echo $selected; ?>><?php echo $country['name']; ?></option>
                                                          <?php endforeach; endif; ?>
                                                        </select>
                                                        <a href="<?php echo BASE_URL; ?>?controller=country&action=adminIndex" class="btn btn-outline-primary" type="button" aria-label="Add new country"><i class="fas fa-plus"></i></a>
                                                        <?php if(isset($errors['country_id'])): ?><div class="invalid-feedback d-block w-100"><?php echo $errors['country_id']; ?></div><?php endif; ?>
                                                      </div>
                                                    </div>
                                                  </div>
                                                  <div class="col-6">
                                                    <div class="form-group mb-0">
                                                      <label for="hsn_code" class="form-label">HSS Code</label>
                                                      <input type="text" class="form-control <?php echo isset($errors['hsn_code']) ? 'is-invalid' : ''; ?>" id="hsn_code" name="hsn_code" value="<?php echo $data['hsn_code'] ?? ''; ?>" maxlength="50" aria-label="HSN code">
                                                      <?php if(isset($errors['hsn_code'])): ?><div class="invalid-feedback"><?php echo $errors['hsn_code']; ?></div><?php endif; ?>
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="accordion-item">
                                            <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pcVarBody" aria-expanded="false">Variants</button></h2>
                                            <div id="pcVarBody" class="accordion-collapse collapse" data-bs-parent="#pcMoreAcc"><div class="accordion-body"><p class="form-text mb-0">Use product edit after save for variant stock (existing backend).</p></div></div>
                                          </div>
                                        </div>

                                    </div>
                                </div>
                                </div><!-- /.pc-sticky-panel -->
                            </div>
                        
                        
                            </div><!-- /.pc-form-sections -->
                            </div><!-- /.col-xl-9 -->

                            <div class="col-12 col-xl-3">
                              <div class="accordion d-xl-none mb-3" id="pcSideAccordion">
                                <div class="accordion-item">
                                  <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#pcAccPreview">Product Preview</button></h2>
                                  <div id="pcAccPreview" class="accordion-collapse collapse show" data-bs-parent="#pcSideAccordion"><div class="accordion-body" id="pcSidebarMobile"></div></div>
                                </div>
                              </div>
                              <aside class="pc-sidebar pc-legacy-sidebar d-none" id="pcSidebar" aria-label="Product preview sidebar">
                                <div class="admin-card pc-side-card mb-3">
                                  <div class="admin-card__body">
                                    <h3 class="pm-section-title">Live Preview</h3>
                                    <div class="pc-preview-box">
                                      <div class="pc-preview-img" id="pcSideImgWrap"><i class="bi bi-image" id="pcSideImgIcon"></i><img id="pcSideImg" alt="" style="display:none;"></div>
                                      <div class="pc-preview-name" id="pcSideName">Untitled product</div>
                                      <div class="pc-preview-meta text-muted small" id="pcSideMeta">SKU · Category</div>
                                      <div class="pc-preview-price" id="pcSidePrice">CHF 0.00</div>
                                    </div>
                                  </div>
                                </div>
                                <div class="admin-card pc-side-card mb-3">
                                  <div class="admin-card__body">
                                    <h3 class="pm-section-title">Inventory Summary</h3>
                                    <div class="pc-sum-row"><span>Stock</span><span id="pcSideStock">0</span></div>
                                    <div class="pc-sum-row"><span>Status</span><span id="pcSideStatus">Active</span></div>
                                    <div class="pc-sum-row"><span>Unit</span><span id="pcSideUnit">—</span></div>
                                  </div>
                                </div>
                                <div class="admin-card pc-side-card mb-3">
                                  <div class="admin-card__body">
                                    <h3 class="pm-section-title">Price Summary</h3>
                                    <div class="pc-sum-row"><span>Buying</span><span id="pcSideBuy">CHF 0.00</span></div>
                                    <div class="pc-sum-row"><span>Selling</span><span id="pcSideSell">CHF 0.00</span></div>
                                    <div class="pc-sum-row"><span>Profit</span><span id="pcSideProfit">CHF 0.00</span></div>
                                    <div class="pc-sum-row"><span>Margin</span><span id="pcSideMargin">0%</span></div>
                                  </div>
                                </div>
                                <div class="admin-card pc-side-card mb-3">
                                  <div class="admin-card__body">
                                    <h3 class="pm-section-title">SEO Score</h3>
                                    <div class="pc-seo-meter"><div class="pc-seo-bar" id="pcSeoBarLegacy" style="width:0%"></div></div>
                                    <div class="small text-muted mt-2"><span id="pcSeoLabel">Add name &amp; description to improve score</span></div>
                                  </div>
                                </div>
                                <div class="admin-card pc-side-card mb-3">
                                  <div class="admin-card__body">
                                    <h3 class="pm-section-title">Publishing</h3>
                                    <div class="pc-sum-row"><span>Store</span><span class="badge bg-success">Ready</span></div>
                                    <div class="pc-sum-row"><span>POS</span><span class="badge bg-secondary">Enabled</span></div>
                                    <div class="pc-sum-row"><span>Completion</span><span id="pcCompletePctLegacy">0%</span></div>
                                    <ul class="pc-checklist small mt-2 mb-0" id="pcChecklistLegacy">
                                      <li data-check="name">Product name</li>
                                      <li data-check="category">Category</li>
                                      <li data-check="price">Sales price</li>
                                      <li data-check="image">Image</li>
                                    </ul>
                                  </div>
                                </div>
                              </aside>
                            </div>
                        </div><!-- /.row outer -->

                        <div class="pm-actionbar">
                            <div class="container-fluid px-3 px-lg-4">
                                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center" style="gap: 12px;">
                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-link order-sm-1" aria-label="Cancel and go back">
                                        Cancel
                                    </a>
                                    <div class="d-flex flex-column flex-sm-row order-sm-2 flex-wrap" style="gap: 10px;">
                                        <button type="button" class="btn btn-outline-secondary" id="pcDraftBar" style="border-radius:12px;">Save Draft</button>
                                        <button type="button" class="btn btn-outline-secondary" id="previewBtnBar" style="border-radius:12px;" onclick="document.getElementById('previewBtn')?.click()">Preview</button>
                                        <button type="reset" class="btn btn-outline-secondary" style="border-radius:12px;">Reset</button>
                                        <button type="submit" class="btn admin-btn-primary text-white" id="submitBtn" aria-label="Create product" style="padding: 0 14px; border-radius:12px;">
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


<script>
(function(){
  function toast(msg, type){
    var host = document.getElementById('pcToastHost');
    if (!host) return;
    var el = document.createElement('div');
    el.className = 'toast align-items-center text-bg-' + (type==='error'?'danger':type) + ' border-0 show';
    el.setAttribute('role','alert');
    el.innerHTML = '<div class="d-flex"><div class="toast-body">'+msg+'</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';
    host.appendChild(el);
    setTimeout(function(){ el.remove(); }, 3000);
  }
  function txt(sel){ var el=document.getElementById(sel); return el ? (el.value||'').trim() : ''; }
  function set(id, v){ var el=document.getElementById(id); if(el) el.textContent = v; }
  function sync(){
    var name = txt('name') || 'Untitled product';
    var sku = txt('sku') || '—';
    var stock = txt('stock_quantity') || '0';
    var price2 = txt('price2') || '0';
    var price = txt('price') || '0';
    var cat = document.getElementById('category_id');
    var catText = (cat && cat.selectedIndex>0) ? cat.options[cat.selectedIndex].text : '—';
    var unit = document.getElementById('unit_id');
    var unitText = (unit && unit.selectedIndex>0) ? unit.options[unit.selectedIndex].text : '—';
    var status = document.getElementById('status');
    var statusText = (status && status.value==='inactive') ? 'Inactive' : 'Active';
    var margin = document.getElementById('kpiMargin');
    var profit = document.getElementById('kpiProfit');
    set('pcKpiStatus', statusText);
    set('pcKpiSku', sku);
    set('pcKpiStock', stock);
    set('pcKpiPrice', 'CHF ' + (parseFloat(price2)||0).toFixed(2));
    if (margin) set('pcKpiMargin', margin.textContent);
    if (profit) set('pcSideProfit', profit.textContent);
    set('pcKpiCategory', catText);
    set('pcSideName', name);
    set('pcSideMeta', sku + ' · ' + catText);
    set('pcSidePrice', 'CHF ' + (parseFloat(price2)||0).toFixed(2));
    set('pcSideStock', stock);
    set('pcSideStatus', statusText);
    set('pcSideUnit', unitText);
    set('pcSideBuy', 'CHF ' + (parseFloat(price)||0).toFixed(2));
    set('pcSideSell', 'CHF ' + (parseFloat(price2)||0).toFixed(2));
    if (margin) set('pcSideMargin', margin.textContent);
    var desc = txt('description');
    var score = 0;
    if (name && name!=='Untitled product') score += 30;
    if (desc.length > 20) score += 25;
    if (sku && sku!=='—') score += 15;
    if (catText!=='—') score += 15;
    var img = document.getElementById('image');
    var hasImg = img && img.files && img.files.length;
    var prev = document.getElementById('imagePreview');
    if (hasImg || (prev && prev.style.display!=='none' && prev.src)) score += 15;
    set('pcKpiSeo', String(score));
    set('pcKpiSeoMini', String(score));
    set('pcKpiImages', (hasImg || (prev && prev.style.display!=='none' && prev.src)) ? '1' : '0');
    var bar = document.getElementById('pcSeoBar');
    if (bar) bar.style.width = score + '%';
    set('pcSeoLabel', score>=80 ? 'Strong SEO readiness' : (score>=50 ? 'Good — add more detail' : 'Add name & description to improve score'));
    var checks = { name: !!(txt('name')), category: catText!=='—', price: !!(txt('price2')), image: !!(hasImg || (prev && prev.style.display!=='none' && prev.src)) };
    var done = 0; Object.keys(checks).forEach(function(k){ if(checks[k]) done++; });
    set('pcCompletePct', Math.round((done/4)*100) + '%');
    document.querySelectorAll('#pcChecklistLegacy [data-check], #pcChecklist [data-check]').forEach(function(li){
      var ok = checks[li.getAttribute('data-check')];
      li.classList.toggle('is-done', !!ok);
    });
    var side = document.getElementById('pcSidebar');
    var mob = document.getElementById('pcSidebarMobile');
    if (side && mob) mob.innerHTML = side.innerHTML;
  }
  document.addEventListener('DOMContentLoaded', function(){
    ['name','sku','stock_quantity','price','price2','sale_price','description','category_id','unit_id','status','statusToggle'].forEach(function(id){
      var el = document.getElementById(id);
      if (!el) return;
      el.addEventListener('input', sync);
      el.addEventListener('change', sync);
    });
    var img = document.getElementById('image');
    if (img) img.addEventListener('change', function(){
      var sideImg = document.getElementById('pcSideImg');
      var sideIcon = document.getElementById('pcSideImgIcon');
      if (img.files && img.files[0] && sideImg) {
        sideImg.src = URL.createObjectURL(img.files[0]);
        sideImg.style.display = '';
        if (sideIcon) sideIcon.style.display = 'none';
      }
      sync();
    });
    var rem = document.getElementById('removeImageBtn');
    if (rem) rem.addEventListener('click', function(){
      var sideImg = document.getElementById('pcSideImg');
      var sideIcon = document.getElementById('pcSideImgIcon');
      if (sideImg) { sideImg.src=''; sideImg.style.display='none'; }
      if (sideIcon) sideIcon.style.display='';
      setTimeout(sync, 50);
    });
    if (typeof updatePricingKpis === 'function') {
      var _u = updatePricingKpis;
      updatePricingKpis = function(){ _u(); sync(); };
    }
    document.getElementById('pcSaveDraftBtn')?.addEventListener('click', function(){ toast('Draft saved locally (UI only). Use Save Product to publish.','warning'); });
    document.getElementById('pcDraftBar')?.addEventListener('click', function(){ toast('Draft saved locally (UI only). Use Save Product to publish.','warning'); });
    document.getElementById('pcDuplicateBtn')?.addEventListener('click', function(){ toast('Duplicate is UI-only on create. Save first, then duplicate from the product list.','info'); });
    // Observe margin/profit text changes
    var mo = new MutationObserver(sync);
    ['kpiMargin','kpiProfit','statusPill'].forEach(function(id){
      var n = document.getElementById(id);
      if (n) mo.observe(n, { childList:true, subtree:true, characterData:true });
    });
    sync();
  });
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
