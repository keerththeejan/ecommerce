<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<!-- Banner Section -->
<div id="banner"></div>
<?php require_once APP_PATH . 'views/customer/banner/index.php'; ?>

<!-- Shop by Category - Same layout as Our Brands -->
<section id="categories" class="featured-categories py-3 py-md-4" style="background: <?php echo !empty($homeCategoriesBgColor) ? htmlspecialchars($homeCategoriesBgColor) : '#fff'; ?>;" data-theme-aware>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-3">
            <h2 id="categories-heading" class="section-title mb-0">Category</h2>
            <a href="<?php echo BASE_URL; ?>?controller=category&action=index" class="btn btn-sm btn-outline-primary d-none d-md-inline-flex">
                View All <i class="fas fa-chevron-right ms-1"></i>
            </a>
        </div>

        <?php
            $activeCategories = [];
            $totalActiveCategories = 0;
            if (!empty($categories)) {
                $activeCategories = array_values(array_filter($categories, function($cat) {
                    return $cat['status'] == 1;
                }));
                $totalActiveCategories = count($activeCategories);
                $activeCategories = array_slice($activeCategories, 0, 12);
            }
        ?>

        <?php if (!empty($activeCategories)) : ?>
        <div class="category-scroll-wrapper">
            <div class="category-scroll-track">
                <div class="category-scroll-inner">
                    <?php foreach($activeCategories as $category): ?>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=category&id=<?php echo $category['id']; ?>" class="category-scroll-item text-decoration-none">
                            <div class="category-card card h-100 border-0 shadow-sm transition-all">
                                <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                                    <div class="category-logo-container">
                                        <?php if (!empty($category['image'])) : ?>
                                            <img src="<?php echo BASE_URL . $category['image']; ?>" 
                                                 class="img-fluid" 
                                                 alt="<?php echo htmlspecialchars($category['name']); ?>" 
                                                 loading="lazy">
                                        <?php else : ?>
                                            <div class="d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-box-open fa-2x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="fw-bold small text-dark text-center mt-1 category-name"><?php echo htmlspecialchars($category['name']); ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="category-scroll-inner" aria-hidden="true">
                    <?php foreach($activeCategories as $category): ?>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=category&id=<?php echo $category['id']; ?>" class="category-scroll-item text-decoration-none">
                            <div class="category-card card h-100 border-0 shadow-sm transition-all">
                                <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                                    <div class="category-logo-container">
                                        <?php if (!empty($category['image'])) : ?>
                                            <img src="<?php echo BASE_URL . $category['image']; ?>" 
                                                 class="img-fluid" 
                                                 alt="" 
                                                 loading="lazy">
                                        <?php else : ?>
                                            <div class="d-flex align-items-center justify-content-center h-100">
                                                <i class="fas fa-box-open fa-2x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="fw-bold small text-dark text-center mt-1 category-name"><?php echo htmlspecialchars($category['name']); ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="text-center mt-3 d-md-none">
            <a href="<?php echo BASE_URL; ?>?controller=category&action=index" class="btn btn-outline-primary px-4">
                View All Categories <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <?php else: ?>
            <div class="alert alert-info mb-0">No categories available</div>
        <?php endif; ?>
    </div>
</section>

