<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Orders admin – trending modern UI */
.orders-admin .card { border: none; box-shadow: 0 4px 20px rgba(0,0,0,.08); transition: box-shadow .3s ease; }
.orders-admin .card:hover { box-shadow: 0 8px 30px rgba(0,0,0,.1); }
.orders-admin .card-header {
  background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
  border: none;
  padding: 1rem 1.25rem;
}
.orders-admin .card-body { padding: 1rem; }
@media (min-width: 768px) { .orders-admin .card-body { padding: 1.25rem; } }
.orders-table-scroll {
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,.06);
  background: #fff;
}
.orders-table-scroll .table { margin-bottom: 0; border-radius: 12px; }
.orders-table-scroll thead th {
  position: sticky;
  top: 0;
  z-index: 1;
  white-space: nowrap;
  font-weight: 600;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 0.85rem 1rem;
  background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
  color: #475569;
  border-bottom: 2px solid #e2e8f0;
}
.orders-table-scroll tbody td { padding: 0.75rem 1rem; vertical-align: middle; font-size: 0.9rem; }
.orders-table-scroll tbody tr { transition: background .15s ease; }
.orders-table-scroll tbody tr:hover { background: rgba(79, 70, 229, 0.04) !important; }
.order-actions .btn { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; padding: 0 !important; border-radius: 8px; transition: transform .2s; }
.order-actions .btn:hover { transform: scale(1.08); }
.order-actions .badge, .orders-admin .badge { min-width: 70px; padding: 0.4rem 0.6rem; font-weight: 600; font-size: 0.75rem; border-radius: 6px; }
.orders-admin .btn-light { background: rgba(255,255,255,.95); color: #4f46e5; font-weight: 600; }
.orders-admin .btn-light:hover { background: #fff; color: #4338ca; }
.orders-admin .btn-primary { background: linear-gradient(135deg, #4f46e5, #7c3aed); border: none; }
.orders-admin .btn-primary:hover { background: linear-gradient(135deg, #4338ca, #6d28d9); }
.orders-admin .form-control:focus { border-color: #7c3aed; box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25); }
@media (max-width: 575.98px) { .orders-table-scroll { max-height: 55vh; } }
@media (min-width: 576px) and (max-width: 991.98px) { .orders-table-scroll { max-height: 60vh; } }
@media (min-width: 992px) { .orders-table-scroll { max-height: 70vh; } }
@media (min-width: 576px) and (max-width: 991.98px) {
  #ordersTable th:nth-child(4), #ordersTable td:nth-child(4),
  #ordersTable th:nth-child(7), #ordersTable td:nth-child(7) { display: none !important; }
}
@media (max-width: 575.98px) {
  #ordersTable thead { display: none; }
  #ordersTable tbody tr {
    display: block;
    margin-bottom: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: box-shadow .2s;
  }
  #ordersTable tbody tr:active { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
  #ordersTable tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.6rem 1rem;
    border-bottom: 1px solid #f1f5f9;
  }
  #ordersTable tbody td:last-child { border-bottom: 0; }
  #ordersTable tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    font-size: 0.75rem;
    color: #64748b;
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  #ordersTable tbody td[data-label="Actions"] { flex-wrap: wrap; }
  #ordersTable tbody td[data-label="Actions"] .order-actions { width: 100%; justify-content: flex-end; flex-wrap: wrap; }
}
.orders-admin .pagination { flex-wrap: wrap; }
.orders-admin .page-link { border-radius: 8px !important; margin: 0 2px; }
.orders-admin .page-item.active .page-link { background: linear-gradient(135deg, #4f46e5, #7c3aed); border-color: transparent; }
.orders-admin .gap-1 > * + * { margin-left: 0.25rem; }
.orders-admin .gap-2 > * + * { margin-left: 0.5rem; }
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 orders-admin">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0 overflow-hidden">
                <div class="card-header text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <h3 class="card-title mb-0 h5 d-flex align-items-center">
                        <i class="fas fa-shopping-cart mr-2 opacity-90"></i> Orders
                        <?php if (isset($orders['total'])): ?>
                            <span class="badge badge-light text-dark ml-2 font-weight-normal"><?php echo (int)$orders['total']; ?> total</span>
                        <?php endif; ?>
                    </h3>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-light btn-sm">
                        <i class="fas fa-sync-alt mr-1"></i> Refresh
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="get" action="" class="mb-4">
                        <input type="hidden" name="controller" value="order" />
                        <input type="hidden" name="action" value="adminIndex" />
                        <div class="row">
                            <div class="col-12 col-sm-6 col-md-3 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-secondary">Search</label>
                                <input type="text" name="q" value="<?php echo htmlspecialchars($filters['q'] ?? ''); ?>" class="form-control form-control-sm" placeholder="Order ID, Name, Email" />
                            </div>
                            <div class="col-6 col-sm-4 col-md-2 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-secondary">Status</label>
                                <select name="status" class="form-control form-control-sm">
                                    <?php $st = $filters['status'] ?? ''; ?>
                                    <option value="">All</option>
                                    <option value="pending" <?php echo $st==='pending'?'selected':''; ?>>Pending</option>
                                    <option value="processing" <?php echo $st==='processing'?'selected':''; ?>>Processing</option>
                                    <option value="shipped" <?php echo $st==='shipped'?'selected':''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo $st==='delivered'?'selected':''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo $st==='cancelled'?'selected':''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-6 col-sm-4 col-md-2 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-secondary">Payment</label>
                                <select name="payment_status" class="form-control form-control-sm">
                                    <?php $ps = $filters['payment_status'] ?? ''; ?>
                                    <option value="">All</option>
                                    <option value="pending" <?php echo $ps==='pending'?'selected':''; ?>>Pending</option>
                                    <option value="paid" <?php echo $ps==='paid'?'selected':''; ?>>Paid</option>
                                    <option value="failed" <?php echo $ps==='failed'?'selected':''; ?>>Failed</option>
                                    <option value="refunded" <?php echo $ps==='refunded'?'selected':''; ?>>Refunded</option>
                                </select>
                            </div>
                            <div class="col-6 col-sm-4 col-md-2 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-secondary">From</label>
                                <input type="date" name="date_from" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>" class="form-control form-control-sm" />
                            </div>
                            <div class="col-6 col-sm-4 col-md-2 mb-2 mb-md-0">
                                <label class="small font-weight-bold text-secondary">To</label>
                                <input type="date" name="date_to" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>" class="form-control form-control-sm" />
                            </div>
                            <div class="col-6 col-sm-4 col-md-1 d-flex align-items-end mb-2 mb-md-0">
                                <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-filter"></i> Filter</button>
                            </div>
                            <div class="col-6 col-sm-4 col-md-1 d-flex align-items-end">
                                <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-outline-secondary btn-sm w-100" title="Reset"><i class="fas fa-undo"></i></a>
                            </div>
                        </div>
                    </form>
                    <?php flash('order_success'); ?>
                    <?php flash('order_error', '', 'alert alert-danger'); ?>

                    <?php if(empty($orders['data'])): ?>
                        <div class="alert alert-info mb-0 d-flex align-items-center">
                            <i class="fas fa-info-circle mr-2 fa-lg"></i> No orders found.
                        </div>
                    <?php else: ?>
                        <?php
                        $page = (int)($orders['current_page'] ?? 1);
                        $perPage = (int)($orders['per_page'] ?? 20);
                        ?>
                        <div class="orders-table-scroll table-responsive">
                            <table id="ordersTable" class="table table-striped table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th style="width: 80px;">Order ID</th>
                                        <th>Customer</th>
                                        <th style="width: 120px;">Date</th>
                                        <th style="width: 100px;">Total</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 100px;">Payment</th>
                                        <th style="width: 180px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders['data'] as $idx => $order): 
                                        $rowNum = ($page - 1) * $perPage + $idx + 1;
                                    ?>
                                        <tr>
                                            <td data-label="#"><?php echo $rowNum; ?></td>
                                            <td data-label="Order ID"><span class="font-weight-bold text-primary">#<?php echo $order['id']; ?></span></td>
                                            <td data-label="Customer"><?php echo htmlspecialchars(trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''))); ?></td>
                                            <td data-label="Date"><?php echo !empty($order['created_at']) ? date('M d, Y H:i', strtotime($order['created_at'])) : '—'; ?></td>
                                            <td data-label="Total"><span class="font-weight-bold"><?php echo formatPrice($order['total_amount'] ?? 0); ?></span></td>
                                            <td data-label="Status">
                                                <?php
                                                $statusClass = 'bg-secondary';
                                                if (!empty($order['status'])) {
                                                    switch($order['status']) {
                                                        case 'pending': $statusClass = 'bg-warning text-dark'; break;
                                                        case 'processing': $statusClass = 'bg-info'; break;
                                                        case 'shipped': $statusClass = 'bg-primary'; break;
                                                        case 'delivered': $statusClass = 'bg-success'; break;
                                                        case 'cancelled': $statusClass = 'bg-danger'; break;
                                                    }
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($order['status'] ?? '—'); ?></span>
                                            </td>
                                            <td data-label="Payment">
                                                <?php
                                                $paymentClass = 'bg-secondary';
                                                if (!empty($order['payment_status'])) {
                                                    switch($order['payment_status']) {
                                                        case 'pending': $paymentClass = 'bg-warning text-dark'; break;
                                                        case 'paid': $paymentClass = 'bg-success'; break;
                                                        case 'failed': $paymentClass = 'bg-danger'; break;
                                                        case 'refunded': $paymentClass = 'bg-info'; break;
                                                    }
                                                }
                                                ?>
                                                <span class="badge <?php echo $paymentClass; ?>"><?php echo ucfirst($order['payment_status'] ?? '—'); ?></span>
                                            </td>
                                            <td data-label="Actions">
                                                <div class="order-actions d-flex flex-wrap">
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary mr-1" title="View"><i class="fas fa-eye"></i></a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=updateStatus&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-secondary mr-1" title="Status"><i class="fas fa-sync-alt"></i></a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=updatePaymentStatus&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-info mr-1" title="Payment"><i class="fas fa-credit-card"></i></a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=delete&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-danger delete-order" title="Delete" data-id="<?php echo $order['id']; ?>"><i class="fas fa-trash-alt"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 d-flex flex-wrap justify-content-center justify-content-md-between align-items-center">
                            <div class="mb-2 mb-md-0 small text-muted">
                                Page <?php echo $page; ?> of <?php echo $orders['total_pages'] ?? 1; ?>
                            </div>
                            <div class="d-flex flex-wrap">
                                <?php
                                $qs = [];
                                foreach (['q','status','payment_status','date_from','date_to'] as $k) {
                                    if (!empty($filters[$k])) { $qs[$k] = $filters[$k]; }
                                }
                                $base = BASE_URL . '?controller=order&action=adminIndex' . (empty($qs)?'':('&'.http_build_query($qs)));
                                echo getPaginationLinks($orders['current_page'], $orders['total_pages'], $base);
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete order with confirmation
    document.querySelectorAll('.delete-order').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var orderId = this.getAttribute('data-id');
            var orderRow = this.closest('tr');
            var self = this;

            if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                var originalHtml = self.innerHTML;
                self.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                self.classList.add('disabled');

                fetch('<?php echo BASE_URL; ?>?controller=order&action=delete&id=' + orderId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success) {
                        var alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show';
                        alert.role = 'alert';
                        alert.innerHTML = data.message + ' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                        var table = document.querySelector('.orders-table-scroll');
                        if (table && table.parentNode) {
                            table.parentNode.insertBefore(alert, table);
                        }
                        orderRow.remove();
                        setTimeout(function() {
                            var al = document.querySelector('.alert-success');
                            if (al) al.remove();
                        }, 4000);
                        if (document.querySelectorAll('#ordersTable tbody tr').length === 0) {
                            var tbody = document.querySelector('#ordersTable tbody');
                            if (tbody) {
                                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4">No orders found.</td></tr>';
                            }
                        }
                    } else {
                        alert(data.message || 'Failed to delete order');
                        self.innerHTML = originalHtml;
                        self.classList.remove('disabled');
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the order');
                    self.innerHTML = originalHtml;
                    self.classList.remove('disabled');
                });
            }
        });
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
