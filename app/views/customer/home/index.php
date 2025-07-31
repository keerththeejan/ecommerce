<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<!-- Banner Section -->
<?php require_once APP_PATH . 'views/customer/banner/index.php'; ?>

<!-- Featured Categories - Improved responsive grid -->
<section class="featured-categories py-5" style="width: 100%; background: #fff; position: relative; overflow: hidden; padding-top: 0px; padding-bottom: 150px;">

    <div class="container-fluid" style="padding: 0; margin: 0 auto; max-width: 100%; position: relative; z-index: 1;">
        <h2 class="mb-5" style="font-size: 32px; font-weight: bold; text-align: center;">YOUR CATEGORIES</h2>

        <!-- 🔹 Category Slider -->
        <div id="categorySlider"
             style="display: flex; overflow-x: auto; scroll-behavior: smooth; gap: 10px; padding: 10px 30px; cursor: grab; scrollbar-width: none; -ms-overflow-style: none;">
             
            <style>
                #categorySlider::-webkit-scrollbar {
                    display: none;
                }
                @keyframes bounce {
                    0%, 80%, 100% { transform: scale(0.9); opacity: 0.6; }
                    40% { transform: scale(1.2); opacity: 1; }
                }
            </style>

            <?php 
            if (!empty($categories)) :
                $activeCategories = array_values(array_filter($categories, function($cat) {
                    return $cat['status'] == 1;
                }));
                foreach($activeCategories as $index => $category): 
            ?>
                <div style="flex: 0 0 auto; width: 200px;">
                    <a href="<?php echo BASE_URL; ?>?controller=product&action=category&id=<?php echo $category['id']; ?>" 
                       style="text-decoration: none; color: inherit;">
                        <div style="text-align: center;">
                            <div style="padding: 0; width: 150px; height: 150px; margin: 0 auto;">
                                <?php if (!empty($category['image'])) : ?>
                                    <img src="<?php echo BASE_URL . $category['image']; ?>" 
                                         alt="<?php echo htmlspecialchars($category['name']); ?>" 
                                         loading="lazy"
                                         style="width: 100%; height: 100%; object-fit: contain;">
                                <?php else : ?>
                                    <div><i class="fas fa-box fa-3x"></i></div>
                                <?php endif; ?>
                            </div>
                            <h3 style="font-size: 16px; margin-top: 10px;"><?php echo $category['name']; ?></h3>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
            <?php if (empty($activeCategories)): ?>
                <div style="padding: 20px;">No categories available</div>
            <?php endif; ?>
        </div>

        <!-- 🔹 Dot Navigation (horizontal like loading gif) -->
        <div style="text-align: center; margin-top: 30px;">
            <div style="display: flex; justify-content: center; gap: 20px;">
                <span onclick="scrollSlider(1)" style="width: 20px; height: 20px; border-radius: 50%; background: #FFC107; animation: bounce 1.2s infinite ease-in-out; animation-delay: 0s; cursor: pointer;"></span>
                <span onclick="scrollSlider(1)" style="width: 20px; height: 20px; border-radius: 50%; background: #F44336; animation: bounce 1.2s infinite ease-in-out; animation-delay: 0.2s; cursor: pointer;"></span>
                <span onclick="scrollSlider(1)" style="width: 20px; height: 20px; border-radius: 50%; background: #00BCD4; animation: bounce 1.2s infinite ease-in-out; animation-delay: 0.4s; cursor: pointer;"></span>
                <span onclick="scrollSlider(1)" style="width: 20px; height: 20px; border-radius: 50%; background: #E040FB; animation: bounce 1.2s infinite ease-in-out; animation-delay: 0.6s; cursor: pointer;"></span>
            </div>
        </div>

        <!-- 🔹 Scroll Function -->
        <script>
            function scrollSlider(direction) {
                var slider = document.getElementById("categorySlider");
                var scrollAmount = 300 * direction;
                slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        </script>
    </div>
</section>




<!-- Featured Products - Enhanced filtering and responsive layout -->
<section class="featured-products py-4 py-md-5 bg-light full-width-section">
    <div class="container-fluid px-4 px-xl-5 max-width-1400">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
            <h2 class="h4 mb-3 mb-md-0">Featured Products</h2>
        </div>

        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2 g-md-4">
            <?php if(!empty($featuredProducts)) : ?>
                <?php foreach($featuredProducts as $product) : ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm product-card transition-all d-flex flex-column">

                            <!-- 🖼️ Image Section - Responsive Box and Auto Image Resize -->
                            <div class="position-relative d-flex justify-content-center align-items-center" style="padding: 15px 0;">
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="text-decoration-none">
                                    
                                    <!-- 👇 Change this box size if needed -->
                                    <div style="width: 100%; height: 120px; overflow: hidden; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                        <?php if(!empty($product['image'])) : ?>
                                            <img src="<?php echo BASE_URL . $product['image']; ?>" 
                                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                                 loading="lazy"
                                                 style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px; transition: transform 0.3s ease;"
                                                 onmouseover="this.style.transform='scale(1.06)';"
                                                 onmouseout="this.style.transform='scale(1)';">
                                        <?php else : ?>
                                            <div class="d-flex align-items-center justify-content-center" style="width: 10 0%; height: 100%; background-color: rgba(240,240,240,0.9); border-radius: 12px;">
                                                <i class="fas fa-box-open fa-2x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>

                                

                                <?php if(isLoggedIn() && $product['stock_quantity'] > 0) : ?>
                                    <button class="btn-wishlist position-absolute top-0 end-0 m-2 bg-white rounded-circle shadow-sm p-2 d-flex align-items-center justify-content-center"
                                            data-product-id="<?php echo $product['id']; ?>">
                                        <i class="far fa-heart text-muted"></i>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <!-- 📝 Content -->
                            <div class="card-body p-2 d-flex flex-column">
                                <a href="<?php echo BASE_URL; ?>?controller=product&action=show&param=<?php echo $product['id']; ?>" class="text-decoration-none text-dark text-center">
                                    <h3 class="h6 card-title mb-1 text-truncate" style="font-size: 0.85rem; min-height: 2.2rem; display: flex; align-items: center; justify-content: center;">
                                        <?php echo $product['name']; ?>
                                    </h3>
                                    
                                    <div class="price-stock-container mt-2">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <?php if(isLoggedIn()): ?>
                                                <span class="fw-bold" style="font-size: 0.95rem;">
                                                    <?php echo formatCurrency($product['sale_price'] ?? $product['price']); ?>
                                                </span>
                                            <?php endif; ?>
                                            
                                            <span class="badge bg-<?php echo $product['stock_quantity'] > 0 ? 'success' : 'secondary'; ?>" style="font-size: 0.7rem; padding: 0.25em 0.5em;">
                                                <?php echo $product['stock_quantity'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                                            </span>
                                        </div>
                                        
                                        <?php if($product['stock_quantity'] > 0): ?>
                                            <div class="text-muted text-end" style="font-size: 0.75rem; margin: 3px 0; padding-right: 5px;">
                                                Stock: <?php echo $product['stock_quantity']; ?> units
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </a>

                                <!-- 🛒 Add to Cart -->
                               <?php if($product['stock_quantity'] > 0): ?>
  <?php if($product['stock_quantity'] > 0): ?>
    <?php if(isLoggedIn()): ?>
        <form action="<?php echo BASE_URL; ?>?controller=cart&action=add" method="POST" class="add-to-cart-form mt-auto">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

            <!-- Flex Row: Quantity and Button Side by Side -->
            <div style="display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 5px;">

                <!-- Quantity Box -->
                <div class="input-group input-group-xs" style="width: 110px; height: 30px;">
                    <button type="button" class="btn btn-outline-secondary quantity-decrease px-0" 
                            style="font-size: 0.7rem; width: 20px; padding: 0; line-height: 1.5;">-</button>

                    <input type="number" name="quantity" class="form-control text-center quantity-input" 
                           value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                           aria-label="Quantity" readonly
                           style="font-size: 0.7rem; height: 30px; padding: 0 2px; width: 30px; line-height: 1.5;">

                    <button type="button" class="btn btn-outline-secondary quantity-increase px-0" 
                            style="font-size: 0.7rem; width: 20px; padding: 0; line-height: 1.5;">+</button>
                </div>

                <!-- Add to Cart Button -->
                <button type="submit" 
                        class="btn btn-xs btn-primary py-1" 
                        style="font-size: 0.7rem; padding: 0; height: 30px; line-height: 1.2; width: 110px;">
                    <i class="fas fa-cart-plus me-1"></i> Add to Cart
                </button>

            </div>
        </form>
    <?php else: ?>
        
    <?php endif; ?>
<?php else: ?>
    <div class="text-danger text-center" style="font-size: 0.75rem;">Out of Stock</div>
<?php endif; ?>

        <!-- Login to Buy -->
        
    <?php endif; ?>

    <!-- Out of Stock Message -->
    


                                   
                               
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0">No featured products available</div>
                </div>
            <?php endif; ?>
        </div>

        <?php if(!empty($featuredProducts)): ?>
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>?controller=product&action=all" class="btn btn-outline-primary px-4">
                View All Products <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>











<!-- Brand Showcase - Enhanced with responsive grid -->
<section class="brands-showcase py-4 py-md-5 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
            <h2 class="h4 mb-0">Our Brands</h2>
            <a href="<?php echo BASE_URL; ?>?controller=brand&action=all" class="btn btn-sm btn-outline-primary d-none d-md-inline-flex">
                View All <i class="fas fa-chevron-right ms-1"></i>
            </a>
        </div>
        
        <div class="row g-2 g-md-3 justify-content-center">
            <?php if(!empty($brands)) : ?>
                <?php foreach(array_slice($brands, 0, 12) as $brand) : ?>
                    <?php if($brand['status'] == 'active') : ?>
                        <div class="col-4 col-sm-3 col-md-2 col-lg-2 col-xl-1">
                            <a href="<?php echo BASE_URL; ?>?controller=brand&action=show&param=<?php echo $brand['slug']; ?>" class="text-decoration-none">
                                <div class="brand-card card h-100 border-0 shadow-sm transition-all">
                                    <div class="card-body p-2 d-flex align-items-center justify-content-center">
                                        <div class="brand-logo-container">
                                            <?php if(!empty($brand['logo'])) : ?>
                                                <img src="<?php echo BASE_URL . 'uploads/brands/' . $brand['logo']; ?>" 
                                                     class="img-fluid" 
                                                     alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                                     loading="lazy"
                                                     onerror="this.onerror=null; this.src='<?php echo BASE_URL; ?>assets/img/no-image.jpg';">
                                            <?php else : ?>
                                                <div class="text-center">
                                                    <span class="fw-bold small text-muted"><?php echo htmlspecialchars($brand['name']); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
                
                <?php if(count($brands) > 12): ?>
                    <div class="col-4 col-sm-3 col-md-2 col-lg-2 col-xl-1 d-md-none">
                        <a href="<?php echo BASE_URL; ?>?controller=brand&action=all" class="text-decoration-none">
                            <div class="brand-card card h-100 border-0 shadow-sm transition-all bg-light">
                                <div class="card-body p-2 d-flex align-items-center justify-content-center">
                                    <div class="text-center">
                                        <i class="fas fa-ellipsis-h text-muted mb-1"></i>
                                        <p class="small mb-0">View All</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0">No brands available</div>
                </div>
            <?php endif; ?>
        </div>
        
        <?php endif; ?>
        
        <?php if(!empty($brands) && count($brands) > 12): ?>
        <div class="text-center mt-3 d-md-none">
            <a href="<?php echo BASE_URL; ?>?controller=brand&action=all" class="btn btn-outline-primary px-4">
                View All Brands <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Responsive Styles -->
<style>
/* Base transition for interactive elements */
.transition-all {
    transition: all 0.3s ease;
}

/* Banner Styles */
.main-banner .carousel-item img {
    height: auto;
    max-height: 500px;
    object-fit: cover;
    width: 100%;
    
}

@media (max-width: 768px) {
    .main-banner .carousel-item img {
        max-height: 200px;
    }
}

/* Category Styles */
.featured-categories {
    padding: 60px 0;
    background-color: #fff;
    
}

.featured-categories h2 {
    font-size: 24px;
    font-weight: 600;
    color: #000;
    text-transform: uppercase;
    margin-bottom: 50px;
    
}

.categories-slider {
    position: relative;
    padding: 0 40px;
}

.categories-wrapper {
    overflow: hidden;
}

.categories-track {
    display: flex;
    transition: transform 0.5s ease;
}

.category-slide {
    min-width: 20%;
    padding: 0 15px;
    flex: 0 0 auto;
    
}

.category-card {
    text-align: center;
}

.category-image-wrapper {
    position: relative;
    padding-bottom: 100%;
    margin-bottom: 15px;
    overflow: hidden;
}

.category-image-wrapper::before {
    content: '';
    position: absolute;
    top: -20%;
    left: -20%;
    right: -20%;
    bottom: -20%;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
    z-index: 1;
}

.splash-bg-1::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23ffecec" d="M41.3,-52.9C54.4,-47.3,66.6,-35.6,71.5,-21.2C76.4,-6.8,74,10.3,65.7,23.5C57.4,36.7,43.3,46,28.7,51.7C14.1,57.4,-0.9,59.5,-17.4,57.3C-33.9,55.2,-51.8,48.8,-63.5,35.8C-75.2,22.8,-80.6,3.2,-76.2,-13.8C-71.8,-30.8,-57.6,-45.2,-42.3,-50.5C-27,-55.8,-10.7,-52,2.8,-55.9C16.3,-59.8,28.2,-58.5,41.3,-52.9Z" transform="translate(100 100)"/></svg>'); }
.splash-bg-2::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23ecffec" d="M42.3,-57.7C55.4,-49.4,66.9,-37.9,71.5,-24.1C76.1,-10.3,73.8,5.8,67.8,19.9C61.8,34,52.1,46.1,39.7,54.5C27.3,62.9,12.1,67.6,-2.9,71.1C-18,74.6,-36,76.9,-45.6,68.1C-55.2,59.3,-56.4,39.4,-61.8,21.9C-67.2,4.4,-76.8,-10.7,-74.8,-24.1C-72.8,-37.5,-59.2,-49.2,-44.6,-57.1C-30,-65,-15,-69.1,0.2,-69.4C15.4,-69.7,29.2,-66,42.3,-57.7Z" transform="translate(100 100)"/></svg>'); }
.splash-bg-3::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23ecedff" d="M44.3,-63.3C57.8,-56.2,70.2,-44.3,74.3,-28.9C78.4,-13,77.3,4,71.4,18.5C65.5,33,54.8,45,42.1,53.7C29.4,62.4,14.7,67.8,0.2,67.5C-14.3,67.2,-28.6,61.2,-41.5,52.5C-54.4,43.8,-65.9,32.4,-71.1,18.1C-76.3,3.8,-75.2,-13.4,-68.3,-27.7C-61.4,-42,-48.7,-53.4,-35.2,-61.4C-21.7,-69.4,-7.2,-74,7.3,-83.8C21.8,-93.6,34.5,-75.8,44.3,-63.3Z" transform="translate(100 100)"/></svg>'); }
.splash-bg-4::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23fff5ec" d="M39.5,-57.1C51.4,-50.8,61.4,-40.2,67.5,-27.3C73.6,-14.4,75.8,0.8,72.1,14.5C68.4,28.2,58.8,40.3,46.7,48.7C34.6,57.1,20,61.8,4.7,55.9C-10.6,50,-26.6,33.5,-39.7,25.2C-52.8,16.9,-63,16.8,-65.8,8.2C-68.6,-0.4,-64,-17.5,-55.3,-30.1C-46.6,-42.7,-33.8,-50.8,-20.8,-56.5C-7.8,-62.2,5.4,-65.5,18.1,-63.9C30.8,-62.3,27.6,-63.4,39.5,-57.1Z" transform="translate(100 100)"/></svg>'); }
.splash-bg-5::before { background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%23f5ecff" d="M47.7,-67.7C60.9,-59.6,70.2,-44.8,74.3,-28.9C78.4,-13,77.3,4,71.4,18.5C65.5,33,54.8,45,42.1,53.7C29.4,62.4,14.7,67.8,0.2,67.5C-14.3,67.2,-28.6,61.2,-41.5,52.5C-54.4,43.8,-65.9,32.4,-71.1,18.1C-76.3,3.8,-75.2,-13.4,-68.3,-27.7C-61.4,-42,-48.7,-53.4,-35.2,-61.4C-21.7,-69.4,-7.2,-74,7.3,-83.8C21.8,-93.6,34.5,-75.8,47.7,-67.7Z" transform="translate(100 100)"/></svg>'); }

.category-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 70%;
    max-height: 70%;
    z-index: 2;
}

.category-name {
    font-size: 16px;
    font-weight: 500;
    color: #000;
    margin: 0;
    
}

.slider-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 30px;
    height: 30px;
    border: none;
    background: #fff;
    border-radius: 50%;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 2;
    cursor: pointer;
}

.slider-nav.prev { left: 0; }
.slider-nav.next { right: 0; }

.slider-dots {
    margin-top: 30px;
}

.dot {
    width: 8px;
    height: 8px;
    border: none;
    background: #ddd;
    border-radius: 50%;
    margin: 0 4px;
    padding: 0;
    cursor: pointer;
    
}

.dot.active {
    background: #000;
    width: 24px;
    border-radius: 4px;
    
}

/* Product Card Styles */
.product-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
}

