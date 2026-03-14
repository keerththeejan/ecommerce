<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
    .reports-dash {
        --r-primary: #3b82f6;
        --r-success: #198754;
        --r-warning: #f59e0b;
        --r-danger: #dc3545;
        --r-info: #0ea5e9;
        --r-surface: var(--surface-color);
        --r-border: var(--border-color);
        --r-text: var(--text-color);
        --r-muted: var(--muted-color);
    }

    .reports-dash .page-header {
        background: linear-gradient(135deg, rgba(59,130,246,0.18) 0%, rgba(14,165,233,0.14) 50%, rgba(25,135,84,0.10) 100%);
        border: 1px solid var(--r-border);
        border-radius: 14px;
        padding: 16px 18px;
        margin-bottom: 16px;
    }

    .reports-dash .page-title {
        font-size: 18px;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .reports-dash .page-subtitle {
        margin: 6px 0 0;
        color: var(--r-muted);
        font-size: 13px;
    }

    .reports-dash .kpi-card {
        border: 1px solid var(--r-border);
        border-radius: 14px;
        background: var(--r-surface);
        overflow: hidden;
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
    }

    .reports-dash .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0,0,0,.10);
        border-color: rgba(59,130,246,0.35);
    }

    .reports-dash .kpi-card__top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 14px 14px 10px;
        gap: 10px;
    }

    .reports-dash .kpi-label {
        margin: 0;
        font-size: 12px;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--r-muted);
        font-weight: 700;
    }

    .reports-dash .kpi-value {
        margin: 8px 0 0;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.15;
        color: var(--r-text);
    }

    .reports-dash .kpi-meta {
        margin: 6px 0 0;
        font-size: 12px;
        color: var(--r-muted);
    }

    .reports-dash .kpi-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        background: rgba(59,130,246,0.12);
        color: var(--r-primary);
        border: 1px solid rgba(59,130,246,0.18);
    }
    .reports-dash .kpi-icon--success { background: rgba(25,135,84,0.12); color: var(--r-success); border-color: rgba(25,135,84,0.18); }
    .reports-dash .kpi-icon--warning { background: rgba(245,158,11,0.14); color: var(--r-warning); border-color: rgba(245,158,11,0.18); }
    .reports-dash .kpi-icon--danger  { background: rgba(220,53,69,0.12); color: var(--r-danger); border-color: rgba(220,53,69,0.18); }

    .reports-dash .panel-card {
        border: 1px solid var(--r-border);
        border-radius: 14px;
        background: var(--r-surface);
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
        overflow: hidden;
    }

    .reports-dash .panel-card .card-header {
        background: linear-gradient(180deg, rgba(248,250,252,1) 0%, rgba(241,245,249,1) 100%);
        border-bottom: 1px solid var(--r-border);
        padding: 12px 14px;
        font-weight: 700;
    }

    [data-theme="dark"] .reports-dash .panel-card .card-header {
        background: rgba(255,255,255,0.04);
    }

    .reports-dash .table {
        margin: 0;
        font-size: 13px;
    }

    .reports-dash .table thead th {
        font-size: 11px;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--r-muted);
        border-top: 0;
        border-bottom: 1px solid var(--r-border);
        white-space: nowrap;
    }

    .reports-dash .table tbody tr {
        transition: background .12s ease;
    }

    .reports-dash .table tbody tr:hover {
        background: rgba(59,130,246,0.05);
    }

    .reports-dash .table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .reports-dash .form-control:focus,
    .reports-dash .btn:focus {
        box-shadow: 0 0 0 .2rem rgba(59,130,246,.20);
    }

    .reports-dash .table-sort {
        cursor: pointer;
        user-select: none;
    }

    .reports-dash .table-sort .sort-ind {
        margin-left: 6px;
        opacity: .6;
        font-size: 11px;
    }

    .reports-dash .badge-soft {
        background: rgba(59,130,246,0.12);
        color: var(--r-primary);
        border: 1px solid rgba(59,130,246,0.18);
        font-weight: 700;
        padding: 6px 8px;
        border-radius: 999px;
        font-size: 12px;
    }

    .reports-dash .badge-soft--success { background: rgba(25,135,84,0.12); color: var(--r-success); border-color: rgba(25,135,84,0.18); }
    .reports-dash .badge-soft--warning { background: rgba(245,158,11,0.14); color: var(--r-warning); border-color: rgba(245,158,11,0.18); }
    .reports-dash .badge-soft--danger  { background: rgba(220,53,69,0.12); color: var(--r-danger); border-color: rgba(220,53,69,0.18); }
    .reports-dash .badge-soft--info    { background: rgba(14,165,233,0.14); color: var(--r-info); border-color: rgba(14,165,233,0.18); }

    .reports-dash .sr-only-focusable:active,
    .reports-dash .sr-only-focusable:focus {
        position: static;
        width: auto;
        height: auto;
        margin: 0;
        overflow: visible;
        clip: auto;
        white-space: normal;
    }

    @media (max-width: 575.98px) {
        .reports-dash .page-header {
            padding: 14px 14px;
        }
        .reports-dash .kpi-value {
            font-size: 20px;
        }
    }
