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
                    <h3 class="card-title mb-0">Update Payment Status - Order #<?php echo $order['id']; ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>?controller=order&action=updatePaymentStatus&id=<?php echo $order['id']; ?>" method="POST">
                        <div class="mb-3">
                            <label for="payment_status" class="form-label">Payment Status</label>
                            <select class="form-select" id="payment_status" name="payment_status" required>
                                <option value="pending" <?php echo ($order['payment_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="paid" <?php echo ($order['payment_status'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
                                <option value="failed" <?php echo ($order['payment_status'] == 'failed') ? 'selected' : ''; ?>>Failed</option>
                                <option value="refunded" <?php echo ($order['payment_status'] == 'refunded') ? 'selected' : ''; ?>>Refunded</option>
                            </select>
                        </div>
                        
                        <div class="alert alert-info">
                            <h5>Payment Status Information:</h5>
                            <ul>
                                <li><strong>Pending</strong>: Payment has not been received or processed yet.</li>
                                <li><strong>Paid</strong>: Payment has been received and confirmed.</li>
                                <li><strong>Failed</strong>: Payment attempt failed or was declined.</li>
                                <li><strong>Refunded</strong>: Payment was refunded to the customer.</li>
                            </ul>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminShow&id=<?php echo $order['id']; ?>" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Payment Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
