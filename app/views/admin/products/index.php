<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Compact SaaS-like stat cards (kept light for readability across themes) */
.stat-card {
  background: #fff !important;
  border: 1px solid rgba(17, 24, 39, 0.10) !important;
  border-radius: 14px;
}
.stat-card .card-body {
  padding: 0.75rem 0.85rem;
  color: #111827 !important;
}
.stat-card .stat-label {
  font-size: 0.72rem;
  letter-spacing: .06em;
  text-transform: uppercase;
  font-weight: 700;
  color: rgba(17, 24, 39, 0.65) !important;
}
.stat-card .stat-value {
  font-size: 1.2rem;
  font-weight: 800;
  line-height: 1.1;
}
.stat-card .stat-subvalue {
  font-size: 0.85rem;
  font-weight: 700;
  color: rgba(17, 24, 39, 0.75) !important;
}
.stat-card .stat-icon {
  width: 36px;
  height: 36px;
  border-radius: 12px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: rgba(13, 110, 253, 0.12);
  color: #0d6efd;
}
.stat-card.stat-success .stat-icon {
  background: rgba(25, 135, 84, 0.12);
  color: #198754;
}
[data-theme="dark"] .stat-card,
[data-theme="dark"] .stat-card .card-body {
  background: #f8f9fa !important;
  border-color: rgba(17, 24, 39, 0.14) !important;
  color: #111827 !important;
}

/* Admin products – responsive & trending */
.products-table { font-size: 0.84rem; }
.products-table .admin-product-img { width: 36px; height: 36px; object-fit: cover; border-radius: 8px; }

.products-admin .page-tight { margin-bottom: 0.5rem !important; }
.products-admin .card-header { padding-top: 0.55rem !important; padding-bottom: 0.55rem !important; }

