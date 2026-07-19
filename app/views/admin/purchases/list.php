<?php
/**
 * ListPurchaseController view — Product hub for purchase workflow (UI only)
 * Preserves: #productsTable, DataTable init, highlight_product_id logic, action links.
 */
$highlightId = isset($_GET['highlight_product_id']) ? (int)$_GET['highlight_product_id'] : 0;
$returned = isset($_GET['returned']) ? (int)$_GET['returned'] : 0;
$products = $products ?? [];
$categories = $categories ?? [];
$brands = $brands ?? [];
$taxRates = $taxRates ?? [];
$brandMap = $brandMap ?? [];
$categoryMap = $categoryMap ?? [];

$totalProducts = count($products);
$inStock = 0;
$lowStock = 0;
$outStock = 0;
$totalStockQty = 0.0;
$inventoryValue = 0.0;
foreach ($products as $p) {
    $sq = isset($p['stock_quantity']) ? (float)$p['stock_quantity'] : 0;
    $price = isset($p['price']) ? (float)$p['price'] : 0;
    $totalStockQty += $sq;
    $inventoryValue += $sq * $price;
    if ($sq <= 0) $outStock++;
    elseif ($sq <= 5) $lowStock++;
    else $inStock++;
}
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/purchase-list.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid purchase-list-page py-3 py-md-4 px-2 px-sm-3" id="listPurchasePage">
    <div class="pl-header">
        <div>
            <nav class="pl-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span>›</span>
                <span>Purchases</span>
                <span>›</span>
                <span aria-current="page">Purchase Products</span>
            </nav>
            <h1 class="pl-title">Purchase Management</h1>
            <p class="pl-subtitle"><?php echo htmlspecialchars($subtitle ?? 'Select products and manage stock for purchasing'); ?></p>
        </div>
        <div class="pl-actions">
            <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController" class="btn btn-outline-secondary pl-btn" title="Refresh">
                <i class="bi bi-arrow-clockwise"></i><span class="d-none d-md-inline">Refresh</span>
            </a>
            <button type="button" class="btn btn-outline-secondary pl-btn" onclick="window.print()" title="Print">
                <i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span>
            </button>
            <button type="button" class="btn btn-outline-secondary pl-btn" id="plExportCsvBtn" title="Excel">
                <i class="bi bi-file-earmark-spreadsheet"></i><span class="d-none d-lg-inline">Excel</span>
            </button>
            <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-outline-secondary pl-btn">
                <i class="fas fa-history me-1"></i><span class="d-none d-md-inline">Purchase History</span>
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=purchase&action=create" class="btn pl-btn pl-btn-primary">
                <i class="bi bi-plus-lg"></i><span>New Purchase</span>
            </a>
        </div>
    </div>

    <?php if (!empty($highlightId)): ?>
      <?php if (!empty($returned)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          Stock returned successfully for Product ID: <strong><?php echo htmlspecialchars($highlightId); ?></strong>
          <a class="ms-2" href="<?php echo BASE_URL; ?>?controller=ListPurchaseController">Clear</a>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php else: ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
          Showing product ID: <strong><?php echo htmlspecialchars($highlightId); ?></strong>
          <a class="ms-2" href="<?php echo BASE_URL; ?>?controller=ListPurchaseController">Clear</a>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s1"><div class="icon"><i class="bi bi-box-seam"></i></div><div><div class="label">Total Products</div><div class="value" data-counter="<?php echo $totalProducts; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s2"><div class="icon"><i class="bi bi-check-circle"></i></div><div><div class="label">In Stock</div><div class="value" data-counter="<?php echo $inStock; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s3"><div class="icon"><i class="bi bi-exclamation-triangle"></i></div><div><div class="label">Low Stock</div><div class="value" data-counter="<?php echo $lowStock; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s4"><div class="icon"><i class="bi bi-arrow-return-left"></i></div><div><div class="label">Return / Empty</div><div class="value" data-counter="<?php echo $outStock; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s5"><div class="icon"><i class="bi bi-stack"></i></div><div><div class="label">Total Units</div><div class="value" data-counter="<?php echo (int)round($totalStockQty); ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s6"><div class="icon"><i class="bi bi-currency-rupee"></i></div><div><div class="label">Stock Value</div><div class="value" data-counter="<?php echo (int)round($inventoryValue); ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s7"><div class="icon"><i class="bi bi-tags"></i></div><div><div class="label">Categories</div><div class="value" data-counter="<?php echo count($categories); ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s8"><div class="icon"><i class="bi bi-award"></i></div><div><div class="label">Brands</div><div class="value" data-counter="<?php echo count($brands); ?>">0</div></div></div>
        </div>
    </div>

    <div class="pl-card mb-3">
        <div class="pl-card-header">
            <h2><i class="bi bi-funnel"></i> Filters</h2>
        </div>
        <div class="pl-card-body">
            <form class="row g-3" id="plFilterForm" onsubmit="return false;">
                <div class="col-md-2">
                    <label class="form-label">Product Type</label>
                    <select class="form-select" id="plFilterType">
                        <option value="">All</option>
                        <option value="single">Single</option>
                        <option value="variable">Variable</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Category</label>
                    <select class="form-select" id="plFilterCategory">
                        <option value="">All</option>
                        <?php if (!empty($categories)) : ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['name'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($cat['name'] ?? ('Category #' . $cat['id'])); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Unit</label>
                    <select class="form-select"><option>All</option></select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tax</label>
                    <select class="form-select">
                        <option value="">All</option>
                        <?php if (!empty($taxRates)) : ?>
                            <?php foreach ($taxRates as $tax): ?>
                                <option value="<?php echo htmlspecialchars($tax['id'] ?? ''); ?>">
                                    <?php echo htmlspecialchars(($tax['name'] ?? 'Tax') . ' (' . number_format((float)($tax['rate'] ?? 0), 2) . '%)'); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Brand</label>
                    <select class="form-select" id="plFilterBrand">
                        <option value="">All</option>
                        <?php if (!empty($brands)) : ?>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?php echo htmlspecialchars($brand['name'] ?? ''); ?>">
                                    <?php echo htmlspecialchars($brand['name'] ?? ('Brand #' . $brand['id'])); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Business Location</label>
                    <select class="form-select"><option>All</option></select>
                </div>
                <div class="col-12 d-flex flex-wrap gap-2 align-items-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="notForSelling">
                        <label class="form-check-label" for="notForSelling">Not for selling</label>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" id="plResetFilters"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</button>
                </div>
            </form>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-products" type="button" role="tab">All Products</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-stock" type="button" role="tab">Stock Report</button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-products" role="tabpanel">
            <div class="pl-card">
                <div class="pl-card-body">
                    <div class="purchase-toolbar d-flex flex-wrap gap-2 align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <label class="me-2">Show</label>
                            <select class="form-select form-select-sm" style="width: 80px;" id="plPageSize">
                                <option>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select>
                            <span class="ms-2">entries</span>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="plExportCsvBtn2">Export CSV</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">Print</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">Export PDF</button>
                        </div>
                        <div class="search-wrap ms-auto" style="width:240px">
                            <input type="text" class="form-control form-control-sm" id="plLiveSearch" placeholder="Search ..." aria-label="Search products">
                        </div>
                    </div>

                    <div class="table-responsive purchases-table-scroll">
                        <table class="table table-bordered table-striped align-middle" id="productsTable" aria-label="Products for purchase">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:32px"><input type="checkbox" id="plSelectAll" aria-label="Select all"></th>
                                    <th>Product image</th>
                                    <th>Action</th>
                                    <th>Product</th>
                                    <th>Business Location</th>
                                    <th>Unit Purchase Price</th>
                                    <th>Selling Price</th>
                                    <th>Stock</th>
                                    <th>Stock Status</th>
                                    <th>Product Type</th>
                                    <th>Category</th>
                                    <th>Brand</th>
                                    <th>Tax</th>
                                    <th>SKU</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)) : ?>
                                    <?php foreach ($products as $p): ?>
                                        <?php
                                            $img = isset($p['image']) && $p['image'] ? $p['image'] : '';
                                            $name = $p['name'] ?? '—';
                                            $sku = $p['sku'] ?? '';
                                            $categoryName = $p['category_name'] ?? ($categoryMap[$p['category_id']]['name'] ?? '—');
                                            $brandName = isset($p['brand_id']) && isset($brandMap[$p['brand_id']]) ? ($brandMap[$p['brand_id']]['name'] ?? '—') : '—';
                                            $purchase = isset($p['price']) ? (float)$p['price'] : null;
                                            $sell = isset($p['sale_price']) && $p['sale_price'] > 0 ? (float)$p['sale_price'] : (isset($p['price']) ? (float)$p['price'] : null);
                                            $stockQty = isset($p['stock_quantity']) ? (float)$p['stock_quantity'] : 0;
                                            $lastPurchaseQty = isset($p['last_purchase_qty']) ? (float)$p['last_purchase_qty'] : 0;
                                            $unitLabel = 'Pieces';
                                            $type = isset($p['type']) ? ucfirst($p['type']) : 'Single';
                                            $taxDisplay = '—';
                                            $stockStatus = $stockQty <= 0 ? 'return' : ($stockQty <= 5 ? 'low' : 'in');
                                        ?>
                                        <tr data-product-id="<?php echo htmlspecialchars($p['id']); ?>"
                                            data-name="<?php echo htmlspecialchars(strtolower($name)); ?>"
                                            data-sku="<?php echo htmlspecialchars(strtolower($sku)); ?>"
                                            data-category="<?php echo htmlspecialchars(strtolower($categoryName)); ?>"
                                            data-brand="<?php echo htmlspecialchars(strtolower($brandName)); ?>"
                                            data-type="<?php echo htmlspecialchars(strtolower($type)); ?>"
                                            data-stock="<?php echo $stockStatus; ?>">
                                            <td data-label="#"><input type="checkbox" class="pl-row-check" value="<?php echo htmlspecialchars($p['id']); ?>"></td>
                                            <td data-label="Product image">
                                                <?php if ($img): ?>
                                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="<?php echo htmlspecialchars($name); ?>" class="pl-thumb rounded border" />
                                                <?php else: ?>
                                                    <div class="pl-thumb-empty"></div>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Action">
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Actions</button>
                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#" title="Labels"><i class="fas fa-tags me-2 text-muted"></i> Labels</a></li>
                                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=ProductController&action=show&id=<?php echo urlencode($p['id']); ?>"><i class="far fa-eye me-2 text-muted"></i> View</a></li>
                                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=ProductController&action=edit&id=<?php echo urlencode($p['id']); ?>"><i class="far fa-edit me-2 text-muted"></i> Edit</a></li>
                                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=purchase&action=purchase3&product_id=<?php echo urlencode($p['id']); ?>" title="Purchase Return"><i class="fas fa-undo-alt me-2 text-muted"></i> Purchase Return</a></li>
                                                        <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>?controller=ProductController&action=delete&id=<?php echo urlencode($p['id']); ?>" onclick="return confirm('Delete this product?')"><i class="far fa-trash-alt me-2"></i> Delete</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item" href="#" title="Add or edit opening stock"><i class="fas fa-database me-2 text-muted"></i> Add or edit opening stock</a></li>
                                                        <li><a class="dropdown-item" href="#" title="Product stock history"><i class="fas fa-undo me-2 text-muted"></i> Product stock history</a></li>
                                                        <li><a class="dropdown-item" href="#" title="Duplicate Product"><i class="far fa-clone me-2 text-muted"></i> Duplicate Product</a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>?controller=purchase&action=create"><i class="bi bi-cart-plus me-2 text-muted"></i> Create Purchase</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                            <td data-label="Product">
                                                <?php echo htmlspecialchars($name); ?>
                                                <?php if (!empty($highlightId) && (int)$highlightId === (int)$p['id']): ?>
                                                    <span class="badge bg-info ms-2">Returned</span>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Business Location">S.N PASUMAI KALANJIYAM</td>
                                            <td data-label="Unit Purchase Price"><?php echo isset($purchase) ? htmlspecialchars(formatPrice($purchase)) : '—'; ?></td>
                                            <td data-label="Selling Price"><?php echo isset($sell) ? htmlspecialchars(formatPrice($sell)) : '—'; ?></td>
                                            <td data-label="Stock" title="Total stock: <?php echo number_format($stockQty, 2); ?>">
                                                <?php echo number_format($lastPurchaseQty > 0 ? $lastPurchaseQty : 0, 2) . ' ' . $unitLabel; ?>
                                            </td>
                                            <td data-label="Stock Status">
                                                <?php
                                                    $badge = '<span class="badge bg-success">In Stock</span>';
                                                    if ($stockQty <= 0) {
                                                        $badge = '<span class="badge bg-info">Return</span>';
                                                    } elseif ($stockQty <= 5) {
                                                        $badge = '<span class="badge bg-warning text-dark">Low</span>';
                                                    }
                                                    echo $badge;
                                                ?>
                                            </td>
                                            <td data-label="Product Type"><?php echo htmlspecialchars($type); ?></td>
                                            <td data-label="Category"><?php echo htmlspecialchars($categoryName); ?></td>
                                            <td data-label="Brand"><?php echo htmlspecialchars($brandName); ?></td>
                                            <td data-label="Tax"><?php echo htmlspecialchars($taxDisplay); ?></td>
                                            <td data-label="SKU"><?php echo htmlspecialchars($sku); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="14" class="text-center text-muted">No products found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="tab-stock" role="tabpanel">
            <div class="pl-card">
                <div class="pl-card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="pl-chart-card">
                                <h3>Stock Mix</h3>
                                <canvas id="plStockChart" height="180"></canvas>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-info mb-0">Stock report summary — In stock: <?php echo $inStock; ?>, Low: <?php echo $lowStock; ?>, Return/Empty: <?php echo $outStock; ?>.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        if (window.jQuery && $.fn.DataTable) {
            $('#productsTable').DataTable();
        }

        // If returning with a specific product to show, highlight and focus it
        const highlightId = <?php echo (int)($highlightId ?: 0); ?>;
        if (highlightId > 0) {
            const rows = document.querySelectorAll('#productsTable tbody tr');
            let target = null;
            rows.forEach(tr => {
                const id = tr.getAttribute('data-product-id');
                if (String(id) === String(highlightId)) {
                    target = tr;
                    tr.classList.add('table-warning');
                } else {
                    tr.style.display = 'none';
                }
            });
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        document.querySelectorAll('[data-counter]').forEach(function(el) {
            var target = parseInt(el.getAttribute('data-counter'), 10) || 0;
            var start = 0, t0 = null, duration = 700;
            function step(ts) {
                if (!t0) t0 = ts;
                var p = Math.min((ts - t0) / duration, 1);
                el.textContent = String(Math.round(start + (target - start) * (1 - Math.pow(1 - p, 3))));
                if (p < 1) requestAnimationFrame(step);
            }
            requestAnimationFrame(step);
        });

        function exportCsv() {
            var lines = ['ID,Product,SKU,Category,Brand,StockStatus'];
            document.querySelectorAll('#productsTable tbody tr[data-product-id]').forEach(function(tr) {
                if (tr.style.display === 'none') return;
                lines.push([
                    tr.getAttribute('data-product-id'),
                    '"' + (tr.getAttribute('data-name') || '').replace(/"/g, '""') + '"',
                    tr.getAttribute('data-sku'),
                    '"' + (tr.getAttribute('data-category') || '').replace(/"/g, '""') + '"',
                    '"' + (tr.getAttribute('data-brand') || '').replace(/"/g, '""') + '"',
                    tr.getAttribute('data-stock')
                ].join(','));
            });
            var a = document.createElement('a');
            a.href = URL.createObjectURL(new Blob([lines.join('\n')], { type: 'text/csv' }));
            a.download = 'purchase-products.csv';
            a.click();
        }
        var ex1 = document.getElementById('plExportCsvBtn');
        var ex2 = document.getElementById('plExportCsvBtn2');
        if (ex1) ex1.addEventListener('click', exportCsv);
        if (ex2) ex2.addEventListener('click', exportCsv);

        var search = document.getElementById('plLiveSearch');
        var catF = document.getElementById('plFilterCategory');
        var brandF = document.getElementById('plFilterBrand');
        var typeF = document.getElementById('plFilterType');
        function applyClientFilter() {
            if (highlightId > 0) return;
            var q = (search && search.value || '').toLowerCase().trim();
            var cat = (catF && catF.value || '').toLowerCase();
            var brand = (brandF && brandF.value || '').toLowerCase();
            var type = (typeF && typeF.value || '').toLowerCase();
            document.querySelectorAll('#productsTable tbody tr[data-product-id]').forEach(function(tr) {
                var ok = true;
                var blob = ((tr.getAttribute('data-name') || '') + ' ' + (tr.getAttribute('data-sku') || '')).toLowerCase();
                if (q && blob.indexOf(q) === -1) ok = false;
                if (cat && (tr.getAttribute('data-category') || '') !== cat) ok = false;
                if (brand && (tr.getAttribute('data-brand') || '') !== brand) ok = false;
                if (type && (tr.getAttribute('data-type') || '').indexOf(type) === -1) ok = false;
                tr.style.display = ok ? '' : 'none';
            });
        }
        if (search) search.addEventListener('input', applyClientFilter);
        if (catF) catF.addEventListener('change', applyClientFilter);
        if (brandF) brandF.addEventListener('change', applyClientFilter);
        if (typeF) typeF.addEventListener('change', applyClientFilter);
        var reset = document.getElementById('plResetFilters');
        if (reset) reset.addEventListener('click', function() {
            if (search) search.value = '';
            if (catF) catF.value = '';
            if (brandF) brandF.value = '';
            if (typeF) typeF.value = '';
            applyClientFilter();
        });

        var selAll = document.getElementById('plSelectAll');
        if (selAll) selAll.addEventListener('change', function() {
            document.querySelectorAll('.pl-row-check').forEach(function(cb) { cb.checked = selAll.checked; });
        });

        if (typeof Chart !== 'undefined') {
            var ctx = document.getElementById('plStockChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['In Stock', 'Low', 'Return/Empty'],
                        datasets: [{ data: [<?php echo $inStock; ?>, <?php echo $lowStock; ?>, <?php echo $outStock; ?>], backgroundColor: ['#10b981', '#f59e0b', '#38bdf8'], borderWidth: 0 }]
                    },
                    options: { plugins: { legend: { position: 'bottom' } }, cutout: '62%' }
                });
            }
        }
    });
</script>
