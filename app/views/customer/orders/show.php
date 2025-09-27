<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">My Account</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=profile" class="list-group-item list-group-item-action">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=changePassword" class="list-group-item list-group-item-action">
                        <i class="fas fa-key me-2"></i> Change Password
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=history" class="list-group-item list-group-item-action active">
                        <i class="fas fa-shopping-bag me-2"></i> Order History
                    </a>
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=logout" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="mb-3">
                <a href="<?php echo BASE_URL; ?>?controller=order&action=history" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Orders
                </a>
                
                <?php if(in_array($order['order']['status'], ['pending','processing']) && $order['order']['payment_status'] == 'pending'): ?>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=cancel&id=<?php echo $order['order']['id']; ?>" class="btn btn-danger">
                        <i class="fas fa-times me-2"></i> Cancel Order
                    </a>
                <?php endif; ?>
                
                <?php if($order['order']['payment_status'] == 'pending' && $order['order']['payment_method'] != 'cod'): ?>
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=payment&id=<?php echo $order['order']['id']; ?>" class="btn btn-success">
                        <i class="fas fa-credit-card me-2"></i> Make Payment
                    </a>
                <?php endif; ?>

                <?php if($order['order']['status'] == 'cancelled'): ?>
                    <form action="<?php echo BASE_URL; ?>?controller=order&action=reconfirm&id=<?php echo $order['order']['id']; ?>" method="post" style="display:inline-block;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-undo me-2"></i> Re-confirm Order
                        </button>
                    </form>
                <?php endif; ?>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Order #<?php echo $order['order']['id']; ?> Details</h4>
                </div>
                <div class="card-body">
                    <?php flash('order_success'); ?>
                    <?php flash('order_error', '', 'alert alert-danger'); ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Order Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 150px;">Order Number:</th>
                                    <td>#<?php echo $order['order']['id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Date:</th>
                                    <td><?php echo date('F j, Y, g:i a', strtotime($order['order']['created_at'])); ?></td>
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
                                            default:
                                                $statusClass = 'bg-secondary';
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
                                            case 'paid':
                                                $paymentClass = 'bg-success';
                                                break;
                                            case 'pending':
                                                $paymentClass = 'bg-warning';
                                                break;
                                            case 'failed':
                                                $paymentClass = 'bg-danger';
                                                break;
                                            default:
                                                $paymentClass = 'bg-secondary';
                                        }
                                        ?>
                                        <span class="badge <?php echo $paymentClass; ?>"><?php echo ucfirst($order['order']['payment_status']); ?></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th style="width: 150px;">Name:</th>
                                    <td><?php echo $order['order']['first_name'] . ' ' . $order['order']['last_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo $order['order']['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Shipping Address:</th>
                                    <td><?php echo nl2br((string)($order['order']['shipping_address'] ?? '')); ?></td>
                                </tr>
                                <tr>
                                    <th>Billing Address:</th>
                                    <td><?php echo nl2br((string)($order['order']['billing_address'] ?? '')); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <h5>Order Items</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($order['items'] as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>?controller=product&action=show&id=<?php echo $item['product_id']; ?>">
                                                <?php echo $item['product_name']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $item['sku']; ?></td>
                                        <td><?php echo formatCurrency($item['price']); ?></td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td class="text-end"><?php echo formatCurrency($item['price'] * $item['quantity']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                    <td class="text-end"><?php echo formatCurrency($order['order']['total_amount'] - $order['order']['tax'] - $order['order']['shipping_fee']); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                    <td class="text-end"><?php echo formatCurrency($order['order']['tax']); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Shipping:</strong></td>
                                    <td class="text-end"><?php echo formatCurrency($order['order']['shipping_fee']); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong><?php echo formatCurrency($order['order']['total_amount']); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    <?php if(!empty($order['order']['notes'])): ?>
                        <div class="mt-4">
                            <h5>Order Notes</h5>
                            <div class="card">
                                <div class="card-body">
                                    <?php echo nl2br($order['order']['notes']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