</style>

<?php
    $totalSales = isset($salesSummary['total_sales']) ? (float)$salesSummary['total_sales'] : 0;
    $totalOrders = isset($salesSummary['total_orders']) ? (int)$salesSummary['total_orders'] : 0;
    $avgOrderValue = isset($salesSummary['avg_order_value']) ? (float)$salesSummary['avg_order_value'] : 0;
    $pendingOrders = isset($salesSummary['pending_orders']) ? (int)$salesSummary['pending_orders'] : 0;
    $processingOrders = isset($salesSummary['processing_orders']) ? (int)$salesSummary['processing_orders'] : 0;
    $completedOrders = isset($salesSummary['completed_orders']) ? (int)$salesSummary['completed_orders'] : 0;
    $cancelledOrders = isset($salesSummary['cancelled_orders']) ? (int)$salesSummary['cancelled_orders'] : 0;
    $topProductsSafe = is_array($topProducts ?? null) ? $topProducts : [];
    $recentOrdersSafe = is_array($recentOrders ?? null) ? $recentOrders : [];
    $customerStatsSafe = is_array($customerStats ?? null) ? $customerStats : [];

    $fmtMoney = function($v) {
        if (function_exists('formatCurrency')) return formatCurrency($v);
        if (function_exists('formatPrice')) return formatPrice($v);
        return number_format((float)$v, 2);
    };

    $kpiNote = 'Last updated: ' . date('Y-m-d H:i');
?>

