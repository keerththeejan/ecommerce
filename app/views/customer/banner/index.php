<?php
require_once dirname(__DIR__, 4) . '/config/database.php';
$db = new Database();
$bannerModel = new Banner($db);
$banners = $bannerModel->getAll('active');

$settingModel = new Setting();
$bannerWidthPercent = (int)$settingModel->getSetting('banner_width_percent', '100');
$bannerHeightDesktop = (int)$settingModel->getSetting('banner_height_desktop', '600');
$bannerHeightMobile = (int)$settingModel->getSetting('banner_height_mobile', '250');

$bannerWidthPercent = ($bannerWidthPercent >= 10 && $bannerWidthPercent <= 100) ? $bannerWidthPercent : 100;
$bannerHeightDesktop = ($bannerHeightDesktop >= 150 && $bannerHeightDesktop <= 1200) ? $bannerHeightDesktop : 600;
$bannerHeightMobile = ($bannerHeightMobile >= 120 && $bannerHeightMobile <= 800) ? $bannerHeightMobile : 250;

// If no active banners, show a default banner
if (empty($banners)) {
    $banners = [
        [
            'title' => 'Welcome to Our Store',
            'description' => 'Discover amazing products and great deals',
            'image_url' => '/images/default-brand.png'
        ],
        [
            'title' => 'Special Offers',
            'description' => 'Check out our latest collection',
            'image_url' => '/images/bottom.png'
        ],
        [
            'title' => 'New Arrivals',
            'description' => 'Explore our newest products',
            'image_url' => '/images/bottom.png'
        ]
    ];
}
?>

<!-- Banner Carousel - Full width, no white box -->
<section class="banner-carousel py-2 py-md-3">
    <div class="container-fluid px-0 px-md-2 max-width-1400">
        <div id="bannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="hover" data-bs-touch="true">
        <!-- Slides -->
        <div class="carousel-inner">
            <?php foreach ($banners as $index => $banner): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                    <div class="banner-image-container">
                        <img src="<?php echo strpos($banner['image_url'], 'http') === 0 ? $banner['image_url'] : (BASE_URL . ltrim(htmlspecialchars($banner['image_url']), '/')); ?>" 
                             class="d-block w-100 banner-image" 
                             alt="<?php echo htmlspecialchars($banner['title']); ?>" 
                             loading="lazy">
                        <div class="carousel-caption">
                            <div class="caption-content">
                                <h2><?php echo htmlspecialchars($banner['title']); ?></h2>
                                <p><?php echo htmlspecialchars($banner['description']); ?></p>
                            </div>
                            <?php if(!empty($banner['title'])): ?>
                                <div class="caption-button">
                                    <a href="<?php echo BASE_URL; ?>?controller=product&action=all" class="btn btn-light btn-banner mt-2">
                                        Shop Now <i class="fas fa-arrow-right ms-2"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2d3436" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="24" height="24">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#2d3436" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" width="24" height="24">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </span>
            <span class="visually-hidden">Next</span>
        </button>
        </div>
    </div>
</section>

<style>
/* Modern Banner Carousel Theme - Full width, no white box */
.banner-carousel {
    margin: 0;
    height: auto;
    position: relative;
    background: transparent;
    padding: 1.5rem 0;
    /* Break out of container - full viewport width */
    width: 100vw;
    max-width: 100vw;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
    padding-left: 0;
    padding-right: 0;
}

.carousel-item,
.banner-image-container {
    height: <?php echo (int)$bannerHeightDesktop; ?>px;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    position: relative;
}

.banner-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.carousel-item:hover .banner-image {
    transform: scale(1.08);
}

/* Modern Caption Styling */
.carousel-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(0deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.4) 50%, transparent 100%);
    padding: 3rem 2.5rem 6rem;
    border-radius: 0 0 16px 16px;
    text-align: left;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 2rem;
}

.caption-content {
    flex: 1;
    text-align: left;
    margin-bottom: 1rem;
}

.caption-button {
    flex-shrink: 0;
    text-align: right;
}

.carousel-caption h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
    color: #ffffff;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
    letter-spacing: -0.5px;
    line-height: 1.2;
}

.carousel-caption p {
    font-size: 1.15rem;
    color: rgba(255, 255, 255, 0.95);
    text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
    margin-bottom: 1.5rem;
    font-weight: 400;
}

/* Banner CTA Button */
.btn-banner {
    background: #ffffff;
    color: #2d3436;
    font-weight: 600;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    border: none;
    font-size: 1rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
    display: inline-block;
    text-decoration: none;
}

.btn-banner:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 6px 24px rgba(0, 0, 0, 0.4);
}

/* Modern Control Buttons */
.carousel-control-prev,
.carousel-control-next {
    width: 50px;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 10;
}

.carousel-control-prev {
    left: 20px;
}

.carousel-control-next {
    right: 20px;
}

.carousel-control-icon {
    width: 50px;
    height: 50px;
    padding: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.carousel-control-icon svg {
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.carousel-control-prev:hover .carousel-control-icon,
.carousel-control-next:hover .carousel-control-icon {
    background: #ffffff;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.banner-carousel:hover .carousel-control-prev,
.banner-carousel:hover .carousel-control-next {
    opacity: 1;
}

/* Mobile adjustments */
@media (max-width: 767.98px) {
    .banner-carousel {
        padding: 1rem 0;
    }
    
    .carousel-inner,
    .carousel-item,
    .banner-image-container {
        height: <?php echo (int)$bannerHeightMobile; ?>px;
        border-radius: 12px;
    }

    .carousel-caption {
        padding: 2rem 1.5rem 4.5rem;
        border-radius: 0 0 12px 12px;
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .caption-content {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .caption-button {
        width: 100%;
        text-align: left;
    }

    .carousel-caption h2 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .carousel-caption p {
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }
    
    .btn-banner {
        padding: 0.625rem 1.5rem;
        font-size: 0.9rem;
    }

    .carousel-control-prev,
    .carousel-control-next {
        display: none;
    }
}

/* Tablet adjustments */
@media (min-width: 768px) and (max-width: 991.98px) {
    .carousel-caption h2 {
        font-size: 2rem;
    }
    
    .carousel-caption p {
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var myCarousel = document.querySelector('#bannerCarousel');
    if (myCarousel) {
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 5000,
            touch: true,
            wrap: true
        });

        // Slide animation
        myCarousel.addEventListener('slide.bs.carousel', function (e) {
            var active = this.querySelector('.carousel-item.active');
            var next = e.relatedTarget;

            if (active && active.querySelector('.carousel-caption')) {
                active.querySelector('.carousel-caption').classList.remove('animate__fadeInUp');
            }

            if (next && next.querySelector('.carousel-caption')) {
                setTimeout(function() {
                    next.querySelector('.carousel-caption').classList.add('animate__animated', 'animate__fadeInUp');
                }, 50);
            }
        });

        var firstCaption = myCarousel.querySelector('.carousel-item.active .carousel-caption');
        if (firstCaption) {
            firstCaption.classList.add('animate__animated', 'animate__fadeInUp');
        }
    }
});
</script>