<!-- Trending Products - Top Selling Products -->
<section id="trending-products" class="trending-products py-4 py-md-5 position-relative">
    <div class="trending-section-bg"></div>
    <div class="container-fluid px-4 px-xl-5 max-width-1400 position-relative">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 mb-md-5">
            <div class="d-flex align-items-center gap-3">
                <div class="trending-icon-wrapper">
                    <i class="fas fa-fire trending-icon"></i>
                </div>
                <div>
                    <h2 class="section-title mb-0 trending-title">Trending Products</h2>
                    <p class="text-muted small mb-0 mt-1">Most popular items right now</p>
                </div>
            </div>
            <div class="trending-badge d-none d-md-flex align-items-center gap-2">
                <span class="badge bg-primary px-3 py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; border: none;">
                    <i class="fas fa-chart-line me-1"></i> Hot Right Now
                </span>
            </div>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2 g-md-4">
            <?php if(!empty($trendingProducts)) { ?>
                <?php foreach($trendingProducts as $product) { ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm product-card transition-all d-flex flex-column">
                            <!-- Image Section -->
                            <div class="product-media position-relative d-flex justify-content-center align-items-center">
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="text-decoration-none">
                                    <div class="product-image-box">
                                        <?php if(!empty($product['image'])) { ?>
                                            <img src="<?php echo BASE_URL . $product['image']; ?>" 
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                 loading="lazy"
                                                 class="product-image">
                                        <?php } else { ?>
                                            <div class="no-image-box d-flex align-items-center justify-content-center">
                                                <i class="fas fa-box-open fa-2x text-muted"></i>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </a>

                                <?php if(isLoggedIn() && $product['stock_quantity'] > 0) { ?>
                                    <button class="btn-wishlist position-absolute top-0 end-0 m-2 bg-white rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center"
                                            data-product-id="<?php echo $product['id']; ?>">
                                        <i class="far fa-heart text-muted"></i>
                                    </button>
                                <?php } ?>
                                
                                <?php if(isset($product['total_sold']) && $product['total_sold'] > 0) { ?>
                                    <div class="trending-sold-badge position-absolute top-0 start-0 m-2">
                                        <span class="badge trending-badge-sold">
                                            <i class="fas fa-fire me-1"></i><?php echo (int)$product['total_sold']; ?> Sold
                                        </span>
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- Content -->
                            <div class="card-body p-3 flex-grow-1 d-flex flex-column justify-content-between">
                                <!-- Stock badge -->
                                <div class="mb-1">
                                    <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'secondary'; ?> small">
                                        <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                    </span>
                                </div>
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                    <h3 class="h6 card-title mb-0 product-title" title="<?php echo htmlspecialchars($product['name']); ?>"><?php echo $product['name']; ?></h3>
                                    <p class="product-desc small text-muted mb-1 d-none d-md-block"><?php echo isset($product['description']) ? truncateText($product['description'], 50) : ''; ?></p>
                                    <div class="d-flex justify-content-between align-items-center mb-1 gap-0">
                                        <?php if(isLoggedIn()) { ?>
                                            <span class="fw-bold"><?php echo formatCurrency(isset($product['sale_price']) ? $product['sale_price'] : $product['price']); ?></span>
                                        <?php } else { ?>
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login"></a>
                                        <?php } ?>
                                        <div class="d-flex flex-column align-items-end product-meta text-end">
                                            <?php if($product['stock_quantity'] > 0) { ?>
                                                <small class="text-muted">Stock: <?php echo $product['stock_quantity']; ?> units</small>
                                                <?php if(isLoggedIn()) { ?>
                                                    <small class="text-muted">Value: <?php echo formatCurrency($product['stock_quantity'] * (isset($product['sale_price']) ? $product['sale_price'] : $product['price'])); ?></small>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </a>

                                <!-- Add to Cart -->
                                <?php if($product['stock_quantity'] > 0) { ?>
                                    <?php if(isLoggedIn()) { ?>
                                        <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="mt-auto add-to-cart-form">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <div class="product-actions-row">
                                                <div class="quantity-group">
                                                    <button type="button" class="btn quantity-decrease">-</button>
                                                    <input type="number" name="quantity" class="quantity-input" 
                                                           value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                                           aria-label="Quantity" readonly>
                                                    <button type="button" class="btn quantity-increase">+</button>
                                                </div>
                                                <button type="submit" class="btn-add-to-cart">
                                                    <i class="fas fa-cart-plus"></i>
                                                    <span>Add to Cart</span>
                                                </button>
                                            </div>
                                        </form>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="alert alert-danger py-1 mb-0 text-center">Out of Stock</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0">No trending products available</div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Featured Products - Enhanced filtering and responsive layout -->
<section id="featured-products" class="featured-products py-3 py-md-4 bg-light">
    <div class="container-fluid px-4 px-xl-5 max-width-1400">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 mb-md-4">
            <h2 class="section-title mb-0">Our Products</h2>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2 g-md-4">
            <?php if(!empty($featuredProducts)) { ?>
                <?php foreach($featuredProducts as $product) { ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm product-card transition-all d-flex flex-column">
                            <!-- 🖼️ Image Section - Responsive Box and Auto Image Resize -->
                            <div class="product-media position-relative d-flex justify-content-center align-items-center">
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="text-decoration-none">
                                    <div class="product-image-box">
                                        <?php if(!empty($product['image'])) { ?>
                                            <img src="<?php echo BASE_URL . $product['image']; ?>" 
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                 loading="lazy"
                                                 class="product-image">
                                        <?php } else { ?>
                                            <div class="no-image-box d-flex align-items-center justify-content-center">
                                                <i class="fas fa-box-open fa-2x text-muted"></i>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </a>

                                <?php if(isLoggedIn() && $product['stock_quantity'] > 0) { ?>
                                    <button class="btn-wishlist position-absolute top-0 end-0 m-2 bg-white rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center"
                                            data-product-id="<?php echo $product['id']; ?>">
                                        <i class="far fa-heart text-muted"></i>
                                    </button>
                                <?php } ?>
                            </div>

                            <!-- Content -->
                            <div class="card-body p-3 flex-grow-1 d-flex flex-column justify-content-between">
                                <!-- Stock badge moved above product name -->
                                <div class="mb-1">
                                    <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'secondary'; ?> small">
                                        <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                    </span>
                                </div>
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                    <h3 class="h6 card-title mb-0 text-truncate product-title"><?php echo $product['name']; ?></h3>
                                    <p class="product-desc small text-muted mb-1 d-none d-md-block"><?php echo isset($product['description']) ? truncateText($product['description'], 50) : ''; ?></p>
                                    <div class="d-flex justify-content-between align-items-center mb-1 gap-0">
                                        <?php if(isLoggedIn()) { ?>
                                            <span class="fw-bold"><?php echo formatCurrency(isset($product['sale_price']) ? $product['sale_price'] : $product['price']); ?></span>
                                        <?php } else { ?>
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login"></a>
                                        <?php } ?>
                                        <div class="d-flex flex-column align-items-end product-meta text-end">
                                            <?php if($product['stock_quantity'] > 0) { ?>
                                                <small class="text-muted">Stock: <?php echo $product['stock_quantity']; ?> units</small>
                                                <?php if(isLoggedIn()) { ?>
                                                    <small class="text-muted">Value: <?php echo formatCurrency($product['stock_quantity'] * (isset($product['sale_price']) ? $product['sale_price'] : $product['price'])); ?></small>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </a>

                                <!-- Add to Cart -->
                                <?php if($product['stock_quantity'] > 0) { ?>
                                    <?php if(isLoggedIn()) { ?>
                                        <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="mt-auto add-to-cart-form">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <div class="product-actions-row">
                                                <div class="quantity-group">
                                                    <button type="button" class="btn quantity-decrease">-</button>
                                                    <input type="number" name="quantity" class="quantity-input" 
                                                           value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                                           aria-label="Quantity" readonly>
                                                    <button type="button" class="btn quantity-increase">+</button>
                                                </div>
                                                <button type="submit" class="btn-add-to-cart">
                                                    <i class="fas fa-cart-plus"></i>
                                                    <span>Add to Cart</span>
                                                </button>
                                            </div>
                                        </form>
                                    <?php } ?>
                                <?php } else { ?>
                                    <div class="alert alert-danger py-1 mb-0 text-center">Out of Stock</div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0">No featured products available</div>
                </div>
            <?php } ?>
        </div>

        <?php if(!empty($featuredProducts)): ?>
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>?controller=product&action=all" class="btn btn-outline-primary px-4">
                View All Products <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>





<!-- Brand Showcase - Enhanced with responsive grid -->
<section id="brands" class="brands-showcase py-3 py-md-4 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-3">
            <h2 class="section-title mb-0">Our Brands</h2>
            <a href="<?php echo BASE_URL; ?>?controller=brand&action=all" class="btn btn-sm btn-outline-primary d-none d-md-inline-flex">
                View All <i class="fas fa-chevron-right ms-1"></i>
            </a>
        </div>
        
        <div class="row g-2 g-md-3 justify-content-center">
            <?php if(!empty($brands)) : ?>
                <?php foreach(array_slice($brands, 0, 12) as $brand) : ?>
                    <?php if($brand['status'] == 'active') : ?>
                        <div class="col-4 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                            <a href="<?php echo BASE_URL; ?>?controller=brand&action=show&param=<?php echo $brand['slug']; ?>" class="text-decoration-none">
                                <div class="brand-card card h-100 border-0 shadow-sm transition-all">
                                    <div class="card-body p-2 d-flex align-items-center justify-content-center">
                                        <div class="brand-logo-container">
                                            <?php if(!empty($brand['logo'])) : ?>
                                                <img src="<?php echo $brand['logo']; ?>" 
                                                     class="img-fluid" 
                                                     alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                                     loading="lazy"
                                                     onerror="this.onerror=null; this.src='<?php echo rtrim(BASE_URL, '/'); ?>/assets/images/default-brand.png';">
                                            <?php else : ?>
                                                <div class="text-center">
                                                    <span class="fw-bold small text-muted"><?php echo htmlspecialchars($brand['name']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <?php if(count($brands) > 12): ?>
                    <div class="col-4 col-sm-3 col-md-2 col-lg-2 col-xl-1 d-md-none">
                        <a href="<?php echo BASE_URL; ?>?controller=brand&action=all" class="text-decoration-none">
                            <div class="brand-card card h-100 border-0 shadow-sm transition-all bg-light">
                                <div class="card-body p-2 d-flex align-items-center justify-content-center">
                                    <div class="text-center">
                                        <i class="fas fa-ellipsis-h text-muted mb-1"></i>
                                        <p class="small mb-0">View All</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0">No brands available</div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if(!empty($brands) && count($brands) > 12): ?>
        <div class="text-center mt-3 d-md-none">
            <a href="<?php echo BASE_URL; ?>?controller=brand&action=all" class="btn btn-outline-primary px-4">
                View All Brands <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Responsive Styles -->
<style>
/* Base transition for interactive elements */
.transition-all {
    transition: all 0.3s ease;
}

/* Product actions row (matches All Products page) */
.product-actions-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.625rem;
}

/* Center the quantity and add-to-cart row and tighten the gap */
.add-to-cart-form .d-flex {
    justify-content: center;
    gap: 0.25rem !important; /* override gap-2 */
}

/* Slightly reduce default cart-quantity width */
.cart-quantity { max-width: 88px; }

/* Quantity group styling (matches All Products page) */
.quantity-group {
    display: flex;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
    background: #ffffff;
    min-width: 100px;
}

.quantity-group .btn {
    background: #ffffff;
    border: none;
    border-right: 1px solid #dee2e6;
    color: #495057;
    padding: 0.375rem 0.625rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    min-width: 32px;
}

.quantity-group .btn:first-child {
    border-right: 1px solid #dee2e6;
}

.quantity-group .btn:last-child {
    border-left: 1px solid #dee2e6;
    border-right: none;
}

.quantity-group .btn:hover {
    background: #f8f9fa;
    color: #212529;
}

.quantity-group input {
    border: none;
    border-left: 1px solid #dee2e6;
    border-right: 1px solid #dee2e6;
    width: 40px;
    text-align: center;
    padding: 0.375rem 0.25rem;
    font-weight: 600;
    font-size: 0.875rem;
    background: #ffffff;
}

/* Add to cart button styling */
.btn-add-to-cart {
    flex: 1;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    color: #ffffff;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0.5rem 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.375rem;
    transition: all 0.3s ease;
    white-space: nowrap;
}

/* Mobile only: prevent overflow, keep desktop unchanged */
@media (max-width: 767.98px) {
    .product-card .card-body,
    .trending-products .product-card .card-body,
    .featured-products .product-card .card-body {
        min-width: 0;
        overflow: hidden;
        padding: 0.5rem 0.75rem !important;
    }
    .product-actions-row {
        flex-wrap: wrap;
        gap: 0.35rem;
        max-width: 100%;
        min-width: 0;
    }
    .product-actions-row .quantity-group {
        min-width: 78px;
        flex-shrink: 0;
    }
    .product-actions-row .quantity-group input {
        width: 28px;
        padding: 0.25rem 0.1rem;
        font-size: 0.8rem;
    }
    .product-actions-row .quantity-group .btn {
        min-width: 28px;
        padding: 0.25rem 0.35rem;
        font-size: 0.8rem;
    }
    .product-actions-row .btn-add-to-cart {
        flex: 1 1 auto;
        min-width: 0;
        font-size: 0.75rem;
        padding: 0.4rem 0.5rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .product-actions-row .btn-add-to-cart span {
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }
}
@media (max-width: 479.98px) {
    .product-actions-row {
        flex-direction: column;
        align-items: stretch;
    }
    .product-actions-row .quantity-group {
        width: 100%;
        min-width: 0;
        justify-content: center;
    }
    .product-actions-row .btn-add-to-cart {
        width: 100%;
        min-width: 0;
    }
}

.btn-add-to-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: #ffffff;
}

/* Banner Styles */
.main-banner .carousel-item img {
    height: auto;
    max-height: 500px;
    object-fit: cover;
    width: 100%;
    
}

@media (max-width: 768px) {
    .main-banner .carousel-item img {
        max-height: 200px;
    }
}

/* Modern Vegist Theme Styles */
.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2d3436;
    letter-spacing: -0.5px;
    margin-bottom: 0;
    line-height: 1.2;
}

/* Section backgrounds */
.featured-products {
    background: #f8f9fa;
    padding: 3rem 0;
}

/* Dark theme for featured products section */
html[data-theme="dark"] .featured-products {
    background: transparent !important;
}

.brands-showcase {
    background: #fff;
    padding: 3rem 0;
}

/* Buttons - Modern Style */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1.25rem;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
}

.btn-outline-primary {
    border: 2px solid #667eea;
    color: #667eea;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: #fff;
    transform: translateY(-2px);
}

/* Category - Single line with auto-scroll */
.category-scroll-wrapper {
    overflow: hidden;
    mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
    -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
}
.category-scroll-track {
    display: flex;
    width: max-content;
    animation: categoryScroll 30s linear infinite;
}
.category-scroll-track:hover {
    animation-play-state: paused;
}
@keyframes categoryScroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.category-scroll-inner {
    display: flex;
    flex-wrap: nowrap;
    gap: 0.75rem;
    padding: 0.25rem 0;
}
.category-scroll-item {
    flex-shrink: 0;
    width: 100px;
}
.category-scroll-wrapper .category-logo-container {
    height: 56px;
    min-height: 56px;
}
.category-scroll-wrapper .category-name {
    font-size: 0.75rem;
}
@media (min-width: 576px) {
    .category-scroll-item { width: 110px; }
    .category-scroll-wrapper .category-logo-container { height: 64px; min-height: 64px; }
}
@media (min-width: 768px) {
    .category-scroll-item { width: 120px; }
    .category-scroll-wrapper .category-logo-container { height: 72px; min-height: 72px; }
}
@media (min-width: 992px) {
    .category-scroll-item { width: 130px; }
    .category-scroll-wrapper .category-logo-container { height: 80px; min-height: 80px; }
}

/* Modern Category Styles - Enhanced Theme */
.featured-categories {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    padding: 3rem 0;
    position: relative;
    overflow: hidden;
}

/* Dark theme for featured categories section */
html[data-theme="dark"] .featured-categories {
    background: transparent !important;
}

.featured-categories::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #667eea 100%);
    background-size: 200% 100%;
    animation: shimmer 3s ease-in-out infinite;
}

@keyframes shimmer {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.featured-categories .section-title {
    font-size: 2rem;
    font-weight: 700;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    letter-spacing: -0.5px;
}

.category-show-all-text {
    color: #667eea;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
}

.category-show-all:hover .category-show-all-text {
    color: #764ba2;
    transform: translateX(5px);
}

.category-show-all-text i {
    transition: transform 0.3s ease;
}

.category-show-all:hover .category-show-all-text i {
    transform: translateX(3px);
}

.category-card {
    background: #ffffff;
    border-radius: 16px;
    overflow: visible;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid #e9ecef;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

/* Dark theme overrides for category cards */
html[data-theme="dark"] .category-card {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(255, 255, 255, 0.15) !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3) !important;
}

html[data-theme="dark"] .category-card:hover {
    background: rgba(255, 255, 255, 0.12) !important;
    border-color: var(--theme-primary) !important;
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
    z-index: 1;
    pointer-events: none;
}

.category-card:hover::before {
    opacity: 1;
}

.category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 28px rgba(102, 126, 234, 0.25) !important;
    border-color: #667eea;
}

.category-image-box {
    width: 100%;
    height: 120px;
    min-height: 120px;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 50%, #f8f9fa 100%);
    border-radius: 16px 16px 8px 8px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    z-index: 2;
    margin: 8px 8px 0 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Dark theme for category image box */
html[data-theme="dark"] .category-image-box {
    background: rgba(255, 255, 255, 0.05) !important;
}

.category-image-box::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
    transition: transform 0.6s ease;
}

.category-card:hover .category-image-box::after {
    transform: scale(1.5);
}

.category-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
    transition: all 0.4s ease;
}

.category-card:hover .category-image {
    transform: scale(1.1) rotate(2deg);
    filter: drop-shadow(0 6px 12px rgba(102, 126, 234, 0.3));
}

.category-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3436 !important;
    padding: 16px 12px;
    position: relative;
    z-index: 2;
    transition: color 0.3s ease;
    background: #ffffff !important;
    margin: 0;
    border-radius: 0 0 16px 16px;
    text-align: center;
    min-height: 1.8rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    border: none !important;
}

/* Dark theme for category title */
html[data-theme="dark"] .category-title {
    background: rgba(255, 255, 255, 0.08) !important;
    color: var(--theme-text) !important;
}

html[data-theme="dark"] .category-card:hover .category-title {
    color: var(--theme-primary) !important;
}

.category-card:hover .category-title {
    color: #667eea !important;
}

.category-card .card-body {
    background: #ffffff !important;
    border: none !important;
    padding: 0 !important;
    margin: 0 !important;
}

/* Dark theme for category card body */
html[data-theme="dark"] .category-card .card-body {
    background: rgba(255, 255, 255, 0.08) !important;
}

.no-image-box {
    color: #b2bec3;
    transition: all 0.3s ease;
}

.category-card:hover .no-image-box {
    color: #667eea;
    transform: scale(1.1);
}

/* Responsive category heading */
@media (max-width: 767.98px) {
    .section-title {
        font-size: 1.35rem;
    }
    
    .featured-categories {
        padding: 2rem 0;
    }
}

/* Ensure anchor scroll aligns nicely with header for categories heading */
#categories-heading { scroll-margin-top: 140px; }
/* Offset for banner anchor so banner top is visible under fixed header */
#banner { scroll-margin-top: 100px; }

/* Product Card - Mobile-first responsive styles */
.product-card {
    display: flex;
    flex-direction: column;
    height: 100%;
}
.product-media {
    padding: 12px 0;
    flex: 0 0 auto;
}
.product-image-box {
    width: 100%;
    height: 160px;
    min-height: 160px;
    max-height: 160px;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    background: #f8f9fa;
    margin-bottom: 10px;
}
.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.25s ease;
}
.product-image:hover { transform: scale(1.04); }
.no-image-box {
    width: 100%;
    height: 100%;
    background-color: rgba(240,240,240,0.9);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Dark theme for product cards */
html[data-theme="dark"] .product-card {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(255, 255, 255, 0.15) !important;
    color: var(--theme-text) !important;
}

html[data-theme="dark"] .product-card:hover {
    background: rgba(255, 255, 255, 0.12) !important;
    border-color: var(--theme-primary) !important;
}

html[data-theme="dark"] .product-card .card-body {
    background: transparent !important;
    color: var(--theme-text) !important;
}

html[data-theme="dark"] .product-title,
html[data-theme="dark"] .product-card .card-title {
    color: var(--theme-text) !important;
}

html[data-theme="dark"] .product-desc {
    color: var(--theme-text) !important;
    opacity: 0.8;
}

html[data-theme="dark"] .product-image-box {
    background: rgba(255, 255, 255, 0.05) !important;
}

html[data-theme="dark"] .no-image-box {
    background-color: rgba(255, 255, 255, 0.08) !important;
    color: var(--theme-text) !important;
}
.product-title {
    font-size: 0.8rem;
    text-align: left;
    margin-bottom: 0.25rem;
    min-height: 2.4rem;
    max-height: 2.8rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.4;
    word-wrap: break-word;
    word-break: break-word;
}
.product-desc {
    font-size: 0.7rem;
    margin-bottom: 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.card-body {
    display: flex;
    flex-direction: column;
    flex-grow: 1;
    padding: 0.75rem !important;
}
.add-to-cart-form {
    margin-top: auto;
    padding-top: 0.5rem;
}

/* Category Media & Layout - Consolidated */
.category-media {
    padding: 0;
    flex: 0 0 auto;
    position: relative;
    z-index: 2;
}

@media (max-width: 575.98px) {
    .product-image-box { height: 140px; min-height: 140px; max-height: 140px; }
}
@media (min-width: 576px) {
    .product-image-box { height: 180px; min-height: 180px; max-height: 180px; }
    .category-image-box { height: 140px; padding: 25px 20px; }
}
@media (min-width: 768px) {
    .product-image-box { height: 200px; min-height: 200px; max-height: 200px; }
    .category-image-box { height: 160px; padding: 30px 25px; }
    .product-title { font-size: 0.9rem; }
    .category-title { font-size: 1rem; padding: 16px 12px; }
    .product-desc { font-size: 0.8rem; }
    .add-to-cart-form .btn { 
        height: 26px; 
        font-size: 0.75rem; 
        padding: 0.15rem 0.5rem;
    }
    .add-to-cart-form .btn i { 
        font-size: 0.7em; 
        margin-right: 2px;
    }
    /* Qty controls on >=768px - same height to align borders */
    .cart-quantity .form-control { height: 26px; }
    .cart-quantity .btn { height: 26px; min-width: 20px; }
}

/* Cart Quantity Controls */
.cart-quantity {
    max-width: 120px;
}
.cart-quantity .form-control {
    font-size: 0.7rem;
    padding: 0.08rem;
    text-align: center;
    height: 26px;
}
.cart-quantity .btn {
    padding: 0.08rem 0.3rem;
    font-size: 0.7rem;
    height: 26px;
}
.add-to-cart-form .btn i {
    font-size: 0.8em;
}

/* Make Add to Cart button narrower and prevent full-width expansion */
.add-to-cart-btn {
    flex: 0 0 auto;
    width: auto !important; /* override w-100 */
    min-width: 72px; /* further compact width */
    white-space: nowrap;
}
@media (min-width: 768px) {
    .add-to-cart-btn {
        min-width: 84px; /* compact on md+ */
    }
}

/* Align quantity controls neatly and ensure consistent sizing */
.cart-quantity {
    display: inline-flex;
    align-items: stretch;
    border-radius: .375rem;
}
.cart-quantity .form-control { height: 26px; line-height: normal; }
.cart-quantity .btn { height: 26px; line-height: normal; }
.cart-quantity .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    padding: 0 .25rem;
}
.cart-quantity .form-control {
    max-width: 48px;
    padding: 0 .25rem;
}
/* Unify border look to avoid visual misalignment */
.cart-quantity .btn,
.cart-quantity .form-control {
    border-width: 1px;
    border-color: #ced4da;
}
.cart-quantity .btn:first-child { border-top-right-radius: 0; border-bottom-right-radius: 0; }
.cart-quantity .form-control { border-radius: 0; }
.cart-quantity .btn:last-child { border-top-left-radius: 0; border-bottom-left-radius: 0; }

/* Remove inner borders so the group looks like a single outline */
.cart-quantity .qty-minus { border-right-width: 0 !important; }
.cart-quantity .quantity-input { border-left-width: 0 !important; border-right-width: 0 !important; }
.cart-quantity .qty-plus { border-left-width: 0 !important; }

/* Consistent focus without extra glow causing misalignment */
.cart-quantity .btn:focus,
.cart-quantity .form-control:focus {
    box-shadow: none;
    outline: none;
    border-color: #ced4da;
}

/* Ensure buttons are properly sized on mobile */
@media (max-width: 767px) {
    .add-to-cart-form .btn {
        padding: 0.15rem 0.35rem;
        font-size: 0.7rem;
        height: 24px;
    }
    .add-to-cart-form .btn i {
        font-size: 0.7em;
        margin-right: 2px;
    }
    .cart-quantity {
        width: 72px !important; /* narrower on mobile */
    }
    .cart-quantity .form-control {
        font-size: 0.7rem;
        padding: 0.08rem;
        height: 26px;
    }
    .cart-quantity .btn {
        padding: 0.08rem 0.25rem;
        font-size: 0.7rem;
        height: 26px; /* match input height for clean borders */
        min-width: 20px; /* decreased width */
    }
}

.categories-slider {
    position: relative;
    padding: 0 40px;
}

.categories-wrapper {
    overflow: hidden;
}

.categories-track {
    display: flex;
    transition: transform 0.5s ease;
}

.category-slide {
    min-width: 20%;
    padding: 0 15px;
    flex: 0 0 auto;
    
}

.category-card {
    text-align: center;
}

.category-image-wrapper {
    position: relative;
    padding-bottom: 100%;
    margin-bottom: 15px;
    overflow: hidden;
}

.category-image-wrapper::before {
    content: '';
    position: absolute;
    top: -20%;
    left: -20%;
    right: -20%;
    bottom: -20%;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    z-index: 1;
}

.splash-bg-1::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23ffecec" d="M41.3,-52.9C54.4,-47.3,66.6,-35.6,71.5,-21.2C76.4,-6.8,74,10.3,65.7,23.5C57.4,36.7,43.3,46,28.7,51.7C14.1,57.4,-0.9,59.5,-17.4,57.3C-33.9,55.2,-51.8,48.8,-63.5,35.8C-75.2,22.8,-80.6,3.2,-76.2,-13.8C-71.8,-30.8,-57.6,-45.2,-42.3,-50.5C-27,-55.8,-10.7,-52,2.8,-55.9C16.3,-59.8,28.2,-58.5,41.3,-52.9Z" transform="translate(100 100)"/></svg>'); }
.splash-bg-2::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23ecffec" d="M42.3,-57.7C55.4,-49.4,66.9,-37.9,71.5,-24.1C76.1,-10.3,73.8,5.8,67.8,19.9C61.8,34,52.1,46.1,39.7,54.5C27.3,62.9,12.1,67.6,-2.9,71.1C-18,74.6,-36,76.9,-45.6,68.1C-55.2,59.3,-56.4,39.4,-61.8,21.9C-67.2,4.4,-76.8,-10.7,-74.8,-24.1C-72.8,-37.5,-59.2,-49.2,-44.6,-57.1C-30,-65,-15,-69.1,0.2,-69.4C15.4,-69.7,29.2,-66,42.3,-57.7Z" transform="translate(100 100)"/></svg>'); }
.splash-bg-3::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23ecedff" d="M44.3,-63.3C57.8,-56.2,70.2,-44.3,74.3,-28.9C78.4,-13,77.3,4,71.4,18.5C65.5,33,54.8,45,42.1,53.7C29.4,62.4,14.7,67.8,0.2,67.5C-14.3,67.2,-28.6,61.2,-41.5,52.5C-54.4,43.8,-65.9,32.4,-71.1,18.1C-76.3,3.8,-75.2,-13.4,-68.3,-27.7C-61.4,-42,-48.7,-53.4,-35.2,-61.4C-21.7,-69.4,-7.2,-74,7.3,-83.8C21.8,-93.6,34.5,-75.8,44.3,-63.3Z" transform="translate(100 100)"/></svg>'); }
.splash-bg-4::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23fff5ec" d="M39.5,-57.1C51.4,-50.8,61.4,-40.2,67.5,-27.3C73.6,-14.4,75.8,0.8,72.1,14.5C68.4,28.2,58.8,40.3,46.7,48.7C34.6,57.1,20,61.8,4.7,55.9C-10.6,50,-26.6,33.5,-39.7,25.2C-52.8,16.9,-63,16.8,-65.8,8.2C-68.6,-0.4,-64,-17.5,-55.3,-30.1C-46.6,-42.7,-33.8,-50.8,-20.8,-56.5C-7.8,-62.2,5.4,-65.5,18.1,-63.9C30.8,-62.3,27.6,-63.4,39.5,-57.1Z" transform="translate(100 100)"/></svg>'); }
.splash-bg-5::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23f5ecff" d="M47.7,-67.7C60.9,-59.6,70.2,-44.8,74.3,-28.9C78.4,-13,77.3,4,71.4,18.5C65.5,33,54.8,45,42.1,53.7C29.4,62.4,14.7,67.8,0.2,67.5C-14.3,67.2,-28.6,61.2,-41.5,52.5C-54.4,43.8,-65.9,32.4,-71.1,18.1C-76.3,3.8,-75.2,-13.4,-68.3,-27.7C-61.4,-42,-48.7,-53.4,-35.2,-61.4C-21.7,-69.4,-7.2,-74,7.3,-83.8C21.8,-93.6,34.5,-75.8,47.7,-67.7Z" transform="translate(100 100)"/></svg>'); }

.category-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 70%;
    max-height: 70%;
    z-index: 2;
}

.category-name {
    font-size: 16px;
    font-weight: 500;
    color: #000;
    margin: 0;
    
}

.slider-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 30px;
    height: 30px;
    border: none;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 2;
    cursor: pointer;
}

.slider-nav.prev { left: 0; }
.slider-nav.next { right: 0; }

.slider-dots {
    margin-top: 30px;
}

.dot {
    width: 8px;
    height: 8px;
    border: none;
    background: #ddd;
    border-radius: 50%;
    margin: 0 4px;
    padding: 0;
    cursor: pointer;
    
}

.dot.active {
    background: #000;
    width: 24px;
    border-radius: 4px;
    
}


/* Product Card Styles - Modern Vegist Theme */
.product-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #f0f0f0;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12) !important;
    border-color: #e0e0e0;
}

.product-image-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border-radius: 12px;
    padding: 15px;
}

.product-title {
    color: #2d3436;
    font-weight: 600;
    font-size: 0.95rem;
}

/* Trending Products Enhanced Styling */
.trending-products {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    position: relative;
    overflow: hidden;
    border-top: 3px solid transparent;
    border-bottom: 3px solid transparent;
    background-image: 
        linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%),
        linear-gradient(90deg, #ff6b6b 0%, #ff6b6b 100%);
    background-size: 100% 100%, 100% 3px;
    background-position: center, top;
    background-repeat: no-repeat;
}

.trending-section-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(255, 107, 107, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 50%, rgba(255, 193, 7, 0.1) 0%, transparent 50%);
    pointer-events: none;
}

.trending-icon-wrapper {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
    animation: pulse-glow 2s ease-in-out infinite;
    transition: transform 0.3s ease;
}

.trending-icon-wrapper:hover {
    transform: scale(1.1);
}

.trending-icon {
    color: #fff;
    font-size: 1.75rem;
}

@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 8px 24px rgba(102, 126, 234, 0.5);
        transform: scale(1.05);
    }
}

.trending-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3436 !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.2;
}