/* Table: alignment + ellipsis */
.products-table td,
.products-table th { vertical-align: middle; }
.products-table .text-ellipsis {
  max-width: 320px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.products-table .num { text-align: right; font-variant-numeric: tabular-nums; }

/* Status / expiry */
.products-table .badge { border-radius: 999px; }
.badge-expired { background: rgba(220, 53, 69, 0.12); color: #b02a37; border: 1px solid rgba(220, 53, 69, 0.25); }
.badge-active { background: rgba(25, 135, 84, 0.12); color: #146c43; border: 1px solid rgba(25, 135, 84, 0.25); }
.product-row-expired { background: rgba(220, 53, 69, 0.06); }
.product-row-expired:hover { background: rgba(220, 53, 69, 0.08); }

/* Stock indicator colors */
.badge-stock-in { background: rgba(25, 135, 84, 0.12); color: #146c43; border: 1px solid rgba(25, 135, 84, 0.25); }
.badge-stock-low { background: rgba(245, 158, 11, 0.14); color: #92400e; border: 1px solid rgba(245, 158, 11, 0.26); }
.badge-stock-out { background: rgba(220, 53, 69, 0.12); color: #b02a37; border: 1px solid rgba(220, 53, 69, 0.25); }

/* Sortable headers */
.th-sort {
  cursor: pointer;
  user-select: none;
}
.th-sort .sort-icon {
  margin-left: 6px;
  opacity: 0.55;
}
.th-sort.is-sorted .sort-icon { opacity: 0.9; }

/* Column toggle */
.col-toggle-menu {
  min-width: 220px;
  max-height: 340px;
  overflow: auto;
}

/* Quick preview */
.pm-preview {
  position: fixed;
  z-index: 2000;
  width: 320px;
  max-width: calc(100vw - 24px);
  background: #fff;
  border: 1px solid rgba(17, 24, 39, 0.10);
  border-radius: 14px;
  box-shadow: 0 18px 40px rgba(17, 24, 39, 0.14);
  display: none;
}
.pm-preview__body { padding: 12px; }
.pm-preview__title { font-weight: 800; font-size: 0.95rem; margin: 0; }
.pm-preview__meta { color: rgba(17,24,39,.62); font-size: 0.8rem; margin-top: 2px; }
.pm-preview__grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 10px; }
.pm-preview__k { color: rgba(17,24,39,.62); font-size: 0.72rem; }
.pm-preview__v { font-weight: 800; font-variant-numeric: tabular-nums; }
.pm-preview__img { width: 100%; height: 140px; object-fit: cover; border-radius: 12px; border: 1px solid rgba(17, 24, 39, 0.08); background: rgba(17,24,39,.02); }

/* Modern icon buttons */
.btn-icon {
  width: 32px;
  height: 32px;
  padding: 0;
  border-radius: 999px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}
.btn-icon i { font-size: 0.9rem; }
.btn-icon.btn-light { background: rgba(17, 24, 39, 0.04); border-color: rgba(17, 24, 39, 0.08); }
.btn-icon.btn-light:hover { background: rgba(17, 24, 39, 0.08); }
.btn-icon.btn-danger { background: rgba(220, 53, 69, 0.10); border-color: rgba(220, 53, 69, 0.16); color: #b02a37; }
.btn-icon.btn-danger:hover { background: rgba(220, 53, 69, 0.16); }
.btn-icon.btn-primary { background: rgba(13, 110, 253, 0.10); border-color: rgba(13, 110, 253, 0.16); color: #0b5ed7; }
.btn-icon.btn-primary:hover { background: rgba(13, 110, 253, 0.16); }
.btn-icon.btn-outline-secondary:hover { background: rgba(108, 117, 125, 0.12); }

/* Compact controls */
.products-toolbar .form-control,
.products-toolbar .custom-select,
.products-toolbar .btn {
  min-height: 32px;
  padding-top: 0.25rem;
  padding-bottom: 0.25rem;
}
.products-toolbar .btn { padding-left: 0.5rem; padding-right: 0.5rem; }
.products-toolbar .form-control,
.products-toolbar .custom-select { font-size: 0.85rem; }

.products-table-scroll {
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 10px;
  border: 1px solid rgba(0,0,0,.08);
  box-shadow: inset 0 1px 2px rgba(0,0,0,.04);
}
.products-table-scroll .table { margin-bottom: 0; border-radius: 12px; }
.products-table-scroll thead th {
  position: sticky;
  top: 0;
  z-index: 1;
  white-space: nowrap;
  font-weight: 600;
  font-size: 0.78rem;
  padding: 0.35rem 0.5rem;
  background: var(--bs-body-bg, #fff);
  color: var(--bs-body-color, #212529);
  box-shadow: 0 1px 0 0 var(--bs-border-color, #dee2e6);
}
.products-table-scroll tbody td {
  padding: 0.3rem 0.5rem;
  vertical-align: middle;
  line-height: 1.2;
}

/* Compact badges/buttons inside table */
.products-table .badge {
  padding: 0.25em 0.5em;
  font-size: 0.72rem;
  border-radius: 999px;
}
.products-table .btn-group .btn { line-height: 1; }

/* Compact pagination */
.products-table-scroll + .mt-3 { margin-top: 0.75rem !important; }
.products-table-scroll + .mt-3 .pagination .page-link { padding: 0.3rem 0.5rem; font-size: 0.82rem; }

/* Responsive height */
@media (max-width: 575.98px) {
  .products-table-scroll { max-height: 55vh; }
}
@media (min-width: 576px) and (max-width: 991.98px) {
  .products-table-scroll { max-height: 60vh; }
}
@media (min-width: 992px) {
  .products-table-scroll { max-height: 70vh; }
}

/* Tablet: hide less critical columns to reduce scroll */
@media (min-width: 576px) and (max-width: 991.98px) {
  #productsTable th:nth-child(6),
  #productsTable th:nth-child(7),
  #productsTable td:nth-child(6),
  #productsTable td:nth-child(7) { display: none !important; }
  #productsTable th:nth-child(13),
  #productsTable th:nth-child(14),
  #productsTable td:nth-child(13),
  #productsTable td:nth-child(14) { display: none !important; }
}

/* Mobile: card-style rows */
@media (max-width: 575.98px) {
  #productsTable thead { display: none; }
  #productsTable tbody tr {
    display: block;
    margin-bottom: 0.75rem;
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg, #fff);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  #productsTable tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.45rem 0.65rem;
    border-bottom: 1px solid rgba(0,0,0,.06);
  }
  #productsTable tbody td:last-child { border-bottom: 0; }
  #productsTable tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--bs-secondary, #6c757d);
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  #productsTable tbody td[data-label="Image"] {
    display: flex;
    justify-content: flex-start;
    gap: 0.5rem;
  }
  #productsTable tbody td[data-label="Image"]::before { content: none; }
  #productsTable tbody td[data-label="Image"] img.admin-product-img {
    width: 40px !important;
    height: 40px !important;
    object-fit: cover;
    border-radius: 10px;
  }
  #productsTable tbody td[data-label="Actions"] { flex-wrap: wrap; gap: 0.25rem; }
  #productsTable tbody td[data-label="Actions"] .btn-group { width: 100%; justify-content: flex-end; }
}

/* Pagination responsive */
.products-table-scroll + .mt-3 .pagination { flex-wrap: wrap; gap: 0.25rem; }
.products-table-scroll + .mt-3 .pagination .page-link { padding: 0.4rem 0.6rem; font-size: 0.875rem; }

/* Fix overflow - search and filters don't take excessive space */
.products-admin .input-group { max-width: 100%; }
.products-admin .form-control { max-width: 100%; }
.products-admin .card-body { overflow-x: hidden; }
.products-admin .row.g-3 { overflow: hidden; }
.modal .close { padding: 0.5rem 1rem; font-size: 1.5rem; opacity: 1; }

/* Force dark text on white/light backgrounds (fix invisible text in dark mode) */
.products-admin .btn-light,
.products-admin .btn-light i { color: #212529 !important; }
.products-admin .form-control,
.products-admin .input-group-text { color: #212529 !important; background-color: #fff !important; }
.products-admin .form-control::placeholder { color: #6c757d !important; }
[data-theme="dark"] .products-admin .btn-light,
[data-theme="dark"] .products-admin .btn-light i { color: #212529 !important; background-color: #f8f9fa !important; }
[data-theme="dark"] .products-admin .form-control,
[data-theme="dark"] .products-admin .input-group-text { color: #212529 !important; background-color: #fff !important; border-color: #dee2e6; }
[data-theme="dark"] .products-admin .form-control::placeholder { color: #6c757d !important; }

/* Table top bar */
.table-topbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.5rem;
  flex-wrap: wrap;
  padding: 0.35rem 0.25rem 0.5rem;
}
.table-topbar .form-control,
.table-topbar .btn { height: 32px; padding-top: 0.25rem; padding-bottom: 0.25rem; }
.table-topbar .form-control { font-size: 0.85rem; }
.table-topbar .table-meta { font-size: 0.8rem; color: rgba(17,24,39,.60); }

/* Inline editing */
.cell-editable { cursor: text; position: relative; }
.cell-editable:focus { outline: none; }
.cell-editable.cell-editing { background: rgba(13, 110, 253, 0.08); }
.cell-editable.cell-saving { opacity: 0.75; }
.cell-editable.cell-saved { animation: cellSavedFlash 600ms ease; }
.cell-editable.cell-error { background: rgba(220, 53, 69, 0.10); }
.cell-editable .cell-status {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 16px;
  height: 16px;
  margin-left: 6px;
  color: rgba(17,24,39,.55);
}
.cell-editable .cell-status .spinner-border { width: 14px; height: 14px; }
.cell-editable .cell-status .cell-check { font-size: 0.85rem; color: #198754; }
@keyframes cellSavedFlash {
  0% { background: rgba(25, 135, 84, 0.16); }
  100% { background: transparent; }
}
.inline-editor {
  width: 100%;
  font-size: 0.84rem;
  padding: 0.15rem 0.35rem;
  height: 28px;
}
</style>

<div class="container-fluid px-2 px-sm-3 products-admin">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="card-header bg-primary text-white d-flex flex-column flex-sm-row justify-content-between align-items-stretch align-items-sm-center gap-2">
                    <h3 class="card-title mb-0 h5 mb-0">Products</h3>
                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=export" class="btn btn-light btn-sm">
                            <i class="fas fa-download mr-1"></i> Export CSV
                        </a>
                        <button type="button" class="btn btn-light btn-sm" data-toggle="modal" data-target="#importModal">
                            <i class="fas fa-upload mr-1"></i> Import CSV
                        </button>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=create" class="btn btn-light btn-sm">
                            <i class="fas fa-plus mr-1"></i> Add New Product
                        </a>
                    </div>

<datalist id="supplierDatalist">
    <?php if (!empty($suppliers) && is_array($suppliers)): ?>
        <?php foreach ($suppliers as $s): ?>
            <?php if (!empty($s['name'])): ?>
                <option value="<?php echo htmlspecialchars((string)$s['name']); ?>"></option>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</datalist>
                </div>
                <div class="card-body p-2 p-md-3">
                    <div class="row g-2 page-tight">
                        <div class="col-12 col-md-6">
                            <div class="card border-0 shadow-sm h-100 stat-card">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-label mb-1">Products</div>
                                        <div class="stat-value text-primary">
                                            <?php 
                                            $totalProducts = 0;
                                            if (isset($products['total_records'])) {
                                                $totalProducts = $products['total_records'];
                                            } elseif (isset($products['total'])) {
                                                $totalProducts = $products['total'];
                                            } elseif (isset($products['data']) && is_array($products['data'])) {
                                                $totalProducts = count($products['data']);
                                            }
                                            echo number_format($totalProducts);
                                            ?>
                                        </div>
                                        <div class="stat-subvalue">Total products</div>
                                    </div>
                                    <span class="stat-icon" aria-hidden="true"><i class="fas fa-boxes"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="card border-0 shadow-sm h-100 stat-card stat-success">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="stat-label mb-1">Stock</div>
                                        <div class="stat-value text-success">
                                            <?php 
                                            $totalStock = 0;
                                            $totalStockValue = 0;
                                            if (isset($products['data']) && is_array($products['data'])) {
                                                foreach ($products['data'] as $product) {
                                                    $quantity = (int)($product['stock_quantity'] ?? 0);
                                                    $price = (float)($product['price'] ?? 0);
                                                    $totalStock += $quantity;
                                                    $totalStockValue += ($quantity * $price);
                                                }
                                            }
                                            echo number_format($totalStock);
                                            ?>
                                        </div>
                                        <div class="stat-subvalue">Value: <?php echo formatPrice($totalStockValue); ?></div>
                                    </div>
                                    <span class="stat-icon" aria-hidden="true"><i class="fas fa-money-bill-wave"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="alert-messages">
                        <?php flash('product_success', '', 'alert alert-success'); ?>
                        <?php flash('product_error', '', 'alert alert-danger'); ?>
                    </div>

                    <?php
                        $categoryOptions = [];
                        if (isset($products['data']) && is_array($products['data'])) {
                            foreach ($products['data'] as $p) {
                                $catName = trim((string)($p['category_name'] ?? ''));
                                if ($catName === '') {
                                    $catName = 'Uncategorized';
                                }
                                $categoryOptions[$catName] = true;
                            }
                        }
                        $categoryOptions = array_keys($categoryOptions);
                        sort($categoryOptions, SORT_NATURAL | SORT_FLAG_CASE);
                    ?>

                    <?php if(empty($products['data'])): ?>
                        <div class="alert alert-info">No products found.</div>
                    <?php else: ?>
                        <div class="table-topbar">
                            <div class="d-flex flex-wrap align-items-center" style="gap: 0.5rem;">
                                <?php if (!empty($categoryOptions)): ?>
                                    <label for="categoryFilterSelect" class="small text-muted mb-0">Category</label>
                                    <select id="categoryFilterSelect" class="form-control form-control-sm" style="width: auto; min-width: 220px;">
                                        <option value="">All categories</option>
                                        <?php foreach ($categoryOptions as $cat): ?>
                                            <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn btn-sm btn-light" id="clearCategoryFilter">Clear</button>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex flex-wrap align-items-center" style="gap: 0.5rem;">
                                <div class="input-group input-group-sm" style="width: min(420px, 100%);">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input type="search" id="tableSearch" class="form-control" placeholder="Search product, SKU, supplier..." aria-label="Search products">
                                    <div class="input-group-append">
                                        <button class="btn btn-light" type="button" id="clearTableSearch" aria-label="Clear search"><i class="fas fa-times"></i></button>
                                    </div>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="colToggleBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-columns mr-1"></i> Columns
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right col-toggle-menu" aria-labelledby="colToggleBtn" id="colToggleMenu"></div>
                                </div>

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="exportBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-download mr-1"></i> Export
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="exportBtn">
                                        <a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=product&action=export"><i class="fas fa-file-csv mr-2"></i>CSV</a>
                                        <button class="dropdown-item" type="button" id="exportExcel"><i class="fas fa-file-excel mr-2"></i>Excel</button>
                                        <button class="dropdown-item" type="button" id="exportPrint"><i class="fas fa-print mr-2"></i>Print</button>
                                    </div>
                                </div>

                                <div class="table-meta d-none d-lg-block"><?php echo count($products['data']); ?> shown</div>
                                <label for="perPageFilter" class="small text-muted mb-0">Show</label>
                                <select id="perPageFilter" class="form-control form-control-sm" style="width: auto; max-width: 110px;">
                                    <?php 
                                    $currentPerPage = $products['per_page_param'] ?? '20';
                                    $currentSearch = $products['search'] ?? '';
                                    $baseUrl = BASE_URL . '?controller=product&action=adminIndex';
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
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($products['data'])): ?>
                        <div class="products-table-scroll table-responsive">
                            <table id="productsTable" class="table table-striped table-hover products-table">
                                <thead>
                                    <tr>
                                        <th data-col="#" class="th-sort" data-sort-key="rownum"># <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="Img">Img</th>
                                        <th data-col="Name" class="th-sort" data-sort-key="name">Name <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="Cat" class="th-sort" data-sort-key="category">Cat <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="SKU" class="th-sort" data-sort-key="sku">SKU <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="Batch" class="th-sort" data-sort-key="batch">Batch <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="Sup" class="th-sort" data-sort-key="supplier">Sup <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="Exp" class="th-sort" data-sort-key="expiry">Exp <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="BP" class="th-sort" data-sort-key="bp">BP <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="Tax">Tax</th>
                                        <th data-col="SP" class="th-sort" data-sort-key="sp">SP <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="WSP" class="th-sort" data-sort-key="wsp">WSP <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="CC" class="th-sort" data-sort-key="cc">CC <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="TC" class="th-sort" data-sort-key="tc">TC <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="Qty" class="th-sort" data-sort-key="qty">Qty <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="Val" class="th-sort" data-sort-key="val">Val <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="St" class="th-sort" data-sort-key="status">St <i class="fas fa-sort sort-icon"></i></th>
                                        <th data-col="Act">Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $page = isset($products['current_page']) ? (int)$products['current_page'] : 1;
                                    $perPage = isset($products['per_page']) ? (int)$products['per_page'] : 15;
                                    foreach($products['data'] as $idx => $product): 
                                        $rowNum = ($page - 1) * $perPage + $idx + 1;
                                    ?>
                                        <?php
                                            $rowCategoryName = trim((string)($product['category_name'] ?? ''));
                                            if ($rowCategoryName === '') {
                                                $rowCategoryName = 'Uncategorized';
                                            }

                                            $supplierName = '';
                                            if (!empty($product['supplier'])) {
                                                $supplierName = $product['supplier'];
                                            } elseif (!empty($product['supplier_id']) && !empty($supplierMap) && isset($supplierMap[$product['supplier_id']])) {
                                                $supplierName = $supplierMap[$product['supplier_id']];
                                            }

                                            $isExpired = false;
                                            if (!empty($product['expiry_date'])) {
                                                try { $isExpired = (strtotime($product['expiry_date']) < time()); } catch (Exception $e) { $isExpired = false; }
                                            }
                                        ?>
                                        <tr id="product-row-<?php echo $product['id']; ?>"
                                            data-category="<?php echo htmlspecialchars($rowCategoryName); ?>"
                                            data-preview-name="<?php echo htmlspecialchars((string)($product['name'] ?? '')); ?>"
                                            data-preview-sku="<?php echo htmlspecialchars((string)($product['sku'] ?? '')); ?>"
                                            data-preview-cat="<?php echo htmlspecialchars($rowCategoryName); ?>"
                                            data-preview-sup="<?php echo htmlspecialchars((string)$supplierName); ?>"
                                            data-preview-exp="<?php echo htmlspecialchars((string)($product['expiry_date'] ?? '')); ?>"
                                            data-preview-bp="<?php echo htmlspecialchars((string)($product['price'] ?? '')); ?>"
                                            data-preview-sp="<?php echo htmlspecialchars((string)($product['price2'] ?? '')); ?>"
                                            data-preview-qty="<?php echo htmlspecialchars((string)($product['stock_quantity'] ?? 0)); ?>"
                                            data-preview-img="<?php echo htmlspecialchars(!empty($product['image']) ? (BASE_URL . $product['image']) : (BASE_URL . 'assets/img/no-image.jpg')); ?>"
                                            class="<?php echo $isExpired ? 'product-row-expired' : ''; ?>">
                                            <td data-label="#"><?php echo $rowNum; ?></td>
                                            <td data-label="Img">
                                                <?php if(!empty($product['image'])): ?>
                                                    <img src="<?php echo BASE_URL . $product['image']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-thumbnail admin-product-img">
                                                <?php else: ?>
                                                    <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" alt="No Image" class="img-thumbnail admin-product-img">
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Name"><span class="text-ellipsis d-inline-block" title="<?php echo htmlspecialchars($product['name']); ?>"><?php echo htmlspecialchars($product['name']); ?></span></td>
                                            <td data-label="Cat" class="cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="category_id" data-type="select" data-value="<?php echo htmlspecialchars((string)($product['category_id'] ?? '')); ?>"><span class="text-ellipsis d-inline-block" title="<?php echo htmlspecialchars($rowCategoryName); ?>"><?php echo htmlspecialchars($rowCategoryName); ?></span></td>
                                            <td data-label="SKU" class="cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="sku" data-type="text" data-value="<?php echo htmlspecialchars((string)($product['sku'] ?? '')); ?>"><span class="text-ellipsis d-inline-block" title="<?php echo htmlspecialchars($product['sku']); ?>"><?php echo htmlspecialchars($product['sku']); ?></span></td>
                                            <td data-label="Batch"><?php echo !empty($product['batch_number']) ? htmlspecialchars($product['batch_number']) : '<span class="text-muted">-</span>'; ?></td>
                                            <td data-label="Sup" class="cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="supplier" data-type="datalist" data-value="<?php echo htmlspecialchars((string)$supplierName); ?>">
                                                <?php echo $supplierName !== '' ? htmlspecialchars($supplierName) : '<span class="text-muted">-</span>'; ?>
                                            </td>
                                            <td data-label="Exp">
                                                <?php if(!empty($product['expiry_date'])): ?>
                                                    <span class="text-nowrap cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="expiry_date" data-type="date" data-value="<?php echo htmlspecialchars((string)($product['expiry_date'] ?? '')); ?>"><?php echo htmlspecialchars($product['expiry_date']); ?></span>
                                                    <?php if($isExpired): ?><span class="badge badge-expired ml-1">Expired</span><?php endif; ?>
                                                <?php else: ?>
                                                    <span class="cell-editable text-muted" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="expiry_date" data-type="date" data-value="">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="BP" class="num cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="price" data-type="number" data-value="<?php echo htmlspecialchars((string)($product['price'] ?? '')); ?>"><?php echo formatPrice($product['price']); ?></td>
                                            <td data-label="Tax">
                                                <?php if(!empty($product['sale_price'])): ?>
                                                    <span class="num d-block cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="sale_price" data-type="number_nullable" data-value="<?php echo htmlspecialchars((string)($product['sale_price'] ?? '')); ?>"><?php echo formatPrice($product['sale_price']); ?></span>
                                                <?php else: ?>
                                                    <span class="cell-editable text-muted" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="sale_price" data-type="number_nullable" data-value="">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="SP">
                                                <?php if(!empty($product['price2'])): ?>
                                                    <span class="num d-block cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="price2" data-type="number_nullable" data-value="<?php echo htmlspecialchars((string)($product['price2'] ?? '')); ?>"><?php echo formatPrice($product['price2']); ?></span>
                                                <?php else: ?>
                                                    <span class="cell-editable text-muted" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="price2" data-type="number_nullable" data-value="">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="WSP">
                                                <?php if(!empty($product['price3'])): ?>
                                                    <span class="num d-block cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="price3" data-type="number_nullable" data-value="<?php echo htmlspecialchars((string)($product['price3'] ?? '')); ?>"><?php echo formatPrice($product['price3']); ?></span>
                                                <?php else: ?>
                                                    <span class="cell-editable text-muted" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="price3" data-type="number_nullable" data-value="">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="CC">
                                                <?php if(!empty($product['customs_charge'])): ?>
                                                    <span class="num d-block cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="customs_charge" data-type="number_nullable" data-value="<?php echo htmlspecialchars((string)($product['customs_charge'] ?? '')); ?>"><?php echo formatPrice($product['customs_charge']); ?></span>
                                                <?php else: ?>
                                                    <span class="cell-editable text-muted" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="customs_charge" data-type="number_nullable" data-value="">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="TC">
                                                <?php if(!empty($product['transport_charge'])): ?>
                                                    <span class="num d-block cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="transport_charge" data-type="number_nullable" data-value="<?php echo htmlspecialchars((string)($product['transport_charge'] ?? '')); ?>"><?php echo formatPrice($product['transport_charge']); ?></span>
                                                <?php else: ?>
                                                    <span class="cell-editable text-muted" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="transport_charge" data-type="number_nullable" data-value="">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Qty">
                                                <?php $qty = (float)($product['stock_quantity'] ?? 0); ?>
                                                <span class="badge <?php echo ($qty <= 0) ? 'badge-stock-out' : (($qty <= 5) ? 'badge-stock-low' : 'badge-stock-in'); ?> cell-editable" tabindex="0" data-editable="1" data-id="<?php echo (int)$product['id']; ?>" data-field="stock_quantity" data-type="number" data-value="<?php echo htmlspecialchars((string)($product['stock_quantity'] ?? 0)); ?>" id="stock-badge-<?php echo $product['id']; ?>"><?php echo $product['stock_quantity'] ?? 0; ?></span>
                                            </td>
                                            <td class="text-nowrap num" data-label="Val">
                                                <?php 
                                                $stockValue = (float)$product['stock_quantity'] * (float)$product['price'];
                                                echo formatPrice($stockValue);
                                                ?>
                                            </td>
                                            <td data-label="St">
                                                <?php $isActive = (strtolower((string)($product['status'] ?? '')) === 'active'); ?>
                                                <span class="badge <?php echo $isActive ? 'badge-active' : 'badge badge-secondary'; ?>">
                                                    <?php echo ucfirst($product['status']); ?>
                                                </span>
                                            </td>
                                            <td data-label="Act">
                                                <div class="btn-group" role="group" aria-label="Actions">
                                                    <button type="button"
                                                            class="btn btn-sm btn-light btn-icon btn-history mr-1"
                                                            data-product-id="<?php echo $product['id']; ?>"
                                                            data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                                                            data-toggle="tooltip" title="History">
                                                        <i class="fas fa-history"></i>
                                                    </button>
                                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo $product['id']; ?>"
                                                       class="btn btn-sm btn-light btn-icon mr-1"
                                                       data-toggle="tooltip" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button"
                                                            class="btn btn-sm btn-outline-secondary btn-icon btn-stock-options mr-1"
                                                            data-product-id="<?php echo $product['id']; ?>"
                                                            data-product-name="<?php echo htmlspecialchars($product['name']); ?>"
                                                            data-current-stock="<?php echo htmlspecialchars((string)($product['stock_quantity'] ?? 0)); ?>"
                                                            data-toggle="tooltip" title="Stock options">
                                                        <i class="fas fa-ellipsis-h"></i>
                                                    </button>
                                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=edit&id=<?php echo $product['id']; ?>" 
                                                       class="btn btn-sm btn-primary btn-icon mr-1" 
                                                       data-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-pen"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-danger btn-icon delete-product" 
                                                            data-id="<?php echo $product['id']; ?>"
                                                            data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                                            data-toggle="tooltip" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <script>
                        document.getElementById('perPageFilter').addEventListener('change', function() {
                            window.location.href = this.value;
                        });
                        </script>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="historyModalLabel">Stock History</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div id="historyContent" class="py-2">
                    <div class="text-center text-muted">Loading...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
    
</div>

<!-- Stock Options / Add Stock Modal -->
<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title" id="stockModalLabel">Update Stock</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="stockProductId" value="">
                <p class="mb-2">Product: <strong id="stockProductName"></strong></p>
                <p class="mb-3 text-muted small">Current stock: <span id="stockCurrent"></span></p>

                <label for="stockAddQty" class="form-label">Add Stock Quantity</label>
                <input type="text" class="form-control" id="stockAddQty" inputmode="numeric" autocomplete="off" placeholder="Enter quantity to add">
                <div class="form-text">This will increase stock by the entered amount.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAddStock">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Save</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong id="productName"></strong>?</p>
                <p class="text-danger">This action cannot be undone.</p>
                <input type="hidden" id="productId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="btn-text">Delete</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Import CSV Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo BASE_URL; ?>?controller=product&action=import" method="POST" enctype="multipart/form-data">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="importModalLabel"><i class="fas fa-upload mr-2"></i>Import Products from CSV</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="import_file" class="form-label">CSV File</label>
                        <input type="file" class="form-control" id="import_file" name="import_file" accept=".csv" required>
                        <div class="form-text">Upload a CSV with columns: name, description, sku, price, sale_price, price2, price3, stock_quantity, category_id, brand_id, country_id, supplier, batch_number, status, add_date, expiry_date, tax_id. Use Export to download a sample.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-upload mr-1"></i>Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tooltips (Bootstrap 4)
    try {
        $('[data-toggle="tooltip"]').tooltip({ container: 'body' });
    } catch (e) {
        // ignore
    }

    // History modal elements
    const historyModalEl = document.getElementById('historyModal');
    const historyContent = document.getElementById('historyContent');
    const historyTitle = document.getElementById('historyModalLabel');

    function normalizeCategoryValue(value) {
        let s = (value ?? '').toString();
        s = s.replace(/\s+/g, ' ').trim().toLowerCase();
        try {
            if (typeof s.normalize === 'function') {
                s = s.normalize('NFKC');
            }
        } catch (e) {
            // ignore
        }
        return s;
    }

    function applyCategoryFilterSelect() {
        const select = document.getElementById('categoryFilterSelect');
        const rows = document.querySelectorAll('#productsTable tbody tr');
        if (!select || !rows) return;
        const selected = normalizeCategoryValue(select.value || '');
        if (selected === '') {
            rows.forEach(r => { r.style.display = ''; });
            return;
        }
        rows.forEach(r => {
            const cat = normalizeCategoryValue(r.getAttribute('data-category') || '');
            r.style.display = (cat === selected) ? '' : 'none';
        });
    }

    function isActionElement(el) {
        if (!el) return false;
        return !!el.closest('.btn-history, .btn-stock-options, .delete-product, a[href*="controller=product&action=edit"]');
    }

    // If an action is clicked while editing, save first then execute action
    document.addEventListener('mousedown', function(e) {
        if (replayingActionClick) return;
        const actionEl = e.target.closest('.btn-history, .btn-stock-options, .delete-product, a[href*="controller=product&action=edit"]');
        if (!actionEl) return;
        if (!(activeCell && activeCell.classList.contains('cell-editing'))) return;

        // Prevent focus from moving away and breaking cursor state
        e.preventDefault();
        e.stopPropagation();

        commitEdit(activeCell, function() {
            replayingActionClick = true;
            setTimeout(function() {
                try {
                    if (actionEl.tagName && actionEl.tagName.toLowerCase() === 'a' && actionEl.href) {
                        window.location.href = actionEl.href;
                    } else {
                        actionEl.click();
                    }
                } finally {
                    replayingActionClick = false;
                }
            }, 0);
        });
    }, true);

    const categorySelect = document.getElementById('categoryFilterSelect');
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            applyCategoryFilterSelect();
        });
    }

    const clearSelectBtn = document.getElementById('clearCategoryFilter');
    if (clearSelectBtn) {
        clearSelectBtn.addEventListener('click', function() {
            if (categorySelect) categorySelect.value = '';
            applyCategoryFilterSelect();
        });
    }

    // Stock modal elements
    const stockModalEl = document.getElementById('stockModal');
    const stockProductIdEl = document.getElementById('stockProductId');
    const stockProductNameEl = document.getElementById('stockProductName');
    const stockCurrentEl = document.getElementById('stockCurrent');
    const stockAddQtyEl = document.getElementById('stockAddQty');
    const confirmAddStockBtn = document.getElementById('confirmAddStock');

    function loadHistory(productId, page = 1) {
        if (!historyContent) return;
        historyContent.innerHTML = '<div class="text-center text-muted">Loading...</div>';
        const url = `?controller=stock&action=history&id=${encodeURIComponent(productId)}&page=${page}&partial=1`;
        fetch(url, { credentials: 'same-origin' })
            .then(r => {
                if (!r.ok) throw new Error('Failed to load history');
                return r.text();
            })
            .then(html => {
                historyContent.innerHTML = html;
            })
            .catch(err => {
                historyContent.innerHTML = `<div class="alert alert-danger mb-0">${err.message}</div>`;
            });
    }

    // Open history modal on button click
        $(document).on('click', '.btn-history', function() {
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name') || '';
            if (historyTitle) historyTitle.textContent = 'Stock History - ' + productName;
            $('#historyModal').modal('show');
            loadHistory(productId, 1);
        });

    // Open stock options modal
    $(document).on('click', '.btn-stock-options', function() {
        const productId = this.getAttribute('data-product-id');
        const productName = this.getAttribute('data-product-name') || '';
        const currentStock = this.getAttribute('data-current-stock') || '0';

        if (stockProductIdEl) stockProductIdEl.value = productId;
        if (stockProductNameEl) stockProductNameEl.textContent = productName;
        if (stockCurrentEl) stockCurrentEl.textContent = currentStock;
        if (stockAddQtyEl) stockAddQtyEl.value = '';

        if (confirmAddStockBtn) {
            const spinner = confirmAddStockBtn.querySelector('.spinner-border');
            const btnText = confirmAddStockBtn.querySelector('.btn-text');
            if (spinner) spinner.classList.add('d-none');
            if (btnText) btnText.textContent = 'Save';
            confirmAddStockBtn.disabled = false;
        }

        $('#stockModal').modal('show');
    });

    // Delegate pagination clicks inside history modal
    if (historyModalEl) {
        historyModalEl.addEventListener('click', function(e) {
            const link = e.target.closest('a.history-page-link');
            if (link) {
                e.preventDefault();
                const page = parseInt(link.getAttribute('data-page') || '1', 10);
                // Extract current product id from existing links
                const href = new URL(link.getAttribute('href'), window.location.href);
                const productId = href.searchParams.get('id');
                if (productId) loadHistory(productId, page);
            }
        });
    }

    // Delete modal elements
    const productNameEl = document.getElementById('productName');
    const productIdEl = document.getElementById('productId');
    const confirmDeleteBtn = document.getElementById('confirmDelete');
    const alertMessages = document.getElementById('alert-messages');

    // Confirm add stock
    if (confirmAddStockBtn) {
        confirmAddStockBtn.addEventListener('click', function() {
            const productId = stockProductIdEl ? stockProductIdEl.value : '';
            const addQty = stockAddQtyEl ? (stockAddQtyEl.value || '').trim() : '';
            if (!productId || !addQty) return;

            const spinner = this.querySelector('.spinner-border');
            const btnText = this.querySelector('.btn-text');
            if (spinner) spinner.classList.remove('d-none');
            if (btnText) btnText.textContent = 'Saving...';
            this.disabled = true;

            fetch(`?controller=product&action=addStock&id=${encodeURIComponent(productId)}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify({ addQty })
            })
            .then(r => {
                if (!r.ok) throw new Error('Network response was not ok');
                return r.json();
            })
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Failed to update stock');

                const newQty = data.stock_quantity;
                const badge = document.getElementById(`stock-badge-${productId}`);
                if (badge) {
                    badge.textContent = newQty;
                    badge.classList.remove('badge-stock-in', 'badge-stock-low', 'badge-stock-out');
                    if (parseFloat(newQty) <= 0) {
                        badge.classList.add('badge-stock-out');
                    } else if (parseFloat(newQty) <= 5) {
                        badge.classList.add('badge-stock-low');
                    } else {
                        badge.classList.add('badge-stock-in');
                    }
                }

                const optBtn = document.querySelector(`.btn-stock-options[data-product-id="${productId}"]`);
                if (optBtn) {
                    optBtn.setAttribute('data-current-stock', String(newQty));
                }

                showAlert(data.message || 'Stock updated successfully', 'success');
            })
            .catch(err => {
                showAlert(err.message || 'Failed to update stock', 'danger');
            })
            .finally(() => {
                $('#stockModal').modal('hide');
                if (spinner) spinner.classList.add('d-none');
                if (btnText) btnText.textContent = 'Save';
                confirmAddStockBtn.disabled = false;
            });
        });
    }
    
    // Handle delete button click
    document.querySelectorAll('.delete-product').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const productName = this.getAttribute('data-name');
            
            productIdEl.value = productId;
            productNameEl.textContent = '"' + productName + '"';
            
            // Reset modal state
            const spinner = confirmDeleteBtn.querySelector('.spinner-border');
            const btnText = confirmDeleteBtn.querySelector('.btn-text');
            spinner.classList.add('d-none');
            btnText.textContent = 'Delete';
            confirmDeleteBtn.disabled = false;
            
            // Show modal
            $('#deleteModal').modal('show');
        });
    });
    
    // Handle confirm delete
    confirmDeleteBtn.addEventListener('click', function() {
        const productId = productIdEl.value;
        if (!productId) return;
        
        // Show loading state
        const spinner = this.querySelector('.spinner-border');
        const btnText = this.querySelector('.btn-text');
        spinner.classList.remove('d-none');
        btnText.textContent = 'Deleting...';
        this.disabled = true;
        
        // Send delete request
        fetch(`?controller=product&action=delete&id=${productId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Remove the deleted row
                const row = document.getElementById(`product-row-${productId}`);
                if (row) {
                    row.remove();
                    
                    // Show success message
                    showAlert(data.message || 'Product deleted successfully', 'success');
                    
                    // Check if table is empty
                    const tbody = document.querySelector('table tbody');
                    if (tbody && tbody.children.length === 0) {
                        location.reload(); // Reload if no more products
                    }
                }
            } else {
                throw new Error(data.message || 'Failed to delete product');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert(error.message || 'An error occurred while deleting the product', 'danger');
        })
        .finally(function() {
            // Hide modal
            $('#deleteModal').modal('hide');
        });
    });
    
    // Function to show alert messages
    function showAlert(message, type = 'success') {
        // Remove any existing alerts
        const existingAlerts = alertMessages.querySelectorAll('.alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        `;
        
        // Add to container
        alertMessages.insertBefore(alertDiv, alertMessages.firstChild);
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $(alertDiv).alert('close');
        }, 5000);
    }
    
    // Close button for alerts (Bootstrap 4 uses data-dismiss)
    $(document).on('click', '[data-dismiss="alert"]', function() {
        var alert = $(this).closest('.alert');
        if (alert.length) alert.alert('close');
    });

    const inlineUrl = '<?php echo BASE_URL; ?>?controller=product&action=inlineUpdate';
    let activeCell = null;
    let activeEditor = null;
    let suppressBlurSave = false;
    let replayingActionClick = false;

    function isEditableCell(el) {
        return el && el.classList && el.classList.contains('cell-editable') && el.getAttribute('data-editable') === '1';
    }

    // Client-side search (filters current page only; server-side search remains supported)
    const tableSearch = document.getElementById('tableSearch');
    const clearTableSearch = document.getElementById('clearTableSearch');
    function applyTableSearch() {
        const q = (tableSearch && tableSearch.value ? tableSearch.value : '').trim().toLowerCase();
        const rows = document.querySelectorAll('#productsTable tbody tr');
        if (!rows) return;
        rows.forEach(r => {
            if (!q) { r.style.display = ''; return; }
            const text = (r.innerText || '').toLowerCase();
            r.style.display = text.includes(q) ? '' : 'none';
        });
    }
    if (tableSearch) {
        tableSearch.addEventListener('input', function() {
            applyTableSearch();
        });
    }
    if (clearTableSearch) {
        clearTableSearch.addEventListener('click', function() {
            if (tableSearch) tableSearch.value = '';
            applyTableSearch();
        });
    }

    // Column toggle (persisted)
    const colMenu = document.getElementById('colToggleMenu');
    const colStorageKey = 'pm_products_columns_v1';
    function getColumnPrefs() {
        try { return JSON.parse(localStorage.getItem(colStorageKey) || '{}') || {}; } catch (e) { return {}; }
    }
    function setColumnPrefs(prefs) {
        try { localStorage.setItem(colStorageKey, JSON.stringify(prefs || {})); } catch (e) { /* ignore */ }
    }
    function applyColumnVisibility() {
        const prefs = getColumnPrefs();
        const ths = document.querySelectorAll('#productsTable thead th');
        const rows = document.querySelectorAll('#productsTable tbody tr');
        ths.forEach((th, idx) => {
            const key = th.getAttribute('data-col') || ('col_' + idx);
            const visible = prefs[key] !== false;
            th.style.display = visible ? '' : 'none';
            rows.forEach(r => {
                const td = r.children && r.children[idx] ? r.children[idx] : null;
                if (td) td.style.display = visible ? '' : 'none';
            });
        });
    }
    function buildColumnMenu() {
        if (!colMenu) return;
        const prefs = getColumnPrefs();
        const ths = Array.from(document.querySelectorAll('#productsTable thead th'));
        colMenu.innerHTML = '';
        ths.forEach((th, idx) => {
            const key = th.getAttribute('data-col') || ('col_' + idx);
            const label = (th.textContent || key).trim();
            const checked = prefs[key] !== false;
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.innerHTML = `<label class="mb-0 d-flex align-items-center" style="gap: 10px; cursor: pointer;">
                <input type="checkbox" ${checked ? 'checked' : ''} data-col-key="${key}" style="transform: translateY(-1px);">
                <span>${label}</span>
            </label>`;
            colMenu.appendChild(item);
        });
        const divider = document.createElement('div');
        divider.className = 'dropdown-divider';
        colMenu.appendChild(divider);
        const reset = document.createElement('button');
        reset.type = 'button';
        reset.className = 'dropdown-item';
        reset.textContent = 'Reset columns';
        reset.addEventListener('click', function() {
            setColumnPrefs({});
            buildColumnMenu();
            applyColumnVisibility();
        });
        colMenu.appendChild(reset);
    }
    if (colMenu) {
        colMenu.addEventListener('change', function(e) {
            const cb = e.target && e.target.matches('input[type="checkbox"][data-col-key]') ? e.target : null;
            if (!cb) return;
            const prefs = getColumnPrefs();
            prefs[cb.getAttribute('data-col-key')] = !!cb.checked;
            setColumnPrefs(prefs);
            applyColumnVisibility();
        });
    }
    buildColumnMenu();
    applyColumnVisibility();

    // Sorting (client-side, current page only)
    function parseDateValue(s) {
        const t = Date.parse(s);
        return isNaN(t) ? 0 : t;
    }
    function parseMoneyValue(s) {
        const n = parseFloat((s || '').toString().replace(/[^0-9.\-]/g, ''));
        return isNaN(n) ? 0 : n;
    }
    function parseNumValue(s) {
        const n = parseFloat((s || '').toString().replace(/[^0-9.\-]/g, ''));
        return isNaN(n) ? 0 : n;
    }
    function getRowSortValue(r, key) {
        if (!r) return '';
        switch (key) {
            case 'name': return (r.querySelector('td[data-label="Name"]')?.innerText || '').trim().toLowerCase();
            case 'category': return (r.querySelector('td[data-label="Cat"]')?.innerText || '').trim().toLowerCase();
            case 'sku': return (r.querySelector('td[data-label="SKU"]')?.innerText || '').trim().toLowerCase();
            case 'batch': return (r.querySelector('td[data-label="Batch"]')?.innerText || '').trim().toLowerCase();
            case 'supplier': return (r.querySelector('td[data-label="Sup"]')?.innerText || '').trim().toLowerCase();
            case 'expiry': return parseDateValue((r.querySelector('td[data-label="Exp"]')?.innerText || '').trim());
            case 'bp': return parseMoneyValue((r.querySelector('td[data-label="BP"]')?.innerText || '').trim());
            case 'sp': return parseMoneyValue((r.querySelector('td[data-label="SP"]')?.innerText || '').trim());
            case 'wsp': return parseMoneyValue((r.querySelector('td[data-label="WSP"]')?.innerText || '').trim());
            case 'cc': return parseMoneyValue((r.querySelector('td[data-label="CC"]')?.innerText || '').trim());
            case 'tc': return parseMoneyValue((r.querySelector('td[data-label="TC"]')?.innerText || '').trim());
            case 'qty': return parseNumValue((r.querySelector('td[data-label="Qty"]')?.innerText || '').trim());
            case 'val': return parseMoneyValue((r.querySelector('td[data-label="Val"]')?.innerText || '').trim());
            case 'status': return (r.querySelector('td[data-label="St"]')?.innerText || '').trim().toLowerCase();
            case 'rownum':
            default:
                return parseNumValue((r.querySelector('td[data-label="#"]')?.innerText || '').trim());
        }
    }
    let currentSort = { key: '', dir: 'asc' };
    function applySort(key) {
        const tbody = document.querySelector('#productsTable tbody');
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const dir = (currentSort.key === key && currentSort.dir === 'asc') ? 'desc' : 'asc';
        currentSort = { key, dir };
        rows.sort((a, b) => {
            const va = getRowSortValue(a, key);
            const vb = getRowSortValue(b, key);
            if (typeof va === 'number' && typeof vb === 'number') return dir === 'asc' ? (va - vb) : (vb - va);
            return dir === 'asc' ? String(va).localeCompare(String(vb)) : String(vb).localeCompare(String(va));
        });
        rows.forEach(r => tbody.appendChild(r));
        document.querySelectorAll('#productsTable thead th.th-sort').forEach(th => {
            const is = th.getAttribute('data-sort-key') === key;
            th.classList.toggle('is-sorted', is);
            const icon = th.querySelector('.sort-icon');
            if (icon) icon.className = 'fas ' + (is ? (dir === 'asc' ? 'fa-sort-up sort-icon' : 'fa-sort-down sort-icon') : 'fa-sort sort-icon');
        });
    }
    document.querySelectorAll('#productsTable thead th.th-sort').forEach(th => {
        th.addEventListener('click', function(e) {
            if (activeCell && activeCell.classList.contains('cell-editing')) return;
            const key = th.getAttribute('data-sort-key') || '';
            if (key) applySort(key);
        });
    });

    // Export
    const exportPrintBtn = document.getElementById('exportPrint');
    if (exportPrintBtn) exportPrintBtn.addEventListener('click', function() { window.print(); });
    const exportExcelBtn = document.getElementById('exportExcel');
    if (exportExcelBtn) exportExcelBtn.addEventListener('click', function() {
        const table = document.getElementById('productsTable');
        if (!table) return;
        let csv = '';
        const rows = Array.from(table.querySelectorAll('tr'));
        rows.forEach(r => {
            const cells = Array.from(r.querySelectorAll('th,td'))
                .filter(c => c.style.display !== 'none')
                .map(c => '"' + (c.innerText || '').replace(/\s+/g, ' ').trim().replace(/"/g, '""') + '"');
            csv += cells.join(',') + '\n';
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'products.csv';
        document.body.appendChild(a);
        a.click();
        a.remove();
        URL.revokeObjectURL(url);
    });

    // Quick preview on hover (desktop only)
    const previewEl = document.createElement('div');
    previewEl.className = 'pm-preview';
    previewEl.innerHTML = `<div class="pm-preview__body">
        <img class="pm-preview__img" alt="" src="" />
        <h6 class="pm-preview__title"></h6>
        <div class="pm-preview__meta"></div>
        <div class="pm-preview__grid">
            <div><div class="pm-preview__k">BP</div><div class="pm-preview__v" data-k="bp"></div></div>
            <div><div class="pm-preview__k">SP</div><div class="pm-preview__v" data-k="sp"></div></div>
            <div><div class="pm-preview__k">Qty</div><div class="pm-preview__v" data-k="qty"></div></div>
            <div><div class="pm-preview__k">Exp</div><div class="pm-preview__v" data-k="exp"></div></div>
        </div>
    </div>`;
    document.body.appendChild(previewEl);
    const previewImgEl = previewEl.querySelector('img');
    const previewTitleEl = previewEl.querySelector('.pm-preview__title');
    const previewMetaEl = previewEl.querySelector('.pm-preview__meta');

    function showPreviewForRow(row, evt) {
        if (!row || window.matchMedia('(max-width: 575.98px)').matches) return;
        if (!previewEl) return;
        const name = row.getAttribute('data-preview-name') || '';
        const sku = row.getAttribute('data-preview-sku') || '';
        const cat = row.getAttribute('data-preview-cat') || '';
        const sup = row.getAttribute('data-preview-sup') || '';
        const exp = row.getAttribute('data-preview-exp') || '';
        const bp = row.getAttribute('data-preview-bp') || '';
        const sp = row.getAttribute('data-preview-sp') || '';
        const qty = row.getAttribute('data-preview-qty') || '';
        const img = row.getAttribute('data-preview-img') || '';
        if (previewImgEl && img) previewImgEl.src = img;
        if (previewTitleEl) previewTitleEl.textContent = name || 'Product';
        if (previewMetaEl) previewMetaEl.textContent = [cat ? ('Cat: ' + cat) : '', sup ? ('Sup: ' + sup) : '', sku ? ('SKU: ' + sku) : ''].filter(Boolean).join(' • ');
        previewEl.querySelector('[data-k="bp"]').textContent = bp ? ('CHF ' + bp) : '-';
        previewEl.querySelector('[data-k="sp"]').textContent = sp ? ('CHF ' + sp) : '-';
        previewEl.querySelector('[data-k="qty"]').textContent = qty !== '' ? qty : '-';
        previewEl.querySelector('[data-k="exp"]').textContent = exp || '-';
        const pad = 14;
        const x = Math.min(window.innerWidth - previewEl.offsetWidth - pad, (evt.clientX || 0) + 16);
        const y = Math.min(window.innerHeight - previewEl.offsetHeight - pad, (evt.clientY || 0) + 16);
        previewEl.style.left = Math.max(pad, x) + 'px';
        previewEl.style.top = Math.max(pad, y) + 'px';
        previewEl.style.display = 'block';
    }
    function hidePreview() {
        if (previewEl) previewEl.style.display = 'none';
    }
    document.querySelectorAll('#productsTable tbody tr').forEach(r => {
        r.addEventListener('mousemove', function(e) {
            if (isActionElement(e.target) || isEditableCell(e.target)) return;
            showPreviewForRow(r, e);
        });
        r.addEventListener('mouseleave', function() { hidePreview(); });
    });

    function getCellList() {
        return Array.from(document.querySelectorAll('#productsTable .cell-editable[data-editable="1"]'));
    }

    function cellIndex(cell) {
        const list = getCellList();
        return list.indexOf(cell);
    }

    function focusCellByIndex(idx) {
        const list = getCellList();
        if (idx < 0 || idx >= list.length) return;
        list[idx].focus();
    }

    function flashCell(cell, ok) {
        if (!cell) return;
        cell.classList.remove('cell-error', 'cell-saved');
        void cell.offsetWidth;
        cell.classList.add(ok ? 'cell-saved' : 'cell-error');
        setTimeout(function() {
            cell.classList.remove('cell-error');
        }, 1200);
    }

    function setCellText(cell, text) {
        if (!cell) return;
        if (cell.tagName && cell.tagName.toLowerCase() === 'span') {
            cell.textContent = text;
            return;
        }
        cell.innerHTML = '';
        cell.appendChild(document.createTextNode(text));
    }

    function ensureStatusEl(cell) {
        if (!cell) return null;
        let s = cell.querySelector(':scope > .cell-status');
        if (!s) {
            s = document.createElement('span');
            s.className = 'cell-status';
            cell.appendChild(s);
        }
        return s;
    }

    function setStatus(cell, html) {
        const s = ensureStatusEl(cell);
        if (!s) return;
        s.innerHTML = html || '';
    }

    function startEdit(cell) {
        if (!isEditableCell(cell)) return;
        if (cell.classList.contains('cell-editing')) return;
        if (activeCell && activeCell !== cell) {
            commitEdit(activeCell);
        }

        const field = cell.getAttribute('data-field') || '';
        const type = cell.getAttribute('data-type') || 'text';
        const raw = cell.getAttribute('data-value');
        const prevText = cell.textContent;

        cell.classList.add('cell-editing');
        setStatus(cell, '');
        cell.setAttribute('data-prev-text', prevText);
        cell.setAttribute('data-prev-value', raw === null ? '' : String(raw));

        let editor = null;
        if (type === 'select') {
            editor = document.createElement('select');
            editor.className = 'form-control form-control-sm inline-editor';
            const opt0 = document.createElement('option');
            opt0.value = '';
            opt0.textContent = 'Uncategorized';
            editor.appendChild(opt0);
            <?php if (!empty($categories) && is_array($categories)): ?>
            <?php foreach ($categories as $c): ?>
                <?php if (isset($c['id']) && isset($c['name'])): ?>
                    (function(){
                        const o = document.createElement('option');
                        o.value = '<?php echo (int)$c['id']; ?>';
                        o.textContent = '<?php echo htmlspecialchars((string)$c['name']); ?>';
                        editor.appendChild(o);
                    })();
                <?php endif; ?>
            <?php endforeach; ?>
            <?php endif; ?>
            editor.value = raw === null ? '' : String(raw);
        } else {
            editor = document.createElement('input');
            editor.className = 'form-control form-control-sm inline-editor';
            if (type === 'number' || type === 'number_nullable') {
                editor.type = 'number';
                editor.step = '0.01';
                if (field === 'stock_quantity') {
                    editor.step = '1';
                }
            } else if (type === 'date') {
                editor.type = 'date';
            } else {
                editor.type = 'text';
            }

            if (type === 'datalist') {
                editor.setAttribute('list', 'supplierDatalist');
            }
            editor.value = (raw === null || raw === undefined) ? '' : String(raw);
        }

        cell.innerHTML = '';
        cell.appendChild(editor);
        // keep a status container next to the editor
        const status = document.createElement('span');
        status.className = 'cell-status';
        cell.appendChild(status);
        activeCell = cell;
        activeEditor = editor;

        setTimeout(function() {
            try { editor.focus(); editor.select && editor.select(); } catch(e) {}
        }, 0);

        editor.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                cancelEdit(cell);
                return;
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                suppressBlurSave = true;
                commitEdit(cell, function() {
                    suppressBlurSave = false;
                    const idx = cellIndex(cell);
                    focusCellByIndex(idx + 1);
                });
                return;
            }
            if (e.key === 'Tab') {
                e.preventDefault();
                suppressBlurSave = true;
                const idx = cellIndex(cell);
                const nextIdx = e.shiftKey ? (idx - 1) : (idx + 1);
                commitEdit(cell, function() {
                    suppressBlurSave = false;
                    focusCellByIndex(nextIdx);
                });
                return;
            }
        });
    }

    function cancelEdit(cell) {
        if (!cell) return;
        const prevText = cell.getAttribute('data-prev-text') || '';
        const prevValue = cell.getAttribute('data-prev-value') || '';
        cell.classList.remove('cell-editing', 'cell-saving');
        cell.setAttribute('data-value', prevValue);
        setStatus(cell, '');
        setCellText(cell, prevText);
        activeCell = null;
        activeEditor = null;
    }

    function commitEdit(cell, cb) {
        if (!cell || !cell.classList.contains('cell-editing')) {
            if (typeof cb === 'function') cb();
            return;
        }
        const id = cell.getAttribute('data-id');
        const field = cell.getAttribute('data-field');
        const type = cell.getAttribute('data-type') || 'text';
        const editor = cell.querySelector('.inline-editor');
        const newValue = editor ? editor.value : '';
        const prevValue = cell.getAttribute('data-prev-value') || '';

        if (String(newValue) === String(prevValue)) {
            const prevText = cell.getAttribute('data-prev-text') || '';
            cell.classList.remove('cell-editing');
            cell.setAttribute('data-value', prevValue);
            setCellText(cell, prevText);
            activeCell = null;
            activeEditor = null;
            if (typeof cb === 'function') cb();
            return;
        }

        cell.classList.add('cell-saving');
        setStatus(cell, '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

        fetch(inlineUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ id: id, field: field, value: (type === 'number_nullable' && newValue === '' ? '' : newValue) })
        })
        .then(async (r) => {
            const text = await r.text();
            try {
                return JSON.parse(text);
            } catch (e) {
                const preview = (text || '').substring(0, 180).replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
                throw new Error(preview ? ('Server returned an invalid response: ' + preview) : 'Server returned an invalid response');
            }
        })
        .then(data => {
            if (!data || !data.success) {
                throw new Error((data && data.message) ? data.message : 'Save failed');
            }

            cell.classList.remove('cell-editing', 'cell-saving');
            cell.setAttribute('data-value', data.value === null ? '' : String(data.value));
            setCellText(cell, (data.display === null || data.display === undefined || data.display === '') ? '-' : String(data.display));

            if (field === 'category_id') {
                const row = cell.closest('tr');
                if (row) row.setAttribute('data-category', String(data.display || 'Uncategorized'));
            }

            setStatus(cell, '<span class="cell-check">✓</span>');
            setTimeout(function(){ setStatus(cell, ''); }, 700);
            flashCell(cell, true);
            activeCell = null;
            activeEditor = null;
            if (typeof cb === 'function') cb();
        })
        .catch(err => {
            cell.classList.remove('cell-saving');
            setStatus(cell, '');
            flashCell(cell, false);
            try { cell.querySelector('.inline-editor') && cell.querySelector('.inline-editor').focus(); } catch(e) {}
            showAlert(err.message || 'Failed to save', 'danger');
            if (typeof cb === 'function') cb();
        });
    }

    document.addEventListener('mousedown', function(e) {
        // If clicking inside the current editor, do nothing
        if (e.target.closest('.inline-editor')) {
            return;
        }

        const table = document.getElementById('productsTable');
        const clickedInsideTable = table ? table.contains(e.target) : false;
        const cell = e.target.closest('.cell-editable');

        // If there is an active edit, close it when clicking outside the active row
        if (activeCell && activeCell.classList.contains('cell-editing')) {
            const activeRow = activeCell.closest('tr');
            const clickedRow = e.target.closest('tr');
            if (clickedInsideTable && activeRow && clickedRow && clickedRow !== activeRow) {
                // If clicking another editable cell, that case is handled below
                if (!(cell && isEditableCell(cell))) {
                    commitEdit(activeCell);
                }
            }
        }

        // Click outside table closes current edit (save)
        if (!clickedInsideTable) {
            if (activeCell && activeCell.classList.contains('cell-editing')) {
                commitEdit(activeCell);
            }
            return;
        }

        // Click on another editable cell: save current then start new
        if (cell && isEditableCell(cell)) {
            if (activeCell && activeCell.classList.contains('cell-editing') && activeCell !== cell) {
                commitEdit(activeCell, function() { startEdit(cell); });
            } else {
                startEdit(cell);
            }
            return;
        }

        // Click inside table but not on editable cell: keep edit open
    });

    document.addEventListener('keydown', function(e) {
        if (activeCell && activeCell.classList.contains('cell-editing')) return;
        const focused = document.activeElement;
        if (!isEditableCell(focused)) return;
        const idx = cellIndex(focused);
        if (idx < 0) return;

        if (e.key === 'Enter') {
            e.preventDefault();
            startEdit(focused);
            return;
        }

        if (e.key === 'ArrowRight') { e.preventDefault(); focusCellByIndex(idx + 1); return; }
        if (e.key === 'ArrowLeft') { e.preventDefault(); focusCellByIndex(idx - 1); return; }
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const cell = focused;
            const field = cell.getAttribute('data-field');
            const row = cell.closest('tr');
            if (!row) return;
            const nextRow = row.nextElementSibling;
            if (!nextRow) return;
            const target = nextRow.querySelector('.cell-editable[data-field="' + field + '"]');
            if (target) target.focus();
            return;
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            const cell = focused;
            const field = cell.getAttribute('data-field');
            const row = cell.closest('tr');
            if (!row) return;
            const prevRow = row.previousElementSibling;
            if (!prevRow) return;
            const target = prevRow.querySelector('.cell-editable[data-field="' + field + '"]');
            if (target) target.focus();
            return;
        }
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
