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
                <style>
                    .products-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                        gap: 1.5rem;
                        padding: 0 15px;
                    }
                    .product-card {
                        display: flex;
                        flex-direction: column;
                        height: 100%;
                        border: 1px solid #e9ecef;
                        border-radius: 0.5rem;
                        overflow: hidden;
                        transition: transform 0.2s, box-shadow 0.2s;
                    }
                    .product-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
                    }
                    .product-image-container {
                        width: 100%;
                        height: 200px;
                        overflow: hidden;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: #f8f9fa;
                        padding: 15px;
                    }
                    .product-image {
                        max-width: 100%;
                        max-height: 100%;
                        object-fit: contain;
                    }
                    .card-body {
                        display: flex;
                        flex-direction: column;
                        flex-grow: 1;
                        padding: 1.25rem;
                    }
                    .card-title {
                        font-size: 1rem;
                        margin-bottom: 0.75rem;
                        line-height: 1.4;
                        height: 2.8em;
                        overflow: hidden;
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                    }
                    .card-text {
                        flex-grow: 1;
                        margin-bottom: 1rem;
                        color: #6c757d;
                        font-size: 0.9rem;
                        line-height: 1.5;
                        overflow: hidden;
                        display: -webkit-box;
                        -webkit-line-clamp: 3;
                        -webkit-box-orient: vertical;
                    }
                    @media (max-width: 767.98px) {
                        .products-grid {
                            grid-template-columns: repeat(2, 1fr);
                            gap: 1rem;
                            padding: 0 10px;
                        }
                        .product-image-container {
                            height: 160px;
                        }
                    }
                    @media (max-width: 480px) {
                        .products-grid {
                            grid-template-columns: 1fr;
                        }
                    }
                </style>
                
                <div class="products-grid">
                    <?php foreach($products['data'] as $product): ?>
                        <div class="product-card">
                            <div class="product-image-container">
                                <?php if(!empty($product['image'])): ?>
                                    <img src="<?php echo BASE_URL . $product['image']; ?>" class="product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <?php else: ?>
                                    <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" class="product-image" alt="No Image">
                                <?php endif; ?>
                            </div>
                                
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                <?php if(!empty($product['description'])): ?>
                                    <p class="card-text"><?php echo nl2br(htmlspecialchars(truncateText($product['description'], 100))); ?></p>
                                <?php endif; ?>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="price-container">
                                            <span class="fw-bold text-danger"><?php echo formatCurrency($product['sale_price']); ?></span>
                                        </div>
                                        
                                        <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'secondary'; ?> ms-auto">
                                            <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                        </span>
                                    </div>
                                    
                                    <?php if($product['stock_quantity'] > 0 && isLoggedIn()): ?>
                                        <div class="text-end mb-3">
                                            <small class="text-muted d-block">Stock: <?php echo $product['stock_quantity']; ?> units</small>
                                            <small class="text-muted d-block">
                                                Value: <?php echo formatCurrency($product['stock_quantity'] * ($product['sale_price'] ?? $product['price'])); ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="d-flex gap-2">
                                        <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary flex-grow-1">View Details</a>
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
                
                <!-- Specific Products Section -->
                <?php if(!empty($specificProducts)): ?>
                    <div class="mt-5 pt-4 border-top">
                        <h3 class="mb-4">Featured Products</h3>
                        <div class="products-grid">
                            <?php foreach($specificProducts as $product): ?>
                                <div class="product-card">
                                    <div class="product-image-container">
                                        <?php if(!empty($product['image'])): ?>
                                            <img src="<?php echo BASE_URL . $product['image']; ?>" class="product-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                        <?php else: ?>
                                            <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" class="product-image" alt="No Image">
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                                        <?php if(!empty($product['description'])): ?>
                                            <p class="card-text"><?php echo nl2br(htmlspecialchars(truncateText($product['description'], 100))); ?></p>
                                        <?php endif; ?>
                                        
                                        <div class="mt-auto">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="price-container">
                                                    <?php if(!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                                        <span class="fw-bold text-danger"><?php echo formatCurrency($product['sale_price']); ?></span>
                                                        <small class="text-muted text-decoration-line-through ms-2"><?php echo formatCurrency($product['price']); ?></small>
                                                    <?php else: ?>
                                                        <span class="fw-bold"><?php echo formatCurrency($product['price']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'secondary'; ?> ms-auto">
                                                    <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                                </span>
                                            </div>
                                            
                                            <?php if($product['stock_quantity'] > 0 && isLoggedIn()): ?>
                                                <div class="text-end mb-3">
                                                    <small class="text-muted d-block">Stock: <?php echo $product['stock_quantity']; ?> units</small>
                                                    <small class="text-muted d-block">
                                                        Value: <?php echo formatCurrency($product['stock_quantity'] * ($product['sale_price'] ?? $product['price'])); ?>
                                                    </small>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="d-flex gap-2">
                                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary flex-grow-1">View Details</a>
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
                    </div>
                <?php endif; ?>
                
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
