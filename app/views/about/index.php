<?php require APPROOT . '/views/includes/header.php'; ?>



    <?php if (!empty($about_entries)) : ?>
        <?php foreach ($about_entries as $about) : ?>
            <div class="row align-items-center mb-5">
                <div class="col-lg-6 <?php echo ((int)$about['id']) % 2 === 0 ? 'order-lg-2' : ''; ?> mb-4 mb-lg-0">
                    <?php if (!empty($about['image_path'])) : ?>
                        <img src="<?php echo URLROOT . '/' . ltrim($about['image_path'], '/'); ?>" 
                             alt="<?php echo htmlspecialchars($about['title']); ?>" 
                             class="img-fluid rounded shadow">
                    <?php endif; ?>
                </div>
                <div class="col-lg-6 <?php echo ((int)$about['id']) % 2 === 0 ? 'order-lg-1' : ''; ?>">
                    <h2 class="h3 mb-3"><?php echo htmlspecialchars($about['title']); ?></h2>
                    <div class="about-content">
                        <?php echo $about['content']; ?>
                    </div>
                </div>
            </div>
            
            <?php if ($about !== end($about_entries)) : ?>
                <hr class="my-5">
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else : ?>
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <p class="mb-0">No content available at the moment. Please check back later.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/includes/footer.php'; ?>

<style>
.about-content {
    line-height: 1.8;
    color: #4a5568;
}

.about-content p {
    margin-bottom: 1.5rem;
}

.about-content h2, 
.about-content h3, 
.about-content h4 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    color: #2d3748;
}

.about-content ul, 
.about-content ol {
    padding-left: 1.5rem;
    margin-bottom: 1.5rem;
}

.about-content a {
    color: #4f46e5;
    text-decoration: none;
}

.about-content a:hover {
    text-decoration: underline;
}
</style>
