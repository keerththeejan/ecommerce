<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title mb-0">Delete Product</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h4 class="alert-heading">Warning!</h4>
                        <p>Are you sure you want to delete this product? This action cannot be undone.</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <?php if(!empty($product['image'])): ?>
                                <img src="<?php echo BASE_URL . $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail mb-3">
                            <?php else: ?>
                                <img src="<?php echo BASE_URL; ?>assets/img/no-image.jpg" alt="No Image" class="img-thumbnail mb-3">
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <h4><?php echo $product['name']; ?></h4>
                            <p><strong>SKU:</strong> <?php echo $product['sku']; ?></p>
                            <p><strong>Price:</strong> <?php echo formatPrice($product['price']); ?></p>
                            <?php if(!empty($product['sale_price'])): ?>
                                <p><strong>Sale Price:</strong> <?php echo formatPrice($product['sale_price']); ?></p>
                            <?php endif; ?>
                            <p><strong>Stock Quantity:</strong> <?php echo $product['stock_quantity']; ?></p>
                            <p><strong>Status:</strong> 
                                <?php if($product['status'] == 'active'): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    
                    <form action="<?php echo BASE_URL; ?>?controller=product&action=delete&id=<?php echo $product['id']; ?>" method="POST" class="mt-3">
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>?controller=product&action=adminIndex" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-danger">Delete Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>