.trending-badge-sold {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%) !important;
    color: #fff !important;
    font-weight: 700;
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
    box-shadow: 0 4px 8px rgba(255, 107, 107, 0.3);
    border-radius: 20px;
}

.trending-products .card {
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

/* Ensure trending products cards have proper styling */
.trending-products .card {
    background: #ffffff !important;
}

/* Product title improvements for trending section */
.trending-products .product-title {
    min-height: 2.4rem;
    max-height: 2.8rem;
    line-height: 1.4;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    word-wrap: break-word;
    word-break: break-word;
    hyphens: auto;
}

@media (min-width: 768px) {
    .trending-products .product-title {
        font-size: 0.9rem;
        min-height: 2.6rem;
        max-height: 3.2rem;
    }
}

.trending-products .card:hover {
    border-color: #667eea;
    box-shadow: 0 12px 28px rgba(102, 126, 234, 0.2) !important;
}

.trending-products .section-title,
.featured-products .section-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2d3436 !important;
}

/* Ensure trending section always has light theme regardless of dark mode */
/* Trending Products - Light theme (default) */
.trending-products {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
}

.trending-products .text-muted {
    color: #636e72 !important;
}

.trending-products .product-card,
.trending-products .card {
    background: #ffffff !important;
    border: 1px solid #e9ecef !important;
    color: #2d3436 !important;
}

