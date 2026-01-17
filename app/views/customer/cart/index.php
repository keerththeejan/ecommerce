<?php 
require_once APP_PATH . 'views/customer/layouts/header.php';
$settingModel = new Setting();
?>

<style>
/* Professional Shopping Cart Styling */
.cart-item-img {
    width: 80px;
    height: 80px;
    object-fit: contain;
    border-radius: 8px;
}

.cart-table thead th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #2d3436;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem 0.75rem;
}

.cart-table tbody td {
    padding: 1.25rem 0.75rem;
    vertical-align: middle;
}

.cart-table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: background-color 0.2s ease;
}

.cart-table tbody tr:hover {
    background-color: #f8f9fa;
}

.remove-item-btn {
    color: #dc3545;
    font-size: 1.1rem;
    padding: 0.5rem;
    border-radius: 50%;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
}

.remove-item-btn:hover {
    background-color: #dc3545;
    color: #ffffff;
    transform: scale(1.1);
}

.quantity-input-group {
    max-width: 120px;
}

.order-summary-card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.coupon-card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.btn-coupon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    border: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-coupon:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: #ffffff;
}

.btn-checkout {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

@media (max-width: 767.98px) {
    .cart-item-img {
        width: 80px;
        height: 80px;
    }
}
</style>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-0 fs-2 fw-bold">Shopping Cart</h1>
        </div>
    </div>
    
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
                                <table class="table cart-table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 100px;">Product</th>
                                            <th>Details</th>
                                            <th class="text-center" style="width: 120px;">Price</th>
                                            <th class="text-center" style="width: 150px;">Quantity</th>
                                            <th class="text-end" style="width: 120px;">Total</th>
                                            <th class="text-center" style="width: 60px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($cartItems as $item): ?>
                                            <tr>
                                                <!-- Image -->
                                                <td>
                                                    <?php if(!empty($item['image'])): ?>
                                                        <img src="<?php echo BASE_URL . $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-item-img">
                                                    <?php else: ?>
                                                        <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" alt="<?php echo $item['name']; ?>" class="cart-item-img">
                                                    <?php endif; ?>
                                                </td>
                                                <!-- Product info -->
                                                <td>
                                                    <h6 class="mb-1 fw-semibold"><?php echo htmlspecialchars($item['name']); ?></h6>
                                                    <?php if(!empty($item['sku'])): ?>
                                                        <small class="text-muted d-block mb-1"><?php echo htmlspecialchars($item['sku']); ?></small>
                                                    <?php endif; ?>
                                                    <?php if($item['stock_quantity'] < 5): ?>
                                                        <div><small class="text-danger fw-semibold">Only <?php echo $item['stock_quantity']; ?> left in stock</small></div>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- Price -->
                                                <td class="text-center">
                                                    <?php if(!empty($item['sale_price'])): ?>
                                                        <div class="mb-1">
                                                            <span class="text-decoration-line-through text-muted small"><?php echo formatCurrency($item['price']); ?></span>
                                                        </div>
                                                        <div>
                                                            <span class="text-danger fw-bold"><?php echo formatCurrency($item['sale_price']); ?></span>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="fw-bold"><?php echo formatCurrency($item['price']); ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- Quantity -->
                                                <td>
                                                    <form action="<?php echo BASE_URL; ?>?controller=cart&action=update" method="POST" class="quantity-form">
                                                        <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                                        <div class="input-group input-group-sm quantity-input-group mx-auto">
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" name="quantity" class="form-control text-center quantity-input" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>" readonly>
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <!-- Total -->
                                                <td class="text-end fw-bold">
                                                    <?php 
                                                        $itemPrice = !empty($item['sale_price']) ? $item['sale_price'] : $item['price'];
                                                        $itemTotal = $itemPrice * $item['quantity'];
                                                        echo formatCurrency($itemTotal);
                                                    ?>
                                                </td>
                                                <!-- Remove -->
                                                <td class="text-center">
                                                    <a href="<?php echo BASE_URL; ?>?controller=cart&action=remove&param=<?php echo $item['id']; ?>" class="remove-item-btn text-decoration-none remove-item" data-cart-id="<?php echo $item['id']; ?>" title="Remove item">
                                                        <i class="fas fa-times"></i>
                                                    </a>
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
                                                        <div class="input-group input-group-sm quantity-input-group">
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="decrease">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="number" name="quantity" class="form-control text-center quantity-input" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock_quantity']; ?>" readonly>
                                                            <button type="button" class="btn btn-outline-secondary quantity-btn" data-action="increase">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <?php if($item['stock_quantity'] < 5): ?>
                                                        <div><small class="text-danger fw-semibold mt-1 d-block">Only <?php echo $item['stock_quantity']; ?> left</small></div>
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
                        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card mb-4 order-summary-card border-0">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0 fs-6 fw-bold">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Subtotal:</span>
                            <span class="fw-semibold cart-total"><?php echo formatCurrency($cartTotal); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping:</span>
                            <span class="text-success fw-semibold">Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Tax:</span>
                            <span class="text-muted small">Calculated at checkout</span>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between mb-4">
                            <strong class="fs-5">Estimated Total:</strong>
                            <strong class="fs-5 text-primary cart-total"><?php echo formatCurrency($cartTotal); ?></strong>
                        </div>
                        <div class="d-grid">
                            <a href="<?php echo BASE_URL; ?>?controller=order&action=checkout" class="btn btn-checkout btn-primary text-white">
                                <i class="fas fa-lock me-2"></i>Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="card coupon-card border-0">
                    <div class="card-body">
                        <h5 class="mb-3 fs-6 fw-bold">Have a coupon?</h5>
                        <form action="#" method="POST" class="mb-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Coupon code" aria-label="Coupon code">
                                <button class="btn btn-coupon" type="button">
                                    <i class="fas fa-tag me-1"></i>Apply
                                </button>
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
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update cart total
                const cartTotalElements = document.querySelectorAll('.cart-total');
                cartTotalElements.forEach(el => {
                    el.textContent = formatCurrency(data.cartTotal || 0);
                });
                
                // Update item price display
                const row = form.closest('tr');
                const card = form.closest('.card.mb-3');
                
                if (row) {
                    // Table structure: Image(1) | Product(2) | Price(3) | Quantity(4) | Total(5) | Remove(6)
                    const totalCell = row.querySelector('td:nth-child(5)');
                    if (totalCell) {
                        // Get price from the price column (column 3)
                        const priceCell = row.querySelector('td:nth-child(3)');
                        if (priceCell) {
                            // Extract price (handle both sale_price and regular price - take the last number which is the actual price)
                            const priceText = priceCell.textContent;
                            const priceMatches = priceText.match(/[\d.]+/g);
                            const unitPrice = priceMatches && priceMatches.length > 0 ? parseFloat(priceMatches[priceMatches.length - 1]) : 0;
                            const itemTotal = unitPrice * quantity;
                            totalCell.textContent = formatCurrency(itemTotal);
                        }
                    }
                }
                
                if (card) {
                    const totalElement = card.querySelector('.item-total');
                    if (totalElement) {
                        // Calculate and update the item total
                        const unitPriceElement = card.querySelector('.item-price');
                        if (unitPriceElement) {
                            const unitPrice = parseFloat(unitPriceElement.textContent.replace(/[^0-9.-]+/g, '')) || 0;
                            const itemTotal = unitPrice * quantity;
                            totalElement.textContent = formatCurrency(itemTotal);
                        }
                    }
                }
            } else {
                alert('Failed to update cart item.');
            }
        })
        .catch(error => {
            console.error('Error updating cart:', error);
            alert('An error occurred while updating the cart. Please refresh the page.');
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
            
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }
            
            const cartId = this.dataset.cartId;
            const url = this.getAttribute('href');
            const button = this; // Store reference for use in callback
            
            fetch(url, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Remove the row or card
                    const row = button.closest('tr');
                    const card = button.closest('.card.mb-3');
                    
                    if (row) row.remove();
                    if (card) card.remove();
                    
                    // Update cart count
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.itemCount || 0;
                    }
                    
                    // Update cart total
                    const cartTotalElements = document.querySelectorAll('.cart-total');
                    cartTotalElements.forEach(el => {
                        el.textContent = formatCurrency(data.cartTotal || 0);
                    });
                    
                    // If cart is empty, reload the page
                    if (data.itemCount === 0) {
                        window.location.reload();
                    }
                } else {
                    alert('Failed to remove item from cart.');
                }
            })
            .catch(error => {
                console.error('Error removing item:', error);
                alert('An error occurred while removing the item. Please refresh the page.');
            });
        });
    });
});
</script>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
