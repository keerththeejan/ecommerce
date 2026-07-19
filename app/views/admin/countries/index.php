<?php
/**
 * Country Management — Enterprise Admin UI (visual layer only)
 * Preserves: #countryForm, field names/IDs, #countriesTable, .edit-country, .delete-country, delete modal.
 */
require_once APP_PATH . 'views/admin/layouts/header.php';

$countries = $data['countries'] ?? [];
$selectedCountry = $data['selectedCountry'] ?? null;
$totalCountries = count($countries);
$activeCount = 0;
$inactiveCount = 0;
$withFlag = 0;
$withProducts = 0;
foreach ($countries as $c) {
    $st = strtolower((string)($c['status'] ?? ''));
    if ($st === 'active' || $st === '1') {
        $activeCount++;
    } else {
        $inactiveCount++;
    }
    if (!empty($c['flag_image'])) {
        $withFlag++;
    }
    if (!empty($c['products_count']) && (int)$c['products_count'] > 0) {
        $withProducts++;
    }
}
$noFlag = max(0, $totalCountries - $withFlag);
$activePct = $totalCountries > 0 ? round(($activeCount / $totalCountries) * 100) : 0;
$flagPct = $totalCountries > 0 ? round(($withFlag / $totalCountries) * 100) : 0;
$unused = max(0, $totalCountries - $withProducts);

