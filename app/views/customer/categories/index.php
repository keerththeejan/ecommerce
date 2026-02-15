<?php require_once APP_PATH . 'views/customer/layouts/header.php'; ?>

<div class="container py-5">
    <style>
        .category-grid .card-img-top {
            height: 120px;
            width: 100%;
            object-fit: contain;
            object-position: center;
            padding: 0.75rem;
            background: #f8f9fa;
        }
        .category-grid .category-img-placeholder {
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (min-width: 768px) {
            .category-grid .card-img-top { height: 140px; }
            .category-grid .category-img-placeholder { min-height: 140px; }
        }
        @media (min-width: 992px) {
            .category-grid .card-img-top { height: 110px; padding: 0.5rem; }
            .category-grid .category-img-placeholder { min-height: 110px; }
        }
    </style>
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Categories</li>
        </ol>
    </nav>

    <h1 class="mb-4">Category</h1>

    <?php if(!empty($categories)) : ?>
        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3 g-md-4 category-grid">
            <?php foreach($categories as $category) : ?>
                <?php if((isset($category['status']) ? (int)$category['status'] : 0) == 1) : ?>
                    <?php
                    $catId = isset($category['id']) ? (int)$category['id'] : 0;
                    $catName = isset($category['name']) ? htmlspecialchars((string)$category['name']) : '';
                    $catImage = isset($category['image']) ? trim((string)$category['image']) : '';
                    $catDesc = isset($category['description']) ? $category['description'] : '';
                    $children = isset($category['children']) && is_array($category['children']) ? $category['children'] : [];
                    ?>
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm">
                            <?php if(!empty($catImage)) : ?>
                                <img src="<?php echo htmlspecialchars(BASE_URL . $catImage); ?>" class="card-img-top" alt="<?php echo $catName; ?>">
                            <?php else : ?>
                                <div class="bg-light p-4 text-center category-img-placeholder">
                                    <i class="fas fa-tags fa-4x text-secondary"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $catName; ?></h5>
                                <?php if(!empty($catDesc)) : ?>
                                    <p class="card-text"><?php echo htmlspecialchars(truncateText($catDesc, 100)); ?></p>
                                <?php endif; ?>
                                
                                <?php if(!empty($children)) : ?>
                                    <div class="mt-3">
                                        <p class="fw-bold mb-2">Subcategories:</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <?php foreach($children as $child) : ?>
                                                <?php $childId = isset($child['id']) ? (int)$child['id'] : 0; $childName = isset($child['name']) ? htmlspecialchars((string)$child['name']) : ''; ?>
                                                <?php if($childId) : ?>
                                                <a href="<?php echo BASE_URL; ?>?controller=category&action=show&param=<?php echo $childId; ?>" class="badge bg-light text-dark text-decoration-none p-2"><?php echo $childName; ?></a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="<?php echo BASE_URL; ?>?controller=category&action=show&param=<?php echo $catId; ?>" class="btn btn-outline-dark w-100">Browse Products</a>
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
