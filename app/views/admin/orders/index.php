<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Orders</h3>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="get" action="" class="row g-3 mb-3">
                        <input type="hidden" name="controller" value="order" />
                        <input type="hidden" name="action" value="adminIndex" />
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" name="q" value="<?php echo htmlspecialchars($filters['q'] ?? ''); ?>" class="form-control" placeholder="Order ID, Name, Email" />
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <?php $st = $filters['status'] ?? ''; ?>
                                <option value="">All</option>
                                <option value="pending" <?php echo $st==='pending'?'selected':''; ?>>Pending</option>
                                <option value="processing" <?php echo $st==='processing'?'selected':''; ?>>Processing</option>
                                <option value="shipped" <?php echo $st==='shipped'?'selected':''; ?>>Shipped</option>
                                <option value="delivered" <?php echo $st==='delivered'?'selected':''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo $st==='cancelled'?'selected':''; ?>>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Payment</label>
                            <select name="payment_status" class="form-select">
                                <?php $ps = $filters['payment_status'] ?? ''; ?>
                                <option value="">All</option>
                                <option value="pending" <?php echo $ps==='pending'?'selected':''; ?>>Pending</option>
                                <option value="paid" <?php echo $ps==='paid'?'selected':''; ?>>Paid</option>
                                <option value="failed" <?php echo $ps==='failed'?'selected':''; ?>>Failed</option>
                                <option value="refunded" <?php echo $ps==='refunded'?'selected':''; ?>>Refunded</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">From</label>
                            <input type="date" name="date_from" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>" class="form-control" />
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">To</label>
                            <input type="date" name="date_to" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>" class="form-control" />
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-outline-secondary w-100" title="Reset"><i class="fas fa-undo"></i></a>
                        </div>
                    </form>
                    <?php flash('order_success'); ?>
                    <?php flash('order_error', '', 'alert alert-danger'); ?>
                    
                    <?php if(empty($orders['data'])): ?>
                        <div class="alert alert-info">No orders found.</div>
                    <?php else: ?>
                        <style>
                        /* Mobile-first responsive table styling */
                        @media (max-width: 576.98px) {
                            table.responsive-table thead { display: none; }
                            table.responsive-table,
                            table.responsive-table tbody,
                            table.responsive-table tr,
                            table.responsive-table td { display: block; width: 100%; }
                            table.responsive-table tr {
                                margin-bottom: 1rem;
                                border: 1px solid rgba(0,0,0,.075);
                                border-radius: .5rem;
                                overflow: hidden;
                                background: var(--bg-color, #fff);
                            }
                            table.responsive-table td {
                                padding: .5rem .75rem;
                                border: none;
                                border-bottom: 1px solid rgba(0,0,0,.05);
                            }
                            table.responsive-table td:last-child { border-bottom: 0; }
                            table.responsive-table td::before {
                                content: attr(data-label);
                                font-weight: 600;
                                display: block;
                                margin-bottom: .25rem;
                                opacity: .8;
                            }
                            .order-actions { display: flex; gap: .5rem; flex-wrap: wrap; }
                        }
                        </style>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover responsive-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($orders['data'] as $order): ?>
                                        <tr>
                                            <td data-label="Order ID">#<?php echo $order['id']; ?></td>
                                            <td data-label="Customer"><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                                            <td data-label="Date"><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                            <td data-label="Total"><?php echo formatPrice($order['total_amount']); ?></td>
                                            <td data-label="Status">
                                                <?php
                                                $statusClass = '';
                                                switch($order['status']) {
                                                    case 'pending':
                                                        $statusClass = 'bg-warning';
                                                        break;
                                                    case 'processing':
                                                        $statusClass = 'bg-info';
                                                        break;
                                                    case 'shipped':
                                                        $statusClass = 'bg-primary';
                                                        break;
                                                    case 'delivered':
                                                        $statusClass = 'bg-success';
                                                        break;
                                                    case 'cancelled':
                                                        $statusClass = 'bg-danger';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($order['status']); ?></span>
                                            </td>
                                            <td data-label="Payment">
                                                <?php
                                                $paymentClass = '';
                                                switch($order['payment_status']) {
                                                    case 'pending':
                                                        $paymentClass = 'bg-warning';
                                                        break;
                                                    case 'paid':
                                                        $paymentClass = 'bg-success';
                                                        break;
                                                    case 'failed':
                                                        $paymentClass = 'bg-danger';
                                                        break;
                                                    case 'refunded':
                                                        $paymentClass = 'bg-info';
                                                        break;
                                                }
                                                ?>
                                                <span class="badge <?php echo $paymentClass; ?>"><?php echo ucfirst($order['payment_status']); ?></span>
                                            </td>
                                            <td data-label="Actions">
                                                <div class="order-actions">
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-primary" title="View Order">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=updateStatus&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-secondary" title="Update Status">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=updatePaymentStatus&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info" title="Update Payment">
                                                        <i class="fas fa-credit-card"></i>
                                                    </a>
                                                    <a href="<?php echo BASE_URL; ?>?controller=order&action=delete&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-danger delete-order" title="Delete Order" data-id="<?php echo $order['id']; ?>">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php
                            // Build query string to preserve filters
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
