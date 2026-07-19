</main>

<?php
// Load footer typography settings
try {
    require_once APP_PATH . 'models/Setting.php';
    $__settingModel = new Setting();
    $__footerTextColor = $__settingModel->getSetting('footer_text_color', '#EEEEEE');
    $__footerFontSize = $__settingModel->getSetting('footer_font_size', '0.95rem');
    $__footerFontFamily = $__settingModel->getSetting('footer_font_family', 'inherit');
    $__footerAccentColor = $__settingModel->getSetting('footer_accent_color', '#00ADB5');
    $__footerHeadingColor = $__settingModel->getSetting('footer_heading_color', '#FFFFFF');
    $__footerHeadingAbout = $__settingModel->getSetting('footer_heading_about', 'About store');
    $__footerHeadingQuickLinks = $__settingModel->getSetting('footer_heading_quick_links', 'Quick Links');
    $__footerHeadingContactInfo = $__settingModel->getSetting('footer_heading_contact_info', 'Contact Info');
    $__footerHeadingNewsletter = $__settingModel->getSetting('footer_heading_newsletter', 'Newsletter');
    $__footerBottomText = $__settingModel->getSetting('footer_bottom_text', 'E-Store. All rights reserved.');
    $__footerBottomLinkPrivacy = $__settingModel->getSetting('footer_bottom_link_privacy', 'Privacy Policy');
    $__footerBottomLinkTerms = $__settingModel->getSetting('footer_bottom_link_terms', 'Terms of Service');
    $__footerBottomLinkFaq = $__settingModel->getSetting('footer_bottom_link_faq', 'FAQ');
    $__footerBottomTextColor = $__settingModel->getSetting('footer_bottom_text_color', '#FFFFFF');
    $__footerBottomLinkColor = $__settingModel->getSetting('footer_bottom_link_color', '#EEEEEE');
    $__footerBottomLinkHoverColor = $__settingModel->getSetting('footer_bottom_link_hover_color', '#00ADB5');
} catch (Exception $e) {
    $__footerTextColor = '#EEEEEE';
    $__footerFontSize = '0.95rem';
    $__footerFontFamily = 'inherit';
    $__footerAccentColor = '#00ADB5';
    $__footerHeadingColor = '#FFFFFF';
    $__footerHeadingAbout = 'About store';
    $__footerHeadingQuickLinks = 'Quick Links';
    $__footerHeadingContactInfo = 'Contact Info';
    $__footerHeadingNewsletter = 'Newsletter';
    $__footerBottomText = 'E-Store. All rights reserved.';
    $__footerBottomLinkPrivacy = 'Privacy Policy';
    $__footerBottomLinkTerms = 'Terms of Service';
    $__footerBottomLinkFaq = 'FAQ';
    $__footerBottomTextColor = '#FFFFFF';
    $__footerBottomLinkColor = '#EEEEEE';
    $__footerBottomLinkHoverColor = '#00ADB5';
}
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/footer.css?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>">
<style>
    /* Admin-configurable footer tokens (preserve settings system) */
    footer.premium-footer {
        --ft-accent: <?php echo htmlspecialchars($__footerAccentColor); ?>;
        --ft-heading: <?php echo htmlspecialchars($__footerHeadingColor); ?>;
        --ft-text: <?php echo htmlspecialchars($__footerTextColor); ?>;
    }
    footer.premium-footer,
    footer.premium-footer * {
        font-family: <?php echo ($__footerFontFamily === 'inherit' || $__footerFontFamily === '') ? "'Poppins', system-ui, sans-serif" : htmlspecialchars($__footerFontFamily); ?> !important;
    }
    footer.premium-footer .footer-widget p,
    footer.premium-footer .about-content,
    footer.premium-footer .footer-links a,
    footer.premium-footer .contact-info li {
        font-size: <?php echo htmlspecialchars($__footerFontSize); ?> !important;
    }
    footer.premium-footer .copyright-text {
        color: <?php echo htmlspecialchars($__footerBottomTextColor); ?> !important;
    }
    footer.premium-footer .footer-bottom-links a {
        color: <?php echo htmlspecialchars($__footerBottomLinkColor); ?> !important;
    }
    footer.premium-footer .footer-bottom-links a:hover {
        color: <?php echo htmlspecialchars($__footerBottomLinkHoverColor); ?> !important;
    }
