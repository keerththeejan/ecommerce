<?php require_once APP_PATH . '/views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-2 fs-5">Countries of Origin</h1>
            
            <div class="row row-cols-2 row-cols-md-6 row-cols-lg-7 g-1">
                <?php if(!empty($countries)) : ?>
                    <?php foreach($countries as $country) : ?>
                        <div class="col mb-1">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if(!empty($country['flag_image'])) : ?>
                                    <img src="<?php echo rtrim(BASE_URL, '/'); ?>/uploads/flags/<?php echo htmlspecialchars($country['flag_image']); ?>"
                                         class="card-img-top"
                                         alt="<?php echo htmlspecialchars($country['name']); ?>"
                                         style="height: 95px; object-fit: contain;"
                                         onerror="this.onerror=null; this.src='<?php echo rtrim(BASE_URL, '/'); ?>/images/default-brand.png';">
                                <?php else : ?>
                                    <div class="bg-light p-2 text-center">
                                        <i class="fas fa-globe fa-lg text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body text-center p-1">
                                    <div class="card-title small mb-1 text-capitalize"><?php echo $country['name']; ?></div>
                                    <a href="<?php echo BASE_URL; ?>?controller=country&action=show&id=<?php echo (int)$country['id']; ?>" class="btn btn-outline-dark btn-sm py-0 px-2">View</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            No countries available.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_PATH . '/views/customer/layouts/footer.php'; ?>
