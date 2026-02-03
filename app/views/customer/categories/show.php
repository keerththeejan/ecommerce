<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page"><a href="<?php echo BASE_URL; ?>?controller=category&action=index" class="text-decoration-none">Categories</a></li>
        </ol>
    </nav>

    <!-- Subcategories -->
    <?php if(!empty($subcategories)) : ?>
        <div class="row mb-5">
            <div class="col-md-12">
                <h2 class="mb-4">Subcategories</h2>
                <div class="row row-cols-1 row-cols-md-4 g-4">
                    <?php foreach($subcategories as $subcategory) : ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if(!empty($subcategory['image'])) : ?>
                                    <img src="<?php echo BASE_URL . $subcategory['image']; ?>" class="card-img-top" alt="<?php echo $subcategory['name']; ?>">
                                <?php else : ?>
                                    <div class="bg-light p-4 text-center">
                                        <i class="fas fa-tags fa-4x text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo $subcategory['name']; ?></h5>
                                    <a href="<?php echo BASE_URL; ?>?controller=category&action=show&param=<?php echo $subcategory['id']; ?>" class="btn btn-outline-dark btn-sm">View Category</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Products in this category (and subcategories); filtered by category only -->
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Products</h2>
            
            <?php 
            // $products passed from CategoryController::show (filtered by this category + subcategories)
            if(!empty($products)) : 
            ?>
                <div class="row row-cols-1 row-cols-md-4 g-4">
                    <?php foreach($products as $product) : ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if(!empty($product['image'])) : ?>
                                    <img src="<?php echo BASE_URL . $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                                <?php else : ?>
                                    <img src="<?php echo BASE_URL; ?>assets/images/product-placeholder.jpg" class="card-img-top" alt="<?php echo $product['name']; ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                    <p class="card-text small text-muted"><?php echo truncateText($product['description'], 50); ?></p>
                                    <?php if(isLoggedIn()): ?>
                                        <?php if(!empty($product['sale_price'])) : ?>
                                            <p class="card-text">
                                                <span class="text-decoration-line-through text-muted"><?php echo formatPrice($product['price']); ?></span>
                                                <span class="text-danger fw-bold ms-2"><?php echo formatPrice($product['sale_price']); ?></span>
                                                <span class="badge bg-danger ms-2"><?php echo calculateDiscountPercentage($product['price'], $product['sale_price']); ?>% OFF</span>
                                            </p>
                                        <?php else : ?>
                                            <p class="card-text fw-bold"><?php echo formatPrice($product['price']); ?></p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="card-text"><a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="text-primary">Login to view price</a></p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-dark">View Details</a>
                                    <?php if(isLoggedIn()) : ?>
                                        <a href="<?php echo BASE_URL; ?>?controller=cart&action=add&param=<?php echo $product['id']; ?>" class="btn btn-sm btn-dark add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                            <i class="fas fa-shopping-cart me-1"></i> Add
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="alert alert-info">
                    <p class="mb-0">No products available in this category.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
