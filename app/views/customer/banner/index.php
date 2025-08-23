<?php
require_once dirname(__DIR__, 4) . '/config/database.php';
$db = new Database();
$bannerModel = new Banner($db);
$banners = $bannerModel->getAll('active');

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

<!-- Full Width Banner Carousel with Reduced Height and No Box -->
<section class="banner-carousel">
    <div id="bannerCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="hover" data-bs-touch="true">
        <!-- Indicators -->
        <div class="carousel-indicators">
            <?php foreach ($banners as $index => $banner): ?>
                <button type="button" 
                        data-bs-target="#bannerCarousel" 
                        data-bs-slide-to="<?php echo $index; ?>" 
                        class="<?php echo $index === 0 ? 'active' : ''; ?>"
                        aria-current="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                        aria-label="Slide <?php echo $index + 1; ?>"></button>
            <?php endforeach; ?>
        </div>

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
                            <h2><?php echo htmlspecialchars($banner['title']); ?></h2>
                            <p><?php echo htmlspecialchars($banner['description']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<style>
/* Full Width Reduced Height Carousel */
.banner-carousel {
    margin: 0;
    padding: 0;
    height: auto;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    margin-top: -30px; /* Even more reduced top margin */
    width: 100vw;
    max-width: none;
}

.carousel-item,
.banner-image-container {
    height: 600px; /* Adjust height here if needed */
}

.banner-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.5s ease-in-out;
}

.carousel-item:hover .banner-image {
    transform: scale(1.05);
}

/* Remove background box behind caption */
.carousel-caption {
    position: absolute;
    bottom: 20px;
    left: 20px;
    background: none;
    padding: 0;
    border-radius: 0;
}

/* Caption Text Styling */
.carousel-caption h2 {
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
    color: #ffffff;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.7); /* improve visibility */
}

.carousel-caption p {
    font-size: 1rem;
    color: #f8f9fa;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.7);
}

.carousel-indicators {
    bottom: 15px;
}

.carousel-indicators button {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: rgba(255,255,255,0.6);
    border: none;
}

.carousel-indicators .active {
    background-color: #fff;
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.banner-carousel:hover .carousel-control-prev,
.banner-carousel:hover .carousel-control-next {
    opacity: 1;
}

/* Mobile adjustments */
@media (max-width: 767.98px) {
    .carousel-inner,
    .carousel-item,
    .banner-image-container {
        height: 250px;
    }

    .carousel-caption {
        bottom: 10px;
    }

    .carousel-caption h2 {
        font-size: 1.25rem;
    }

    .carousel-caption p {
        font-size: 0.9rem;
    }

    .carousel-control-prev,
    .carousel-control-next {
        display: none;
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
