<?php
// Staff only
if (!isStaff()) {
    redirect('user/login');
}

/**
 * Normalize report payload from PosController (single summary row from getDailySalesReport).
 * Frontend-only presentation — does not change queries or controllers.
 */
$reportData = (is_array($report ?? null)) ? $report : [];
$sessions = (is_array($sessions ?? null)) ? $sessions : [];
$sessionId = $sessionId ?? '';

$orderCount = (int)($reportData['order_count'] ?? $reportData['orders'] ?? 0);
$totalSales = (float)($reportData['total_sales'] ?? $reportData['total'] ?? 0);
$cashSales = (float)($reportData['cash_sales'] ?? 0);
$cardSales = (float)($reportData['card_sales'] ?? 0);
$otherSales = (float)($reportData['other_sales'] ?? 0);
$subtotal = (float)($reportData['subtotal'] ?? $totalSales);
$tax = (float)($reportData['tax'] ?? 0);

// Detect list-of-days shape (legacy view) vs summary shape
$reportRows = [];
$isList = !empty($reportData) && isset($reportData[0]) && is_array($reportData[0]);
if ($isList) {
    foreach ($reportData as $row) {
        if (!is_array($row)) {
            continue;
        }
        $reportRows[] = [
            'date' => (string)($row['date'] ?? $row['day'] ?? ''),
            'orders' => (int)($row['orders'] ?? $row['order_count'] ?? 0),
            'subtotal' => (float)($row['subtotal'] ?? 0),
            'tax' => (float)($row['tax'] ?? 0),
            'total' => (float)($row['total'] ?? $row['total_sales'] ?? 0),
        ];
    }
    $orderCount = 0;
    $subtotal = 0;
    $tax = 0;
    $totalSales = 0;
    foreach ($reportRows as $r) {
        $orderCount += $r['orders'];
        $subtotal += $r['subtotal'];
        $tax += $r['tax'];
        $totalSales += $r['total'];
    }
} elseif (!empty($reportData) && (isset($reportData['order_count']) || isset($reportData['total_sales']))) {
    $label = !empty($sessionId) ? ('Session #' . (int)$sessionId) : date('Y-m-d');
    $reportRows[] = [
        'date' => $label,
        'orders' => $orderCount,
        'subtotal' => $subtotal > 0 ? $subtotal : $totalSales,
        'tax' => $tax,
        'total' => $totalSales,
        'cash' => $cashSales,
        'card' => $cardSales,
        'other' => $otherSales,
    ];
}

$avgOrder = $orderCount > 0 ? ($totalSales / $orderCount) : 0;
$hasData = $orderCount > 0 || $totalSales > 0 || count($reportRows) > 0;
$currentDateLabel = date('D, M j, Y');
$staffName = htmlspecialchars($_SESSION['user_name'] ?? 'Staff');
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>POS Reports — Sivakamy</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/pos-report.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">
</head>
<body class="pos-report-app">
<nav class="navbar navbar-expand-lg rpt-navbar">
  <div class="container-fluid px-3">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>?controller=pos">
      <span class="rpt-brand-mark" aria-hidden="true">P</span>
      POS Reports
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#rptNav" aria-controls="rptNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="rptNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>?controller=pos"><i class="bi bi-shop-window me-1"></i>POS</a></li>
        <li class="nav-item"><a class="nav-link active" href="<?php echo BASE_URL; ?>?controller=pos&action=report"><i class="bi bi-graph-up me-1"></i>Reports</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>?controller=pos&action=session"><i class="bi bi-clock-history me-1"></i>Session</a></li>
        <?php if (function_exists('isAdmin') && isAdmin()): ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>?controller=home&action=admin"><i class="bi bi-speedometer2 me-1"></i>Admin</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge rounded-pill text-bg-light border px-3 py-2"><i class="bi bi-calendar3 me-1"></i><?php echo htmlspecialchars($currentDateLabel); ?></span>
        <span class="badge rounded-pill text-bg-primary-subtle text-primary border px-3 py-2"><i class="bi bi-person me-1"></i><?php echo $staffName; ?></span>
      </div>
    </div>
  </div>