.trending-products .product-card:hover,
.trending-products .card:hover {
    border-color: #667eea !important;
}

.trending-products .product-title,
.trending-products .card-title {
    color: #2d3436 !important;
}

/* Trending Products - Dark theme */
html[data-theme="dark"] .trending-products {
    background: transparent !important;
}

html[data-theme="dark"] .trending-products * {
    color: var(--theme-text) !important;
}

html[data-theme="dark"] .trending-products .text-muted {
    color: rgba(255, 255, 255, 0.7) !important;
}

html[data-theme="dark"] .trending-products .product-card,
html[data-theme="dark"] .trending-products .card {
    background: rgba(255, 255, 255, 0.08) !important;
    border: 1px solid rgba(255, 255, 255, 0.15) !important;
    color: var(--theme-text) !important;
}

html[data-theme="dark"] .trending-products .product-card:hover,
html[data-theme="dark"] .trending-products .card:hover {
    background: rgba(255, 255, 255, 0.12) !important;
    border-color: var(--theme-primary) !important;
}

html[data-theme="dark"] .trending-products .product-title,
html[data-theme="dark"] .trending-products .card-title,
html[data-theme="dark"] .trending-products .product-desc {
    color: var(--theme-text) !important;
}

html[data-theme="dark"] .trending-products .badge {
    color: #ffffff !important;
}

