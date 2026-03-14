<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
    .cust-report {
        --c-primary: #3b82f6;
        --c-success: #198754;
        --c-warning: #f59e0b;
        --c-danger: #dc3545;
        --c-info: #0ea5e9;
        --c-surface: var(--surface-color);
        --c-border: var(--border-color);
        --c-text: var(--text-color);
        --c-muted: var(--muted-color);
    }

    .cust-report .page-header {
        background: linear-gradient(135deg, rgba(14,165,233,0.14) 0%, rgba(59,130,246,0.16) 45%, rgba(25,135,84,0.10) 100%);
        border: 1px solid var(--c-border);
        border-radius: 14px;
        padding: 16px 18px;
        margin-bottom: 16px;
    }

    .cust-report .page-title {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .cust-report .page-subtitle {
        margin: 6px 0 0;
        color: var(--c-muted);
        font-size: 13px;
    }

    .cust-report .panel-card {
        border: 1px solid var(--c-border);
        border-radius: 14px;
        background: var(--c-surface);
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
        overflow: hidden;
    }

    .cust-report .panel-card .card-header {
        background: linear-gradient(180deg, rgba(248,250,252,1) 0%, rgba(241,245,249,1) 100%);
        border-bottom: 1px solid var(--c-border);
        padding: 12px 14px;
        font-weight: 800;
    }

    [data-theme="dark"] .cust-report .panel-card .card-header {
        background: rgba(255,255,255,0.04);
    }

    .cust-report .table {
        margin: 0;
        font-size: 13px;
    }

    .cust-report .table thead th {
        font-size: 11px;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--c-muted);
        border-top: 0;
        border-bottom: 1px solid var(--c-border);
        white-space: nowrap;
    }

    .cust-report .table tbody tr {
        transition: background .12s ease;
    }

    .cust-report .table tbody tr:hover {
        background: rgba(59,130,246,0.05);
    }

    .cust-report .table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .cust-report .form-control:focus,
    .cust-report .btn:focus {
        box-shadow: 0 0 0 .2rem rgba(59,130,246,.20);
    }

    .cust-report .badge-soft {
        background: rgba(59,130,246,0.12);
        color: var(--c-primary);
        border: 1px solid rgba(59,130,246,0.18);
        font-weight: 800;
        padding: 6px 8px;
        border-radius: 999px;
        font-size: 12px;
        white-space: nowrap;
    }

    .cust-report .badge-soft--success { background: rgba(25,135,84,0.12); color: var(--c-success); border-color: rgba(25,135,84,0.18); }
    .cust-report .badge-soft--warning { background: rgba(245,158,11,0.14); color: var(--c-warning); border-color: rgba(245,158,11,0.18); }
    .cust-report .badge-soft--danger  { background: rgba(220,53,69,0.12); color: var(--c-danger); border-color: rgba(220,53,69,0.18); }
    .cust-report .badge-soft--info    { background: rgba(14,165,233,0.14); color: var(--c-info); border-color: rgba(14,165,233,0.18); }

    .cust-report .table-sort {
        cursor: pointer;
        user-select: none;
    }

    .cust-report .table-sort .sort-ind {
        margin-left: 6px;
        opacity: .6;
        font-size: 11px;
    }

    .cust-report .sr-only-focusable:active,
    .cust-report .sr-only-focusable:focus {
        position: static;
        width: auto;
        height: auto;
        margin: 0;
        overflow: visible;
        clip: auto;
        white-space: normal;
    }
</style>

<?php
    $customerDataSafe = is_array($customerData ?? null) ? $customerData : [];
    $fmtMoney = function($v) {
        if (function_exists('formatCurrency')) return formatCurrency($v);
        if (function_exists('formatPrice')) return formatPrice($v);
        return number_format((float)$v, 2);
    };
?>

