<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0">Delete Order #<?php echo $order['id']; ?></h3>
                </div>
                <div class="card-body">
                    <p>Are you sure you want to delete this order? This action cannot be undone.</p>
                    
                    <div class="alert alert-warning">
                        <strong>Warning:</strong> Deleting this order will also remove all associated order items.
                    </div>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Order Details</h5>
                            <p><strong>Customer:</strong> <?php echo $order['first_name'] . ' ' . $order['last_name']; ?></p>
                            <p><strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?></p>
                            <p><strong>Total:</strong> <?php echo formatPrice($order['total_amount']); ?></p>
                            <p>
                                <strong>Status:</strong> 
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
                            </p>
                        </div>
                    </div>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=order&action=delete&id=<?php echo $order['id']; ?>" method="POST" id="deleteForm">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-danger" id="deleteBtn">
                            <i class="fas fa-trash-alt"></i> Delete Order
                        </button>
                        <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteForm = document.getElementById('deleteForm');
    const deleteBtn = document.getElementById('deleteBtn');
    
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
                // Show loading state
                deleteBtn.disabled = true;
                deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                
                // Submit the form
                this.submit();
            }
        });
    }
});
</script>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
