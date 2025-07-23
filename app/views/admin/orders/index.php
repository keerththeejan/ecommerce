<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Orders</h3>
                </div>
                <div class="card-body">
                    <?php flash('order_success'); ?>
                    <?php flash('order_error', '', 'alert alert-danger'); ?>
                    
                    <?php if(empty($orders['data'])): ?>
                        <div class="alert alert-info">No orders found.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
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
                                            <td>#<?php echo $order['id']; ?></td>
                                            <td><?php echo $order['first_name'] . ' ' . $order['last_name']; ?></td>
                                            <td><?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></td>
                                            <td><?php echo formatPrice($order['total_amount']); ?></td>
                                            <td>
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
                                            <td>
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
                                            <td>
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
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-3">
                            <?php echo getPaginationLinks($orders['current_page'], $orders['total_pages'], BASE_URL . '?controller=order&action=adminIndex'); ?>
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
