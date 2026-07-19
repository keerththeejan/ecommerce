<?php
/**
 * Order Management — Enterprise Admin UI (visual layer only)
 * Preserves filters, #ordersTable, edit/delete modals, AJAX update/delete.
 */
require_once APP_PATH . 'views/admin/layouts/header.php';

$orders = $orders ?? ($data['orders'] ?? []);
$filters = $filters ?? ($data['filters'] ?? []);
$orderRows = $orders['data'] ?? [];
$totalOrders = (int)($orders['total'] ?? 0);
$currentPage = (int)($orders['current_page'] ?? 1);
$totalPages = (int)($orders['total_pages'] ?? 1);

$st = $filters['status'] ?? '';
$ps = $filters['payment_status'] ?? '';
$pm = $filters['payment_method'] ?? '';

$countPending = $countProcessing = $countShipped = $countDelivered = $countCancelled = 0;
$countPaid = $countPayPending = 0;
$pageRevenue = 0.0;
$todaySales = 0.0;
$monthSales = 0.0;
$today = date('Y-m-d');
$monthPrefix = date('Y-m');
$customersSeen = [];

foreach ($orderRows as $o) {
    $status = strtolower((string)($o['status'] ?? ''));
    $pay = strtolower((string)($o['payment_status'] ?? ''));
    $amt = (float)($o['total_amount'] ?? 0);
    $pageRevenue += $amt;
    $created = (string)($o['created_at'] ?? '');
    if ($created !== '' && substr($created, 0, 10) === $today) {
        $todaySales += $amt;
    }
    if ($created !== '' && substr($created, 0, 7) === $monthPrefix) {
        $monthSales += $amt;
    }
    $emailKey = strtolower((string)($o['email'] ?? ''));
    if ($emailKey !== '') {
        $customersSeen[$emailKey] = true;
    }
    switch ($status) {
        case 'pending': $countPending++; break;
        case 'processing': $countProcessing++; break;
        case 'shipped': $countShipped++; break;
        case 'delivered':
        case 'completed': $countDelivered++; break;
        case 'cancelled': $countCancelled++; break;
    }
    if ($pay === 'paid') {
        $countPaid++;
    } elseif ($pay === 'pending') {
        $countPayPending++;
    }
}
$newCustomersPage = count($customersSeen);

$qs = [];
foreach (['order_id', 'customer_name', 'email', 'status', 'payment_status', 'payment_method', 'date_from', 'date_to', 'q'] as $k) {
    if (!empty($filters[$k])) {
        $qs[$k] = $filters[$k];
    }
}
$basePagination = BASE_URL . '?controller=order&action=adminIndex' . (empty($qs) ? '' : ('&' . http_build_query($qs)));