$editMode = false;
$editId = '';
$editName = '';
$editStatusActive = true;
$editFlagUrl = 'https://flagcdn.com/24x18/xx.png';
if ($selectedCountry && !empty($selectedCountry['id']) && isset($_GET['id'])) {
    $editMode = true;
    $editId = (int)$selectedCountry['id'];
    $editName = htmlspecialchars($selectedCountry['name'] ?? '');
    $editStatusActive = (strtolower((string)($selectedCountry['status'] ?? '')) === 'active');
    if (!empty($selectedCountry['flag_image'])) {
        $editFlagUrl = BASE_URL . 'uploads/flags/' . $selectedCountry['flag_image'];
    }
}
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/country-list.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid country-list-page py-3 py-md-4 px-2 px-sm-3" id="countryAdminPage">
    <div class="cl-toast-host" id="clToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="cl-header">
        <div>
            <nav class="cl-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span>›</span>
                <span>Settings</span>
                <span>›</span>
                <span aria-current="page">Countries</span>
            </nav>
            <h1 class="cl-title">Country Management</h1>
            <p class="cl-subtitle">Manage countries of origin, flags, and availability for your product catalog.</p>
        </div>
        <div class="cl-actions">
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clRefreshBtn" title="Refresh" onclick="location.reload()">
                <i class="bi bi-arrow-clockwise"></i><span class="d-none d-md-inline">Refresh</span>
            </button>
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clPrintBtn" title="Print" onclick="window.print()">
                <i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span>
            </button>
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clExportCsvBtn" title="Excel">
                <i class="bi bi-file-earmark-spreadsheet"></i><span class="d-none d-lg-inline">Excel</span>
            </button>
            <button type="button" class="btn btn-outline-secondary cl-btn" onclick="window.print()" title="PDF">
                <i class="bi bi-filetype-pdf"></i><span class="d-none d-lg-inline">PDF</span>
            </button>
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clImportBtn" title="Import (UI)">
                <i class="bi bi-upload"></i><span class="d-none d-xl-inline">Import</span>
            </button>
            <button type="button" class="btn cl-btn cl-btn-primary" id="clAddCountryBtn">
                <i class="bi bi-plus-lg"></i><span>Add Country</span>
            </button>
        </div>
    </div>

    <?php flash('country_message'); ?>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-3">
            <div class="cl-stat s1"><div class="icon" aria-hidden="true"><i class="bi bi-globe-americas"></i></div><div><div class="label">Total Countries</div><div class="value" data-counter="<?php echo (int)$totalCountries; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="cl-stat s2"><div class="icon" aria-hidden="true"><i class="bi bi-check-circle"></i></div><div><div class="label">Active Countries</div><div class="value" data-counter="<?php echo (int)$activeCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="cl-stat s3"><div class="icon" aria-hidden="true"><i class="bi bi-x-circle"></i></div><div><div class="label">Inactive Countries</div><div class="value" data-counter="<?php echo (int)$inactiveCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="cl-stat s4"><div class="icon" aria-hidden="true"><i class="bi bi-flag"></i></div><div><div class="label">Countries with Flag</div><div class="value" data-counter="<?php echo (int)$withFlag; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="cl-stat s5"><div class="icon" aria-hidden="true"><i class="bi bi-flag-fill"></i></div><div><div class="label">Missing Flag</div><div class="value" data-counter="<?php echo (int)$noFlag; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="cl-stat s6"><div class="icon" aria-hidden="true"><i class="bi bi-box-seam"></i></div><div><div class="label">In Use (Products)</div><div class="value" data-counter="<?php echo (int)$withProducts; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="cl-stat s7"><div class="icon" aria-hidden="true"><i class="bi bi-pie-chart"></i></div><div><div class="label">Active Rate %</div><div class="value" data-counter="<?php echo (int)$activePct; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="cl-stat s8"><div class="icon" aria-hidden="true"><i class="bi bi-image"></i></div><div><div class="label">Flag Coverage %</div><div class="value" data-counter="<?php echo (int)$flagPct; ?>">0</div></div></div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-4">
            <div class="cl-card cl-form-card h-100">
                <div class="cl-card-header">
                    <h2 id="countryFormTitle"><?php echo $editMode ? 'Edit Country' : 'Add New Country'; ?></h2>
                    <button type="button" class="btn btn-sm btn-outline-secondary <?php echo $editMode ? '' : 'd-none'; ?>" id="cancelEditBtn">
                        <i class="bi bi-x-lg me-1"></i>Cancel
                    </button>
                </div>
                <div class="cl-card-body">
                    <form id="countryForm" action="?controller=country&action=<?php echo $editMode ? 'update' : 'create'; ?>" method="POST" enctype="multipart/form-data" novalidate>
                        <input type="hidden" name="id" id="country_id" value="<?php echo $editId; ?>">

                        <div class="mb-3">
                            <label class="form-label" for="country_name">Country Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="country_name"
                                    name="name"
                                    list="countryNameList"
                                    placeholder="e.g. India, United States"
                                    value="<?php echo $editName; ?>"
                                    required
                                    autocomplete="off"
                                    aria-describedby="countryNameHelp"
                                >
                            </div>
                            <div id="countryNameHelp" class="form-text">Must be unique. Used as origin for products.</div>
                            <div class="invalid-feedback">Please enter a country name.</div>
                            <datalist id="countryNameList">
                                <?php foreach ($countries as $c): ?>
                                    <option value="<?php echo htmlspecialchars($c['name'] ?? ''); ?>"></option>
                                <?php endforeach; ?>
                            </datalist>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="flag_image">Flag Image</label>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <img
                                    id="flagPreview"
                                    class="table-flag"
                                    src="<?php echo htmlspecialchars($editFlagUrl); ?>"
                                    alt="Flag preview"
                                    style="width:48px;height:36px;<?php echo $editMode ? '' : 'opacity:.55;'; ?>"
                                >
                                <div class="small text-muted">JPG, PNG, GIF, or WEBP · Max 2MB</div>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="flag_image" name="flag_image" accept="image/*">
                                <label class="custom-file-label" for="flag_image">Choose image</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="status" name="status" value="1" <?php echo $editStatusActive ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="status">Active (visible for product origin)</label>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn cl-btn cl-btn-primary" id="countrySubmitBtn">
                                <?php if ($editMode): ?>
                                    <i class="fas fa-save me-2"></i> Update Country
                                <?php else: ?>
                                    <i class="fas fa-plus me-2"></i> Add New Country
                                <?php endif; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="cl-card">
                <div class="cl-card-body pt-2">
                    <div class="cl-toolbar">
                        <div class="d-flex align-items-center flex-wrap gap-2" style="flex:1 1 280px;">
                            <div class="input-group input-group-sm" style="max-width: 360px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="clLiveSearch" placeholder="Search country name or status…" aria-label="Search countries" autocomplete="off">
                                <button type="button" class="btn btn-outline-secondary" id="clSearchClear">Clear</button>
                            </div>
                            <select class="form-select form-select-sm" id="clFilterStatus" aria-label="Filter by status" style="width:auto;min-width:120px;">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <select class="form-select form-select-sm" id="clFilterFlag" aria-label="Filter by flag" style="width:auto;min-width:120px;">
                                <option value="">All Flags</option>
                                <option value="yes">Has Flag</option>
                                <option value="no">No Flag</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="clResetFilters" title="Reset filters">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                            <span class="small text-muted" id="clResultCount"></span>
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <label for="clPageSize" class="form-label small text-muted mb-0">Rows</label>
                            <select id="clPageSize" class="form-select form-select-sm" style="width:auto;" aria-label="Rows per page">
                                <option value="10">10</option>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">All</option>
                            </select>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Columns">
                                    <i class="bi bi-layout-three-columns"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 cl-col-toggle" data-col="1" checked> Flag</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 cl-col-toggle" data-col="2" checked> Country</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 cl-col-toggle" data-col="3" checked> Status</label></li>
                                    <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 cl-col-toggle" data-col="4" checked> Actions</label></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="cl-bulk-bar" id="clBulkBar" aria-live="polite">
                        <strong><span id="clSelectedCount">0</span> selected</strong>
                        <button type="button" class="btn btn-sm btn-outline-success" id="clBulkActivate">Activate</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clBulkDeactivate">Deactivate</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="clBulkDelete">Delete</button>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="clBulkExport">Export Selected</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clBulkClear">Clear</button>
                    </div>

                    <div class="countries-table-scroll">
                        <table class="table table-hover mb-0" id="countriesTable" aria-label="Countries table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width:40px;" data-label="Select">
                                        <input type="checkbox" class="form-check-input" id="clSelectAll" aria-label="Select all countries">
                                    </th>
                                    <th scope="col" class="sortable" data-sort="flag" style="width:72px;">Flag</th>
                                    <th scope="col" class="sortable" data-sort="name">Country Name</th>
                                    <th scope="col" class="sortable" data-sort="status" style="width:120px;">Status</th>
                                    <th scope="col" class="text-end" style="width:200px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($countries)): ?>
                                    <?php foreach ($countries as $country):
                                        $status = strtolower((string)($country['status'] ?? 'inactive'));
                                        $isActive = ($status === 'active' || $status === '1');
                                        $countryName = htmlspecialchars($country['name'] ?? '');
                                        $flagUrl = 'https://flagcdn.com/24x18/xx.png';
                                        $hasFlag = false;
                                        if (!empty($country['flag_image'])) {
                                            $flagUrl = BASE_URL . 'uploads/flags/' . $country['flag_image'];
                                            $hasFlag = true;
                                        }
                                        $productsCount = (int)($country['products_count'] ?? 0);
                                        $canDelete = $productsCount <= 0;
                                    ?>
                                    <tr class="country-row"
                                        data-id="<?php echo (int)$country['id']; ?>"
                                        data-name="<?php echo $countryName; ?>"
                                        data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>"
                                        data-flag="<?php echo $hasFlag ? 'yes' : 'no'; ?>"
                                        data-products="<?php echo $productsCount; ?>">
                                        <td data-label="Select" onclick="event.stopPropagation();">
                                            <input type="checkbox" class="form-check-input cl-row-check" value="<?php echo (int)$country['id']; ?>" aria-label="Select <?php echo $countryName; ?>">
                                        </td>
                                        <td data-label="Flag">
                                            <img
                                                src="<?php echo htmlspecialchars($flagUrl); ?>"
                                                alt="<?php echo $countryName; ?> flag"
                                                class="table-flag"
                                                loading="lazy"
                                                onerror="this.onerror=null;this.src='https://flagcdn.com/24x18/xx.png';"
                                            >
                                        </td>
                                        <td data-label="Country Name">
                                            <div class="cl-name-cell">
                                                <div>
                                                    <div class="brand-name"><?php echo $countryName; ?></div>
                                                    <div class="meta">
                                                        <?php if ($productsCount > 0): ?>
                                                            <i class="bi bi-box-seam me-1"></i><?php echo $productsCount; ?> product<?php echo $productsCount === 1 ? '' : 's'; ?>
                                                        <?php else: ?>
                                                            No products linked
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge-status <?php echo $isActive ? 'badge-status--active' : 'badge-status--inactive'; ?> status-badge">
                                                <?php echo $isActive ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td>
                                        <td data-label="Actions" class="text-nowrap text-end" onclick="event.stopPropagation();">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Country actions">
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-primary edit-country"
                                                    data-id="<?php echo (int)$country['id']; ?>"
                                                    data-name="<?php echo $countryName; ?>"
                                                    data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>"
                                                    data-flag-url="<?php echo htmlspecialchars($flagUrl); ?>"
                                                    data-has-products="<?php echo $productsCount; ?>"
                                                    title="Edit"
                                                    aria-label="Edit <?php echo $countryName; ?>"
                                                >
                                                    <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Edit</span>
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-danger delete-country"
                                                    data-id="<?php echo (int)$country['id']; ?>"
                                                    data-name="<?php echo $countryName; ?>"
                                                    data-disabled="<?php echo $canDelete ? '0' : '1'; ?>"
                                                    title="<?php echo $canDelete ? 'Delete' : 'Cannot delete — has products'; ?>"
                                                    aria-label="Delete <?php echo $countryName; ?>"
                                                >
                                                    <i class="fas fa-trash"></i> <span class="d-none d-md-inline">Delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5">
                                            <div class="cl-empty">
                                                <i class="bi bi-globe2 d-block mb-2" style="font-size:2rem;opacity:.4;"></i>
                                                No countries found. Add your first country of origin.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-topbar border-0 border-top">
                        <div class="small text-muted" id="clPageInfo">Showing 0–0 of 0</div>
                        <nav aria-label="Countries pagination">
                            <ul class="pagination pagination-sm mb-0" id="clPagination"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="cl-chart-card">
                <h3>Status Mix</h3>
                <canvas id="clStatusChart" height="200" aria-label="Status chart"></canvas>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="cl-chart-card">
                <h3>Flag Coverage</h3>
                <canvas id="clFlagChart" height="200" aria-label="Flag coverage chart"></canvas>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="cl-chart-card">
                <h3>Catalog Usage</h3>
                <ul class="cl-side-list">
                    <li><span class="k">Countries with products</span><span class="v"><?php echo (int)$withProducts; ?></span></li>
                    <li><span class="k">Unused countries</span><span class="v"><?php echo (int)$unused; ?></span></li>
                    <li><span class="k">Active rate</span><span class="v"><?php echo (int)$activePct; ?>%</span></li>
                    <li><span class="k">Flag coverage</span><span class="v"><?php echo (int)$flagPct; ?>%</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteCountryModal" tabindex="-1" role="dialog" aria-labelledby="deleteCountryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:14px;overflow:hidden;">
            <div class="modal-header" style="background:rgba(220,53,69,0.08);">
                <h5 class="modal-title" id="deleteCountryModalLabel"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Delete Country</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">Are you sure you want to delete <strong id="countryNameToDelete"></strong>?</div>
                <div class="text-muted small" id="deleteCountryHint">This action cannot be undone.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                <form id="deleteCountryForm" action="?controller=country&action=delete" method="POST" class="mb-0">
                    <input type="hidden" name="id" id="deleteCountryId" value="">
                    <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function() {
    var form = document.getElementById('countryForm');
    var formTitle = document.getElementById('countryFormTitle');
    var cancelEditBtn = document.getElementById('cancelEditBtn');
    var idInput = document.getElementById('country_id');
    var nameInput = document.getElementById('country_name');
    var statusInput = document.getElementById('status');
    var submitBtn = document.getElementById('countrySubmitBtn');
    var fileInput = document.getElementById('flag_image');
    var fileLabel = document.querySelector('label.custom-file-label[for="flag_image"]');
    var preview = document.getElementById('flagPreview');

    function setModeAdd() {
        if (!form) return;
        form.action = '?controller=country&action=create';
        if (idInput) idInput.value = '';
        if (formTitle) formTitle.textContent = 'Add New Country';
        if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-plus me-2"></i> Add New Country';
        if (cancelEditBtn) cancelEditBtn.classList.add('d-none');
        if (nameInput) nameInput.value = '';
        if (statusInput) statusInput.checked = true;
        if (preview) { preview.src = 'https://flagcdn.com/24x18/xx.png'; preview.style.opacity = '.55'; }
        if (fileInput) fileInput.value = '';
        if (fileLabel) fileLabel.textContent = 'Choose image';
        if (nameInput) { nameInput.classList.remove('is-invalid'); }
    }

    function setModeEdit(country) {
        if (!form || !country) return;
        form.action = '?controller=country&action=update';
        if (idInput) idInput.value = country.id || '';
        if (nameInput) nameInput.value = country.name || '';
        if (statusInput) statusInput.checked = (country.status === 'active');
        if (preview && country.flagUrl) { preview.src = country.flagUrl; preview.style.opacity = '1'; }
        if (formTitle) formTitle.textContent = 'Edit Country';
        if (submitBtn) submitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Update Country';
        if (cancelEditBtn) cancelEditBtn.classList.remove('d-none');
        if (fileInput) fileInput.value = '';
        if (fileLabel) fileLabel.textContent = 'Choose image';
        try { window.scrollTo({ top: 0, behavior: 'smooth' }); } catch (e) {}
    }

    var addBtn = document.getElementById('clAddCountryBtn');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            setModeAdd();
            try { nameInput && nameInput.focus(); } catch (e) {}
            try { window.scrollTo({ top: 0, behavior: 'smooth' }); } catch (e) {}
        });
    }

    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function() {
            setModeAdd();
            try { nameInput && nameInput.focus(); } catch (e) {}
        });
    }

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (fileLabel && fileInput.files && fileInput.files[0]) {
                fileLabel.textContent = fileInput.files[0].name;
            }
            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) { preview.src = e.target.result; preview.style.opacity = '1'; }
                };
                reader.readAsDataURL(fileInput.files[0]);
            }
        });
    }

    function showDeleteModal() {
        var modalEl = document.getElementById('deleteCountryModal');
        if (!modalEl) return;
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (window.jQuery) {
            window.jQuery(modalEl).modal('show');
        }
    }

    document.addEventListener('click', function(e) {
        var editBtn = e.target.closest('.edit-country');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();
            setModeEdit({
                id: editBtn.getAttribute('data-id'),
                name: editBtn.getAttribute('data-name') || '',
                status: editBtn.getAttribute('data-status') || 'inactive',
                flagUrl: editBtn.getAttribute('data-flag-url') || ''
            });
            return;
        }

        var delBtn = e.target.closest('.delete-country');
        if (delBtn) {
            e.preventDefault();
            e.stopPropagation();

            var disabled = delBtn.getAttribute('data-disabled') === '1';
            var id = delBtn.getAttribute('data-id');
            var name = delBtn.getAttribute('data-name') || 'this country';

            document.getElementById('countryNameToDelete').textContent = name;
            document.getElementById('deleteCountryId').value = id;

            var hint = document.getElementById('deleteCountryHint');
            var confirmBtn = document.getElementById('confirmDeleteBtn');
            if (disabled) {
                if (hint) hint.textContent = 'You cannot delete a country that has associated products.';
                if (confirmBtn) confirmBtn.disabled = true;
            } else {
                if (hint) hint.textContent = 'This action cannot be undone.';
                if (confirmBtn) confirmBtn.disabled = false;
            }

            showDeleteModal();
            return;
        }
    });

    if (form) {
        form.addEventListener('submit', function(e) {
            if (!nameInput || !nameInput.value || !nameInput.value.trim()) {
                e.preventDefault();
                nameInput.classList.add('is-invalid');
                try { nameInput.focus(); } catch (ex) {}
                return false;
            }
            nameInput.classList.remove('is-invalid');
        });
    }

    /* KPI counters */
    document.querySelectorAll('[data-counter]').forEach(function(el) {
        var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
        var start = 0;
        var duration = 700;
        var t0 = null;
        function step(ts) {
            if (!t0) t0 = ts;
            var p = Math.min((ts - t0) / duration, 1);
            var eased = 1 - Math.pow(1 - p, 3);
            el.textContent = String(Math.round(start + (target - start) * eased));
            if (p < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    });

    function showToast(msg, type) {
        var host = document.getElementById('clToastHost');
        if (!host) return;
        var t = document.createElement('div');
        t.className = 'cl-toast ' + (type || 'info');
        t.setAttribute('role', 'status');
        t.textContent = msg;
        host.appendChild(t);
        setTimeout(function() {
            t.style.opacity = '0';
            setTimeout(function() { t.remove(); }, 300);
        }, 2800);
    }

    var table = document.getElementById('countriesTable');
    var tbody = table ? table.querySelector('tbody') : null;
    var allRows = tbody ? Array.prototype.slice.call(tbody.querySelectorAll('tr.country-row')) : [];
    var searchInput = document.getElementById('clLiveSearch');
    var statusFilter = document.getElementById('clFilterStatus');
    var flagFilter = document.getElementById('clFilterFlag');
    var pageSizeSel = document.getElementById('clPageSize');
    var pageInfo = document.getElementById('clPageInfo');
    var pagination = document.getElementById('clPagination');
    var selectAll = document.getElementById('clSelectAll');
    var bulkBar = document.getElementById('clBulkBar');
    var selectedCountEl = document.getElementById('clSelectedCount');
    var resultCount = document.getElementById('clResultCount');
    var currentPage = 1;
    var sortKey = 'name';
    var sortAsc = true;

    function getFiltered() {
        var q = (searchInput && searchInput.value || '').toLowerCase().trim();
        var st = statusFilter ? statusFilter.value : '';
        var fl = flagFilter ? flagFilter.value : '';
        return allRows.filter(function(row) {
            var name = (row.getAttribute('data-name') || '').toLowerCase();
            var status = row.getAttribute('data-status') || '';
            var flag = row.getAttribute('data-flag') || '';
            if (q && name.indexOf(q) === -1 && status.indexOf(q) === -1) return false;
            if (st && status !== st) return false;
            if (fl && flag !== fl) return false;
            return true;
        });
    }

    function sortRows(rows) {
        return rows.slice().sort(function(a, b) {
            var av = (a.getAttribute('data-' + sortKey) || a.getAttribute('data-name') || '').toLowerCase();
            var bv = (b.getAttribute('data-' + sortKey) || b.getAttribute('data-name') || '').toLowerCase();
            if (av < bv) return sortAsc ? -1 : 1;
            if (av > bv) return sortAsc ? 1 : -1;
            return 0;
        });
    }

    function updateBulkBar() {
        var checked = tbody ? tbody.querySelectorAll('.cl-row-check:checked') : [];
        var n = checked.length;
        if (selectedCountEl) selectedCountEl.textContent = String(n);
        if (bulkBar) {
            if (n > 0) bulkBar.classList.add('is-visible');
            else bulkBar.classList.remove('is-visible');
        }
    }

    function renderTable() {
        if (!tbody) return;
        var filtered = sortRows(getFiltered());
        var sizeVal = pageSizeSel ? pageSizeSel.value : '25';
        var pageSize = sizeVal === 'all' ? (filtered.length || 1) : (parseInt(sizeVal, 10) || 25);
        var totalPages = Math.max(1, Math.ceil(filtered.length / pageSize) || 1);
        if (currentPage > totalPages) currentPage = totalPages;
        var start = (currentPage - 1) * pageSize;
        var end = Math.min(start + pageSize, filtered.length);

        allRows.forEach(function(r) { r.style.display = 'none'; });
        filtered.forEach(function(r, i) {
            r.style.display = (i >= start && i < end) ? '' : 'none';
        });

        if (resultCount) resultCount.textContent = filtered.length + ' result' + (filtered.length === 1 ? '' : 's');
        if (pageInfo) {
            if (!filtered.length) pageInfo.textContent = 'Showing 0–0 of 0';
            else pageInfo.textContent = 'Showing ' + (start + 1) + '–' + end + ' of ' + filtered.length;
        }

        if (pagination) {
            pagination.innerHTML = '';
            function addPage(label, page, disabled, active) {
                var li = document.createElement('li');
                li.className = 'page-item' + (disabled ? ' disabled' : '') + (active ? ' active' : '');
                var a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = label;
                a.addEventListener('click', function(ev) {
                    ev.preventDefault();
                    if (disabled) return;
                    currentPage = page;
                    renderTable();
                });
                li.appendChild(a);
                pagination.appendChild(li);
            }
            addPage('‹', Math.max(1, currentPage - 1), currentPage <= 1, false);
            var maxShow = Math.min(totalPages, 7);
            for (var p = 1; p <= maxShow; p++) addPage(String(p), p, false, p === currentPage);
            addPage('›', Math.min(totalPages, currentPage + 1), currentPage >= totalPages, false);
        }
        updateBulkBar();
    }

    if (searchInput) searchInput.addEventListener('input', function() { currentPage = 1; renderTable(); });
    var clearBtn = document.getElementById('clSearchClear');
    if (clearBtn) clearBtn.addEventListener('click', function() { if (searchInput) searchInput.value = ''; currentPage = 1; renderTable(); });
    if (statusFilter) statusFilter.addEventListener('change', function() { currentPage = 1; renderTable(); });
    if (flagFilter) flagFilter.addEventListener('change', function() { currentPage = 1; renderTable(); });
    if (pageSizeSel) pageSizeSel.addEventListener('change', function() { currentPage = 1; renderTable(); });

    var resetBtn = document.getElementById('clResetFilters');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (flagFilter) flagFilter.value = '';
            if (pageSizeSel) pageSizeSel.value = '25';
            currentPage = 1;
            renderTable();
            showToast('Filters reset', 'info');
        });
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            var visible = allRows.filter(function(r) { return r.style.display !== 'none'; });
            visible.forEach(function(r) {
                var cb = r.querySelector('.cl-row-check');
                if (cb) cb.checked = selectAll.checked;
            });
            updateBulkBar();
        });
    }

    if (tbody) {
        tbody.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('cl-row-check')) updateBulkBar();
        });
    }

    document.querySelectorAll('#countriesTable thead .sortable').forEach(function(th) {
        th.addEventListener('click', function() {
            var key = th.getAttribute('data-sort');
            if (sortKey === key) sortAsc = !sortAsc;
            else { sortKey = key; sortAsc = true; }
            renderTable();
        });
    });

    document.querySelectorAll('.cl-col-toggle').forEach(function(cb) {
        cb.addEventListener('change', function() {
            var idx = parseInt(cb.getAttribute('data-col'), 10);
            if (!table || isNaN(idx)) return;
            var show = cb.checked;
            Array.prototype.forEach.call(table.querySelectorAll('tr'), function(tr) {
                if (tr.children[idx]) tr.children[idx].style.display = show ? '' : 'none';
            });
        });
    });

    function exportCsv(rows) {
        var lines = ['ID,Name,Status,Products,Has Flag'];
        rows.forEach(function(r) {
            lines.push([
                r.getAttribute('data-id'),
                '"' + (r.getAttribute('data-name') || '').replace(/"/g, '""') + '"',
                r.getAttribute('data-status'),
                r.getAttribute('data-products'),
                r.getAttribute('data-flag')
            ].join(','));
        });
        var blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'countries-export.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    }

    var exportBtn = document.getElementById('clExportCsvBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            exportCsv(getFiltered());
            showToast('Exported countries to CSV', 'success');
        });
    }

    var bulkExport = document.getElementById('clBulkExport');
    if (bulkExport) {
        bulkExport.addEventListener('click', function() {
            var selected = allRows.filter(function(r) {
                var cb = r.querySelector('.cl-row-check');
                return cb && cb.checked;
            });
            exportCsv(selected);
            showToast('Exported selected countries', 'success');
        });
    }

    function bulkHint(action) {
        showToast(action + ' — use Edit / Delete on each country (backend bulk APIs not available).', 'warning');
    }
    var ba = document.getElementById('clBulkActivate');
    var bd = document.getElementById('clBulkDeactivate');
    var bdel = document.getElementById('clBulkDelete');
    var bclear = document.getElementById('clBulkClear');
    var bimport = document.getElementById('clImportBtn');
    if (ba) ba.addEventListener('click', function() { bulkHint('Bulk activate'); });
    if (bd) bd.addEventListener('click', function() { bulkHint('Bulk deactivate'); });
    if (bdel) bdel.addEventListener('click', function() { bulkHint('Bulk delete'); });
    if (bclear) bclear.addEventListener('click', function() {
        allRows.forEach(function(r) { var cb = r.querySelector('.cl-row-check'); if (cb) cb.checked = false; });
        if (selectAll) selectAll.checked = false;
        updateBulkBar();
    });
    if (bimport) bimport.addEventListener('click', function() { showToast('Import is a UI preview — use Add Country for now.', 'info'); });

    renderTable();

    if (typeof Chart !== 'undefined') {
        var statusCtx = document.getElementById('clStatusChart');
        var flagCtx = document.getElementById('clFlagChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Inactive'],
                    datasets: [{ data: [<?php echo (int)$activeCount; ?>, <?php echo (int)$inactiveCount; ?>], backgroundColor: ['#10b981', '#94a3b8'], borderWidth: 0 }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '62%' }
            });
        }
        if (flagCtx) {
            new Chart(flagCtx, {
                type: 'doughnut',
                data: {
                    labels: ['With Flag', 'Missing Flag'],
                    datasets: [{ data: [<?php echo (int)$withFlag; ?>, <?php echo (int)$noFlag; ?>], backgroundColor: ['#3b82f6', '#f59e0b'], borderWidth: 0 }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '62%' }
            });
        }
    }
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