<a class="sr-only sr-only-focusable" href="#reportsMain">Skip to reports content</a>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 reports-dash" id="reportsMain">
    <div class="page-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <div class="pr-md-3">
            <h1 class="page-title">Reports Dashboard</h1>
            <p class="page-subtitle">Sales, orders, customers, and product performance at a glance.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex flex-wrap" style="gap:8px;">
            <a href="<?php echo BASE_URL; ?>?controller=report&action=sales" class="btn btn-primary btn-sm"><i class="fas fa-chart-line mr-1"></i>Sales Report</a>
            <a href="<?php echo BASE_URL; ?>?controller=report&action=products" class="btn btn-outline-primary btn-sm"><i class="fas fa-box mr-1"></i>Product Report</a>
            <a href="<?php echo BASE_URL; ?>?controller=report&action=customers" class="btn btn-outline-secondary btn-sm"><i class="fas fa-users mr-1"></i>Customer Report</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="kpi-card h-100" role="group" aria-label="Total Sales">
                <div class="kpi-card__top">
                    <div>
                        <p class="kpi-label">Total Sales</p>
                        <div class="kpi-value"><?php echo $fmtMoney($totalSales); ?></div>
                        <div class="kpi-meta"><?php echo htmlspecialchars($kpiNote); ?></div>
                    </div>
                    <div class="kpi-icon" aria-hidden="true"><i class="fas fa-dollar-sign"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="kpi-card h-100" role="group" aria-label="Total Orders">
                <div class="kpi-card__top">
                    <div>
                        <p class="kpi-label">Total Orders</p>
                        <div class="kpi-value"><?php echo (int)$totalOrders; ?></div>
                        <div class="kpi-meta">All paid orders counted in sales KPI.</div>
                    </div>
                    <div class="kpi-icon kpi-icon--success" aria-hidden="true"><i class="fas fa-shopping-cart"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="kpi-card h-100" role="group" aria-label="Average Order Value">
                <div class="kpi-card__top">
                    <div>
                        <p class="kpi-label">Average Order Value</p>
                        <div class="kpi-value"><?php echo $fmtMoney($avgOrderValue); ?></div>
                        <div class="kpi-meta">Avg across paid orders.</div>
                    </div>
                    <div class="kpi-icon kpi-icon--warning" aria-hidden="true"><i class="fas fa-receipt"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="kpi-card h-100" role="group" aria-label="Pending Orders">
                <div class="kpi-card__top">
                    <div>
                        <p class="kpi-label">Pending Orders</p>
                        <div class="kpi-value"><?php echo (int)$pendingOrders; ?></div>
                        <div class="kpi-meta">Awaiting action.</div>
                    </div>
                    <div class="kpi-icon kpi-icon--danger" aria-hidden="true"><i class="fas fa-hourglass-half"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-7 mb-3">
            <div class="panel-card h-100">
                <div class="card-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                    <div>Top Selling Products</div>
                    <div class="mt-2 mt-md-0 d-flex" style="gap:8px;">
                        <div class="input-group input-group-sm" style="min-width: 220px;">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                            <input type="text" class="form-control" id="topProductsSearch" placeholder="Search products..." aria-label="Search top products">
                        </div>
                        <a href="<?php echo BASE_URL; ?>?controller=report&action=products" class="btn btn-outline-primary btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($topProductsSafe)): ?>
                        <div class="p-3">
                            <div class="alert alert-info mb-0">No product sales data available.</div>
                        </div>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table class="table table-hover mb-0" id="topProductsTable">
                                <thead>
                                    <tr>
                                        <th class="table-sort" data-sort-key="name">Product<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="table-sort" data-sort-key="category">Category<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="text-right table-sort" data-sort-key="price">Price<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="text-right table-sort" data-sort-key="sold">Sold<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($topProductsSafe as $p): ?>
                                        <tr>
                                            <td data-sort="<?php echo htmlspecialchars((string)($p['name'] ?? '')); ?>"><?php echo htmlspecialchars((string)($p['name'] ?? '')); ?></td>
                                            <td data-sort="<?php echo htmlspecialchars((string)($p['category_name'] ?? '')); ?>"><?php echo htmlspecialchars((string)($p['category_name'] ?? '')); ?></td>
                                            <td class="text-right" data-sort="<?php echo htmlspecialchars((string)($p['price'] ?? 0)); ?>"><?php echo $fmtMoney($p['price'] ?? 0); ?></td>
                                            <td class="text-right" data-sort="<?php echo htmlspecialchars((string)($p['total_sold'] ?? 0)); ?>"><span class="badge-soft badge-soft--info"><?php echo (int)($p['total_sold'] ?? 0); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5 mb-3">
            <div class="panel-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>Order Status</div>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-outline-secondary btn-sm">Manage</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-3 mb-sm-0">
                            <canvas id="orderStatusChart" height="160" aria-label="Order status chart" role="img"></canvas>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-muted" style="font-size:12px;">Pending</div>
                                <div><span class="badge-soft badge-soft--warning"><?php echo (int)$pendingOrders; ?></span></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-muted" style="font-size:12px;">Processing</div>
                                <div><span class="badge-soft badge-soft--info"><?php echo (int)$processingOrders; ?></span></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="text-muted" style="font-size:12px;">Completed</div>
                                <div><span class="badge-soft badge-soft--success"><?php echo (int)$completedOrders; ?></span></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted" style="font-size:12px;">Cancelled</div>
                                <div><span class="badge-soft badge-soft--danger"><?php echo (int)$cancelledOrders; ?></span></div>
                            </div>
                            <div class="mt-3">
                                <div class="alert alert-light mb-0" role="status" aria-live="polite" style="border:1px solid var(--border-color);">
                                    Auto-updates as orders are processed. Refresh the page to sync.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-7 mb-3">
            <div class="panel-card h-100">
                <div class="card-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                    <div>Recent Orders</div>
                    <div class="mt-2 mt-md-0 d-flex" style="gap:8px;">
                        <div class="input-group input-group-sm" style="min-width: 220px;">
                            <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                            <input type="text" class="form-control" id="recentOrdersSearch" placeholder="Search orders..." aria-label="Search recent orders">
                        </div>
                        <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($recentOrdersSafe)): ?>
                        <div class="p-3">
                            <div class="alert alert-info mb-0">No recent orders.</div>
                        </div>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table class="table table-hover mb-0" id="recentOrdersTable">
                                <thead>
                                    <tr>
                                        <th class="table-sort" data-sort-key="id">Order ID<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="table-sort" data-sort-key="customer">Customer<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="text-right table-sort" data-sort-key="amount">Amount<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="table-sort" data-sort-key="status">Status<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recentOrdersSafe as $o):
                                        $oid = (int)($o['id'] ?? 0);
                                        $cust = trim((string)($o['first_name'] ?? '') . ' ' . (string)($o['last_name'] ?? ''));
                                        $amount = (float)($o['total_amount'] ?? 0);
                                        $st = (string)($o['status'] ?? '');

                                        $badge = 'badge-soft';
                                        if ($st === 'pending') $badge .= ' badge-soft--warning';
                                        else if ($st === 'processing') $badge .= ' badge-soft--info';
                                        else if ($st === 'completed' || $st === 'delivered') $badge .= ' badge-soft--success';
                                        else if ($st === 'cancelled') $badge .= ' badge-soft--danger';
                                    ?>
                                        <tr>
                                            <td data-sort="<?php echo $oid; ?>"><a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $oid; ?>">#<?php echo $oid; ?></a></td>
                                            <td data-sort="<?php echo htmlspecialchars($cust); ?>"><?php echo htmlspecialchars($cust); ?></td>
                                            <td class="text-right" data-sort="<?php echo htmlspecialchars((string)$amount); ?>"><?php echo $fmtMoney($amount); ?></td>
                                            <td data-sort="<?php echo htmlspecialchars($st); ?>"><span class="<?php echo htmlspecialchars($badge); ?>"><?php echo htmlspecialchars(ucfirst($st)); ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5 mb-3">
            <div class="panel-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>Customer Statistics</div>
                    <a href="<?php echo BASE_URL; ?>?controller=report&action=customers" class="btn btn-outline-primary btn-sm">View</a>
                </div>
                <div class="card-body">
                    <?php
                        $totalCustomers = isset($customerStatsSafe['total_customers']) ? (int)$customerStatsSafe['total_customers'] : 0;
                        $newCustomers = isset($customerStatsSafe['new_customers']) ? (int)$customerStatsSafe['new_customers'] : 0;
                        $activeCustomers = isset($customerStatsSafe['active_customers']) ? (int)$customerStatsSafe['active_customers'] : 0;
                    ?>
                    <div class="row">
                        <div class="col-12 col-sm-4 mb-3 mb-sm-0 text-center">
                            <div class="kpi-value" style="font-size:20px; margin:0;"><?php echo $totalCustomers; ?></div>
                            <div class="text-muted" style="font-size:12px;">Total</div>
                        </div>
                        <div class="col-12 col-sm-4 mb-3 mb-sm-0 text-center">
                            <div class="kpi-value" style="font-size:20px; margin:0;"><?php echo $newCustomers; ?></div>
                            <div class="text-muted" style="font-size:12px;">New (30d)</div>
                        </div>
                        <div class="col-12 col-sm-4 text-center">
                            <div class="kpi-value" style="font-size:20px; margin:0;"><?php echo $activeCustomers; ?></div>
                            <div class="text-muted" style="font-size:12px;">Active</div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <div class="text-muted" style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase;">Top Products (Units Sold)</div>
                        <div class="mt-2">
                            <canvas id="topProductsChart" height="150" aria-label="Top products chart" role="img"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="panel-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>UI Style Guide</div>
                    <span class="text-muted" style="font-size:12px;">Use these tokens for consistent UI across admin pages</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                            <div class="text-muted" style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase;">Colors</div>
                            <div class="mt-2 d-flex flex-wrap" style="gap:8px;">
                                <span class="badge-soft">Primary</span>
                                <span class="badge-soft badge-soft--success">Success</span>
                                <span class="badge-soft badge-soft--warning">Warning</span>
                                <span class="badge-soft badge-soft--danger">Danger</span>
                                <span class="badge-soft badge-soft--info">Info</span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                            <div class="text-muted" style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase;">Typography</div>
                            <div class="mt-2">
                                <div style="font-weight:800;">Inter 600–800</div>
                                <div class="text-muted" style="font-size:12px;">Titles 18px, section headers 14px, table text 13px</div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="text-muted" style="font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase;">Buttons</div>
                            <div class="mt-2 d-flex flex-wrap" style="gap:8px;">
                                <button type="button" class="btn btn-primary btn-sm" disabled>Primary</button>
                                <button type="button" class="btn btn-outline-primary btn-sm" disabled>Outline</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Secondary</button>
                            </div>
                            <div class="text-muted mt-2" style="font-size:12px;">Rounded corners: 14px cards, 10–12px controls. Spacing: 8px grid.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function debounce(fn, delay) {
        var t = null;
        return function() {
            var args = arguments;
            clearTimeout(t);
            t = setTimeout(function() { fn.apply(null, args); }, delay);
        };
    }

    function filterTable(tableId, query) {
        var table = document.getElementById(tableId);
        if (!table) return;
        var q = (query || '').toLowerCase().trim();
        var rows = table.querySelectorAll('tbody tr');
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var txt = (row.textContent || '').toLowerCase();
            row.style.display = (q === '' || txt.indexOf(q) !== -1) ? '' : 'none';
        }
    }

    function getCellSortValue(td) {
        if (!td) return '';
        var v = td.getAttribute('data-sort');
        if (v === null || v === undefined) v = td.textContent || '';
        v = String(v).trim();
        var n = parseFloat(v);
        if (!isNaN(n) && v.match(/^[-+]?\d*(\.\d+)?$/)) return n;
        return v.toLowerCase();
    }

    function makeSortable(tableId) {
        var table = document.getElementById(tableId);
        if (!table) return;
        var headers = table.querySelectorAll('thead th.table-sort');
        var state = { key: null, dir: 'asc' };

        function sortByIndex(idx) {
            var tbody = table.querySelector('tbody');
            if (!tbody) return;
            var rows = Array.prototype.slice.call(tbody.querySelectorAll('tr'));
            rows.sort(function(a, b) {
                var av = getCellSortValue(a.children[idx]);
                var bv = getCellSortValue(b.children[idx]);
                if (typeof av === 'number' && typeof bv === 'number') {
                    return state.dir === 'asc' ? (av - bv) : (bv - av);
                }
                if (av < bv) return state.dir === 'asc' ? -1 : 1;
                if (av > bv) return state.dir === 'asc' ? 1 : -1;
                return 0;
            });
            rows.forEach(function(r) { tbody.appendChild(r); });
        }

        for (var i = 0; i < headers.length; i++) {
            (function(i2) {
                headers[i2].addEventListener('click', function() {
                    var key = headers[i2].getAttribute('data-sort-key') || String(i2);
                    if (state.key === key) state.dir = state.dir === 'asc' ? 'desc' : 'asc';
                    else { state.key = key; state.dir = 'asc'; }
                    sortByIndex(i2);
                });
            })(i);
        }
    }

    var topSearch = document.getElementById('topProductsSearch');
    if (topSearch) {
        topSearch.addEventListener('input', debounce(function(e) {
            filterTable('topProductsTable', e.target.value);
        }, 120));
    }
    var ordersSearch = document.getElementById('recentOrdersSearch');
    if (ordersSearch) {
        ordersSearch.addEventListener('input', debounce(function(e) {
            filterTable('recentOrdersTable', e.target.value);
        }, 120));
    }

    makeSortable('topProductsTable');
    makeSortable('recentOrdersTable');

    try {
        if (window.Chart) {
            var statusEl = document.getElementById('orderStatusChart');
            if (statusEl) {
                var pending = <?php echo (int)$pendingOrders; ?>;
                var processing = <?php echo (int)$processingOrders; ?>;
                var completed = <?php echo (int)$completedOrders; ?>;
                var cancelled = <?php echo (int)$cancelledOrders; ?>;
                new Chart(statusEl.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
                        datasets: [{
                            data: [pending, processing, completed, cancelled],
                            backgroundColor: ['#f59e0b', '#0ea5e9', '#198754', '#dc3545'],
                            borderColor: 'rgba(255,255,255,0.25)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: true }
                        },
                        cutout: '62%'
                    }
                });
            }

            var topEl = document.getElementById('topProductsChart');
            if (topEl) {
                var labels = [];
                var values = [];
                <?php foreach ($topProductsSafe as $p): ?>
                    labels.push(<?php echo json_encode((string)($p['name'] ?? '')); ?>);
                    values.push(<?php echo (int)($p['total_sold'] ?? 0); ?>);
                <?php endforeach; ?>

                new Chart(topEl.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Units Sold',
                            data: values,
                            backgroundColor: 'rgba(59,130,246,0.35)',
                            borderColor: 'rgba(59,130,246,0.75)',
                            borderWidth: 1,
                            borderRadius: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 6 } },
                            y: { beginAtZero: true, ticks: { precision: 0 } }
                        }
                    }
                });
            }
        }
    } catch (e) {
        // ignore
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
