<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="row g-0">
                    <div class="col-md-6">
                        <div class="product-image-container position-relative">
                            <?php if(!empty($product['image'])) : ?>
                                <img src="<?php echo BASE_URL . $product['image']; ?>" 
                                     class="card-img-top img-fluid" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     loading="lazy">
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
                                    <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="add-to-cart-form">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <div class="input-group input-group-sm mb-3">
                                            <button type="button" class="btn btn-outline-secondary quantity-decrease px-2">-</button>
                                            <input type="number" name="quantity" class="form-control text-center quantity-input" 
                                                   value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                                   aria-label="Quantity" readonly>
                                            <button type="button" class="btn btn-outline-secondary quantity-increase px-2">+</button>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">
                                            <i class="fas fa-cart-plus me-1"></i> Add to Cart
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-warning text-center">
                                        This product is currently out of stock.
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="text-center py-3">
                                    <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-outline-success w-100">
                                        <i class="fas fa-sign-in-alt me-1"></i> Login to Buy
                                    </a>
                                </div>
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
