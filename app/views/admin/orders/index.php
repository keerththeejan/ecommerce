<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
    .page-shell {
      width: 100%;
      max-width: none;
      margin: 0;
    }

    .page-title {
      font-weight: 600;
      letter-spacing: -0.02em;
      margin-bottom: 0;
    }
    .page-subtitle {
      color: var(--muted-color);
      font-size: 0.9rem;
      margin-top: 0.25rem;
      margin-bottom: 0;
    }

    .orders-admin .card { border-radius: 14px; border: 1px solid var(--border-color); }
    .orders-admin .card-header {
      background: var(--surface-color);
      border-bottom: 1px solid var(--border-color);
      border-top-left-radius: 14px;
      border-top-right-radius: 14px;
    }

    .orders-admin .btn { border-radius: 10px; }
    .orders-admin .btn:focus { box-shadow: 0 0 0 .2rem rgba(59,130,246,.25); }

    .orders-admin .form-control,
    .orders-admin .custom-select {
      border-radius: 10px;
    }
    .orders-admin .form-control:focus,
    .orders-admin .custom-select:focus {
      border-color: rgba(59,130,246,.6);
      box-shadow: 0 0 0 .2rem rgba(59,130,246,.15);
    }

    .orders-table-scroll {
      max-height: 65vh;
      overflow: auto;
      -webkit-overflow-scrolling: touch;
      border-top: 1px solid var(--border-color);
    }
    .orders-table-scroll .table { margin-bottom: 0; }

    .orders-table-scroll thead th {
      position: sticky;
      top: 0;
      z-index: 2;
      background: var(--surface-color);
      box-shadow: 0 1px 0 0 var(--border-color);
      border-top: 0;
      font-weight: 600;
      font-size: 0.85rem;
      letter-spacing: 0.02em;
      color: var(--muted-color);
      text-transform: uppercase;
      white-space: nowrap;
    }

    .orders-admin #ordersTable tbody tr { transition: background-color .15s ease, box-shadow .15s ease; }
    .orders-admin #ordersTable tbody tr:hover { background: rgba(59,130,246,.06); }

    .status-badge { font-weight: 600; }
    .payment-badge { font-weight: 600; }

    @media (max-width: 575.98px) {
      #ordersTable thead { display: none; }
      #ordersTable tbody tr {
        display: block;
        margin-bottom: 0.75rem;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        background: var(--surface-color);
      }
      #ordersTable tbody td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.55rem 0.75rem;
        border-bottom: 1px solid var(--border-color);
      }
      #ordersTable tbody td:last-child { border-bottom: 0; }
      #ordersTable tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        font-size: 0.8rem;
        color: var(--muted-color);
        margin-right: 0.75rem;
        flex-shrink: 0;
      }
      #ordersTable tbody td[data-label="Actions"] .btn-group { width: 100%; display: flex; gap: 0.5rem; }
      #ordersTable tbody td[data-label="Actions"] .btn { flex: 1 1 auto; }
    }
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 orders-admin">
    <div class="page-shell">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4">
            <div>
                <h2 class="h4 page-title">Manage Orders</h2>
                <p class="page-subtitle">Search, filter, edit and maintain orders across your product system.</p>
            </div>
            <div class="mt-2 mt-md-0 d-flex flex-wrap" style="gap: .5rem;">
                <span class="btn btn-outline-secondary" style="pointer-events:none;">
                    Total: <strong><?php echo (int)($orders['total'] ?? 0); ?></strong>
                </span>
                <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-outline-primary" id="refreshBtn">
                    <i class="fas fa-sync-alt mr-2"></i> Refresh
                </a>
            </div>
        </div>

        <div class="card shadow-sm mb-3 mb-md-4">
            <div class="card-header py-3">
                <div class="font-weight-600">Filters</div>
                <div class="text-muted small">Use targeted filters for precision, or leave blank to see everything.</div>
            </div>
            <div class="card-body">
                <form method="get" action="" class="mb-0" novalidate>
                    <input type="hidden" name="controller" value="order" />
                    <input type="hidden" name="action" value="adminIndex" />
                    <?php
                        $st = $filters['status'] ?? '';
                        $ps = $filters['payment_status'] ?? '';
                        $pm = $filters['payment_method'] ?? '';
                    ?>
                    <div class="form-row">
                        <div class="form-group col-12 col-sm-6 col-md-2">
                            <label class="small font-weight-600 text-muted mb-1">Order ID</label>
                            <input type="text" name="order_id" value="<?php echo htmlspecialchars($filters['order_id'] ?? ''); ?>" class="form-control" placeholder="#123" inputmode="numeric" />
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-2">
                            <label class="small font-weight-600 text-muted mb-1">Name</label>
                            <input type="text" name="customer_name" value="<?php echo htmlspecialchars($filters['customer_name'] ?? ''); ?>" class="form-control" placeholder="Customer name" />
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-2">
                            <label class="small font-weight-600 text-muted mb-1">Email</label>
                            <input type="text" name="email" value="<?php echo htmlspecialchars($filters['email'] ?? ''); ?>" class="form-control" placeholder="email@example.com" inputmode="email" />
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-2">
                            <label class="small font-weight-600 text-muted mb-1">Status</label>
                            <select name="status" class="custom-select">
                                <option value="">All</option>
                                <option value="pending" <?php echo $st==='pending'?'selected':''; ?>>Pending</option>
                                <option value="processing" <?php echo $st==='processing'?'selected':''; ?>>Processing</option>
                                <option value="shipped" <?php echo $st==='shipped'?'selected':''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $st==='delivered'?'selected':''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $st==='cancelled'?'selected':''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-2">
                            <label class="small font-weight-600 text-muted mb-1">Payment Method</label>
                            <select name="payment_method" class="custom-select">
                                <option value="">All</option>
                                <option value="cod" <?php echo $pm==='cod'?'selected':''; ?>>COD</option>
                                <option value="card" <?php echo $pm==='card'?'selected':''; ?>>Card</option>
                                <option value="upi" <?php echo $pm==='upi'?'selected':''; ?>>UPI</option>
                                <option value="netbanking" <?php echo $pm==='netbanking'?'selected':''; ?>>Netbanking</option>
                            </select>
                            <small class="text-muted">Options shown are common; filter matches exact values.</small>
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-2">
                            <label class="small font-weight-600 text-muted mb-1">Payment Status</label>
                            <select name="payment_status" class="custom-select">
                                <option value="">All</option>
                                <option value="pending" <?php echo $ps==='pending'?'selected':''; ?>>Pending</option>
                                <option value="paid" <?php echo $ps==='paid'?'selected':''; ?>>Paid</option>
                                <option value="failed" <?php echo $ps==='failed'?'selected':''; ?>>Failed</option>
                                <option value="refunded" <?php echo $ps==='refunded'?'selected':''; ?>>Refunded</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12 col-sm-6 col-md-2">
                            <label class="small font-weight-600 text-muted mb-1">From</label>
                            <input type="date" name="date_from" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>" class="form-control" />
                        </div>
                        <div class="form-group col-12 col-sm-6 col-md-2">
                            <label class="small font-weight-600 text-muted mb-1">To</label>
                            <input type="date" name="date_to" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>" class="form-control" />
                        </div>
                        <div class="form-group col-12 col-md-8 d-flex align-items-end" style="gap: .5rem;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php flash('order_success'); ?>
        <?php flash('order_error', '', 'alert alert-danger'); ?>

        <div class="card shadow-sm">
            <div class="card-header py-3">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center" style="gap: .75rem;">
                    <div>
                        <div class="font-weight-600">All Orders</div>
                        <div class="text-muted small">Status and payment can be updated directly from the Edit modal.</div>
                    </div>
                    <div class="text-muted small">Showing: <strong><?php echo (int)count($orders['data'] ?? []); ?></strong></div>
                </div>
            </div>
            <div class="orders-table-scroll">
                <div class="table-responsive">
                    <table id="ordersTable" class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 90px;">Order ID</th>
                                <th>Customer</th>
                                <th style="width: 120px;">Status</th>
                                <th style="width: 120px;">Payment</th>
                                <th style="width: 110px;">Total</th>
                                <th style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders['data'])): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">No orders found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach(($orders['data'] ?? []) as $order):
                                    $customerName = htmlspecialchars(trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? '')));
                                    $email = htmlspecialchars($order['email'] ?? '');
                                    $status = $order['status'] ?? '';
                                    $paymentStatus = $order['payment_status'] ?? '';
                                    $paymentMethod = $order['payment_method'] ?? '';

                                    $statusClass = 'secondary';
                                    switch($status) {
                                        case 'pending': $statusClass = 'warning text-dark'; break;
                                        case 'processing': $statusClass = 'info'; break;
                                        case 'shipped': $statusClass = 'primary'; break;
                                        case 'delivered': $statusClass = 'success'; break;
                                        case 'cancelled': $statusClass = 'danger'; break;
                                    }

                                    $payClass = 'secondary';
                                    switch($paymentStatus) {
                                        case 'pending': $payClass = 'warning text-dark'; break;
                                        case 'paid': $payClass = 'success'; break;
                                        case 'failed': $payClass = 'danger'; break;
                                        case 'refunded': $payClass = 'info'; break;
                                    }
                                ?>
                                <tr id="order-row-<?php echo (int)$order['id']; ?>">
                                    <td data-label="Order ID">
                                        <a class="font-weight-600 text-primary" href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo (int)$order['id']; ?>">
                                            #<?php echo (int)$order['id']; ?>
                                        </a>
                                        <div class="text-muted small"><?php echo !empty($order['created_at']) ? date('M d, Y', strtotime($order['created_at'])) : '—'; ?></div>
                                    </td>
                                    <td data-label="Customer">
                                        <div class="font-weight-600"><?php echo $customerName ?: '—'; ?></div>
                                        <div class="text-muted small"><?php echo $email ?: '—'; ?></div>
                                    </td>
                                    <td data-label="Status">
                                        <span class="badge badge-<?php echo $statusClass; ?> status-badge" data-role="status-badge">
                                            <?php echo ucfirst($status ?: '—'); ?>
                                        </span>
                                    </td>
                                    <td data-label="Payment">
                                        <span class="badge badge-<?php echo $payClass; ?> payment-badge" data-role="payment-badge">
                                            <?php echo ucfirst($paymentStatus ?: '—'); ?>
                                        </span>
                                        <div class="text-muted small" data-role="payment-method"><?php echo htmlspecialchars($paymentMethod ?: '—'); ?></div>
                                    </td>
                                    <td data-label="Total">
                                        <span class="font-weight-600"><?php echo formatPrice($order['total_amount'] ?? 0); ?></span>
                                    </td>
                                    <td data-label="Actions" onclick="event.stopPropagation();">
                                        <div class="btn-group btn-group-sm" role="group" aria-label="Order actions">
                                            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo (int)$order['id']; ?>" class="btn btn-outline-secondary">
                                                <i class="fas fa-eye"></i> <span class="d-none d-sm-inline">View</span>
                                            </a>
                                            <button
                                                type="button"
                                                class="btn btn-outline-primary edit-order"
                                                data-id="<?php echo (int)$order['id']; ?>"
                                                data-status="<?php echo htmlspecialchars($status); ?>"
                                                data-payment_status="<?php echo htmlspecialchars($paymentStatus); ?>"
                                                data-payment_method="<?php echo htmlspecialchars($paymentMethod); ?>"
                                            >
                                                <i class="fas fa-edit"></i> <span class="d-none d-sm-inline">Edit</span>
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-outline-danger delete-order"
                                                data-id="<?php echo (int)$order['id']; ?>"
                                                data-name="#<?php echo (int)$order['id']; ?>"
                                            >
                                                <i class="fas fa-trash"></i> <span class="d-none d-sm-inline">Delete</span>
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

            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-center justify-content-md-between align-items-center">
                    <div class="mb-2 mb-md-0 small text-muted">
                        Page <?php echo (int)($orders['current_page'] ?? 1); ?> of <?php echo (int)($orders['total_pages'] ?? 1); ?>
                    </div>
                    <div class="d-flex flex-wrap">
                        <?php
                        $qs = [];
                        foreach (['order_id','customer_name','email','status','payment_status','payment_method','date_from','date_to','q'] as $k) {
                            if (!empty($filters[$k])) { $qs[$k] = $filters[$k]; }
                        }
                        $base = BASE_URL . '?controller=order&action=adminIndex' . (empty($qs)?'':('&'.http_build_query($qs)));
                        echo getPaginationLinks($orders['current_page'], $orders['total_pages'], $base);
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm mt-3 mt-md-4">
            <div class="card-header py-3">
                <div class="font-weight-600">Style Guide</div>
                <div class="text-muted small">Consistent with your admin dashboard tokens (Inter, rounded corners, clear focus rings).</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="text-muted small mb-2">Colors</div>
                        <div class="d-flex flex-wrap" style="gap: .5rem;">
                            <div class="p-2" style="border:1px solid var(--border-color); border-radius: 12px; min-width: 160px;">
                                <div class="small text-muted">Primary</div>
                                <div class="font-weight-600">#3b82f6</div>
                            </div>
                            <div class="p-2" style="border:1px solid var(--border-color); border-radius: 12px; min-width: 160px;">
                                <div class="small text-muted">Success</div>
                                <div class="font-weight-600">#198754</div>
                            </div>
                            <div class="p-2" style="border:1px solid var(--border-color); border-radius: 12px; min-width: 160px;">
                                <div class="small text-muted">Danger</div>
                                <div class="font-weight-600">#dc3545</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 mt-3 mt-md-0">
                        <div class="text-muted small mb-2">Buttons</div>
                        <div class="text-muted small">
                            Use outlined buttons for secondary actions; primary button for filtering; focus rings always visible for keyboard navigation.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editOrderModal" tabindex="-1" role="dialog" aria-labelledby="editOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="text-muted small mb-3">Update status and payment details. Customer info and items are available in View.</div>
                <form id="editOrderForm" action="<?php echo BASE_URL; ?>?controller=order&action=adminUpdate" method="POST" novalidate>
                    <input type="hidden" name="id" id="edit_order_id" value="">
                    <div class="form-group">
                        <label for="edit_status" class="mb-1">Order Status</label>
                        <select class="custom-select" id="edit_status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_payment_status" class="mb-1">Payment Status</label>
                        <select class="custom-select" id="edit_payment_status" name="payment_status" required>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_payment_method" class="mb-1">Payment Method</label>
                        <input type="text" class="form-control" id="edit_payment_method" name="payment_method" placeholder="e.g. cod / upi / card" />
                        <small class="text-muted">Saved exactly as entered. Use consistent codes for better filtering.</small>
                    </div>
                </form>
                <div class="alert alert-danger d-none" id="editOrderError" role="alert"></div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
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
        <div class="modal-content" style="border-radius: 14px; overflow: hidden;">
            <div class="modal-header" style="background: rgba(220,53,69,0.08); border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title" id="deleteOrderModalLabel">Delete Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">Are you sure you want to delete <strong id="orderNameToDelete"></strong>?</div>
                <div class="text-muted small">This action cannot be undone.</div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
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

<script>
(function() {
    var BASE_URL = '<?php echo BASE_URL; ?>';

    function safeJson(response) {
        return response.text().then(function(text) {
            try { return { ok: response.ok, data: JSON.parse(text) }; }
            catch (e) { return { ok: false, data: { success: false, message: 'Invalid server response' }, raw: text }; }
        });
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

            $('#editOrderModal').modal('show');
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
            $('#deleteOrderModal').modal('show');
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

                        if (statusBadge) { statusBadge.textContent = (order.status || '—').charAt(0).toUpperCase() + (order.status || '—').slice(1); }
                        if (payBadge) { payBadge.textContent = (order.payment_status || '—').charAt(0).toUpperCase() + (order.payment_status || '—').slice(1); }
                        if (payMethod) { payMethod.textContent = order.payment_method ? order.payment_method : '—'; }

                        var editBtn = row.querySelector('.edit-order');
                        if (editBtn) {
                            editBtn.setAttribute('data-status', order.status || '');
                            editBtn.setAttribute('data-payment_status', order.payment_status || '');
                            editBtn.setAttribute('data-payment_method', order.payment_method || '');
                        }
                    }

                    $('#editOrderModal').modal('hide');
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
                    $('#deleteOrderModal').modal('hide');
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
})();
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