<a class="sr-only sr-only-focusable" href="#customersMain">Skip to customer report</a>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 cust-report" id="customersMain">
    <div class="page-header d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
        <div class="pr-md-3">
            <h1 class="page-title">Customer Report</h1>
            <p class="page-subtitle">Filter and sort customer performance metrics with a clean, mobile-friendly table.</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex flex-wrap" style="gap:8px;">
            <a href="<?php echo BASE_URL; ?>?controller=report&action=index" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left mr-1"></i>Back</a>
            <a href="<?php echo BASE_URL; ?>?controller=report&action=customers" class="btn btn-outline-primary btn-sm">Reset</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="panel-card">
                <div class="card-header">Filters & Sorting</div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>" method="GET" class="mb-0" id="customerReportFilters">
                        <input type="hidden" name="controller" value="report">
                        <input type="hidden" name="action" value="customers">

                        <div class="form-row">
                            <div class="form-group col-12 col-md-4">
                                <label for="sort_by" class="mb-1">Sort by (server)</label>
                                <select class="form-control" id="sort_by" name="sort_by">
                                    <option value="orders" <?php echo $sortBy == 'orders' ? 'selected' : ''; ?>>Orders (High to Low)</option>
                                    <option value="spent" <?php echo $sortBy == 'spent' ? 'selected' : ''; ?>>Total Spent (High to Low)</option>
                                </select>
                                <small class="text-muted">Applies via page reload for accurate totals.</small>
                            </div>

                            <div class="form-group col-12 col-md-4">
                                <label for="custSearch" class="mb-1">Search (client)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-search"></i></span></div>
                                    <input type="text" class="form-control" id="custSearch" placeholder="Name, email, ID..." aria-label="Search customers">
                                </div>
                                <small class="text-muted">Instant filtering without reloading.</small>
                            </div>

                            <div class="form-group col-12 col-md-4">
                                <label class="mb-1">Quick filters (client)</label>
                                <div class="d-flex flex-wrap" style="gap:8px;">
                                    <input type="number" class="form-control" id="minOrders" placeholder="Min orders" style="max-width: 140px;" min="0" aria-label="Minimum orders">
                                    <input type="number" class="form-control" id="minSpent" placeholder="Min spent" style="max-width: 140px;" min="0" step="0.01" aria-label="Minimum spent">
                                </div>
                                <small class="text-muted">Use to narrow results quickly.</small>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap" style="gap:8px;">
                            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="resetClientFilters">Reset client filters</button>
                            <span class="text-muted align-self-center" style="font-size:12px;">Tip: click table headers to sort locally.</span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="panel-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>Customers</div>
                    <span class="text-muted" style="font-size:12px;" id="custCount"></span>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($customerDataSafe)): ?>
                        <div class="p-3">
                            <div class="alert alert-info mb-0">No customer data available.</div>
                        </div>
                    <?php else: ?>
                        <div class="table-wrap">
                            <table id="reportCustomersTable" class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="table-sort" data-sort-key="id">ID<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="table-sort" data-sort-key="name">Name<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="table-sort" data-sort-key="email">Email<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="text-right table-sort" data-sort-key="orders">Orders<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="text-right table-sort" data-sort-key="spent">Total Spent<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="text-right table-sort" data-sort-key="aov">Avg Order<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="table-sort" data-sort-key="last">Last Order<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                        <th class="table-sort" data-sort-key="reg">Registered<span class="sort-ind" aria-hidden="true">⇅</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($customerDataSafe as $customer):
                                        $id = (int)($customer['id'] ?? 0);
                                        $first = (string)($customer['first_name'] ?? '');
                                        $last = (string)($customer['last_name'] ?? '');
                                        $name = trim($first . ' ' . $last);
                                        $email = (string)($customer['email'] ?? '');
                                        $orders = (int)($customer['order_count'] ?? 0);
                                        $spent = (float)($customer['total_spent'] ?? 0);
                                        $aov = $orders > 0 ? ($spent / $orders) : 0;
                                        $lastOrderDate = !empty($customer['last_order_date']) ? (string)$customer['last_order_date'] : '';
                                        $createdAt = !empty($customer['created_at']) ? (string)$customer['created_at'] : '';

                                        $lastLabel = $lastOrderDate !== '' ? date('M d, Y', strtotime($lastOrderDate)) : 'N/A';
                                        $regLabel = $createdAt !== '' ? date('M d, Y', strtotime($createdAt)) : 'N/A';
                                        $lastSort = $lastOrderDate !== '' ? strtotime($lastOrderDate) : 0;
                                        $regSort = $createdAt !== '' ? strtotime($createdAt) : 0;
                                    ?>
                                        <tr data-orders="<?php echo $orders; ?>" data-spent="<?php echo htmlspecialchars((string)$spent); ?>">
                                            <td data-sort="<?php echo $id; ?>"><?php echo $id; ?></td>
                                            <td data-sort="<?php echo htmlspecialchars($name); ?>">
                                                <a href="<?php echo BASE_URL; ?>?controller=user&action=adminEdit&id=<?php echo $id; ?>">
                                                    <?php echo htmlspecialchars($name !== '' ? $name : ('Customer #' . $id)); ?>
                                                </a>
                                            </td>
                                            <td data-sort="<?php echo htmlspecialchars($email); ?>"><?php echo htmlspecialchars($email); ?></td>
                                            <td class="text-right" data-sort="<?php echo $orders; ?>"><span class="badge-soft badge-soft--info"><?php echo $orders; ?></span></td>
                                            <td class="text-right" data-sort="<?php echo htmlspecialchars((string)$spent); ?>"><?php echo $fmtMoney($spent); ?></td>
                                            <td class="text-right" data-sort="<?php echo htmlspecialchars((string)$aov); ?>"><?php echo $fmtMoney($aov); ?></td>
                                            <td data-sort="<?php echo (int)$lastSort; ?>"><?php echo htmlspecialchars($lastLabel); ?></td>
                                            <td data-sort="<?php echo (int)$regSort; ?>"><?php echo htmlspecialchars($regLabel); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-3">
            <div class="panel-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div>UI Style Guide</div>
                    <span class="text-muted" style="font-size:12px;">Minimal tokens for consistent admin UI</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                            <div class="text-muted" style="font-size:12px; font-weight:800; letter-spacing:.06em; text-transform:uppercase;">Colors</div>
                            <div class="mt-2 d-flex flex-wrap" style="gap:8px;">
                                <span class="badge-soft">Primary</span>
                                <span class="badge-soft badge-soft--success">Success</span>
                                <span class="badge-soft badge-soft--warning">Warning</span>
                                <span class="badge-soft badge-soft--danger">Danger</span>
                                <span class="badge-soft badge-soft--info">Info</span>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                            <div class="text-muted" style="font-size:12px; font-weight:800; letter-spacing:.06em; text-transform:uppercase;">Typography</div>
                            <div class="mt-2">
                                <div style="font-weight:800;">Inter 500–800</div>
                                <div class="text-muted" style="font-size:12px;">Title 18px, table 13px, meta 12px</div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="text-muted" style="font-size:12px; font-weight:800; letter-spacing:.06em; text-transform:uppercase;">Buttons</div>
                            <div class="mt-2 d-flex flex-wrap" style="gap:8px;">
                                <button type="button" class="btn btn-primary btn-sm" disabled>Primary</button>
                                <button type="button" class="btn btn-outline-primary btn-sm" disabled>Outline</button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" disabled>Secondary</button>
                            </div>
                            <div class="text-muted mt-2" style="font-size:12px;">Rounded corners: 14px cards. Spacing: 8px grid.</div>
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

    function applyClientFilters() {
        var table = document.getElementById('reportCustomersTable');
        if (!table) return;

        var q = (document.getElementById('custSearch') && document.getElementById('custSearch').value || '').toLowerCase().trim();
        var minOrders = parseInt((document.getElementById('minOrders') && document.getElementById('minOrders').value) || '', 10);
        var minSpent = parseFloat((document.getElementById('minSpent') && document.getElementById('minSpent').value) || '');
        if (isNaN(minOrders)) minOrders = null;
        if (isNaN(minSpent)) minSpent = null;

        var rows = table.querySelectorAll('tbody tr');
        var visible = 0;
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var txt = (row.textContent || '').toLowerCase();
            var orders = parseInt(row.getAttribute('data-orders') || '0', 10);
            var spent = parseFloat(row.getAttribute('data-spent') || '0');

            var ok = true;
            if (q !== '' && txt.indexOf(q) === -1) ok = false;
            if (ok && minOrders !== null && orders < minOrders) ok = false;
            if (ok && minSpent !== null && spent < minSpent) ok = false;

            row.style.display = ok ? '' : 'none';
            if (ok) visible++;
        }

        var cnt = document.getElementById('custCount');
        if (cnt) cnt.textContent = visible + ' shown';
    }

    makeSortable('reportCustomersTable');

    var onChange = debounce(applyClientFilters, 120);
    var s = document.getElementById('custSearch');
    if (s) s.addEventListener('input', onChange);
    var mo = document.getElementById('minOrders');
    if (mo) mo.addEventListener('input', onChange);
    var ms = document.getElementById('minSpent');
    if (ms) ms.addEventListener('input', onChange);

    var reset = document.getElementById('resetClientFilters');
    if (reset) {
        reset.addEventListener('click', function() {
            if (s) s.value = '';
            if (mo) mo.value = '';
            if (ms) ms.value = '';
            applyClientFilters();
        });
    }

    applyClientFilters();
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
