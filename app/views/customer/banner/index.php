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
            'image_url' => '/assets/images/default-banner.jpg'
        ],
        [
            'title' => 'Special Offers',
            'description' => 'Check out our latest collection',
            'image_url' => '/assets/images/default-banner-2.jpg'
        ],
        [
            'title' => 'New Arrivals',
            'description' => 'Explore our newest products',
            'image_url' => '/assets/images/default-banner-3.jpg'
        ]
    ];
}
?>

<!-- Full Width Banner Carousel Section -->
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
/* Full Width & Height Banner Carousel */
.banner-carousel {
    margin: 0;
    padding: 0;
    width: 100vw;
    height: 100vh;
    overflow: hidden;
    position: relative;
    z-index: 1;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
}

.carousel-inner, .carousel-item {
    width: 100%;
    height: 100%;
    border-radius: 0;
    overflow: hidden;
}

.banner-image-container {
    position: relative;
    width: 100vw;
    height: 100vh;
    overflow: hidden;
}

.banner-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.5s ease-in-out;
}

.carousel-item:hover .banner-image {
    transform: scale(1.05);
}

.carousel-caption {
    position: absolute;
    bottom: 20%;
    left: 10%;
    right: 10%;
    text-align: left;
    padding: 2.5rem;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    border-radius: 15px;
    transition: all 0.3s ease;
    z-index: 2;
}

.carousel-caption:hover {
    background: rgba(0, 0, 0, 0.7);
}

.carousel-caption h2 {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    color: #fff;
}

.carousel-caption p {
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    color: #f8f9fa;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.carousel-indicators {
    bottom: 50px;
    z-index: 3;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
    background-color: rgba(255, 255, 255, 0.5);
    border: none;
}

.carousel-indicators .active {
    background-color: #fff;
    transform: scale(1.2);
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 4;
}

.banner-carousel:hover .carousel-control-prev,
.banner-carousel:hover .carousel-control-next {
    opacity: 1;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 2.5rem;
    height: 2.5rem;
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 50%;
    background-size: 1.5rem;
}

/* Responsive */
@media (max-width: 767.98px) {
    .banner-carousel {
        height: auto;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        width: 100vw;
        max-width: none;
    }

    .carousel-inner, .carousel-item {
        height: auto;
    }

    .banner-image-container {
        position: relative;
        width: 100vw;
        height: 0;
        padding-top: 56.25%;
    }

    .banner-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .carousel-caption {
        padding: 10px;
        bottom: 20%;
        left: 15px;
        right: 15px;
        width: auto;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 8px;
    }

    .carousel-caption h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .carousel-caption p {
        margin-bottom: 1rem;
        display: none;
    }

    .carousel-control-prev,
    .carousel-control-next {
        display: none;
    }
}
</style>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

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
