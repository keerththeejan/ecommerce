<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<!-- Wishlist Section -->
<section class="wishlist-section py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">My Wishlist</h1>
            <a href="<?php echo BASE_URL; ?>?controller=home" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i> Continue Shopping
            </a>
        </div>
        
        <?php 
        // Display flash messages if any
        if (isset($_SESSION['flash_messages'])) {
            foreach ($_SESSION['flash_messages'] as $message) {
                echo '<div class="alert alert-' . $message['type'] . ' alert-dismissible fade show" role="alert">';
                echo htmlspecialchars($message['message']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
            }
            // Clear the flash messages
            unset($_SESSION['flash_messages']);
        }
        ?>
        
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <?php if (!empty($wishlistItems)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 100px;">Image</th>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Stock Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($wishlistItems as $item): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $item['id']; ?>">
                                                <?php if (!empty($item['image'])): ?>
                                                    <img src="<?php echo BASE_URL . 'public/uploads/products/' . basename($item['image']); ?>" 
                                                         alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                                         class="img-fluid" 
                                                         style="max-width: 80px; height: auto;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </a>
                                        </td>
                                        <td>
                                            <h6 class="mb-1">
                                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $item['id']; ?>" class="text-dark">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </a>
                                            </h6>
                                            <p class="text-muted small mb-0">SKU: <?php echo !empty($item['sku']) ? htmlspecialchars($item['sku']) : 'N/A'; ?></p>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold">CHF <?php echo number_format($item['price'], 2); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($item['stock_quantity'] > 0): ?>
                                                <span class="badge bg-success">In Stock</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Out of Stock</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <?php if ($item['stock_quantity'] > 0): ?>
                                                    <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="d-inline">
                                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                                        <input type="hidden" name="quantity" value="1">
                                                        <button type="submit" class="btn btn-sm btn-outline-primary" title="Add to Cart">
                                                            <i class="fas fa-shopping-cart"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <a href="<?php echo BASE_URL; ?>?controller=wishlist&action=remove&id=<?php echo $item['id']; ?>" 
                                                   class="btn btn-sm btn-outline-danger" 
                                                   title="Remove from Wishlist"
                                                   onclick="return confirm('Are you sure you want to remove this item from your wishlist?')">
                                                    <i class="far fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="far fa-heart text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                        </div>
                        <h4 class="mb-3">Your wishlist is empty</h4>
                        <p class="text-muted mb-4">You haven't added any products to your wishlist yet.</p>
                        <a href="<?php echo BASE_URL; ?>?controller=shop" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.wishlist-section {
    background-color: #f8f9fa;
    min-height: 60vh;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    line-height: 1.5;
    border-radius: 0.2rem;
}

.badge {
    font-weight: 500;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
}

/* Responsive styles */
@media (max-width: 767.98px) {
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .table thead {
        display: none;
    }
    
    .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
    }
    
    .table tr {
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        position: relative;
        padding-top: 2.5rem;
    }
    
    .table td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        border-bottom: 1px solid #dee2e6;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }
    
    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 1rem;
        width: 45%;
        text-align: left;
        font-weight: bold;
    }
    
    .table td:last-child {
        border-bottom: 0;
    }
    
    /* Reset specific cells */
    .table td.text-center {
        text-align: right;
        padding-left: 50%;
    }
    
    .table td[data-label]::before {
        content: attr(data-label);
    }
    
    /* Special handling for the first cell (image) */
    .table td:first-child {
        position: absolute;
        top: 0;
        left: 0;
        width: 80px;
        padding: 0.5rem;
        text-align: center;
    }
    
    .table td:first-child::before {
        display: none;
    }
    
    .table td:nth-child(2) {
        padding-top: 1rem;
        padding-left: calc(80px + 1rem);
    }
    
    .table td:nth-child(2)::before {
        display: none;
    }
}
</style>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
