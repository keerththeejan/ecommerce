<?php require_once APP_PATH . '/views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>?controller=country&action=index">Countries</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $country['name']; ?></li>
                </ol>
            </nav>
            
            <div class="d-flex align-items-center mb-4">
                <?php if(!empty($country['flag_image'])) : ?>
                    <img src="<?php echo rtrim(BASE_URL, '/'); ?>/uploads/flags/<?php echo htmlspecialchars($country['flag_image']); ?>"
                         alt="<?php echo htmlspecialchars($country['name']); ?>"
                         class="me-3"
                         style="height: 40px; width: auto;"
                         onerror="this.onerror=null; this.src='<?php echo rtrim(BASE_URL, '/'); ?>/images/default-brand.png';">
                <?php else : ?>
                    <i class="fas fa-globe fa-2x me-3 text-secondary"></i>
                <?php endif; ?>
                <h1 class="mb-0"><?php echo $country['name']; ?> Products</h1>
            </div>
            
            <?php if(!empty($country['description'])) : ?>
                <div class="mb-4">
                    <p><?php echo $country['description']; ?></p>
                </div>
            <?php endif; ?>
            
            <div class="row row-cols-2 row-cols-md-4 g-3">
                <?php if(!empty($products)) : ?>
                    <?php foreach($products as $product) : ?>
                        <div class="col mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if(!empty($product['image'])) : ?>
                                    <img src="<?php echo BASE_URL . $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>" style="height: 180px; object-fit: cover;">
                                <?php else : ?>
                                    <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" class="card-img-top" alt="<?php echo $product['name']; ?>" style="height: 180px; object-fit: cover;">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title fs-6"><?php echo $product['name']; ?></h5>
                                    <p class="card-text small text-muted d-none d-md-block"><?php echo truncateText($product['description'], 50); ?></p>
                                    <?php if(isLoggedIn()): ?>
                                        <?php if(!empty($product['sale_price'])) : ?>
                                            <p class="card-text">
                                                <span class="text-decoration-line-through text-muted small"><?php echo formatPrice($product['price']); ?></span>
                                                <span class="text-danger fw-bold ms-1"><?php echo formatPrice($product['sale_price']); ?></span>
                                                <span class="badge bg-danger ms-1 small"><?php echo calculateDiscountPercentage($product['price'], $product['sale_price']); ?>%</span>
                                            </p>
                                        <?php else : ?>
                                            <p class="card-text fw-bold"><?php echo formatPrice($product['price']); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="card-text"><a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="text-primary small">Login to view price</a></p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-dark">View</a>
                                    <?php if(isLoggedIn()) : ?>
                                        <a href="<?php echo BASE_URL; ?>?controller=cart&action=add&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-dark add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            No products available from <?php echo $country['name']; ?>.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/customer/layouts/footer.php'; ?>
