<?php
/**
 * Premium Product Details — frontend only
 * Variables from controller: $product, $relatedProducts
 */
$product = is_array($product ?? null) ? $product : (array)($product ?? []);
$relatedProducts = is_array($relatedProducts ?? null) ? $relatedProducts : [];

$productId = (int)($product['id'] ?? 0);
$productName = (string)($product['name'] ?? 'Product');
$productDesc = (string)($product['description'] ?? '');
$stock = (int)($product['stock_quantity'] ?? 0);
$sku = (string)($product['sku'] ?? '');
$categoryId = (int)($product['category_id'] ?? 0);
$categoryName = (string)($product['category_name'] ?? '');
$countryId = (int)($product['country_id'] ?? 0);
$countryName = (string)($product['country_name'] ?? '');
$brandName = (string)($product['brand_name'] ?? ($product['brand'] ?? ''));
$barcode = (string)($product['barcode'] ?? '');
$weight = (string)($product['weight'] ?? '');

$basePrice = isset($product['price']) ? (float)$product['price'] : 0;
$salePrice = !empty($product['sale_price']) ? (float)$product['sale_price'] : null;
$price2 = !empty($product['price2']) ? (float)$product['price2'] : null;

if (isLoggedIn()) {
    $currentPrice = $price2 !== null ? $price2 : ($salePrice !== null ? $salePrice : $basePrice);
} else {
    $currentPrice = null;
}

$hasDiscount = isLoggedIn() && $salePrice !== null && $basePrice > $salePrice && $price2 === null;
$discountPct = $hasDiscount ? (int)round((($basePrice - $salePrice) / $basePrice) * 100) : 0;
$saveAmount = $hasDiscount ? ($basePrice - $salePrice) : 0;

$mainImageUrl = product_image_url(!empty($product['image']) ? $product['image'] : null);
$gallery = [];
if (!empty($product['image'])) {
    $gallery[] = $product['image'];
}
foreach (['image2', 'image3', 'image4', 'gallery'] as $gKey) {
    if (empty($product[$gKey])) {
        continue;
    }
    if (is_array($product[$gKey])) {
        foreach ($product[$gKey] as $img) {
            if (!empty($img) && !in_array($img, $gallery, true)) {
                $gallery[] = $img;
            }
        }
    } elseif (!in_array($product[$gKey], $gallery, true)) {
        $gallery[] = $product[$gKey];
    }
}
if (empty($gallery)) {
    $gallery[] = null;
}

$stockState = $stock <= 0 ? 'out' : ($stock <= 5 ? 'low' : 'in');
$stockLabel = $stock <= 0 ? 'Out of Stock' : ($stock <= 5 ? 'Low Stock' : 'In Stock');
$stockPct = $stock <= 0 ? 0 : min(100, max(8, (int)round(($stock / max($stock, 20)) * 100)));

$pageUrl = rtrim(BASE_URL, '/') . '/?controller=product&action=show&param=' . $productId;
$inWishlist = $productId > 0 && function_exists('is_in_wishlist') && is_in_wishlist($productId);

/* SEO vars consumed by header.php (frontend only) */
$pageTitle = $productName . ' | Sivakamy';
$pageDescription = truncateText(strip_tags($productDesc), 160);
$ogType = 'product';
$ogImage = $mainImageUrl;
$ogUrl = $pageUrl;
$twitterCard = 'summary_large_image';
$productSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Product',
    'name' => $productName,
    'description' => truncateText(strip_tags($productDesc), 300),
    'image' => $mainImageUrl,
    'sku' => $sku !== '' ? $sku : null,
    'offers' => array_filter([
        '@type' => 'Offer',
        'url' => $pageUrl,
        'priceCurrency' => defined('CURRENCY_CODE') ? CURRENCY_CODE : 'CHF',
        'price' => isLoggedIn() && $currentPrice !== null ? number_format((float)$currentPrice, 2, '.', '') : null,
        'availability' => $stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
    ]),
];
if ($brandName !== '') {
    $productSchema['brand'] = ['@type' => 'Brand', 'name' => $brandName];
}

