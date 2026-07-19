<?php
/**
 * Banner Management — Enterprise DAM UI (visual layer only)
 * Preserves: #bannersTable, .delete-banner, #deleteBannerModal, #confirmDelete, create/edit/delete routes.
 */
$current_page = 'banners';
$page_title = 'Banner Management';
require_once APP_PATH . 'views/admin/layouts/header.php';

$banners = $banners ?? [];
if (!is_array($banners)) {
    $banners = [];
}

$totalBanners = count($banners);
$activeCount = 0;
$inactiveCount = 0;
foreach ($banners as $b) {
    $st = strtolower((string)($b['status'] ?? ''));
    if ($st === 'active') {
        $activeCount++;
    } else {
        $inactiveCount++;
    }
}
$activePct = $totalBanners > 0 ? round(($activeCount / $totalBanners) * 100) : 0;
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/banner-list.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid banner-list-page py-3 py-md-4 px-2 px-sm-3 banners-admin" id="bannerAdminPage">
    <div class="bn-toast-host" id="bnToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="bn-header">
        <div>
            <nav class="bn-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span>›</span>
                <span>Website</span>
                <span>›</span>
                <span aria-current="page">Banner Management</span>
            </nav>
            <h1 class="bn-title">Banner Management</h1>
            <p class="bn-subtitle">Digital asset hub for homepage heroes, sliders, and promotional creatives.</p>
        </div>
        <div class="bn-actions">
            <a href="<?php echo BASE_URL; ?>?controller=banner&action=index" class="btn btn-outline-secondary bn-btn" id="bnRefreshBtn" title="Refresh">
                <i class="bi bi-arrow-clockwise"></i><span class="d-none d-md-inline">Refresh</span>
            </a>
            <button type="button" class="btn btn-outline-secondary bn-btn" onclick="window.print()" title="Print">
                <i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span>
            </button>
            <button type="button" class="btn btn-outline-secondary bn-btn" id="bnExportCsvBtn" title="Excel">
                <i class="bi bi-file-earmark-spreadsheet"></i><span class="d-none d-lg-inline">Excel</span>
            </button>
            <button type="button" class="btn btn-outline-secondary bn-btn" onclick="window.print()" title="PDF">
                <i class="bi bi-filetype-pdf"></i><span class="d-none d-lg-inline">PDF</span>
            </button>
            <button type="button" class="btn btn-outline-secondary bn-btn" id="bnImportBtn" title="Import (UI)">
                <i class="bi bi-upload"></i><span class="d-none d-xl-inline">Import</span>
            </button>
            <a href="<?php echo BASE_URL; ?>?controller=banner&action=create" class="btn bn-btn bn-btn-primary" id="bnAddBannerBtn">
                <i class="bi bi-plus-lg"></i><span>Add Banner</span>
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bn-stat s1"><div class="icon" aria-hidden="true"><i class="bi bi-images"></i></div><div><div class="label">Total Banners</div><div class="value" data-counter="<?php echo $totalBanners; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bn-stat s2"><div class="icon" aria-hidden="true"><i class="bi bi-check-circle"></i></div><div><div class="label">Active</div><div class="value" data-counter="<?php echo $activeCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bn-stat s3"><div class="icon" aria-hidden="true"><i class="bi bi-x-circle"></i></div><div><div class="label">Inactive</div><div class="value" data-counter="<?php echo $inactiveCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bn-stat s4"><div class="icon" aria-hidden="true"><i class="bi bi-phone"></i></div><div><div class="label">Mobile Ready</div><div class="value" data-counter="<?php echo $totalBanners; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bn-stat s5"><div class="icon" aria-hidden="true"><i class="bi bi-display"></i></div><div><div class="label">Desktop Ready</div><div class="value" data-counter="<?php echo $totalBanners; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bn-stat s6"><div class="icon" aria-hidden="true"><i class="bi bi-house"></i></div><div><div class="label">Homepage Assets</div><div class="value" data-counter="<?php echo $totalBanners; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bn-stat s7"><div class="icon" aria-hidden="true"><i class="bi bi-pie-chart"></i></div><div><div class="label">Active Rate %</div><div class="value" data-counter="<?php echo $activePct; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bn-stat s8"><div class="icon" aria-hidden="true"><i class="bi bi-collection"></i></div><div><div class="label">Library Size</div><div class="value" data-counter="<?php echo $totalBanners; ?>">0</div></div></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-9">
            <div class="bn-card mb-3">
                <div class="bn-toolbar">
                    <div class="d-flex align-items-center flex-wrap gap-2" style="flex:1 1 280px;">
                        <div class="input-group input-group-sm" style="max-width: 360px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" class="form-control" id="bnLiveSearch" placeholder="Search title, description, status…" aria-label="Search banners" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" id="bnSearchClear">Clear</button>
                        </div>
                        <select class="form-select form-select-sm" id="bnFilterStatus" aria-label="Filter by status" style="width:auto;min-width:120px;">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="bnResetFilters" title="Reset filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                        <span class="small text-muted" id="bnResultCount"></span>
                    </div>
                    <div class="d-flex align-items-center flex-wrap gap-2 bn-view-toggle" role="group" aria-label="Display mode">
                        <button type="button" class="btn btn-sm btn-outline-secondary active" id="bnViewGrid" data-view="grid" title="Grid view"><i class="bi bi-grid-3x3-gap"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="bnViewList" data-view="list" title="List view"><i class="bi bi-list-ul"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="bnViewGallery" data-view="gallery" title="Large preview"><i class="bi bi-image"></i></button>
                    </div>
                </div>

                <div class="bn-bulk-bar" id="bnBulkBar" aria-live="polite">
                    <strong><span id="bnSelectedCount">0</span> selected</strong>
                    <button type="button" class="btn btn-sm btn-outline-success" id="bnBulkActivate">Activate</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="bnBulkDeactivate">Deactivate</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="bnBulkExport">Export</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="bnBulkDelete">Delete</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="bnBulkClear">Clear</button>
                </div>

                <!-- GRID VIEW -->
                <div class="bn-card-body" id="bnGridWrap">
                    <div class="bn-grid" id="bnGrid">
                        <?php if (empty($banners)): ?>
                            <div class="bn-empty w-100" style="grid-column:1/-1;">
                                <i class="bi bi-images d-block mb-2" style="font-size:2rem;opacity:.4;"></i>
                                No banners found. <a href="<?php echo BASE_URL; ?>?controller=banner&action=create">Add your first banner</a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($banners as $index => $banner):
                                $bid = (int)($banner['id'] ?? 0);
                                $title = htmlspecialchars($banner['title'] ?? '');
                                $desc = isset($banner['description']) && $banner['description'] !== ''
                                    ? htmlspecialchars(strlen($banner['description']) > 90 ? substr($banner['description'], 0, 90) . '...' : $banner['description'])
                                    : 'No description';
                                $status = strtolower((string)($banner['status'] ?? 'inactive'));
                                $isActive = ($status === 'active');
                                $img = BASE_URL . ltrim($banner['image_url'] ?? '', '/');
                                $created = !empty($banner['created_at']) ? date('M d, Y', strtotime($banner['created_at'])) : '—';
                            ?>
                            <article class="bn-grid-card banner-item"
                                data-id="<?php echo $bid; ?>"
                                data-title="<?php echo $title; ?>"
                                data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>"
                                data-desc="<?php echo htmlspecialchars(strtolower($banner['description'] ?? '')); ?>"
                                data-image="<?php echo htmlspecialchars($img); ?>"
                                data-created="<?php echo htmlspecialchars($banner['created_at'] ?? ''); ?>">
                                <div class="bn-grid-media">
                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo $title; ?>" loading="lazy" onerror="this.style.opacity='.25'">
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <input type="checkbox" class="form-check-input bn-row-check" value="<?php echo $bid; ?>" aria-label="Select <?php echo $title; ?>">
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="bn-badge <?php echo $isActive ? 'bn-status-active' : 'bn-status-inactive'; ?>">
                                            <?php echo $isActive ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="bn-grid-body">
                                    <h3 class="bn-grid-title"><?php echo $title ?: 'Untitled Banner'; ?></h3>
                                    <p class="bn-grid-meta mb-0"><?php echo $desc; ?></p>
                                    <div class="bn-device-pills mt-2">
                                        <span><i class="bi bi-display me-1"></i>Desktop</span>
                                        <span><i class="bi bi-phone me-1"></i>Mobile</span>
                                        <span><i class="bi bi-house me-1"></i>Homepage</span>
                                    </div>
                                    <div class="small text-muted mb-2"><i class="bi bi-calendar3 me-1"></i><?php echo $created; ?> · #<?php echo $bid; ?></div>
                                    <div class="d-flex flex-wrap gap-1 mt-auto">
                                        <button type="button" class="btn btn-sm btn-outline-secondary bn-preview-btn"
                                            data-id="<?php echo $bid; ?>"
                                            data-title="<?php echo $title; ?>"
                                            data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>"
                                            data-image="<?php echo htmlspecialchars($img); ?>"
                                            data-desc="<?php echo htmlspecialchars($banner['description'] ?? ''); ?>"
                                            title="Preview"><i class="bi bi-eye"></i></button>
                                        <a href="<?php echo BASE_URL; ?>?controller=banner&action=edit&id=<?php echo $bid; ?>" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-banner" data-id="<?php echo $bid; ?>" title="Delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                </div>
                            </article>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- LIST / TABLE VIEW -->
                <div class="banners-table-scroll d-none" id="bnListWrap">
                    <div class="table-responsive">
                        <table id="bannersTable" class="table table-hover align-middle mb-0" aria-label="Banners table">
                            <thead>
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" class="form-check-input" id="bnSelectAll" aria-label="Select all banners">
                                    </th>
                                    <th style="width:60px;">#</th>
                                    <th style="width:110px;">Thumbnail</th>
                                    <th>Banner</th>
                                    <th style="width:100px;">Status</th>
                                    <th style="width:110px;">Created</th>
                                    <th style="width:140px;" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($banners)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No banners found. <a href="<?php echo BASE_URL; ?>?controller=banner&action=create">Add your first banner</a></td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($banners as $index => $banner):
                                        $bid = (int)($banner['id'] ?? 0);
                                        $title = htmlspecialchars($banner['title'] ?? '');
                                        $desc = isset($banner['description']) && $banner['description'] !== ''
                                            ? (strlen($banner['description']) > 50 ? substr($banner['description'], 0, 50) . '...' : $banner['description'])
                                            : '—';
                                        $status = strtolower((string)($banner['status'] ?? 'inactive'));
                                        $isActive = ($status === 'active');
                                        $img = BASE_URL . ltrim($banner['image_url'] ?? '', '/');
                                        $created = !empty($banner['created_at']) ? date('M d, Y', strtotime($banner['created_at'])) : '—';
                                    ?>
                                    <tr class="banner-row banner-item"
                                        data-id="<?php echo $bid; ?>"
                                        data-title="<?php echo $title; ?>"
                                        data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>"
                                        data-desc="<?php echo htmlspecialchars(strtolower($banner['description'] ?? '')); ?>"
                                        data-image="<?php echo htmlspecialchars($img); ?>"
                                        data-created="<?php echo htmlspecialchars($banner['created_at'] ?? ''); ?>">
                                        <td data-label="Select" onclick="event.stopPropagation();">
                                            <input type="checkbox" class="form-check-input bn-row-check" value="<?php echo $bid; ?>" aria-label="Select <?php echo $title; ?>">
                                        </td>
                                        <td data-label="#"><?php echo $index + 1; ?></td>
                                        <td data-label="Image">
                                            <img class="banner-thumb" src="<?php echo htmlspecialchars($img); ?>"
                                                 alt="<?php echo $title; ?>"
                                                 loading="lazy"
                                                 onerror="this.style.display='none'">
                                        </td>
                                        <td data-label="Banner">
                                            <div class="fw-semibold"><?php echo $title; ?></div>
                                            <div class="text-muted small"><?php echo htmlspecialchars($desc); ?></div>
                                        </td>
                                        <td data-label="Status">
                                            <span class="bn-badge <?php echo $isActive ? 'bn-status-active' : 'bn-status-inactive'; ?>">
                                                <?php echo ucfirst($status ?: 'inactive'); ?>
                                            </span>
                                        </td>
                                        <td data-label="Created"><span class="small text-muted"><?php echo $created; ?></span></td>
                                        <td data-label="Actions" class="text-end" onclick="event.stopPropagation();">
                                            <div class="actions-wrap d-flex flex-wrap gap-1 justify-content-end">
                                                <button type="button" class="btn btn-sm btn-outline-secondary bn-preview-btn"
                                                    data-id="<?php echo $bid; ?>"
                                                    data-title="<?php echo $title; ?>"
                                                    data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>"
                                                    data-image="<?php echo htmlspecialchars($img); ?>"
                                                    data-desc="<?php echo htmlspecialchars($banner['description'] ?? ''); ?>"
                                                    title="Preview"><i class="bi bi-eye"></i></button>
                                                <a href="<?php echo BASE_URL; ?>?controller=banner&action=edit&id=<?php echo $bid; ?>"
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-banner"
                                                        data-id="<?php echo $bid; ?>"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <div class="bn-chart-card">
                        <h3>Status Mix</h3>
                        <canvas id="bnStatusChart" height="180" aria-label="Banner status chart"></canvas>
                    </div>
                </div>
                <div class="col-12 col-md-6">
                    <div class="bn-chart-card">
                        <h3>Library Snapshot</h3>
                        <ul class="bn-side-list">
                            <li><span class="k">Total assets</span><span class="v"><?php echo $totalBanners; ?></span></li>
                            <li><span class="k">Active</span><span class="v"><?php echo $activeCount; ?></span></li>
                            <li><span class="k">Inactive</span><span class="v"><?php echo $inactiveCount; ?></span></li>
                            <li><span class="k">Active rate</span><span class="v"><?php echo $activePct; ?>%</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-3">
            <div class="bn-card mb-3" id="bnPreviewPanel" style="position:sticky;top:1rem;">
                <div class="bn-card-header">
                    <h3 class="mb-0" style="font-size:1rem;">Banner Preview</h3>
                </div>
                <div class="bn-card-body" id="bnPreviewBody">
                    <p class="text-muted small mb-0">Select a banner or click Preview to inspect desktop, tablet, and mobile framing.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBannerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header" style="background:rgba(220,53,69,0.08);">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this banner? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- Quick Preview Modal -->
