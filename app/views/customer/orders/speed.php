<?php 
// Set page title
$pageTitle = 'Quick Order';

// Include header
require_once APPROOT . '/app/views/includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Quick Order</h4>
                </div>
                <div class="card-body">
                    <?php flash('login_required'); ?>
                    <?php flash('order_success'); ?>
                    
                    <form action="<?php echo URLROOT; ?>/order/speed" method="post">
                        <?php if (isAdmin()): ?>
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer</label>
                            <select name="customer_id" class="form-select <?php echo (!empty($data['customer_id_error'])) ? 'is-invalid' : ''; ?>">
                                <option value="">Select Customer</option>
                                <?php foreach ($data['customers'] as $customer): ?>
                                    <option value="<?php echo $customer->id; ?>" <?php echo ($data['customer_id'] == $customer->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($customer->first_name . ' ' . $customer->last_name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['customer_id_error']; ?></span>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label for="product_sku" class="form-label">Product SKU</label>
                            <input type="text" name="product_sku" class="form-control <?php echo (!empty($data['product_sku_error'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo htmlspecialchars($data['product_sku']); ?>" placeholder="Enter product SKU" autofocus>
                            <div class="invalid-feedback"><?php echo $data['product_sku_error']; ?></div>
                            <div class="form-text">Enter the SKU of the product you want to order</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control <?php echo (!empty($data['quantity_error'])) ? 'is-invalid' : ''; ?>" 
                                   value="<?php echo htmlspecialchars($data['quantity']); ?>" min="1">
                            <div class="invalid-feedback"><?php echo $data['quantity_error']; ?></div>
                        </div>
                        
                        <?php if (!empty($data['shipping_methods'])): ?>
                            <div class="mb-3">
                                <label for="shipping_id" class="form-label">Shipping Method</label>
                                <select name="shipping_id" class="form-select <?php echo (!empty($data['shipping_id_error'])) ? 'is-invalid' : ''; ?>">
                                    <option value="">Select Shipping Method</option>
                                    <?php foreach ($data['shipping_methods'] as $method): ?>
                                        <option value="<?php echo $method->id; ?>" <?php echo (isset($_POST['shipping_id']) && $_POST['shipping_id'] == $method->id) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($method->name . ' - $' . number_format($method->base_price, 2)); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['shipping_id_error']; ?></span>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                No shipping methods available. Please contact support.
                                <input type="hidden" name="shipping_id" value="">
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" 
                                    <?php echo ($data['payment_method'] == 'cod') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="cod">
                                    Cash on Delivery (COD)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="card" value="card"
                                    <?php echo ($data['payment_method'] == 'card') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="card">
                                    Credit/Debit Card
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-bolt me-2"></i>Place Order Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Include footer
require_once APPROOT . '/app/views/includes/footer.php'; 
?>
