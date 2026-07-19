<?php
$suppliers = $suppliers ?? [];
if (!is_array($suppliers)) $suppliers = [];
require_once APP_PATH . 'views/admin/layouts/header.php';

$totalSuppliers = count($suppliers);
$withEmail = 0;
$withPhone = 0;
$withProduct = 0;
$newThisMonth = 0;
$monthStart = date('Y-m-01');
foreach ($suppliers as $s) {
    if (!empty($s['email'])) $withEmail++;
    if (!empty($s['phone'])) $withPhone++;
    if (!empty($s['product_name'])) $withProduct++;
    $created = $s['created_at'] ?? '';
    if ($created !== '' && substr((string)$created, 0, 10) >= $monthStart) $newThisMonth++;
}
$activeSuppliers = $totalSuppliers; // no status column in DB — treat listed as active
$inactiveSuppliers = 0;
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/supplier-list.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid supplier-list-page py-3 py-md-4 px-2 px-sm-3 suppliers-admin page-shell" id="supplierAdminPage">
    <div class="sl-toast-host" id="slToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="sl-header">
        <div>
            <nav class="sl-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span>›</span>
                <span>Inventory</span>
                <span>›</span>
                <span aria-current="page">Suppliers</span>
            </nav>
            <h1 class="sl-title">Supplier Management</h1>
            <p class="sl-subtitle">Enterprise supplier directory — contacts, products, and purchase-ready records.</p>
        </div>
        <div class="sl-actions">
            <button type="button" class="btn btn-outline-secondary sl-btn" id="slRefreshBtn" title="Refresh"><i class="bi bi-arrow-clockwise"></i><span class="d-none d-md-inline">Refresh</span></button>
            <button type="button" class="btn btn-outline-secondary sl-btn" id="slPrintBtn" title="Print"><i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span></button>
            <button type="button" class="btn btn-outline-secondary sl-btn" id="slExportCsvBtn" title="Excel"><i class="bi bi-file-earmark-spreadsheet"></i><span class="d-none d-lg-inline">Excel</span></button>
            <button type="button" class="btn btn-outline-secondary sl-btn" id="slExportPdfBtn" title="PDF"><i class="bi bi-filetype-pdf"></i><span class="d-none d-lg-inline">PDF</span></button>
            <button type="button" class="btn btn-outline-secondary sl-btn" id="slImportBtn" title="Import"><i class="bi bi-upload"></i><span class="d-none d-xl-inline">Import</span></button>
            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-outline-secondary sl-btn">
                <i class="bi bi-box-seam"></i><span class="d-none d-md-inline">Products</span>
            </a>
            <a href="#" class="btn sl-btn sl-btn-primary" data-toggle="modal" data-target="#addSupplierModal" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                <i class="bi bi-plus-lg"></i><span>Add Supplier</span>
            </a>
        </div>
    </div>

    <!-- KPI cards -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-3">
            <div class="sl-stat s1"><div class="icon" aria-hidden="true"><i class="bi bi-truck"></i></div><div><div class="label">Total Suppliers</div><div class="value" data-counter="<?php echo $totalSuppliers; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="sl-stat s2"><div class="icon" aria-hidden="true"><i class="bi bi-check-circle"></i></div><div><div class="label">Active Suppliers</div><div class="value" data-counter="<?php echo $activeSuppliers; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="sl-stat s3"><div class="icon" aria-hidden="true"><i class="bi bi-x-circle"></i></div><div><div class="label">Inactive Suppliers</div><div class="value" data-counter="<?php echo $inactiveSuppliers; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="sl-stat s4"><div class="icon" aria-hidden="true"><i class="bi bi-calendar-plus"></i></div><div><div class="label">New This Month</div><div class="value" data-counter="<?php echo $newThisMonth; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="sl-stat s5"><div class="icon" aria-hidden="true"><i class="bi bi-envelope"></i></div><div><div class="label">With Email</div><div class="value" data-counter="<?php echo $withEmail; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="sl-stat s6"><div class="icon" aria-hidden="true"><i class="bi bi-telephone"></i></div><div><div class="label">With Phone</div><div class="value" data-counter="<?php echo $withPhone; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="sl-stat s7"><div class="icon" aria-hidden="true"><i class="bi bi-box-seam"></i></div><div><div class="label">Products Supplied</div><div class="value" data-counter="<?php echo $withProduct; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="sl-stat s8"><div class="icon" aria-hidden="true"><i class="bi bi-clipboard-check"></i></div><div><div class="label">Pending POs</div><div class="value" data-counter="0">0</div></div></div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-lg-8">
            <div class="sl-card">
                <div class="sl-card-body pt-2">
                    <?php flash('supplier_success'); ?>
                    <?php flash('supplier_error'); ?>

                    <div class="sl-toolbar">
                        <div class="d-flex align-items-center flex-wrap gap-2" style="flex:1 1 320px;">
                            <div class="input-group input-group-sm" style="max-width: 420px;">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="supplierSearch" placeholder="Search name, product, email, phone…" aria-label="Search suppliers">
                                <button type="button" class="btn btn-outline-secondary" id="supplierSearchClear">Clear</button>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="slFilterToggle" data-bs-toggle="offcanvas" data-bs-target="#slFilterDrawer" aria-controls="slFilterDrawer">
                                <i class="bi bi-funnel"></i> Filters
                            </button>
                            <span class="small text-muted" id="supplierCount"></span>
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <label for="supplierPerPage" class="form-label small text-muted mb-0">Rows</label>
                            <select id="supplierPerPage" class="form-select form-select-sm" style="width: auto;">
                                <option value="10">10</option>
                                <option value="20" selected>20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="supplierPrevBtn" aria-label="Previous page">Prev</button>
                            <span class="small text-muted" id="supplierPageInfo">Page 1 / 1</span>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="supplierNextBtn" aria-label="Next page">Next</button>
                        </div>
                    </div>

                    <div class="sl-bulk-bar" id="slBulkBar" aria-live="polite">
                        <strong><span id="slSelectedCount">0</span> selected</strong>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="slBulkExport">Export Selected</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="slBulkEmail" disabled title="UI only">Send Email</button>
                        <button type="button" class="btn btn-sm btn-outline-danger" id="slBulkClear">Clear</button>
                    </div>

                    <div class="suppliers-table-scroll" role="region" aria-label="Suppliers table" tabindex="0">
                        <table id="suppliersTable" class="table align-middle mb-0" aria-describedby="suppliers-helptext">
                            <thead>
                                <tr>
                                    <th style="width:44px;"><input type="checkbox" class="form-check-input" id="slSelectAll" aria-label="Select all"></th>
                                    <th style="width: 56px;">#</th>
                                    <th>Supplier Name</th>
                                    <th>Product</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th style="width:100px;">Status</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($suppliers)): ?>
                                    <?php foreach ($suppliers as $idx => $supplier):
                                        $rowNum = $idx + 1;
                                        $sid = (int)($supplier['id'] ?? 0);
                                        $sname = (string)($supplier['name'] ?? '');
                                        $initial = strtoupper(substr($sname !== '' ? $sname : 'S', 0, 1));
                                    ?>
                                        <tr data-supplier-id="<?php echo $sid; ?>">
                                            <td data-label="Select"><input type="checkbox" class="form-check-input sl-row-check" value="<?php echo $sid; ?>" aria-label="Select supplier"></td>
                                            <td data-label="#"><?php echo $rowNum; ?></td>
                                            <td data-label="Supplier Name">
                                                <div class="sl-name-cell">
                                                    <span class="sl-avatar" aria-hidden="true"><?php echo htmlspecialchars($initial); ?></span>
                                                    <div>
                                                        <div class="fw-semibold"><?php echo htmlspecialchars($sname); ?></div>
                                                        <div class="meta">ID #<?php echo $sid; ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="Product"><?php echo !empty($supplier['product_name']) ? htmlspecialchars($supplier['product_name']) : '—'; ?></td>
                                            <td data-label="Email"><?php echo !empty($supplier['email']) ? htmlspecialchars($supplier['email']) : '—'; ?></td>
                                            <td data-label="Phone"><?php echo !empty($supplier['phone']) ? htmlspecialchars($supplier['phone']) : '—'; ?></td>
                                            <td data-label="Status"><span class="badge rounded-pill text-bg-success">Active</span></td>
                                            <td data-label="Actions">
                                                <div class="btn-group btn-group-sm supplier-actions" role="group" aria-label="Supplier Actions">
                                                    <button type="button" class="btn btn-outline-secondary sl-view-btn"
                                                            data-id="<?php echo $sid; ?>"
                                                            data-name="<?php echo htmlspecialchars($sname); ?>"
                                                            data-product_name="<?php echo htmlspecialchars($supplier['product_name'] ?? ''); ?>"
                                                            data-email="<?php echo htmlspecialchars($supplier['email'] ?? ''); ?>"
                                                            data-phone="<?php echo htmlspecialchars($supplier['phone'] ?? ''); ?>"
                                                            data-address="<?php echo htmlspecialchars($supplier['address'] ?? ''); ?>"
                                                            title="View">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-primary edit-supplier"
                                                            data-id="<?php echo $sid; ?>"
                                                            data-name="<?php echo htmlspecialchars($sname); ?>"
                                                            data-product_name="<?php echo htmlspecialchars($supplier['product_name'] ?? ''); ?>"
                                                            data-email="<?php echo htmlspecialchars($supplier['email'] ?? ''); ?>"
                                                            data-phone="<?php echo htmlspecialchars($supplier['phone'] ?? ''); ?>"
                                                            data-address="<?php echo htmlspecialchars($supplier['address'] ?? ''); ?>">
                                                        <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Edit</span>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger delete-supplier"
                                                            data-id="<?php echo $sid; ?>"
                                                            data-name="<?php echo htmlspecialchars($sname); ?>">
                                                        <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Delete</span>
                                                    </button>
                                                    <div class="btn-group btn-group-sm">
                                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="More"></button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <?php if (!empty($supplier['phone'])): ?>
                                                            <li><a class="dropdown-item" href="tel:<?php echo htmlspecialchars($supplier['phone']); ?>"><i class="bi bi-telephone me-2"></i>Call</a></li>
                                                            <?php endif; ?>
                                                            <?php if (!empty($supplier['email'])): ?>
                                                            <li><a class="dropdown-item" href="mailto:<?php echo htmlspecialchars($supplier['email']); ?>"><i class="bi bi-envelope me-2"></i>Email</a></li>
                                                            <?php endif; ?>
                                                            <li><button type="button" class="dropdown-item sl-view-btn" data-id="<?php echo $sid; ?>" data-name="<?php echo htmlspecialchars($sname); ?>" data-product_name="<?php echo htmlspecialchars($supplier['product_name'] ?? ''); ?>" data-email="<?php echo htmlspecialchars($supplier['email'] ?? ''); ?>" data-phone="<?php echo htmlspecialchars($supplier['phone'] ?? ''); ?>" data-address="<?php echo htmlspecialchars($supplier['address'] ?? ''); ?>"><i class="bi bi-person-badge me-2"></i>Profile</button></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No suppliers found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="suppliers-helptext" class="sr-only">Use the edit and delete buttons in the actions column to manage suppliers.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sl-chart-card mb-3">
                <h3>Contact Coverage</h3>
                <canvas id="slCoverageChart" height="180" aria-label="Supplier contact coverage chart"></canvas>
            </div>
            <div class="sl-chart-card">
                <h3>Directory Snapshot</h3>
                <canvas id="slSnapshotChart" height="180" aria-label="Supplier snapshot chart"></canvas>
                <ul class="list-unstyled small mb-0 mt-3">
                    <li class="d-flex justify-content-between py-1"><span class="text-muted">Total purchases</span><strong>—</strong></li>
                    <li class="d-flex justify-content-between py-1"><span class="text-muted">Outstanding balance</span><strong>—</strong></li>
                    <li class="d-flex justify-content-between py-1"><span class="text-muted">Pending POs</span><strong>0</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Filter offcanvas (UI) -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="slFilterDrawer" aria-labelledby="slFilterDrawerLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="slFilterDrawerLabel">Advanced Filters</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-3">
            <label class="form-label" for="slFilterCountry">Country</label>
            <input type="text" class="form-control" id="slFilterCountry" placeholder="Any">
        </div>
        <div class="mb-3">
            <label class="form-label" for="slFilterCity">City</label>
            <input type="text" class="form-control" id="slFilterCity" placeholder="Any">
        </div>
        <div class="mb-3">
            <label class="form-label" for="slFilterStatus">Status</label>
            <select class="form-select" id="slFilterStatus">
                <option value="">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <p class="small text-muted">Filters refine the on-page search list. Core CRUD is unchanged.</p>
        <div class="d-grid gap-2">
            <button type="button" class="btn btn-primary" id="slApplyFilters" data-bs-dismiss="offcanvas">Apply</button>
            <button type="button" class="btn btn-outline-secondary" id="slResetFilters">Reset Filters</button>
        </div>
    </div>
