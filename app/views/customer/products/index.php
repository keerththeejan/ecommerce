<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="storefront-shell py-4 py-lg-5">
    <div class="storefront-grid storefront-grid--sidebar">
        <!-- Sidebar with categories - Hidden on mobile -->
        <div class="d-none d-lg-block">
            <div class="sticky-top" style="top: 120px;">
                <div class="card mb-4 storefront-sidebar-card">
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
                
                <div class="card storefront-sidebar-card">
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
        </div>
        
        <!-- Main content - Full width on mobile -->
        <div class="storefront-section">
            <!-- Mobile filter toggle button -->
            <div class="d-md-none mb-3">
                <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileFilters" aria-controls="mobileFilters">
                    <i class="fas fa-filter"></i> Filter Products
                </button>
            </div>
            
            <div class="storefront-toolbar">
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
                <div class="products-grid pc-grid">
                    <?php foreach($products['data'] as $product): ?>
                        <article class="product-card pc-card">
                            <div class="product-image-container pc-media position-relative">
                                <span class="pc-stock-badge badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'secondary'; ?>">
                                    <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out'; ?>
                                </span>
                                <?php echo wishlist_heart_button($product['id']); ?>
                                <?php if(!empty($product['image'])): ?>
                                    <img <?php echo product_img_attrs($product['image'], $product['name'], 'product-image'); ?>>
                                <?php else: ?>
                                    <img <?php echo product_img_attrs(null, 'No Image', 'product-image'); ?>>
                                <?php endif; ?>
                            </div>

                            <div class="card-body pc-body">
                                <h5 class="card-title pc-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <p class="card-text pc-desc"><?php echo !empty($product['description']) ? htmlspecialchars(truncateText(strip_tags($product['description']), 90)) : '&nbsp;'; ?></p>

                                <div class="pc-meta price-stock-row">
                                    <div class="price-container pc-price">
                                        <?php if(isLoggedIn()): ?>
                                            <?php echo formatCurrency(!empty($product['price2']) ? $product['price2'] : (!empty($product['sale_price']) ? $product['sale_price'] : $product['price'])); ?>
                                        <?php else: ?>
                                            <span class="text-muted small">Login for price</span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="pc-stock stock-label"><?php echo $product['stock_quantity'] > 0 ? ('Stock: ' . (int)$product['stock_quantity']) : 'Unavailable'; ?></span>
                                </div>

                                <?php if($product['stock_quantity'] > 0 && isLoggedIn()): ?>
                                    <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="add-to-cart-form">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <div class="actions pc-actions">
                                            <div class="quantity-group">
                                                <button type="button" class="btn quantity-decrease" aria-label="Decrease">-</button>
                                                <input type="number" name="quantity" class="quantity-input" value="1" min="1" max="<?php echo (int)$product['stock_quantity']; ?>" inputmode="numeric" pattern="[0-9]*" aria-label="Quantity">
                                                <button type="button" class="btn quantity-increase" aria-label="Increase">+</button>
                                            </div>
                                            <button type="submit" class="btn-add-to-cart">
                                                <i class="bi bi-cart-plus-fill" aria-hidden="true"></i>
                                                <span>Add to Cart</span>
                                            </button>
                                        </div>
                                    </form>
                                <?php elseif($product['stock_quantity'] > 0 && !isLoggedIn()): ?>
                                    <div class="actions pc-actions">
                                        <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-sm btn-outline-secondary">Login</a>
                                    </div>
                                <?php else: ?>
                                    <div class="actions pc-actions">
                                        <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        <button class="btn btn-sm btn-secondary" disabled>Stock Out</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </article>
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
                
                <!-- Specific Products Section -->
                <?php if(!empty($specificProducts)): ?>
                    <div class="mt-5 pt-4 border-top">
                        <h3 class="mb-4">Featured Products</h3>
                        <div class="products-grid pc-grid">
                            <?php foreach($specificProducts as $product): ?>
                                <article class="product-card pc-card">
                                    <div class="product-image-container pc-media position-relative">
                                        <span class="pc-stock-badge badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'secondary'; ?>">
                                            <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out'; ?>
                                        </span>
                                        <?php echo wishlist_heart_button($product['id']); ?>
                                        <?php if(!empty($product['image'])): ?>
                                            <img <?php echo product_img_attrs($product['image'], $product['name'], 'product-image'); ?>>
                                        <?php else: ?>
                                            <img <?php echo product_img_attrs(null, 'No Image', 'product-image'); ?>>
                                        <?php endif; ?>
                                    </div>

                                    <div class="card-body pc-body">
                                        <h5 class="card-title pc-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <p class="card-text pc-desc"><?php echo !empty($product['description']) ? htmlspecialchars(truncateText(strip_tags($product['description']), 90)) : '&nbsp;'; ?></p>

                                        <div class="pc-meta price-stock-row">
                                            <div class="price-container pc-price">
                                                <?php if(isLoggedIn()): ?>
                                                    <?php echo formatCurrency(!empty($product['price2']) ? $product['price2'] : (!empty($product['sale_price']) ? $product['sale_price'] : $product['price'])); ?>
                                                <?php else: ?>
                                                    <span class="text-muted small">Login for price</span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="pc-stock stock-label"><?php echo $product['stock_quantity'] > 0 ? ('Stock: ' . (int)$product['stock_quantity']) : 'Unavailable'; ?></span>
                                        </div>

                                        <?php if($product['stock_quantity'] > 0 && isLoggedIn()): ?>
                                            <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="add-to-cart-form">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <div class="actions pc-actions">
                                                    <div class="quantity-group">
                                                        <button type="button" class="btn quantity-decrease" aria-label="Decrease">-</button>
                                                        <input type="number" name="quantity" class="quantity-input" value="1" min="1" max="<?php echo (int)$product['stock_quantity']; ?>" inputmode="numeric" pattern="[0-9]*" aria-label="Quantity">
                                                        <button type="button" class="btn quantity-increase" aria-label="Increase">+</button>
                                                    </div>
                                                    <button type="submit" class="btn-add-to-cart">
                                                        <i class="bi bi-cart-plus-fill" aria-hidden="true"></i>
                                                        <span>Add to Cart</span>
                                                    </button>
                                                </div>
                                            </form>
                                        <?php elseif($product['stock_quantity'] > 0 && !isLoggedIn()): ?>
                                            <div class="actions pc-actions">
                                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-sm btn-outline-secondary">Login</a>
                                            </div>
                                        <?php else: ?>
                                            <div class="actions pc-actions">
                                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                <button class="btn btn-sm btn-secondary" disabled>Out of Stock</button>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Mobile Offcanvas Filters -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileFilters" aria-labelledby="mobileFiltersLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileFiltersLabel">Filter Products</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Categories</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="list-group-item list-group-item-action <?php echo (!isset($_GET['param']) || empty($_GET['action']) || $_GET['action'] == 'index') ? 'active' : ''; ?>">
                    All Products
                </a>
                <?php foreach($categories as $category): ?>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=category&param=<?php echo $category['id']; ?>" class="list-group-item list-group-item-action <?php echo (isset($_GET['param']) && $_GET['param'] == $category['id']) ? 'active' : ''; ?>">
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
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