.product-image-container {
    position: relative;
    overflow: hidden;
}

.product-image-container img {
    transition: transform 0.3s ease;
}

.product-card:hover .product-image-container img {
    transform: scale(1.05);
}

.btn-wishlist {
    width: 32px;
    height: 32px;
    border: none;
    transition: all 0.3s ease;
}

.btn-wishlist:hover {
    color: #dc3545 !important;
}

.btn-wishlist.active {
    color: #dc3545 !important;
}


.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Brand Styles */
.brand-card:hover {
    transform: translateY(-5px) !important;
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
}

.brand-logo-container {
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    
}

.brand-logo-container img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    filter: grayscale(100%);
    opacity: 0.7;
    transition: all 0.3s ease;
    
}

.brand-card:hover .brand-logo-container img {
    filter: grayscale(0%);
    opacity: 1;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
    .brand-logo-container {
        height: 40px;
    }
    
    .product-card .card-title {
        font-size: 0.9rem;
    }
    
    .product-card .card-text {
        font-size: 0.8rem;
    }
}

@media (max-width: 575.98px) {
    .brand-logo-container {
        height: 30px;
    }
    
    .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .input-group-sm > .form-control,
    .input-group-sm > .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.75rem;
    }
}

/* Full-width Section Styles */
.full-width-section {
    width: 100vw;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    overflow: hidden;
    background-color: transparent;
}

