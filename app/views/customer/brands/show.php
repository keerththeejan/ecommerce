<?php
// Check if brand data exists
if (empty($brand)) {
    echo '<div class="alert alert-danger">Brand not found.</div>';
    return;
}
?>

<div class="container py-5">
    <div class="row">
        <!-- Brand Info -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="row g-0">
                    <div class="col-md-3 text-center p-4">
                        <?php if (!empty($brand['logo'])): ?>
                            <img src="<?php echo BASE_URL . 'public/uploads/brands/' . htmlspecialchars($brand['logo']); ?>" 
                                 alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                 class="img-fluid rounded" 
                                 style="max-height: 200px; width: auto;">
                        <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                 style="width: 200px; height: 200px; margin: 0 auto;">
                                <i class="fas fa-building fa-4x text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <div class="card-body">
                            <h1 class="card-title"><?php echo htmlspecialchars($brand['name']); ?></h1>
                            <?php if (!empty($brand['description'])): ?>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($brand['description'])); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-12">
            <h3 class="mb-4">Products by <?php echo htmlspecialchars($brand['name']); ?></h3>
            
            <?php if (!empty($products['data'])): ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                    <?php foreach ($products['data'] as $product): ?>
                        <div class="col">
                            <div class="card h-100">
                                <?php 
                                $productImage = !empty($product['image']) 
                                    ? BASE_URL . 'public/uploads/products/' . $product['image'] 
                                    : BASE_URL . 'public/images/default-product.png';
                                ?>
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>">
                                    <img src="<?php echo $productImage; ?>" 
                                         class="card-img-top" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>"
                                         style="height: 200px; object-fit: contain; background: #f8f9fa;">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" 
                                           class="text-decoration-none text-dark">
                                            <?php echo htmlspecialchars($product['name']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small">
                                        <?php echo !empty($product['short_description']) 
                                            ? htmlspecialchars($product['short_description']) 
                                            : 'No description available'; ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0">$<?php echo number_format($product['price'], 2); ?></span>
                                        <a href="<?php echo BASE_URL; ?>?controller=cart&action=add&id=<?php echo $product['id']; ?>" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($products['total_pages'] > 1): ?>
                    <nav class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php if ($products['current_page'] > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" 
                                       href="<?php echo BASE_URL; ?>?controller=brand&action=show&param=<?php echo $brand['slug']; ?>&page=<?php echo $products['current_page'] - 1; ?>">
                                        Previous
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $products['total_pages']; $i++): ?>
                                <li class="page-item <?php echo $i == $products['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" 
                                       href="<?php echo BASE_URL; ?>?controller=brand&action=show&param=<?php echo $brand['slug']; ?>&page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($products['current_page'] < $products['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" 
                                       href="<?php echo BASE_URL; ?>?controller=brand&action=show&param=<?php echo $brand['slug']; ?>&page=<?php echo $products['current_page'] + 1; ?>">
                                        Next
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">No products found for this brand.</div>
            <?php endif; ?>
        </div>
    </div>
</div>