html[data-theme="dark"] .trending-products .btn-wishlist {
    background: rgba(255, 255, 255, 0.15) !important;
    border: 1px solid rgba(255, 255, 255, 0.2) !important;
}

html[data-theme="dark"] .trending-products .btn-wishlist:hover {
    background: rgba(255, 255, 255, 0.25) !important;
}

html[data-theme="dark"] .trending-products .btn-wishlist i {
    color: var(--theme-text) !important;
}

/* Ensure product images are visible in dark theme */
html[data-theme="dark"] .trending-products .product-image-box {
    background: rgba(255, 255, 255, 0.05) !important;
}

html[data-theme="dark"] .trending-products .no-image-box {
    background-color: rgba(255, 255, 255, 0.08) !important;
    color: var(--theme-text) !important;
}

/* Link color in dark mode */
html[data-theme="dark"] .trending-products .product-link {
    color: var(--theme-text) !important;
}

html[data-theme="dark"] .trending-products .product-link:hover {
    color: var(--theme-primary) !important;
}

.product-image-container {
    position: relative;
    overflow: hidden;
}

.product-image-container img {
    transition: transform 0.3s ease;
}

.product-card:hover .product-image-container img {
    transform: scale(1.05);
}

.btn-wishlist {
    width: 32px;
    height: 32px;
    border: none;
    transition: all 0.3s ease;
}