</style>

<?php
$__siteLogo = '';
$__siteName = defined('SITE_NAME') ? SITE_NAME : 'Sivakamy';
try {
    if (!isset($__settingModel)) {
        require_once APP_PATH . 'models/Setting.php';
        $__settingModel = new Setting();
    }
    $__siteLogo = $__settingModel->getSetting('site_logo');
    $__sn = $__settingModel->getSetting('site_name');
    if (!empty($__sn)) $__siteName = $__sn;
} catch (Exception $e) {
    // keep defaults
}
?>

<footer class="full-width-section premium-footer" role="contentinfo">
    <div class="footer-main">
        <div class="container-fluid px-3 px-lg-4 max-width-1400">

            <!-- Row 1: Brand + link columns -->
            <div class="row g-4 footer-row">
                <!-- Brand -->
                <div class="col-12 col-md-6 col-xl">
                    <div class="footer-widget">
                        <?php
                        $aboutContent = 'Your one-stop shop for quality products with fast delivery and trusted service.';
                        if (isset($GLOBALS['db'])) {
                            try {
                                require_once APP_PATH . 'models/AboutStore.php';
                                $aboutStore = new AboutStore($GLOBALS['db']);
                                $aboutEntries = $aboutStore->getAll();
                                if (!empty($aboutEntries[0]['content'])) {
                                    $aboutContent = $aboutEntries[0]['content'];
                                }
                            } catch (Exception $e) {
                                error_log('Error loading about store content: ' . $e->getMessage());
                            }
                        }
                        ?>
                        <a class="footer-brand-logo" href="<?php echo BASE_URL; ?>" aria-label="<?php echo htmlspecialchars($__siteName); ?>">
                            <?php if (!empty($__siteLogo)): ?>
                                <img src="<?php echo BASE_URL . 'uploads/' . htmlspecialchars($__siteLogo); ?>" alt="<?php echo htmlspecialchars($__siteName); ?>" loading="lazy" width="140" height="40">
                            <?php else: ?>
                                <span class="brand-text"><?php echo htmlspecialchars($__siteName); ?></span>
                            <?php endif; ?>
                        </a>
                        <p class="about-content">
                            <?php
                            $short = strip_tags($aboutContent);
                            echo htmlspecialchars(strlen($short) > 110 ? substr($short, 0, 110) . '…' : $short);
                            ?>
                        </p>
                        <div class="footer-trust" aria-label="Trust badges">
                            <span title="SSL Secure"><i class="bi bi-shield-lock" aria-hidden="true"></i></span>
                            <span title="100% Genuine"><i class="bi bi-award" aria-hidden="true"></i></span>
                            <span title="Fast Delivery"><i class="bi bi-truck" aria-hidden="true"></i></span>
                            <span title="Easy Returns"><i class="bi bi-arrow-repeat" aria-hidden="true"></i></span>
                        </div>
                        <div class="social-links" aria-label="Social media">
                            <a href="#" aria-label="Facebook"><i class="bi bi-facebook" aria-hidden="true"></i></a>
                            <a href="#" aria-label="Instagram"><i class="bi bi-instagram" aria-hidden="true"></i></a>
                            <a href="#" aria-label="YouTube"><i class="bi bi-youtube" aria-hidden="true"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin" aria-hidden="true"></i></a>
                            <a href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Customer Service -->
                <div class="col-6 col-md-3 col-xl">
                    <div class="footer-widget">
                        <h4>Customer Service</h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo BASE_URL; ?>?controller=page&action=faq">Help Center</a></li>
                            <li><a href="<?php echo BASE_URL; ?>?controller=order&action=history">Track Order</a></li>
                            <li><a href="<?php echo BASE_URL; ?>?controller=page&action=faq">Returns</a></li>
                            <li><a href="<?php echo BASE_URL; ?>?controller=page&action=faq"><?php echo htmlspecialchars($__footerBottomLinkFaq); ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- Company -->
                <div class="col-6 col-md-3 col-xl">
                    <div class="footer-widget">
                        <h4>Company</h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo BASE_URL; ?>?controller=about&action=index">About</a></li>
                            <li><a href="<?php echo BASE_URL; ?>?controller=contact">Careers</a></li>
                            <li><a href="<?php echo BASE_URL; ?>?controller=page&action=privacy"><?php echo htmlspecialchars($__footerBottomLinkPrivacy); ?></a></li>
                            <li><a href="<?php echo BASE_URL; ?>?controller=page&action=terms"><?php echo htmlspecialchars($__footerBottomLinkTerms); ?></a></li>
                        </ul>
                    </div>
                </div>

                <!-- Shop -->
                <div class="col-6 col-md-6 col-xl">
                    <div class="footer-widget">
                        <h4>Shop</h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo BASE_URL; ?>#categories-heading">Categories</a></li>
                            <li><a href="<?php echo BASE_URL; ?>#brands">Brands</a></li>
                            <li><a href="<?php echo BASE_URL; ?>?controller=product&action=index">Offers</a></li>
                            <li><a href="<?php echo BASE_URL; ?>#featured-products">Best Sellers</a></li>
                            <li><a href="<?php echo BASE_URL; ?>?controller=product&action=index">Flash Deals</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Contact -->
                <div class="col-6 col-md-6 col-xl">
                    <div class="footer-widget">
                        <h4><?php echo htmlspecialchars($__footerHeadingContactInfo); ?></h4>
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
                                <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                                <?php if ($ci && !empty($ci['phone'])): ?>
                                    <a href="tel:<?php echo htmlspecialchars($ci['phone']); ?>"><?php echo htmlspecialchars($ci['phone']); ?></a>
                                <?php else: ?>
                                    <span>—</span>
                                <?php endif; ?>
                            </li>
                            <li>
                                <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                                <?php if ($ci && !empty($ci['email'])): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($ci['email']); ?>"><?php echo htmlspecialchars($ci['email']); ?></a>
                                <?php else: ?>
                                    <span>—</span>
                                <?php endif; ?>
                            </li>
                            <li>
                                <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                                <span><?php echo $ci && !empty($ci['address']) ? nl2br(htmlspecialchars($ci['address'])) : '—'; ?></span>
                            </li>
                            <li>
                                <i class="bi bi-clock-fill" aria-hidden="true"></i>
                                <span>
                                    <?php echo $ci && !empty($ci['hours_weekdays']) ? htmlspecialchars($ci['hours_weekdays']) : 'Mon – Fri: 9:00 – 18:00'; ?>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Newsletter full width -->
            <div class="footer-newsletter-bar">
                <?php
                $newsletterDesc = 'Subscribe for exclusive offers and new arrivals.';
                try {
                    require_once APP_PATH . 'models/Setting.php';
                    $settingModel = new Setting();
                    $descVal = $settingModel->getSetting('newsletter_description', $newsletterDesc);
                    if (!empty($descVal)) { $newsletterDesc = $descVal; }
                } catch (Exception $e) {
                    error_log('Footer newsletter settings load failed: ' . $e->getMessage());
                }
                ?>
                <div class="footer-newsletter-inner">
                    <div class="footer-newsletter-copy">
                        <h4><?php echo htmlspecialchars($__footerHeadingNewsletter); ?></h4>
                        <p><?php echo htmlspecialchars($newsletterDesc); ?></p>
                    </div>
                    <form class="newsletter-form" id="newsletter-form" method="post" action="<?php echo BASE_URL; ?>?controller=newsletter&action=subscribe">
                        <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token']) : ''; ?>">
                        <input type="email" name="email" id="newsletter-email" placeholder="Your email address" required autocomplete="email" aria-label="Email address for newsletter">
                        <button type="submit" aria-label="Subscribe to newsletter">
                            <i class="bi bi-send-fill btn-icon" aria-hidden="true"></i>
                            <span class="btn-spinner spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <span class="btn-label">Subscribe</span>
                        </button>
                    </form>
                    <div id="newsletter-result" aria-live="polite"></div>
                </div>
            </div>

            <!-- Payment logos -->
            <div class="footer-pay-strip" aria-label="Payment methods">
                <span class="footer-pay-badge" title="Visa"><svg viewBox="0 0 48 16" width="42" height="14" aria-hidden="true"><rect width="48" height="16" rx="3" fill="#1A1F71"/><text x="24" y="11.5" text-anchor="middle" fill="#fff" font-size="7" font-weight="700" font-family="Arial">VISA</text></svg></span>
                <span class="footer-pay-badge" title="Mastercard"><svg viewBox="0 0 48 16" width="42" height="14" aria-hidden="true"><circle cx="18" cy="8" r="6" fill="#EB001B"/><circle cx="30" cy="8" r="6" fill="#F79E1B"/></svg></span>
                <span class="footer-pay-badge" title="PayPal"><svg viewBox="0 0 48 16" width="42" height="14" aria-hidden="true"><rect width="48" height="16" rx="3" fill="#003087"/><text x="24" y="11" text-anchor="middle" fill="#fff" font-size="6" font-weight="700" font-family="Arial">PayPal</text></svg></span>
                <span class="footer-pay-badge" title="Stripe"><svg viewBox="0 0 48 16" width="42" height="14" aria-hidden="true"><rect width="48" height="16" rx="3" fill="#635BFF"/><text x="24" y="11" text-anchor="middle" fill="#fff" font-size="6.5" font-weight="700" font-family="Arial">Stripe</text></svg></span>
                <span class="footer-pay-badge" title="Apple Pay"><svg viewBox="0 0 48 16" width="42" height="14" aria-hidden="true"><rect width="48" height="16" rx="3" fill="#111"/><text x="24" y="11" text-anchor="middle" fill="#fff" font-size="5.5" font-weight="600" font-family="Arial"> Pay</text></svg></span>
                <span class="footer-pay-badge" title="Google Pay"><svg viewBox="0 0 48 16" width="42" height="14" aria-hidden="true"><rect width="48" height="16" rx="3" fill="#fff"/><text x="24" y="11" text-anchor="middle" fill="#3c4043" font-size="5.5" font-weight="700" font-family="Arial">G Pay</text></svg></span>
            </div>
        </div>
    </div>

    <!-- Bottom bar -->
    <div class="footer-bottom">
        <div class="container-fluid px-3 px-lg-4 max-width-1400">
            <div class="footer-bottom-content">
                <p class="copyright-text">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($__siteName); ?>. All Rights Reserved.</p>
                <div class="footer-bottom-links">
                    <a href="<?php echo BASE_URL; ?>?controller=page&action=privacy"><?php echo htmlspecialchars($__footerBottomLinkPrivacy); ?></a>
                    <a href="<?php echo BASE_URL; ?>?controller=page&action=terms"><?php echo htmlspecialchars($__footerBottomLinkTerms); ?></a>
                    <a href="<?php echo BASE_URL; ?>?controller=page&action=privacy">Cookies</a>
                    <a href="<?php echo BASE_URL; ?>">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    footer.full-width-section { margin-bottom: -2px; }
    footer .footer-bottom { margin-bottom: -2px; }
