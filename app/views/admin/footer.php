<?php
// Get the about section data passed from the controller
$aboutSection = $data['about'] ?? [];
$footerSections = $data['sections'] ?? [];
?>

<footer class="bg-light py-4">
    <div class="container">
        <div class="row">
            <!-- About Store Section -->
            <?php if ($aboutSection && $aboutSection['status'] == 1): ?>
            <div class="col-md-4 mb-4">
                <h5 class="mb-3"><?php echo htmlspecialchars($aboutSection['title']); ?></h5>
                <p class="text-muted">
                    <?php echo nl2br(htmlspecialchars($aboutSection['content'])); ?>
                </p>
                <?php if (isset($_SESSION['admin_id'])): ?>
                    <a href="<?php echo URLROOT; ?>/admin/footer/edit_about" class="btn btn-sm btn-outline-primary mt-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Dynamic Footer Sections -->
            <?php foreach ($footerSections as $section): ?>
            <div class="col-md-2 mb-4">
                <h5 class="mb-3"><?php echo htmlspecialchars($section['title']); ?></h5>
                <ul class="list-unstyled">
                    <?php foreach ($section['links'] as $link): ?>
                    <li class="mb-2">
                        <a href="<?php echo htmlspecialchars($link['url']); ?>" class="text-dark text-decoration-none">
                            <?php echo htmlspecialchars($link['text']); ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="row mt-4">
            <div class="col-12 text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> Your Company Name. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>