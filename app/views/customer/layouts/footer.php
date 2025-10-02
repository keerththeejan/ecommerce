</main>

<!-- Footer -->
<style>
    /* Footer Styles */
    html, body {
        height: 100%;
        margin: 0;
        scroll-behavior: smooth;
    }
    
    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }
    
    main {
        flex: 1 0 auto;
    }
    
    /* Modern Footer */
    footer {
        background: linear-gradient(135deg, #222831 0%, #393E46 100%);
        color: #fff;
        padding: 0;
        position: relative;
        z-index: 10;
    }

    /* Constrain inner content to align with site layout */
    .max-width-1400 {
        max-width: 1400px;
        margin-left: auto;
        margin-right: auto;
    }
    
    /* Footer Top Section with Wave */
    .footer-top {
        position: relative;
        padding: 100px 0 70px;
        background: linear-gradient(135deg, #222831 0%, #393E46 100%);
        overflow: hidden;
    }
    
    .footer-top::before {
        content: '';
        position: absolute;
        top: -70px;
        left: 0;
        right: 0;
        height: 70px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23222831' fill-opacity='1' d='M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,149.3C960,160,1056,160,1152,138.7C1248,117,1344,75,1392,53.3L1440,32L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3C/svg%3E");
        background-size: cover;
        background-repeat: no-repeat;
    }
    
    /* Decorative elements */
    .footer-top::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(0, 173, 181, 0.05);
        top: -150px;
        right: -100px;
        z-index: 0;
    }
    
    .footer-decoration {
        position: absolute;
        z-index: 0;
    }
    
    .footer-decoration.circle-1 {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(0, 173, 181, 0.03);
        bottom: -100px;
        left: 10%;
    }
    
    .footer-decoration.circle-2 {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.02);
        top: 20%;
        right: 20%;
    }
    
    /* Store Info */
    .footer-widget {
        margin-bottom: 30px;
        position: relative;
        z-index: 1;
    }
    
    .footer-widget h4 {
        color: #fff;
        margin-bottom: 20px;
        font-size: 1.25rem;
        font-weight: 600;
        position: relative;
        padding-bottom: 15px;
        display: inline-block;
    }
    
    .footer-widget h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #00ADB5 0%, rgba(0, 173, 181, 0.2) 100%);
        border-radius: 3px;
    }
    
    .footer-widget h4::before {
        content: '';
        position: absolute;
        width: 8px;
        height: 8px;
        background: #00ADB5;
        border-radius: 50%;
        left: -15px;
        top: 50%;
        transform: translateY(-50%);
    }
    
    .footer-widget p {
        margin-bottom: 0.75rem;
        color: #EEEEEE;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    .contact-info {
        padding-left: 0;
        list-style: none;
        margin-bottom: 0;
    }
    
    .contact-info li {
        position: relative;
        padding-left: 30px;
        margin-bottom: 15px;
        color: #EEEEEE;
    }
    
    .contact-info i {
        position: absolute;
        left: 0;
        top: 4px;
        color: #00ADB5;
    }
    
    /* Footer Links */
    .footer-links {
        padding-left: 0;
        list-style: none;
    }
    
    .footer-links li {
        margin-bottom: 12px;
    }
    
    .footer-links a {
        color: #EEEEEE;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        padding-left: 15px;
        display: block;
        font-size: 0.95rem;
    }
    
    .footer-links a:before {
        content: '→';
        position: absolute;
        left: 0;
        top: 0;
        color: #00ADB5;
        transition: transform 0.3s ease;
    }
    
    .footer-links a:hover {
        color: #00ADB5;
        transform: translateX(5px);
    }
    
    .footer-links a:hover:before {
        transform: translateX(3px);
    }
    
    /* Social Icons */
    .social-links {
        margin-top: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .social-links a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        transition: all 0.3s ease;
        font-size: 1.1rem;
        color: #EEEEEE;
        position: relative;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .social-links a::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #00ADB5;
        transform: translateY(100%);
        transition: all 0.3s ease;
        z-index: -1;
    }
    
    .social-links a:hover {
        color: #fff;
        transform: translateY(-5px);
    }
    
    .social-links a:hover::before {
        transform: translateY(0);
    }
    
    /* Newsletter Form */
    .newsletter-form {
        position: relative;
        margin-top: 25px;
        max-width: 100%;
    }
    
    .newsletter-form input {
        height: 55px;
        border-radius: 50px;
        padding-left: 25px;
        padding-right: 65px;
        border: none;
        background: rgba(255, 255, 255, 0.08);
        color: #fff;
        width: 100%;
        font-size: 0.95rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .newsletter-form input:focus {
        background: rgba(255, 255, 255, 0.12);
        outline: none;
        box-shadow: 0 5px 20px rgba(0, 173, 181, 0.2);
    }
    
    .newsletter-form input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .newsletter-form button {
        position: absolute;
        right: 5px;
        top: 5px;
        height: 45px;
        width: 45px;
        border-radius: 50%;
        background: linear-gradient(135deg, #00ADB5 0%, #00858c 100%);
        border: none;
        color: #fff;
        transition: all 0.3s ease;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
    }
    
    .newsletter-form button:hover {
        background: linear-gradient(135deg, #00c2cc 0%, #00ADB5 100%);
        transform: translateY(-2px);
        box-shadow: 0 7px 15px rgba(0, 0, 0, 0.25);
    }
    
    /* Copyright Section */
    .footer-bottom {
        background: #1A1D24;
        padding: 20px 0;
        position: relative;
        overflow: hidden;
    }
    
    .footer-bottom-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        position: relative;
        z-index: 1;
    }
    
    .copyright-text {
        font-size: 0.9rem;
        color: #EEEEEE;
        margin-bottom: 0;
    }
    
    .footer-bottom-links {
        display: flex;
        gap: 20px;
    }
    
    .footer-bottom-links a {
        color: #EEEEEE;
        font-size: 0.9rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .footer-bottom-links a:hover {
        color: #00ADB5;
    }
    
    .footer-bottom::before {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(0, 173, 181, 0.03);
        bottom: -100px;
        left: 10%;
    }
    
    .footer-bottom::after {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.02);
        top: -75px;
        right: 10%;
    }
    
    @media (max-width: 767px) {
        .footer-top { padding: 56px 0 34px; }
        .footer-bottom { padding: 14px 0; }
        .footer-widget h4 { font-size: 1.1rem; margin-bottom: 12px; }
        .footer-widget p { font-size: 0.95rem; }
        .footer-links a { font-size: 0.95rem; }
        .footer-bottom-content {
            justify-content: center;
            text-align: center;
        }
    }
    
    /* Mobile spacing optimizations */
    @media (max-width: 576px) {
        .footer-top { padding: 36px 0 28px; }
        .footer-bottom { padding: 16px 0; }
        .footer-widget h4 { margin-bottom: 14px; }
        .footer-widget p { line-height: 1.45; margin-bottom: 0.5rem; }
        .footer-links li { margin-bottom: 8px; }
        .contact-info li { margin-bottom: 8px; }
        .social-links { margin-top: 16px; gap: 10px; }
        .newsletter-form { margin-top: 16px; }
        .newsletter-form input { height: 48px; }
        .newsletter-form button { height: 40px; width: 40px; top: 4px; right: 4px; }
    }

    /* Ensure sections like the footer can span full viewport width across all pages */
    .full-width-section {
        width: 100vw;
        position: relative;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -50vw;
        overflow: hidden;
        background-color: transparent;
    }
</style>

<footer class="full-width-section">
    <!-- Footer Top with Wave Effect -->
    <div class="footer-top">
        <!-- Decorative elements -->
        <div class="footer-decoration circle-1"></div>
        <div class="footer-decoration circle-2"></div>
        
        <div class="container-fluid px-4 px-xl-5 max-width-1400">
            <div class="row gx-2 gx-md-4 gy-4">
                <!-- About Store Widget -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <?php
                        // Get the latest About Store entry
                        $aboutTitle = 'About Our Store';
                        $aboutContent = 'Your one-stop shop for quality products. We offer the best deals and fast delivery to your doorstep with a satisfaction guarantee on all purchases.';
                        
                        // Check if global DB connection exists
                        if (isset($GLOBALS['db'])) {
                            try {
                                // Include the model
                                require_once APP_PATH . 'models/AboutStore.php';
                                
                                // Create model instance with global DB connection
                                $aboutStore = new AboutStore($GLOBALS['db']);
                                $aboutEntries = $aboutStore->getAll();
                                
                                if (!empty($aboutEntries[0])) {
                                    $aboutTitle = htmlspecialchars($aboutEntries[0]['title']);
                                    $aboutContent = $aboutEntries[0]['content'];
                                }
                            } catch (Exception $e) {
                                // Log error but don't break the page
                                error_log('Error loading about store content: ' . $e->getMessage());
                            }
                        }
                        ?>
                        <h4><a href="<?php echo BASE_URL; ?>?controller=about&action=index" style="color: inherit; text-decoration: none;"><?php echo $aboutTitle; ?></a></h4>
                        <div class="about-content">
                            <?php 
                            // Display first 150 characters of content with proper HTML formatting
                            $shortContent = strip_tags($aboutContent);
                            $shortContent = strlen($shortContent) > 150 ? substr($shortContent, 0, 150) . '...' : $shortContent;
                            echo $shortContent; 
                            ?>
                        </div>
                      
                        <div class="social-links">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-instagram"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links Widget -->
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="footer-widget">
                        <h4>Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo BASE_URL; ?>#banner">Home Banner</a></li>
                            <li><a href="<?php echo BASE_URL; ?>#categories-heading">Categories</a></li>
                            <li><a href="<?php echo BASE_URL; ?>#featured-products">Featured Products</a></li>
                            <li><a href="<?php echo BASE_URL; ?>#brands">Brands</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Contact Info Widget -->
                <div class="col-lg-3 col-md-6 col-6">
                    <div class="footer-widget">
                        <h4>Contact Info</h4>
                        <?php
                        $ci = null;
                        try {
                            require_once APP_PATH . 'models/ContactInfo.php';
                            $ciModel = new ContactInfo();
                            $ci = $ciModel->getLatest();
                        } catch (Exception $e) {
                            error_log('Footer contact info load failed: ' . $e->getMessage());
                        }
                        ?>
                        <ul class="contact-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <?php echo $ci && !empty($ci['address']) ? nl2br(htmlspecialchars($ci['address'])) : 'Address not set'; ?>
                            </li>
                            <li>
                                <i class="fas fa-phone"></i>
                                <?php if ($ci && !empty($ci['phone'])): ?>
                                    <a href="tel:<?php echo htmlspecialchars($ci['phone']); ?>" class="text-decoration-none text-light"><?php echo htmlspecialchars($ci['phone']); ?></a>
                                <?php else: ?>
                                    <span class="text-muted">Phone not set</span>
                                <?php endif; ?>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <?php if ($ci && !empty($ci['email'])): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($ci['email']); ?>" class="text-decoration-none text-light"><?php echo htmlspecialchars($ci['email']); ?></a>
                                <?php else: ?>
                                    <span class="text-muted">Email not set</span>
                                <?php endif; ?>
                            </li>
                            <li>
                                <i class="fas fa-clock"></i>
                                <?php echo $ci && !empty($ci['hours_weekdays']) ? htmlspecialchars($ci['hours_weekdays']) : 'Mon - Fri: 9:00 AM - 8:00 PM'; ?><br>
                                <?php echo $ci && !empty($ci['hours_weekends']) ? htmlspecialchars($ci['hours_weekends']) : 'Sat - Sun: 10:00 AM - 6:00 PM'; ?>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Newsletter Widget -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <?php
                        // Newsletter widget settings with defaults
                        $newsletterTitle = 'Newsletter';
                        $newsletterDesc = 'Subscribe to our newsletter to get exclusive updates about our latest products, special offers, and seasonal discounts.';

                        // Try load from settings if available
                        try {
                            require_once APP_PATH . 'models/Setting.php';
                            $settingModel = new Setting();
                            $titleVal = $settingModel->getSetting('newsletter_title', $newsletterTitle);
                            $descVal  = $settingModel->getSetting('newsletter_description', $newsletterDesc);
                            if (!empty($titleVal)) { $newsletterTitle = $titleVal; }
                            if (!empty($descVal))  { $newsletterDesc  = $descVal; }
                        } catch (Exception $e) {
                            // Ignore and keep defaults
                            error_log('Footer newsletter settings load failed: ' . $e->getMessage());
                        }
                        ?>
                        <h4><?php echo htmlspecialchars($newsletterTitle); ?></h4>
                        <p><?php echo htmlspecialchars($newsletterDesc); ?></p>
                        <form class="newsletter-form" method="post" action="<?php echo BASE_URL; ?>?controller=newsletter&action=subscribe">
                            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token']) : ''; ?>">
                            <input type="email" name="email" placeholder="Your Email Address" required>
                            <button type="submit" aria-label="Subscribe"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom / Copyright -->
    <div class="footer-bottom">
        <div class="container-fluid px-4 px-xl-5 max-width-1400">
            <div class="footer-bottom-content">
                <p class="copyright-text">&copy; <?php echo date('Y'); ?> E-Store. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="<?php echo BASE_URL; ?>?controller=page&action=privacy">Privacy Policy</a>
                    <a href="<?php echo BASE_URL; ?>?controller=page&action=terms">Terms of Service</a>
                    <a href="<?php echo BASE_URL; ?>?controller=page&action=faq">FAQ</a>
                </div>
            </div>
        </div>
    </div>
</footer>

    <!-- Policy Modal -->
    <style>
      /* Content normalization */
      .policy-content { white-space: pre-wrap; font-size: 0.95rem; line-height: 1.65; }
      .policy-content * { font-size: inherit !important; line-height: inherit !important; }
      .policy-content h1 { font-size: 1.35rem !important; }
      .policy-content h2 { font-size: 1.15rem !important; }
      .policy-content h3 { font-size: 1.05rem !important; }
      .policy-content h4 { font-size: 1rem !important; }
      .policy-content p, .policy-content li { margin-bottom: 0.5rem; }
      .policy-content img, .policy-content video { max-width: 100%; height: auto; }

      /* Modal refinements */
      /* Right-side slide-in panel */
      .policy-modal .modal-dialog { max-width: 560px; width: min(560px, 95vw); }
      .policy-modal .modal-dialog.modal-right { margin: 0 0 0 auto; height: 100%; transform: translateX(100%); }
      .policy-modal.show .modal-dialog.modal-right { transform: translateX(0); transition: transform 0.32s ease-in-out; }
      .modal.fade .modal-dialog.modal-right { transition: transform 0.32s ease-in-out; }
      .policy-modal .modal-content { height: 100vh; border-top-left-radius: 14px; border-bottom-left-radius: 14px; border-top-right-radius: 0; border-bottom-right-radius: 0; box-shadow: -12px 0 40px rgba(0,0,0,0.25); }
      .policy-modal .modal-header { padding: 0.7rem 1.1rem; border-bottom: 1px solid #eee; }
      .policy-modal .modal-body { padding: 0.85rem 1.1rem; height: calc(100vh - 6.2rem); overflow-y: auto; }
      .policy-modal .modal-footer { padding: 0.55rem 1.1rem; border-top: 1px solid #eee; }
      .policy-modal .modal-title { font-weight: 600; }
      .policy-modal .policy-content ol, .policy-modal .policy-content ul { padding-left: 1.2rem; }
      @media (max-width: 576px) {
        .policy-modal .modal-dialog { margin: 0; max-width: 100%; }
        .policy-modal .modal-dialog.modal-right { width: 100%; }
        .policy-modal .modal-content { border-radius: 0; height: 100vh; }
        .policy-modal .modal-body { height: calc(100vh - 6.2rem); }
      }
    </style>
    <div class="modal fade policy-modal" id="policyModal" tabindex="-1" aria-labelledby="policyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-right">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="policyModalLabel">Policy</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div id="policyModalBody" class="policy-content"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap and Dependencies -->
    <script>
    // No need for footer padding adjustment as the footer is not fixed anymore
    </script>
    <!-- jQuery (required by our custom main.js) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Global config for JS -->
    <script>
        window.baseUrl = '<?php echo BASE_URL; ?>';
    </script>
    
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>
    
    <!-- Quantity Adjuster Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Intercept footer policy links and open in modal
        const footerLinks = document.querySelectorAll('.footer-bottom-links a[href*="?controller=page&action="]');
        const policyModalEl = document.getElementById('policyModal');
        let policyModal;
        if (policyModalEl) {
            policyModal = new bootstrap.Modal(policyModalEl, { backdrop: true, keyboard: true, focus: true });
        }

        footerLinks.forEach(function(link){
            link.addEventListener('click', function(e){
                e.preventDefault();
                const url = this.getAttribute('href');
                // Show loading state
                document.getElementById('policyModalLabel').textContent = 'Loading...';
                document.getElementById('policyModalBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';
                if (policyModal) { policyModal.show(); }

                // Fetch content via AJAX expecting JSON from PageController
                fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' }})
                    .then(async (res) => {
                        const text = await res.text();
                        try {
                            return JSON.parse(text);
                        } catch (err) {
                            // Fallback if server returned HTML
                            return { title: 'Information', content: text };
                        }
                    })
                    .then((data) => {
                        document.getElementById('policyModalLabel').textContent = data.title || 'Information';
                        document.getElementById('policyModalBody').innerHTML = data.content || '<div class="alert alert-info mb-0">No content available.</div>';
                    })
                    .catch(() => {
                        document.getElementById('policyModalLabel').textContent = 'Error';
                        document.getElementById('policyModalBody').innerHTML = '<div class="alert alert-danger">Failed to load content. Please try again.</div>';
                    });
            });
        });

        // Quantity adjuster functionality
        document.addEventListener('click', function(e) {
            // Handle quantity increase
            if (e.target.classList.contains('quantity-increase')) {
                const input = e.target.closest('.input-group').querySelector('.quantity-input');
                const max = parseInt(input.getAttribute('max'));
                let value = parseInt(input.value) || 0;
                if (value < max) {
                    input.value = value + 1;
                }
            }
            
            // Handle quantity decrease
            if (e.target.classList.contains('quantity-decrease')) {
                const input = e.target.closest('.input-group').querySelector('.quantity-input');
                const min = parseInt(input.getAttribute('min'));
                let value = parseInt(input.value) || 1;
                if (value > min) {
                    input.value = value - 1;
                }
            }
        });
        
        // Prevent form submission when clicking on quantity buttons
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('click', function(e) {
                if (e.target.classList.contains('quantity-increase') || e.target.classList.contains('quantity-decrease')) {
                    e.preventDefault();
                }
            });
        });
    });
    </script>
</body>
</html>
