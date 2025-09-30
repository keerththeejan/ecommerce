<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-times me-2"></i>Cancel Order</h5>
                    <span class="badge bg-light text-danger">Order #<?php echo (int)$order['id']; ?></span>
                </div>
                <div class="card-body">
                    <?php flash('order_error', '', 'alert alert-danger'); ?>
                    <div class="alert alert-warning d-flex align-items-start" role="alert">
                        <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                        <div>
                            <strong>Are you sure you want to cancel this order?</strong>
                            <div class="small text-muted">This action cannot be undone. If the order has already been processed or shipped, cancellation may not be possible.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Order Summary</h6>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Order ID:</strong> #<?php echo (int)$order['id']; ?></li>
                            <li><strong>Date:</strong> <?php echo date('F j, Y, g:i a', strtotime($order['created_at'])); ?></li>
                            <li><strong>Status:</strong> <?php echo htmlspecialchars(ucfirst($order['status'])); ?></li>
                            <li><strong>Payment Status:</strong> <?php echo htmlspecialchars(ucfirst($order['payment_status'])); ?></li>
                            <li><strong>Total:</strong> <?php echo formatCurrency($order['total_amount']); ?></li>
                        </ul>
                    </div>

                    <form action="<?php echo BASE_URL; ?>?controller=order&action=cancel&id=<?php echo (int)$order['id']; ?>" method="POST">
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=show&id=<?php echo (int)$order['id']; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Order
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times me-2"></i>Confirm Cancel Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
