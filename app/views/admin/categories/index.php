<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/category-list.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<?php
$rows = !empty($categories['data']) && is_array($categories['data']) ? $categories['data'] : [];
$totalAll = (int)($categories['total'] ?? count($rows));
$activeCount = 0;
$inactiveCount = 0;
foreach ($rows as $c) {
    if ((int)($c['status'] ?? 0) === 1) $activeCount++;
    else $inactiveCount++;
}
// Featured / homepage / hidden are not in backend payload — derive display-only metrics safely
$featuredCount = 0;
$homepageCount = 0;
$hiddenCount = $inactiveCount;

$cur = (int)($categories['current_page'] ?? 1);
$last = (int)($categories['last_page'] ?? 1);
$perParam = $categories['per_page_param'] ?? '20';
$s = trim((string)($categories['search'] ?? ''));
$pageBase = BASE_URL . '?controller=category&action=adminIndex&per_page=' . urlencode((string)$perParam);
if ($s !== '') $pageBase .= '&search=' . urlencode($s);

// Build simple parent→children map for tree (current page)
$roots = [];
$childrenMap = [];
foreach ($rows as $c) {
    $pid = $c['parent_name'] ?? '';
    if ($pid === '' || $pid === null) {
        $roots[] = $c;
    } else {
        $childrenMap[$pid][] = $c;
    }
}
$latest = array_slice(array_reverse($rows), 0, 5);
?>

