<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<style>
/* Professional Product Grid Styling */
.all-products-container {
    max-width: 1680px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.products-header {
    margin-bottom: 2rem;
}

.products-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3436;
    margin-bottom: 1.5rem;
}

.filter-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
}

.product-card {
    height: 100%;
    border: 1px solid #e0e0f0;
    border-radius: 12px;
    overflow: hidden;
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    padding: 0;
    max-width: 100%;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-color: #667eea;
}

.product-image-container {
    position: relative;
    width: 100%;
    height: 180px;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 0.75rem 1rem;
}

.product-image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image-container img {
    transform: scale(1.05);
}

.btn-wishlist {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 36px;
    height: 36px;
    background: #ffffff;
    border: none;
    border-radius: 50%;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: all 0.3s ease;
}

.btn-wishlist:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-wishlist i {
    color: #2d3436;
    font-size: 1rem;
}

.stock-badge {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    background: #28a745;
    color: #ffffff;
    font-size: 0.7rem;
    font-weight: 700;
    border-radius: 20px;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.product-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #2d3436;
    margin-bottom: 0.625rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    word-wrap: break-word;
    word-break: break-word;
}

.product-price-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.625rem;
}

.product-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d3436;
    line-height: 1.2;
}

.product-meta {
    text-align: right;
    font-size: 0.7rem;
    color: #2d3436;
    line-height: 1.5;
    font-weight: 400;
}

.product-actions-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.625rem;
}

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

.btn-add-to-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: #ffffff;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
    }
}

