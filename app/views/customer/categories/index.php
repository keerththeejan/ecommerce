<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Categories</li>
        </ol>
    </nav>

    <h1 class="mb-4">Category</h1>

    <?php if(!empty($categories)) : ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach($categories as $category) : ?>
                <?php if($category['status'] == 1) : ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <?php if(!empty($category['image'])) : ?>
                                <img src="<?php echo BASE_URL . $category['image']; ?>" class="card-img-top" alt="<?php echo $category['name']; ?>">
                            <?php else : ?>
                                <div class="bg-light p-4 text-center">
                                    <i class="fas fa-tags fa-4x text-secondary"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $category['name']; ?></h5>
                                <?php if(!empty($category['description'])) : ?>
                                    <p class="card-text"><?php echo truncateText($category['description'], 100); ?></p>
                                <?php endif; ?>
                                
                                <?php if(!empty($category['children'])) : ?>
                                    <div class="mt-3">
                                        <p class="fw-bold mb-2">Subcategories:</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php foreach($category['children'] as $child) : ?>
                                                <a href="<?php echo BASE_URL; ?>?controller=category&action=show&param=<?php echo $child['id']; ?>" class="badge bg-light text-dark text-decoration-none p-2">
                                                    <?php echo $child['name']; ?>
                                                </a>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="<?php echo BASE_URL; ?>?controller=category&action=show&param=<?php echo $category['id']; ?>" class="btn btn-outline-dark w-100">Browse Products</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="alert alert-info">
            <p class="mb-0">No categories available.</p>
        </div>
    <?php endif; ?>
</div>

<?php require_once APP_PATH . 'views/customer/layouts/footer.php'; ?>
