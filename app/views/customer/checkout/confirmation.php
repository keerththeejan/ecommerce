<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Order Confirmation</h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <h3 class="mb-3">Thank you for your order!</h3>
                        <p class="lead">Your order has been placed successfully.</p>
                        
                        <div class="order-details mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
                                    <p><strong>Order Date:</strong> <?php echo date('M d, Y h:i A', strtotime($order['created_at'])); ?></p>
                                    <p><strong>Payment Status:</strong> Paid</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Total Amount:</strong> <?php echo formatCurrency($order['total_amount']); ?></strong></p>
                                    <p><strong>Shipping Address:</strong></p>
                                    <p><?php echo htmlspecialchars($order['shipping_address']); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="<?php echo BASE_URL; ?>" class="btn btn-primary me-2">Continue Shopping</a>
                            <a href="<?php echo BASE_URL; ?>orders" class="btn btn-outline-primary">View Orders</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
