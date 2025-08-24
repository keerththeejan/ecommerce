<?php require_once APP_PATH . '/views/customer/layouts/header.php'; ?>

<style>
/* Grid-only styles kept; removed legacy scroller styles */
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
                                <?php 
                                    $logo = trim($brand['logo']);
                                    $src = '';
                                    if (filter_var($logo, FILTER_VALIDATE_URL)) {
                                        $src = $logo;
                                    } elseif (strpos($logo, 'public/uploads/') === 0) {
                                        // Strip leading 'public/' since BASE_URL already ends with /public/
                                        $src = rtrim(BASE_URL, '/') . '/' . ltrim(substr($logo, strlen('public/')), '/');
                                    } elseif (strpos($logo, 'uploads/') === 0) {
                                        $src = rtrim(BASE_URL, '/') . '/' . ltrim($logo, '/');
                                    } else {
                                        // Fallback to brands folder using just the basename
                                        $src = rtrim(BASE_URL, '/') . '/uploads/brands/' . basename($logo);
                                    }
                                ?>
                                <img src="<?php echo htmlspecialchars($src); ?>"
                                     alt="<?php echo htmlspecialchars($brand['name']); ?>" 
                                     class="img-fluid"
                                     onerror="this.onerror=null; this.src='<?php echo rtrim(BASE_URL, '/'); ?>/images/default-brand.png';">
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
    padding: 10px 0;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 16px;
}

.brand-card {
    width: 100%;
    border-radius: 20px;
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
    height: 180px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 16px;
}

.brand-image {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}

/* Hide scrollbar for Chrome, Safari and Opera */
/* No horizontal scrollbar in grid layout */
.brands-wrapper::-webkit-scrollbar { display: none; }

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
    .brand-image-container { height: 120px; padding: 15px; }
}

@media (max-width: 480px) {
    .brand-image-container { height: 100px; padding: 10px; }
    .brand-card h5 { font-size: 0.95rem; }
    .brand-card .btn-sm { padding: 0.25rem 0.6rem; font-size: 0.8rem; }
}
</style>

<?php require_once APP_PATH . '/views/customer/layouts/footer.php'; ?>
