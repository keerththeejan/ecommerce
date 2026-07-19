<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/brand-list.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<?php
$rows = !empty($brands['data']) && is_array($brands['data']) ? $brands['data'] : [];
$totalAll = (int)($brands['total'] ?? count($rows));
$activeCount = 0;
$inactiveCount = 0;
foreach ($rows as $b) {
    $st = is_array($b) ? ($b['status'] ?? '') : ($b->status ?? '');
    if ($st === 'active' || $st === 1 || $st === '1') $activeCount++;
    else $inactiveCount++;
}
$featuredCount = 0;
$hiddenCount = $inactiveCount;
$withProducts = 0; // not in payload
$totalProducts = 0;
$recentCount = min(5, count($rows));

$cur = (int)($brands['current_page'] ?? 1);
$last = (int)($brands['last_page'] ?? 1);
$perParam = $brands['per_page_param'] ?? '20';
$searchVal = isset($search) ? (string)$search : '';
$pageBase = BASE_URL . '?controller=brand&action=adminIndex&per_page=' . urlencode((string)$perParam);
if ($searchVal !== '') $pageBase .= '&search=' . urlencode($searchVal);

$latest = array_slice(array_reverse($rows), 0, 5);
$currentPerPage = $brands['per_page_param'] ?? '20';
$defaultLogo = BASE_URL . 'public/images/default-brand.png';
?>