<div class="container-fluid cat-list-page" id="categoryAdminPage">
    <div class="cl-toast-host" id="clToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="cl-header">
        <div>
            <nav class="cl-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span>›</span>
                <span>Products</span>
                <span>›</span>
                <span aria-current="page">Categories</span>
            </nav>
            <h1 class="cl-title">Category Management</h1>
            <p class="cl-subtitle">Organize your catalog with hierarchy, tax rules, visibility, and storefront-ready structure.</p>
        </div>
        <div class="cl-actions">
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clRefreshBtn" title="Refresh">
                <i class="bi bi-arrow-clockwise"></i><span class="d-none d-md-inline">Refresh</span>
            </button>
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clPrintBtn" title="Print">
                <i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span>
            </button>
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clExportCsvBtn" title="Export Excel/CSV">
                <i class="bi bi-file-earmark-spreadsheet"></i><span class="d-none d-lg-inline">Excel</span>
            </button>
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clExportPdfBtn" title="Export PDF">
                <i class="bi bi-filetype-pdf"></i><span class="d-none d-lg-inline">PDF</span>
            </button>
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clImportBtn" title="Import">
                <i class="bi bi-upload"></i><span class="d-none d-xl-inline">Import</span>
            </button>
            <a href="<?php echo BASE_URL; ?>?controller=category&action=create" class="btn cl-btn cl-btn-primary">
                <i class="bi bi-plus-lg"></i><span>Add Category</span>
            </a>
        </div>
    </div>

    <!-- Summary cards -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="cl-stat s1">
                <div class="icon" aria-hidden="true"><i class="bi bi-folder2-open"></i></div>
                <div>
                    <div class="label">Total</div>
                    <div class="value" data-counter="<?php echo $totalAll; ?>">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="cl-stat s2">
                <div class="icon" aria-hidden="true"><i class="bi bi-check-circle"></i></div>
                <div>
                    <div class="label">Active</div>
                    <div class="value" data-counter="<?php echo $activeCount; ?>">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="cl-stat s3">
                <div class="icon" aria-hidden="true"><i class="bi bi-x-circle"></i></div>
                <div>
                    <div class="label">Inactive</div>
                    <div class="value" data-counter="<?php echo $inactiveCount; ?>">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="cl-stat s4">
                <div class="icon" aria-hidden="true"><i class="bi bi-star"></i></div>
                <div>
                    <div class="label">Featured</div>
                    <div class="value" data-counter="<?php echo $featuredCount; ?>">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="cl-stat s5">
                <div class="icon" aria-hidden="true"><i class="bi bi-house"></i></div>
                <div>
                    <div class="label">Homepage</div>
                    <div class="value" data-counter="<?php echo $homepageCount; ?>">0</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="cl-stat s6">
                <div class="icon" aria-hidden="true"><i class="bi bi-eye-slash"></i></div>
                <div>
                    <div class="label">Hidden</div>
                    <div class="value" data-counter="<?php echo $hiddenCount; ?>">0</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-9">
            <div class="cl-card">
                <div class="cl-toolbar">
                    <div class="flex-grow-1" style="min-width:260px;max-width:560px;">
                        <form method="GET" action="<?php echo BASE_URL; ?>" class="mb-0" id="categorySearchForm" role="search">
                            <input type="hidden" name="controller" value="category">
                            <input type="hidden" name="action" value="adminIndex">
                            <input type="hidden" name="per_page" value="<?php echo htmlspecialchars($categories['per_page_param'] ?? '20'); ?>">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search" aria-hidden="true"></i></span>
                                <input type="text" name="search" id="categorySearchInput" class="form-control" placeholder="Search name, parent, tax…" value="<?php echo htmlspecialchars($categories['search'] ?? ''); ?>" aria-label="Search categories" autocomplete="off">
                                <button type="submit" class="btn btn-outline-primary cl-btn">Search</button>
                                <?php if(!empty($categories['search'])): ?>
                                    <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex&per_page=<?php echo htmlspecialchars($categories['per_page_param'] ?? '20'); ?>" class="btn btn-outline-secondary cl-btn">Reset</a>
                                <?php else: ?>
                                    <button type="button" class="btn btn-outline-secondary cl-btn" id="clClientResetBtn">Reset</button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-secondary cl-btn" data-bs-toggle="offcanvas" data-bs-target="#clFilterDrawer" aria-controls="clFilterDrawer">
                            <i class="bi bi-funnel"></i><span>Filters</span>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary cl-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-layout-three-columns"></i><span class="d-none d-md-inline">Columns</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" id="clColumnMenu">
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 cl-col-toggle" data-col="tax" checked> Tax</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 cl-col-toggle" data-col="code" checked> Code</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 cl-col-toggle" data-col="order" checked> Order</label></li>
                            </ul>
                        </div>
                        <a href="<?php echo BASE_URL; ?>?controller=category&action=create" class="btn btn-sm cl-btn cl-btn-primary">
                            <i class="bi bi-plus-lg"></i><span>New</span>
                        </a>
                    </div>
                </div>

                <div class="cl-bulk-bar" id="clBulkBar" aria-live="polite">
                    <div class="small fw-semibold"><span id="clSelectedCount">0</span> selected</div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-success cl-btn" id="clBulkActivate">Activate</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary cl-btn" id="clBulkDeactivate">Deactivate</button>
                        <button type="button" class="btn btn-sm btn-outline-warning cl-btn" id="clBulkFeatured">Mark Featured</button>
                        <button type="button" class="btn btn-sm btn-outline-primary cl-btn" id="clBulkExport">Export Selected</button>
                        <button type="button" class="btn btn-sm btn-outline-danger cl-btn" id="clBulkDelete">Delete Selected</button>
                    </div>
                </div>

                <div class="card-body pt-2 px-0 pb-0">
                    <div id="alert-messages" class="px-3">
                        <?php flash('category_success'); ?>
                        <?php flash('category_error', '', 'alert alert-danger'); ?>
                    </div>

                    <?php if(empty($rows)): ?>
                        <div class="cl-empty">
                            <i class="bi bi-folder" aria-hidden="true"></i>
                            <?php if(!empty($categories['search'])): ?>
                                <div class="fw-semibold text-dark mb-1">No categories found for "<?php echo htmlspecialchars($categories['search']); ?>"</div>
                                <a href="<?php echo BASE_URL; ?>?controller=category&action=adminIndex&per_page=<?php echo htmlspecialchars($categories['per_page_param'] ?? '20'); ?>">Show all</a>
                            <?php else: ?>
                                <div class="fw-semibold mb-1">No categories found</div>
                                <a class="btn cl-btn cl-btn-primary mt-2" href="<?php echo BASE_URL; ?>?controller=category&action=create">Add Category</a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="table-topbar">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <label for="perPageFilter" class="form-label small text-muted mb-0">Rows</label>
                                <select id="perPageFilter" class="form-select form-select-sm" style="width: auto;" aria-label="Rows per page">
                                <?php 
                                $currentPerPage = $categories['per_page_param'] ?? '20';
                                $currentSearch = $categories['search'] ?? '';
                                $baseUrl = BASE_URL . '?controller=category&action=adminIndex';
                                foreach (['20', '50', '100', 'all'] as $opt): 
                                    $sel = ($currentPerPage === $opt) ? ' selected' : '';
                                    $url = $baseUrl . '&per_page=' . $opt;
                                    if ($currentSearch !== '') {
                                        $url .= '&search=' . urlencode($currentSearch);
                                    }
                                ?>
                                    <option value="<?php echo htmlspecialchars($url); ?>"<?php echo $sel; ?>><?php echo $opt === 'all' ? 'All' : $opt; ?></option>
                                <?php endforeach; ?>
                                </select>
                                <span class="small text-muted"><?php echo (int)($categories['total'] ?? 0); ?> total</span>
                                <input type="search" class="form-control form-control-sm" id="clLiveFilter" placeholder="Live filter this page…" style="max-width:200px" aria-label="Live filter">
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <a class="btn btn-sm btn-outline-secondary cl-btn <?php echo ($cur <= 1) ? 'disabled' : ''; ?>" href="<?php echo $pageBase . '&page=' . max(1, $cur - 1); ?>" aria-label="Previous page">Prev</a>
                                <span class="small text-muted">Page <?php echo $cur; ?> / <?php echo $last; ?></span>
                                <a class="btn btn-sm btn-outline-secondary cl-btn <?php echo ($cur >= $last) ? 'disabled' : ''; ?>" href="<?php echo $pageBase . '&page=' . min($last, $cur + 1); ?>" aria-label="Next page">Next</a>
                            </div>
                        </div>

                        <script>
                        document.getElementById('perPageFilter').addEventListener('change', function() {
                            window.location.href = this.value;
                        });
                        </script>

                        <div class="categories-table-scroll" role="region" aria-label="Categories table" tabindex="0">
                            <table id="categoriesTable" class="table categories-table" aria-describedby="categories-helptext">
                                <thead>
                                    <tr>
                                        <th scope="col">
                                            <input type="checkbox" class="form-check-input" id="clSelectAll" aria-label="Select all categories">
                                        </th>
                                        <th scope="col">#</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Name</th>
                                        <th scope="col" class="cl-col-code">Code</th>
                                        <th scope="col">Parent</th>
                                        <th scope="col" class="cl-col-tax">Tax Rate</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="cl-col-order">Order</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="categories-table-body">
                                    <?php 
                                    $catPage = isset($categories['current_page']) ? (int)$categories['current_page'] : 1;
                                    $catPerPage = isset($categories['per_page']) ? (int)$categories['per_page'] : 20;
                                    foreach($rows as $idx => $category): 
                                        $rowNum = ($catPage - 1) * $catPerPage + $idx + 1;
                                        $isActive = (int)($category['status'] ?? 0) === 1;
                                        $code = 'CAT-' . str_pad((string)(int)$category['id'], 4, '0', STR_PAD_LEFT);
                                        $parentLabel = !empty($category['parent_name']) ? $category['parent_name'] : 'None';
                                        $taxLabel = !empty($category['tax_name'])
                                            ? ($category['tax_name'] . ' (' . $category['tax_rate'] . '%)')
                                            : 'Not set';
                                    ?>
                                        <tr id="category-row-<?php echo $category['id']; ?>"
                                            data-name="<?php echo htmlspecialchars(strtolower($category['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>"
                                            data-parent="<?php echo htmlspecialchars(strtolower($parentLabel), ENT_QUOTES, 'UTF-8'); ?>"
                                            data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>"
                                            data-code="<?php echo htmlspecialchars(strtolower($code), ENT_QUOTES, 'UTF-8'); ?>">
                                            <td data-label="Select">
                                                <input type="checkbox" class="form-check-input cl-row-check" value="<?php echo (int)$category['id']; ?>" aria-label="Select <?php echo htmlspecialchars($category['name']); ?>">
                                            </td>
                                            <td data-label="#"><?php echo $rowNum; ?></td>
                                            <td data-label="Image">
                                                <?php 
                                                    $thumb = !empty($category['image']) 
                                                        ? (strpos($category['image'], 'uploads/') === 0 
                                                            ? BASE_URL . $category['image'] 
                                                            : BASE_URL . 'uploads/categories/' . $category['image']) 
                                                        : BASE_URL . 'assets/img/no-image.png';
                                                ?>
                                                <img src="<?php echo $thumb; ?>" alt="<?php echo htmlspecialchars($category['name']); ?>" class="cat-thumb" loading="lazy">
                                            </td>
                                            <td data-label="Name">
                                                <div class="cat-name"><?php echo htmlspecialchars($category['name']); ?></div>
                                                <div class="cat-meta">ID: <?php echo (int)$category['id']; ?></div>
                                            </td>
                                            <td data-label="Code" class="cl-col-code"><span class="cat-meta mb-0"><?php echo htmlspecialchars($code); ?></span></td>
                                            <td data-label="Parent">
                                                <?php if(!empty($category['parent_name'])): ?>
                                                    <?php echo htmlspecialchars($category['parent_name']); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">None</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Tax Rate" class="cl-col-tax">
                                                <?php if(!empty($category['tax_name'])): ?>
                                                    <?php echo htmlspecialchars($category['tax_name'] . ' (' . $category['tax_rate'] . '%)'); ?>
                                                <?php else: ?>
                                                    <span class="text-muted">Not set</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Status">
                                                <?php if($isActive): ?>
                                                    <span class="badge-status badge-status--active"><span style="width:6px;height:6px;border-radius:999px;background:#198754;display:inline-block;"></span>Active</span>
                                                <?php else: ?>
                                                    <span class="badge-status badge-status--inactive"><span style="width:6px;height:6px;border-radius:999px;background:#dc2626;display:inline-block;"></span>Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Order" class="cl-col-order"><span class="text-muted"><?php echo $rowNum; ?></span></td>
                                            <td data-label="Actions">
                                                <div class="d-inline-flex flex-wrap gap-1">
                                                    <a href="<?php echo BASE_URL; ?>?controller=category&action=edit&id=<?php echo $category['id']; ?>" class="btn btn-sm btn-outline-primary btn-action" aria-label="Edit category <?php echo htmlspecialchars($category['name']); ?>" title="Edit">
                                                        <i class="fas fa-pen"></i>
                                                        <span class="d-none d-xl-inline">Edit</span>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex&category=<?php echo (int)$category['id']; ?>" class="btn btn-sm btn-outline-secondary btn-icon" title="Products" aria-label="View products">
                                                        <i class="bi bi-box-seam"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-icon cl-quick-view" title="Preview"
                                                        data-name="<?php echo htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-parent="<?php echo htmlspecialchars($parentLabel, ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-tax="<?php echo htmlspecialchars($taxLabel, ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-status="<?php echo $isActive ? 'Active' : 'Inactive'; ?>"
                                                        data-thumb="<?php echo htmlspecialchars($thumb, ENT_QUOTES, 'UTF-8'); ?>"
                                                        aria-label="Preview category">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-action delete-category" data-id="<?php echo $category['id']; ?>" data-name="<?php echo htmlspecialchars($category['name']); ?>" aria-label="Delete category <?php echo htmlspecialchars($category['name']); ?>">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="d-none d-xl-inline">Delete</span>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div id="categories-helptext" class="visually-hidden">Use search to filter categories. Use edit and delete buttons in the actions column to manage a category.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-3 cl-side-col">
            <aside class="cl-card">
                <div class="cl-card-header">
                    <h3><i class="bi bi-bar-chart text-primary"></i> Quick Statistics</h3>
                </div>
                <div class="cl-card-body">
                    <ul class="cl-side-list">
                        <li><span class="k">Total</span><span class="v"><?php echo $totalAll; ?></span></li>
                        <li><span class="k">On this page</span><span class="v"><?php echo count($rows); ?></span></li>
                        <li><span class="k">Active (page)</span><span class="v"><?php echo $activeCount; ?></span></li>
                        <li><span class="k">Inactive (page)</span><span class="v"><?php echo $inactiveCount; ?></span></li>
                        <li><span class="k">Page</span><span class="v"><?php echo $cur; ?> / <?php echo max(1, $last); ?></span></li>
                    </ul>
                </div>
            </aside>

            <aside class="cl-card">
                <div class="cl-card-header">
                    <h3><i class="bi bi-clock-history text-primary"></i> Latest Categories</h3>
                </div>
                <div class="cl-card-body">
                    <?php if (empty($latest)): ?>
                        <div class="small text-muted">No categories yet.</div>
                    <?php else: ?>
                        <ul class="cl-side-list">
                            <?php foreach ($latest as $lc): ?>
                                <li>
                                    <span class="k"><?php echo htmlspecialchars($lc['name']); ?></span>
                                    <span class="v"><?php echo ((int)($lc['status'] ?? 0) === 1) ? 'Active' : 'Off'; ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </aside>

            <aside class="cl-card">
                <div class="cl-card-header">
                    <h3><i class="bi bi-diagram-3 text-primary"></i> Category Tree</h3>
                </div>
                <div class="cl-card-body cl-tree">
                    <?php if (empty($rows)): ?>
                        <div class="small text-muted">No tree data on this page.</div>
                    <?php else: ?>
                        <?php foreach ($roots as $root): ?>
                            <details open>
                                <summary><?php echo htmlspecialchars($root['name']); ?></summary>
                                <?php
                                $kids = $childrenMap[$root['name']] ?? [];
                                if (empty($kids)): ?>
                                    <div class="child text-muted">No children on this page</div>
                                <?php else:
                                    foreach ($kids as $kid): ?>
                                        <div class="child">↳ <?php echo htmlspecialchars($kid['name']); ?></div>
                                    <?php endforeach;
                                endif; ?>
                            </details>
                        <?php endforeach; ?>
                        <?php
                        // Show orphan children whose parent isn't on this page
                        foreach ($childrenMap as $pname => $kids) {
                            $parentOnPage = false;
                            foreach ($roots as $r) {
                                if ($r['name'] === $pname) { $parentOnPage = true; break; }
                            }
                            if ($parentOnPage) continue;
                            echo '<details open><summary class="text-muted">' . htmlspecialchars($pname) . ' (parent)</summary>';
                            foreach ($kids as $kid) {
                                echo '<div class="child">↳ ' . htmlspecialchars($kid['name']) . '</div>';
                            }
                            echo '</details>';
                        }
                        ?>
                    <?php endif; ?>
                </div>
            </aside>

            <aside class="cl-card">
                <div class="cl-card-header">
                    <h3><i class="bi bi-lightning text-primary"></i> Shortcuts</h3>
                </div>
                <div class="cl-card-body cl-shortcut">
                    <a href="<?php echo BASE_URL; ?>?controller=category&action=create"><i class="bi bi-plus-circle text-primary"></i> Add Category</a>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex"><i class="bi bi-box-seam text-primary"></i> Products</a>
                    <a href="<?php echo BASE_URL; ?>?controller=home&action=admin"><i class="bi bi-speedometer2 text-primary"></i> Dashboard</a>
                </div>
            </aside>
        </div>
    </div>
</div>

<!-- Filter Drawer -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="clFilterDrawer" aria-labelledby="clFilterDrawerLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="clFilterDrawerLabel">Filter Categories</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="mb-3">
            <label class="form-label fw-semibold" for="clFilterStatus">Status</label>
            <select id="clFilterStatus" class="form-select">
                <option value="">All</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold" for="clFilterParent">Parent Category</label>
            <input type="text" id="clFilterParent" class="form-control" placeholder="Parent name contains…">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold" for="clFilterCode">Category Code</label>
            <input type="text" id="clFilterCode" class="form-control" placeholder="e.g. CAT-0001">
        </div>
        <p class="small text-muted">Client-side filters apply to the current page. Use Search for server-wide results.</p>
        <div class="d-grid gap-2">
            <button type="button" class="btn cl-btn cl-btn-primary" id="clApplyFilters" data-bs-dismiss="offcanvas">Apply Filters</button>
            <button type="button" class="btn btn-outline-secondary cl-btn" id="clClearFilters">Clear</button>
        </div>
    </div>
</div>

<!-- Quick Preview Modal -->
<div class="modal fade" id="clPreviewModal" tabindex="-1" aria-labelledby="clPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="clPreviewModalLabel">Category Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img src="" alt="" id="clPrevThumb" class="rounded-4 border" style="max-width:160px;max-height:120px;object-fit:cover;">
                </div>
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2"><strong>Name:</strong> <span id="clPrevName">—</span></li>
                    <li class="mb-2"><strong>Parent:</strong> <span id="clPrevParent">—</span></li>
                    <li class="mb-2"><strong>Tax:</strong> <span id="clPrevTax">—</span></li>
                    <li><strong>Status:</strong> <span id="clPrevStatus">—</span></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation -->
<div class="modal fade" id="clBulkDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4">
            <div class="modal-header py-2">
                <h6 class="modal-title mb-0">Delete selected?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body small">This will permanently delete <strong id="clBulkDeleteCount">0</strong> categor(ies). This cannot be undone.</div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-danger" id="clConfirmBulkDelete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteCategoryModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the category "<span id="category-name"></span>"?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let categoryToDelete = null;
    const deleteButtons = document.querySelectorAll('.delete-category');
    const deleteModalEl = document.getElementById('deleteCategoryModal');
    const deleteModal = deleteModalEl ? new bootstrap.Modal(deleteModalEl) : null;
    const categoryNameSpan = document.getElementById('category-name');
    const confirmDeleteBtn = document.getElementById('confirm-delete');
    const alertMessages = document.getElementById('alert-messages');

    // Handle delete button click
    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = this.getAttribute('data-id');
            const categoryName = this.getAttribute('data-name');
            
            categoryToDelete = categoryId;
            if (categoryNameSpan) categoryNameSpan.textContent = categoryName;
            if (deleteModal) deleteModal.show();
        });
    });

    // Handle confirm delete
    if (confirmDeleteBtn) {
    confirmDeleteBtn.addEventListener('click', function() {
        if (!categoryToDelete) return;
        
        const button = this;
        const originalText = button.innerHTML;
        
        // Disable button and show loading state
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Deleting...';
        
        // Send AJAX request
        fetch(`<?php echo BASE_URL; ?>?controller=category&action=delete&id=${categoryToDelete}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from the table
                const row = document.getElementById(`category-row-${categoryToDelete}`);
                if (row) row.remove();
                
                // Show success message
                showAlert('Category deleted successfully', 'success');
                
                // If no more rows, show a message
                if (document.querySelectorAll('#categories-table-body tr').length === 0) {
                    const tbody = document.getElementById('categories-table-body');
                    if (tbody) tbody.innerHTML = '<tr><td colspan="10" class="text-center">No categories found.</td></tr>';
                }
                updateBulkBar();
            } else {
                showAlert(data.message || 'Failed to delete category', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while deleting the category', 'danger');
        })
        .finally(() => {
            // Reset button state
            button.disabled = false;
            button.innerHTML = originalText;
            // Hide modal
            if (deleteModal) deleteModal.hide();
            // Reset category to delete
            categoryToDelete = null;
        });
    });
    }
    
    // Function to show alert messages
    function showAlert(message, type = 'success') {
        if (!alertMessages) return;
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Clear previous alerts
        alertMessages.innerHTML = '';
        // Add new alert
        alertMessages.appendChild(alertDiv);
        
        // Auto-remove after 5 seconds
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
            if (alert) alert.close();
        }, 5000);
    }

    // ---- Enterprise UI helpers (do not alter CRUD contracts) ----
    function toast(type, title, msg) {
        const host = document.getElementById('clToastHost');
        if (!host) return;
        const el = document.createElement('div');
        el.className = 'cl-toast ' + type;
        el.innerHTML = '<strong>' + title + '</strong><div class="small text-muted">' + msg + '</div>';
        host.appendChild(el);
        setTimeout(function () { el.remove(); }, 4000);
    }

    // Animated counters
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

    // Selection / bulk bar
    const selectAll = document.getElementById('clSelectAll');
    const bulkBar = document.getElementById('clBulkBar');
    function selectedChecks() {
        return Array.prototype.slice.call(document.querySelectorAll('.cl-row-check:checked'));
    }
    function updateBulkBar() {
        const selected = selectedChecks();
        const countEl = document.getElementById('clSelectedCount');
        if (countEl) countEl.textContent = String(selected.length);
        if (bulkBar) bulkBar.classList.toggle('is-visible', selected.length > 0);
        document.querySelectorAll('#categories-table-body tr').forEach(function (tr) {
            const cb = tr.querySelector('.cl-row-check');
            tr.classList.toggle('is-selected', !!(cb && cb.checked));
        });
    }
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.cl-row-check').forEach(function (cb) {
                const tr = cb.closest('tr');
                if (tr && tr.style.display === 'none') return;
                cb.checked = selectAll.checked;
            });
            updateBulkBar();
        });
    }
    document.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('cl-row-check')) updateBulkBar();
    });

    // Live page filter
    function applyClientFilters() {
        const live = (document.getElementById('clLiveFilter') && document.getElementById('clLiveFilter').value || '').toLowerCase().trim();
        const st = (document.getElementById('clFilterStatus') && document.getElementById('clFilterStatus').value) || '';
        const parent = (document.getElementById('clFilterParent') && document.getElementById('clFilterParent').value || '').toLowerCase().trim();
        const code = (document.getElementById('clFilterCode') && document.getElementById('clFilterCode').value || '').toLowerCase().trim();
        document.querySelectorAll('#categories-table-body tr').forEach(function (tr) {
            if (!tr.getAttribute('data-name') && !tr.id) return;
            const hay = [
                tr.getAttribute('data-name') || '',
                tr.getAttribute('data-parent') || '',
                tr.getAttribute('data-code') || '',
                tr.getAttribute('data-status') || ''
            ].join(' ');
            let ok = true;
            if (live && hay.indexOf(live) === -1) ok = false;
            if (st && (tr.getAttribute('data-status') || '') !== st) ok = false;
            if (parent && (tr.getAttribute('data-parent') || '').indexOf(parent) === -1) ok = false;
            if (code && (tr.getAttribute('data-code') || '').indexOf(code) === -1) ok = false;
            tr.style.display = ok ? '' : 'none';
        });
    }
    const liveFilter = document.getElementById('clLiveFilter');
    if (liveFilter) liveFilter.addEventListener('input', applyClientFilters);
    const applyFilters = document.getElementById('clApplyFilters');
    if (applyFilters) applyFilters.addEventListener('click', applyClientFilters);
    const clearFilters = document.getElementById('clClearFilters');
    if (clearFilters) clearFilters.addEventListener('click', function () {
        ['clFilterStatus', 'clFilterParent', 'clFilterCode', 'clLiveFilter'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        applyClientFilters();
        toast('info', 'Filters cleared', 'Showing all rows on this page.');
    });
    const clientReset = document.getElementById('clClientResetBtn');
    if (clientReset) clientReset.addEventListener('click', function () {
        const input = document.getElementById('categorySearchInput');
        if (input) input.value = '';
        if (liveFilter) liveFilter.value = '';
        applyClientFilters();
    });

    // Column visibility
    document.querySelectorAll('.cl-col-toggle').forEach(function (cb) {
        cb.addEventListener('change', function () {
            const col = cb.getAttribute('data-col');
            const show = cb.checked;
            document.querySelectorAll('.cl-col-' + col).forEach(function (el) {
                el.style.display = show ? '' : 'none';
            });
        });
    });

    // Export CSV of visible rows
    function exportVisibleCsv(selectedOnly) {
        const rows = [];
        rows.push(['ID', 'Name', 'Code', 'Parent', 'Tax', 'Status']);
        document.querySelectorAll('#categories-table-body tr').forEach(function (tr) {
            if (tr.style.display === 'none') return;
            const cb = tr.querySelector('.cl-row-check');
            if (selectedOnly && (!cb || !cb.checked)) return;
            const id = cb ? cb.value : '';
            const name = (tr.querySelector('.cat-name') && tr.querySelector('.cat-name').textContent) || '';
            const code = tr.getAttribute('data-code') || '';
            const parent = tr.getAttribute('data-parent') || '';
            const taxTd = tr.querySelector('[data-label="Tax Rate"]');
            const tax = taxTd ? taxTd.textContent.trim() : '';
            const status = tr.getAttribute('data-status') || '';
            rows.push([id, name, code.toUpperCase(), parent, tax, status]);
        });
        const csv = rows.map(function (r) {
            return r.map(function (c) {
                const s = String(c).replace(/"/g, '""');
                return '"' + s + '"';
            }).join(',');
        }).join('\n');
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'categories-export.csv';
        a.click();
        URL.revokeObjectURL(a.href);
        toast('success', 'Export ready', 'CSV downloaded for visible/selected rows.');
    }

    const exportCsvBtn = document.getElementById('clExportCsvBtn');
    if (exportCsvBtn) exportCsvBtn.addEventListener('click', function () { exportVisibleCsv(false); });
    const bulkExport = document.getElementById('clBulkExport');
    if (bulkExport) bulkExport.addEventListener('click', function () { exportVisibleCsv(true); });

    const printBtn = document.getElementById('clPrintBtn');
    if (printBtn) printBtn.addEventListener('click', function () { window.print(); });

    const pdfBtn = document.getElementById('clExportPdfBtn');
    if (pdfBtn) pdfBtn.addEventListener('click', function () {
        toast('info', 'PDF', 'Use Print → Save as PDF for a printable export.');
        window.print();
    });

    const importBtn = document.getElementById('clImportBtn');
    if (importBtn) importBtn.addEventListener('click', function () {
        toast('warning', 'Import', 'CSV import is a UI shortcut. Use Add Category or ask for an import API to persist rows.');
    });

    const refreshBtn = document.getElementById('clRefreshBtn');
    if (refreshBtn) refreshBtn.addEventListener('click', function () { window.location.reload(); });

    // Bulk activate/deactivate/featured — UI-only badges (no backend status API on list)
    function bulkUiToast(action) {
        const n = selectedChecks().length;
        if (!n) { toast('warning', 'Nothing selected', 'Select one or more categories first.'); return; }
        toast('info', action, n + ' row(s) marked in the workspace. Status changes require Edit / backend support.');
    }
    const bulkActivate = document.getElementById('clBulkActivate');
    const bulkDeactivate = document.getElementById('clBulkDeactivate');
    const bulkFeatured = document.getElementById('clBulkFeatured');
    if (bulkActivate) bulkActivate.addEventListener('click', function () { bulkUiToast('Activate'); });
    if (bulkDeactivate) bulkDeactivate.addEventListener('click', function () { bulkUiToast('Deactivate'); });
    if (bulkFeatured) bulkFeatured.addEventListener('click', function () { bulkUiToast('Featured'); });

    // Bulk delete via existing delete AJAX
    const bulkDeleteBtn = document.getElementById('clBulkDelete');
    const bulkDeleteModalEl = document.getElementById('clBulkDeleteModal');
    const bulkDeleteModal = bulkDeleteModalEl ? new bootstrap.Modal(bulkDeleteModalEl) : null;
    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function () {
            const n = selectedChecks().length;
            if (!n) { toast('warning', 'Nothing selected', 'Select categories to delete.'); return; }
            const countEl = document.getElementById('clBulkDeleteCount');
            if (countEl) countEl.textContent = String(n);
            if (bulkDeleteModal) bulkDeleteModal.show();
        });
    }
    const confirmBulkDelete = document.getElementById('clConfirmBulkDelete');
    if (confirmBulkDelete) {
        confirmBulkDelete.addEventListener('click', function () {
            const ids = selectedChecks().map(function (cb) { return cb.value; });
            if (!ids.length) return;
            confirmBulkDelete.disabled = true;
            let chain = Promise.resolve();
            ids.forEach(function (id) {
                chain = chain.then(function () {
                    return fetch('<?php echo BASE_URL; ?>?controller=category&action=delete&id=' + encodeURIComponent(id), {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(function (r) { return r.json(); }).then(function (data) {
                        if (data && data.success) {
                            const row = document.getElementById('category-row-' + id);
                            if (row) row.remove();
                        } else {
                            showAlert((data && data.message) || ('Failed to delete #' + id), 'danger');
                        }
                    });
                });
            });
            chain.finally(function () {
                confirmBulkDelete.disabled = false;
                if (bulkDeleteModal) bulkDeleteModal.hide();
                updateBulkBar();
                showAlert('Bulk delete finished', 'success');
            });
        });
    }

    // Quick preview
    document.querySelectorAll('.cl-quick-view').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('clPrevName').textContent = btn.getAttribute('data-name') || '—';
            document.getElementById('clPrevParent').textContent = btn.getAttribute('data-parent') || '—';
            document.getElementById('clPrevTax').textContent = btn.getAttribute('data-tax') || '—';
            document.getElementById('clPrevStatus').textContent = btn.getAttribute('data-status') || '—';
            const img = document.getElementById('clPrevThumb');
            if (img) {
                img.src = btn.getAttribute('data-thumb') || '';
                img.alt = btn.getAttribute('data-name') || '';
            }
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('clPreviewModal'));
            modal.show();
        });
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
