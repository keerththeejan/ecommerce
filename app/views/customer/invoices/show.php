<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Invoice Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>Invoice #<?php echo $invoice['invoice_number']; ?></h1>
                    <p class="text-muted mb-0">Order #<?php echo $invoice['order_number']; ?></p>
                </div>
                <div class="text-end">
                    <div class="btn-group">
                        <a href="<?php echo BASE_URL; ?>?controller=invoice&action=download&param=<?php echo $invoice['id']; ?>" class="btn btn-outline-primary">
                            <i class="fas fa-download me-2"></i>Download
                        </a>
                        <a href="<?php echo BASE_URL; ?>?controller=invoice&action=print&param=<?php echo $invoice['id']; ?>" target="_blank" class="btn btn-outline-secondary">
                            <i class="fas fa-print me-2"></i>Print
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Invoice Status -->
            <?php
            $statusClass = 'secondary';
            if($invoice['status'] == 'paid') $statusClass = 'success';
            if($invoice['status'] == 'overdue') $statusClass = 'danger';
            if($invoice['status'] == 'partially_paid') $statusClass = 'warning';
            ?>
            <div class="alert alert-<?php echo $statusClass; ?> d-flex justify-content-between align-items-center">
                <div>
                    <strong>Status:</strong> 
                    <span class="badge bg-<?php echo $statusClass; ?> ms-2">
                        <?php echo ucwords(str_replace('_', ' ', $invoice['status'])); ?>
                    </span>
                </div>
                <div>
                    <strong>Invoice Date:</strong> <?php echo date('M d, Y', strtotime($invoice['invoice_date'])); ?>
                    <span class="mx-2">|</span>
                    <strong>Due Date:</strong> <?php echo date('M d, Y', strtotime($invoice['due_date'])); ?>
                </div>
            </div>
            
            <div class="row mb-5">
                <!-- Billing Info -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Bill To</h5>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                <strong><?php echo htmlspecialchars($invoice['billing_name'] ?? $invoice['customer_name']); ?></strong><br>
                                <?php echo htmlspecialchars($invoice['billing_address'] ?? $invoice['customer_address']); ?><br>
                                <?php 
                                $billingCity = $invoice['billing_city'] ?? $invoice['customer_city'];
                                $billingState = $invoice['billing_state'] ?? $invoice['customer_state'];
                                $billingZip = $invoice['billing_zip'] ?? $invoice['customer_zip'];
                                $billingCountry = $invoice['billing_country'] ?? $invoice['customer_country'];
                                
                                echo htmlspecialchars(trim(implode(', ', array_filter([$billingCity, $billingState, $billingZip, $billingCountry]))));
                                ?><br>
                                <?php if(!empty($invoice['billing_phone'] ?? $invoice['customer_phone'])): ?>
                                    <i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($invoice['billing_phone'] ?? $invoice['customer_phone']); ?><br>
                                <?php endif; ?>
                                <i class="fas fa-envelope me-2"></i> <?php echo htmlspecialchars($invoice['customer_email']); ?>
                            </address>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Info -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Ship To</h5>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                <strong><?php echo htmlspecialchars($invoice['shipping_name'] ?? $invoice['customer_name']); ?></strong><br>
                                <?php echo htmlspecialchars($invoice['shipping_address'] ?? $invoice['customer_address']); ?><br>
                                <?php 
                                $shippingCity = $invoice['shipping_city'] ?? $invoice['customer_city'];
                                $shippingState = $invoice['shipping_state'] ?? $invoice['customer_state'];
                                $shippingZip = $invoice['shipping_zip'] ?? $invoice['customer_zip'];
                                $shippingCountry = $invoice['shipping_country'] ?? $invoice['customer_country'];
                                
                                echo htmlspecialchars(trim(implode(', ', array_filter([$shippingCity, $shippingState, $shippingZip, $shippingCountry]))));
                                ?><br>
                                <?php if(!empty($invoice['shipping_phone'] ?? $invoice['customer_phone'])): ?>
                                    <i class="fas fa-phone me-2"></i> <?php echo htmlspecialchars($invoice['shipping_phone'] ?? $invoice['customer_phone']); ?>
                                <?php endif; ?>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0" style="width: 50px">#</th>
                                <th class="border-0">Product</th>
                                <th class="text-center border-0" style="width: 100px">Qty</th>
                                <th class="text-end border-0" style="width: 150px">Unit Price</th>
                                <th class="text-end border-0" style="width: 150px">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $subtotal = 0;
                            $itemCount = 0;
                            
                            foreach($invoice['items'] as $index => $item): 
                                $itemTotal = $item['quantity'] * $item['price'];
                                $subtotal += $itemTotal;
                                $itemCount++;
                            ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if(!empty($item['product_image'])): ?>
                                                <img src="<?php echo BASE_URL . $item['product_image']; ?>" alt="<?php echo htmlspecialchars($item['product_name']); ?>" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-0"><?php echo htmlspecialchars($item['product_name']); ?></h6>
                                                <?php if(!empty($item['options'])): ?>
                                                    <small class="text-muted"><?php echo htmlspecialchars($item['options']); ?></small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                                    <td class="text-end"><?php echo formatCurrency($item['price']); ?></td>
                                    <td class="text-end"><?php echo formatCurrency($itemTotal); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" rowspan="4">
                                    <?php if(!empty($invoice['notes'])): ?>
                                        <div class="text-muted small">
                                            <strong>Notes:</strong><br>
                                            <?php echo nl2br(htmlspecialchars($invoice['notes'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <th class="text-end">Subtotal:</th>
                                <td class="text-end"><?php echo formatCurrency($subtotal); ?></td>
                            </tr>
                            <?php if($invoice['shipping_fee'] > 0): ?>
                                <tr>
                                    <th class="text-end">Shipping:</th>
                                    <td class="text-end"><?php echo formatCurrency($invoice['shipping_fee']); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if($invoice['tax_amount'] > 0): ?>
                                <tr>
                                    <th class="text-end">Tax (<?php echo $invoice['tax_rate']; ?>%):</th>
                                    <td class="text-end"><?php echo formatCurrency($invoice['tax_amount']); ?></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-end">Total:</th>
                                <td class="text-end">
                                    <h4 class="mb-0"><?php echo formatCurrency($invoice['total_amount']); ?></h4>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Payment Info -->
            <?php if(!empty($invoice['payment_method']) || !empty($invoice['payment_status'])): ?>
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if(!empty($invoice['payment_method'])): ?>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Payment Method:</strong><br>
                                        <?php echo ucwords(str_replace('_', ' ', $invoice['payment_method'])); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($invoice['payment_status'])): ?>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Payment Status:</strong><br>
                                        <span class="badge bg-<?php 
                                            echo $invoice['payment_status'] === 'paid' ? 'success' : 
                                                ($invoice['payment_status'] === 'pending' ? 'warning' : 'danger'); 
                                        ?>">
                                            <?php echo ucfirst($invoice['payment_status']); ?>
                                        </span>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($invoice['payment_date'])): ?>
                                <div class="col-md-6">
                                    <p class="mb-0">
                                        <strong>Payment Date:</strong><br>
                                        <?php echo date('M d, Y', strtotime($invoice['payment_date'])); ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($invoice['transaction_id'])): ?>
                                <div class="col-md-6">
                                    <p class="mb-0">
                                        <strong>Transaction ID:</strong><br>
                                        <?php echo $invoice['transaction_id']; ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Order Summary -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Order Date:</strong> 
                                <?php echo date('M d, Y h:i A', strtotime($invoice['created_at'])); ?>
                            </p>
                            <p class="mb-2">
                                <strong>Order Status:</strong> 
                                <span class="badge bg-<?php 
                                    echo $invoice['order_status'] === 'completed' ? 'success' : 
                                        ($invoice['order_status'] === 'processing' ? 'primary' : 
                                        ($invoice['order_status'] === 'shipped' ? 'info' : 'secondary')); 
                                ?>">
                                    <?php echo ucfirst($invoice['order_status']); ?>
                                </span>
                            </p>
                            <?php if(!empty($invoice['tracking_number'])): ?>
                                <p class="mb-0">
                                    <strong>Tracking Number:</strong> 
                                    <?php echo $invoice['tracking_number']; ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Shipping Method:</strong> 
                                <?php echo !empty($invoice['shipping_method']) ? ucwords(str_replace('_', ' ', $invoice['shipping_method'])) : 'Standard Shipping'; ?>
                            </p>
                            <?php if(!empty($invoice['shipped_date'])): ?>
                                <p class="mb-0">
                                    <strong>Shipped Date:</strong> 
                                    <?php echo date('M d, Y', strtotime($invoice['shipped_date'])); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="<?php echo BASE_URL; ?>?controller=invoice" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Invoices
                </a>
                <div class="btn-group">
                    <a href="<?php echo BASE_URL; ?>?controller=order&action=show&param=<?php echo $invoice['order_id']; ?>" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-bag me-2"></i>View Order
                    </a>
                    <?php if($invoice['status'] !== 'paid'): ?>
                        <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">
                            <i class="fas fa-credit-card me-2"></i>Pay Now
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<?php if($invoice['status'] !== 'paid'): ?>
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Make a Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm" action="<?php echo BASE_URL; ?>?controller=payment&action=process" method="POST">
                    <input type="hidden" name="invoice_id" value="<?php echo $invoice['id']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $invoice['total_amount'] - ($invoice['amount_paid'] ?? 0); ?>">
                    
                    <div class="mb-3">
                        <label class="form-label">Amount to Pay</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" value="<?php echo number_format($invoice['total_amount'] - ($invoice['amount_paid'] ?? 0), 2); ?>" disabled>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method" required>
                            <option value="">Select a payment method</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Bank Transfer</option>
                        </select>
                    </div>
                    
                    <div id="creditCardFields" class="d-none">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Card Number</label>
                                    <input type="text" class="form-control" name="card_number" placeholder="1234 5678 9012 3456">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Expiration Date</label>
                                    <input type="text" class="form-control" name="expiry_date" placeholder="MM/YY">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">CVV</label>
                                    <input type="text" class="form-control" name="cvv" placeholder="123">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Name on Card</label>
                                    <input type="text" class="form-control" name="card_name" placeholder="John Doe">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-credit-card me-2"></i>Pay Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethod = document.querySelector('select[name="payment_method"]');
    const creditCardFields = document.getElementById('creditCardFields');
    
    if (paymentMethod && creditCardFields) {
        paymentMethod.addEventListener('change', function() {
            if (this.value === 'credit_card') {
                creditCardFields.classList.remove('d-none');
            } else {
                creditCardFields.classList.add('d-none');
            }
        });
    }
    
    // Initialize form validation
    const form = document.getElementById('paymentForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    }
});
</script>
<?php endif; ?>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
