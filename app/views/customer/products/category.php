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
                <style>
                    .category-page .product-image-container {
                        width: 100%;
                        height: 160px;
                        min-height: 160px;
                        max-height: 160px;
                        overflow: hidden;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: #f8f9fa;
                        padding: 0;
                    }
                    .category-page .fixed-height-img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        object-position: center;
                    }
                    .category-page .product-card { height: 100%; transition: transform 0.2s, box-shadow 0.2s; }
                    .category-page .product-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
                    @media (min-width: 768px) { .category-page .product-image-container { height: 180px; min-height: 180px; max-height: 180px; } }
                    @media (min-width: 992px) { .category-page .product-image-container { height: 200px; min-height: 200px; max-height: 200px; } }
                </style>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-3 category-page">
                    <?php foreach($products as $product): ?>
                        <div class="col">
                            <div class="product-card card h-100 border shadow-sm">
                                <div class="product-image-container">
                                    <?php if(!empty($product['image'])): ?>
                                        <img src="<?php echo BASE_URL . $product['image']; ?>" class="fixed-height-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" class="fixed-height-img" alt="No Image">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title small text-truncate" style="-webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text small text-muted flex-grow-1" style="font-size: 0.8rem; -webkit-line-clamp: 2; display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden;"><?php echo htmlspecialchars(truncateText($product['description'] ?? '', 100)); ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center" style="font-size: 0.9rem; min-height: 24px;">
                                        <?php if(isLoggedIn()): ?>
                                            <?php if(!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                                <span class="text-danger fw-bold"><?php echo formatCurrency($product['sale_price']); ?></span>
                                                <span></span> <!-- Empty span for alignment -->
                                            <?php else: ?>
                                                <span class="text-danger fw-bold"><?php echo formatCurrency($product['price']); ?></span>
                                                <span></span> <!-- Empty span for alignment -->
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="text-primary" style="font-size: 0.8rem;">Login to view price</a>
                                            <span></span> <!-- Empty span for alignment -->
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="card-footer d-flex justify-content-between p-2" style="background-color: #f8f9fa;">
                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=show&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary py-1" style="font-size: 0.7rem; padding-left: 0.5rem; padding-right: 0.5rem;">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    
                                    <?php if($product['stock_quantity'] > 0): ?>
                                        <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="mb-0">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-sm btn-success py-1" style="font-size: 0.7rem; padding-left: 0.5rem; padding-right: 0.5rem;">
                                                <i class="fas fa-cart-plus me-1"></i>Add
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary py-1" style="font-size: 0.7rem;" disabled>
                                            <i class="fas fa-times-circle me-1"></i>Out of Stock
                                        </button>
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