</div>

<!-- Profile preview drawer -->
<div class="sl-preview-backdrop" id="slPreviewBackdrop" hidden></div>
<aside class="sl-preview-drawer" id="slPreviewDrawer" aria-hidden="true" aria-label="Supplier profile">
    <div class="d-flex justify-content-between align-items-start mb-3">
        <h2 class="h5 mb-0">Supplier Profile</h2>
        <button type="button" class="btn-close" id="slPreviewClose" aria-label="Close"></button>
    </div>
    <div class="text-center mb-3">
        <div class="sl-avatar mx-auto mb-2" id="slPrevAvatar" style="width:64px;height:64px;font-size:1.25rem;">S</div>
        <div class="fw-bold" id="slPrevName">—</div>
        <div class="text-muted small" id="slPrevId">—</div>
    </div>
    <dl class="row small mb-0">
        <dt class="col-4 text-muted">Product</dt><dd class="col-8" id="slPrevProduct">—</dd>
        <dt class="col-4 text-muted">Email</dt><dd class="col-8" id="slPrevEmail">—</dd>
        <dt class="col-4 text-muted">Phone</dt><dd class="col-8" id="slPrevPhone">—</dd>
        <dt class="col-4 text-muted">Address</dt><dd class="col-8" id="slPrevAddress">—</dd>
        <dt class="col-4 text-muted">Status</dt><dd class="col-8"><span class="badge text-bg-success">Active</span></dd>
        <dt class="col-4 text-muted">Credit</dt><dd class="col-8">—</dd>
        <dt class="col-4 text-muted">Balance</dt><dd class="col-8">—</dd>
    </dl>
    <hr>
    <p class="small text-muted mb-2">Recent purchases and ledger require purchase modules — UI placeholders only.</p>