<div class="container-fluid brand-list-page py-3 py-md-4 px-2 px-sm-3" id="brandAdminPage">
    <div class="bl-toast-host" id="blToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="bl-header">
        <div>
            <nav class="bl-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span>›</span>
                <span>Catalog</span>
                <span>›</span>
                <span aria-current="page">Brands</span>
            </nav>
            <h1 class="bl-title">Brand Management</h1>
            <p class="bl-subtitle">Manage brand identity, logos, visibility, and catalog associations.</p>
        </div>
        <div class="bl-actions">
            <button type="button" class="btn btn-outline-secondary bl-btn" id="blRefreshBtn" title="Refresh"><i class="bi bi-arrow-clockwise"></i><span class="d-none d-md-inline">Refresh</span></button>
            <button type="button" class="btn btn-outline-secondary bl-btn" id="blPrintBtn" title="Print"><i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span></button>
            <button type="button" class="btn btn-outline-secondary bl-btn" id="blExportCsvBtn" title="Excel / CSV"><i class="bi bi-file-earmark-spreadsheet"></i><span class="d-none d-lg-inline">Excel</span></button>
            <button type="button" class="btn btn-outline-secondary bl-btn" id="blExportPdfBtn" title="PDF"><i class="bi bi-filetype-pdf"></i><span class="d-none d-lg-inline">PDF</span></button>
            <button type="button" class="btn btn-outline-secondary bl-btn" id="blImportBtn" title="Import"><i class="bi bi-upload"></i><span class="d-none d-xl-inline">Import</span></button>
            <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" class="btn bl-btn bl-btn-primary"><i class="bi bi-plus-lg"></i><span>Add Brand</span></a>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bl-stat s1"><div class="icon" aria-hidden="true"><i class="bi bi-tags"></i></div><div><div class="label">Total Brands</div><div class="value" data-counter="<?php echo $totalAll; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bl-stat s2"><div class="icon" aria-hidden="true"><i class="bi bi-check-circle"></i></div><div><div class="label">Active</div><div class="value" data-counter="<?php echo $activeCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bl-stat s3"><div class="icon" aria-hidden="true"><i class="bi bi-x-circle"></i></div><div><div class="label">Inactive</div><div class="value" data-counter="<?php echo $inactiveCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bl-stat s4"><div class="icon" aria-hidden="true"><i class="bi bi-star"></i></div><div><div class="label">Featured</div><div class="value" data-counter="<?php echo $featuredCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bl-stat s5"><div class="icon" aria-hidden="true"><i class="bi bi-eye-slash"></i></div><div><div class="label">Hidden</div><div class="value" data-counter="<?php echo $hiddenCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bl-stat s6"><div class="icon" aria-hidden="true"><i class="bi bi-box-seam"></i></div><div><div class="label">With Products</div><div class="value" data-counter="<?php echo $withProducts; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bl-stat s7"><div class="icon" aria-hidden="true"><i class="bi bi-basket"></i></div><div><div class="label">Products</div><div class="value" data-counter="<?php echo $totalProducts; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bl-stat s8"><div class="icon" aria-hidden="true"><i class="bi bi-clock-history"></i></div><div><div class="label">Recently Added</div><div class="value" data-counter="<?php echo $recentCount; ?>">0</div></div></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-9">
            <div class="bl-card">
                <div class="bl-toolbar">
                    <div class="flex-grow-1" style="min-width:240px;max-width:560px;">
                        <form action="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex" method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-0" id="brandSearchForm" role="search">
                            <input type="hidden" name="controller" value="brand">
                            <input type="hidden" name="action" value="adminIndex">
                            <?php if ($currentPerPage !== '20'): ?>
                            <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($currentPerPage); ?>">
                            <?php endif; ?>
                            <div class="input-group flex-grow-1" style="min-width:180px;">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" name="search" id="brandSearchInput" class="form-control border-start-0" placeholder="Search brands…" value="<?php echo htmlspecialchars($searchVal); ?>" aria-label="Search brands" autocomplete="off">
                            </div>
                            <button type="submit" class="btn btn-outline-primary bl-btn">Search</button>
                            <?php if(!empty($searchVal)): ?>
                                <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex<?php echo $currentPerPage !== '20' ? '&per_page=' . urlencode($currentPerPage) : ''; ?>" class="btn btn-outline-secondary bl-btn">Reset</a>
                            <?php else: ?>
                                <button type="button" class="btn btn-outline-secondary bl-btn" id="blClientResetBtn">Reset</button>
                            <?php endif; ?>
                        </form>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-secondary bl-btn" data-bs-toggle="offcanvas" data-bs-target="#blFilterDrawer"><i class="bi bi-funnel"></i><span>Filters</span></button>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary bl-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-layout-three-columns"></i><span class="d-none d-md-inline">Columns</span></button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 bl-col-toggle" data-col="slug" checked> Slug</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 bl-col-toggle" data-col="code" checked> Code</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 bl-col-toggle" data-col="created" checked> Created</label></li>
                            </ul>
                        </div>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-outline-secondary bl-btn btn-sm"><i class="fas fa-arrow-left"></i><span class="d-none d-lg-inline">Product</span></a>
                        <a href="<?php echo BASE_URL; ?>?controller=brand&action=create" class="btn bl-btn bl-btn-primary btn-sm"><i class="bi bi-plus-lg"></i><span>New</span></a>
                    </div>
                </div>

                <div class="bl-alpha" id="blAlphaFilter" aria-label="Alphabet filter">
                    <button type="button" class="active" data-letter="">All</button>
                    <?php foreach (range('A', 'Z') as $letter): ?>
                        <button type="button" data-letter="<?php echo $letter; ?>"><?php echo $letter; ?></button>
                    <?php endforeach; ?>
                </div>

                <div class="bl-bulk-bar" id="blBulkBar" aria-live="polite">
                    <div class="small fw-semibold"><span id="blSelectedCount">0</span> selected</div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-success bl-btn" id="blBulkActivate">Activate</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary bl-btn" id="blBulkDeactivate">Deactivate</button>
                        <button type="button" class="btn btn-sm btn-outline-warning bl-btn" id="blBulkFeature">Feature</button>
                        <button type="button" class="btn btn-sm btn-outline-primary bl-btn" id="blBulkExport">Export Selected</button>
                        <button type="button" class="btn btn-sm btn-outline-danger bl-btn" id="blBulkDelete">Delete</button>
                    </div>
                </div>

                <div id="alert-messages" class="px-3 pt-2">
                    <?php flash('brand_success', '', 'alert alert-success alert-dismissible fade show'); ?>
                    <?php flash('brand_error', '', 'alert alert-danger alert-dismissible fade show'); ?>
                </div>

                <?php if(empty($rows)): ?>
                    <div class="bl-empty">
                        <i class="bi bi-tags fs-1 text-primary d-block mb-2"></i>
                        <?php if(!empty($searchVal)): ?>
                            <div class="fw-semibold mb-1">No brands found matching "<?php echo htmlspecialchars($searchVal); ?>"</div>
                            <a href="<?php echo BASE_URL; ?>?controller=brand&action=adminIndex">Show all</a>
                        <?php else: ?>
                            <div class="fw-semibold mb-1">No brands found. Create your first brand!</div>
                            <a class="btn bl-btn bl-btn-primary mt-2" href="<?php echo BASE_URL; ?>?controller=brand&action=create">Add Brand</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="table-topbar">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <label for="brandPerPageFilter" class="form-label mb-0 small text-muted">Show:</label>
                            <select id="brandPerPageFilter" class="form-select form-select-sm" style="width: auto; min-width: 4rem;" aria-label="Rows per page">
                            <?php
                            $baseUrl = BASE_URL . '?controller=brand&action=adminIndex';
                            if (!empty($searchVal)) $baseUrl .= '&search=' . urlencode($searchVal);
                            foreach (['20', '50', '100', 'all'] as $opt):
                                $url = $baseUrl . '&per_page=' . $opt;
                                $sel = ($currentPerPage === $opt) ? ' selected' : '';
                            ?>
                                <option value="<?php echo htmlspecialchars($url); ?>"<?php echo $sel; ?>><?php echo $opt === 'all' ? 'All' : $opt; ?></option>
                            <?php endforeach; ?>
                            </select>
                            <span class="small text-muted"><?php echo $totalAll; ?> total</span>
                            <input type="search" class="form-control form-control-sm" id="blLiveFilter" placeholder="Live filter…" style="max-width:180px" aria-label="Live filter">
                        </div>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <a class="btn btn-sm btn-outline-secondary bl-btn <?php echo ($cur <= 1) ? 'disabled' : ''; ?>" href="<?php echo $pageBase . '&page=' . max(1, $cur - 1); ?>">Prev</a>
                            <span class="small text-muted">Page <?php echo $cur; ?> / <?php echo max(1, $last); ?></span>
                            <a class="btn btn-sm btn-outline-secondary bl-btn <?php echo ($cur >= $last) ? 'disabled' : ''; ?>" href="<?php echo $pageBase . '&page=' . min($last, $cur + 1); ?>">Next</a>
                        </div>
                    </div>
                    <script>
                    (function() {
                        var el = document.getElementById('brandPerPageFilter');
                        if (el) el.addEventListener('change', function() { window.location.href = this.value; });
                    })();
                    </script>

                    <div class="brands-table-scroll table-responsive" role="region" aria-label="Brands table" tabindex="0">
                        <table id="brandsTable" class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" style="cursor:default"><input type="checkbox" class="form-check-input" id="blSelectAll" aria-label="Select all"></th>
                                    <th scope="col" style="width: 60px;" data-sort="num">#</th>
                                    <th scope="col" style="width: 100px;cursor:default">Logo</th>
                                    <th scope="col" data-sort="name">Name</th>
                                    <th scope="col" class="bl-col-code" data-sort="code">Code</th>
                                    <th scope="col" class="bl-col-slug" data-sort="slug">Slug</th>
                                    <th scope="col" data-sort="status">Status</th>
                                    <th scope="col" class="bl-col-created" data-sort="created">Created</th>
                                    <th scope="col" style="width: 170px;cursor:default">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $page = isset($brands['current_page']) ? (int)$brands['current_page'] : 1;
                                $perPage = isset($brands['per_page']) ? (int)$brands['per_page'] : 20;
                                foreach($rows as $idx => $brand):
                                    $rowNum = ($page - 1) * $perPage + $idx + 1;
                                    $isActive = ($brand['status'] == 'active');
                                    $code = 'BR-' . str_pad((string)(int)$brand['id'], 4, '0', STR_PAD_LEFT);
                                    $letter = strtoupper(substr(trim($brand['name'] ?? ''), 0, 1));
                                    if (!preg_match('/[A-Z]/', $letter)) $letter = '#';
                                    $createdTs = !empty($brand['created_at']) ? strtotime($brand['created_at']) : 0;
                                    $logoUrl = !empty($brand['logo']) ? $brand['logo'] : '';
                                ?>
                                    <tr id="brand-row-<?php echo (int)$brand['id']; ?>"
                                        data-name="<?php echo htmlspecialchars(strtolower($brand['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                        data-slug="<?php echo htmlspecialchars(strtolower($brand['slug'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                        data-code="<?php echo htmlspecialchars(strtolower($code), ENT_QUOTES, 'UTF-8'); ?>"
                                        data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>"
                                        data-letter="<?php echo htmlspecialchars($letter); ?>"
                                        data-created="<?php echo (int)$createdTs; ?>">
                                        <td data-label="Select"><input type="checkbox" class="form-check-input bl-row-check" value="<?php echo (int)$brand['id']; ?>" aria-label="Select <?php echo htmlspecialchars($brand['name']); ?>"></td>
                                        <td data-label="#"><?php echo $rowNum; ?></td>
                                        <td class="align-middle" data-label="Logo">
                                            <div class="brand-logo-container">
                                                <img src="<?php echo htmlspecialchars($logoUrl); ?>"
                                                     alt="<?php echo htmlspecialchars($brand['name']); ?>"
                                                     class="img-fluid"
                                                     loading="lazy"
                                                     onerror="this.onerror=null; this.src='<?php echo $defaultLogo; ?>';">
                                            </div>
                                        </td>
                                        <td data-label="Name">
                                            <div class="brand-name"><?php echo htmlspecialchars($brand['name']); ?></div>
                                            <div class="brand-meta">ID: <?php echo (int)$brand['id']; ?></div>
                                        </td>
                                        <td data-label="Code" class="bl-col-code"><span class="brand-meta mb-0"><?php echo htmlspecialchars($code); ?></span></td>
                                        <td data-label="Slug" class="bl-col-slug"><?php echo htmlspecialchars($brand['slug']); ?></td>
                                        <td data-label="Status">
                                            <?php if($isActive): ?>
                                                <span class="badge-status badge-status--active"><span style="width:6px;height:6px;border-radius:999px;background:#198754;display:inline-block;"></span>Active</span>
                                            <?php else: ?>
                                                <span class="badge-status badge-status--inactive"><span style="width:6px;height:6px;border-radius:999px;background:#dc2626;display:inline-block;"></span>Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Created" class="bl-col-created"><?php echo !empty($brand['created_at']) ? date('M d, Y', strtotime($brand['created_at'])) : '—'; ?></td>
                                        <td data-label="Actions">
                                            <div class="btn-group btn-group-sm flex-wrap">
                                                <button type="button" class="btn btn-outline-secondary bl-quick-view"
                                                    data-name="<?php echo htmlspecialchars($brand['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-slug="<?php echo htmlspecialchars($brand['slug'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                                                    data-status="<?php echo $isActive ? 'Active' : 'Inactive'; ?>"
                                                    data-logo="<?php echo htmlspecialchars($logoUrl ?: $defaultLogo, ENT_QUOTES, 'UTF-8'); ?>"
                                                    title="View" aria-label="Preview brand"><i class="bi bi-eye"></i></button>
                                                <a href="<?php echo BASE_URL; ?>?controller=brand&action=edit&id=<?php echo $brand['id']; ?>" class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Edit</span>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex&brand=<?php echo (int)$brand['id']; ?>" class="btn btn-outline-secondary" title="Products" aria-label="View products"><i class="bi bi-box-seam"></i></a>
                                                <button type="button" class="btn btn-outline-danger delete-brand" data-id="<?php echo $brand['id']; ?>" data-name="<?php echo htmlspecialchars($brand['name']); ?>">
                                                    <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 col-xl-3 bl-side-col">
            <aside class="bl-card">
                <div class="bl-card-header"><h3><i class="bi bi-bar-chart text-primary"></i> Brand Statistics</h3></div>
                <div class="bl-card-body">
                    <ul class="bl-side-list">
                        <li><span class="k">Total</span><span class="v"><?php echo $totalAll; ?></span></li>
                        <li><span class="k">On this page</span><span class="v"><?php echo count($rows); ?></span></li>
                        <li><span class="k">Active (page)</span><span class="v"><?php echo $activeCount; ?></span></li>
                        <li><span class="k">Inactive (page)</span><span class="v"><?php echo $inactiveCount; ?></span></li>
                        <li><span class="k">Page</span><span class="v"><?php echo $cur; ?> / <?php echo max(1, $last); ?></span></li>
                    </ul>
                </div>
            </aside>

            <aside class="bl-card">
                <div class="bl-card-header"><h3><i class="bi bi-clock-history text-primary"></i> Recently Added</h3></div>
                <div class="bl-card-body">
                    <?php if (empty($latest)): ?>
                        <div class="small text-muted">No brands yet.</div>
                    <?php else: ?>
                        <ul class="bl-side-list">
                            <?php foreach ($latest as $lb): ?>
                                <li>
                                    <span class="k"><?php echo htmlspecialchars($lb['name']); ?></span>
                                    <span class="v"><?php echo (($lb['status'] ?? '') === 'active') ? 'Active' : 'Off'; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </aside>

            <aside class="bl-card">
                <div class="bl-card-header"><h3><i class="bi bi-search text-primary"></i> SEO Snapshot</h3></div>
                <div class="bl-card-body">
                    <div class="bl-seo-mini" id="blSeoPreview">
                        <div class="g-url"><?php echo rtrim(BASE_URL, '/'); ?>/brand/…</div>
                        <div class="g-title">Select a brand to preview</div>
                        <div class="g-desc">Meta description preview appears when you open brand preview.</div>
                    </div>
                </div>
            </aside>

            <aside class="bl-card">
                <div class="bl-card-header"><h3><i class="bi bi-lightning text-primary"></i> Quick Actions</h3></div>
                <div class="bl-card-body bl-shortcut">
                    <a href="<?php echo BASE_URL; ?>?controller=brand&action=create"><i class="bi bi-plus-circle text-primary"></i> Add Brand</a>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex"><i class="bi bi-box-seam text-primary"></i> Products</a>
                    <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex"><i class="bi bi-folder2-open text-primary"></i> Categories</a>
                    <a href="<?php echo BASE_URL; ?>?controller=home&action=admin"><i class="bi bi-speedometer2 text-primary"></i> Dashboard</a>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- Filter Drawer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="blFilterDrawer" aria-labelledby="blFilterDrawerLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="blFilterDrawerLabel">Advanced Filters</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-3">
            <label class="form-label fw-semibold" for="blFilterStatus">Status</label>
            <select id="blFilterStatus" class="form-select">
                <option value="">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold" for="blFilterCode">Brand Code</label>
            <input type="text" id="blFilterCode" class="form-control" placeholder="e.g. BR-0001">
        </div>
        <p class="small text-muted">Client-side filters apply to the current page. Use Search for server-wide results.</p>
        <div class="d-grid gap-2">
            <button type="button" class="btn bl-btn bl-btn-primary" id="blApplyFilters" data-bs-dismiss="offcanvas">Apply Filters</button>
            <button type="button" class="btn btn-outline-secondary bl-btn" id="blClearFilters">Clear</button>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="blPreviewModal" tabindex="-1" aria-labelledby="blPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="blPreviewModalLabel">Brand Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="" id="blPrevLogo" class="rounded-4 border mb-3" style="max-width:140px;max-height:100px;object-fit:contain;">
                <h5 id="blPrevName" class="mb-1">—</h5>
                <div class="text-muted small mb-2" id="blPrevSlug">—</div>
                <span class="badge bg-secondary" id="blPrevStatus">—</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation -->
<div class="modal fade" id="blBulkDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4">
            <div class="modal-header py-2">
                <h6 class="modal-title mb-0">Delete selected?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body small">Permanently delete <strong id="blBulkDeleteCount">0</strong> brand(s)? Brands with products cannot be deleted.</div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-danger" id="blConfirmBulkDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteBrandModal" tabindex="-1" aria-labelledby="deleteBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteBrandModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the brand "<strong id="brandNameToDelete"></strong>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBrand">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
// Delete brand functionality
document.addEventListener('DOMContentLoaded', function() {
    // Function to get CSRF token
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    let pendingDelete = { id: null, name: null, row: null };

    // Event delegation for delete buttons
    document.addEventListener('click', function(e) {
        const deleteBtn = e.target.closest('.delete-brand');
        if (!deleteBtn) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        const brandId = deleteBtn.getAttribute('data-id');
        const brandName = deleteBtn.getAttribute('data-name');
        const row = deleteBtn.closest('tr');

        pendingDelete = { id: brandId, name: brandName, row: row };
        const nameEl = document.getElementById('brandNameToDelete');
        if (nameEl) nameEl.textContent = brandName || '';
        const modalEl = document.getElementById('deleteBrandModal');
        if (modalEl && window.bootstrap) {
            bootstrap.Modal.getOrCreateInstance(modalEl).show();
        } else if (confirm(`Are you sure you want to delete the brand "${brandName}"? This action cannot be undone.`)) {
            deleteBrand(brandId, brandName, row);
        }
    });

    const confirmDeleteBrand = document.getElementById('confirmDeleteBrand');
    if (confirmDeleteBrand) {
        confirmDeleteBrand.addEventListener('click', function () {
            if (!pendingDelete.id) return;
            const modalEl = document.getElementById('deleteBrandModal');
            if (modalEl && window.bootstrap) bootstrap.Modal.getOrCreateInstance(modalEl).hide();
            deleteBrand(pendingDelete.id, pendingDelete.name, pendingDelete.row);
            pendingDelete = { id: null, name: null, row: null };
        });
    }
    
    function deleteBrand(brandId, brandName, row) {
        if (!brandId) {
            console.error('Missing brand ID');
            showAlert('Error: Missing brand information', 'danger');
            return;
        }
        
        const deleteBtn = row?.querySelector('.delete-brand');
        const originalHtml = deleteBtn?.innerHTML || '';
        
        // Show loading state
        if (deleteBtn) {
            deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
            deleteBtn.disabled = true;
        }
        
        // Get CSRF token
        const csrfToken = getCsrfToken();
        if (!csrfToken) {
            console.error('CSRF token not found');
            resetButton(deleteBtn, originalHtml);
            showAlert('Security error: Please refresh the page and try again', 'danger');
            return;
        }
        
        // Make the request
        fetch(`?controller=brand&action=delete&id=${brandId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `_method=DELETE&csrf_token=${encodeURIComponent(csrfToken)}`
        })
        .then(async response => {
            const data = await response.json().catch(() => ({}));
            
            if (!response.ok) {
                const error = new Error(data.message || `HTTP error! status: ${response.status}`);
                error.response = response;
                error.data = data;
                throw error;
            }
            
            return data;
        })
        .then(data => {
            if (data.success) {
                // Fade out and remove row
                if (row) {
                    row.style.transition = 'opacity 0.3s';
                    row.style.opacity = '0';
                    
                    setTimeout(() => {
                        row.remove();
                        checkIfTableEmpty();
                        if (typeof updateBulkBar === 'function') updateBulkBar();
                    }, 300);
                }
                
                showAlert(data.message || 'Brand deleted successfully', 'success');
            } else {
                throw new Error(data.message || 'Failed to delete brand');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            const errorMessage = error.data?.message || error.message || 'An error occurred while deleting the brand';
            showAlert(errorMessage, 'danger');
            
            // If it's an authentication error, redirect to login
            if (error.response?.status === 401) {
                setTimeout(() => {
                    window.location.href = '?controller=user&action=login';
                }, 2000);
            }
        })
        .finally(() => {
            if (deleteBtn && originalHtml) {
                resetButton(deleteBtn, originalHtml);
            }
        });
    }

    // Expose for bulk delete
    window.__brandDeleteBrand = deleteBrand;
    
    function checkIfTableEmpty() {
        const tbody = document.querySelector('#brandsTable tbody') || document.querySelector('table tbody');
        if (tbody && tbody.querySelectorAll('tr[data-name]').length === 0) {
            const noResults = document.createElement('tr');
            noResults.innerHTML = `
                <td colspan="9" class="text-center py-4">
                    <div class="alert alert-info mb-0">No brands found.</div>
                </td>`;
            tbody.appendChild(noResults);
        }
    }
    
    function showAlert(message, type = 'success') {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.alert-dismissible');
        existingAlerts.forEach(alert => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        });
        
        // Create alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            <i class="${type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Add to page — prefer #alert-messages, fallback to legacy selector
        const container = document.getElementById('alert-messages')
            || document.querySelector('.container-fluid > .row > .col-md-12')
            || document.querySelector('.brand-list-page');
        if (container) {
            container.insertBefore(alertDiv, container.firstChild);
            
            // Auto-dismiss
            setTimeout(() => {
                if (alertDiv.parentNode) {
                    const bsAlert = bootstrap.Alert.getOrCreateInstance(alertDiv);
                    bsAlert.close();
                }
            }, 5000);
        } else {
            alert(message);
        }
    }
    
    function resetButton(button, originalHtml) {
        if (button) {
            button.innerHTML = originalHtml;
            button.disabled = false;
            
            // Re-enable any form elements that might be disabled
            const form = button.closest('form');
            if (form) {
                const formElements = form.elements;
                for (let i = 0; i < formElements.length; i++) {
                    formElements[i].disabled = false;
                }
            }
        }
    }

    // ---- Enterprise UI helpers ----
    function toast(type, title, msg) {
        const host = document.getElementById('blToastHost');
        if (!host) return;
        const el = document.createElement('div');
        el.className = 'bl-toast ' + type;
        el.innerHTML = '<strong>' + title + '</strong><div class="small text-muted">' + msg + '</div>';
        host.appendChild(el);
        setTimeout(function () { el.remove(); }, 4000);
    }

    document.querySelectorAll('[data-counter]').forEach(function (el) {
        const target = parseInt(el.getAttribute('data-counter'), 10) || 0;
        const duration = 700;
        const start = performance.now();
        function tick(now) {
            const p = Math.min(1, (now - start) / duration);
            el.textContent = String(Math.round(target * (0.5 - Math.cos(Math.PI * p) / 2)));
            if (p < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    });

    let alphaLetter = '';
    const selectAll = document.getElementById('blSelectAll');
    const bulkBar = document.getElementById('blBulkBar');

    function selectedChecks() {
        return Array.prototype.slice.call(document.querySelectorAll('.bl-row-check:checked'));
    }
    function updateBulkBar() {
        const selected = selectedChecks();
        const countEl = document.getElementById('blSelectedCount');
        if (countEl) countEl.textContent = String(selected.length);
        if (bulkBar) bulkBar.classList.toggle('is-visible', selected.length > 0);
        document.querySelectorAll('#brandsTable tbody tr[data-name]').forEach(function (tr) {
            const cb = tr.querySelector('.bl-row-check');
            tr.classList.toggle('is-selected', !!(cb && cb.checked));
        });
    }
    window.updateBulkBar = updateBulkBar;

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.bl-row-check').forEach(function (cb) {
                const tr = cb.closest('tr');
                if (tr && tr.style.display === 'none') return;
                cb.checked = selectAll.checked;
            });
            updateBulkBar();
        });
    }
    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('bl-row-check')) updateBulkBar();
    });

    function applyClientFilters() {
        const live = (document.getElementById('blLiveFilter') && document.getElementById('blLiveFilter').value || '').toLowerCase().trim();
        const st = (document.getElementById('blFilterStatus') && document.getElementById('blFilterStatus').value) || '';
        const code = (document.getElementById('blFilterCode') && document.getElementById('blFilterCode').value || '').toLowerCase().trim();
        document.querySelectorAll('#brandsTable tbody tr[data-name]').forEach(function (tr) {
            const hay = [
                tr.getAttribute('data-name') || '',
                tr.getAttribute('data-slug') || '',
                tr.getAttribute('data-code') || '',
                tr.getAttribute('data-status') || ''
            ].join(' ');
            let ok = true;
            if (live && hay.indexOf(live) === -1) ok = false;
            if (st && (tr.getAttribute('data-status') || '') !== st) ok = false;
            if (code && (tr.getAttribute('data-code') || '').indexOf(code) === -1) ok = false;
            if (alphaLetter && (tr.getAttribute('data-letter') || '') !== alphaLetter) ok = false;
            tr.style.display = ok ? '' : 'none';
        });
    }

    const liveFilter = document.getElementById('blLiveFilter');
    if (liveFilter) liveFilter.addEventListener('input', applyClientFilters);
    const applyFilters = document.getElementById('blApplyFilters');
    if (applyFilters) applyFilters.addEventListener('click', applyClientFilters);
    const clearFilters = document.getElementById('blClearFilters');
    if (clearFilters) clearFilters.addEventListener('click', function () {
        ['blFilterStatus', 'blFilterCode', 'blLiveFilter'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        alphaLetter = '';
        document.querySelectorAll('#blAlphaFilter button').forEach(function (b) {
            b.classList.toggle('active', b.getAttribute('data-letter') === '');
        });
        applyClientFilters();
        toast('info', 'Filters cleared', 'Showing all rows on this page.');
    });

    document.querySelectorAll('#blAlphaFilter button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#blAlphaFilter button').forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            alphaLetter = btn.getAttribute('data-letter') || '';
            applyClientFilters();
        });
    });

    const clientReset = document.getElementById('blClientResetBtn');
    if (clientReset) clientReset.addEventListener('click', function () {
        const input = document.getElementById('brandSearchInput');
        if (input) input.value = '';
        if (liveFilter) liveFilter.value = '';
        alphaLetter = '';
        applyClientFilters();
    });

    document.querySelectorAll('.bl-col-toggle').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const col = cb.getAttribute('data-col');
            document.querySelectorAll('.bl-col-' + col).forEach(function (el) {
                el.style.display = cb.checked ? '' : 'none';
            });
        });
    });

    // Simple column sort on current page
    document.querySelectorAll('#brandsTable thead th[data-sort]').forEach(function (th) {
        th.addEventListener('click', function () {
            const key = th.getAttribute('data-sort');
            const tbody = document.querySelector('#brandsTable tbody');
            if (!tbody) return;
            const rows = Array.prototype.slice.call(tbody.querySelectorAll('tr[data-name]'));
            const asc = th.getAttribute('data-asc') !== '1';
            th.setAttribute('data-asc', asc ? '1' : '0');
            rows.sort(function (a, b) {
                let av = a.getAttribute('data-' + (key === 'num' ? 'created' : key)) || '';
                let bv = b.getAttribute('data-' + (key === 'num' ? 'created' : key)) || '';
                if (key === 'num' || key === 'created') {
                    av = parseInt(a.querySelector('[data-label="#"]')?.textContent || '0', 10);
                    bv = parseInt(b.querySelector('[data-label="#"]')?.textContent || '0', 10);
                    if (key === 'created') {
                        av = parseInt(a.getAttribute('data-created') || '0', 10);
                        bv = parseInt(b.getAttribute('data-created') || '0', 10);
                    }
                    return asc ? av - bv : bv - av;
                }
                av = String(av); bv = String(bv);
                return asc ? av.localeCompare(bv) : bv.localeCompare(av);
            });
            rows.forEach(function (r) { tbody.appendChild(r); });
        });
    });

    function exportVisibleCsv(selectedOnly) {
        const rows = [['ID', 'Name', 'Code', 'Slug', 'Status', 'Created']];
        document.querySelectorAll('#brandsTable tbody tr[data-name]').forEach(function (tr) {
            if (tr.style.display === 'none') return;
            const cb = tr.querySelector('.bl-row-check');
            if (selectedOnly && (!cb || !cb.checked)) return;
            rows.push([
                cb ? cb.value : '',
                (tr.querySelector('.brand-name') && tr.querySelector('.brand-name').textContent) || '',
                (tr.getAttribute('data-code') || '').toUpperCase(),
                tr.getAttribute('data-slug') || '',
                tr.getAttribute('data-status') || '',
                (tr.querySelector('[data-label="Created"]') && tr.querySelector('[data-label="Created"]').textContent.trim()) || ''
            ]);
        });
        const csv = rows.map(function (r) {
            return r.map(function (c) { return '"' + String(c).replace(/"/g, '""') + '"'; }).join(',');
        }).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'brands-export.csv';
        a.click();
        URL.revokeObjectURL(a.href);
        toast('success', 'Export ready', 'CSV downloaded.');
    }

    const exportCsvBtn = document.getElementById('blExportCsvBtn');
    if (exportCsvBtn) exportCsvBtn.addEventListener('click', function () { exportVisibleCsv(false); });
    const bulkExport = document.getElementById('blBulkExport');
    if (bulkExport) bulkExport.addEventListener('click', function () { exportVisibleCsv(true); });
    const printBtn = document.getElementById('blPrintBtn');
    if (printBtn) printBtn.addEventListener('click', function () { window.print(); });
    const pdfBtn = document.getElementById('blExportPdfBtn');
    if (pdfBtn) pdfBtn.addEventListener('click', function () {
        toast('info', 'PDF', 'Use Print → Save as PDF.');
        window.print();
    });
    const importBtn = document.getElementById('blImportBtn');
    if (importBtn) importBtn.addEventListener('click', function () {
        toast('warning', 'Import', 'Import UI is ready. Persisting imports needs a backend import endpoint.');
    });
    const refreshBtn = document.getElementById('blRefreshBtn');
    if (refreshBtn) refreshBtn.addEventListener('click', function () { window.location.reload(); });

    function bulkUiToast(action) {
        const n = selectedChecks().length;
        if (!n) { toast('warning', 'Nothing selected', 'Select one or more brands first.'); return; }
        toast('info', action, n + ' row(s) marked in the workspace. Persist via Edit when backend supports bulk updates.');
    }
    ['blBulkActivate', 'blBulkDeactivate', 'blBulkFeature'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) el.addEventListener('click', function () { bulkUiToast(el.textContent.trim()); });
    });

    const bulkDeleteBtn = document.getElementById('blBulkDelete');
    const bulkDeleteModalEl = document.getElementById('blBulkDeleteModal');
    const bulkDeleteModal = bulkDeleteModalEl ? new bootstrap.Modal(bulkDeleteModalEl) : null;
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function () {
            const n = selectedChecks().length;
            if (!n) { toast('warning', 'Nothing selected', 'Select brands to delete.'); return; }
            const countEl = document.getElementById('blBulkDeleteCount');
            if (countEl) countEl.textContent = String(n);
            if (bulkDeleteModal) bulkDeleteModal.show();
        });
    }
    const confirmBulkDelete = document.getElementById('blConfirmBulkDelete');
    if (confirmBulkDelete) {
        confirmBulkDelete.addEventListener('click', function () {
            const items = selectedChecks().map(function (cb) {
                return { id: cb.value, row: cb.closest('tr'), name: cb.closest('tr')?.querySelector('.brand-name')?.textContent || '' };
            });
            if (!items.length) return;
            confirmBulkDelete.disabled = true;
            let chain = Promise.resolve();
            items.forEach(function (item) {
                chain = chain.then(function () {
                    return new Promise(function (resolve) {
                        if (window.__brandDeleteBrand) {
                            window.__brandDeleteBrand(item.id, item.name, item.row);
                        }
                        setTimeout(resolve, 450);
                    });
                });
            });
            chain.finally(function () {
                confirmBulkDelete.disabled = false;
                if (bulkDeleteModal) bulkDeleteModal.hide();
                updateBulkBar();
            });
        });
    }

    document.querySelectorAll('.bl-quick-view').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('blPrevName').textContent = btn.getAttribute('data-name') || '—';
            document.getElementById('blPrevSlug').textContent = btn.getAttribute('data-slug') || '—';
            document.getElementById('blPrevStatus').textContent = btn.getAttribute('data-status') || '—';
            const img = document.getElementById('blPrevLogo');
            if (img) {
                img.src = btn.getAttribute('data-logo') || '';
                img.alt = btn.getAttribute('data-name') || '';
            }
            const seo = document.getElementById('blSeoPreview');
            if (seo) {
                const slug = btn.getAttribute('data-slug') || '';
                const name = btn.getAttribute('data-name') || 'Brand';
                seo.querySelector('.g-url').textContent = '<?php echo rtrim(BASE_URL, '/'); ?>/brand/' + slug;
                seo.querySelector('.g-title').textContent = name + ' | Sivakamy';
                seo.querySelector('.g-desc').textContent = 'Shop ' + name + ' products at Sivakamy.';
            }
            bootstrap.Modal.getOrCreateInstance(document.getElementById('blPreviewModal')).show();
        });
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
