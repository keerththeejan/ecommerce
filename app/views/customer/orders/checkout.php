<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<style>
/* Fixed-size images for checkout (order items) */
.checkout-item-img {
    width: 64px;
    height: 64px;
    object-fit: contain;
}
@media (max-width: 767.98px) {
    .checkout-item-img { width: 64px; height: 64px; }
}
</style>

<div class="container py-5">
    <h1 class="mb-4 fs-2">Checkout</h1>
    
    <?php flash('order_error', '', 'alert alert-danger'); ?>
    
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fs-6">Shipping & Billing Information</h5>
                </div>
                <div class="card-body">
                    <?php $hasAddresses = !empty($addresses); ?>
                    <form action="<?php echo BASE_URL; ?>?controller=order&action=checkout" method="POST" id="checkout-form">
                        <div class="mb-3">
                            <?php if($hasAddresses): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="shipping_address" class="form-label mb-0">Shipping Address</label>
                                <div class="d-flex gap-2">
                                    <select id="shipping_address_select" class="form-select form-select-sm" style="min-width:260px">
                                        <option value="">Select saved shipping address...</option>
                                        <?php foreach($addresses as $addr): if(isset($addr['type']) && $addr['type']==='shipping'): ?>
                                            <?php 
                                                $label = trim(($addr['name'] ?? '') . ' - ' . ($addr['address_line1'] ?? '') . ', ' . ($addr['city'] ?? '')); 
                                                $text = trim(($addr['name'] ?? '') . "\n" . ($addr['address_line1'] ?? '') . (empty($addr['address_line2']) ? '' : ("\n".$addr['address_line2'])) . "\n" . ($addr['city'] ?? '') . ', ' . ($addr['state'] ?? '') . ' ' . ($addr['postal_code'] ?? '') . "\n" . ($addr['country'] ?? '') . (empty($addr['phone']) ? '' : ("\nPhone: ".$addr['phone'])));
                                            ?>
                                            <option value="<?php echo htmlspecialchars($text); ?>" <?php echo !empty($addr['is_default']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?><?php echo !empty($addr['is_default']) ? ' (Default)' : ''; ?></option>
                                        <?php endif; endforeach; ?>
                                    </select>
                                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo BASE_URL; ?>?controller=address">Manage</a>
                                </div>
                            </div>
                            <?php else: ?>
                                <label for="shipping_address" class="form-label">Shipping Address</label>
                            <?php endif; ?>
                            <textarea class="form-control <?php echo isset($errors['shipping_address']) ? 'is-invalid' : ''; ?>" id="shipping_address" name="shipping_address" rows="3" <?php echo isset($data['same_address']) && $data['same_address'] ? 'disabled' : ''; ?>><?php echo $data['shipping_address']; ?></textarea>
                            <?php if(isset($errors['shipping_address'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['shipping_address']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <?php if($hasAddresses): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label for="billing_address" class="form-label mb-0">Billing Address</label>
                                <div class="d-flex gap-2">
                                    <select id="billing_address_select" class="form-select form-select-sm" style="min-width:260px">
                                        <option value="">Select saved billing address...</option>
                                        <?php foreach($addresses as $addr): if(isset($addr['type']) && $addr['type']==='billing'): ?>
                                            <?php 
                                                $label = trim(($addr['name'] ?? '') . ' - ' . ($addr['address_line1'] ?? '') . ', ' . ($addr['city'] ?? '')); 
                                                $text = trim(($addr['name'] ?? '') . "\n" . ($addr['address_line1'] ?? '') . (empty($addr['address_line2']) ? '' : ("\n".$addr['address_line2'])) . "\n" . ($addr['city'] ?? '') . ', ' . ($addr['state'] ?? '') . ' ' . ($addr['postal_code'] ?? '') . "\n" . ($addr['country'] ?? '') . (empty($addr['phone']) ? '' : ("\nPhone: ".$addr['phone'])));
                                            ?>
                                            <option value="<?php echo htmlspecialchars($text); ?>" <?php echo !empty($addr['is_default']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($label); ?><?php echo !empty($addr['is_default']) ? ' (Default)' : ''; ?></option>
                                        <?php endif; endforeach; ?>
                                    </select>
                                    <a class="btn btn-outline-secondary btn-sm" href="<?php echo BASE_URL; ?>?controller=address">Manage</a>
                                </div>
                            </div>
                            <?php else: ?>
                                <label for="billing_address" class="form-label">Billing Address</label>
                            <?php endif; ?>
                            <textarea class="form-control <?php echo isset($errors['billing_address']) ? 'is-invalid' : ''; ?>" id="billing_address" name="billing_address" rows="3"><?php echo $data['billing_address']; ?></textarea>
                            <?php if(isset($errors['billing_address'])): ?>
                                <div class="invalid-feedback"><?php echo $errors['billing_address']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" id="same_address" name="same_address" <?php echo isset($data['same_address']) && $data['same_address'] ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="same_address">Shipping address same as billing address</label>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label">Payment Method</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" <?php echo $data['payment_method'] == 'cod' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="payment_cod">
                                        Cash on Delivery
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_card" value="card" <?php echo $data['payment_method'] == 'card' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="payment_card">
                                        Credit/Debit Card
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_paypal" value="paypal" <?php echo $data['payment_method'] == 'paypal' ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="payment_paypal">
                                        PayPal
                                    </label>
                                </div>
                            </div>
                            <?php if(isset($errors['payment_method'])): ?>
                                <div class="text-danger mt-2"><?php echo $errors['payment_method']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2" placeholder="Special instructions for delivery or order"><?php echo $data['notes']; ?></textarea>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fs-6">Order Items</h5>
                </div>
                <div class="card-body">
                    <!-- Desktop view - table -->
                    <div class="d-none d-md-block">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($cartItems as $item): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <?php if(!empty($item['image'])): ?>
                                                        <img src="<?php echo BASE_URL . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="img-thumbnail me-3 checkout-item-img">
                                                    <?php else: ?>
                                                        <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" alt="<?php echo $item['name']; ?>" class="img-thumbnail me-3 checkout-item-img">
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0 small"><?php echo $item['name']; ?></h6>
                                                        <small class="text-muted"><?php echo $item['sku']; ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if(!empty($item['sale_price'])): ?>
                                                    <span class="text-decoration-line-through text-muted small"><?php echo formatCurrency($item['price']); ?></span><br>
                                                    <span class="text-danger small"><?php echo formatCurrency($item['sale_price']); ?></span>
                                                <?php else: ?>
                                                    <span class="small"><?php echo formatCurrency($item['price']); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="small"><?php echo $item['quantity']; ?></span>
                                            </td>
                                            <td class="text-end">
                                                <?php 
                                                    $itemPrice = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                                                    $itemTotal = $itemPrice * $item['quantity'];
                                                    echo formatCurrency($itemTotal);
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Mobile view - cards -->
                    <div class="d-md-none">
                        <?php foreach($cartItems as $item): ?>
                            <div class="card mb-3 border">
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        <div class="col-4 d-flex align-items-center">
                                            <?php if(!empty($item['image'])): ?>
                                                <img src="<?php echo BASE_URL . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="rounded checkout-item-img">
                                            <?php else: ?>
                                                <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" alt="<?php echo $item['name']; ?>" class="rounded checkout-item-img">
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-8">
                                            <h6 class="mb-1 fs-6"><?php echo truncateText($item['name'], 40); ?></h6>
                                            <small class="text-muted d-block mb-2"><?php echo $item['sku']; ?></small>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <?php if(!empty($item['sale_price'])): ?>
                                                        <span class="text-decoration-line-through text-muted small"><?php echo formatCurrency($item['price']); ?></span>
                                                        <span class="text-danger fw-bold small"><?php echo formatCurrency($item['sale_price']); ?></span>
                                                    <?php else: ?>
                                                        <span class="fw-bold small"><?php echo formatCurrency($item['price']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="small me-2">Qty: <?php echo $item['quantity']; ?></span>
                                                    <?php 
                                                        $itemPrice = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                                                        $itemTotal = $itemPrice * $item['quantity'];
                                                    ?>
                                                    <span class="fw-bold small"><?php echo formatCurrency($itemTotal); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fs-6">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <span>Subtotal:</span>
                        <span><?php echo formatCurrency($cartTotal); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <span>Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Tax (10%):</span>
                        <span><?php echo formatCurrency($cartTotal * 0.1); ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Total:</strong>
                        <strong><?php echo formatCurrency($cartTotal + ($cartTotal * 0.1)); ?></strong>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" form="checkout-form" class="btn btn-primary btn-lg">
                            <i class="fas fa-lock me-2"></i> Place Order
                        </button>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <a href="<?php echo BASE_URL; ?>?controller=cart" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i> Return to Cart
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">Have a coupon?</h5>
                    <form action="#" method="POST" class="mb-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Coupon code">
                            <button class="btn btn-outline-secondary" type="button">Apply</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle same address checkbox
    const sameAddressCheckbox = document.getElementById('same_address');
    const shippingAddressField = document.getElementById('shipping_address');
    const billingAddressField = document.getElementById('billing_address');
    const shippingSelect = document.getElementById('shipping_address_select');
    const billingSelect = document.getElementById('billing_address_select');
    
    function handleSameAddressChange() {
        if(sameAddressCheckbox.checked) {
            shippingAddressField.disabled = true;
            shippingAddressField.value = billingAddressField.value;
        } else {
            shippingAddressField.disabled = false;
        }
    }
    
    sameAddressCheckbox.addEventListener('change', handleSameAddressChange);
    billingAddressField.addEventListener('input', function() {
        if(sameAddressCheckbox.checked) {
            shippingAddressField.value = billingAddressField.value;
        }
    });
    
    // Populate from selects
    if (shippingSelect) {
        shippingSelect.addEventListener('change', function() {
            if (this.value) {
                shippingAddressField.value = this.value;
            }
        });
        // If a default option is selected, apply it on load
        if (shippingSelect.value) {
            shippingAddressField.value = shippingSelect.value;
        }
    }
    if (billingSelect) {
        billingSelect.addEventListener('change', function() {
            if (this.value) {
                billingAddressField.value = this.value;
                if(sameAddressCheckbox && sameAddressCheckbox.checked) {
                    shippingAddressField.value = this.value;
                }
            }
        });
        if (billingSelect.value) {
            billingAddressField.value = billingSelect.value;
            if(sameAddressCheckbox && sameAddressCheckbox.checked) {
                shippingAddressField.value = billingSelect.value;
            }
        }
    }
    
    // Initialize
    handleSameAddressChange();
});
</script>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