.btn-wishlist:hover {
    color: #dc3545 !important;
}

.btn-wishlist.active {
    color: #dc3545 !important;
}


.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Brand Styles */
.brand-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
}

.brand-logo-container {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    
}

.brand-logo-container img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    transition: all 0.3s ease;
}

.brand-card:hover .brand-logo-container img {
    opacity: 1;
}

/* Shop by Category – brand animations only (same as Our Brands) */
.featured-categories .category-card {
    transition: all 0.3s ease;
}
.featured-categories .category-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
}
.featured-categories .category-logo-container {
    height: 80px;
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.featured-categories .category-logo-container img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    transition: all 0.3s ease;
}
.featured-categories .category-name {
    font-size: 0.9rem;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .brand-logo-container { height: 40px; }
    .featured-categories .category-logo-container {
        height: 56px;
        min-height: 56px;
    }
    
    .product-card .card-title {
        font-size: 0.9rem;
    }
    
    .product-card .card-text {
        font-size: 0.8rem;
    }
    
    /* Trending products mobile improvements */
    .trending-title {
        font-size: 1.4rem !important;
    }
    
    .trending-icon-wrapper {
        width: 50px;
        height: 50px;
    }
    
    .trending-icon {
        font-size: 1.5rem;
    }
    
    .featured-categories .category-name {
        font-size: 0.8rem !important;
    }
}

