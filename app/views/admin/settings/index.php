<?php require_once APP_PATH . 'views/admin/layouts/header.php'; ?>

<style>
/* Settings page – responsive */
.settings-page .card-body { padding: 1rem; }
@media (min-width: 768px) { .settings-page .card-body { padding: 1.25rem; } }
.settings-page .nav-tabs { flex-wrap: wrap; }
.settings-page .nav-tabs .nav-link { white-space: nowrap; }
@media (max-width: 575.98px) {
  .settings-page .nav-tabs .nav-item { margin-bottom: 0.25rem; }
}
</style>

<div class="container-fluid py-3 py-md-4 px-2 px-sm-3 settings-page">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-3 mb-md-4">Store Settings</h2>
            <?php flash('setting_success'); ?>
            <?php flash('setting_error', '', 'alert alert-danger'); ?>

            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-header bg-light">
                    <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="general-tab" data-toggle="tab" data-target="#general" type="button" role="tab" aria-controls="general">General</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="store-tab" data-toggle="tab" data-target="#store" type="button" role="tab" aria-controls="store">Store</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="payment-tab" data-toggle="tab" data-target="#payment" type="button" role="tab" aria-controls="payment">Payment</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="email-tab" data-toggle="tab" data-target="#email" type="button" role="tab" aria-controls="email">Email</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="settingsTabsContent">
                        <!-- General Settings -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                            <form action="<?php echo BASE_URL; ?>?controller=setting&action=updateGeneral" method="POST" enctype="multipart/form-data">
                                <div class="row g-2 g-md-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="site_name" class="form-label">Site Name</label>
                                        <input type="text" class="form-control <?php echo isset($errors['site_name']) ? 'is-invalid' : ''; ?>" id="site_name" name="site_name" value="<?php echo isset($generalSettings['site_name']) ? $generalSettings['site_name'] : ''; ?>" required>
                                        <?php if (isset($errors['site_name'])): ?>
                                            <div class="invalid-feedback d-block"><?php echo $errors['site_name']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="site_email" class="form-label">Site Email</label>
                                        <input type="email" class="form-control <?php echo isset($errors['site_email']) ? 'is-invalid' : ''; ?>" id="site_email" name="site_email" value="<?php echo isset($generalSettings['site_email']) ? $generalSettings['site_email'] : ''; ?>" required>
                                        <?php if (isset($errors['site_email'])): ?>
                                            <div class="invalid-feedback d-block"><?php echo $errors['site_email']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="row g-2 g-md-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="site_description" class="form-label">Site Description</label>
                                        <textarea class="form-control" id="site_description" name="site_description" rows="3"><?php echo isset($generalSettings['site_description']) ? htmlspecialchars($generalSettings['site_description']) : ''; ?></textarea>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="site_phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="site_phone" name="site_phone" value="<?php echo isset($generalSettings['site_phone']) ? $generalSettings['site_phone'] : ''; ?>">
                                    </div>
                                </div>
                                <div class="row g-2 g-md-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="site_logo" class="form-label">Site Logo</label>
                                        <?php
                                        $logoFile = !empty($generalSettings['site_logo']) ? $generalSettings['site_logo'] : '';
                                        $logoPath = UPLOAD_PATH . $logoFile;
                                        $logoUrl = !empty($logoFile) ? BASE_URL . 'public/uploads/' . $logoFile : '';
                                        ?>
                                        <?php if (!empty($logoFile) && file_exists($logoPath)): ?>
                                            <div class="mb-2">
                                                <img src="<?php echo $logoUrl; ?>" alt="Current Logo" class="img-thumbnail mb-2" style="max-height: 100px; max-width: 100%;">
                                                <div class="form-check mt-2">
                                                    <input class="form-check-input" type="checkbox" name="remove_logo" id="remove_logo" value="1">
                                                    <label class="form-check-label" for="remove_logo">Remove logo</label>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/jpeg,image/png,image/gif,image/webp">
                                        <input type="hidden" name="current_logo" value="<?php echo !empty($generalSettings['site_logo']) ? htmlspecialchars($generalSettings['site_logo']) : ''; ?>">
                                        <small class="form-text text-muted">JPG, PNG, GIF, WEBP. Max 2MB.</small>
                                    </div>
                                </div>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-12 col-md-6 col-lg-4">
                                <label for="home_categories_bg_color" class="form-label">Home Categories Background</label>
                                <input type="color" class="form-control form-control-color d-block <?php echo isset($errors['home_categories_bg_color']) ? 'is-invalid' : ''; ?>" id="home_categories_bg_color" name="home_categories_bg_color" value="<?php echo isset($generalSettings['home_categories_bg_color']) ? htmlspecialchars($generalSettings['home_categories_bg_color']) : '#ffffff'; ?>" title="Choose color">
                                <?php if (isset($errors['home_categories_bg_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['home_categories_bg_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <label for="header_bg_color" class="form-label">Header Background</label>
                                <input type="color" class="form-control form-control-color d-block <?php echo isset($errors['header_bg_color']) ? 'is-invalid' : ''; ?>" id="header_bg_color" name="header_bg_color" value="<?php echo isset($generalSettings['header_bg_color']) ? htmlspecialchars($generalSettings['header_bg_color']) : '#ffffff'; ?>" title="Choose color">
                                <?php if (isset($errors['header_bg_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['header_bg_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-6 col-lg-4">
                                <label for="header_width" class="form-label">Header Width</label>
                                <select class="form-control <?php echo isset($errors['header_width']) ? 'is-invalid' : ''; ?>" id="header_width" name="header_width">
                                    <option value="boxed" <?php echo (isset($generalSettings['header_width']) ? $generalSettings['header_width'] : 'boxed') === 'boxed' ? 'selected' : ''; ?>>Boxed</option>
                                    <option value="full" <?php echo (isset($generalSettings['header_width']) ? $generalSettings['header_width'] : 'boxed') === 'full' ? 'selected' : ''; ?>>Full width</option>
                                </select>
                                <?php if (isset($errors['header_width'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['header_width']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label for="banner_width_percent" class="form-label">Banner Width (%)</label>
                                <input type="number" class="form-control <?php echo isset($errors['banner_width_percent']) ? 'is-invalid' : ''; ?>" id="banner_width_percent" name="banner_width_percent" min="10" max="100" value="<?php echo isset($generalSettings['banner_width_percent']) ? htmlspecialchars($generalSettings['banner_width_percent']) : '100'; ?>">
                                <?php if (isset($errors['banner_width_percent'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['banner_width_percent']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="banner_height_desktop" class="form-label">Banner Height Desktop (px)</label>
                                <input type="number" class="form-control <?php echo isset($errors['banner_height_desktop']) ? 'is-invalid' : ''; ?>" id="banner_height_desktop" name="banner_height_desktop" min="150" max="1200" value="<?php echo isset($generalSettings['banner_height_desktop']) ? htmlspecialchars($generalSettings['banner_height_desktop']) : '600'; ?>">
                                <?php if (isset($errors['banner_height_desktop'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['banner_height_desktop']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="banner_height_mobile" class="form-label">Banner Height Mobile (px)</label>
                                <input type="number" class="form-control <?php echo isset($errors['banner_height_mobile']) ? 'is-invalid' : ''; ?>" id="banner_height_mobile" name="banner_height_mobile" min="120" max="800" value="<?php echo isset($generalSettings['banner_height_mobile']) ? htmlspecialchars($generalSettings['banner_height_mobile']) : '250'; ?>">
                                <?php if (isset($errors['banner_height_mobile'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['banner_height_mobile']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="theme_primary_color" class="form-label">Theme Primary</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['theme_primary_color']) ? 'is-invalid' : ''; ?>" id="theme_primary_color" name="theme_primary_color" value="<?php echo isset($generalSettings['theme_primary_color']) ? htmlspecialchars($generalSettings['theme_primary_color']) : '#0d6efd'; ?>" title="Choose color">
                                <?php if (isset($errors['theme_primary_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['theme_primary_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="theme_secondary_color" class="form-label">Theme Secondary</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['theme_secondary_color']) ? 'is-invalid' : ''; ?>" id="theme_secondary_color" name="theme_secondary_color" value="<?php echo isset($generalSettings['theme_secondary_color']) ? htmlspecialchars($generalSettings['theme_secondary_color']) : '#6c757d'; ?>" title="Choose color">
                                <?php if (isset($errors['theme_secondary_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['theme_secondary_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="theme_background_color" class="form-label">Theme Background</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['theme_background_color']) ? 'is-invalid' : ''; ?>" id="theme_background_color" name="theme_background_color" value="<?php echo isset($generalSettings['theme_background_color']) ? htmlspecialchars($generalSettings['theme_background_color']) : '#ffffff'; ?>" title="Choose color">
                                <?php if (isset($errors['theme_background_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['theme_background_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="theme_text_color" class="form-label">Theme Text</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['theme_text_color']) ? 'is-invalid' : ''; ?>" id="theme_text_color" name="theme_text_color" value="<?php echo isset($generalSettings['theme_text_color']) ? htmlspecialchars($generalSettings['theme_text_color']) : '#212529'; ?>" title="Choose color">
                                <?php if (isset($errors['theme_text_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['theme_text_color']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="theme_default_mode" class="form-label">Default Theme</label>
                                <select class="form-control <?php echo isset($errors['theme_default_mode']) ? 'is-invalid' : ''; ?>" id="theme_default_mode" name="theme_default_mode">
                                    <option value="light" <?php echo (isset($generalSettings['theme_default_mode']) ? $generalSettings['theme_default_mode'] : 'light') === 'light' ? 'selected' : ''; ?>>Light</option>
                                    <option value="dark" <?php echo (isset($generalSettings['theme_default_mode']) ? $generalSettings['theme_default_mode'] : 'light') === 'dark' ? 'selected' : ''; ?>>Dark</option>
                                </select>
                                <?php if (isset($errors['theme_default_mode'])): ?>
                                    <div class="invalid-feedback"><?php echo $errors['theme_default_mode']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="theme_dark_primary_color" class="form-label">Dark Primary</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['theme_dark_primary_color']) ? 'is-invalid' : ''; ?>" id="theme_dark_primary_color" name="theme_dark_primary_color" value="<?php echo isset($generalSettings['theme_dark_primary_color']) ? htmlspecialchars($generalSettings['theme_dark_primary_color']) : '#4dabf7'; ?>" title="Choose color">
                                <?php if (isset($errors['theme_dark_primary_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['theme_dark_primary_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="theme_dark_secondary_color" class="form-label">Dark Secondary</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['theme_dark_secondary_color']) ? 'is-invalid' : ''; ?>" id="theme_dark_secondary_color" name="theme_dark_secondary_color" value="<?php echo isset($generalSettings['theme_dark_secondary_color']) ? htmlspecialchars($generalSettings['theme_dark_secondary_color']) : '#adb5bd'; ?>" title="Choose color">
                                <?php if (isset($errors['theme_dark_secondary_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['theme_dark_secondary_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="theme_dark_background_color" class="form-label">Dark Background</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['theme_dark_background_color']) ? 'is-invalid' : ''; ?>" id="theme_dark_background_color" name="theme_dark_background_color" value="<?php echo isset($generalSettings['theme_dark_background_color']) ? htmlspecialchars($generalSettings['theme_dark_background_color']) : '#0b1220'; ?>" title="Choose color">
                                <?php if (isset($errors['theme_dark_background_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['theme_dark_background_color']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="theme_dark_text_color" class="form-label">Dark Text</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['theme_dark_text_color']) ? 'is-invalid' : ''; ?>" id="theme_dark_text_color" name="theme_dark_text_color" value="<?php echo isset($generalSettings['theme_dark_text_color']) ? htmlspecialchars($generalSettings['theme_dark_text_color']) : '#e9ecef'; ?>" title="Choose color">
                                <?php if (isset($errors['theme_dark_text_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['theme_dark_text_color']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr class="my-4">
                        <h5 class="mb-3">Footer</h5>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_text_color" class="form-label">Footer Text Color</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['footer_text_color']) ? 'is-invalid' : ''; ?>" id="footer_text_color" name="footer_text_color" value="<?php echo isset($generalSettings['footer_text_color']) ? htmlspecialchars($generalSettings['footer_text_color']) : '#EEEEEE'; ?>" title="Choose color">
                                <?php if (isset($errors['footer_text_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['footer_text_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_accent_color" class="form-label">Footer Accent</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['footer_accent_color']) ? 'is-invalid' : ''; ?>" id="footer_accent_color" name="footer_accent_color" value="<?php echo isset($generalSettings['footer_accent_color']) ? htmlspecialchars($generalSettings['footer_accent_color']) : '#00ADB5'; ?>" title="Choose color">
                                <?php if (isset($errors['footer_accent_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['footer_accent_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_heading_color" class="form-label">Footer Heading Color</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['footer_heading_color']) ? 'is-invalid' : ''; ?>" id="footer_heading_color" name="footer_heading_color" value="<?php echo isset($generalSettings['footer_heading_color']) ? htmlspecialchars($generalSettings['footer_heading_color']) : '#FFFFFF'; ?>" title="Choose color">
                                <?php if (isset($errors['footer_heading_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['footer_heading_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_font_size" class="form-label">Footer Font Size</label>
                                <input type="text" class="form-control <?php echo isset($errors['footer_font_size']) ? 'is-invalid' : ''; ?>" id="footer_font_size" name="footer_font_size" placeholder="e.g. 14px or 0.95rem" value="<?php echo isset($generalSettings['footer_font_size']) ? htmlspecialchars($generalSettings['footer_font_size']) : '0.95rem'; ?>">
                                <?php if (isset($errors['footer_font_size'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['footer_font_size']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="footer_font_family" class="form-label">Footer Font Family</label>
                                <select class="form-control <?php echo isset($errors['footer_font_family']) ? 'is-invalid' : ''; ?>" id="footer_font_family" name="footer_font_family">
                                    <?php
                                        $families = [
                                            'inherit' => 'Inherit (default)',
                                            'Arial, Helvetica, sans-serif' => 'Arial / Helvetica / Sans-Serif',
                                            'Roboto, Arial, sans-serif' => 'Roboto',
                                            'Georgia, serif' => 'Georgia / Serif',
                                            'Times New Roman, Times, serif' => 'Times New Roman / Serif',
                                            'Courier New, Courier, monospace' => 'Courier New / Monospace',
                                            'system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif' => 'System UI'
                                        ];
                                        $currentFamily = isset($generalSettings['footer_font_family']) ? $generalSettings['footer_font_family'] : 'inherit';
                                        foreach ($families as $val => $label) {
                                            $sel = ($currentFamily === $val) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($val) . '" ' . $sel . '>' . htmlspecialchars($label) . '</option>';
                                        }
                                    ?>
                                </select>
                                <?php if (isset($errors['footer_font_family'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['footer_font_family']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_heading_about" class="form-label">Footer Heading: About</label>
                                <input type="text" class="form-control" id="footer_heading_about" name="footer_heading_about" value="<?php echo isset($generalSettings['footer_heading_about']) ? htmlspecialchars($generalSettings['footer_heading_about']) : 'About store'; ?>">
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_heading_quick_links" class="form-label">Footer Heading: Quick Links</label>
                                <input type="text" class="form-control" id="footer_heading_quick_links" name="footer_heading_quick_links" value="<?php echo isset($generalSettings['footer_heading_quick_links']) ? htmlspecialchars($generalSettings['footer_heading_quick_links']) : 'Quick Links'; ?>">
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_heading_contact_info" class="form-label">Footer Heading: Contact</label>
                                <input type="text" class="form-control" id="footer_heading_contact_info" name="footer_heading_contact_info" value="<?php echo isset($generalSettings['footer_heading_contact_info']) ? htmlspecialchars($generalSettings['footer_heading_contact_info']) : 'Contact Info'; ?>">
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_heading_newsletter" class="form-label">Footer Heading: Newsletter</label>
                                <input type="text" class="form-control" id="footer_heading_newsletter" name="footer_heading_newsletter" value="<?php echo isset($generalSettings['footer_heading_newsletter']) ? htmlspecialchars($generalSettings['footer_heading_newsletter']) : 'Newsletter'; ?>">
                            </div>
                        </div>
                        <h6 class="mb-2">Footer Bottom Bar</h6>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label for="footer_bottom_text" class="form-label">Footer Bottom Text</label>
                                <input type="text" class="form-control" id="footer_bottom_text" name="footer_bottom_text" value="<?php echo isset($generalSettings['footer_bottom_text']) ? htmlspecialchars($generalSettings['footer_bottom_text']) : 'E-Store. All rights reserved.'; ?>">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Footer Bottom Links Text</label>
                                <div class="row g-2">
                                    <div class="col-12 col-md-4">
                                        <input type="text" class="form-control" id="footer_bottom_link_privacy" name="footer_bottom_link_privacy" value="<?php echo isset($generalSettings['footer_bottom_link_privacy']) ? htmlspecialchars($generalSettings['footer_bottom_link_privacy']) : 'Privacy Policy'; ?>">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" class="form-control" id="footer_bottom_link_terms" name="footer_bottom_link_terms" value="<?php echo isset($generalSettings['footer_bottom_link_terms']) ? htmlspecialchars($generalSettings['footer_bottom_link_terms']) : 'Terms of Service'; ?>">
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <input type="text" class="form-control" id="footer_bottom_link_faq" name="footer_bottom_link_faq" value="<?php echo isset($generalSettings['footer_bottom_link_faq']) ? htmlspecialchars($generalSettings['footer_bottom_link_faq']) : 'FAQ'; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 g-md-3 mb-3">
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_bottom_link_color" class="form-label">Bottom Links Color</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['footer_bottom_link_color']) ? 'is-invalid' : ''; ?>" id="footer_bottom_link_color" name="footer_bottom_link_color" value="<?php echo isset($generalSettings['footer_bottom_link_color']) ? htmlspecialchars($generalSettings['footer_bottom_link_color']) : '#EEEEEE'; ?>" title="Choose color">
                                <?php if (isset($errors['footer_bottom_link_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['footer_bottom_link_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_bottom_text_color" class="form-label">Bottom Text Color</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['footer_bottom_text_color']) ? 'is-invalid' : ''; ?>" id="footer_bottom_text_color" name="footer_bottom_text_color" value="<?php echo isset($generalSettings['footer_bottom_text_color']) ? htmlspecialchars($generalSettings['footer_bottom_text_color']) : '#EEEEEE'; ?>" title="Choose color">
                                <?php if (isset($errors['footer_bottom_text_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['footer_bottom_text_color']; ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-sm-6 col-md-3">
                                <label for="footer_bottom_link_hover_color" class="form-label">Bottom Links Hover</label>
                                <input type="color" class="form-control form-control-color <?php echo isset($errors['footer_bottom_link_hover_color']) ? 'is-invalid' : ''; ?>" id="footer_bottom_link_hover_color" name="footer_bottom_link_hover_color" value="<?php echo isset($generalSettings['footer_bottom_link_hover_color']) ? htmlspecialchars($generalSettings['footer_bottom_link_hover_color']) : '#00ADB5'; ?>" title="Choose color">
                                <?php if (isset($errors['footer_bottom_link_hover_color'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $errors['footer_bottom_link_hover_color']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="row g-2 g-md-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="site_address" class="form-label">Address</label>
                                        <textarea class="form-control" id="site_address" name="site_address" rows="3"><?php echo isset($generalSettings['site_address']) ? htmlspecialchars($generalSettings['site_address']) : ''; ?></textarea>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label for="site_favicon" class="form-label">Favicon URL</label>
                                        <input type="text" class="form-control" id="site_favicon" name="site_favicon" value="<?php echo isset($generalSettings['site_favicon']) ? htmlspecialchars($generalSettings['site_favicon']) : ''; ?>" placeholder="e.g. /favicon.ico">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save General Settings</button>
                            </form>
                        </div>

                    <!-- Store Settings -->
                    <div class="tab-pane fade" id="store" role="tabpanel" aria-labelledby="store-tab">
                        <form action="<?php echo BASE_URL; ?>?controller=setting&action=updateStore" method="POST">
                            <div class="row g-2 g-md-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="store_currency" class="form-label">Currency</label>
                                    <input type="text" class="form-control <?php echo isset($errors['store_currency']) ? 'is-invalid' : ''; ?>" id="store_currency" name="store_currency" value="<?php echo isset($storeSettings['store_currency']) ? $storeSettings['store_currency'] : 'INR'; ?>" required>
                                    <?php if (isset($errors['store_currency'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['store_currency']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="store_currency_symbol" class="form-label">Currency Symbol</label>
                                    <input type="text" class="form-control <?php echo isset($errors['store_currency_symbol']) ? 'is-invalid' : ''; ?>" id="store_currency_symbol" name="store_currency_symbol" value="<?php echo isset($storeSettings['store_currency_symbol']) ? $storeSettings['store_currency_symbol'] : '₹'; ?>" required>
                                    <?php if (isset($errors['store_currency_symbol'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['store_currency_symbol']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>


                            <button type="submit" class="btn btn-primary">Save Store Settings</button>
                        </form>
                    </div>

                    <!-- Payment Settings -->
                    <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                        <form action="<?php echo BASE_URL; ?>?controller=setting&action=updatePayment" method="POST">
                            <div class="card mb-3">
                                <div class="card-header">Cash on Delivery</div>
                                <div class="card-body">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="payment_cod_enabled" name="payment_cod_enabled" value="1" <?php echo (isset($paymentSettings['payment_cod_enabled']) && $paymentSettings['payment_cod_enabled'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_cod_enabled">Enable Cash on Delivery</label>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">Bank Transfer</div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="payment_bank_transfer_enabled" name="payment_bank_transfer_enabled" value="1" <?php echo (isset($paymentSettings['payment_bank_transfer_enabled']) && $paymentSettings['payment_bank_transfer_enabled'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_bank_transfer_enabled">Enable Bank Transfer</label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="payment_bank_details" class="form-label">Bank Account Details</label>
                                        <textarea class="form-control" id="payment_bank_details" name="payment_bank_details" rows="3"><?php echo isset($paymentSettings['payment_bank_details']) ? $paymentSettings['payment_bank_details'] : ''; ?></textarea>
                                        <div class="form-text">Enter the bank account details that will be shown to customers.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">PayPal</div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="payment_paypal_enabled" name="payment_paypal_enabled" value="1" <?php echo (isset($paymentSettings['payment_paypal_enabled']) && $paymentSettings['payment_paypal_enabled'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_paypal_enabled">Enable PayPal</label>
                                    </div>
                                    <div class="mb-3">
                                        <label for="payment_paypal_email" class="form-label">PayPal Email</label>
                                        <input type="email" class="form-control <?php echo isset($errors['payment_paypal_email']) ? 'is-invalid' : ''; ?>" id="payment_paypal_email" name="payment_paypal_email" value="<?php echo isset($paymentSettings['payment_paypal_email']) ? $paymentSettings['payment_paypal_email'] : ''; ?>">
                                        <?php if (isset($errors['payment_paypal_email'])): ?>
                                            <div class="invalid-feedback"><?php echo $errors['payment_paypal_email']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="payment_paypal_sandbox" name="payment_paypal_sandbox" value="1" <?php echo (isset($paymentSettings['payment_paypal_sandbox']) && $paymentSettings['payment_paypal_sandbox'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="payment_paypal_sandbox">PayPal Sandbox Mode</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Payment Settings</button>
                        </form>
                    </div>

                    <!-- Email Settings -->
                    <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                        <form action="<?php echo BASE_URL; ?>?controller=setting&action=updateEmail" method="POST">
                            <div class="row g-2 g-md-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="email_from_name" class="form-label">From Name</label>
                                    <input type="text" class="form-control <?php echo isset($errors['email_from_name']) ? 'is-invalid' : ''; ?>" id="email_from_name" name="email_from_name" value="<?php echo isset($emailSettings['email_from_name']) ? $emailSettings['email_from_name'] : ''; ?>" required>
                                    <?php if (isset($errors['email_from_name'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['email_from_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="email_from_address" class="form-label">From Email Address</label>
                                    <input type="email" class="form-control <?php echo isset($errors['email_from_address']) ? 'is-invalid' : ''; ?>" id="email_from_address" name="email_from_address" value="<?php echo isset($emailSettings['email_from_address']) ? $emailSettings['email_from_address'] : ''; ?>" required>
                                    <?php if (isset($errors['email_from_address'])): ?>
                                        <div class="invalid-feedback"><?php echo $errors['email_from_address']; ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header">SMTP Settings</div>
                                <div class="card-body">
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox" id="email_smtp_enabled" name="email_smtp_enabled" value="1" <?php echo (isset($emailSettings['email_smtp_enabled']) && $emailSettings['email_smtp_enabled'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="email_smtp_enabled">Use SMTP for sending emails</label>
                                    </div>

                                    <div class="row g-2 g-md-3 mb-3">
                                        <div class="col-12 col-md-6">
                                            <label for="email_smtp_host" class="form-label">SMTP Host</label>
                                            <input type="text" class="form-control <?php echo isset($errors['email_smtp_host']) ? 'is-invalid' : ''; ?>" id="email_smtp_host" name="email_smtp_host" value="<?php echo isset($emailSettings['email_smtp_host']) ? $emailSettings['email_smtp_host'] : ''; ?>">
                                            <?php if (isset($errors['email_smtp_host'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['email_smtp_host']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="email_smtp_port" class="form-label">SMTP Port</label>
                                            <input type="text" class="form-control <?php echo isset($errors['email_smtp_port']) ? 'is-invalid' : ''; ?>" id="email_smtp_port" name="email_smtp_port" value="<?php echo isset($emailSettings['email_smtp_port']) ? $emailSettings['email_smtp_port'] : ''; ?>">
                                            <?php if (isset($errors['email_smtp_port'])): ?>
                                                <div class="invalid-feedback"><?php echo $errors['email_smtp_port']; ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row g-2 g-md-3 mb-3">
                                        <div class="col-12 col-md-6">
                                            <label for="email_smtp_username" class="form-label">SMTP Username</label>
                                            <input type="text" class="form-control" id="email_smtp_username" name="email_smtp_username" value="<?php echo isset($emailSettings['email_smtp_username']) ? $emailSettings['email_smtp_username'] : ''; ?>">
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <label for="email_smtp_password" class="form-label">SMTP Password</label>
                                            <input type="password" class="form-control" id="email_smtp_password" name="email_smtp_password" value="<?php echo isset($emailSettings['email_smtp_password']) ? $emailSettings['email_smtp_password'] : ''; ?>">
                                            <div class="form-text">Leave empty to keep the current password.</div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email_smtp_encryption" class="form-label">Encryption</label>
                                        <select class="form-control" id="email_smtp_encryption" name="email_smtp_encryption">
                                            <option value="" <?php echo (!isset($emailSettings['email_smtp_encryption']) || $emailSettings['email_smtp_encryption'] == '') ? 'selected' : ''; ?>>None</option>
                                            <option value="tls" <?php echo (isset($emailSettings['email_smtp_encryption']) && $emailSettings['email_smtp_encryption'] == 'tls') ? 'selected' : ''; ?>>TLS</option>
                                            <option value="ssl" <?php echo (isset($emailSettings['email_smtp_encryption']) && $emailSettings['email_smtp_encryption'] == 'ssl') ? 'selected' : ''; ?>>SSL</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save Email Settings</button>

                            
                        </form>

                        
                    </div>

                   
                    
                    
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<?php require_once APP_PATH . 'views/admin/layouts/footer.php'; ?>