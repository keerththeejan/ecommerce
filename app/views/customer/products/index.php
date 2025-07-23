<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar with categories -->
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="list-group-item list-group-item-action active">
                        All Products
                    </a>
                    <?php foreach($categories as $category): ?>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=category&param=<?php echo $category['id']; ?>" class="list-group-item list-group-item-action">
                            <?php echo $category['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Special Offers</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=sale" class="list-group-item list-group-item-action text-danger">
                        <i class="fas fa-fire"></i> Products on Sale
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">All Products</h2>
                
                <!-- Search form -->
                <form action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET" class="d-flex">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="search">
                    <input type="text" name="keyword" class="form-control me-2" placeholder="Search products...">
                    <button type="submit" class="btn btn-outline-primary">Search</button>
                </form>
            </div>
            
            <?php if(empty($products['data'])): ?>
                <div class="alert alert-info">No products found.</div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach($products['data'] as $product): ?>
                        <div class="col">
                            <div class="card h-100">
                                <div class="product-image-container" style="width: 230px; height: 250px; margin: 0 auto; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <?php if(!empty($product['image'])): ?>
                                        <img src="<?php echo BASE_URL . $product['image']; ?>" class="card-img-top img-fluid" alt="<?php echo $product['name']; ?>" style="width: 100%; height: 100%; object-fit: contain;">
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" class="card-img-top img-fluid" alt="No Image" style="width: 100%; height: 100%; object-fit: contain;">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                    <p class="card-text"><?php echo truncateText($product['description'], 100); ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <?php if(isLoggedIn()): ?>
                                            <?php if(!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                                <span class="fw-bold"><?php echo formatCurrency($product['sale_price']); ?></span>
                                            <?php else: ?>
                                                <span class="fw-bold"><?php echo formatCurrency($product['price']); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-sm btn-outline-primary">Login to View Price</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="card-body border-top pt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'secondary'; ?>">
                                            <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                        </span>
                                        <?php if($product['stock_quantity'] > 0): ?>
                                            <div class="text-end">
                                                <small class="text-muted d-block">Stock: <?php echo $product['stock_quantity']; ?> units</small>
                                                <?php if(isLoggedIn()): ?>
                                                    <small class="text-muted d-block">
                                                        Value: <?php echo formatCurrency($product['stock_quantity'] * ($product['sale_price'] ?? $product['price'])); ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex justify-content-between gap-2">
                                        <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary flex-grow-1">View Details</a>
                                        <?php if($product['stock_quantity'] > 0): ?>
                                            <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="flex-grow-1">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-sm btn-success w-100">Add to Cart</button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary flex-grow-1" disabled>Out of Stock</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    <?php 
                    if (isset($products['current_page']) && isset($products['last_page'])) {
                        echo getPaginationLinks($products['current_page'], $products['last_page'], BASE_URL . '?controller=product&action=index'); 
                    }
                    ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
