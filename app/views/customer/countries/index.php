<?php require_once APP_PATH . '/views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Countries of Origin</h1>
            
            <div class="row row-cols-2 row-cols-md-4 g-3">
                <?php if(!empty($countries)) : ?>
                    <?php foreach($countries as $country) : ?>
                        <div class="col mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <?php if(!empty($country['flag_image'])) : ?>
                                    <img src="<?php echo BASE_URL . $country['flag_image']; ?>" class="card-img-top" alt="<?php echo $country['name']; ?>" style="height: 140px; object-fit: cover;">
                                <?php else : ?>
                                    <div class="bg-light p-4 text-center">
                                        <i class="fas fa-globe fa-3x text-secondary"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body text-center">
                                    <h5 class="card-title fs-6"><?php echo $country['name']; ?></h5>
                                    <a href="<?php echo BASE_URL; ?>?controller=country&action=show&param=<?php echo $country['id']; ?>" class="btn btn-outline-dark btn-sm mt-2">View Products</a>
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
