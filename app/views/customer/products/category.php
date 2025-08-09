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
                    <?php foreach($categories as $cat): ?>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=category&id=<?php echo $cat['id']; ?>" class="list-group-item list-group-item-action <?php echo ($cat['id'] == $category['id']) ? 'active' : ''; ?>">
                            <?php echo $cat['name']; ?>
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
                <h2 class="mb-0"><?php echo $category['name']; ?></h2>
                
                <!-- Search form -->
                <form action="<?php echo BASE_URL; ?>?controller=product&action=search" method="GET" class="d-flex">
                    <input type="hidden" name="controller" value="product">
                    <input type="hidden" name="action" value="search">
                    <input type="text" name="keyword" class="form-control me-2" placeholder="Search products...">
                    <button type="submit" class="btn btn-outline-primary">Search</button>
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
                    .fixed-height-img {
                        height: 200px;
                        width: 100%;
                        object-fit: contain;
                        padding: 15px;
                        background: #f8f9fa;
                    }
                    .card {
                        transition: transform 0.2s;
                    }
                    .card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                    }
                    .card-body {
                        display: flex;
                        flex-direction: column;
                    }
                    .card-text {
                        flex-grow: 1;
                    }
                </style>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach($products as $product): ?>
                        <div class="col">
                            <div class="card h-100">
                                <div class="d-flex justify-content-center align-items-center" style="height: 200px; overflow: hidden;">
                                    <?php if(!empty($product['image'])): ?>
                                        <img src="<?php echo BASE_URL . $product['image']; ?>" class="fixed-height-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" class="fixed-height-img" alt="No Image">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                    <p class="card-text"><?php echo truncateText($product['description'], 100); ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if(isLoggedIn()): ?>
                                            <?php if(!empty($product['sale_price']) && $product['sale_price'] < $product['price']): ?>
                                                <div>
                                                    <span class="text-decoration-line-through text-muted"><?php echo formatCurrency($product['price']); ?></span>
                                                    <span class="text-danger fw-bold"><?php echo formatCurrency($product['sale_price']); ?></span>
                                                </div>
                                                <span class="badge bg-danger">
                                                    <?php echo calculateDiscountPercentage($product['price'], $product['sale_price']); ?>% OFF
                                                </span>
                                            <?php else: ?>
                                                <span class="fw-bold"><?php echo formatCurrency($product['price']); ?></span>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="text-primary">Login to view price</a>
                                        <?php endif; ?>
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