require_once APP_PATH . 'views/customer/layouts/header.php';
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/product-details.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">

<section class="pdp" data-product-id="<?php echo $productId; ?>" data-stock="<?php echo $stock; ?>">
    <div class="container pdp-container py-3 py-lg-4">
        <!-- Breadcrumb -->
        <nav class="pdp-breadcrumb" aria-label="Breadcrumb">
            <ol>
                <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                <li><a href="<?php echo BASE_URL; ?>?controller=product&action=index">Products</a></li>
                <?php if ($categoryId > 0): ?>
                    <li><a href="<?php echo BASE_URL; ?>?controller=product&action=category&param=<?php echo $categoryId; ?>"><?php echo htmlspecialchars($categoryName); ?></a></li>
                <?php endif; ?>
                <li aria-current="page"><?php echo htmlspecialchars(truncateText($productName, 40)); ?></li>
            </ol>
        </nav>

        <div class="pdp-grid">
            <!-- LEFT: Gallery -->
            <div class="pdp-gallery">
                <div class="pdp-stage" id="pdpStage">
                    <button type="button" class="pdp-fullscreen-btn" id="pdpFullscreenBtn" aria-label="Open fullscreen preview">
                        <i class="bi bi-arrows-fullscreen" aria-hidden="true"></i>
                    </button>
                    <div class="pdp-zoom-wrap" id="pdpZoomWrap">
                        <img
                            id="pdpMainImage"
                            class="pdp-main-image"
                            src="<?php echo htmlspecialchars(product_image_url($gallery[0])); ?>"
                            alt="<?php echo htmlspecialchars($productName); ?>"
                            data-zoom-src="<?php echo htmlspecialchars(product_image_url($gallery[0])); ?>"
                        >
                        <div class="pdp-lens" id="pdpLens" hidden></div>
                    </div>
                    <div class="pdp-zoom-result" id="pdpZoomResult" hidden aria-hidden="true"></div>
                </div>

                <?php if (count($gallery) > 0): ?>
                <div class="pdp-thumbs" role="listbox" aria-label="Product images">
                    <?php foreach ($gallery as $idx => $img): ?>
                        <button
                            type="button"
                            class="pdp-thumb<?php echo $idx === 0 ? ' is-active' : ''; ?>"
                            role="option"
                            aria-selected="<?php echo $idx === 0 ? 'true' : 'false'; ?>"
                            data-full="<?php echo htmlspecialchars(product_image_url($img)); ?>"
                            aria-label="View image <?php echo $idx + 1; ?>"
                        >
                            <img src="<?php echo htmlspecialchars(product_image_url($img)); ?>" alt="" loading="lazy">
                        </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- RIGHT: Info (sticky) -->
            <div class="pdp-info">
                <div class="pdp-info-sticky">
                    <?php if ($categoryName !== ''): ?>
                        <a class="pdp-eyebrow" href="<?php echo BASE_URL; ?>?controller=product&action=category&param=<?php echo $categoryId; ?>">
                            <?php echo htmlspecialchars($categoryName); ?>
                        </a>
                    <?php endif; ?>

                    <h1 class="pdp-title"><?php echo htmlspecialchars($productName); ?></h1>

                    <div class="pdp-meta-row">
                        <?php if ($brandName !== ''): ?>
                            <span class="pdp-meta-chip"><i class="bi bi-award" aria-hidden="true"></i> <?php echo htmlspecialchars($brandName); ?></span>
                        <?php endif; ?>
                        <?php if ($sku !== ''): ?>
                            <span class="pdp-meta-chip">SKU: <?php echo htmlspecialchars($sku); ?></span>
                        <?php endif; ?>
                        <a href="#pdpReviews" class="pdp-rating" aria-label="Jump to reviews">
                            <span class="pdp-stars" aria-hidden="true">★★★★★</span>
                            <span class="pdp-rating-count">Reviews</span>
                        </a>
                    </div>

                    <div class="pdp-stock pdp-stock--<?php echo $stockState; ?>">
                        <span class="pdp-stock-dot" aria-hidden="true"></span>
                        <span class="pdp-stock-label"><?php echo $stockLabel; ?></span>
                        <?php if ($stock > 0): ?>
                            <span class="pdp-stock-qty"><?php echo $stock; ?> available</span>
                        <?php endif; ?>
                    </div>
                    <?php if ($stock > 0 && $stock <= 5): ?>
                        <div class="pdp-stock-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?php echo $stockPct; ?>" aria-label="Stock remaining">
                            <span style="width: <?php echo $stockPct; ?>%"></span>
                        </div>
                        <p class="pdp-stock-urgent">Only <?php echo $stock; ?> left — order soon</p>
                    <?php endif; ?>

                    <div class="pdp-price-block">
                        <?php if (isLoggedIn()): ?>
                            <div class="pdp-price-row">
                                <span class="pdp-price"><?php echo formatCurrency($currentPrice); ?></span>
                                <?php if ($hasDiscount): ?>
                                    <span class="pdp-price-old"><?php echo formatCurrency($basePrice); ?></span>
                                    <span class="pdp-badge-sale">-<?php echo $discountPct; ?>%</span>
                                <?php endif; ?>
                            </div>
                            <?php if ($hasDiscount): ?>
                                <p class="pdp-save">You save <?php echo formatCurrency($saveAmount); ?></p>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="pdp-login-price">
                                <i class="bi bi-lock-fill" aria-hidden="true"></i> Login to view price
                            </a>
                        <?php endif; ?>
                    </div>

                    <ul class="pdp-perks">
                        <li><i class="bi bi-truck" aria-hidden="true"></i> Fast delivery on eligible orders</li>
                        <li><i class="bi bi-arrow-repeat" aria-hidden="true"></i> Easy returns within policy window</li>
                        <li><i class="bi bi-shield-check" aria-hidden="true"></i> Secure checkout &amp; quality guarantee</li>
                    </ul>

                    <?php if ($stock > 0): ?>
                        <?php if (!isLoggedIn()): ?>
                            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="pdp-btn pdp-btn-primary pdp-btn-block">
                                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                                Login to purchase
                            </a>
                        <?php else: ?>
                            <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="add-to-cart-form pdp-purchase" id="pdpAddToCartForm">
                                <input type="hidden" name="product_id" value="<?php echo $productId; ?>">

                                <label class="pdp-qty-label" for="pdpQuantity">Quantity</label>
                                <div class="pdp-qty quantity-group" role="group" aria-label="Quantity selector">
                                    <button type="button" class="btn quantity-decrease" aria-label="Decrease quantity">−</button>
                                    <input
                                        type="number"
                                        id="pdpQuantity"
                                        name="quantity"
                                        class="quantity-input"
                                        value="1"
                                        min="1"
                                        max="<?php echo $stock; ?>"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        aria-label="Quantity"
                                    >
                                    <button type="button" class="btn quantity-increase" aria-label="Increase quantity">+</button>
                                </div>

                                <div class="pdp-actions">
                                    <button type="submit" class="pdp-btn pdp-btn-primary btn-add-to-cart" id="pdpAddBtn">
                                        <i class="bi bi-cart-plus-fill" aria-hidden="true"></i>
                                        <span>Add to Cart</span>
                                    </button>
                                    <button type="button" class="pdp-btn pdp-btn-buy" id="pdpBuyNowBtn">
                                        <i class="bi bi-lightning-charge-fill" aria-hidden="true"></i>
                                        <span>Buy Now</span>
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <button type="button" class="pdp-btn pdp-btn-muted pdp-btn-block" disabled>
                            <i class="bi bi-x-circle" aria-hidden="true"></i> Out of Stock
                        </button>
                    <?php endif; ?>

                    <div class="pdp-secondary-actions">
                        <button
                            type="button"
                            class="pdp-icon-btn btn-wishlist-pdp <?php echo $inWishlist ? 'active' : ''; ?>"
                            data-product-id="<?php echo $productId; ?>"
                            aria-pressed="<?php echo $inWishlist ? 'true' : 'false'; ?>"
                            aria-label="<?php echo $inWishlist ? 'Remove from wishlist' : 'Add to wishlist'; ?>"
                        >
                            <i class="<?php echo $inWishlist ? 'fas' : 'far'; ?> fa-heart" aria-hidden="true"></i>
                            <span class="wishlist-pdp-label"><?php echo $inWishlist ? 'Wishlisted' : 'Wishlist'; ?></span>
                        </button>
                        <button type="button" class="pdp-icon-btn" id="pdpShareBtn" aria-label="Share product">
                            <i class="bi bi-share" aria-hidden="true"></i>
                            <span>Share</span>
                        </button>
                        <button type="button" class="pdp-icon-btn" id="pdpCompareBtn" aria-label="Compare product" data-product-id="<?php echo $productId; ?>">
                            <i class="bi bi-arrow-left-right" aria-hidden="true"></i>
                            <span>Compare</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details + Accordion -->
        <div class="pdp-below">
            <div class="pdp-details-card">
                <h2 class="pdp-section-title">Product details</h2>
                <table class="pdp-table">
                    <tbody>
                        <?php if ($sku !== ''): ?>
                        <tr><th scope="row">SKU</th><td><?php echo htmlspecialchars($sku); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($barcode !== ''): ?>
                        <tr><th scope="row">Barcode</th><td><?php echo htmlspecialchars($barcode); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($brandName !== ''): ?>
                        <tr><th scope="row">Brand</th><td><?php echo htmlspecialchars($brandName); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($categoryName !== ''): ?>
                        <tr>
                            <th scope="row">Category</th>
                            <td>
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=category&param=<?php echo $categoryId; ?>">
                                    <?php echo htmlspecialchars($categoryName); ?>
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <?php if ($weight !== ''): ?>
                        <tr><th scope="row">Weight</th><td><?php echo htmlspecialchars($weight); ?></td></tr>
                        <?php endif; ?>
                        <?php if ($countryName !== ''): ?>
                        <tr>
                            <th scope="row">Country</th>
                            <td>
                                <?php if ($countryId > 0): ?>
                                    <a href="<?php echo BASE_URL; ?>?controller=country&action=show&id=<?php echo $countryId; ?>">
                                        <?php echo htmlspecialchars($countryName); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo htmlspecialchars($countryName); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th scope="row">Availability</th>
                            <td>
                                <span class="pdp-stock pdp-stock--inline pdp-stock--<?php echo $stockState; ?>">
                                    <span class="pdp-stock-dot" aria-hidden="true"></span>
                                    <?php echo $stockLabel; ?>
                                    <?php if ($stock > 0): ?> (<?php echo $stock; ?>)<?php endif; ?>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pdp-accordion accordion" id="pdpAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="pdpDescHeading">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#pdpDesc" aria-expanded="true" aria-controls="pdpDesc">
                            Description
                        </button>
                    </h2>
                    <div id="pdpDesc" class="accordion-collapse collapse show" aria-labelledby="pdpDescHeading" data-bs-parent="#pdpAccordion">
                        <div class="accordion-body">
                            <?php if (trim(strip_tags($productDesc)) !== ''): ?>
                                <?php echo nl2br(htmlspecialchars($productDesc)); ?>
                            <?php else: ?>
                                <p class="text-muted mb-0">No description available for this product.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="pdpShipHeading">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pdpShip" aria-expanded="false" aria-controls="pdpShip">
                            Shipping
                        </button>
                    </h2>
                    <div id="pdpShip" class="accordion-collapse collapse" aria-labelledby="pdpShipHeading" data-bs-parent="#pdpAccordion">
                        <div class="accordion-body">
                            Orders are processed promptly. Delivery times vary by location and carrier. You will receive tracking once your order ships.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="pdpReturnHeading">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pdpReturn" aria-expanded="false" aria-controls="pdpReturn">
                            Returns
                        </button>
                    </h2>
                    <div id="pdpReturn" class="accordion-collapse collapse" aria-labelledby="pdpReturnHeading" data-bs-parent="#pdpAccordion">
                        <div class="accordion-body">
                            Eligible items may be returned according to our store return policy. Please keep packaging and proof of purchase.
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="pdpReviewsHeading">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pdpReviews" aria-expanded="false" aria-controls="pdpReviews">
                            Reviews
                        </button>
                    </h2>
                    <div id="pdpReviews" class="accordion-collapse collapse" aria-labelledby="pdpReviewsHeading" data-bs-parent="#pdpAccordion">
                        <div class="accordion-body">
                            <div class="pdp-reviews-summary">
                                <div class="pdp-reviews-score">
                                    <span class="pdp-stars" aria-hidden="true">★★★★★</span>
                                    <span>Share your experience with this product</span>
                                </div>
                                <button type="button" class="pdp-btn pdp-btn-outline" disabled title="Coming soon">
                                    Write a review
                                </button>
                            </div>
                            <p class="text-muted small mb-0 mt-3">Verified purchase reviews will appear here when available.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related products -->
        <?php if (!empty($relatedProducts)): ?>
        <section class="pdp-related" aria-labelledby="pdpRelatedHeading">
            <div class="pdp-related-head">
                <h2 id="pdpRelatedHeading" class="pdp-section-title mb-0">Related products</h2>
                <?php if ($categoryId > 0): ?>
                    <a class="pdp-related-link" href="<?php echo BASE_URL; ?>?controller=product&action=category&param=<?php echo $categoryId; ?>">View category</a>
                <?php endif; ?>
            </div>
            <div class="pdp-related-track" id="pdpRelatedTrack">
                <?php foreach ($relatedProducts as $related): ?>
                    <?php
                        $related = is_array($related) ? $related : (array)$related;
                        $rid = (int)($related['id'] ?? 0);
                        if ($rid <= 0) {
                            continue;
                        }
                        $rName = (string)($related['name'] ?? 'Product');
                        $rStock = (int)($related['stock_quantity'] ?? 0);
                    ?>
                    <article class="pdp-related-card">
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $rid; ?>" class="pdp-related-media">
                            <img <?php echo product_img_attrs($related['image'] ?? null, $rName, 'pdp-related-img'); ?>>
                        </a>
                        <div class="pdp-related-body">
                            <h3 class="pdp-related-title">
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $rid; ?>">
                                    <?php echo htmlspecialchars(truncateText($rName, 48)); ?>
                                </a>
                            </h3>
                            <div class="pdp-related-footer">
                                <?php if (isLoggedIn()): ?>
                                    <span class="pdp-related-price">
                                        <?php
                                        echo formatCurrency(
                                            !empty($related['price2']) ? $related['price2'] : (
                                                !empty($related['sale_price']) ? $related['sale_price'] : ($related['price'] ?? 0)
                                            )
                                        );
                                        ?>
                                    </span>
                                <?php else: ?>
                                    <span class="pdp-related-price muted">Login for price</span>
                                <?php endif; ?>
                                <span class="badge <?php echo $rStock > 0 ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo $rStock > 0 ? 'In Stock' : 'Out'; ?>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </div>
</section>

<!-- Fullscreen lightbox -->
<div class="pdp-lightbox" id="pdpLightbox" hidden>
    <button type="button" class="pdp-lightbox-close" id="pdpLightboxClose" aria-label="Close preview">&times;</button>
    <img src="" alt="" id="pdpLightboxImg">
</div>

<!-- Mobile sticky CTA -->
<?php if ($stock > 0): ?>
<div class="pdp-mobile-bar d-lg-none" id="pdpMobileBar">
    <div class="pdp-mobile-bar-inner">
        <?php if (isLoggedIn()): ?>
            <div class="pdp-mobile-price"><?php echo formatCurrency($currentPrice); ?></div>
            <button type="button" class="pdp-btn pdp-btn-primary" id="pdpMobileAddBtn">
                <i class="bi bi-cart-plus-fill" aria-hidden="true"></i> Add to Cart
            </button>
        <?php else: ?>
            <a href="<?php echo BASE_URL; ?>?controller=user&action=login" class="pdp-btn pdp-btn-primary pdp-btn-block">
                Login to purchase
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<script src="<?php echo BASE_URL; ?>assets/js/product-gallery.js?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>"></script>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
