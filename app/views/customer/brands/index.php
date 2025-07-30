<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<style>
.brand-card {
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
    display: block;
    text-decoration: none;
    color: inherit;
}

.brand-card {
    display: block;
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
    border-radius: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
}

.brand-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(0,0,0,0.05), rgba(0,0,0,0.1));
    z-index: 1;
}

.brand-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
}

.brand-card:hover::before {
    background: linear-gradient(45deg, rgba(0,0,0,0.1), rgba(0,0,0,0.2));
}

.brand-image-container {
    background-color: #f8f9fa;
    border-radius: 20px;
    overflow: hidden;
    margin: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    min-height: 300px;
}

.brand-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    transition: all 0.3s ease;
    height: auto;
    width: auto;
}

/* Image size classes */
.image-size-small {
    height: 500px;
}

.image-size-medium {
    height: 300px;
}

.image-size-large {
    height: 400px;
}

.image-size-full {
    height: 600px;
}

/* Hover effects */
.brand-card:hover .brand-image-container {
    background-color: #f0f0f0;
}

.brand-card:hover .brand-image {
    transform: scale(1.05);
}

.brand-card:nth-child(1) { animation: fadeInZoom 0.8s ease-in-out forwards 0.2s; }
.brand-card:nth-child(2) { animation: fadeInZoom 0.8s ease-in-out forwards 0.4s; }
.brand-card:nth-child(3) { animation: fadeInZoom 0.8s ease-in-out forwards 0.6s; }
.brand-card:nth-child(4) { animation: fadeInZoom 0.8s ease-in-out forwards 0.8s; }
.brand-card:nth-child(5) { animation: fadeInZoom 0.8s ease-in-out forwards 1s; }
.brand-card:nth-child(6) { animation: fadeInZoom 0.8s ease-in-out forwards 1.2s; }
.brand-card:nth-child(7) { animation: fadeInZoom 0.8s ease-in-out forwards 1.4s; }
.brand-card:nth-child(8) { animation: fadeInZoom 0.8s ease-in-out forwards 1.6s; }

