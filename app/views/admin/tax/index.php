<?php
/**
 * Tax Management — Enterprise Finance UI (visual layer only)
 * Preserves: #taxTable, edit/delete links, flash messages, tax rate object fields.
 */
require_once APP_PATH . 'views/admin/layouts/header.php';

$taxRates = $taxRates ?? ($data['taxRates'] ?? []);
if (!is_array($taxRates) && !($taxRates instanceof Traversable)) {
    $taxRates = [];
}
$taxRates = is_array($taxRates) ? $taxRates : iterator_to_array($taxRates);

$totalRules = count($taxRates);
$activeCount = 0;
$inactiveCount = 0;
$rateSum = 0.0;
foreach ($taxRates as $taxRate) {
    $active = is_object($taxRate) ? !empty($taxRate->is_active) : !empty($taxRate['is_active']);
    $rate = (float)(is_object($taxRate) ? ($taxRate->rate ?? 0) : ($taxRate['rate'] ?? 0));
    $rateSum += $rate;
    if ($active) $activeCount++;
    else $inactiveCount++;
}
$avgRate = $totalRules > 0 ? round($rateSum / $totalRules, 2) : 0;
$activePct = $totalRules > 0 ? round(($activeCount / $totalRules) * 100) : 0;

// Heuristic type labels from name (UI only)
if (!function_exists('tx_type_from_name')) {
    function tx_type_from_name($name) {
        $n = strtolower((string)$name);
        if (strpos($n, 'gst') !== false) return 'GST';
        if (strpos($n, 'vat') !== false) return 'VAT';
        if (strpos($n, 'purchase') !== false) return 'Purchase Tax';
        if (strpos($n, 'sales') !== false || strpos($n, 'sale') !== false) return 'Sales Tax';
        return 'Tax';
    }
}
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/tax-list.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid tax-list-page py-3 py-md-4 px-2 px-sm-3" id="taxAdminPage">
    <div class="tx-toast-host" id="txToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="tx-header">
        <div>
            <nav class="tx-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span>›</span>
                <span>Finance</span>
                <span>›</span>
                <span aria-current="page">Tax Management</span>
            </nav>
            <h1 class="tx-title">Tax Management</h1>
            <p class="tx-subtitle">Configure tax rates used across sales, purchases, POS, and invoices.</p>
        </div>
        <div class="tx-actions">
            <a href="<?php echo BASE_URL; ?>?controller=tax&action=index" class="btn btn-outline-secondary tx-btn" title="Refresh">
                <i class="bi bi-arrow-clockwise"></i><span class="d-none d-md-inline">Refresh</span>
            </a>
            <button type="button" class="btn btn-outline-secondary tx-btn" onclick="window.print()" title="Print">
                <i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span>
            </button>
            <button type="button" class="btn btn-outline-secondary tx-btn" id="txExportCsvBtn" title="Excel">
                <i class="bi bi-file-earmark-spreadsheet"></i><span class="d-none d-lg-inline">Excel</span>
            </button>
            <button type="button" class="btn btn-outline-secondary tx-btn" onclick="window.print()" title="PDF">
                <i class="bi bi-filetype-pdf"></i><span class="d-none d-lg-inline">PDF</span>
            </button>
            <button type="button" class="btn btn-outline-secondary tx-btn" id="txImportBtn" title="Import (UI)">
                <i class="bi bi-upload"></i><span class="d-none d-xl-inline">Import</span>
            </button>
            <a href="<?php echo BASE_URL; ?>?controller=tax&action=add" class="btn tx-btn tx-btn-primary">
                <i class="bi bi-plus-lg"></i><span>Add Tax</span>
            </a>
        </div>
    </div>

    <?php flash('tax_success'); ?>
    <?php flash('tax_error'); ?>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-3">
            <div class="tx-stat s1"><div class="icon"><i class="bi bi-percent"></i></div><div><div class="label">Total Tax Rules</div><div class="value" data-counter="<?php echo $totalRules; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="tx-stat s2"><div class="icon"><i class="bi bi-check-circle"></i></div><div><div class="label">Active</div><div class="value" data-counter="<?php echo $activeCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="tx-stat s3"><div class="icon"><i class="bi bi-x-circle"></i></div><div><div class="label">Inactive</div><div class="value" data-counter="<?php echo $inactiveCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="tx-stat s4"><div class="icon"><i class="bi bi-graph-up"></i></div><div><div class="label">Avg Rate %</div><div class="value" data-counter="<?php echo (int)round($avgRate); ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="tx-stat s5"><div class="icon"><i class="bi bi-pie-chart"></i></div><div><div class="label">Active Rate %</div><div class="value" data-counter="<?php echo $activePct; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="tx-stat s6"><div class="icon"><i class="bi bi-cart3"></i></div><div><div class="label">Sales Tax Rules</div><div class="value" data-counter="<?php echo $totalRules; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="tx-stat s7"><div class="icon"><i class="bi bi-box-seam"></i></div><div><div class="label">Purchase Ready</div><div class="value" data-counter="<?php echo $activeCount; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="tx-stat s8"><div class="icon"><i class="bi bi-receipt"></i></div><div><div class="label">Invoice Ready</div><div class="value" data-counter="<?php echo $activeCount; ?>">0</div></div></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-9">
            <div class="tx-card">
                <div class="tx-toolbar">
                    <div class="d-flex flex-wrap gap-2 align-items-center" style="flex:1;">
                        <div class="input-group input-group-sm" style="max-width:320px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="search" class="form-control" id="txLiveSearch" placeholder="Search tax name or rate…" aria-label="Search tax rates" autocomplete="off">
                            <button type="button" class="btn btn-outline-secondary" id="txSearchClear">Clear</button>
                        </div>
                        <select class="form-select form-select-sm" id="txFilterStatus" style="width:auto;min-width:120px;" aria-label="Filter by status">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <select class="form-select form-select-sm" id="txPageSize" style="width:auto;" aria-label="Rows per page">
                            <option value="10">10 / page</option>
                            <option value="25" selected>25 / page</option>
                            <option value="50">50 / page</option>
                            <option value="all">All</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="txResetFilters" title="Reset filters"><i class="bi bi-arrow-counterclockwise"></i></button>
                        <span class="small text-muted" id="txResultCount"></span>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Columns">
                            <i class="bi bi-layout-three-columns"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 tx-col-toggle" data-col="1" checked> Name</label></li>
                            <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 tx-col-toggle" data-col="2" checked> Rate</label></li>
                            <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 tx-col-toggle" data-col="3" checked> Type</label></li>
                            <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 tx-col-toggle" data-col="4" checked> Status</label></li>
                            <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 tx-col-toggle" data-col="5" checked> Actions</label></li>
                        </ul>
                    </div>
                </div>

                <div class="tx-bulk-bar" id="txBulkBar" aria-live="polite">
                    <strong><span id="txSelectedCount">0</span> selected</strong>
                    <button type="button" class="btn btn-sm btn-outline-success" id="txBulkActivate">Activate</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="txBulkDeactivate">Deactivate</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="txBulkExport">Export</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="txBulkDelete">Delete</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="txBulkClear">Clear</button>
                </div>

                <div class="card-body p-0">
                    <?php if (empty($taxRates)): ?>
                        <div class="tx-empty text-center p-4">
                            <i class="bi bi-percent d-block mb-2" style="font-size:2rem;opacity:.4;"></i>
                            <p class="text-muted mb-2">No tax rates found. Add your first tax rate to get started.</p>
                            <a href="<?php echo BASE_URL; ?>?controller=tax&action=add" class="btn tx-btn tx-btn-primary btn-sm">Add Tax</a>
                        </div>
                    <?php else: ?>
                        <div class="tax-table-scroll table-responsive">
                            <table id="taxTable" class="table table-hover mb-0" aria-label="Tax rates table">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;">
                                            <input type="checkbox" class="form-check-input" id="txSelectAll" aria-label="Select all tax rates">
                                        </th>
                                        <th>Name</th>
                                        <th class="text-end">Rate (%)</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($taxRates as $taxRate):
                                        $id = is_object($taxRate) ? (int)$taxRate->id : (int)($taxRate['id'] ?? 0);
                                        $name = is_object($taxRate) ? (string)$taxRate->name : (string)($taxRate['name'] ?? '');
                                        $rate = (float)(is_object($taxRate) ? $taxRate->rate : ($taxRate['rate'] ?? 0));
                                        $isActive = is_object($taxRate) ? !empty($taxRate->is_active) : !empty($taxRate['is_active']);
                                        $typeLabel = tx_type_from_name($name);
                                    ?>
                                        <tr class="tax-row"
                                            data-id="<?php echo $id; ?>"
                                            data-name="<?php echo htmlspecialchars(strtolower($name)); ?>"
                                            data-rate="<?php echo $rate; ?>"
                                            data-status="<?php echo $isActive ? 'active' : 'inactive'; ?>"
                                            data-type="<?php echo htmlspecialchars(strtolower($typeLabel)); ?>">
                                            <td data-label="Select" onclick="event.stopPropagation();">
                                                <input type="checkbox" class="form-check-input tx-row-check" value="<?php echo $id; ?>" aria-label="Select <?php echo htmlspecialchars($name); ?>">
                                            </td>
                                            <td data-label="Name">
                                                <div class="fw-semibold"><?php echo htmlspecialchars($name); ?></div>
                                                <div class="small text-muted">ID #<?php echo $id; ?></div>
                                            </td>
                                            <td class="text-end" data-label="Rate (%)">
                                                <span class="tx-rate"><?php echo number_format($rate, 2); ?>%</span>
                                            </td>
                                            <td data-label="Type">
                                                <span class="badge bg-info text-dark"><?php echo htmlspecialchars($typeLabel); ?></span>
                                            </td>
                                            <td data-label="Status">
                                                <span class="badge bg-<?php echo $isActive ? 'success' : 'secondary'; ?>">
                                                    <?php echo $isActive ? 'Active' : 'Inactive'; ?>
                                                </span>
                                            </td>
                                            <td class="text-end" data-label="Actions" onclick="event.stopPropagation();">
                                                <button type="button" class="btn btn-sm btn-outline-secondary tx-preview-btn me-1"
                                                    data-id="<?php echo $id; ?>"
                                                    data-name="<?php echo htmlspecialchars($name); ?>"
                                                    data-rate="<?php echo number_format($rate, 2); ?>"
                                                    data-status="<?php echo $isActive ? 'Active' : 'Inactive'; ?>"
                                                    data-type="<?php echo htmlspecialchars($typeLabel); ?>"
                                                    title="Quick view">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <a href="<?php echo BASE_URL; ?>?controller=tax&action=edit&id=<?php echo $id; ?>"
                                                   class="btn btn-sm btn-outline-primary me-1"
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo BASE_URL; ?>?controller=tax&action=delete&id=<?php echo $id; ?>"
                                                      method="POST"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this tax rate?');">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 p-3 border-top">
                            <div class="small text-muted" id="txPageInfo">Showing 0–0 of 0</div>
                            <nav aria-label="Tax pagination">
                                <ul class="pagination pagination-sm mb-0" id="txPagination"></ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-3">
            <div class="tx-card tx-preview-panel mb-3" id="txPreviewCard">
                <div class="tx-card-header">
                    <h2 style="font-size:0.95rem;">Tax Preview</h2>
                </div>
                <div class="tx-card-body" id="txPreviewBody">
                    <p class="text-muted small mb-0">Select a tax row or click preview to inspect rate details.</p>
                </div>
            </div>
            <div class="tx-chart-card mb-3">
                <h3>Status Mix</h3>
                <canvas id="txStatusChart" height="180" aria-label="Tax status chart"></canvas>
            </div>
            <div class="tx-card">
                <div class="tx-card-header"><h2 style="font-size:0.95rem;">Quick Tips</h2></div>
                <div class="tx-card-body">
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-1">Active rates can apply on POS and invoices.</li>
                        <li class="mb-1">Keep rates between 0 and 100%.</li>
                        <li>Use clear names (e.g. GST 18%, VAT 5%).</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function() {
    function showToast(msg, type) {
        var host = document.getElementById('txToastHost');
        if (!host) return;
        var t = document.createElement('div');
        t.className = 'tx-toast ' + (type || 'info');
        t.setAttribute('role', 'status');
        t.textContent = msg;
        host.appendChild(t);
        setTimeout(function() {
            t.style.opacity = '0';
            setTimeout(function() { t.remove(); }, 300);
        }, 2800);
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

    var table = document.getElementById('taxTable');
    var tbody = table ? table.querySelector('tbody') : null;
    var allRows = tbody ? Array.prototype.slice.call(tbody.querySelectorAll('tr.tax-row')) : [];
    var searchInput = document.getElementById('txLiveSearch');
    var statusFilter = document.getElementById('txFilterStatus');
    var pageSizeSel = document.getElementById('txPageSize');
    var pageInfo = document.getElementById('txPageInfo');
    var pagination = document.getElementById('txPagination');
    var resultCount = document.getElementById('txResultCount');
    var selectAll = document.getElementById('txSelectAll');
    var bulkBar = document.getElementById('txBulkBar');
    var selectedCountEl = document.getElementById('txSelectedCount');
    var currentPage = 1;

    function getFiltered() {
        var q = (searchInput && searchInput.value || '').toLowerCase().trim();
        var st = statusFilter ? statusFilter.value : '';
        return allRows.filter(function(row) {
            var name = row.getAttribute('data-name') || '';
            var rate = row.getAttribute('data-rate') || '';
            var status = row.getAttribute('data-status') || '';
            var type = row.getAttribute('data-type') || '';
            if (q && name.indexOf(q) === -1 && rate.indexOf(q) === -1 && type.indexOf(q) === -1) return false;
            if (st && status !== st) return false;
            return true;
        });
    }

    function updateBulkBar() {
        var n = tbody ? tbody.querySelectorAll('.tx-row-check:checked').length : 0;
        if (selectedCountEl) selectedCountEl.textContent = String(n);
        if (bulkBar) {
            if (n > 0) bulkBar.classList.add('is-visible');
            else bulkBar.classList.remove('is-visible');
        }
    }

    function renderTable() {
        if (!tbody || !allRows.length) return;
        var filtered = getFiltered();
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
            for (var p = 1; p <= Math.min(totalPages, 7); p++) addPage(String(p), p, false, p === currentPage);
            addPage('›', Math.min(totalPages, currentPage + 1), currentPage >= totalPages, false);
        }
        updateBulkBar();
    }

    if (searchInput) searchInput.addEventListener('input', function() { currentPage = 1; renderTable(); });
    if (statusFilter) statusFilter.addEventListener('change', function() { currentPage = 1; renderTable(); });
    if (pageSizeSel) pageSizeSel.addEventListener('change', function() { currentPage = 1; renderTable(); });
    var clearBtn = document.getElementById('txSearchClear');
    if (clearBtn) clearBtn.addEventListener('click', function() {
        if (searchInput) searchInput.value = '';
        currentPage = 1;
        renderTable();
    });
    var resetBtn = document.getElementById('txResetFilters');
    if (resetBtn) resetBtn.addEventListener('click', function() {
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        if (pageSizeSel) pageSizeSel.value = '25';
        currentPage = 1;
        renderTable();
        showToast('Filters reset', 'info');
    });

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            allRows.filter(function(r) { return r.style.display !== 'none'; }).forEach(function(r) {
                var cb = r.querySelector('.tx-row-check');
                if (cb) cb.checked = selectAll.checked;
            });
            updateBulkBar();
        });
    }
    if (tbody) {
        tbody.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('tx-row-check')) updateBulkBar();
        });
    }

    document.querySelectorAll('.tx-col-toggle').forEach(function(cb) {
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
        var lines = ['ID,Name,Rate,Status,Type'];
        rows.forEach(function(r) {
            lines.push([
                r.getAttribute('data-id'),
                '"' + (r.querySelector('[data-label="Name"] .fw-semibold') || {}).textContent + '"',
                r.getAttribute('data-rate'),
                r.getAttribute('data-status'),
                r.getAttribute('data-type')
            ].join(','));
        });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(new Blob([lines.join('\n')], { type: 'text/csv' }));
        a.download = 'tax-rates-export.csv';
        a.click();
    }

    var exportBtn = document.getElementById('txExportCsvBtn');
    if (exportBtn) exportBtn.addEventListener('click', function() {
        exportCsv(getFiltered());
        showToast('Exported tax rates to CSV', 'success');
    });
    var bulkExport = document.getElementById('txBulkExport');
    if (bulkExport) bulkExport.addEventListener('click', function() {
        exportCsv(allRows.filter(function(r) {
            var cb = r.querySelector('.tx-row-check');
            return cb && cb.checked;
        }));
        showToast('Exported selected tax rates', 'success');
    });

    function bulkHint(msg) {
        showToast(msg + ' — use Edit / Delete on each tax rule (no bulk API).', 'warning');
    }
    var ba = document.getElementById('txBulkActivate');
    var bd = document.getElementById('txBulkDeactivate');
    var bdel = document.getElementById('txBulkDelete');
    var bclear = document.getElementById('txBulkClear');
    if (ba) ba.addEventListener('click', function() { bulkHint('Bulk activate'); });
    if (bd) bd.addEventListener('click', function() { bulkHint('Bulk deactivate'); });
    if (bdel) bdel.addEventListener('click', function() { bulkHint('Bulk delete'); });
    if (bclear) bclear.addEventListener('click', function() {
        allRows.forEach(function(r) { var cb = r.querySelector('.tx-row-check'); if (cb) cb.checked = false; });
        if (selectAll) selectAll.checked = false;
        updateBulkBar();
    });
    var importBtn = document.getElementById('txImportBtn');
    if (importBtn) importBtn.addEventListener('click', function() {
        showToast('Import is a UI preview — use Add Tax to create rates.', 'info');
    });

    document.addEventListener('click', function(e) {
        var btn = e.target.closest('.tx-preview-btn');
        if (!btn) return;
        var body = document.getElementById('txPreviewBody');
        if (!body) return;
        body.innerHTML =
            '<div class="fw-bold mb-1">' + (btn.getAttribute('data-name') || '') + '</div>' +
            '<div class="tx-rate mb-2">' + (btn.getAttribute('data-rate') || '0') + '%</div>' +
            '<span class="badge bg-info text-dark me-1">' + (btn.getAttribute('data-type') || 'Tax') + '</span>' +
            '<span class="badge bg-secondary">' + (btn.getAttribute('data-status') || '') + '</span>' +
            '<hr><a class="btn btn-sm btn-outline-primary w-100" href="<?php echo BASE_URL; ?>?controller=tax&action=edit&id=' + encodeURIComponent(btn.getAttribute('data-id') || '') + '">Edit Tax</a>';
    });

    renderTable();

    if (typeof Chart !== 'undefined') {
        var ctx = document.getElementById('txStatusChart');
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
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