/* Hero Carousel Styles */
.hero-carousel {
    margin-bottom: 3rem;
}

.hero-slide {
    height: 80vh; /* 80% of viewport height */
    min-height: 600px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.hero-slide::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0.1) 100%);
}

.hero-content {
    color: #fff;
    padding: 2rem;
    border-radius: 10px;
    position: relative;
    z-index: 2;
}

.hero-subtitle {
    font-size: 1.2rem;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 1rem;
    color: #f8f9fa;
    font-weight: 500;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    line-height: 1.2;
}

.hero-text {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    max-width: 600px;
}

.hero-buttons .btn {
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.hero-buttons .btn-primary {
    box-shadow: 0 5px 15px rgba(0,123,255,0.4);
}

.hero-buttons .btn-outline-light {
    border-width: 2px;
}

.hero-buttons .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

/* Carousel Controls */
.carousel-control-prev, .carousel-control-next {
    width: 5%;
    opacity: 0;
    transition: all 0.3s ease;
}

.hero-carousel:hover .carousel-control-prev,
.hero-carousel:hover .carousel-control-next {
    opacity: 0.8;
}

.carousel-indicators {
    margin-bottom: 2rem;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.5);
    border: none;
    margin: 0 5px;
    
}

.carousel-indicators button.active {
    background-color: #fff;
    transform: scale(1.2);
}

