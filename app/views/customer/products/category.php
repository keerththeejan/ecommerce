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
            <div class="d-flex justify-content-end align-items-center mb-4">
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
                    .product-image-container {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        background: #f8f9fa;
                        padding: 10px;
                    }
                    .fixed-height-img {
                        max-width: 100%;
                        max-height: 100%;
                        object-fit: contain;
                    }
                    .card-body {
                        display: flex;
                        flex-direction: column;
                        padding: 0.75rem;
                    }
                    .card-title {
                        font-size: 0.9rem;
                        margin-bottom: 0.4rem;
                        line-height: 1.3;
                        height: 2.2em;
                        overflow: hidden;
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                    }
                    .card-text {
                        flex-grow: 1;
                        margin-bottom: 0.5rem;
                        color: #6c757d;
                        font-size: 0.8rem;
                        line-height: 1.3;
                        overflow: hidden;
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                    }
                </style>
                <style>
                    .products-grid {
                        display: grid;
                        grid-template-columns: repeat(4, 1fr);
                        gap: 1.5rem;
                        padding: 0 15px;
                    }
                    @media (max-width: 1200px) {
                        .products-grid {
                            grid-template-columns: repeat(3, 1fr);
                        }
                    }
                    @media (max-width: 992px) {
                        .products-grid {
                            grid-template-columns: repeat(2, 1fr);
                        }
                    }
                    @media (max-width: 768px) {
                        .products-grid {
                            grid-template-columns: repeat(2, 1fr);
                            gap: 0.75rem;
                            padding: 0 10px;
                        }
                    }
                    @media (max-width: 480px) {
                        .products-grid {
                            grid-template-columns: 1fr;
                            gap: 1rem;
                        }
                    }
                    .product-card {
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
                </style>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                        <div class="col">
                            <div class="product-card card h-100">
                                <div class="product-image-container" style="height: 160px;">
                                    <?php if(!empty($product['image'])): ?>
                                        <img src="<?php echo BASE_URL . $product['image']; ?>" class="fixed-height-img" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" class="fixed-height-img" alt="No Image">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                    <p class="card-text"><?php echo truncateText($product['description'], 100); ?></p>
                                    
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
