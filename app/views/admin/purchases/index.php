<?php
/**
 * Purchase History — Enterprise dashboard UI (visual layer only)
 * Preserves: #purchasesTable, DataTable, view/edit/delete links & forms.
 */
$purchases = $purchases ?? [];
$totalPurchases = count($purchases);
$completed = $pending = $ordered = $cancelled = $returns = 0;
$totalValue = 0.0;
$outstanding = 0.0;
$suppliersSeen = [];
$today = date('Y-m-d');
$monthPrefix = date('Y-m');
$todayCount = 0;
$monthValue = 0.0;

foreach ($purchases as $purchase) {
    $po = isset($purchase['purchase_no']) ? (string)$purchase['purchase_no'] : '';
    $nt = isset($purchase['notes']) ? (string)$purchase['notes'] : '';
    $isReturn = (strpos($po, 'PR-') === 0) || (stripos($nt, '[RETURN]') !== false);
    $statusRaw = strtolower((string)($purchase['status'] ?? ''));
    $total = (float)($purchase['total_amount'] ?? 0);
    $advance = (float)($purchase['paid_amount'] ?? 0);
    $balance = max($total - $advance, 0);
    $totalValue += $total;
    $outstanding += $balance;
    $supName = (string)($purchase['supplier_name'] ?? '');
    if ($supName !== '') $suppliersSeen[$supName] = true;
    $pDate = !empty($purchase['purchase_date']) ? substr((string)$purchase['purchase_date'], 0, 10) : '';
    if ($pDate === $today) $todayCount++;
    if ($pDate !== '' && substr($pDate, 0, 7) === $monthPrefix) $monthValue += $total;
    if ($isReturn) $returns++;
    elseif ($statusRaw === 'received') $completed++;
    elseif ($statusRaw === 'pending') $pending++;
    elseif ($statusRaw === 'ordered') $ordered++;
    elseif ($statusRaw === 'cancelled') $cancelled++;
}
$supplierCount = count($suppliersSeen);
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/purchase-list.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid purchase-list-page py-3 py-md-4 px-2 px-sm-3" id="purchaseHistoryPage">
    <div class="pl-header">
        <div>
            <nav class="pl-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span>›</span>
                <span>Purchases</span>
                <span>›</span>
                <span aria-current="page">Purchase List</span>
            </nav>
            <h1 class="pl-title"><?php echo htmlspecialchars($title ?? 'Purchase Management'); ?></h1>
            <p class="pl-subtitle">Purchase history, payments, and supplier procurement records.</p>
        </div>
        <div class="pl-actions">
            <a href="<?php echo BASE_URL; ?>?controller=purchase&action=index" class="btn btn-outline-secondary pl-btn" title="Refresh">
                <i class="bi bi-arrow-clockwise"></i><span class="d-none d-md-inline">Refresh</span>
            </a>
            <button type="button" class="btn btn-outline-secondary pl-btn" onclick="window.print()" title="Print">
                <i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span>
            </button>
            <button type="button" class="btn btn-outline-secondary pl-btn" id="phExportCsvBtn" title="Excel">
                <i class="bi bi-file-earmark-spreadsheet"></i><span class="d-none d-lg-inline">Excel</span>
            </button>
            <a href="<?php echo BASE_URL; ?>?controller=ListPurchaseController" class="btn btn-outline-secondary pl-btn">
                <i class="bi bi-box-seam me-1"></i><span class="d-none d-md-inline">Products</span>
            </a>
            <a href="<?php echo BASE_URL; ?>?controller=purchase&action=create" class="btn pl-btn pl-btn-primary">
                <i class="bi bi-plus-lg"></i><span>New Purchase</span>
            </a>
        </div>
    </div>

    <?php flash('success'); ?>
    <?php flash('error'); ?>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s1"><div class="icon"><i class="bi bi-bag-check"></i></div><div><div class="label">Total Purchases</div><div class="value" data-counter="<?php echo $totalPurchases; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s2"><div class="icon"><i class="bi bi-currency-rupee"></i></div><div><div class="label">Purchase Value</div><div class="value" data-counter="<?php echo (int)round($totalValue); ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s3"><div class="icon"><i class="bi bi-building"></i></div><div><div class="label">Suppliers</div><div class="value" data-counter="<?php echo $supplierCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s4"><div class="icon"><i class="bi bi-calendar-day"></i></div><div><div class="label">Today</div><div class="value" data-counter="<?php echo $todayCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s5"><div class="icon"><i class="bi bi-graph-up"></i></div><div><div class="label">Month Value</div><div class="value" data-counter="<?php echo (int)round($monthValue); ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s6"><div class="icon"><i class="bi bi-check-circle"></i></div><div><div class="label">Received</div><div class="value" data-counter="<?php echo $completed; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s7"><div class="icon"><i class="bi bi-hourglass-split"></i></div><div><div class="label">Pending</div><div class="value" data-counter="<?php echo $pending; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="pl-stat s8"><div class="icon"><i class="bi bi-cash-stack"></i></div><div><div class="label">Outstanding</div><div class="value" data-counter="<?php echo (int)round($outstanding); ?>">0</div></div></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-9">
            <div class="pl-card mb-3">
                <div class="pl-toolbar">
                    <div class="d-flex flex-wrap gap-2 align-items-center" style="flex:1;">
                        <div class="input-group input-group-sm" style="max-width:320px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" class="form-control" id="phLiveSearch" placeholder="Search purchase, supplier, products…" aria-label="Search purchases">
                        </div>
                        <select class="form-select form-select-sm" id="phFilterStatus" style="width:auto;min-width:130px;">
                            <option value="">All Status</option>
                            <option value="received">Received</option>
                            <option value="pending">Pending</option>
                            <option value="ordered">Ordered</option>
                            <option value="return">Return</option>
                        </select>
                        <select class="form-select form-select-sm" id="phFilterPayment" style="width:auto;min-width:130px;">
                            <option value="">All Payment</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="phResetFilters"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </div>
                </div>

                <div class="pl-bulk-bar" id="phBulkBar">
                    <strong><span id="phSelectedCount">0</span> selected</strong>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="phBulkExport">Export</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="phBulkClear">Clear</button>
                </div>

                <div class="card-body pt-0">
                    <div class="table-responsive purchases-table-scroll">
                        <table class="table table-bordered" id="purchasesTable" width="100%" cellspacing="0" aria-label="Purchases table">
                            <thead>
                                <tr>
                                    <th style="width:36px;"><input type="checkbox" class="form-check-input" id="phSelectAll" aria-label="Select all"></th>
                                    <th>ID</th>
                                    <th>Supplier</th>
                                    <th>Products</th>
                                    <th>Date</th>
                                    <th>Stock</th>
                                    <th>Total Amount</th>
                                    <th>Advance</th>
                                    <th class="text-danger">Due</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($purchases)): ?>
                                    <?php foreach ($purchases as $purchase): ?>
                                        <?php
                                            $po = isset($purchase['purchase_no']) ? (string)$purchase['purchase_no'] : '';
                                            $nt = isset($purchase['notes']) ? (string)$purchase['notes'] : '';
                                            $isReturn = (strpos($po, 'PR-') === 0) || (stripos($nt, '[RETURN]') !== false);
                                            $total = (float)($purchase['total_amount'] ?? 0);
                                            $advance = (float)($purchase['paid_amount'] ?? 0);
                                            $balance = max($total - $advance, 0);
                                            $refund = max($advance - $total, 0);
                                            $payStatus = strtolower((string)($purchase['payment_status'] ?? ''));
                                            $payBadge = 'secondary';
                                            $payLabel = 'N/A';
                                            if ($payStatus === 'paid') { $payBadge = 'success'; $payLabel = 'Paid'; }
                                            else if ($payStatus === 'partial') { $payBadge = 'warning'; $payLabel = 'Partial'; }
                                            else if ($payStatus === 'unpaid' || $balance > 0) { $payBadge = 'danger'; $payLabel = 'Unpaid'; }
                                            $statusRaw = strtolower((string)($purchase['status'] ?? ''));
                                            if ($isReturn) {
                                                $stBadge = 'danger';
                                                $stLabel = 'Return';
                                                $stKey = 'return';
                                            } else if ($statusRaw === 'received') {
                                                $stBadge = 'success';
                                                $stLabel = 'Received';
                                                $stKey = 'received';
                                            } else if ($statusRaw === 'pending') {
                                                $stBadge = 'warning';
                                                $stLabel = 'Pending';
                                                $stKey = 'pending';
                                            } else if ($statusRaw === 'ordered') {
                                                $stBadge = 'info';
                                                $stLabel = 'Ordered';
                                                $stKey = 'ordered';
                                            } else {
                                                $stBadge = 'secondary';
                                                $stLabel = ucfirst($statusRaw ?: 'Unknown');
                                                $stKey = $statusRaw;
                                            }
                                            $supName = htmlspecialchars($purchase['supplier_name'] ?? '');
                                            $prodNames = htmlspecialchars($purchase['product_names'] ?? '');
                                        ?>
                                        <tr class="purchase-row"
                                            data-id="<?php echo (int)$purchase['id']; ?>"
                                            data-supplier="<?php echo htmlspecialchars(strtolower($purchase['supplier_name'] ?? '')); ?>"
                                            data-products="<?php echo htmlspecialchars(strtolower($purchase['product_names'] ?? '')); ?>"
                                            data-status="<?php echo htmlspecialchars($stKey); ?>"
                                            data-payment="<?php echo htmlspecialchars(strtolower($payLabel)); ?>"
                                            data-total="<?php echo $total; ?>">
                                            <td data-label="Select"><input type="checkbox" class="form-check-input ph-row-check" value="<?php echo (int)$purchase['id']; ?>"></td>
                                            <td data-label="ID">#<?php echo $purchase['id']; ?></td>
                                            <td data-label="Supplier"><?php echo $supName; ?></td>
                                            <td class="text-truncate" style="max-width: 260px;" data-label="Products" title="<?php echo $prodNames; ?>">
                                                <?php echo $prodNames; ?>
                                            </td>
                                            <td data-label="Date"><?php echo date('M d, Y', strtotime($purchase['purchase_date'])); ?></td>
                                            <td data-label="Stock"><?php echo (int)($purchase['total_items'] ?? 0); ?></td>
                                            <td data-label="Total Amount"><?php echo formatPrice($total); ?></td>
                                            <td data-label="Advance"><?php echo formatPrice($advance); ?></td>
                                            <td class="text-danger fw-bold" data-label="Due">
                                                <?php echo formatPrice($balance); ?>
                                                <?php if ($refund > 0): ?>
                                                    <div class="small mt-1"><span class="badge bg-success">Refund <?php echo formatPrice($refund); ?></span></div>
                                                <?php endif; ?>
                                            </td>
                                            <td data-label="Payment">
                                                <span class="badge bg-<?php echo $payBadge; ?>"><?php echo $payLabel; ?></span>
                                            </td>
                                            <td data-label="Status">
                                                <span class="badge bg-<?php echo $stBadge; ?>"><?php echo $stLabel; ?></span>
                                            </td>
                                            <td data-label="Actions">
                                                <a href="<?php echo BASE_URL; ?>?controller=purchase&action=show&id=<?php echo $purchase['id']; ?>"
                                                   class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?php echo BASE_URL; ?>?controller=purchase&action=edit&id=<?php echo $purchase['id']; ?>"
                                                   class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo BASE_URL; ?>?controller=purchase&action=delete&id=<?php echo $purchase['id']; ?>"
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this purchase?');">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="12" class="text-center">No purchases found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-3">
            <div class="pl-card mb-3 pl-preview-panel">
                <div class="pl-card-header"><h2 style="font-size:0.95rem;">Quick Stats</h2></div>
                <div class="pl-card-body">
                    <ul class="pl-side-list mb-0">
                        <li><span class="k">Ordered</span><span class="v"><?php echo $ordered; ?></span></li>
                        <li><span class="k">Returns</span><span class="v"><?php echo $returns; ?></span></li>
                        <li><span class="k">Cancelled</span><span class="v"><?php echo $cancelled; ?></span></li>
                        <li><span class="k">Outstanding</span><span class="v"><?php echo formatPrice($outstanding); ?></span></li>
                    </ul>
                </div>
            </div>
            <div class="pl-chart-card mb-3">
                <h3>Status Mix</h3>
                <canvas id="phStatusChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#purchasesTable').DataTable({
        "order": [[4, "desc"]] // Sort by date (shifted by checkbox column)
    });

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

    var search = document.getElementById('phLiveSearch');
    var stF = document.getElementById('phFilterStatus');
    var payF = document.getElementById('phFilterPayment');
    function applyFilter() {
        var q = (search && search.value || '').toLowerCase().trim();
        var st = stF ? stF.value : '';
        var pay = payF ? payF.value : '';
        document.querySelectorAll('#purchasesTable tbody tr.purchase-row').forEach(function(tr) {
            var ok = true;
            var blob = (tr.getAttribute('data-id') + ' ' + (tr.getAttribute('data-supplier') || '') + ' ' + (tr.getAttribute('data-products') || '')).toLowerCase();
            if (q && blob.indexOf(q) === -1) ok = false;
            if (st && (tr.getAttribute('data-status') || '') !== st) ok = false;
            if (pay && (tr.getAttribute('data-payment') || '') !== pay) ok = false;
            tr.style.display = ok ? '' : 'none';
        });
    }
    if (search) search.addEventListener('input', applyFilter);
    if (stF) stF.addEventListener('change', applyFilter);
    if (payF) payF.addEventListener('change', applyFilter);
    var reset = document.getElementById('phResetFilters');
    if (reset) reset.addEventListener('click', function() {
        if (search) search.value = '';
        if (stF) stF.value = '';
        if (payF) payF.value = '';
        applyFilter();
    });

    var bulkBar = document.getElementById('phBulkBar');
    var selCount = document.getElementById('phSelectedCount');
    function updateBulk() {
        var n = document.querySelectorAll('.ph-row-check:checked').length;
        if (selCount) selCount.textContent = String(n);
        if (bulkBar) {
            if (n > 0) bulkBar.classList.add('is-visible');
            else bulkBar.classList.remove('is-visible');
        }
    }
    var selAll = document.getElementById('phSelectAll');
    if (selAll) selAll.addEventListener('change', function() {
        document.querySelectorAll('.ph-row-check').forEach(function(cb) { cb.checked = selAll.checked; });
        updateBulk();
    });
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('ph-row-check')) updateBulk();
    });

    function exportCsv() {
        var lines = ['ID,Supplier,Status,Payment,Total'];
        document.querySelectorAll('#purchasesTable tbody tr.purchase-row').forEach(function(tr) {
            if (tr.style.display === 'none') return;
            lines.push([
                tr.getAttribute('data-id'),
                '"' + (tr.getAttribute('data-supplier') || '').replace(/"/g, '""') + '"',
                tr.getAttribute('data-status'),
                tr.getAttribute('data-payment'),
                tr.getAttribute('data-total')
            ].join(','));
        });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([lines.join('\n')], { type: 'text/csv' }));
        a.download = 'purchases-export.csv';
        a.click();
    }
    var ex = document.getElementById('phExportCsvBtn');
    var bex = document.getElementById('phBulkExport');
    if (ex) ex.addEventListener('click', exportCsv);
    if (bex) bex.addEventListener('click', exportCsv);
    var clr = document.getElementById('phBulkClear');
    if (clr) clr.addEventListener('click', function() {
        document.querySelectorAll('.ph-row-check').forEach(function(cb) { cb.checked = false; });
        if (selAll) selAll.checked = false;
        updateBulk();
    });

    if (typeof Chart !== 'undefined') {
        var ctx = document.getElementById('phStatusChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Received', 'Pending', 'Ordered', 'Returns'],
                    datasets: [{
                        data: [<?php echo $completed; ?>, <?php echo $pending; ?>, <?php echo $ordered; ?>, <?php echo $returns; ?>],
                        backgroundColor: ['#10b981', '#f59e0b', '#3b82f6', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }, cutout: '62%' }
            });
        }
    }
});
</script>