/* Responsive Styles */
@media (max-width: 991.98px) {
    .hero-slide {
        height: 500px;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-text {
        font-size: 1.1rem;
    }
}

@media (max-width: 767.98px) {
    .hero-slide {
        height: 450px;
        background-position: 70% center;
    }
    
    .hero-slide::before {
        background: linear-gradient(0deg, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.6) 50%, rgba(0,0,0,0.4) 100%);
    }
    
    .hero-content {
        text-align: center;
        padding: 1.5rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
        letter-spacing: 2px;
    }
    
    .hero-title {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    
    .hero-text {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .hero-buttons .btn {
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 575.98px) {
    .hero-slide {
        height: 400px;
    }
    
    .hero-content {
        padding: 1rem;
    }
    
    .hero-title {
        font-size: 1.75rem;
    }
    
    .hero-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .hero-buttons .btn-outline-light {
        margin-left: 0 !important;
    }
}

/* Utility Classes */
.object-fit-cover {
    object-fit: cover;
}

.object-fit-contain {
    object-fit: contain;
}

.ratio-1x1 {
    aspect-ratio: 1 / 1;
}

.max-width-1400 {
    max-width: 1400px;
    margin-left: auto;
    margin-right: auto;
}
</style>

<!-- Category Slider JavaScript -->
<script>
let currentSlide = 0;
const slidesPerView = 5;

function slideCategories(direction) {
    const track = document.querySelector('.categories-track');
    const slides = document.querySelectorAll('.category-slide');
    const totalSlides = slides.length;
    const maxSlide = Math.ceil(totalSlides / slidesPerView) - 1;

    if (direction === 'next' && currentSlide < maxSlide) {
        currentSlide++;
    } else if (direction === 'prev' && currentSlide > 0) {
        currentSlide--;
    }

    updateSlider();
}

function goToSlide(slideIndex) {
    currentSlide = slideIndex;
    updateSlider();
}

function updateSlider() {
    const track = document.querySelector('.categories-track');
    const slides = document.querySelectorAll('.category-slide');
    const slideWidth = 100 / slidesPerView;
    track.style.transform = `translateX(-${currentSlide * slideWidth * slidesPerView}%)`;

    // Update dots
    const dots = document.querySelectorAll('.dot');
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlide);
    });

    // Update nav buttons
    const prevBtn = document.querySelector('.slider-nav.prev');
    const nextBtn = document.querySelector('.slider-nav.next');
    const maxSlide = Math.ceil(slides.length / slidesPerView) - 1;

    prevBtn.style.visibility = currentSlide === 0 ? 'hidden' : 'visible';
    nextBtn.style.visibility = currentSlide === maxSlide ? 'hidden' : 'visible';
}

// Initialize slider
document.addEventListener('DOMContentLoaded', () => {
    updateSlider();
});
</script>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>