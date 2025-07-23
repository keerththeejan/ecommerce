<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $order['id']; ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Order
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Update Order Status - Order #<?php echo $order['id']; ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>?controller=order&action=updateStatus&id=<?php echo $order['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="status" class="form-label">Order Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" <?php echo ($order['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="processing" <?php echo ($order['status'] == 'processing') ? 'selected' : ''; ?>>Processing</option>
                                <option value="shipped" <?php echo ($order['status'] == 'shipped') ? 'selected' : ''; ?>>Shipped</option>
                                <option value="delivered" <?php echo ($order['status'] == 'delivered') ? 'selected' : ''; ?>>Delivered</option>
                                <option value="cancelled" <?php echo ($order['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>
                        
                        <div class="alert alert-info">
                            <h5>Status Information:</h5>
                            <ul>
                                <li><strong>Pending</strong>: Order has been placed but not yet processed.</li>
                                <li><strong>Processing</strong>: Order is being prepared for shipping.</li>
                                <li><strong>Shipped</strong>: Order has been shipped to the customer.</li>
                                <li><strong>Delivered</strong>: Order has been delivered to the customer.</li>
                                <li><strong>Cancelled</strong>: Order has been cancelled.</li>
                            </ul>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $order['id']; ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