</nav>

<div class="rpt-shell">
  <div class="rpt-layout">
    <aside class="rpt-sidebar no-print" aria-label="Quick reports">
      <h6>Quick Reports</h6>
      <a class="active" href="<?php echo BASE_URL; ?>?controller=pos&action=report"><i class="bi bi-bar-chart-line"></i> Sales Report</a>
      <a href="<?php echo BASE_URL; ?>?controller=pos&action=session"><i class="bi bi-wallet2"></i> Cash Sessions</a>
      <a href="<?php echo BASE_URL; ?>?controller=pos"><i class="bi bi-cart-check"></i> Open POS</a>
      <?php if (function_exists('isAdmin') && isAdmin()): ?>
        <a href="<?php echo BASE_URL; ?>?controller=report&action=sales"><i class="bi bi-graph-up-arrow"></i> Admin Sales</a>
        <a href="<?php echo BASE_URL; ?>?controller=report&action=products"><i class="bi bi-box-seam"></i> Product Report</a>
        <a href="<?php echo BASE_URL; ?>?controller=report&action=customers"><i class="bi bi-people"></i> Customer Report</a>
        <a href="<?php echo BASE_URL; ?>?controller=report&action=index"><i class="bi bi-pie-chart"></i> Analytics Hub</a>
      <?php endif; ?>
    </aside>

    <main>
      <div class="rpt-page-header">
        <div>
          <h1><i class="bi bi-graph-up-arrow text-primary me-2"></i>POS Reports</h1>
          <ol class="rpt-breadcrumb">
            <li><a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a></li>
            <li><a href="<?php echo BASE_URL; ?>?controller=pos">POS</a></li>
            <li aria-current="page">Reports</li>
          </ol>
        </div>
        <div class="rpt-header-actions no-print">
          <button type="button" class="btn btn-outline-secondary btn-sm" id="rptPrintBtn"><i class="bi bi-printer me-1"></i>Print</button>
          <button type="button" class="btn btn-outline-secondary btn-sm" id="rptPdfBtn"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</button>
          <button type="button" class="btn btn-outline-primary btn-sm" id="rptCsvBtn"><i class="bi bi-filetype-csv me-1"></i>CSV</button>
          <button type="button" class="btn btn-primary btn-sm" id="rptExcelBtn"><i class="bi bi-file-earmark-excel me-1"></i>Excel</button>
        </div>
      </div>

      <!-- Filter panel — session_id is the only server filter (existing) -->
      <div class="rpt-card rpt-filter-card mb-3">
        <div class="rpt-card-header">
          <h5><i class="bi bi-funnel me-1 text-primary"></i> Filters</h5>
          <span class="text-muted small">Session filter applies on the server</span>
        </div>
        <div class="rpt-card-body">
          <form class="row g-2 rpt-filter-form align-items-end" method="get" action="<?php echo BASE_URL; ?>" id="rptSessionForm">
            <input type="hidden" name="controller" value="pos">
            <input type="hidden" name="action" value="report">
            <div class="col-sm-6 col-md-4 col-lg-3">
              <label class="form-label" for="session_id">Session</label>
              <select name="session_id" id="session_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Sessions (Today)</option>
                <?php foreach ($sessions as $s): ?>
                  <option value="<?php echo (int)$s['id']; ?>" <?php echo (!empty($sessionId) && (int)$sessionId === (int)$s['id']) ? 'selected' : ''; ?>>
                    #<?php echo (int)$s['id']; ?> — <?php echo htmlspecialchars($s['opened_at'] ?? ''); ?>
                    <?php if (!empty($s['first_name'])): ?>
                      (<?php echo htmlspecialchars(trim(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? ''))); ?>)
                    <?php endif; ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
              <label class="form-label" for="rptTableSearch">Search table</label>
              <input type="search" id="rptTableSearch" class="form-control" placeholder="Filter rows…" autocomplete="off">
            </div>
            <div class="col-auto">
              <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Apply</button>
              <a href="<?php echo BASE_URL; ?>?controller=pos&action=report" class="btn btn-outline-secondary">Reset</a>
            </div>
          </form>
          <div class="rpt-quick-filters no-print" role="group" aria-label="Quick views">
            <span class="small text-muted me-1 align-self-center">Quick:</span>
            <button type="button" class="btn btn-outline-secondary btn-sm active" data-rpt-quick="all">All rows</button>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-rpt-quick="has-orders">With orders</button>
            <a class="btn btn-outline-secondary btn-sm" href="<?php echo BASE_URL; ?>?controller=pos&action=report">Today</a>
          </div>
        </div>
      </div>

      <!-- KPI cards from existing report summary -->
      <div class="rpt-kpi-grid">
        <div class="rpt-kpi is-blue">
          <i class="bi bi-receipt icon" aria-hidden="true"></i>
          <div class="label">Orders</div>
          <div class="value" data-counter="<?php echo $orderCount; ?>"><?php echo $orderCount; ?></div>
        </div>
        <div class="rpt-kpi is-green">
          <i class="bi bi-currency-exchange icon" aria-hidden="true"></i>
          <div class="label">Revenue</div>
          <div class="value"><?php echo formatPrice($totalSales); ?></div>
        </div>
        <div class="rpt-kpi is-amber">
          <i class="bi bi-cash-stack icon" aria-hidden="true"></i>
          <div class="label">Cash Sales</div>
          <div class="value"><?php echo formatPrice($cashSales); ?></div>
        </div>
        <div class="rpt-kpi is-cyan">
          <i class="bi bi-credit-card icon" aria-hidden="true"></i>
          <div class="label">Card Sales</div>
          <div class="value"><?php echo formatPrice($cardSales); ?></div>
        </div>
        <div class="rpt-kpi is-rose">
          <i class="bi bi-graph-up icon" aria-hidden="true"></i>
          <div class="label">Avg. Order</div>
          <div class="value"><?php echo formatPrice($avgOrder); ?></div>
        </div>
      </div>

      <!-- Charts -->
      <div class="rpt-charts">
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h5>Sales Overview</h5>
            <span class="badge text-bg-primary-subtle text-primary">Live</span>
          </div>
          <div class="rpt-card-body">
            <div class="rpt-chart-wrap">
              <canvas id="rptSalesChart" aria-label="Sales overview chart" role="img"></canvas>
            </div>
          </div>
        </div>
        <div class="rpt-card">
          <div class="rpt-card-header">
            <h5>Payment Methods</h5>
          </div>
          <div class="rpt-card-body">
            <div class="rpt-chart-wrap">
              <canvas id="rptPaymentChart" aria-label="Payment methods chart" role="img"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Report table -->
      <div class="rpt-card">
        <div class="rpt-card-header">
          <h5><i class="bi bi-table me-1 text-primary"></i> Daily Sales Report</h5>
          <span class="text-muted small"><?php echo count($reportRows); ?> row(s)</span>
        </div>
        <div class="rpt-card-body">
          <?php if (!$hasData && empty($reportRows)): ?>
            <div class="rpt-empty">
              <i class="bi bi-inbox"></i>
              <strong>No sales found</strong>
              <p class="mb-0 mt-1">No sales found for the selected criteria.</p>
            </div>
          <?php else: ?>
            <div class="rpt-table-toolbar no-print">
              <div class="text-muted small">Sticky header · Client search · Export via toolbar</div>
            </div>
            <div class="rpt-table-wrap">
              <table class="table table-hover rpt-table align-middle" id="rptSalesTable">
                <thead>
                  <tr>
                    <th>Date / Session</th>
                    <th class="text-end">Orders</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">Tax</th>
                    <th class="text-end">Grand Total</th>
                    <th class="text-end no-print">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sumOrders = 0;
                    $sumSub = 0;
                    $sumTax = 0;
                    $sumTotal = 0;
                    foreach ($reportRows as $row):
                      $sumOrders += (int)$row['orders'];
                      $sumSub += (float)$row['subtotal'];
                      $sumTax += (float)$row['tax'];
                      $sumTotal += (float)$row['total'];
                  ?>
                    <tr data-rpt-row data-orders="<?php echo (int)$row['orders']; ?>" data-search="<?php echo htmlspecialchars(strtolower($row['date'])); ?>">
                      <td><?php echo htmlspecialchars($row['date']); ?></td>
                      <td class="text-end"><?php echo (int)$row['orders']; ?></td>
                      <td class="text-end"><?php echo formatPrice($row['subtotal']); ?></td>
                      <td class="text-end"><?php echo formatPrice($row['tax']); ?></td>
                      <td class="text-end fw-semibold text-primary"><?php echo formatPrice($row['total']); ?></td>
                      <td class="text-end no-print">
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()" title="Print"><i class="bi bi-printer"></i></button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Total</th>
                    <th class="text-end"><?php echo $sumOrders; ?></th>
                    <th class="text-end"><?php echo formatPrice($sumSub); ?></th>
                    <th class="text-end"><?php echo formatPrice($sumTax); ?></th>
                    <th class="text-end"><?php echo formatPrice($sumTotal); ?></th>
                    <th class="no-print"></th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <div class="rpt-empty d-none" id="rptTableEmpty">
              <i class="bi bi-search"></i>
              <p class="mb-0">No rows match your search.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Sessions history (already available from controller) -->
      <?php if (!empty($sessions)): ?>
      <div class="rpt-card mt-3">
        <div class="rpt-card-header">
          <h5><i class="bi bi-clock-history me-1 text-primary"></i> Recent Sessions</h5>
        </div>
        <div class="rpt-card-body">
          <div class="rpt-table-wrap">
            <table class="table table-sm rpt-table align-middle" id="rptSessionsTable">
              <thead>
                <tr>
                  <th>Session</th>
                  <th>Cashier</th>
                  <th>Opened</th>
                  <th>Closed</th>
                  <th class="text-end no-print">View</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($sessions as $s): ?>
                  <tr>
                    <td>#<?php echo (int)$s['id']; ?></td>
                    <td><?php echo htmlspecialchars(trim(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? ''))); ?></td>
                    <td><?php echo htmlspecialchars($s['opened_at'] ?? '—'); ?></td>
                    <td><?php echo htmlspecialchars($s['closed_at'] ?? 'Open'); ?></td>
                    <td class="text-end no-print">
                      <a class="btn btn-sm btn-outline-primary" href="<?php echo BASE_URL; ?>?controller=pos&action=report&session_id=<?php echo (int)$s['id']; ?>">
                        Report
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
  'use strict';

  var chartLabels = <?php echo json_encode(array_column($reportRows, 'date')); ?>;
  var chartTotals = <?php echo json_encode(array_map('floatval', array_column($reportRows, 'total'))); ?>;
  var chartOrders = <?php echo json_encode(array_map('intval', array_column($reportRows, 'orders'))); ?>;
  var cash = <?php echo json_encode((float)$cashSales); ?>;
  var card = <?php echo json_encode((float)$cardSales); ?>;
  var other = <?php echo json_encode((float)$otherSales); ?>;

  function initCharts() {
    if (typeof Chart === 'undefined') return;
    var salesEl = document.getElementById('rptSalesChart');
    var payEl = document.getElementById('rptPaymentChart');

    if (salesEl) {
      var labels = chartLabels.length ? chartLabels : ['No data'];
      var totals = chartTotals.length ? chartTotals : [0];
      var orders = chartOrders.length ? chartOrders : [0];
      new Chart(salesEl, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [
            {
              type: 'bar',
              label: 'Revenue',
              data: totals,
              backgroundColor: 'rgba(37, 99, 235, 0.75)',
              borderRadius: 10,
              yAxisID: 'y'
            },
            {
              type: 'line',
              label: 'Orders',
              data: orders,
              borderColor: '#10B981',
              backgroundColor: 'rgba(16, 185, 129, 0.15)',
              tension: 0.35,
              yAxisID: 'y1'
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          interaction: { mode: 'index', intersect: false },
          plugins: { legend: { position: 'bottom' } },
          scales: {
            y: { beginAtZero: true, position: 'left', grid: { color: 'rgba(15,23,42,0.06)' } },
            y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false } }
          },
          animation: { duration: 900 }
        }
      });
    }

    if (payEl) {
      new Chart(payEl, {
        type: 'doughnut',
        data: {
          labels: ['Cash', 'Card', 'Other'],
          datasets: [{
            data: [cash, card, other],
            backgroundColor: ['#10B981', '#2563EB', '#F59E0B'],
            borderWidth: 0,
            hoverOffset: 6
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          cutout: '68%',
          plugins: { legend: { position: 'bottom' } },
          animation: { duration: 900 }
        }
      });
    }
  }

  function exportTable(filename, excel) {
    var table = document.getElementById('rptSalesTable');
    if (!table) return;
    var rows = [];
    table.querySelectorAll('tr').forEach(function (tr) {
      var cols = [];
      tr.querySelectorAll('th,td').forEach(function (cell, idx) {
        if (cell.classList.contains('no-print')) return;
        cols.push('"' + String(cell.innerText || '').replace(/"/g, '""').trim() + '"');
      });
      if (cols.length) rows.push(cols.join(','));
    });
    var csv = rows.join('\n');
    var blob = new Blob([csv], { type: excel ? 'application/vnd.ms-excel' : 'text/csv;charset=utf-8;' });
    var url = URL.createObjectURL(blob);
    var a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
  }

  function filterTable() {
    var q = (document.getElementById('rptTableSearch') || {}).value || '';
    q = String(q).toLowerCase().trim();
    var quick = document.querySelector('[data-rpt-quick].active');
    var mode = quick ? quick.getAttribute('data-rpt-quick') : 'all';
    var rows = document.querySelectorAll('#rptSalesTable tbody tr[data-rpt-row]');
    var visible = 0;
    rows.forEach(function (row) {
      var search = row.getAttribute('data-search') || '';
      var orders = parseInt(row.getAttribute('data-orders') || '0', 10);
      var matchQ = !q || search.indexOf(q) !== -1;
      var matchQuick = mode !== 'has-orders' || orders > 0;
      var show = matchQ && matchQuick;
      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    var empty = document.getElementById('rptTableEmpty');
    if (empty) empty.classList.toggle('d-none', visible !== 0 || rows.length === 0);
  }

  document.addEventListener('DOMContentLoaded', function () {
    initCharts();

    var search = document.getElementById('rptTableSearch');
    if (search) search.addEventListener('input', filterTable);

    document.querySelectorAll('[data-rpt-quick]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        document.querySelectorAll('[data-rpt-quick]').forEach(function (b) { b.classList.remove('active'); });
        btn.classList.add('active');
        filterTable();
      });
    });

    var printBtn = document.getElementById('rptPrintBtn');
    var pdfBtn = document.getElementById('rptPdfBtn');
    if (printBtn) printBtn.addEventListener('click', function () { window.print(); });
    if (pdfBtn) pdfBtn.addEventListener('click', function () { window.print(); });

    var csvBtn = document.getElementById('rptCsvBtn');
    var xlsBtn = document.getElementById('rptExcelBtn');
    if (csvBtn) csvBtn.addEventListener('click', function () { exportTable('pos-sales-report.csv', false); });
    if (xlsBtn) xlsBtn.addEventListener('click', function () { exportTable('pos-sales-report.xls', true); });
  });
})();
</script>
</body>
</html>
