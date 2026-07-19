<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<?php
$wishlistItems = isset($wishlistItems) && is_array($wishlistItems) ? $wishlistItems : [];
$wishlistTotal = isset($wishlistTotal) ? (int)$wishlistTotal : count($wishlistItems);
$wishlistPage = isset($wishlistPage) ? (int)$wishlistPage : 1;
$wishlistLastPage = isset($wishlistLastPage) ? (int)$wishlistLastPage : 1;
$wishlistQuery = isset($wishlistQuery) ? (string)$wishlistQuery : '';
$wishlistSort = isset($wishlistSort) ? (string)$wishlistSort : 'newest';
$baseWishlistUrl = rtrim(BASE_URL, '/') . '/?controller=wishlist';
?>

<section class="wishlist-page py-4 py-lg-5">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">My Wishlist</h1>
                <p class="text-muted mb-0 small" id="wishlistItemCountLabel">
                    <span id="wishlistItemCount"><?php echo (int)$wishlistTotal; ?></span>
                    item<?php echo $wishlistTotal === 1 ? '' : 's'; ?> saved
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <?php if ($wishlistTotal > 0): ?>
                    <button type="button" class="btn btn-outline-danger" id="wishlistClearBtn">
                        <i class="fas fa-trash me-1"></i>Clear All
                    </button>
                <?php endif; ?>
                <a href="<?php echo rtrim(BASE_URL, '/') . '/?controller=shop'; ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        </div>

        <?php flash('login_required'); ?>
        <?php flash('wishlist_success'); ?>
        <?php flash('wishlist_removed'); ?>
        <?php flash('wishlist_error'); ?>

        <form method="GET" action="<?php echo htmlspecialchars($baseWishlistUrl, ENT_QUOTES, 'UTF-8'); ?>" class="wishlist-toolbar card border-0 shadow-sm mb-4">
            <input type="hidden" name="controller" value="wishlist">
            <input type="hidden" name="action" value="index">
            <div class="card-body d-flex flex-wrap gap-2 align-items-center">
                <div class="flex-grow-1" style="min-width: 200px;">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent"><i class="fas fa-search text-muted"></i></span>
                        <input type="search" name="q" class="form-control border-start-0" placeholder="Search wishlist..."
                               value="<?php echo htmlspecialchars($wishlistQuery, ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>
                <select name="sort" class="form-select" style="max-width: 220px;" onchange="this.form.submit()">
                    <option value="newest" <?php echo $wishlistSort === 'newest' ? 'selected' : ''; ?>>Newest first</option>
                    <option value="oldest" <?php echo $wishlistSort === 'oldest' ? 'selected' : ''; ?>>Oldest first</option>
                    <option value="name_asc" <?php echo $wishlistSort === 'name_asc' ? 'selected' : ''; ?>>Name (A–Z)</option>
                    <option value="name_desc" <?php echo $wishlistSort === 'name_desc' ? 'selected' : ''; ?>>Name (Z–A)</option>
                    <option value="price_asc" <?php echo $wishlistSort === 'price_asc' ? 'selected' : ''; ?>>Price (Low–High)</option>
                    <option value="price_desc" <?php echo $wishlistSort === 'price_desc' ? 'selected' : ''; ?>>Price (High–Low)</option>
                </select>
                <button type="submit" class="btn btn-primary">Apply</button>
            </div>
        </form>

        <?php if (empty($wishlistItems)): ?>
            <div class="wishlist-empty text-center py-5 px-3" id="wishlistEmptyState">
                <div class="wishlist-empty-icon mb-3">
                    <i class="far fa-heart"></i>
                </div>
                <h2 class="h4 mb-2">Your wishlist is empty</h2>
                <p class="text-muted mb-4">Tap the heart on any product to save it here for later.</p>
                <a href="<?php echo rtrim(BASE_URL, '/') . '/?controller=shop'; ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="wishlist-grid" id="wishlistGrid">
                <?php foreach ($wishlistItems as $item):
                    $item = is_object($item) ? (array)$item : $item;
                    $pid = (int)($item['product_id'] ?? $item['id'] ?? 0);
                    if ($pid <= 0) {
                        continue;
                    }
                    $name = (string)($item['name'] ?? 'Product');
                    $stock = (float)($item['stock_quantity'] ?? 0);
                    $price = !empty($item['price2'])
                        ? (float)$item['price2']
                        : (!empty($item['sale_price']) ? (float)$item['sale_price'] : (float)($item['price'] ?? 0));
                    $inStock = $stock > 0 && (($item['status'] ?? 'active') === 'active');
                    $addedDate = !empty($item['added_date']) ? date('M j, Y', strtotime($item['added_date'])) : '';
                ?>
                    <article class="wishlist-card" data-wishlist-item="<?php echo $pid; ?>" data-product-id="<?php echo $pid; ?>">
                        <div class="wishlist-card-media">
                            <a href="<?php echo rtrim(BASE_URL, '/') . '/?controller=product&action=show&param=' . $pid; ?>">
                                <img <?php echo product_img_attrs($item['image'] ?? null, $name, 'wishlist-card-img product-image'); ?>>
                            </a>
                            <button type="button"
                                    class="btn-wishlist wishlist-heart active wishlist-card-remove"
                                    data-product-id="<?php echo $pid; ?>"
                                    data-wishlist-remove="1"
                                    aria-label="Remove from wishlist"
                                    title="Remove">
                                <i class="fas fa-heart" aria-hidden="true"></i>
                            </button>
                        </div>
                        <div class="wishlist-card-body">
                            <h3 class="wishlist-card-title">
                                <a href="<?php echo rtrim(BASE_URL, '/') . '/?controller=product&action=show&param=' . $pid; ?>">
                                    <?php echo htmlspecialchars($name); ?>
                                </a>
                            </h3>
                            <div class="wishlist-card-meta">
                                <span class="wishlist-card-price"><?php echo formatCurrency($price); ?></span>
                                <span class="badge <?php echo $inStock ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo $inStock ? ('In Stock · ' . (int)$stock) : 'Out of Stock'; ?>
                                </span>
                            </div>
                            <?php if ($addedDate !== ''): ?>
                                <div class="wishlist-card-date text-muted small">
                                    <i class="far fa-clock me-1"></i>Added <?php echo htmlspecialchars($addedDate); ?>
                                </div>
                            <?php endif; ?>
                            <div class="wishlist-card-actions">
                                <?php if ($inStock): ?>
                                    <button type="button"
                                            class="btn btn-success w-100 btn-sm wishlist-move-cart-btn flex-grow-1"
                                            data-product-id="<?php echo $pid; ?>">
                                        <i class="fas fa-cart-plus me-1"></i>Move to Cart
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="btn btn-secondary w-100 btn-sm" disabled>Unavailable</button>
                                <?php endif; ?>
                                <button type="button"
                                        class="btn btn-outline-danger btn-sm wishlist-remove-btn"
                                        data-product-id="<?php echo $pid; ?>"
                                        aria-label="Remove">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($wishlistLastPage > 1): ?>
                <nav class="mt-4" aria-label="Wishlist pagination">
                    <ul class="pagination justify-content-center flex-wrap">
                        <?php
                        $qs = http_build_query(array_filter([
                            'controller' => 'wishlist',
                            'q' => $wishlistQuery !== '' ? $wishlistQuery : null,
                            'sort' => $wishlistSort !== 'newest' ? $wishlistSort : null
                        ]));
                        for ($i = 1; $i <= $wishlistLastPage; $i++):
                            $href = rtrim(BASE_URL, '/') . '/?' . $qs . '&page=' . $i;
                        ?>
                            <li class="page-item <?php echo $i === $wishlistPage ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