</style>

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
    <!-- Bootstrap 5 JS Bundle with Popper - Latest Stable -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Global config for JS -->
    <script>
        window.baseUrl = '<?php echo BASE_URL; ?>';
        window.isLoggedIn = <?php echo isLoggedIn() ? 'true' : 'false'; ?>;
        window.wishlistProductIds = <?php echo json_encode(array_values(wishlist_product_ids())); ?>;
        window.loginUrl = '<?php echo rtrim(BASE_URL, '/'); ?>/?controller=user&action=login';
    </script>
    
    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/main.js?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/wishlist.js?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/navbar.js?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>"></script>
    <script src="<?php echo BASE_URL; ?>assets/js/home.js?v=<?php echo defined('ASSET_VERSION') ? ASSET_VERSION : time(); ?>"></script>
    
    <!-- Quantity Adjuster Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Newsletter loading spinner (UI only â€” does not alter subscribe AJAX in main.js)
        if (window.jQuery) {
            jQuery(document).on('submit', '#newsletter-form', function () {
                jQuery(this).addClass('is-loading');
            });
            jQuery(document).ajaxComplete(function (_e, _xhr, settings) {
                if (settings && settings.url && String(settings.url).indexOf('newsletter') !== -1) {
                    jQuery('#newsletter-form').removeClass('is-loading');
                }
            });
        }

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

        // Quantity adjuster functionality - improved with better debugging
        // Use event delegation to catch all clicks on quantity buttons
        document.addEventListener('click', function(e) {
            // Handle clicks on the button itself or any child elements (like icons)
            let targetButton = null;
            
            // Check if click is directly on a quantity button or parent
            const buttonSelectors = ['.qty-plus', '.qty-minus', '.quantity-increase', '.quantity-decrease'];
            for (const selector of buttonSelectors) {
                if (e.target.matches(selector) || e.target.closest(selector)) {
                    targetButton = e.target.matches(selector) ? e.target : e.target.closest(selector);
                    break;
                }
            }
            
            if (!targetButton) {
                return; // Not a quantity button click
            }
            
            // Prevent default and stop propagation
            e.preventDefault();
            e.stopPropagation();
            
            // Determine if increase or decrease
            const isIncrease = targetButton.classList.contains('qty-plus') || 
                              targetButton.classList.contains('quantity-increase');
            
            // Find the input group (try multiple selectors)
            let inputGroup = targetButton.closest('.quantity-group');
            if (!inputGroup) {
                inputGroup = targetButton.closest('.input-group');
            }
            if (!inputGroup) {
                inputGroup = targetButton.closest('.cart-quantity');
            }
            if (!inputGroup) {
                console.warn('Quantity adjuster: Could not find quantity-group, input-group, or cart-quantity');
                return;
            }
            
            // Find the quantity input (try multiple selectors)
            let input = inputGroup.querySelector('input.quantity-input');
            if (!input) {
                input = inputGroup.querySelector('input[name="quantity"]');
            }
            if (!input) {
                console.warn('Quantity adjuster: Could not find quantity input');
                return;
            }
            
            // Get current value and limits
            const max = parseInt(input.getAttribute('max') || input.getAttribute('data-max') || '9999');
            const min = parseInt(input.getAttribute('min') || '1');
            let value = parseInt(input.value) || 1;
            
            // Calculate new value
            let newValue = value;
            if (isIncrease && value < max) {
                newValue = value + 1;
            } else if (!isIncrease && value > min) {
                newValue = value - 1;
            } else {
                // Already at limit, don't change
                return;
            }
            
            // Update value (editable field Ã¢â‚¬â€ no readonly restore)
            input.value = newValue;
            
            // Trigger events
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
            
            if (window.DEBUG_STOREFRONT) {
                console.log('Quantity ' + (isIncrease ? 'increased' : 'decreased') + ' to:', newValue);
            }
        }, true); // Use capture phase for better event handling
        
    });
    </script>

    <!-- Enhanced UX JavaScript -->
    <script>
    (function() {
        'use strict';

        // Page Load Animation
        window.addEventListener('load', function() {
            document.body.classList.remove('loading');
            document.body.classList.add('loaded');
            
            // Fade in sections
            const sections = document.querySelectorAll('section, .card, .product-card, .category-card');
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('fade-in-up');
                            observer.unobserve(entry.target);
                        }, index * 50);
                    }
                });
            }, observerOptions);

            sections.forEach(section => {
                observer.observe(section);
            });
        });

        // Add loading class on page start
        document.body.classList.add('loading');

        // Scroll to Top Button
        const scrollToTopBtn = document.createElement('button');
        scrollToTopBtn.className = 'scroll-to-top';
        scrollToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
        scrollToTopBtn.setAttribute('aria-label', 'Scroll to top');
        scrollToTopBtn.style.display = 'none';
        document.body.appendChild(scrollToTopBtn);

        // Show/hide scroll to top button
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
                scrollToTopBtn.style.display = 'flex';
            } else {
                scrollToTopBtn.classList.remove('visible');
                setTimeout(() => {
                    if (!scrollToTopBtn.classList.contains('visible')) {
                        scrollToTopBtn.style.display = 'none';
                    }
                }, 300);
            }
        });

        // Scroll to top functionality
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Enhanced Image Lazy Loading Ã¢â‚¬â€ always reveal images (no opacity trap)
        (function revealImages() {
            function mark(img) {
                img.classList.add('loaded');
                img.style.opacity = '';
            }
            document.querySelectorAll('img').forEach(function(img) {
                if (img.complete) {
                    mark(img);
                } else {
                    img.addEventListener('load', function() { mark(img); });
                    img.addEventListener('error', function() {
                        if (!img.dataset.fallbackApplied) {
                            img.dataset.fallbackApplied = '1';
                            img.src = (window.baseUrl || '/') + 'assets/img/no-image.png';
                        }
                        mark(img);
                    });
                }
            });
        })();

        // Button Loading State Enhancement
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitBtn && !submitBtn.classList.contains('no-loading')) {
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                }
            });
        });

        // Smooth Scroll for Anchor Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.length > 1) {
                    const target = document.querySelector(href);
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });

        // Enhanced Product Card Interactions
        document.querySelectorAll('.product-card, .category-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.zIndex = '10';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.zIndex = '1';
            });
        });

        // Keyboard Navigation Enhancement (do not hijack Bootstrap dropdowns/menus)
        document.querySelectorAll('.btn:not([data-bs-toggle]), .card a').forEach(element => {
            element.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    if (this.closest('.dropdown-menu') || this.getAttribute('data-bs-toggle')) {
                        return;
                    }
                    if (e.key === ' ') {
                        e.preventDefault();
                    }
                    this.click();
                }
            });
        });

        // Add ripple effect to buttons (skip toggles / dropdowns)
        document.querySelectorAll('.btn:not([data-bs-toggle]):not(.theme-toggle):not(.no-ripple)').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!this.classList.contains('no-ripple')) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = x + 'px';
                    ripple.style.top = y + 'px';
                    ripple.classList.add('ripple');
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                }
            });
        });

        // NOTE: Do NOT globally throttle .btn/form clicks Ã¢â‚¬â€ it breaks Bootstrap dropdowns,
        // add-to-cart, search submit, and theme toggle.

        // Enhanced Carousel Controls
        document.querySelectorAll('.carousel').forEach(carousel => {
            const carouselInstance = bootstrap.Carousel.getInstance(carousel);
            if (carouselInstance) {
                carousel.addEventListener('slide.bs.carousel', function() {
                    // Add smooth transition
                    carousel.style.transition = 'transform 0.6s ease-in-out';
                });
            }
        });

        // Console message for developers
        console.log('%cÃ°Å¸Å¡â‚¬ Enhanced UX Loaded', 'color: #667eea; font-size: 16px; font-weight: bold;');
        console.log('%cSmooth scrolling, animations, and interactive elements are now active.', 'color: #636e72; font-size: 12px;');
    })();
    </script>

    <style>
    /* Ripple Effect */
    .btn {
        position: relative;
        overflow: hidden;
    }

    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    </style>
</body>
</html>