@keyframes fadeInZoom {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.brand-card:hover {
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Our Brands</h1>
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-outline-primary">View All <i class="fas fa-arrow-right"></i></a>
            <select class="form-select form-select-sm" id="imageSizeSelector" style="width: 150px;">
                <option value="small">Small Images</option>
                <option value="medium" selected>Medium Images</option>
                <option value="large">Large Images</option>
                <option value="full">Full Size</option>
            </select>
        </div>
    </div>

    <script>
    // Auto-scroll animation for brands
    const brandContainer = document.querySelector('.brand-container');
    let isScrolling = false;
    let scrollInterval;

    function startAutoScroll() {
        if (isScrolling) return;
        
        isScrolling = true;
        const container = brandContainer;
        const containerWidth = container.offsetWidth;
        const scrollWidth = container.scrollWidth;
        
        // Calculate scroll duration based on number of brands
        const brands = container.querySelectorAll('.brand-card');
        const brandCount = brands.length;
        const baseDuration = 3000; // Base duration in milliseconds
        const durationPerBrand = 1000; // Additional duration per brand
        const scrollDuration = baseDuration + (brandCount * durationPerBrand);
        
        // Calculate scroll amount based on container width
        const scrollAmount = containerWidth * 0.8; // Scroll 80% of container width
        
        scrollInterval = setInterval(() => {
            const scrollLeft = container.scrollLeft;
            const maxScroll = scrollWidth - containerWidth;
            
            if (scrollLeft >= maxScroll) {
                // Reset to start with a smooth animation
                container.scrollTo({ 
                    left: 0, 
                    behavior: 'smooth',
                    duration: 1500
                });
            } else {
                // Calculate next scroll position
                const nextScroll = Math.min(scrollLeft + scrollAmount, maxScroll);
                
                // Scroll smoothly with easing
                container.scrollTo({ 
                    left: nextScroll, 
                    behavior: 'smooth',
                    duration: 1000
                });
            }
        }, scrollDuration);
    }

    function stopAutoScroll() {
        isScrolling = false;
        if (scrollInterval) {
            clearInterval(scrollInterval);
        }
    }

    // Start auto-scroll on page load
    startAutoScroll();

    // Add hover effect to pause scrolling
    brandContainer.addEventListener('mouseenter', () => {
        stopAutoScroll();
    });

    brandContainer.addEventListener('mouseleave', () => {
        startAutoScroll();
    });

    // Add touch support for mobile devices
    brandContainer.addEventListener('touchstart', () => {
        stopAutoScroll();
    });

    brandContainer.addEventListener('touchend', () => {
        startAutoScroll();
    });

    // Add manual scroll buttons
    const scrollButtons = `
        <div class="scroll-buttons">
            <button class="scroll-left" onclick="scrollBrands('left')">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="scroll-right" onclick="scrollBrands('right')">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    `;

    // Add buttons to container
    brandContainer.insertAdjacentHTML('afterend', scrollButtons);

    // Add scroll buttons functionality
    function scrollBrands(direction) {
        const container = brandContainer;
        const scrollAmount = 300; // Adjust this value for scroll speed
        
        if (direction === 'left') {
            container.scrollLeft -= scrollAmount;
        } else {
            container.scrollLeft += scrollAmount;
        }
    }

    // Add scroll buttons styles
    const style = document.createElement('style');
    style.textContent = `
        .scroll-buttons {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            z-index: 10;
            pointer-events: none;
        }
        
        .scroll-buttons button {
            background: rgba(255, 255, 255, 0.8);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            pointer-events: auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .scroll-buttons button:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }
        
        .scroll-buttons button i {
            color: #333;
            font-size: 18px;
        }
    `;
    document.head.appendChild(style);

    // Image size selector
    document.getElementById('imageSizeSelector').addEventListener('change', function() {
        const cards = document.querySelectorAll('.brand-card');
        const size = this.value;
        
        const sizes = {
            'small': { height: '300px', padding: '20px' },
            'medium': { height: '400px', padding: '30px' },
            'large': { height: '500px', padding: '40px' },
            'full': { height: '600px', padding: '50px' }
        };
        
        cards.forEach(card => {
            const container = card.querySelector('.brand-image-container');
            if (container) {
                container.style.height = sizes[size].height;
                container.style.padding = sizes[size].padding;
                
                // Update image size
                const img = container.querySelector('img');
                if (img) {
                    img.style.maxHeight = sizes[size].height;
                    img.style.maxWidth = '100%';
                }
            }
        });
    });

    // Initialize with medium size
    // Initialize with medium size
    document.getElementById('imageSizeSelector').value = 'medium';
    document.getElementById('imageSizeSelector').dispatchEvent(new Event('change'));

    // Set initial size
    const cards = document.querySelectorAll('.brand-card');
    cards.forEach(card => {
        const container = card.querySelector('.brand-image-container');
        if (container) {
            container.style.height = sizes['medium'].height;
            container.style.padding = sizes['medium'].padding;
            const img = container.querySelector('img');
            if (img) {
                img.style.maxHeight = sizes['medium'].height;
                img.style.maxWidth = '100%';
            }
        }
    });
    </script>

    <?php flash('brand_error', '', 'alert alert-danger'); ?>

    <?php if (empty($brands)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No brands available at the moment. Please check back later.</p>
        </div>
    <?php else: ?>
        <div style="display: flex; overflow-x: auto; gap: 0px; scroll-behavior: smooth; animation: scrollBrands 2s linear infinite;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Our Brands</h1>
        <a href="#" class="btn btn-outline-primary">View All <i class="fas fa-arrow-right"></i></a>
    </div>

    <?php flash('brand_error', '', 'alert alert-danger'); ?>

    <?php if (empty($brands)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No brands available at the moment. Please check back later.</p>
        </div>
    <?php else: ?>
        <div style="display: flex; overflow-x: auto; gap: 0px; scroll-behavior: smooth; animation: scrollBrands 2s linear infinite;">
            <?php foreach ($brands as $brand): ?>
                <div style="flex: 0 0 auto; width: 300px; position: relative;">
                    <a href="<?php echo BASE_URL . '?controller=brand&action=show&param=' . $brand['slug']; ?>" class="brand-card">
                        <div class="brand-image-container">
                            <img src="<?php echo htmlspecialchars($logoPath); ?>"
                                 alt="<?php echo htmlspecialchars($brand['name']); ?>"
                                 class="brand-image"
                                 onerror="this.onerror=null; this.src='<?php echo rtrim(BASE_URL, '/'); ?>/public/images/default-brand.png';">
                                    <?php
                                    $logoPath = '';
                                    if (!empty($brand['logo'])) {
                                        if (strpos($brand['logo'], 'http') === 0) {
                                            $logoPath = $brand['logo'];
                                        } else {
                                            $logoPath = rtrim(BASE_URL, '/') . '/' . ltrim($brand['logo'], '/');
                                        }
                                    }
                                    ?>
                                    <?php if (!empty($logoPath)): ?>
                                        <img src="<?php echo htmlspecialchars($logoPath); ?>"
                                             alt="<?php echo htmlspecialchars($brand['name']); ?>"
                                             class="brand-image"
                                             onerror="this.onerror=null; this.src='<?php echo rtrim(BASE_URL, '/'); ?>/public/images/default-brand.png';">
                                    <?php else: ?>
                                        <div class="brand-image-container">
                                            <div class="brand-image">
                                                <i class="fas fa-building text-muted fa-2x"></i>
                                            </div>
                                            <i class="fas fa-building text-muted fa-2x"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <h5 style="font-size: 16px; margin-top: 10px;"><?php echo $brand['name']; ?></h5>
                                <?php if (!empty($brand['description'])): ?>
                                    <p style="font-size: 12px; color: #6c757d;"><?php echo truncateText($brand['description'], 60); ?></p>
                                <?php endif; ?>
                            </div>
                            <div style="text-align: center; padding-bottom: 10px;">
                                <span style="font-size: 12px;" class="btn btn-outline-primary btn-sm">View Products</span>
                            </div>
                            <?php if (isset($_SESSION['admin_id'])): ?>
                                <div style="position: absolute; top: 5px; right: 5px;">
                                    <a href="<?php echo BASE_URL; ?>?controller=brand&action=edit&id=<?php echo $brand['id']; ?>"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Keyframes animation added -->
        <style>
            @keyframes scrollBrands {
                0%   { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }

            /* Optional scroll bar hide for better UX */
            ::-webkit-scrollbar {
                height: 6px;
            }
            ::-webkit-scrollbar-thumb {
                background-color: #ccc;
                border-radius: 3px;
            }
        </style>
    <?php endif; ?>
</div>


<style>
.brand-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid rgba(0,0,0,0.1);
}
.brand-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
.brand-logo {
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
