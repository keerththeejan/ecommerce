<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="product-image-container position-relative">
                            <?php if(!empty($product['image'])) : ?>
                                <img src="<?php echo BASE_URL . 'public/uploads/' . basename($product['image']); ?>" 
                                     class="card-img-top img-fluid" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg';">
                            <?php else : ?>
                                <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" 
                                     class="card-img-top img-fluid" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     loading="lazy">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <h2 class="card-title mb-3"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="card-text mb-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="product-price">
                                    <?php if(!empty($product['sale_price'])) : ?>
                                        <span class="text-danger fw-bold"><?php echo formatCurrency($product['sale_price']); ?></span>
                                        <span class="text-decoration-line-through text-muted small ms-1"><?php echo formatCurrency($product['price']); ?></span>
                                    <?php else : ?>
                                        <span class="fw-bold"><?php echo formatCurrency($product['price']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'danger'; ?>">
                                    <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                </span>
                            </div>

                            <?php if(isLoggedIn()): ?>
                                <?php if($product['stock_quantity'] > 0): ?>
                                    <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="add-to-cart-form" id="addToCartForm">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <div class="input-group input-group-sm mb-3">
                                            <button type="button" class="btn btn-outline-secondary quantity-decrease px-2">-</button>
                                            <input type="number" name="quantity" class="form-control text-center quantity-input" 
                                                   value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                                   aria-label="Quantity">
                                            <button type="button" class="btn btn-outline-secondary quantity-increase px-2">+</button>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-success" id="addToCartBtn">
                                                <i class="fas fa-shopping-bag me-1"></i> Create Order
                                            </button>
                                            <a href="<?php echo BASE_URL; ?>?controller=cart" class="btn btn-outline-secondary">
                                                <i class="fas fa-shopping-cart me-1"></i> View Cart
                                            </a>
                                        </div>
                                    </form>
                                    
                                    <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const form = document.getElementById('addToCartForm');
                                        const submitBtn = document.getElementById('addToCartBtn');
                                        let originalBtnHtml = submitBtn.innerHTML;
                                        
                                        form.addEventListener('submit', function(e) {
                                            e.preventDefault();
                                            
                                            // Show loading state
                                            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Creating Order...';
                                            submitBtn.disabled = true;
                                            
                                            // Show processing message
                                            const processingAlert = document.createElement('div');
                                            processingAlert.className = 'alert alert-info alert-dismissible fade show mt-3';
                                            processingAlert.role = 'alert';
                                            processingAlert.innerHTML = `
                                                Processing your order...
                                            `;
                                            form.parentNode.insertBefore(processingAlert, form.nextSibling);
                                            
                                            // Submit form via AJAX
                                            fetch(form.action, {
                                                method: 'POST',
                                                body: new URLSearchParams(new FormData(form)),
                                                headers: {
                                                    'X-Requested-With': 'XMLHttpRequest',
                                                    'Content-Type': 'application/x-www-form-urlencoded',
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                // Remove processing message
                                                if (processingAlert.parentNode) {
                                                    processingAlert.parentNode.removeChild(processingAlert);
                                                }
                                                
                                                if (data.redirect) {
                                                    window.location.href = data.redirect;
                                                } else if (data.success) {
                                                    // Show success message
                                                    const alertDiv = document.createElement('div');
                                                    alertDiv.className = 'alert alert-success alert-dismissible fade show mt-3';
                                                    alertDiv.role = 'alert';
                                                    alertDiv.innerHTML = `
                                                        ${data.message || 'Order created successfully'}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    `;
                                                    form.parentNode.insertBefore(alertDiv, form.nextSibling);
                                                    
                                                    // Redirect to admin orders page after a short delay
                                                    setTimeout(() => {
                                                        window.location.href = '<?php echo BASE_URL; ?>?controller=order&action=adminIndex';
                                                    }, 1500);
                                                } else {
                                                    throw new Error(data.message || 'Failed to create order');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                
                                                // Remove processing message
                                                if (processingAlert.parentNode) {
                                                    processingAlert.parentNode.removeChild(processingAlert);
                                                }
                                                
                                                // Show error message
                                                const alertDiv = document.createElement('div');
                                                alertDiv.className = 'alert alert-danger alert-dismissible fade show mt-3';
                                                alertDiv.role = 'alert';
                                                alertDiv.innerHTML = `
                                                    ${error.message || 'An error occurred while creating the order'}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                `;
                                                form.parentNode.insertBefore(alertDiv, form.nextSibling);
                                                
                                                // Reset button state
                                                submitBtn.innerHTML = originalBtnHtml;
                                                submitBtn.disabled = false;
                                            });
                                        });
                                        
                                        // Quantity buttons
                                        const decreaseBtn = form.querySelector('.quantity-decrease');
                                        const increaseBtn = form.querySelector('.quantity-increase');
                                        const quantityInput = form.querySelector('.quantity-input');
                                        
                                        if (decreaseBtn && increaseBtn && quantityInput) {
                                            // Initialize with proper values
                                            let max = parseInt(quantityInput.max) || 999; // Fallback to 999 if max is not set
                                            
                                            // Update quantity function
                                            const updateQuantity = (newValue) => {
                                                let value = parseInt(newValue);
                                                if (isNaN(value) || value < 1) value = 1;
                                                if (value > max) value = max;
                                                quantityInput.value = value;
                                                return value;
                                            };
                                            
                                            // Handle manual input
                                            quantityInput.addEventListener('change', (e) => {
                                                updateQuantity(e.target.value);
                                            });
                                            
                                            // Decrease button
                                            decreaseBtn.addEventListener('click', () => {
                                                let value = parseInt(quantityInput.value);
                                                if (value > 1) {
                                                    quantityInput.value = value - 1;
                                                }
                                            });
                                            
                                            // Increase button
                                            increaseBtn.addEventListener('click', () => {
                                                let value = parseInt(quantityInput.value);
                                                if (value < max) {
                                                    quantityInput.value = value + 1;
                                                }
                                            });
                                            
                                            // Initial validation
                                            updateQuantity(quantityInput.value);
                                        }
                                    });
                                    </script>
                                <?php else: ?>
                                    <div class="alert alert-warning text-center">
                                        This product is currently out of stock.
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                
                            <?php endif; ?>

                            <?php if(isLoggedIn() && $product['stock_quantity'] > 0) : ?>
                            <div class="mt-3">
                                <a href="<?php echo BASE_URL; ?>?controller=wishlist&action=add&id=<?php echo $product['id']; ?>" 
                                   class="btn btn-outline-danger w-100" 
                                   title="Add to Wishlist">
                                    <i class="far fa-heart me-1"></i> Add to Wishlist
                                </a>
                            </div>
                            <?php endif; ?>

                            <div class="mt-4">
                                <h5>Product Details</h5>
                                <ul class="list-unstyled">
                                    <li><strong>SKU:</strong> <?php echo htmlspecialchars($product['sku']); ?></li>
                                    <li><strong>Category:</strong> <?php echo htmlspecialchars($product['category_name']); ?></li>
                                    <li><strong>Brand:</strong> <?php echo htmlspecialchars($product['brand_name']); ?></li>
                                    <li><strong>Weight:</strong> <?php echo htmlspecialchars($product['weight']); ?> <?php echo htmlspecialchars($product['weight_unit']); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
