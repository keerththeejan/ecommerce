<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<style>
.brands-container {
    display: flex;
    overflow-x: auto;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
    gap: 15px;
    padding: 15px 0;
    width: 100%;
}

.brands-container::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

.brand-card {
    opacity: 0;
    transform: scale(0.8);
    transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
    display: block;
    text-decoration: none;
    color: inherit;
    flex: 0 0 auto;
    width: 200px;
    position: relative;
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
    transform: translateY(-10px) scale(1.05);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
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
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Our Brands</h1>
        <div class="d-flex gap-2">
            <a href="#" class="btn btn-outline-primary d-none d-md-inline-flex">View All <i class="fas fa-arrow-right"></i></a>
            <select class="form-select form-select-sm d-none d-md-block" id="imageSizeSelector" style="width: 150px;">
                <option value="small">Small Images</option>
                <option value="medium" selected>Medium Images</option>
                <option value="large">Large Images</option>
                <option value="full">Full Size</option>
            </select>
        </div>
    </div>
    <div class="brands-container">

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

    <?php if (empty($brands)): ?>
        <div class="alert alert-info">
            <p class="mb-0">No brands available at the moment. Please check back later.</p>
        </div>
    <?php else: ?>
        <div class="brands-wrapper">
            <?php foreach ($brands as $brand): ?>
                <div class="brand-card">
                    <div class="brand-image-container">
                        <div class="brand-image">
                            <?php if (!empty($brand['logo'])): ?>
                                <img src="<?php echo BASE_URL . 'uploads/brands/' . $brand['logo']; ?>" 
                                     alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                     class="img-fluid">
                            <?php else: ?>
                                <i class="fas fa-building text-muted fa-3x"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="p-3 text-center">
                        <h5 class="mb-2"><?php echo htmlspecialchars($brand['name']); ?></h5>
                        <?php if (!empty($brand['description'])): ?>
                            <p class="small text-muted mb-2">
                                <?php echo htmlspecialchars(truncateText($brand['description'], 60)); ?>
                            </p>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>?controller=product&action=index&brand=<?php echo $brand['id']; ?>" 
                           class="btn btn-outline-primary btn-sm">
                            View Products
                        </a>
                    </div>
                    <?php if (isset($_SESSION['admin_id'])): ?>
                        <div class="position-absolute" style="top: 10px; right: 10px;">
                            <a href="<?php echo BASE_URL; ?>?controller=brand&action=edit&id=<?php echo $brand['id']; ?>"
                               class="btn btn-sm btn-outline-secondary"
                               title="Edit Brand">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.brands-wrapper {
    width: 100%;
    overflow-x: auto;
    padding: 10px 0;
    -webkit-overflow-scrolling: touch;
}

.brand-card {
    flex: 0 0 auto;
    width: 200px;
    margin-right: 15px;
    border-radius: 12px;
    overflow: hidden;
    background: white;
    box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    position: relative;
}

.brand-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.brand-image-container {
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    padding: 20px;
}

.brand-image {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

/* Hide scrollbar for Chrome, Safari and Opera */
.brands-wrapper::-webkit-scrollbar {
    height: 6px;
}

.brands-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.brands-wrapper::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.brands-wrapper::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Hide scrollbar for IE, Edge and Firefox */
.brands-wrapper {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: thin;  /* Firefox */
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .brand-card {
        width: 160px;
    }
    
    .brand-image-container {
        height: 120px;
        padding: 15px;
    }
}

@media (max-width: 480px) {
    .brand-card {
        width: 140px;
    }
    
    .brand-image-container {
        height: 100px;
        padding: 10px;
    }
    
    .brand-card h5 {
        font-size: 0.9rem;
    }
    
    .brand-card .btn-sm {
        padding: 0.2rem 0.5rem;
        font-size: 0.75rem;
    }
}
</style>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
