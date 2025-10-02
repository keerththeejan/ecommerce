<?php 
require_once APP_PATH . 'views/customer/layouts/header.php';
$settingModel = new Setting();
?>

<style>
/* Fixed-size cart item images for consistent display */
.cart-item-img {
    width: 80px;
    height: 80px;
    object-fit: contain;
}
@media (max-width: 767.98px) {
    .cart-item-img {
        width: 80px;
        height: 80px;
    }
}
</style>

<div class="container py-5">
    <h1 class="mb-4 fs-2">Shopping Cart</h1>
    
    <?php flash('cart_success'); ?>
    <?php flash('cart_error', '', 'alert alert-danger'); ?>
    
    <?php if(empty($cartItems)): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                <h3>Your cart is empty</h3>
                <p class="mb-4">Looks like you haven't added any products to your cart yet.</p>
                <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i> Continue Shopping
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fs-6">Cart Items (<?php echo count($cartItems); ?>)</h5>
                            <a href="<?php echo BASE_URL; ?>?controller=cart&action=clear" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash me-1"></i><span class="d-none d-md-inline">Clear Cart</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Desktop view - table -->
                        <div class="d-none d-md-block">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th class="text-end">Total</th>
                                            <th style="width: 50px;"></th>
                                        <tbody>
                                        <?php foreach($cartItems as $item): ?>
                                            <tr>
                                                <!-- Image -->
                                                <td>
                                                    <?php if(!empty($item['image'])): ?>
                                                        <img src="<?php echo BASE_URL . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="img-thumbnail cart-item-img fixed-size">
                                                    <?php else: ?>
                                                        <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" alt="<?php echo $item['name']; ?>" class="img-thumbnail cart-item-img fixed-size">
                                                    <?php endif; ?>
                                                </td>
                                                <!-- Product info -->
                                                <td>
                                                    <h6 class="mb-1"><?php echo $item['name']; ?></h6>
                                                    <?php if(!empty($item['sku'])): ?>
                                                        <small class="text-muted"><?php echo $item['sku']; ?></small>
                                                    <?php endif; ?>
                                                    <?php if($item['stock_quantity'] < 5): ?>
                                                        <div><small class="text-danger">Only <?php echo $item['stock_quantity']; ?> left in stock</small></div>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- Price -->
                                                <td>
                                                    <?php if(!empty($item['sale_price'])): ?>
                                                        <span class="text-decoration-line-through text-muted"><?php echo formatCurrency($item['price']); ?></span><br>
                                                        <span class="text-danger"><?php echo formatCurrency($item['sale_price']); ?></span>
                                                    <?php else: ?>
                                                        <?php echo formatCurrency($item['price']); ?>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- Quantity -->
                                                <td>
                                                    <form action="<?php echo BASE_URL; ?>?controller=cart&action=update" method="POST" class="quantity-form">
                                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                                        <div class="input-group input-group-sm">
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" name="quantity" class="form-control text-center quantity-input" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>">
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <!-- Total -->
                                                <td class="text-end">
                                                    <?php 
                                                        $itemPrice = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                                                        $itemTotal = $itemPrice * $item['quantity'];
                                                        echo formatCurrency($itemTotal);
                                                    ?>
                                                </td>
                                                <!-- Remove -->
                                                <td>
                                                    <a href="<?php echo BASE_URL; ?>?controller=cart&action=remove&param=<?php echo $item['id']; ?>" class="text-danger remove-item" data-cart-id="<?php echo $item['id']; ?>">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </td>
{{ ... }}
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
                                                    <img src="<?php echo BASE_URL . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="rounded cart-item-img">
                                                <?php else: ?>
                                                    <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" alt="<?php echo $item['name']; ?>" class="rounded cart-item-img">
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-8">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="mb-1 fs-6"><?php echo truncateText($item['name'], 40); ?></h6>
                                                    <a href="<?php echo BASE_URL; ?>?controller=cart&action=remove&param=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger remove-item p-1" data-cart-id="<?php echo $item['id']; ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                                <?php if(!empty($item['sku'])): ?>
                                                    <small class="text-muted d-block mb-2"><?php echo $item['sku']; ?></small>
                                                <?php endif; ?>
                                                
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div>
                                                        <?php if(!empty($item['sale_price'])): ?>
                                                            <span class="text-decoration-line-through text-muted small"><?php echo formatCurrency($item['price']); ?></span>
                                                            <span class="text-danger fw-bold small"><?php echo formatCurrency($item['sale_price']); ?></span>
                                                        <?php else: ?>
                                                            <span class="fw-bold small"><?php echo formatCurrency($item['price']); ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <?php 
                                                            $itemPrice = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                                                            $itemTotal = $itemPrice * $item['quantity'];
                                                        ?>
                                                        <span class="fw-bold small"><?php echo formatCurrency($itemTotal); ?></span>
                                                    </div>
                                                </div>
                                                
                                                <form action="<?php echo BASE_URL; ?>?controller=cart&action=update" method="POST" class="quantity-form mb-2">
                                                    <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                                    <div class="d-flex align-items-center">
                                                        <div class="input-group input-group-sm">
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" name="quantity" class="form-control text-center quantity-input" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>">
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php if($item['stock_quantity'] < 5): ?>
                                                        <div><small class="text-danger">Only <?php echo $item['stock_quantity']; ?> left</small></div>
                                                    <?php endif; ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mb-4">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i><span class="d-none d-md-inline">Continue Shopping</span>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fs-6">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span><?php echo formatCurrency($cartTotal); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>Calculated at checkout</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Estimated Total:</strong>
                            <strong><?php echo formatCurrency($cartTotal); ?></strong>
                        </div>
                        <div class="d-grid">
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=checkout" class="btn btn-primary">
                                <i class="fas fa-lock me-2"></i>Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3 fs-6">Have a coupon?</h5>
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
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity buttons functionality with AJAX
    const quantityBtns = document.querySelectorAll('.quantity-btn');
    quantityBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const action = this.dataset.action;
            const form = this.closest('form');
            const input = form.querySelector('.quantity-input');
            const cartId = form.querySelector('input[name="cart_id"]').value;
            let value = parseInt(input.value);
            const maxValue = parseInt(input.max);
            
            if (action === 'decrease' && value > 1) {
                value = value - 1;
            } else if (action === 'increase' && value < maxValue) {
                value = value + 1;
            } else {
                return; // No change needed
            }
            
            input.value = value;
            
            // Update cart via AJAX
            updateCartItemQuantity(cartId, value, form);
        });
    });
    
    // Direct input change with AJAX
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const form = this.closest('form');
            const cartId = form.querySelector('input[name="cart_id"]').value;
            let value = parseInt(this.value);
            const maxValue = parseInt(this.max);
            
            // Validate input
            if (value < 1) value = 1;
            if (value > maxValue) value = maxValue;
            this.value = value;
            
            // Update cart via AJAX
            updateCartItemQuantity(cartId, value, form);
        });
    });
    
    // Function to update cart item quantity
    function updateCartItemQuantity(cartId, quantity, form) {
        const formData = new FormData();
        formData.append('cart_id', cartId);
        formData.append('quantity', quantity);
        
        fetch('<?php echo BASE_URL; ?>?controller=cart&action=update', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart total
                const cartTotalElements = document.querySelectorAll('.cart-total');
                cartTotalElements.forEach(el => {
                    el.textContent = data.cartTotal;
                });
                
                // Update item price display
                const row = form.closest('tr');
                const card = form.closest('.card.mb-3');
                
                if (row) {
                    const priceCell = row.querySelector('td:nth-child(5)');
                    if (priceCell) {
                        // Calculate and update the item total
                        const unitPrice = parseFloat(row.querySelector('td:nth-child(3)').textContent.replace(/[^0-9.-]+/g, ''));
                        const itemTotal = unitPrice * quantity;
                        priceCell.textContent = formatCurrency(itemTotal);
                    }
                }
                
                if (card) {
                    const totalElement = card.querySelector('.item-total');
                    if (totalElement) {
                        // Calculate and update the item total
                        const unitPriceElement = card.querySelector('.item-price');
                        if (unitPriceElement) {
                            const unitPrice = parseFloat(unitPriceElement.textContent.replace(/[^0-9.-]+/g, ''));
                            const itemTotal = unitPrice * quantity;
                            totalElement.textContent = formatCurrency(itemTotal);
                        }
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    
    // Helper function to format currency
    function formatCurrency(amount) {
        const currencySymbol = '<?php echo addslashes($settingModel->getSetting('store_currency_symbol', '₹')); ?>';
        return currencySymbol + ' ' + parseFloat(amount).toFixed(2);
    }
    
    // Remove item AJAX functionality
    const removeButtons = document.querySelectorAll('.remove-item');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const cartId = this.dataset.cartId;
            const url = this.getAttribute('href');
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the row or card
                    const row = this.closest('tr');
                    const card = this.closest('.card.mb-3');
                    
                    if (row) row.remove();
                    if (card) card.remove();
                    
                    // Update cart count
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.itemCount;
                    }
                    
                    // Update cart total
                    const cartTotalElement = document.querySelector('.cart-total');
                    if (cartTotalElement) {
                        cartTotalElement.textContent = data.cartTotal;
                    }
                    
                    // If cart is empty, reload the page
                    if (data.itemCount === 0) {
                        window.location.reload();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    });
});
</script>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