@media (max-width: 992px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.875rem;
    }
    
    .filter-section .d-flex {
        flex-direction: column;
        gap: 0.75rem !important;
    }
    
    .filter-section .d-flex > * {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .all-products-container {
        padding: 1rem 0.5rem;
    }
    
    .products-header h1 {
        font-size: 1.75rem;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    
    .product-image-container {
        height: 150px;
        padding: 0.625rem 0.75rem;
    }
    
    .product-card .card-body {
        padding: 0.75rem 0.875rem 0.875rem !important;
        min-width: 0;
        overflow: hidden;
    }
    
    .product-title {
        font-size: 0.85rem;
    }
    
    .product-price {
        font-size: 1.25rem;
    }
    
    .product-meta {
        font-size: 0.7rem;
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
    
    .btn-add-to-cart {
        flex: 1 1 auto;
        min-width: 0;
        font-size: 0.75rem;
        padding: 0.4rem 0.5rem;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .btn-add-to-cart span {
        display: inline;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .btn-add-to-cart i {
        flex-shrink: 0;
    }
    
    .filter-section {
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .product-image-container {
        height: 180px;
    }
    
    .product-actions-row {
        flex-direction: column;
    }
    
    .quantity-group {
        width: 100%;
    }
    
    .btn-add-to-cart {
        width: 100%;
    }
    
    .btn-add-to-cart span {
        display: inline;
    }
}

@media (max-width: 576px) {
    .filter-section .row > div {
        margin-bottom: 0.75rem;
    }
    
    .filter-section .d-flex {
        flex-wrap: wrap;
    }
    
    .filter-section .btn {
        white-space: nowrap;
        min-width: 80px;
    }
}
</style>

<div class="container-fluid all-products-container">
    <div class="products-header">
        <h1>All Products</h1>
        
        <!-- Filters and Search -->
        <div class="filter-section">
            <div class="row g-3">
                <div class="col-12 col-md-6">
                    <form action="" method="GET" class="d-flex gap-2 flex-wrap">
                        <select name="category" class="form-select form-select-sm flex-grow-1" style="min-width: 150px;">
                            <option value="">All Categories</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="sort" class="form-select form-select-sm flex-grow-1" style="min-width: 150px;">
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="price_asc">Price (Low-High)</option>
                            <option value="price_desc">Price (High-Low)</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary" style="white-space: nowrap;">Filter</button>
                    </form>
                </div>
                <div class="col-12 col-md-6">
                    <form action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET" class="d-flex gap-2">
                        <input type="hidden" name="controller" value="product">
                        <input type="hidden" name="action" value="search">
                        <input type="text" name="keyword" class="form-control form-control-sm flex-grow-1" placeholder="Search products...">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="products-grid">
                <?php foreach($products as $product): ?>
                    <div class="product-card card">
                        <!-- Product Image with Wishlist Icon -->
                        <div class="product-image-container position-relative">
                            <a href="<?php echo BASE_URL; ?>?controller=product&action=show&id=<?php echo $product['id']; ?>" class="text-decoration-none w-100 h-100 d-flex align-items-center justify-content-center">
                                <?php if(!empty($product['image'])) : ?>
                                    <?php 
                                        $imagePath = strpos($product['image'], 'http') === 0 ? $product['image'] : BASE_URL . ltrim($product['image'], '/');
                                        if(strpos($product['image'], 'uploads/') === false && strpos($product['image'], '/') !== 0) {
                                            $imagePath = BASE_URL . 'uploads/' . ltrim($product['image'], '/');
                                        }
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg';">
                                <?php else : ?>
                                    <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         loading="lazy">
                                <?php endif; ?>
                            </a>
                            
                            <?php if(isLoggedIn() && $product['stock_quantity'] > 0) : ?>
                                <a href="<?php echo BASE_URL; ?>?controller=wishlist&action=add&id=<?php echo $product['id']; ?>" 
                                   class="btn-wishlist"
                                   title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if(!empty($product['sale_price'])) : ?>
                                <span class="position-absolute top-0 start-0 m-2 badge bg-danger">SALE</span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Product Content -->
                        <div class="card-body d-flex flex-column" style="padding: 0.875rem 1rem 1rem;">
                            <!-- Stock Badge -->
                            <?php if(isLoggedIn()): ?>
                                <?php if($product['stock_quantity'] <= 0): ?>
                                    <span class="stock-badge" style="background: #6c757d;">Out of Stock</span>
                                <?php elseif($product['stock_quantity'] <= 5): ?>
                                    <span class="stock-badge" style="background: #ffc107; color: #212529;">Only <?php echo $product['stock_quantity']; ?> left</span>
                                <?php else: ?>
                                    <span class="stock-badge">In Stock</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if($product['stock_quantity'] <= 0): ?>
                                    <span class="stock-badge" style="background: #6c757d;">Out of Stock</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <!-- Product Name -->
                            <h5 class="product-title" title="<?php echo htmlspecialchars($product['name']); ?>">
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </a>
                            </h5>
                            
                            <!-- Price and Stock/Value Info -->
                            <?php if(isLoggedIn()): ?>
                                <div class="product-price-row">
                                    <div class="product-price">
                                        <?php if(!empty($product['sale_price'])) : ?>
                                            <span class="text-danger"><?php echo formatCurrency($product['sale_price']); ?></span>
                                            <span class="text-decoration-line-through text-muted small ms-1" style="font-size: 0.875rem;"><?php echo formatCurrency($product['price']); ?></span>
                                        <?php else : ?>
                                            <?php echo formatCurrency($product['price']); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-meta">
                                        <?php if($product['stock_quantity'] > 0): ?>
                                            <div>Stock: <?php echo $product['stock_quantity']; ?> units</div>
                                            <div>Value: <?php echo formatCurrency($product['stock_quantity'] * (!empty($product['sale_price']) ? $product['sale_price'] : $product['price'])); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Quantity and Add to Cart -->
                                <?php if($product['stock_quantity'] > 0): ?>
                                    <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="add-to-cart-form">
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
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="product-price-row">
                                    <div class="product-meta text-start" style="font-size: 0.875rem;">
                                        <span class="text-muted">Login to see price</span>
                                    </div>
                                </div>
                                <?php if($product['stock_quantity'] > 0): ?>
                                    <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn-add-to-cart mt-2">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>Login to Purchase</span>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <?php if($totalPages > 1): ?>
    <div class="mt-5">
        <nav aria-label="Product pagination">
            <ul class="pagination justify-content-center pagination-lg">
                <?php if($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo BASE_URL; ?>?controller=product&action=all&page=<?php echo ($page - 1); ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo BASE_URL; ?>?controller=product&action=all&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                
                <?php if($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo BASE_URL; ?>?controller=product&action=all&page=<?php echo ($page + 1); ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
