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
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="list-group-item list-group-item-action">
                        All Products
                    </a>
                    <?php foreach($categories as $category): ?>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=category&id=<?php echo $category['id']; ?>" class="list-group-item list-group-item-action">
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
                <h2 class="mb-0">Search Results: "<?php echo $keyword; ?>"</h2>
                
                <!-- Search form -->
                <form action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET" class="d-flex">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="search">
                    <input type="text" name="keyword" class="form-control me-2" placeholder="Search products..." value="<?php echo $keyword; ?>">
                    <button type="submit" class="btn btn-outline-primary">Search</button>
                </form>
            </div>
            
            <?php if(empty($products)): ?>
                <div class="alert alert-info">No products found matching your search criteria.</div>
                <p>Try searching with different keywords or browse our categories.</p>
            <?php else: ?>
                <p class="mb-4">Found <?php echo count($products); ?> product(s) matching your search criteria.</p>
                
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach($products as $product): ?>
                        <div class="col">
                            <div class="card h-100 position-relative">
                                <?php echo wishlist_heart_button($product['id']); ?>
                                <?php if(!empty($product['image'])): ?>
                                    <img <?php echo product_img_attrs($product['image'], $product['name'], 'card-img-top product-image'); ?>>
                                <?php else: ?>
                                    <img <?php echo product_img_attrs(null, 'No Image', 'card-img-top product-image'); ?>>
                                <?php endif; ?>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                    <p class="card-text"><?php echo truncateText($product['description'], 100); ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-danger"><?php echo formatCurrency(!empty($product['price2']) ? $product['price2'] : (!empty($product['sale_price']) ? $product['sale_price'] : $product['price'])); ?></span>
                                        <span></span>
                                    </div>
                                </div>
                                
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=show&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-primary">View Details</a>
                                    
                                    <?php if($product['stock_quantity'] > 0): ?>
                                        <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-success">Add to Cart</button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled>Out of Stock</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