<div class="modal fade" id="bnQuickPreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title" id="bnQuickPreviewTitle">Banner Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bn-preview-frame" style="aspect-ratio:16/9;">
                    <img id="bnQuickPreviewImg" src="" alt="Banner preview">
                </div>
                <p class="text-muted small mb-0" id="bnQuickPreviewDesc"></p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-outline-primary" id="bnQuickPreviewEdit">Edit Banner</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var BASE_URL = '<?php echo BASE_URL; ?>';

    // Handle delete button click — preserved contract
    const deleteButtons = document.querySelectorAll('.delete-banner');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    let bannerIdToDelete = null;
    const deleteModalEl = document.getElementById('deleteBannerModal');
    const deleteModal = deleteModalEl && typeof bootstrap !== 'undefined'
        ? new bootstrap.Modal(deleteModalEl)
        : null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            bannerIdToDelete = this.getAttribute('data-id');
            if (deleteModal) deleteModal.show();
        });
    });

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (bannerIdToDelete) {
                window.location.href = `<?php echo BASE_URL; ?>?controller=banner&action=delete&id=${bannerIdToDelete}`;
            }
        });
    }

    function showToast(msg, type) {
        var host = document.getElementById('bnToastHost');
        if (!host) return;
        var t = document.createElement('div');
        t.className = 'bn-toast ' + (type || 'info');
        t.setAttribute('role', 'status');
        t.textContent = msg;
        host.appendChild(t);
        setTimeout(function() {
            t.style.opacity = '0';
            setTimeout(function() { t.remove(); }, 300);
        }, 2800);
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

    /* View modes */
    var gridWrap = document.getElementById('bnGridWrap');
    var listWrap = document.getElementById('bnListWrap');
    var viewBtns = document.querySelectorAll('[data-view]');
    function setView(mode) {
        viewBtns.forEach(function(b) { b.classList.toggle('active', b.getAttribute('data-view') === mode); });
        if (mode === 'list') {
            if (gridWrap) gridWrap.classList.add('d-none');
            if (listWrap) listWrap.classList.remove('d-none');
        } else {
            if (gridWrap) gridWrap.classList.remove('d-none');
            if (listWrap) listWrap.classList.add('d-none');
            if (gridWrap) {
                gridWrap.classList.toggle('bn-gallery-mode', mode === 'gallery');
                var cards = gridWrap.querySelectorAll('.bn-grid-card');
                cards.forEach(function(c) {
                    c.style.gridColumn = mode === 'gallery' ? 'span 1' : '';
                });
                var grid = document.getElementById('bnGrid');
                if (grid) {
                    grid.style.gridTemplateColumns = mode === 'gallery'
                        ? 'repeat(auto-fill, minmax(360px, 1fr))'
                        : '';
                }
            }
        }
    }
    viewBtns.forEach(function(b) {
        b.addEventListener('click', function() { setView(b.getAttribute('data-view')); });
    });

    /* Live search / filter */
    var searchInput = document.getElementById('bnLiveSearch');
    var statusFilter = document.getElementById('bnFilterStatus');
    var resultCount = document.getElementById('bnResultCount');
    var allItems = Array.prototype.slice.call(document.querySelectorAll('.banner-item'));

    function applyFilters() {
        var q = (searchInput && searchInput.value || '').toLowerCase().trim();
        var st = statusFilter ? statusFilter.value : '';
        var shown = 0;
        allItems.forEach(function(el) {
            var title = (el.getAttribute('data-title') || '').toLowerCase();
            var desc = (el.getAttribute('data-desc') || '').toLowerCase();
            var status = el.getAttribute('data-status') || '';
            var match = true;
            if (q && title.indexOf(q) === -1 && desc.indexOf(q) === -1 && status.indexOf(q) === -1) match = false;
            if (st && status !== st) match = false;
            el.style.display = match ? '' : 'none';
            if (match) shown++;
        });
        /* grid + list share same data-id items counted twice — count unique ids */
        var ids = {};
        allItems.forEach(function(el) {
            if (el.style.display === 'none') return;
            ids[el.getAttribute('data-id')] = true;
        });
        var unique = Object.keys(ids).length;
        if (resultCount) resultCount.textContent = unique + ' result' + (unique === 1 ? '' : 's');
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (statusFilter) statusFilter.addEventListener('change', applyFilters);
    var clearBtn = document.getElementById('bnSearchClear');
    if (clearBtn) clearBtn.addEventListener('click', function() {
        if (searchInput) searchInput.value = '';
        applyFilters();
    });
    var resetBtn = document.getElementById('bnResetFilters');
    if (resetBtn) resetBtn.addEventListener('click', function() {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        applyFilters();
        showToast('Filters reset', 'info');
    });
    applyFilters();

    /* Preview panel + modal */
    function renderPreview(data) {
        var body = document.getElementById('bnPreviewBody');
        if (!body || !data) return;
        var active = (data.status || '') === 'active';
        body.innerHTML =
            '<div class="bn-preview-devices">' +
            '<button type="button" class="active" data-aspect="16/9"><i class="bi bi-display"></i> Desktop</button>' +
            '<button type="button" data-aspect="4/3"><i class="bi bi-tablet"></i> Tablet</button>' +
            '<button type="button" data-aspect="9/16"><i class="bi bi-phone"></i> Mobile</button>' +
            '</div>' +
            '<div class="bn-preview-frame" id="bnSidePreviewFrame"><img src="' + (data.image || '') + '" alt=""></div>' +
            '<div class="fw-bold mb-1">' + (data.title || 'Untitled') + '</div>' +
            '<span class="bn-badge ' + (active ? 'bn-status-active' : 'bn-status-inactive') + ' mb-2">' + (active ? 'Active' : 'Inactive') + '</span>' +
            '<p class="small text-muted">' + (data.desc || 'No description') + '</p>' +
            '<a class="btn btn-sm btn-outline-primary w-100" href="' + BASE_URL + '?controller=banner&action=edit&id=' + encodeURIComponent(data.id) + '">Edit Banner</a>';

        body.querySelectorAll('.bn-preview-devices button').forEach(function(btn) {
            btn.addEventListener('click', function() {
                body.querySelectorAll('.bn-preview-devices button').forEach(function(b) { b.classList.remove('active'); });
                btn.classList.add('active');
                var frame = document.getElementById('bnSidePreviewFrame');
                if (frame) frame.style.aspectRatio = btn.getAttribute('data-aspect') || '16/9';
            });
        });
    }

    document.addEventListener('click', function(e) {
        var prev = e.target.closest('.bn-preview-btn');
        if (!prev) return;
        e.preventDefault();
        var data = {
            id: prev.getAttribute('data-id'),
            title: prev.getAttribute('data-title'),
            status: prev.getAttribute('data-status'),
            image: prev.getAttribute('data-image'),
            desc: prev.getAttribute('data-desc')
        };
        renderPreview(data);
        var modalEl = document.getElementById('bnQuickPreviewModal');
        var img = document.getElementById('bnQuickPreviewImg');
        var title = document.getElementById('bnQuickPreviewTitle');
        var desc = document.getElementById('bnQuickPreviewDesc');
        var editLink = document.getElementById('bnQuickPreviewEdit');
        if (img) img.src = data.image || '';
        if (title) title.textContent = data.title || 'Banner Preview';
        if (desc) desc.textContent = data.desc || '';
        if (editLink) editLink.href = BASE_URL + '?controller=banner&action=edit&id=' + encodeURIComponent(data.id);
        if (modalEl && typeof bootstrap !== 'undefined') {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        }
    });

    /* Bulk selection */
    var selectAll = document.getElementById('bnSelectAll');
    var bulkBar = document.getElementById('bnBulkBar');
    var selectedCountEl = document.getElementById('bnSelectedCount');

    function updateBulkBar() {
        var checked = document.querySelectorAll('.bn-row-check:checked');
        var ids = {};
        Array.prototype.forEach.call(checked, function(cb) { ids[cb.value] = true; });
        var n = Object.keys(ids).length;
        if (selectedCountEl) selectedCountEl.textContent = String(n);
        if (bulkBar) {
            if (n > 0) bulkBar.classList.add('is-visible');
            else bulkBar.classList.remove('is-visible');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            document.querySelectorAll('#bannersTable .bn-row-check').forEach(function(cb) {
                var row = cb.closest('.banner-item');
                if (row && row.style.display === 'none') return;
                cb.checked = selectAll.checked;
            });
            updateBulkBar();
        });
    }
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('bn-row-check')) updateBulkBar();
    });

    function getSelectedIds() {
        var ids = {};
        document.querySelectorAll('.bn-row-check:checked').forEach(function(cb) { ids[cb.value] = true; });
        return Object.keys(ids);
    }

    function exportCsv() {
        var lines = ['ID,Title,Status,Created'];
        var seen = {};
        allItems.forEach(function(el) {
            var id = el.getAttribute('data-id');
            if (seen[id] || el.style.display === 'none') return;
            seen[id] = true;
            lines.push([
                id,
                '"' + (el.getAttribute('data-title') || '').replace(/"/g, '""') + '"',
                el.getAttribute('data-status'),
                '"' + (el.getAttribute('data-created') || '') + '"'
            ].join(','));
        });
        var blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'banners-export.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    }

    var exportBtn = document.getElementById('bnExportCsvBtn');
    if (exportBtn) exportBtn.addEventListener('click', function() {
        exportCsv();
        showToast('Exported banners to CSV', 'success');
    });
    var bulkExport = document.getElementById('bnBulkExport');
    if (bulkExport) bulkExport.addEventListener('click', function() {
        exportCsv();
        showToast('Exported banners to CSV', 'success');
    });

    function bulkHint(msg) {
        showToast(msg + ' — use Edit / Delete on each banner (no bulk API).', 'warning');
    }
    var ba = document.getElementById('bnBulkActivate');
    var bd = document.getElementById('bnBulkDeactivate');
    var bdel = document.getElementById('bnBulkDelete');
    var bclear = document.getElementById('bnBulkClear');
    if (ba) ba.addEventListener('click', function() { bulkHint('Bulk activate'); });
    if (bd) bd.addEventListener('click', function() { bulkHint('Bulk deactivate'); });
    if (bdel) bdel.addEventListener('click', function() {
        var ids = getSelectedIds();
        if (ids.length === 1) {
            bannerIdToDelete = ids[0];
            if (deleteModal) deleteModal.show();
        } else {
            bulkHint('Bulk delete');
        }
    });
    if (bclear) bclear.addEventListener('click', function() {
        document.querySelectorAll('.bn-row-check').forEach(function(cb) { cb.checked = false; });
        if (selectAll) selectAll.checked = false;
        updateBulkBar();
    });
    var importBtn = document.getElementById('bnImportBtn');
    if (importBtn) importBtn.addEventListener('click', function() {
        showToast('Import is a UI preview — use Add Banner to upload creatives.', 'info');
    });

    if (typeof Chart !== 'undefined') {
        var ctx = document.getElementById('bnStatusChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Active', 'Inactive'],
                    datasets: [{
                        data: [<?php echo (int)$activeCount; ?>, <?php echo (int)$inactiveCount; ?>],
                        backgroundColor: ['#10b981', '#94a3b8'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '62%' }
            });
        }
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