if (!function_exists('ol_status_class')) {
    function ol_status_class($status) {
        $s = strtolower((string)$status);
        $map = [
            'pending' => 'ol-status-pending',
            'processing' => 'ol-status-processing',
            'shipped' => 'ol-status-shipped',
            'delivered' => 'ol-status-delivered',
            'completed' => 'ol-status-completed',
            'cancelled' => 'ol-status-cancelled',
        ];
        return $map[$s] ?? 'badge-status';
    }
}
if (!function_exists('ol_pay_class')) {
    function ol_pay_class($pay) {
        $p = strtolower((string)$pay);
        $map = [
            'pending' => 'ol-pay-pending',
            'paid' => 'ol-pay-paid',
            'failed' => 'ol-pay-failed',
            'refunded' => 'ol-pay-refunded',
        ];
        return $map[$p] ?? '';
    }
}
?>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/order-list.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<div class="container-fluid order-list-page py-3 py-md-4 px-2 px-sm-3 orders-admin" id="orderAdminPage">
    <div class="ol-toast-host" id="olToastHost" aria-live="polite" aria-atomic="true"></div>

    <div class="ol-header">
        <div>
            <nav class="ol-breadcrumb" aria-label="Breadcrumb">
                <a href="<?php echo BASE_URL; ?>?controller=home&action=admin">Dashboard</a>
                <span>›</span>
                <span>Sales</span>
                <span>›</span>
                <span aria-current="page">Orders</span>
            </nav>
            <h1 class="ol-title">Order Management</h1>
            <p class="ol-subtitle">Search, filter, fulfill, and maintain orders across your storefront.</p>
        </div>
        <div class="ol-actions">
            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-outline-secondary ol-btn" id="refreshBtn" title="Refresh">
                <i class="bi bi-arrow-clockwise"></i><span class="d-none d-md-inline">Refresh</span>
            </a>
            <button type="button" class="btn btn-outline-secondary ol-btn" id="olPrintBtn" title="Print" onclick="window.print()">
                <i class="bi bi-printer"></i><span class="d-none d-lg-inline">Print</span>
            </button>
            <button type="button" class="btn btn-outline-secondary ol-btn" id="olExportCsvBtn" title="Excel">
                <i class="bi bi-file-earmark-spreadsheet"></i><span class="d-none d-lg-inline">Excel</span>
            </button>
            <button type="button" class="btn btn-outline-secondary ol-btn" onclick="window.print()" title="PDF">
                <i class="bi bi-filetype-pdf"></i><span class="d-none d-lg-inline">PDF</span>
            </button>
            <button type="button" class="btn btn-outline-secondary ol-btn" id="olImportBtn" title="Import (UI)">
                <i class="bi bi-upload"></i><span class="d-none d-xl-inline">Import</span>
            </button>
            <button type="button" class="btn ol-btn ol-btn-primary" id="olCreateOrderBtn" title="Orders are created from the storefront checkout">
                <i class="bi bi-plus-lg"></i><span>Create Order</span>
            </button>
        </div>
    </div>

    <?php flash('order_success'); ?>
    <?php flash('order_error', '', 'alert alert-danger'); ?>

    <div class="row g-3 mb-3">
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s1"><div class="icon" aria-hidden="true"><i class="bi bi-cart3"></i></div><div><div class="label">Total Orders</div><div class="value" data-counter="<?php echo $totalOrders; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s2"><div class="icon" aria-hidden="true"><i class="bi bi-check-circle"></i></div><div><div class="label">Delivered (page)</div><div class="value" data-counter="<?php echo $countDelivered; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s3"><div class="icon" aria-hidden="true"><i class="bi bi-hourglass-split"></i></div><div><div class="label">Pending (page)</div><div class="value" data-counter="<?php echo $countPending; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s4"><div class="icon" aria-hidden="true"><i class="bi bi-truck"></i></div><div><div class="label">Shipped (page)</div><div class="value" data-counter="<?php echo $countShipped; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s5"><div class="icon" aria-hidden="true"><i class="bi bi-gear"></i></div><div><div class="label">Processing (page)</div><div class="value" data-counter="<?php echo $countProcessing; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s6"><div class="icon" aria-hidden="true"><i class="bi bi-x-circle"></i></div><div><div class="label">Cancelled (page)</div><div class="value" data-counter="<?php echo $countCancelled; ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s7"><div class="icon" aria-hidden="true"><i class="bi bi-currency-rupee"></i></div><div><div class="label">Page Revenue</div><div class="value" data-counter="<?php echo (int)round($pageRevenue); ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s8"><div class="icon" aria-hidden="true"><i class="bi bi-graph-up-arrow"></i></div><div><div class="label">Today (page)</div><div class="value" data-counter="<?php echo (int)round($todaySales); ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s1"><div class="icon" aria-hidden="true"><i class="bi bi-calendar3"></i></div><div><div class="label">Month (page)</div><div class="value" data-counter="<?php echo (int)round($monthSales); ?>">0</div></div></div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="ol-stat s2"><div class="icon" aria-hidden="true"><i class="bi bi-people"></i></div><div><div class="label">Customers (page)</div><div class="value" data-counter="<?php echo $newCustomersPage; ?>">0</div></div></div>
        </div>
    </div>

    <div class="ol-card ol-filter-card mb-3">
        <div class="ol-card-header">
            <h2><i class="bi bi-funnel"></i> Advanced Search</h2>
            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="collapse" data-bs-target="#olFilterBody" aria-expanded="true">
                <i class="bi bi-chevron-down"></i>
            </button>
        </div>
        <div class="collapse show" id="olFilterBody">
            <div class="ol-card-body">
                <form method="get" action="" class="mb-0" id="orderFilterForm" novalidate>
                    <input type="hidden" name="controller" value="order" />
                    <input type="hidden" name="action" value="adminIndex" />

                    <div class="row g-2 g-md-3 mb-2">
                        <div class="col-12 col-lg-4">
                            <label class="form-label" for="olGlobalQ">Global Search</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="q" id="olGlobalQ" value="<?php echo htmlspecialchars($filters['q'] ?? ''); ?>" class="form-control" placeholder="Order #, name, or email…" />
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label">Order ID</label>
                            <input type="text" name="order_id" value="<?php echo htmlspecialchars($filters['order_id'] ?? ''); ?>" class="form-control" placeholder="#123" inputmode="numeric" />
                        </div>
                        <div class="col-6 col-md-4 col-lg-3">
                            <label class="form-label">Customer Name</label>
                            <input type="text" name="customer_name" value="<?php echo htmlspecialchars($filters['customer_name'] ?? ''); ?>" class="form-control" placeholder="Customer name" />
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <label class="form-label">Email</label>
                            <input type="text" name="email" value="<?php echo htmlspecialchars($filters['email'] ?? ''); ?>" class="form-control" placeholder="email@example.com" inputmode="email" />
                        </div>
                    </div>

                    <div class="row g-2 g-md-3 mb-2">
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label">Order Status</label>
                            <select name="status" class="form-select custom-select">
                                <option value="">All</option>
                                <option value="pending" <?php echo $st === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo $st === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo $st === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $st === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $st === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-select custom-select">
                                <option value="">All</option>
                                <option value="cod" <?php echo $pm === 'cod' ? 'selected' : ''; ?>>COD</option>
                                <option value="card" <?php echo $pm === 'card' ? 'selected' : ''; ?>>Card</option>
                                <option value="upi" <?php echo $pm === 'upi' ? 'selected' : ''; ?>>UPI</option>
                                <option value="netbanking" <?php echo $pm === 'netbanking' ? 'selected' : ''; ?>>Netbanking</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label">Payment Status</label>
                            <select name="payment_status" class="form-select custom-select">
                                <option value="">All</option>
                                <option value="pending" <?php echo $ps === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="paid" <?php echo $ps === 'paid' ? 'selected' : ''; ?>>Paid</option>
                                <option value="failed" <?php echo $ps === 'failed' ? 'selected' : ''; ?>>Failed</option>
                                <option value="refunded" <?php echo $ps === 'refunded' ? 'selected' : ''; ?>>Refunded</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label">From</label>
                            <input type="date" name="date_from" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>" class="form-control" />
                        </div>
                        <div class="col-6 col-md-4 col-lg-2">
                            <label class="form-label">To</label>
                            <input type="date" name="date_to" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>" class="form-control" />
                        </div>
                        <div class="col-12 col-md-4 col-lg-2 d-flex align-items-end gap-2 flex-wrap">
                            <button type="submit" class="btn ol-btn ol-btn-primary flex-grow-1">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-outline-secondary ol-btn">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-9">
            <div class="ol-card">
                <div class="ol-card-header">
                    <div>
                        <h2 class="mb-0">All Orders</h2>
                        <div class="text-muted small">Showing <strong><?php echo (int)count($orderRows); ?></strong> of <strong><?php echo $totalOrders; ?></strong> · Edit status & payment inline</div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Columns">
                                <i class="bi bi-layout-three-columns"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 ol-col-toggle" data-col="0" checked> Select</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 ol-col-toggle" data-col="1" checked> Order</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 ol-col-toggle" data-col="2" checked> Customer</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 ol-col-toggle" data-col="3" checked> Status</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 ol-col-toggle" data-col="4" checked> Payment</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 ol-col-toggle" data-col="5" checked> Total</label></li>
                                <li><label class="dropdown-item"><input type="checkbox" class="form-check-input me-2 ol-col-toggle" data-col="6" checked> Actions</label></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="ol-bulk-bar" id="olBulkBar" aria-live="polite">
                    <strong><span id="olSelectedCount">0</span> selected</strong>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="olBulkPrint">Print Invoice</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="olBulkProcessing">Mark Processing</button>
                    <button type="button" class="btn btn-sm btn-outline-info" id="olBulkShipped">Mark Shipped</button>
                    <button type="button" class="btn btn-sm btn-outline-success" id="olBulkDelivered">Mark Delivered</button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="olBulkCancel">Cancel</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="olBulkExport">Export</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="olBulkClear">Clear</button>
                </div>

                <div class="orders-table-scroll">
                    <div class="table-responsive">
                        <table id="ordersTable" class="table table-hover align-middle mb-0" aria-label="Orders table">
                            <thead>
                                <tr>
                                    <th style="width:40px;" data-label="Select">
                                        <input type="checkbox" class="form-check-input" id="olSelectAll" aria-label="Select all orders">
                                    </th>
                                    <th style="width:100px;">Order No</th>
                                    <th>Customer</th>
                                    <th style="width:120px;">Status</th>
                                    <th style="width:130px;">Payment</th>
                                    <th style="width:110px;">Grand Total</th>
                                    <th style="width:200px;" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($orderRows)): ?>
                                    <tr>
                                        <td colspan="7">
                                            <div class="ol-empty">
                                                <i class="bi bi-cart-x d-block mb-2" style="font-size:2rem;opacity:.4;"></i>
                                                No orders found. Adjust filters or wait for new checkouts.
                                            </div>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($orderRows as $order):
                                        $oid = (int)$order['id'];
                                        $customerName = htmlspecialchars(trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? '')));
                                        $email = htmlspecialchars($order['email'] ?? '');
                                        $phone = htmlspecialchars($order['phone'] ?? $order['customer_phone'] ?? '');
                                        $status = $order['status'] ?? '';
                                        $paymentStatus = $order['payment_status'] ?? '';
                                        $paymentMethod = $order['payment_method'] ?? '';
                                        $totalFmt = formatPrice($order['total_amount'] ?? 0);
                                        $createdFmt = !empty($order['created_at']) ? date('M d, Y', strtotime($order['created_at'])) : '—';
                                        $createdFull = !empty($order['created_at']) ? date('M d, Y H:i', strtotime($order['created_at'])) : '—';
                                    ?>
                                    <tr id="order-row-<?php echo $oid; ?>"
                                        class="order-row"
                                        data-id="<?php echo $oid; ?>"
                                        data-status="<?php echo htmlspecialchars($status); ?>"
                                        data-payment="<?php echo htmlspecialchars($paymentStatus); ?>"
                                        data-method="<?php echo htmlspecialchars($paymentMethod); ?>"
                                        data-total="<?php echo htmlspecialchars((string)($order['total_amount'] ?? 0)); ?>"
                                        data-customer="<?php echo $customerName; ?>"
                                        data-email="<?php echo $email; ?>"
                                        data-created="<?php echo htmlspecialchars($order['created_at'] ?? ''); ?>">
                                        <td data-label="Select" onclick="event.stopPropagation();">
                                            <input type="checkbox" class="form-check-input ol-row-check" value="<?php echo $oid; ?>" aria-label="Select order #<?php echo $oid; ?>">
                                        </td>
                                        <td data-label="Order No">
                                            <a class="ol-order-id" href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $oid; ?>">
                                                #<?php echo $oid; ?>
                                            </a>
                                            <div class="text-muted small"><?php echo $createdFmt; ?></div>
                                        </td>
                                        <td data-label="Customer">
                                            <div class="font-weight-600 brand-name"><?php echo $customerName ?: '—'; ?></div>
                                            <div class="text-muted small"><?php echo $email ?: '—'; ?></div>
                                            <?php if ($phone !== ''): ?>
                                                <div class="text-muted small"><i class="bi bi-telephone me-1"></i><?php echo $phone; ?></div>
                                            <?php endif; ?>
                                        </td>
                                        <td data-label="Status">
                                            <span class="badge status-badge <?php echo ol_status_class($status); ?>" data-role="status-badge">
                                                <?php echo ucfirst($status ?: '—'); ?>
                                            </span>
                                        </td>
                                        <td data-label="Payment">
                                            <span class="badge payment-badge <?php echo ol_pay_class($paymentStatus); ?>" data-role="payment-badge">
                                                <?php echo ucfirst($paymentStatus ?: '—'); ?>
                                            </span>
                                            <div class="text-muted small" data-role="payment-method"><?php echo htmlspecialchars($paymentMethod ?: '—'); ?></div>
                                        </td>
                                        <td data-label="Grand Total">
                                            <span class="ol-total"><?php echo $totalFmt; ?></span>
                                        </td>
                                        <td data-label="Actions" class="text-end" onclick="event.stopPropagation();">
                                            <div class="btn-group btn-group-sm" role="group" aria-label="Order actions">
                                                <a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $oid; ?>" class="btn btn-outline-secondary" title="View">
                                                    <i class="fas fa-eye"></i> <span class="d-none d-xl-inline">View</span>
                                                </a>
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-primary edit-order"
                                                    data-id="<?php echo $oid; ?>"
                                                    data-status="<?php echo htmlspecialchars($status); ?>"
                                                    data-payment_status="<?php echo htmlspecialchars($paymentStatus); ?>"
                                                    data-payment_method="<?php echo htmlspecialchars($paymentMethod); ?>"
                                                    title="Edit"
                                                >
                                                    <i class="fas fa-edit"></i> <span class="d-none d-xl-inline">Edit</span>
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary ol-preview-btn"
                                                    data-id="<?php echo $oid; ?>"
                                                    data-customer="<?php echo $customerName; ?>"
                                                    data-email="<?php echo $email; ?>"
                                                    data-status="<?php echo htmlspecialchars($status); ?>"
                                                    data-payment="<?php echo htmlspecialchars($paymentStatus); ?>"
                                                    data-method="<?php echo htmlspecialchars($paymentMethod); ?>"
                                                    data-total="<?php echo htmlspecialchars($totalFmt); ?>"
                                                    data-created="<?php echo htmlspecialchars($createdFull); ?>"
                                                    title="Quick preview">
                                                    <i class="bi bi-layout-sidebar-inset-reverse"></i>
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-outline-danger delete-order"
                                                    data-id="<?php echo $oid; ?>"
                                                    data-name="#<?php echo $oid; ?>"
                                                    title="Delete"
                                                >
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

                <div class="ol-card-body border-top">
                    <div class="d-flex flex-wrap justify-content-center justify-content-md-between align-items-center gap-2">
                        <div class="small text-muted">
                            Page <?php echo $currentPage; ?> of <?php echo max(1, $totalPages); ?>
                        </div>
                        <div class="d-flex flex-wrap">
                            <?php echo getPaginationLinks($orders['current_page'], $orders['total_pages'], $basePagination); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-3">
            <div class="ol-card ol-preview-panel mb-3" id="olPreviewCard">
                <div class="ol-card-header">
                    <h3 class="mb-0" style="font-size:1rem;">Order Preview</h3>
                </div>
                <div class="ol-card-body" id="olPreviewBody">
                    <p class="text-muted small mb-0">Select an order row or click the preview icon to inspect customer, payment, and timeline.</p>
                </div>
            </div>

            <div class="ol-chart-card mb-3">
                <h3>Status Mix (page)</h3>
                <canvas id="olStatusChart" height="180" aria-label="Order status chart"></canvas>
            </div>
            <div class="ol-chart-card">
                <h3>Payment Mix (page)</h3>
                <canvas id="olPaymentChart" height="180" aria-label="Payment status chart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editOrderModal" tabindex="-1" role="dialog" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header">
                <h5 class="modal-title" id="editOrderModalLabel"><i class="bi bi-pencil-square me-2"></i>Edit Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-muted small mb-3">Update status and payment details. Customer info and items are available in View.</div>
                <form id="editOrderForm" action="<?php echo BASE_URL; ?>?controller=order&action=adminUpdate" method="POST" novalidate>
                    <input type="hidden" name="id" id="edit_order_id" value="">
                    <div class="mb-3 form-group">
                        <label for="edit_status" class="form-label mb-1">Order Status</label>
                        <select class="form-select custom-select" id="edit_status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3 form-group">
                        <label for="edit_payment_status" class="form-label mb-1">Payment Status</label>
                        <select class="form-select custom-select" id="edit_payment_status" name="payment_status" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div class="mb-0 form-group">
                        <label for="edit_payment_method" class="form-label mb-1">Payment Method</label>
                        <input type="text" class="form-control" id="edit_payment_method" name="payment_method" placeholder="e.g. cod / upi / card" />
                        <small class="text-muted">Saved exactly as entered. Use consistent codes for better filtering.</small>
                    </div>
                </form>
                <div class="alert alert-danger d-none mt-3" id="editOrderError" role="alert"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                <button type="submit" form="editOrderForm" class="btn btn-primary" id="saveOrderBtn">
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span class="button-text">Save Changes</span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteOrderModal" tabindex="-1" role="dialog" aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header" style="background: rgba(220,53,69,0.08);">
                <h5 class="modal-title" id="deleteOrderModalLabel"><i class="bi bi-exclamation-triangle me-2 text-danger"></i>Delete Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">Are you sure you want to delete <strong id="orderNameToDelete"></strong>?</div>
                <div class="text-muted small">This action cannot be undone.</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                <form id="deleteOrderForm" action="<?php echo BASE_URL; ?>?controller=order&action=delete" method="POST" class="mb-0">
                    <input type="hidden" name="id" id="deleteOrderId" value="">
                    <button type="submit" class="btn btn-danger" id="confirmDeleteOrderBtn">
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="button-text">Delete</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function() {
    var BASE_URL = '<?php echo BASE_URL; ?>';

    function safeJson(response) {
        return response.text().then(function(text) {
            try { return { ok: response.ok, data: JSON.parse(text) }; }
            catch (e) { return { ok: false, data: { success: false, message: 'Invalid server response' }, raw: text }; }
        });
    }

    function showModal(id) {
        var el = document.getElementById(id);
        if (!el) return;
        if (window.jQuery && typeof window.jQuery(el).modal === 'function') {
            window.jQuery(el).modal('show');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            bootstrap.Modal.getOrCreateInstance(el).show();
        }
    }

    function hideModal(id) {
        var el = document.getElementById(id);
        if (!el) return;
        if (window.jQuery && typeof window.jQuery(el).modal === 'function') {
            window.jQuery(el).modal('hide');
        } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var inst = bootstrap.Modal.getInstance(el);
            if (inst) inst.hide();
        }
    }

    function showToast(msg, type) {
        var host = document.getElementById('olToastHost');
        if (!host) return;
        var t = document.createElement('div');
        t.className = 'ol-toast ' + (type || 'info');
        t.setAttribute('role', 'status');
        t.textContent = msg;
        host.appendChild(t);
        setTimeout(function() {
            t.style.opacity = '0';
            setTimeout(function() { t.remove(); }, 300);
        }, 2800);
    }

    function statusClass(status) {
        var map = {
            pending: 'ol-status-pending',
            processing: 'ol-status-processing',
            shipped: 'ol-status-shipped',
            delivered: 'ol-status-delivered',
            completed: 'ol-status-completed',
            cancelled: 'ol-status-cancelled'
        };
        return map[(status || '').toLowerCase()] || '';
    }

    function payClass(pay) {
        var map = {
            pending: 'ol-pay-pending',
            paid: 'ol-pay-paid',
            failed: 'ol-pay-failed',
            refunded: 'ol-pay-refunded'
        };
        return map[(pay || '').toLowerCase()] || '';
    }

    function timelineHtml(status) {
        var steps = ['created', 'pending', 'processing', 'shipped', 'delivered'];
        var cancelled = (status || '').toLowerCase() === 'cancelled';
        var idx = steps.indexOf((status || '').toLowerCase());
        if (idx < 0) idx = 1;
        var labels = {
            created: 'Order Created',
            pending: 'Payment / Pending',
            processing: 'Packed / Processing',
            shipped: 'Shipped',
            delivered: 'Delivered'
        };
        var html = '<ul class="ol-timeline">';
        if (cancelled) {
            html += '<li class="is-done">Order Created</li><li class="is-current" style="color:#b91c1c;">Cancelled</li>';
        } else {
            steps.forEach(function(s, i) {
                var cls = i < idx ? 'is-done' : (i === idx ? 'is-current is-done' : '');
                html += '<li class="' + cls + '">' + labels[s] + '</li>';
            });
        }
        html += '</ul>';
        return html;
    }

    function renderPreview(data) {
        var body = document.getElementById('olPreviewBody');
        if (!body || !data) return;
        body.innerHTML =
            '<div class="mb-3">' +
            '<div class="fw-bold">#' + (data.id || '') + '</div>' +
            '<div class="text-muted small">' + (data.created || '—') + '</div></div>' +
            '<div class="mb-3"><div class="small text-muted text-uppercase fw-semibold">Customer</div>' +
            '<div class="fw-semibold">' + (data.customer || '—') + '</div>' +
            '<div class="small text-muted">' + (data.email || '—') + '</div></div>' +
            '<div class="mb-3 d-flex gap-2 flex-wrap">' +
            '<span class="badge status-badge ' + statusClass(data.status) + '">' + (data.status || '—') + '</span>' +
            '<span class="badge payment-badge ' + payClass(data.payment) + '">' + (data.payment || '—') + '</span></div>' +
            '<div class="mb-3"><div class="small text-muted text-uppercase fw-semibold">Payment</div>' +
            '<div>' + (data.method || '—') + '</div>' +
            '<div class="ol-total mt-1">' + (data.total || '—') + '</div></div>' +
            '<div class="mb-2"><div class="small text-muted text-uppercase fw-semibold mb-2">Timeline</div>' +
            timelineHtml(data.status) + '</div>' +
            '<a class="btn btn-sm btn-outline-primary w-100" href="' + BASE_URL + '?controller=order&action=adminShow&id=' + encodeURIComponent(data.id) + '">Open full order</a>';
    }

    document.addEventListener('click', function(e) {
        var editBtn = e.target.closest('.edit-order');
        if (editBtn) {
            e.preventDefault();
            e.stopPropagation();

            document.getElementById('edit_order_id').value = editBtn.getAttribute('data-id') || '';
            document.getElementById('edit_status').value = editBtn.getAttribute('data-status') || 'pending';
            document.getElementById('edit_payment_status').value = editBtn.getAttribute('data-payment_status') || 'pending';
            document.getElementById('edit_payment_method').value = editBtn.getAttribute('data-payment_method') || '';

            var err = document.getElementById('editOrderError');
            if (err) { err.classList.add('d-none'); err.textContent = ''; }

            showModal('editOrderModal');
            return;
        }

        var delBtn = e.target.closest('.delete-order');
        if (delBtn) {
            e.preventDefault();
            e.stopPropagation();

            var id = delBtn.getAttribute('data-id');
            var name = delBtn.getAttribute('data-name') || ('#' + id);
            document.getElementById('orderNameToDelete').textContent = name;
            document.getElementById('deleteOrderId').value = id;
            showModal('deleteOrderModal');
            return;
        }

        var prevBtn = e.target.closest('.ol-preview-btn');
        if (prevBtn) {
            e.preventDefault();
            renderPreview({
                id: prevBtn.getAttribute('data-id'),
                customer: prevBtn.getAttribute('data-customer'),
                email: prevBtn.getAttribute('data-email'),
                status: prevBtn.getAttribute('data-status'),
                payment: prevBtn.getAttribute('data-payment'),
                method: prevBtn.getAttribute('data-method'),
                total: prevBtn.getAttribute('data-total'),
                created: prevBtn.getAttribute('data-created')
            });
            return;
        }
    });

    var editForm = document.getElementById('editOrderForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            e.preventDefault();

            var saveBtn = document.getElementById('saveOrderBtn');
            var spinner = saveBtn ? saveBtn.querySelector('.spinner-border') : null;
            var btnText = saveBtn ? saveBtn.querySelector('.button-text') : null;
            var err = document.getElementById('editOrderError');
            if (err) { err.classList.add('d-none'); err.textContent = ''; }

            if (saveBtn) saveBtn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            if (btnText) btnText.textContent = 'Saving...';

            var formData = new FormData(editForm);

            fetch(editForm.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(safeJson)
            .then(function(res) {
                if (res.ok && res.data && res.data.success) {
                    var order = res.data.order || {};
                    var row = document.getElementById('order-row-' + order.id);
                    if (row) {
                        var statusBadge = row.querySelector('[data-role="status-badge"]');
                        var payBadge = row.querySelector('[data-role="payment-badge"]');
                        var payMethod = row.querySelector('[data-role="payment-method"]');

                        if (statusBadge) {
                            statusBadge.textContent = (order.status || '—').charAt(0).toUpperCase() + (order.status || '—').slice(1);
                            statusBadge.className = 'badge status-badge ' + statusClass(order.status);
                        }
                        if (payBadge) {
                            payBadge.textContent = (order.payment_status || '—').charAt(0).toUpperCase() + (order.payment_status || '—').slice(1);
                            payBadge.className = 'badge payment-badge ' + payClass(order.payment_status);
                        }
                        if (payMethod) { payMethod.textContent = order.payment_method ? order.payment_method : '—'; }

                        var editBtn = row.querySelector('.edit-order');
                        if (editBtn) {
                            editBtn.setAttribute('data-status', order.status || '');
                            editBtn.setAttribute('data-payment_status', order.payment_status || '');
                            editBtn.setAttribute('data-payment_method', order.payment_method || '');
                        }
                    }

                    hideModal('editOrderModal');
                    showToast('Order updated successfully', 'success');
                    return;
                }

                var msg = (res.data && res.data.message) ? res.data.message : 'Failed to update order';
                if (err) { err.textContent = msg; err.classList.remove('d-none'); }
            })
            .catch(function(ex) {
                if (err) { err.textContent = ex && ex.message ? ex.message : 'Failed to update order'; err.classList.remove('d-none'); }
            })
            .finally(function() {
                if (saveBtn) saveBtn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
                if (btnText) btnText.textContent = 'Save Changes';
            });
        });
    }

    var deleteForm = document.getElementById('deleteOrderForm');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();

            var btn = document.getElementById('confirmDeleteOrderBtn');
            var spinner = btn ? btn.querySelector('.spinner-border') : null;
            var btnText = btn ? btn.querySelector('.button-text') : null;

            if (btn) btn.disabled = true;
            if (spinner) spinner.classList.remove('d-none');
            if (btnText) btnText.textContent = 'Deleting...';

            var id = document.getElementById('deleteOrderId').value;
            fetch(BASE_URL + '?controller=order&action=delete&id=' + encodeURIComponent(id), {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(safeJson)
            .then(function(res) {
                if (res.ok && res.data && res.data.success) {
                    var row = document.getElementById('order-row-' + id);
                    if (row) row.remove();
                    hideModal('deleteOrderModal');
                    showToast('Order deleted', 'success');
                    updateBulkBar();
                    return;
                }
                alert((res.data && res.data.message) ? res.data.message : 'Failed to delete order');
            })
            .catch(function() {
                alert('Failed to delete order');
            })
            .finally(function() {
                if (btn) btn.disabled = false;
                if (spinner) spinner.classList.add('d-none');
                if (btnText) btnText.textContent = 'Delete';
            });
        });
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

    /* Bulk / export UI */
    var selectAll = document.getElementById('olSelectAll');
    var bulkBar = document.getElementById('olBulkBar');
    var selectedCountEl = document.getElementById('olSelectedCount');
    var table = document.getElementById('ordersTable');
    var tbody = table ? table.querySelector('tbody') : null;

    function updateBulkBar() {
        var checked = tbody ? tbody.querySelectorAll('.ol-row-check:checked') : [];
        var n = checked.length;
        if (selectedCountEl) selectedCountEl.textContent = String(n);
        if (bulkBar) {
            if (n > 0) bulkBar.classList.add('is-visible');
            else bulkBar.classList.remove('is-visible');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            var checks = tbody ? tbody.querySelectorAll('.ol-row-check') : [];
            Array.prototype.forEach.call(checks, function(cb) { cb.checked = selectAll.checked; });
            updateBulkBar();
        });
    }
    if (tbody) {
        tbody.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('ol-row-check')) updateBulkBar();
        });
    }

    function getSelectedRows() {
        return Array.prototype.slice.call(tbody ? tbody.querySelectorAll('tr.order-row') : []).filter(function(r) {
            var cb = r.querySelector('.ol-row-check');
            return cb && cb.checked;
        });
    }

    function exportCsv(rows) {
        var lines = ['OrderID,Customer,Email,Status,PaymentStatus,PaymentMethod,Total,Created'];
        rows.forEach(function(r) {
            lines.push([
                r.getAttribute('data-id'),
                '"' + (r.getAttribute('data-customer') || '').replace(/"/g, '""') + '"',
                '"' + (r.getAttribute('data-email') || '').replace(/"/g, '""') + '"',
                r.getAttribute('data-status'),
                r.getAttribute('data-payment'),
                r.getAttribute('data-method'),
                r.getAttribute('data-total'),
                '"' + (r.getAttribute('data-created') || '') + '"'
            ].join(','));
        });
        var blob = new Blob([lines.join('\n')], { type: 'text/csv;charset=utf-8;' });
        var a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = 'orders-export.csv';
        a.click();
        URL.revokeObjectURL(a.href);
    }

    var exportBtn = document.getElementById('olExportCsvBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            var rows = Array.prototype.slice.call(tbody ? tbody.querySelectorAll('tr.order-row') : []);
            exportCsv(rows);
            showToast('Exported visible orders to CSV', 'success');
        });
    }

    var bulkExport = document.getElementById('olBulkExport');
    if (bulkExport) bulkExport.addEventListener('click', function() {
        exportCsv(getSelectedRows());
        showToast('Exported selected orders', 'success');
    });

    function bulkHint(msg) {
        showToast(msg + ' — use Edit on each order (no bulk API).', 'warning');
    }
    [['olBulkPrint', 'Print invoice'], ['olBulkProcessing', 'Mark processing'], ['olBulkShipped', 'Mark shipped'], ['olBulkDelivered', 'Mark delivered'], ['olBulkCancel', 'Cancel orders']].forEach(function(pair) {
        var el = document.getElementById(pair[0]);
        if (el) el.addEventListener('click', function() { bulkHint(pair[1]); });
    });
    var bulkClear = document.getElementById('olBulkClear');
    if (bulkClear) bulkClear.addEventListener('click', function() {
        Array.prototype.forEach.call(tbody ? tbody.querySelectorAll('.ol-row-check') : [], function(cb) { cb.checked = false; });
        if (selectAll) selectAll.checked = false;
        updateBulkBar();
    });
    var importBtn = document.getElementById('olImportBtn');
    if (importBtn) importBtn.addEventListener('click', function() {
        showToast('Import is a UI preview. Orders are created via storefront checkout.', 'info');
    });
    var createBtn = document.getElementById('olCreateOrderBtn');
    if (createBtn) createBtn.addEventListener('click', function(e) {
        showToast('Create Order opens the list — new orders come from checkout.', 'info');
    });

    document.querySelectorAll('.ol-col-toggle').forEach(function(cb) {
        cb.addEventListener('change', function() {
            var idx = parseInt(cb.getAttribute('data-col'), 10);
            if (!table || isNaN(idx)) return;
            var show = cb.checked;
            Array.prototype.forEach.call(table.querySelectorAll('tr'), function(tr) {
                if (tr.children[idx]) tr.children[idx].style.display = show ? '' : 'none';
            });
        });
    });

    if (typeof Chart !== 'undefined') {
        var statusCtx = document.getElementById('olStatusChart');
        var payCtx = document.getElementById('olPaymentChart');
        if (statusCtx) {
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
                    datasets: [{
                        data: [<?php echo (int)$countPending; ?>, <?php echo (int)$countProcessing; ?>, <?php echo (int)$countShipped; ?>, <?php echo (int)$countDelivered; ?>, <?php echo (int)$countCancelled; ?>],
                        backgroundColor: ['#eab308', '#3b82f6', '#0ea5e9', '#10b981', '#ef4444'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }, cutout: '62%' }
            });
        }
        if (payCtx) {
            new Chart(payCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Paid', 'Pending', 'Other'],
                    datasets: [{
                        data: [<?php echo (int)$countPaid; ?>, <?php echo (int)$countPayPending; ?>, <?php echo max(0, count($orderRows) - $countPaid - $countPayPending); ?>],
                        backgroundColor: ['#10b981', '#eab308', '#94a3b8'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }, cutout: '62%' }
            });
        }
    }
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
