<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">All Products</h1>
            
            <!-- Filters and Search -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form action="" method="GET" class="d-flex gap-2">
                        <select name="category" class="form-select form-select-sm">
                            <option value="">All Categories</option>
                            <?php foreach($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="sort" class="form-select form-select-sm">
                            <option value="name_asc">Name (A-Z)</option>
                            <option value="name_desc">Name (Z-A)</option>
                            <option value="price_asc">Price (Low-High)</option>
                            <option value="price_desc">Price (High-Low)</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-outline-primary">Filter</button>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <form class="d-flex gap-2">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Search products...">
                        <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                <?php foreach($products as $product): ?>
                    <div class="col mb-3">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="product-image-container position-relative">
                                <?php if(!empty($product['image'])) : ?>
                                    <img src="<?php echo BASE_URL . 'public/uploads/' . basename($product['image']); ?>" 
                                         class="card-img-top img-fluid p-2" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg';">
                                <?php else : ?>
                                    <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" 
                                         class="card-img-top img-fluid p-2" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         loading="lazy">
                                <?php endif; ?>
                                <?php if(!empty($product['sale_price'])) : ?>
                                    <span class="position-absolute top-0 end-0 m-2 badge bg-danger">SALE</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fs-6 mb-2 text-truncate" title="<?php echo htmlspecialchars($product['name']); ?>">
                                    <?php echo $product['name']; ?>
                                </h5>
                                <p class="card-text small text-muted d-none d-md-block mb-2">
                                    <?php echo truncateText($product['description'], 50); ?>
                                </p>
                                <div class="mt-auto">
                                    <?php if(isLoggedIn()): ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="product-price">
                                                <?php if(!empty($product['sale_price'])) : ?>
                                                    <span class="text-danger fw-bold"><?php echo formatCurrency($product['sale_price']); ?></span>
                                                    <span class="text-decoration-line-through text-muted small ms-1"><?php echo formatCurrency($product['price']); ?></span>
                                                <?php else : ?>
                                                    <span class="fw-bold"><?php echo formatCurrency($product['price']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if($product['stock_quantity'] <= 0): ?>
                                                <span class="badge bg-secondary">Out of Stock</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if($product['stock_quantity'] > 0): ?>
                                            <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="add-to-cart-form">
                                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                                <div class="input-group input-group-sm mb-2">
                                                    <button type="button" class="btn btn-outline-secondary quantity-decrease px-2">-</button>
                                                    <input type="number" name="quantity" class="form-control text-center quantity-input" 
                                                           value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                                           aria-label="Quantity" readonly>
                                                    <button type="button" class="btn btn-outline-secondary quantity-increase px-2">+</button>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-success w-100">
                                                    <i class="fas fa-cart-plus me-1"></i> <span class="d-none d-sm-inline">Add to Cart</span><span class="d-inline d-sm-none">Add</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="text-center py-2">
                                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="btn btn-sm btn-outline-success w-100">
                                                <i class="fas fa-sign-in-alt me-1"></i> <span class="d-none d-sm-inline">Login to Buy</span><span class="d-inline d-sm-none">Login</span>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if(isLoggedIn() && $product['stock_quantity'] > 0) : ?>
                            <div class="card-footer bg-white border-top-0 d-flex justify-content-end pt-0">
                                <a href="<?php echo BASE_URL; ?>?controller=wishlist&action=add&id=<?php echo $product['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if($totalPages > 1): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <nav aria-label="Product navigation">
                        <ul class="pagination justify-content-center">
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
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
