<?php
// Get active footer sections from the database
$footerSections = [];
$footerModel = new \App\Models\FooterSection();
$footerSections = $footerModel->getActiveSections();
?>

<footer class="bg-dark text-white mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <?php foreach ($footerSections as $section): ?>
                <div class="col-md-3">
                    <h5><?php echo htmlspecialchars($section['title']); ?></h5>
                    <?php if ($section['type'] === 'about'): ?>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($section['content'])); ?></p>
                    <?php elseif ($section['type'] === 'links'): ?>
                        <ul class="list-unstyled">
                            <?php 
                            $links = json_decode($section['content'], true);
                            if (is_array($links)) {
                                foreach ($links as $link): ?>
                                    <li class="mb-2">
                                        <a href="<?php echo htmlspecialchars($link['url']); ?>" class="text-decoration-none text-muted">
                                            <?php echo htmlspecialchars($link['text']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; 
                            }
                            ?>
                        </ul>
                    <?php elseif ($section['type'] === 'contact'): ?>
                        <?php 
                        $contactInfo = json_decode($section['content'], true);
                        if (is_array($contactInfo)): ?>
                            <address class="text-muted">
                                <?php if (!empty($contactInfo['address'])): ?>
                                    <p class="mb-2">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        <?php echo htmlspecialchars($contactInfo['address']); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($contactInfo['phone'])): ?>
                                    <p class="mb-2">
                                        <i class="fas fa-phone me-2"></i>
                                        <a href="tel:<?php echo htmlspecialchars($contactInfo['phone']); ?>" class="text-muted text-decoration-none">
                                            <?php echo htmlspecialchars($contactInfo['phone']); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($contactInfo['email'])): ?>
                                    <p class="mb-2">
                                        <i class="fas fa-envelope me-2"></i>
                                        <a href="mailto:<?php echo htmlspecialchars($contactInfo['email']); ?>" class="text-muted text-decoration-none">
                                            <?php echo htmlspecialchars($contactInfo['email']); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                            </address>
                        <?php endif; ?>
                    <?php elseif ($section['type'] === 'social'): ?>
                        <div class="social-links">
                            <?php 
                            $socialLinks = json_decode($section['content'], true);
                            if (is_array($socialLinks)) {
                                foreach ($socialLinks as $platform => $url): 
                                    if (!empty($url)): ?>
                                        <a href="<?php echo htmlspecialchars($url); ?>" target="_blank" class="text-white me-3" title="<?php echo ucfirst($platform); ?>">
                                            <i class="fab fa-<?php echo htmlspecialchars($platform); ?> fa-lg"></i>
                                        </a>
                                    <?php endif; 
                                endforeach; 
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <hr class="bg-secondary">
        <div class="row">
            <div class="col-12 text-center">
                <p class="mb-0 text-muted">&copy; <?php echo date('Y'); ?> Your E-commerce Store. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
