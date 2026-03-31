<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
.edit-shell { background: var(--bg-color); min-height: calc(100vh - 56px); }
.edit-header { position: sticky; top: 0; z-index: 40; backdrop-filter: blur(10px); border-bottom: 1px solid var(--border-color); background: color-mix(in srgb, var(--bg-color) 88%, transparent); }
.edit-title { margin: 0; font-weight: 800; font-size: 1.1rem; }
.edit-subtitle { margin: .1rem 0 0; color: var(--muted-color); font-size: .82rem; }
.edit-card { background: var(--surface-color); border: 1px solid var(--border-color); border-radius: 14px; box-shadow: 0 10px 28px rgba(17,24,39,.06), 0 1px 2px rgba(17,24,39,.05); transition: transform .2s ease, box-shadow .2s ease; }
.edit-card:hover { transform: translateY(-1px); box-shadow: 0 14px 28px rgba(17,24,39,.1); }
.edit-card .card-header { background: transparent; border-bottom: 1px solid var(--border-color); font-weight: 800; font-size: .8rem; letter-spacing: .02em; text-transform: uppercase; color: var(--muted-color); padding: .8rem .9rem; }
.edit-card .card-body { padding: .95rem; }
.edit-shell .form-control, .edit-shell .form-select { min-height: 42px; border-radius: 10px; border-color: var(--border-color); }
.edit-shell .form-control:focus, .edit-shell .form-select:focus { border-color: rgba(37,99,235,.6); box-shadow: 0 0 0 .2rem rgba(37,99,235,.15); }
.required-star { color: #dc2626; margin-left: 2px; }
.pm-select-add { display: flex; gap: .5rem; align-items: stretch; flex-wrap: nowrap; }
.pm-select-add .select2-container { flex: 1 1 auto; min-width: 0; width: auto !important; }
.pm-select-add > .btn { flex: 0 0 auto; border-radius: 10px; min-height: 42px; }
.media-drop { border: 1px dashed rgba(148,163,184,.85); border-radius: 12px; background: var(--surface-muted); padding: .85rem; cursor: pointer; transition: border-color .2s ease, box-shadow .2s ease; }
.media-drop.is-dragover, .media-drop:focus-within { border-color: rgba(37,99,235,.7); box-shadow: 0 0 0 .2rem rgba(37,99,235,.12); }
.media-preview { width: 100%; height: 200px; object-fit: contain; border-radius: 12px; border: 1px solid var(--border-color); background: #fff; }
.kpi-card { border-radius: 12px; border: 1px solid var(--border-color); background: linear-gradient(180deg, rgba(37,99,235,.06), transparent); padding: .9rem; }
.kpi-label { font-size: .72rem; color: var(--muted-color); text-transform: uppercase; letter-spacing: .04em; margin: 0; }
.kpi-value { font-size: 1.15rem; font-weight: 800; margin: .2rem 0 0; font-variant-numeric: tabular-nums; }
.sticky-summary { position: sticky; top: 88px; }
.actionbar { position: sticky; bottom: 0; z-index: 30; border-top: 1px solid var(--border-color); background: color-mix(in srgb, var(--surface-color) 94%, transparent); backdrop-filter: blur(8px); padding: .7rem 0; margin-top: 1rem; }
@media (max-width: 991.98px) { .sticky-summary { position: static; } .pm-select-add { flex-wrap: wrap; } .pm-select-add .select2-container, .pm-select-add > .btn { width: 100%; } }
</style>

<div class="edit-shell">
    <div class="edit-header">
        <div class="container-fluid px-3 px-lg-4 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center" style="gap:10px;">
                <div>
                    <h1 class="edit-title">Edit Product</h1>
                    <p class="edit-subtitle">Update product details with full pricing, inventory and media controls.</p>
                </div>
                <div class="d-flex flex-wrap" style="gap:8px;">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i>Back</a>
                    <button type="button" id="previewBtn" class="btn btn-outline-info"><i class="fas fa-eye mr-1"></i>Preview</button>
                    <button type="button" id="saveContinueBtn" class="btn btn-outline-primary"><i class="fas fa-save mr-1"></i>Save & Continue</button>
                    <button type="button" id="headerSaveBtn" class="btn btn-primary"><i class="fas fa-check mr-1"></i>Update Product</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-3 px-lg-4 py-3 py-lg-4">
        <div id="alert-messages">
            <?php flash('product_success', '', 'alert alert-success alert-dismissible fade show'); ?>
            <?php flash('product_error', '', 'alert alert-danger alert-dismissible fade show'); ?>
        </div>

        <form id="editProductForm" action="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo (int)$product['id']; ?>" method="POST" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="status" id="statusInput" value="<?php echo htmlspecialchars($product['status'] ?? 'active'); ?>">
            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <div class="edit-card card h-100">
                        <div class="card-header">Basic Info</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name <span class="required-star">*</span></label>
                                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" required value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>">
                                <?php if(isset($errors['name'])): ?><div class="invalid-feedback"><?php echo $errors['name']; ?></div><?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category <span class="required-star">*</span></label>
                                <div class="pm-select-add">
                                    <select class="form-select select2 <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        <?php foreach($categories as $category): $catTaxRate = isset($category['tax_rate']) ? $category['tax_rate'] : ''; ?>
                                            <option value="<?php echo (int)$category['id']; ?>" data-tax-rate="<?php echo htmlspecialchars((string)$catTaxRate); ?>" <?php echo ((int)($product['category_id'] ?? 0) === (int)$category['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <a href="<?php echo BASE_URL; ?>?controller=category&action=create" class="btn btn-outline-primary"><i class="fas fa-plus"></i></a>
                                </div>
                                <div class="form-text">Use searchable list or add a new category quickly.</div>
                            </div>
                            <div class="mb-3">
                                <label for="brand_id" class="form-label">Brand</label>
                                <div class="pm-select-add">
                                    <select class="form-select select2" id="brand_id" name="brand_id">
                                        <option value="">Select Brand</option>
                                        <?php
                                            $brandModel = new Brand();
                                            $brands = $brandModel->getActiveBrands();
                                            foreach($brands as $brand):
                                        ?>
                                        <option value="<?php echo (int)$brand['id']; ?>" <?php echo ((int)($product['brand_id'] ?? 0) === (int)$brand['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($brand['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" class="btn btn-outline-primary"><i class="fas fa-plus"></i></a>
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="category_tax_rate" class="form-label">Category Tax Rate</label>
                                <input type="text" class="form-control" id="category_tax_rate" value="" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="edit-card card h-100">
                        <div class="card-header">Pricing & Tax</div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="price" class="form-label">Buying Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">CHF</span>
                                    <input type="text" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars((string)($product['price'] ?? '')); ?>" inputmode="decimal">
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label for="sale_price" class="form-label mb-0">Including Tax Price</label>
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="includingTaxToggle" <?php echo !empty($product['sale_price']) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">CHF</span>
                                    <input type="text" class="form-control" id="sale_price" name="sale_price" value="<?php echo htmlspecialchars((string)($product['sale_price'] ?? '')); ?>" inputmode="decimal">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="price2" class="form-label">Sales Price <span class="required-star">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">CHF</span>
                                    <input type="text" class="form-control fw-bold text-primary" id="price2" name="price2" required value="<?php echo htmlspecialchars((string)($product['price2'] ?? $product['price'] ?? '')); ?>" inputmode="decimal">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="price3" class="form-label">Wholesale Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">CHF</span>
                                    <input type="text" class="form-control" id="price3" name="price3" value="<?php echo htmlspecialchars((string)($product['price3'] ?? $product['price'] ?? '')); ?>" inputmode="decimal">
                                </div>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="customs_charge" class="form-label">Customs Charge</label>
                                    <div class="input-group">
                                        <span class="input-group-text">CHF</span>
                                        <input type="text" class="form-control" id="customs_charge" name="customs_charge" value="<?php echo htmlspecialchars((string)($product['customs_charge'] ?? '')); ?>" inputmode="decimal">
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="transport_charge" class="form-label">Transport Charge</label>
                                    <div class="input-group">
                                        <span class="input-group-text">CHF</span>
                                        <input type="text" class="form-control" id="transport_charge" name="transport_charge" value="<?php echo htmlspecialchars((string)($product['transport_charge'] ?? '')); ?>" inputmode="decimal">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-0">
                                <label for="tax_id" class="form-label">Tax Rate</label>
                                <select class="form-select select2" id="tax_id" name="tax_id">
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
                                    ?>
                                    <option value="<?php echo htmlspecialchars((string)$tid); ?>" data-rate="<?php echo htmlspecialchars((string)$trate); ?>" <?php echo ((string)($product['tax_id'] ?? '') === (string)$tid) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($label); ?>
                                    </option>
                                    <?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="sticky-summary d-flex flex-column" style="gap:12px;">
                        <div class="edit-card card">
                            <div class="card-header">Inventory & Media</div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU <span class="required-star">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="sku" name="sku" required value="<?php echo htmlspecialchars($product['sku'] ?? ''); ?>">
                                        <button type="button" class="btn btn-outline-secondary" id="generateSkuBtn"><i class="fas fa-wand-magic-sparkles mr-1"></i>Generate</button>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" value="<?php echo htmlspecialchars((string)($product['stock_quantity'] ?? 0)); ?>">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="expiry_date" class="form-label">Expiry Date</label>
                                        <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo htmlspecialchars((string)($product['expiry_date'] ?? '')); ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="unit_id" class="form-label">Unit</label>
                                    <div class="pm-select-add">
                                        <select class="form-select select2" id="unit_id" name="unit_id">
                                            <option value="">Select Unit</option>
                                            <?php if (!empty($units)): foreach ($units as $unit):
                                                $unitId = is_array($unit) ? ($unit['id'] ?? null) : ($unit->id ?? null);
                                                $unitName = is_array($unit) ? ($unit['name'] ?? '') : ($unit->name ?? '');
                                                $unitShort = is_array($unit) ? ($unit['short_name'] ?? '') : ($unit->short_name ?? '');
                                            ?>
                                            <option value="<?php echo htmlspecialchars((string)$unitId); ?>" <?php echo ((string)($product['unit_id'] ?? '') === (string)$unitId) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars(trim($unitName . ($unitShort ? ' (' . $unitShort . ')' : ''))); ?>
                                            </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-toggle="modal" data-bs-target="#addUnitModal" data-target="#addUnitModal"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="supplier" class="form-label">Supplier</label>
                                    <div class="pm-select-add">
                                        <select class="form-select select2" id="supplier" name="supplier">
                                            <option value="">Select Supplier</option>
                                            <?php if(!empty($suppliers)): foreach($suppliers as $supplier):
                                                $value = htmlspecialchars($supplier['name']);
                                            ?>
                                            <option value="<?php echo $value; ?>" <?php echo (isset($product['supplier']) && $product['supplier'] === $supplier['name']) ? 'selected' : ''; ?>><?php echo $value; ?></option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                        <a href="<?php echo BASE_URL; ?>?controller=supplier&action=index" class="btn btn-outline-primary"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">Country</label>
                                    <div class="pm-select-add">
                                        <select class="form-select select2" id="country_id" name="country_id">
                                            <option value="">Select Country</option>
                                            <?php
                                                $countryModel = new Country();
                                                $countries = $countryModel->getActiveCountries();
                                                if (!empty($countries)):
                                                    foreach ($countries as $country):
                                            ?>
                                            <option value="<?php echo (int)$country['id']; ?>" <?php echo ((int)($product['country_id'] ?? 0) === (int)$country['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($country['name']); ?>
                                            </option>
                                            <?php endforeach; endif; ?>
                                        </select>
                                        <a href="<?php echo BASE_URL; ?>?controller=country&action=adminIndex" class="btn btn-outline-primary"><i class="fas fa-plus"></i></a>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="batch_number" class="form-label">Batch</label>
                                        <input type="text" class="form-control" id="batch_number" name="batch_number" value="<?php echo htmlspecialchars((string)($product['batch_number'] ?? '')); ?>">
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="hsn_code" class="form-label">HSS Code</label>
                                        <input type="text" class="form-control" id="hsn_code" name="hsn_code" value="<?php echo htmlspecialchars((string)($product['hsn_code'] ?? '')); ?>">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <label class="form-label mb-0" for="statusToggle">Status</label>
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="statusToggle" <?php echo (($product['status'] ?? 'active') === 'active') ? 'checked' : ''; ?>>
                                        </div>
                                    </div>
                                    <div class="form-text"><span id="statusLabel"><?php echo (($product['status'] ?? 'active') === 'active') ? 'Active' : 'Inactive'; ?></span></div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">Product Image</label>
                                    <div id="mediaDrop" class="media-drop" tabindex="0">
                                        <img id="imagePreview" class="media-preview" src="<?php echo !empty($product['image']) ? (BASE_URL . $product['image']) : ''; ?>" style="<?php echo !empty($product['image']) ? '' : 'display:none;'; ?>" alt="Product Image">
                                        <div id="imagePreviewIcon" class="text-center text-muted py-4" style="<?php echo !empty($product['image']) ? 'display:none;' : ''; ?>">
                                            <i class="fas fa-cloud-upload-alt fa-2x"></i>
                                            <div class="mt-2">Drop image or click to upload</div>
                                        </div>
                                        <div class="d-flex mt-2" style="gap:8px;">
                                            <button type="button" class="btn btn-outline-primary btn-sm" id="chooseImageBtn"><i class="fas fa-upload mr-1"></i>Change</button>
                                            <button type="button" class="btn btn-outline-secondary btn-sm" id="removeImageBtn" <?php echo empty($product['image']) ? 'disabled' : ''; ?>><i class="fas fa-times mr-1"></i>Remove</button>
                                        </div>
                                        <input type="file" class="d-none" id="image" name="image" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="kpi-card">
                            <p class="kpi-label">Total Cost</p>
                            <p class="kpi-value" id="sumTotalCost">CHF 0.00</p>
                            <div class="row g-2 mt-1">
                                <div class="col-6">
                                    <p class="kpi-label">Profit</p>
                                    <p class="kpi-value" id="sumProfit">CHF 0.00</p>
                                </div>
                                <div class="col-6">
                                    <p class="kpi-label">Margin</p>
                                    <p class="kpi-value" id="sumMargin">0%</p>
                                </div>
                            </div>
                            <div class="small text-muted mt-2" id="lastUpdatedInfo">Last updated: <?php echo !empty($product['updated_at']) ? htmlspecialchars($product['updated_at']) : 'N/A'; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="actionbar">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center" style="gap:10px;">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary">Back</a>
                    <div class="d-flex flex-column flex-sm-row" style="gap:8px;">
                        <button type="button" id="cancelChangesBtn" class="btn btn-outline-secondary">Cancel</button>
                        <button type="submit" id="submitBtn" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Update Product</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Unit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addUnitForm">
                    <div class="mb-2">
                        <label class="form-label" for="new_unit_name">Unit Name</label>
                        <input type="text" class="form-control" id="new_unit_name" name="unit_name" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="new_unit_short_name">Short Name</label>
                        <input type="text" class="form-control" id="new_unit_short_name" name="short_name" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label" for="new_allow_decimal">Allow Decimal</label>
                        <select class="form-select" id="new_allow_decimal" name="allow_decimal" required>
                            <option value="">Select</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal" data-dismiss="modal" type="button">Close</button>
                <button class="btn btn-primary" id="saveUnitBtn" type="button">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="productPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-12 col-md-5"><img id="previewImageModal" src="" alt="" class="img-fluid rounded border"></div>
                    <div class="col-12 col-md-7">
                        <h5 id="previewNameModal" class="mb-1"></h5>
                        <p class="text-muted mb-2" id="previewMetaModal"></p>
                        <div><strong>Sales Price:</strong> <span id="previewPriceModal"></span></div>
                        <div><strong>Stock:</strong> <span id="previewStockModal"></span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editProductForm');
    const submitBtn = document.getElementById('submitBtn');
    const saveContinueBtn = document.getElementById('saveContinueBtn');
    const headerSaveBtn = document.getElementById('headerSaveBtn');
    const previewBtn = document.getElementById('previewBtn');
    const statusToggle = document.getElementById('statusToggle');
    const statusInput = document.getElementById('statusInput');
    const statusLabel = document.getElementById('statusLabel');
    const includingTaxToggle = document.getElementById('includingTaxToggle');
    const salePriceInput = document.getElementById('sale_price');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewIcon = document.getElementById('imagePreviewIcon');
    const mediaDrop = document.getElementById('mediaDrop');
    const chooseImageBtn = document.getElementById('chooseImageBtn');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const cancelBtn = document.getElementById('cancelChangesBtn');
    const unitSelect = document.getElementById('unit_id');
    let stayAfterSave = true;

    try { if (window.jQuery && $.fn && $.fn.select2) $('.select2').select2({ width: '100%' }); } catch (e) {}

    function setIncludingTaxEnabled(enabled) {
        if (!salePriceInput) return;
        salePriceInput.disabled = !enabled;
        salePriceInput.style.opacity = enabled ? '1' : '.6';
        if (!enabled) salePriceInput.value = '';
    }
    if (includingTaxToggle) {
        setIncludingTaxEnabled(!!includingTaxToggle.checked);
        includingTaxToggle.addEventListener('change', function() { setIncludingTaxEnabled(!!includingTaxToggle.checked); updateSummary(); });
    }

    if (statusToggle) {
        statusToggle.addEventListener('change', function() {
            const status = this.checked ? 'active' : 'inactive';
            statusInput.value = status;
            if (statusLabel) statusLabel.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        });
    }

    function updateCategoryTaxRateUI() {
        const categorySelect = document.getElementById('category_id');
        const out = document.getElementById('category_tax_rate');
        if (!categorySelect || !out) return;
        const opt = categorySelect.options && categorySelect.selectedIndex >= 0 ? categorySelect.options[categorySelect.selectedIndex] : null;
        const rate = opt ? (opt.getAttribute('data-tax-rate') || '') : '';
        out.value = rate && rate.trim() !== '' ? (rate.trim() + '%') : '—';
    }
    const categorySelectEl = document.getElementById('category_id');
    if (categorySelectEl) {
        categorySelectEl.addEventListener('change', function() { updateCategoryTaxRateUI(); updateSummary(); });
        try { $(categorySelectEl).on('change.select2', function() { updateCategoryTaxRateUI(); updateSummary(); }); } catch (e) {}
    }
    updateCategoryTaxRateUI();

    function toNum(v) { const n = parseFloat(String(v || '').replace(/[^0-9.\-]/g, '')); return isNaN(n) ? 0 : n; }
    function fmtCHF(n) { return 'CHF ' + (isNaN(n) ? 0 : n).toFixed(2); }
    function getTaxRate() {
        const tax = document.getElementById('tax_id');
        const opt = tax && tax.selectedIndex >= 0 ? tax.options[tax.selectedIndex] : null;
        const r = parseFloat(opt ? (opt.getAttribute('data-rate') || '0') : '0');
        return isNaN(r) ? 0 : r;
    }
    function updateSummary() {
        const buying = toNum(document.getElementById('price')?.value);
        const customs = toNum(document.getElementById('customs_charge')?.value);
        const transport = toNum(document.getElementById('transport_charge')?.value);
        const sales = toNum(document.getElementById('price2')?.value);
        const rate = getTaxRate();
        const totalCost = buying + customs + transport + (buying * rate / 100);
        const profit = sales - totalCost;
        const margin = sales > 0 ? (profit / sales) * 100 : 0;
        const totalEl = document.getElementById('sumTotalCost');
        const profitEl = document.getElementById('sumProfit');
        const marginEl = document.getElementById('sumMargin');
        if (totalEl) totalEl.textContent = fmtCHF(totalCost);
        if (profitEl) { profitEl.textContent = fmtCHF(profit); profitEl.style.color = profit >= 0 ? '#198754' : '#dc3545'; }
        if (marginEl) { marginEl.textContent = (isNaN(margin) ? '0%' : margin.toFixed(1) + '%'); marginEl.style.color = margin >= 0 ? '#198754' : '#dc3545'; }
    }
    ['price','customs_charge','transport_charge','price2','tax_id','sale_price'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) { el.addEventListener('input', updateSummary); el.addEventListener('change', updateSummary); }
    });
    updateSummary();

    document.getElementById('generateSkuBtn')?.addEventListener('click', function() {
        const name = (document.getElementById('name')?.value || '').trim();
        const base = (name ? name.replace(/[^A-Za-z0-9]/g, '').toUpperCase().substring(0, 10) : 'SKU') || 'SKU';
        document.getElementById('sku').value = base + (Date.now() % 100000).toString();
    });

    function setImagePreview(file) {
        if (!file) {
            imagePreview.src = '';
            imagePreview.style.display = 'none';
            imagePreviewIcon.style.display = '';
            if (removeImageBtn) removeImageBtn.disabled = true;
            return;
        }
        const url = URL.createObjectURL(file);
        imagePreview.src = url;
        imagePreview.style.display = '';
        imagePreviewIcon.style.display = 'none';
        if (removeImageBtn) removeImageBtn.disabled = false;
    }
    function openImagePicker() { imageInput?.click(); }
    if (mediaDrop) {
        mediaDrop.addEventListener('click', function(e) { if (e.target.closest('#removeImageBtn')) return; openImagePicker(); });
        mediaDrop.addEventListener('keydown', function(e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openImagePicker(); } });
        ['dragenter','dragover'].forEach(evt => mediaDrop.addEventListener(evt, function(e){ e.preventDefault(); mediaDrop.classList.add('is-dragover'); }));
        ['dragleave','drop'].forEach(evt => mediaDrop.addEventListener(evt, function(e){ e.preventDefault(); mediaDrop.classList.remove('is-dragover'); }));
        mediaDrop.addEventListener('drop', function(e) {
            const file = e.dataTransfer?.files?.[0] || null;
            if (!file) return;
            imageInput.files = e.dataTransfer.files;
            setImagePreview(file);
        });
    }
    chooseImageBtn?.addEventListener('click', openImagePicker);
    removeImageBtn?.addEventListener('click', function(e){ e.preventDefault(); if (imageInput) imageInput.value=''; setImagePreview(null); });
    imageInput?.addEventListener('change', function(){ setImagePreview(imageInput.files?.[0] || null); });

    function showAlert(message, type) {
        const alertMessages = document.getElementById('alert-messages');
        if (!alertMessages) return;
        alertMessages.querySelectorAll('.alert').forEach(a => a.remove());
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-' + (type || 'success') + ' alert-dismissible fade show';
        alertDiv.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert" data-dismiss="alert"></button>';
        alertMessages.prepend(alertDiv);
        setTimeout(function(){ try { $(alertDiv).alert('close'); } catch(e) {} }, 4000);
    }
    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.className = 'toast align-items-center text-white ' + ((type === 'danger') ? 'bg-danger' : 'bg-success') + ' border-0 position-fixed top-0 end-0 m-3';
        toast.innerHTML = '<div class="d-flex"><div class="toast-body">' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" data-dismiss="toast"></button></div>';
        document.body.appendChild(toast);
        try { bootstrap.Toast.getOrCreateInstance(toast, { delay: 2800 }).show(); } catch(e) {}
        toast.addEventListener('hidden.bs.toast', function(){ toast.remove(); });
    }

    function submitForm() {
        if (!form) return;
        if (!confirm('Are you sure you want to update this product?')) return;
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        saveContinueBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-1"></span>Saving...';
        const formData = new FormData(form);
        formData.set('status', statusInput.value || 'active');
        fetch(form.action, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.json())
            .then(function(res){
                if (!res || !res.success) throw new Error(res?.message || 'Failed to update');
                showAlert(res.message || 'Updated successfully', 'success');
                showToast('Product updated successfully', 'success');
                if (res.meta?.last_updated) {
                    const info = document.getElementById('lastUpdatedInfo');
                    if (info) info.textContent = 'Last updated: ' + res.meta.last_updated;
                }
                if (!stayAfterSave) {
                    setTimeout(function(){ window.location.href = '<?php echo BASE_URL; ?>?controller=product&action=adminIndex'; }, 350);
                }
            })
            .catch(function(err){
                showAlert(err.message || 'Update failed', 'danger');
                showToast(err.message || 'Update failed', 'danger');
            })
            .finally(function(){
                submitBtn.disabled = false;
                saveContinueBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
    }

    form?.addEventListener('submit', function(e){ e.preventDefault(); stayAfterSave = true; submitForm(); });
    saveContinueBtn?.addEventListener('click', function(){ stayAfterSave = true; submitForm(); });
    submitBtn?.addEventListener('click', function(){ stayAfterSave = false; });
    headerSaveBtn?.addEventListener('click', function(){ stayAfterSave = false; submitForm(); });
    cancelBtn?.addEventListener('click', function(){ if (confirm('Discard unsaved changes?')) window.location.href = '<?php echo BASE_URL; ?>?controller=product&action=adminIndex'; });

    previewBtn?.addEventListener('click', function() {
        const modalEl = document.getElementById('productPreviewModal');
        document.getElementById('previewNameModal').textContent = document.getElementById('name')?.value || 'Product';
        const catSel = document.getElementById('category_id');
        const catText = catSel && catSel.selectedIndex >= 0 ? catSel.options[catSel.selectedIndex].text : '';
        const sup = document.getElementById('supplier')?.value || '';
        document.getElementById('previewMetaModal').textContent = [catText, sup ? ('Supplier: ' + sup) : ''].filter(Boolean).join(' | ');
        document.getElementById('previewPriceModal').textContent = 'CHF ' + (document.getElementById('price2')?.value || '0.00');
        document.getElementById('previewStockModal').textContent = document.getElementById('stock_quantity')?.value || '0';
        document.getElementById('previewImageModal').src = imagePreview?.src || '';
        try { $(modalEl).modal('show'); } catch(e) {}
    });

    const addUnitForm = document.getElementById('addUnitForm');
    document.getElementById('saveUnitBtn')?.addEventListener('click', function() {
        const unitData = new FormData(addUnitForm);
        const name = (unitData.get('unit_name') || '').toString().trim();
        const shortName = (unitData.get('short_name') || '').toString().trim();
        const allowDecimal = (unitData.get('allow_decimal') || '').toString();
        if (!name || !shortName || allowDecimal === '') { showAlert('Please complete all unit fields', 'danger'); return; }
        fetch('<?php echo BASE_URL; ?>?controller=unit&action=create', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin', body: unitData })
            .then(r => r.json())
            .then(function(res){
                if (!res || !res.success) throw new Error(res?.message || 'Failed to create unit');
                if (unitSelect) {
                    const label = (res.name || '') + (res.short_name ? (' (' + res.short_name + ')') : '');
                    const opt = new Option(label.trim(), String(res.id), true, true);
                    unitSelect.appendChild(opt);
                    try { $(unitSelect).trigger('change.select2'); } catch(e) {}
                }
                addUnitForm.reset();
                try { $('#addUnitModal').modal('hide'); } catch(e) {}
                showToast('Unit added', 'success');
            })
            .catch(function(err){ showAlert(err.message || 'Unable to add unit', 'danger'); });
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