</aside>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
            <div class="modal-header">
                <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editSupplierForm" method="POST" action="">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="edit_product_name" name="product_name">
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_email" name="email">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="edit_phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="edit_address" class="form-label">Address</label>
                        <textarea class="form-control" id="edit_address" name="address" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="updateSupplierBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        Update Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteSupplierModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="deleteSupplierName"></strong>?</p>
                <p class="text-danger mb-0">This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="button-text">Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Supplier Modal -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
            <div class="modal-header">
                <h5 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSupplierForm" method="POST" action="<?php echo BASE_URL; ?>?controller=supplier&action=create">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Supplier Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?php echo !empty($data['name_err']) ? 'is-invalid' : ''; ?>"
                            id="name" name="name" required
                            value="<?php echo isset($data['name']) ? htmlspecialchars($data['name']) : ''; ?>">
                        <div class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control"
                            id="product_name" name="product_name"
                            value="<?php echo isset($data['product_name']) ? htmlspecialchars($data['product_name']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?php echo !empty($data['email_err']) ? 'is-invalid' : ''; ?>"
                            id="email" name="email"
                            value="<?php echo isset($data['email']) ? htmlspecialchars($data['email']) : ''; ?>">
                        <div class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone"
                            value="<?php echo isset($data['phone']) ? htmlspecialchars($data['phone']) : ''; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php
                            echo isset($data['address']) ? htmlspecialchars($data['address']) : '';
                        ?></textarea>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="button" class="btn btn-outline-secondary me-md-2" id="resetSupplierForm">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary" id="saveSupplierBtn">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <i class="fas fa-save me-1"></i> Save Supplier
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1080">
    <div class="toast align-items-center text-white bg-success border-0" id="successToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-check-circle me-2"></i>
                <span id="toastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" data-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <div class="toast align-items-center text-white bg-danger border-0" id="errorToast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-exclamation-circle me-2"></i>
                <span id="errorToastMessage"></span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" data-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
