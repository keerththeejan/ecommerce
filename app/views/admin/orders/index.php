<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Orders admin – trending responsive */
.orders-admin .card-body { padding: 1rem; }
@media (min-width: 768px) { .orders-admin .card-body { padding: 1.25rem; } }
.orders-table-scroll {
  overflow: auto;
  -webkit-overflow-scrolling: touch;
  border-radius: 12px;
  border: 1px solid rgba(0,0,0,.08);
  box-shadow: inset 0 1px 3px rgba(0,0,0,.05);
}
.orders-table-scroll .table { margin-bottom: 0; border-radius: 12px; }
.orders-table-scroll thead th {
  position: sticky;
  top: 0;
  z-index: 1;
  white-space: nowrap;
  font-weight: 600;
  font-size: 0.85rem;
  padding: 0.75rem;
  background: var(--bs-body-bg, #fff);
  color: var(--bs-body-color, #212529);
  box-shadow: 0 1px 0 0 var(--bs-border-color, #dee2e6);
}
.orders-table-scroll tbody td { padding: 0.65rem 0.75rem; vertical-align: middle; }
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
    border: 1px solid var(--bs-border-color, #dee2e6);
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg, #fff);
    box-shadow: 0 2px 8px rgba(0,0,0,.06);
  }
  #ordersTable tbody td {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.75rem;
    border-bottom: 1px solid rgba(0,0,0,.06);
  }
  #ordersTable tbody td:last-child { border-bottom: 0; }
  #ordersTable tbody td::before {
    content: attr(data-label);
    font-weight: 600;
    font-size: 0.8rem;
    color: var(--bs-secondary, #6c757d);
    margin-right: 0.5rem;
    flex-shrink: 0;
  }
  #ordersTable tbody td[data-label="Actions"] { flex-wrap: wrap; gap: 0.25rem; }
  #ordersTable tbody td[data-label="Actions"] .order-actions { width: 100%; justify-content: flex-end; flex-wrap: wrap; }
}
.orders-admin .pagination { flex-wrap: wrap; gap: 0.25rem; }
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 orders-admin">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-header bg-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2 py-3">
                    <h3 class="card-title mb-0 h5">Orders</h3>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="get" action="" class="row g-3 mb-3">
                        <input type="hidden" name="controller" value="order" />
                        <input type="hidden" name="action" value="adminIndex" />
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label small">Search</label>
                            <input type="text" name="q" value="<?php echo htmlspecialchars($filters['q'] ?? ''); ?>" class="form-control form-control-sm" placeholder="Order ID, Name, Email" />
                        </div>
                        <div class="col-6 col-sm-4 col-md-2">
                            <label class="form-label small">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <?php $st = $filters['status'] ?? ''; ?>
                                <option value="">All</option>
                                <option value="pending" <?php echo $st==='pending'?'selected':''; ?>>Pending</option>
                                <option value="processing" <?php echo $st==='processing'?'selected':''; ?>>Processing</option>
                                <option value="shipped" <?php echo $st==='shipped'?'selected':''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $st==='delivered'?'selected':''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $st==='cancelled'?'selected':''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-4 col-md-2">
                            <label class="form-label small">Payment</label>
                            <select name="payment_status" class="form-select form-select-sm">
                                <?php $ps = $filters['payment_status'] ?? ''; ?>
                                <option value="">All</option>
                                <option value="pending" <?php echo $ps==='pending'?'selected':''; ?>>Pending</option>
                                <option value="paid" <?php echo $ps==='paid'?'selected':''; ?>>Paid</option>
                                <option value="failed" <?php echo $ps==='failed'?'selected':''; ?>>Failed</option>
                                <option value="refunded" <?php echo $ps==='refunded'?'selected':''; ?>>Refunded</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-4 col-md-2">
                            <label class="form-label small">From</label>
                            <input type="date" name="date_from" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>" class="form-control form-control-sm" />
                        </div>
                        <div class="col-6 col-sm-4 col-md-2">
                            <label class="form-label small">To</label>
                            <input type="date" name="date_to" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>" class="form-control form-control-sm" />
                        </div>
                        <div class="col-6 col-sm-4 col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fas fa-filter"></i></button>
                        </div>
                        <div class="col-6 col-sm-4 col-md-1 d-flex align-items-end">
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-outline-secondary btn-sm w-100" title="Reset"><i class="fas fa-undo"></i></a>
                        </div>
                    </form>
                    <?php flash('order_success'); ?>
                    <?php flash('order_error', '', 'alert alert-danger'); ?>

                    <?php if(empty($orders['data'])): ?>
                        <div class="alert alert-info mb-0">No orders found.</div>
                    <?php else: ?>
                        <?php
                        $page = (int)($orders['current_page'] ?? 1);
                        $perPage = (int)($orders['per_page'] ?? 20);
                        ?>
                        <div class="orders-table-scroll table-responsive">
                            <table id="ordersTable" class="table table-striped table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">#</th>
                                        <th style="width: 80px;">Order ID</th>
                                        <th>Customer</th>
                                        <th style="width: 110px;">Date</th>
                                        <th style="width: 90px;">Total</th>
                                        <th style="width: 90px;">Status</th>
                                        <th style="width: 90px;">Payment</th>
                                        <th style="width: 160px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders['data'] as $idx => $order): 
                                        $rowNum = ($page - 1) * $perPage + $idx + 1;
                                    ?>
                                        <tr>
                                            <td data-label="#"><?php echo $rowNum; ?></td>
                                            <td data-label="Order ID">#<?php echo $order['id']; ?></td>
                                            <td data-label="Customer"><?php echo htmlspecialchars(trim(($order['first_name'] ?? '') . ' ' . ($order['last_name'] ?? ''))); ?></td>
                                            <td data-label="Date"><?php echo !empty($order['created_at']) ? date('M d, Y H:i', strtotime($order['created_at'])) : '—'; ?></td>
                                            <td data-label="Total"><?php echo formatPrice($order['total_amount'] ?? 0); ?></td>
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
                                                <div class="order-actions d-flex flex-wrap gap-1">
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-primary" title="View"><i class="fas fa-eye"></i></a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=updateStatus&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-secondary" title="Status"><i class="fas fa-sync-alt"></i></a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=updatePaymentStatus&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-info" title="Payment"><i class="fas fa-credit-card"></i></a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=delete&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-danger delete-order" title="Delete" data-id="<?php echo $order['id']; ?>"><i class="fas fa-trash-alt"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3 d-flex flex-wrap justify-content-center justify-content-md-start">
                            <?php
                            $qs = [];
                            foreach (['q','status','payment_status','date_from','date_to'] as $k) {
                                if (!empty($filters[$k])) { $qs[$k] = $filters[$k]; }
                            }
                            $base = BASE_URL . '?controller=order&action=adminIndex' . (empty($qs)?'':('&'.http_build_query($qs)));
                            echo getPaginationLinks($orders['current_page'], $orders['total_pages'], $base);
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete order with confirmation
    document.querySelectorAll('.delete-order').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const orderId = this.getAttribute('data-id');
            const orderRow = this.closest('tr');
            
            if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                // Show loading state
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.classList.add('disabled');
                
                // Send AJAX request
                fetch(`?controller=order&action=delete&id=${orderId}`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ _method: 'DELETE' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show';
                        alert.role = 'alert';
                        alert.innerHTML = `
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        
                        // Insert alert before the table
                        const table = document.querySelector('.table');
                        table.parentNode.insertBefore(alert, table);
                        
                        // Remove the row
                        orderRow.remove();
                        
                        // If no more orders, show message
                        if (document.querySelectorAll('tbody tr').length === 0) {
                            const tbody = document.querySelector('tbody');
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="7" class="text-center">No orders found.</td>
                                </tr>
                            `;
                        }
                    } else {
                        // Show error message
                        alert(data.message || 'Failed to delete order');
                        this.innerHTML = originalHtml;
                        this.classList.remove('disabled');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the order');
                    this.innerHTML = originalHtml;
                    this.classList.remove('disabled');
                });
            }
        });
    });
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