@media (max-width: 575.98px) {
    .brand-logo-container { height: 30px; }
    .featured-categories .category-logo-container {
        height: 48px;
        min-height: 48px;
    }
    
    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .input-group-sm > .form-control,
    .input-group-sm > .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
}

/* Removed full-width-section - using consistent container width */

/* Hero Carousel Styles */
.hero-carousel {
    margin-bottom: 3rem;
}

.hero-slide {
    height: 80vh; /* 80% of viewport height */
    min-height: 600px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.hero-slide::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.1) 100%);
}

.hero-content {
    color: #fff;
    padding: 2rem;
    border-radius: 10px;
    position: relative;
    z-index: 2;
}

.hero-subtitle {
    font-size: 1.2rem;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 1rem;
    color: #f8f9fa;
    font-weight: 500;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.hero-text {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    max-width: 600px;
}

.hero-buttons .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.hero-buttons .btn-primary {
    box-shadow: 0 5px 15px rgba(0,123,255,0.4);
}

.hero-buttons .btn-outline-light {
    border-width: 2px;
}

.hero-buttons .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* Carousel Controls */
.carousel-control-prev, .carousel-control-next {
    width: 5%;
    opacity: 0;
    transition: all 0.3s ease;
}

.hero-carousel:hover .carousel-control-prev,
.hero-carousel:hover .carousel-control-next {
    opacity: 0.8;
}

.carousel-indicators {
    margin-bottom: 2rem;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.5);
    border: none;
    margin: 0 5px;
    
}

.carousel-indicators button.active {
    background-color: #fff;
    transform: scale(1.2);
}

/* Responsive Styles */
@media (max-width: 991.98px) {
    .hero-slide {
        height: 500px;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-text {
        font-size: 1.1rem;
    }
}

@media (max-width: 767.98px) {
    .hero-slide {
        height: 450px;
        background-position: 70% center;
    }
    
    .hero-slide::before {
        background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.6) 50%, rgba(0,0,0,0.4) 100%);
    }
    
    .hero-content {
        text-align: center;
        padding: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
        letter-spacing: 2px;
    }
    
    .hero-title {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    
    .hero-text {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .hero-buttons .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 575.98px) {
    .hero-slide {
        height: 400px;
    }
    
    .hero-content {
        padding: 1rem;
    }
    
    .hero-title {
        font-size: 1.75rem;
    }
    
    .hero-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .hero-buttons .btn-outline-light {
        margin-left: 0 !important;
    }
}

/* Utility Classes */
.object-fit-cover {
    object-fit: cover;
}

.object-fit-contain {
    object-fit: contain;
}

.ratio-1x1 {
    aspect-ratio: 1 / 1;
}

.max-width-1400 {
    max-width: 1680px;
    margin-left: auto;
    margin-right: auto;
}

/* Modern Vegist Theme Enhancements */
.badge {
    border-radius: 6px;
    font-weight: 600;
    padding: 0.35rem 0.65rem;
    font-size: 0.75rem;
}

.badge.bg-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%) !important;
}

.badge.bg-success {
    background: linear-gradient(135deg, #51cf66 0%, #40c057 100%) !important;
}

.price-text {
    color: #2d3436;
    font-size: 1.1rem;
    font-weight: 700;
}

/* Improved section backgrounds */
.featured-products {
    background: #f8f9fa;
}

.brands-showcase {
    background: #fff;
}

/* Modern button styles */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
}

.btn-outline-primary {
    border: 2px solid #667eea;
    color: #667eea;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #667eea;
    color: #fff;
    transform: translateY(-2px);
}