// Base URL for building links in JS
const BASE_URL = '<?php echo BASE_URL; ?>';

function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe
        .toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function showAlert(message, type = 'success') {
    const existingAlert = document.querySelector('.alert-dismissible');
    if (existingAlert) existingAlert.remove();

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alertDiv.style.zIndex = '1090';
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    `;
    document.body.appendChild(alertDiv);
    setTimeout(() => {
        try { $(alertDiv).alert('close'); } catch (e) { alertDiv.remove(); }
    }, 5000);
}

function showFormErrors(errors) {
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => { el.textContent = ''; });

    if (errors) {
        Object.keys(errors).forEach(field => {
            const input = document.querySelector(`[name="${field}"]`);
            const feedback = input ? input.nextElementSibling : null;
            if (input && feedback && feedback.classList.contains('invalid-feedback')) {
                input.classList.add('is-invalid');
                feedback.textContent = errors[field];
            }
        });
    }
}

function buildSupplierRow(s) {
    const initial = escapeHtml((s.name || 'S').charAt(0).toUpperCase());
    return `
        <td data-label="Select"><input type="checkbox" class="form-check-input sl-row-check" value="${s.id}" aria-label="Select supplier"></td>
        <td data-label="#">${s.id}</td>
        <td data-label="Supplier Name">
            <div class="sl-name-cell">
                <span class="sl-avatar" aria-hidden="true">${initial}</span>
                <div>
                    <div class="fw-semibold">${escapeHtml(s.name)}</div>
                    <div class="meta">ID #${s.id}</div>
                </div>
            </div>
        </td>
        <td data-label="Product">${s.product_name ? escapeHtml(s.product_name) : '—'}</td>
        <td data-label="Email">${s.email ? escapeHtml(s.email) : '—'}</td>
        <td data-label="Phone">${s.phone ? escapeHtml(s.phone) : '—'}</td>
        <td data-label="Status"><span class="badge rounded-pill text-bg-success">Active</span></td>
        <td data-label="Actions" class="text-nowrap">
            <div class="btn-group btn-group-sm supplier-actions" role="group" aria-label="Supplier Actions">
                <button type="button" class="btn btn-outline-secondary sl-view-btn"
                        data-id="${s.id}"
                        data-name="${escapeHtml(s.name)}"
                        data-product_name="${escapeHtml(s.product_name || '')}"
                        data-email="${escapeHtml(s.email || '')}"
                        data-phone="${escapeHtml(s.phone || '')}"
                        data-address="${escapeHtml(s.address || '')}">
                    <i class="bi bi-eye"></i>
                </button>
                <button type="button" class="btn btn-outline-primary edit-supplier"
                        data-id="${s.id}"
                        data-name="${escapeHtml(s.name)}"
                        data-product_name="${escapeHtml(s.product_name || '')}"
                        data-email="${escapeHtml(s.email || '')}"
                        data-phone="${escapeHtml(s.phone || '')}"
                        data-address="${escapeHtml(s.address || '')}"
                        onclick="event.stopPropagation();">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button type="button" class="btn btn-outline-danger delete-supplier"
                        data-id="${s.id}"
                        data-name="${escapeHtml(s.name)}"
                        onclick="event.stopPropagation();">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </td>`;
}

document.addEventListener('click', function(e) {
    const editBtn = e.target.closest('.edit-supplier');
    if (editBtn) {
        e.preventDefault();
        e.stopPropagation();
        const modalElement = document.getElementById('editSupplierModal');
        if (!modalElement) return;
        const form = modalElement.querySelector('form');
        if (form) {
            form.reset();
            form.action = `${BASE_URL}?controller=supplier&action=update`;
            document.getElementById('edit_id').value = editBtn.getAttribute('data-id');
            document.getElementById('edit_name').value = editBtn.getAttribute('data-name') || '';
            document.getElementById('edit_product_name').value = editBtn.getAttribute('data-product_name') || '';
            document.getElementById('edit_email').value = editBtn.getAttribute('data-email') || '';
            document.getElementById('edit_phone').value = editBtn.getAttribute('data-phone') || '';
            document.getElementById('edit_address').value = editBtn.getAttribute('data-address') || '';
            form.querySelectorAll('.is-invalid').forEach(input => input.classList.remove('is-invalid'));
            $(modalElement).modal('show');
        }
    }
});

document.addEventListener('click', function(e) {
    const deleteBtn = e.target.closest('.delete-supplier');
    if (deleteBtn) {
        e.preventDefault();
        e.stopPropagation();
        document.getElementById('deleteSupplierName').textContent = deleteBtn.getAttribute('data-name');
        document.getElementById('confirmDeleteBtn').setAttribute('data-id', deleteBtn.getAttribute('data-id'));
        $(document.getElementById('deleteSupplierModal')).modal('show');
    }
});

document.addEventListener('submit', function(e) {
    if (e.target && e.target.id === 'editSupplierForm') {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const updateBtn = form.querySelector('button[type="submit"]');
        const spinner = updateBtn.querySelector('.spinner-border');
        updateBtn.disabled = true;
        if (spinner) spinner.classList.remove('d-none');

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(async (response) => {
            const text = await response.text();
            try { return { ok: response.ok, data: JSON.parse(text) }; }
            catch (err) { throw new Error('Invalid server response'); }
        })
        .then(({ ok, data }) => {
            if (ok && data.success) {
                $(document.getElementById('editSupplierModal')).modal('hide');
                showAlert(data.message || 'Supplier updated successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else if (data.errors) {
                showFormErrors(data.errors);
            } else {
                throw new Error(data.message || 'Failed to update supplier');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert(error.message || 'Failed to update supplier. Please try again.', 'danger');
        })
        .finally(() => {
            if (updateBtn) {
                updateBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
            }
        });
    }
});

document.addEventListener('click', function(e) {
    const confirmBtn = e.target.closest('#confirmDeleteBtn');
    if (!confirmBtn) return;
    const supplierId = confirmBtn.getAttribute('data-id');
    if (!supplierId) return;
    const spinner = confirmBtn.querySelector('.spinner-border');
    confirmBtn.disabled = true;
    if (spinner) spinner.classList.remove('d-none');

    fetch(`${BASE_URL}?controller=supplier&action=delete`, {
        method: 'POST',
        body: new URLSearchParams({ id: supplierId }),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async (response) => {
        const text = await response.text();
        try { return { ok: response.ok, data: JSON.parse(text) }; }
        catch (err) { throw new Error('Invalid server response'); }
    })
    .then(({ ok, data }) => {
        if (ok && data && data.success) {
            $(document.getElementById('deleteSupplierModal')).modal('hide');
            showAlert(data.message || 'Supplier deleted successfully');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(data?.message || 'Failed to delete supplier');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(error.message || 'An error occurred while deleting the supplier', 'danger');
    })
    .finally(() => {
        confirmBtn.disabled = false;
        if (spinner) spinner.classList.add('d-none');
    });
});

const addSupplierForm = document.getElementById('addSupplierForm');
if (addSupplierForm) {
    addSupplierForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const saveBtn = document.getElementById('saveSupplierBtn');
        const spinner = saveBtn.querySelector('.spinner-border');
        saveBtn.disabled = true;
        if (spinner) spinner.classList.remove('d-none');

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(async (response) => {
            const text = await response.text();
            let data;
            try { data = JSON.parse(text); } catch (_) { throw new Error(text && text.trim() ? text : 'Invalid server response'); }
            return { ok: response.ok, data };
        })
        .then(({ ok, data }) => {
            if (ok && data.success) {
                document.getElementById('toastMessage').textContent = data.message || 'Supplier added successfully';
                $(document.getElementById('successToast')).toast('show');
                $(document.getElementById('addSupplierModal')).modal('hide');

                const s = data.supplier;
                const tbody = document.querySelector('#suppliersTable tbody');
                if (tbody && s) {
                    const tr = document.createElement('tr');
                    tr.setAttribute('data-supplier-id', s.id);
                    tr.innerHTML = buildSupplierRow(s);
                    const placeholder = tbody.querySelector('td[colspan]');
                    if (placeholder) placeholder.closest('tr').remove();
                    tbody.prepend(tr);
                }
                form.reset();
            } else if (data.errors) {
                showFormErrors(data.errors);
            } else {
                throw new Error(data.message || 'Failed to add supplier');
            }
        })
        .catch(err => {
            document.getElementById('errorToastMessage').textContent = err.message || 'Failed to save supplier.';
            $(document.getElementById('errorToast')).toast('show');
        })
        .finally(() => {
            saveBtn.disabled = false;
            if (spinner) spinner.classList.add('d-none');
        });
    });
}

document.getElementById('resetSupplierForm')?.addEventListener('click', function() {
    addSupplierForm?.reset();
});

// Client-side search + pagination (preserved)
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('suppliersTable');
    const tbody = table ? table.querySelector('tbody') : null;
    const searchInput = document.getElementById('supplierSearch');
    const clearBtn = document.getElementById('supplierSearchClear');
    const perPageSel = document.getElementById('supplierPerPage');
    const prevBtn = document.getElementById('supplierPrevBtn');
    const nextBtn = document.getElementById('supplierNextBtn');
    const pageInfo = document.getElementById('supplierPageInfo');
    const countEl = document.getElementById('supplierCount');
    if (!tbody || !searchInput || !perPageSel || !prevBtn || !nextBtn || !pageInfo || !countEl) return;

    let allRows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.querySelector('td') && !r.querySelector('td[colspan]'));
    let state = { q: '', page: 1, per: parseInt(perPageSel.value, 10) || 20 };

    function refreshRows() {
        allRows = Array.from(tbody.querySelectorAll('tr')).filter(r => r.querySelector('td') && !r.querySelector('td[colspan]'));
    }
    function rowText(r) { return (r.innerText || '').toLowerCase(); }
    function filteredRows() {
        const q = (state.q || '').trim().toLowerCase();
        if (!q) return allRows;
        return allRows.filter(r => rowText(r).includes(q));
    }
    function render() {
        refreshRows();
        const rows = filteredRows();
        const total = rows.length;
        const per = Math.max(1, state.per);
        const pages = Math.max(1, Math.ceil(total / per) || 1);
        state.page = Math.min(Math.max(1, state.page), pages);
        const start = (state.page - 1) * per;
        const end = start + per;
        allRows.forEach(r => { r.style.display = 'none'; });
        rows.slice(start, end).forEach(r => { r.style.display = ''; });
        pageInfo.textContent = 'Page ' + state.page + ' / ' + pages;
        countEl.textContent = total + ' total';
        prevBtn.disabled = state.page <= 1;
        nextBtn.disabled = state.page >= pages;
    }

    searchInput.addEventListener('input', function() { state.q = searchInput.value || ''; state.page = 1; render(); });
    if (clearBtn) clearBtn.addEventListener('click', function() { searchInput.value=''; state.q=''; state.page=1; render(); try{searchInput.focus();}catch(e){} });
    perPageSel.addEventListener('change', function() { state.per = parseInt(perPageSel.value, 10) || 20; state.page = 1; render(); });
    prevBtn.addEventListener('click', function() { state.page = Math.max(1, state.page - 1); render(); });
    nextBtn.addEventListener('click', function() { state.page = state.page + 1; render(); });
    render();

    // KPI counters
    document.querySelectorAll('[data-counter]').forEach(function(el) {
        const target = parseInt(el.getAttribute('data-counter'), 10) || 0;
        const start = performance.now();
        const dur = 700;
        function tick(now) {
            const p = Math.min(1, (now - start) / dur);
            el.textContent = String(Math.round(target * (0.5 - Math.cos(Math.PI * p) / 2)));
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    });

    // Charts (frontend only)
    if (typeof Chart !== 'undefined') {
        const cov = document.getElementById('slCoverageChart');
        if (cov) {
            new Chart(cov, {
                type: 'doughnut',
                data: {
                    labels: ['With Email', 'With Phone', 'With Product'],
                    datasets: [{
                        data: [<?php echo (int)$withEmail; ?>, <?php echo (int)$withPhone; ?>, <?php echo (int)$withProduct; ?>],
                        backgroundColor: ['#2563eb', '#0ea5e9', '#10b981']
                    }]
                },
                options: { plugins: { legend: { position: 'bottom' } }, cutout: '62%' }
            });
        }
        const snap = document.getElementById('slSnapshotChart');
        if (snap) {
            new Chart(snap, {
                type: 'bar',
                data: {
                    labels: ['Total', 'Active', 'New'],
                    datasets: [{
                        label: 'Suppliers',
                        data: [<?php echo (int)$totalSuppliers; ?>, <?php echo (int)$activeSuppliers; ?>, <?php echo (int)$newThisMonth; ?>],
                        backgroundColor: ['#6366f1', '#22c55e', '#f59e0b'],
                        borderRadius: 8
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
                }
            });
        }
    }

    // Toolbar helpers
    document.getElementById('slRefreshBtn')?.addEventListener('click', function() { window.location.reload(); });
    document.getElementById('slPrintBtn')?.addEventListener('click', function() { window.print(); });
    document.getElementById('slExportCsvBtn')?.addEventListener('click', function() {
        const rows = [['ID','Name','Product','Email','Phone']];
        document.querySelectorAll('#suppliersTable tbody tr').forEach(function(tr) {
            if (tr.style.display === 'none' || tr.querySelector('td[colspan]')) return;
            const cells = tr.querySelectorAll('td');
            if (cells.length < 6) return;
            const name = (cells[2].innerText || '').replace(/\s+/g, ' ').trim();
            rows.push([
                (cells[1].innerText || '').trim(),
                name,
                (cells[3].innerText || '').trim(),
                (cells[4].innerText || '').trim(),
                (cells[5].innerText || '').trim()
            ]);
        });
        const csv = rows.map(r => r.map(c => '"' + String(c).replace(/"/g, '""') + '"').join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'suppliers.csv';
        a.click();
    });
    document.getElementById('slExportPdfBtn')?.addEventListener('click', function() { window.print(); });
    document.getElementById('slImportBtn')?.addEventListener('click', function() {
        showAlert('Import is a UI action — wire to your import endpoint when ready.', 'info');
    });

    // Bulk select
    const selectAll = document.getElementById('slSelectAll');
    const bulkBar = document.getElementById('slBulkBar');
    function updateBulk() {
        const checks = document.querySelectorAll('.sl-row-check:checked');
        const n = checks.length;
        document.getElementById('slSelectedCount').textContent = String(n);
        bulkBar?.classList.toggle('is-visible', n > 0);
    }
    selectAll?.addEventListener('change', function() {
        document.querySelectorAll('.sl-row-check').forEach(function(c) {
            if (c.closest('tr')?.style.display === 'none') return;
            c.checked = selectAll.checked;
        });
        updateBulk();
    });
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('sl-row-check')) updateBulk();
    });
    document.getElementById('slBulkClear')?.addEventListener('click', function() {
        document.querySelectorAll('.sl-row-check').forEach(c => { c.checked = false; });
        if (selectAll) selectAll.checked = false;
        updateBulk();
    });
    document.getElementById('slBulkExport')?.addEventListener('click', function() {
        document.getElementById('slExportCsvBtn')?.click();
    });

    // Profile preview
    const drawer = document.getElementById('slPreviewDrawer');
    const backdrop = document.getElementById('slPreviewBackdrop');
    function openPreview(btn) {
        document.getElementById('slPrevName').textContent = btn.getAttribute('data-name') || '—';
        document.getElementById('slPrevId').textContent = 'ID #' + (btn.getAttribute('data-id') || '');
        document.getElementById('slPrevProduct').textContent = btn.getAttribute('data-product_name') || '—';
        document.getElementById('slPrevEmail').textContent = btn.getAttribute('data-email') || '—';
        document.getElementById('slPrevPhone').textContent = btn.getAttribute('data-phone') || '—';
        document.getElementById('slPrevAddress').textContent = btn.getAttribute('data-address') || '—';
        document.getElementById('slPrevAvatar').textContent = ((btn.getAttribute('data-name') || 'S').charAt(0) || 'S').toUpperCase();
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        backdrop.hidden = false;
        backdrop.classList.add('is-open');
    }
    function closePreview() {
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        backdrop.classList.remove('is-open');
        backdrop.hidden = true;
    }
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.sl-view-btn');
        if (btn) { e.preventDefault(); openPreview(btn); }
    });
    document.getElementById('slPreviewClose')?.addEventListener('click', closePreview);
    backdrop?.addEventListener('click', closePreview);

    document.getElementById('slResetFilters')?.addEventListener('click', function() {
        document.getElementById('slFilterCountry').value = '';
        document.getElementById('slFilterCity').value = '';
        document.getElementById('slFilterStatus').value = '';
        searchInput.value = '';
        state.q = '';
        state.page = 1;
        render();
    });
    document.getElementById('slApplyFilters')?.addEventListener('click', function() {
        // Soft filter: append keywords into search
        const parts = [
            document.getElementById('slFilterCountry')?.value,
            document.getElementById('slFilterCity')?.value
        ].filter(Boolean);
        if (parts.length) {
            searchInput.value = parts.join(' ');
            state.q = searchInput.value;
            state.page = 1;
            render();
        }
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
