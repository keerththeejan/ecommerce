<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="<?php echo BASE_URL; ?>?controller=order&action=adminIndex" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Order #<?php echo $order['order']['id']; ?></h3>
                    <div>
                        <a href="<?php echo BASE_URL; ?>?controller=order&action=updateStatus&id=<?php echo $order['order']['id']; ?>" class="btn btn-light">Update Status</a>
                        <a href="<?php echo BASE_URL; ?>?controller=order&action=updatePaymentStatus&id=<?php echo $order['order']['id']; ?>" class="btn btn-light">Update Payment</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php flash('order_success'); ?>
                    <?php flash('order_error', '', 'alert alert-danger'); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Order ID:</th>
                                    <td>#<?php echo $order['order']['id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td><?php echo date('M d, Y H:i', strtotime($order['order']['created_at'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <?php
                                        $statusClass = '';
                                        switch($order['order']['status']) {
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
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($order['order']['status']); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Payment Method:</th>
                                    <td><?php echo ucfirst($order['order']['payment_method']); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Status:</th>
                                    <td>
                                        <?php
                                        $paymentClass = '';
                                        switch($order['order']['payment_status']) {
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
                                        <span class="badge <?php echo $paymentClass; ?>"><?php echo ucfirst($order['order']['payment_status']); ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <table class="table">
                                <tr>
                                    <th>Name:</th>
                                    <td><?php echo $order['order']['first_name'] . ' ' . $order['order']['last_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $order['order']['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Shipping Address:</th>
                                    <td><?php echo $order['order']['shipping_address']; ?></td>
                                </tr>
                                <tr>
                                    <th>Billing Address:</th>
                                    <td><?php echo $order['order']['billing_address']; ?></td>
                                </tr>
                                <?php if(!empty($order['order']['notes'])): ?>
                                <tr>
                                    <th>Notes:</th>
                                    <td><?php echo $order['order']['notes']; ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                    
                    <h5 class="mt-4">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($order['items'] as $item): ?>
                                    <tr>
                                        <td><?php echo $item['product_name']; ?></td>
                                        <td><?php echo $item['sku']; ?></td>
                                        <td><?php echo formatPrice($item['price']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td class="text-end"><?php echo formatPrice($item['price'] * $item['quantity']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal:</th>
                                    <td class="text-end"><?php echo formatPrice($order['order']['total_amount'] - $order['order']['tax'] - $order['order']['shipping_fee']); ?></td>
                                </tr>
                                <?php if($order['order']['shipping_fee'] > 0): ?>
                                <tr>
                                    <th colspan="4" class="text-end">Shipping:</th>
                                    <td class="text-end"><?php echo formatPrice($order['order']['shipping_fee']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($order['order']['tax'] > 0): ?>
                                <tr>
                                    <th colspan="4" class="text-end">Tax:</th>
                                    <td class="text-end"><?php echo formatPrice($order['order']['tax']); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <td class="text-end"><strong><?php echo formatPrice($order['order']['total_amount']); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
