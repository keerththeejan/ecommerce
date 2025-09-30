<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Breadcrumb -->
        <div class="col-12 mb-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb small">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=product&action=index">Products</a></li>
                    <?php if(!empty($product['category_id'])): ?>
                        <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=product&action=category&id=<?php echo $product['category_id']; ?>"><?php echo $product['category_name']; ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo truncateText($product['name'], 30); ?></li>
                </ol>
            </nav>
        </div>
        
        <!-- Product details -->
        <div class="col-md-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="product-image-container" style="width: 230px; height: 250px; margin: 0 auto; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    <?php if(!empty($product['image'])): ?>
                        <img src="<?php echo BASE_URL . $product['image']; ?>" class="card-img-top img-fluid" alt="<?php echo $product['name']; ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    <?php else: ?>
                        <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" class="card-img-top img-fluid" alt="No Image" style="width: 100%; height: 100%; object-fit: contain;">
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-7 mb-4">
            <h1 class="mb-3 fs-2"><?php echo $product['name']; ?></h1>
            
            <?php if(isLoggedIn()): ?>
                <?php if(!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                    <div class="mb-3">
                        <span class="text-decoration-line-through text-muted me-2">
                            <?php echo formatCurrency($product['price']); ?>
                        </span>
                        <span class="fw-bold fs-4 text-danger">
                            <?php echo formatCurrency($product['sale_price']); ?>
                        </span>
                        <span class="badge bg-danger ms-2">Sale!</span>
                    </div>
                <?php else: ?>
                    <div class="mb-3">
                        <span class="fw-bold fs-4">
                            <?php echo formatCurrency($product['price']); ?>
                        </span>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="mb-3">
                    <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-outline-primary">Login to View Price</a>
                </div>
            <?php endif; ?>
            
            <div class="mb-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge <?php echo $product['stock_quantity'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                    </span>
                    <?php if($product['stock_quantity'] > 0): ?>
                        <?php if($product['stock_quantity'] <= 5): ?>
                            <div class="text-danger">
                                <span class="fw-bold">Only <?php echo $product['stock_quantity']; ?> units left!</span>
                                <?php if(isLoggedIn()): ?>
                                    <br>
                                    <small class="text-muted">
                                        Total Stock Value: <?php echo formatCurrency($product['stock_quantity'] * ($product['sale_price'] ?? $product['price'])); ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div>
                                <span class="text-muted">Available Stock: <?php echo $product['stock_quantity']; ?> units</span>
                                <?php if(isLoggedIn()): ?>
                                    <br>
                                    <small class="text-muted">
                                        Total Stock Value: <?php echo formatCurrency($product['stock_quantity'] * ($product['sale_price'] ?? $product['price'])); ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="mb-4">
                <p class="small"><?php echo nl2br($product['description']); ?></p>
            </div>
            
            <?php if($product['stock_quantity'] > 0): ?>
                <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="mb-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    
                    <div class="row g-2 align-items-center mb-3">
                        <div class="col-auto">
                            <label for="quantity" class="col-form-label">Quantity:</label>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-sm quantity-input">
                                <button type="button" class="btn btn-outline-secondary quantity-decrease">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                                <button type="button" class="btn btn-outline-secondary quantity-increase">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="form-text text-muted small">
                                (Max: <?php echo $product['stock_quantity']; ?>)
                            </span>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-block">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>
                        <a href="<?php echo BASE_URL; ?>?controller=wishlist&action=add&id=<?php echo $product['id']; ?>" class="btn btn-outline-secondary ms-md-2">
                            <i class="fas fa-heart me-2"></i>Add to Wishlist
                        </a>
                    </div>
                </form>
            <?php else: ?>
                <div class="d-grid mb-4">
                    <button class="btn btn-secondary" disabled>
                        <i class="fas fa-times-circle me-2"></i>Out of Stock
                    </button>
                </div>
            <?php endif; ?>
            
            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fs-6">Product Details</h5>
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                            <tr>
                                <th scope="row" class="w-25">SKU</th>
                                <td><?php echo $product['sku']; ?></td>
                            </tr>
                            <tr>
                                <th scope="row">Category</th>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=category&id=<?php echo $product['category_id']; ?>">
                                        <?php echo $product['category_name']; ?>
                                    </a>
                                </td>
                            </tr>
                            <?php if(isset($product['country_id']) && !empty($product['country_id'])): ?>
                            <tr>
                                <th scope="row">Country</th>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>?controller=country&action=show&id=<?php echo (int)$product['country_id']; ?>">
                                        <?php echo !empty($product['country_name']) ? htmlspecialchars($product['country_name']) : 'Unknown'; ?>
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Related products -->
        <?php if(!empty($relatedProducts)): ?>
            <div class="col-12 mt-4">
                <h3 class="mb-4 fs-4">Related Products</h3>
                <div class="row row-cols-2 row-cols-md-4 g-3">
                    <?php foreach($relatedProducts as $relatedProduct): ?>
                        <div class="col mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if(!empty($relatedProduct['image'])): ?>
                                    <img src="<?php echo BASE_URL . $relatedProduct['image']; ?>" class="card-img-top" alt="<?php echo $relatedProduct['name']; ?>" style="height: 180px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" class="card-img-top" alt="No Image" style="height: 180px; object-fit: cover;">
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title fs-6"><?php echo truncateText($relatedProduct['name'], 40); ?></h5>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if(isLoggedIn()): ?>
                                            <?php if(!empty($relatedProduct['sale_price']) && $relatedProduct['sale_price'] < $relatedProduct['price']): ?>
                                                <span class="fw-bold small">
                                                    <?php echo formatCurrency($relatedProduct['sale_price']); ?>
                                                </span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-sm btn-outline-primary w-100">Login to View Price</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="card-footer bg-white border-top-0">
                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=show&id=<?php echo $relatedProduct['id']; ?>" class="btn btn-sm btn-outline-primary w-100">View</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
