<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>
<?php
$catName = isset($category['name']) ? htmlspecialchars($category['name']) : 'Category';
$catId = isset($category['id']) ? (int)$category['id'] : 0;
?>
<div class="container py-4 py-lg-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=product&action=index">Products</a></li>
        </ol>
    </nav>

    <!-- Mobile: Filter / Category toggle button -->
    <div class="d-md-none mb-3">
        <button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-between" type="button" data-bs-toggle="offcanvas" data-bs-target="#categoryOffcanvas" aria-controls="categoryOffcanvas">
            <span><i class="fas fa-filter me-2"></i>Filter by Category</span>
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>

    <div class="row g-3 g-lg-4">
        <!-- Sidebar - hidden on mobile, offcanvas on mobile -->
        <div class="col-md-3 d-none d-md-block">
            <div class="sticky-top" style="top: 100px;">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Categories</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="list-group-item list-group-item-action">All Products</a>
                    <?php foreach($categories as $cat): ?>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=category&param=<?php echo (int)$cat['id']; ?>" class="list-group-item list-group-item-action <?php echo (isset($cat['id']) && (int)$cat['id'] === $catId) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($cat['name'] ?? ''); ?>
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

        <!-- Mobile Offcanvas: Category filter -->
        <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="categoryOffcanvas">
            <div class="offcanvas-header border-bottom">
                <h5 class="offcanvas-title">Filter by Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0">
                <div class="list-group list-group-flush">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=index" class="list-group-item list-group-item-action">All Products</a>
                    <?php foreach($categories as $cat): ?>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=category&param=<?php echo (int)$cat['id']; ?>" class="list-group-item list-group-item-action <?php echo (isset($cat['id']) && (int)$cat['id'] === $catId) ? 'active' : ''; ?>">
                            <?php echo htmlspecialchars($cat['name'] ?? ''); ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=sale" class="list-group-item list-group-item-action text-danger"><i class="fas fa-fire me-2"></i>Products on Sale</a>
                </div>
            </div>
        </div>
        
        <!-- Main content -->
        <div class="col-12 col-md-9">
            <!-- Search bar -->
            <div class="d-flex justify-content-end mb-4">
                <!-- Search form -->
                <form action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET" class="d-flex flex-grow-1 flex-md-grow-0">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="search">
                    <input type="text" name="keyword" class="form-control form-control-sm me-2" placeholder="Search products...">
                    <button type="submit" class="btn btn-outline-primary btn-sm flex-shrink-0">Search</button>
                </form>
            </div>
            
            <?php if(!empty($category['description'])): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="card-text"><?php echo $category['description']; ?></p>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if(empty($products)): ?>
                <div class="alert alert-info">No products found in this category.</div>
            <?php else: ?>
                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-3 category-page premium-product-grid">
                    <?php foreach($products as $product): ?>
                        <div class="col">
                            <article class="product-card pc-card h-100">
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
                                    <p class="card-text pc-desc"><?php echo htmlspecialchars(truncateText(strip_tags($product['description'] ?? ''), 90)); ?></p>
                                    <div class="pc-meta price-stock-row">
                                        <?php if(isLoggedIn()): ?>
                                            <span class="pc-price"><?php echo formatCurrency(!empty($product['price2']) ? $product['price2'] : (!empty($product['sale_price']) ? $product['sale_price'] : $product['price'])); ?></span>
                                        <?php else: ?>
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="pc-price text-muted small text-decoration-none">Login for price</a>
                                        <?php endif; ?>
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
                                    <?php elseif($product['stock_quantity'] > 0): ?>
                                        <div class="actions pc-actions">
                                            <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-sm btn-outline-secondary">Login</a>
                                        </div>
                                    <?php else: ?>
                                        <div class="actions pc-actions">
                                            <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                                            <button class="btn btn-sm btn-outline-secondary" disabled>Out of Stock</button>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
