<?php require_once APP_PATH . '/views/customer/layouts/header.php'; ?>

<div class="storefront-shell py-4 py-lg-5">
    <div class="row">
        <div class="col-12">
            <div class="storefront-toolbar">
                <h1 class="mb-0 fs-4">Countries of Origin</h1>
            </div>
            
            <div class="row row-cols-2 row-cols-sm-3 row-cols-md-5 row-cols-lg-6 row-cols-xl-7 g-2 g-md-3">
                <?php if(!empty($countries)) : ?>
                    <?php foreach($countries as $country) : ?>
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm storefront-panel">
                                <?php if(!empty($country['flag_image'])) : ?>
                                    <img src="<?php echo rtrim(BASE_URL, '/'); ?>/uploads/flags/<?php echo htmlspecialchars($country['flag_image']); ?>"
                                         class="card-img-top"
                                         alt="<?php echo htmlspecialchars($country['name']); ?>"
                                         style="height: 110px; object-fit: contain;"
                                         onerror="this.onerror=null; this.src='<?php echo rtrim(BASE_URL, '/'); ?>/images/default-brand.png';">
                                <?php else : ?>
                                    <div class="bg-light p-3 text-center" style="min-height:110px;">
                                        <i class="fas fa-globe fa-lg text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body text-center p-2 p-md-3">
                                    <div class="card-title small mb-2 text-capitalize"><?php echo $country['name']; ?></div>
                                    <a href="<?php echo BASE_URL; ?>?controller=country&action=show&id=<?php echo (int)$country['id']; ?>" class="btn btn-outline-dark btn-sm px-3">View</a>
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