/* Responsive Homepage Theme - Mobile First */
@media (max-width: 575.98px) {
    /* Optimize section spacing on mobile */
    .featured-categories,
    .trending-products,
    .featured-products,
    .brands-showcase {
        padding-top: 1.25rem !important;
        padding-bottom: 1.25rem !important;
    }
    
    .banner-carousel {
        padding-top: 0.75rem !important;
        padding-bottom: 0.75rem !important;
    }
    
    /* Reduce container padding on mobile */
    .container-fluid.px-4 {
        padding-left: 0.75rem !important;
        padding-right: 0.75rem !important;
    }
    
    /* Mobile heading sizes */
    .section-title {
        font-size: 1.25rem !important;
        margin-bottom: 1rem !important;
    }
    
    .featured-categories,
    .trending-products,
    .featured-products {
        padding: 2rem 0 !important;
    }
    
    /* Mobile section margins */
    .mb-3.mb-md-4,
    .mb-4 {
        margin-bottom: 1rem !important;
    }
    
    /* Mobile cart sizing */
    .add-to-cart-form .btn { height: 36px !important; }
    .cart-quantity { width: 100px !important; }
    .cart-quantity .btn,
    .cart-quantity .form-control { height: 36px !important; line-height: 36px !important; }
    
    /* Trending badge mobile */
    .trending-products .badge {
        font-size: 0.65rem !important;
        padding: 0.25rem 0.4rem !important;
    }
    
    /* Card body padding on mobile */
    .category-card .card-body {
        padding: 0.75rem !important;
    }
    
    .category-image-box {
        padding: 15px 12px !important;
        height: 100px !important;
        margin: 6px 6px 0 6px !important;
    }
    
    .category-title {
        font-size: 0.85rem !important;
        padding: 12px 8px !important;
        background: #ffffff !important;
    }
    
    .product-card .card-body {
        padding: 0.75rem !important;
    }
    
    /* Product media padding */
    .product-media {
        padding: 8px 0 !important;
    }
    
    .category-media {
        padding: 8px 0 !important;
    }
    
    /* Remove unwanted gaps */
    section + section {
        margin-top: 0 !important;
    }
}

/* Tablet Responsive */
@media (min-width: 576px) and (max-width: 991.98px) {
    .featured-categories,
    .trending-products,
    .featured-products,
    .brands-showcase {
        padding-top: 2rem !important;
        padding-bottom: 2rem !important;
    }
    
    .container-fluid.px-4 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
}

/* Desktop Optimizations */
@media (min-width: 992px) {
    /* Ensure consistent spacing */
    .featured-categories,
    .trending-products,
    .featured-products,
    .brands-showcase {
        padding-top: 2.5rem !important;
        padding-bottom: 2.5rem !important;
    }
    
    /* Remove unwanted section gaps */
    section {
        margin-bottom: 0;
    }
    
    section + section {
        margin-top: 0;
    }
}

/* Fix spacing between sections globally */
.featured-categories + .trending-products,
.trending-products + .featured-products,
.featured-products + .brands-showcase {
    margin-top: 0 !important;
}

<!-- Product Details Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add to Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="add-to-cart-form">
                <input type="hidden" name="product_id" id="modalProductId" value="">
                <div class="modal-body">
                    <h6 id="modalProductName" class="mb-3"></h6>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <span class="me-2">Price:</span>
                            <span class="h5 mb-0 text-primary" id="modalProductPrice"></span>
                        </div>
                        <div class="input-group input-group-sm" style="width: 140px;">
                            <button type="button" class="btn btn-outline-secondary quantity-decrease px-2">-</button>
                            <input type="number" name="quantity" class="form-control text-center quantity-input" 
                                   value="1" min="1" max="1" aria-label="Quantity">
                            <button type="button" class="btn btn-outline-secondary quantity-increase px-2">+</button>
                        </div>
                    </div>
                    <div class="small text-muted mb-3">
                        <span id="stockInfo"></span> available
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-cart-plus me-1"></i> Add to Cart
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle modal show event
    var productModal = document.getElementById('productModal');
    if (productModal) {
        productModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var productId = button.getAttribute('data-product-id');
            var productName = button.getAttribute('data-product-name');
            var productPrice = parseFloat(button.getAttribute('data-product-price'));
            var stockQuantity = parseInt(button.getAttribute('data-product-stock'));
            
            // Update modal content
            document.getElementById('modalProductId').value = productId;
            document.getElementById('modalProductName').textContent = productName;
            document.getElementById('modalProductPrice').textContent = '₹' + productPrice.toFixed(2);
            document.getElementById('stockInfo').textContent = stockQuantity + ' units';
            
            // Update quantity input max value
            var quantityInput = productModal.querySelector('.quantity-input');
            quantityInput.max = stockQuantity;
            quantityInput.value = 1;
        });
    }
    
    // Quantity controls are handled globally in footer.php
    // This prevents duplicate handlers
    
    // Wishlist functionality
    const wishlistButtons = document.querySelectorAll('.btn-wishlist');
    wishlistButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const icon = this.querySelector('i');
            
            if (!productId) {
                console.warn('Wishlist: Product ID not found');
                return;
            }
            
            // Toggle active state visually
            this.classList.toggle('active');
            if (icon) {
                if (this.classList.contains('active')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    icon.style.color = '#dc3545';
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    icon.style.color = '';
                }
            }
            
            // Send AJAX request
            const url = '<?php echo BASE_URL; ?>?controller=wishlist&action=add&id=' + productId;
            
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
                    // Show success message
                    const toast = document.createElement('div');
                    toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
                    toast.setAttribute('role', 'alert');
                    toast.setAttribute('aria-live', 'assertive');
                    toast.setAttribute('aria-atomic', 'true');
                    toast.innerHTML = `
                        <div class="d-flex">
                            <div class="toast-body">
                                <i class="fas fa-check-circle me-2"></i> ${data.message || 'Added to wishlist'}
                            </div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                    `;
                    document.body.appendChild(toast);
                    const toastInstance = new bootstrap.Toast(toast);
                    toastInstance.show();
                    toast.addEventListener('hidden.bs.toast', function() {
                        toast.remove();
                    });
                } else {
                    // Revert visual state
                    button.classList.toggle('active');
                    if (icon) {
                        if (button.classList.contains('active')) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                        }
                    }
                    alert(data.message || 'Failed to add to wishlist');
                }
            })
            .catch(error => {
                console.error('Wishlist error:', error);
                // Revert visual state
                button.classList.toggle('active');
                if (icon) {
                    if (button.classList.contains('active')) {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    } else {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    }
                }
                alert('An error occurred. Please try again.');
            });
        });
    });
    
    // Product card hover effects
    const productCards = document.querySelectorAll('.product-card');
    productCards.forEach(function(card) {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'all 0.3s ease';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Lazy loading for product images
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(function(img) {
            imageObserver.observe(img);
        });
    }
    
    // Update cart count on page load if user is logged in
    if (typeof updateCartCount === 'function') {
        updateCartCount();
    }
    
    // Add smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#' && href.length > 1) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
});    
</script>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>