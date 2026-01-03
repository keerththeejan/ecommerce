<?php
/**
 * Setting Controller
 * Handles application settings
 */
class SettingController extends Controller {
    private $settingModel;
    
    public function __construct() {
        // Check if admin
        if(!isAdmin()) {
            redirect('user/login');
        }
        
        $this->settingModel = $this->model('Setting');
    }
    
    /**
     * Settings dashboard
     */
    public function index() {
        // Get settings by group
        $generalSettings = [];
        $storeSettings = [
            'store_currency' => 'INR',
            'store_currency_symbol' => '₹',
            'store_tax_rate' => '18',
            'store_shipping_flat_rate' => '100',
            'store_free_shipping_threshold' => '1000',
            'store_inventory_management' => '0',
            'store_low_stock_threshold' => '5'
        ];
        $paymentSettings = [];
        $emailSettings = [];
        
        // Get store settings with defaults
        $storeSettings = [
            'store_currency' => $this->settingModel->getSetting('store_currency', 'INR'),
            'store_currency_symbol' => $this->settingModel->getSetting('store_currency_symbol', '₹'),
            'store_tax_rate' => $this->settingModel->getSetting('store_tax_rate', '18'),
            'store_shipping_flat_rate' => $this->settingModel->getSetting('store_shipping_flat_rate', '100'),
            'store_free_shipping_threshold' => $this->settingModel->getSetting('store_free_shipping_threshold', '1000'),
            'store_inventory_management' => $this->settingModel->getSetting('store_inventory_management', '0'),
            'store_low_stock_threshold' => $this->settingModel->getSetting('store_low_stock_threshold', '5')
        ];
        
        // Get other settings by group
        $generalSettings = $this->settingModel->getSettingsByGroup('general');
        $generalSettings['home_categories_bg_color'] = $this->settingModel->getSetting('home_categories_bg_color', '#fff');
        $generalSettings['header_bg_color'] = $this->settingModel->getSetting('header_bg_color', '#ffffff');
        $generalSettings['header_width'] = $this->settingModel->getSetting('header_width', 'boxed');
        $generalSettings['banner_width_percent'] = $this->settingModel->getSetting('banner_width_percent', '100');
        $generalSettings['banner_height_desktop'] = $this->settingModel->getSetting('banner_height_desktop', '600');
        $generalSettings['banner_height_mobile'] = $this->settingModel->getSetting('banner_height_mobile', '250');
        $generalSettings['theme_primary_color'] = $this->settingModel->getSetting('theme_primary_color', '#0d6efd');
        $generalSettings['theme_secondary_color'] = $this->settingModel->getSetting('theme_secondary_color', '#6c757d');
        $generalSettings['theme_background_color'] = $this->settingModel->getSetting('theme_background_color', '#ffffff');
        $generalSettings['theme_text_color'] = $this->settingModel->getSetting('theme_text_color', '#212529');
        $generalSettings['theme_default_mode'] = $this->settingModel->getSetting('theme_default_mode', 'light');
        $generalSettings['theme_dark_primary_color'] = $this->settingModel->getSetting('theme_dark_primary_color', '#4dabf7');
        $generalSettings['theme_dark_secondary_color'] = $this->settingModel->getSetting('theme_dark_secondary_color', '#adb5bd');
        $generalSettings['theme_dark_background_color'] = $this->settingModel->getSetting('theme_dark_background_color', '#0b1220');
        $generalSettings['theme_dark_text_color'] = $this->settingModel->getSetting('theme_dark_text_color', '#e9ecef');
        // Footer typography settings
        $generalSettings['footer_text_color'] = $this->settingModel->getSetting('footer_text_color', '#EEEEEE');
        $generalSettings['footer_font_size'] = $this->settingModel->getSetting('footer_font_size', '0.95rem');
        $generalSettings['footer_font_family'] = $this->settingModel->getSetting('footer_font_family', 'inherit');
        $paymentSettings = $this->settingModel->getSettingsByGroup('payment');
        $emailSettings = $this->settingModel->getSettingsByGroup('email');
        
        // Get tax settings with defaults
        $taxSettings = [
            'tax1_rate' => $this->settingModel->getSetting('tax1_rate', '0'),
            'tax1_name' => $this->settingModel->getSetting('tax1_name', 'Tax 1'),
            'tax2_rate' => $this->settingModel->getSetting('tax2_rate', '0'),
            'tax2_name' => $this->settingModel->getSetting('tax2_name', 'Tax 2'),
            'tax3_rate' => $this->settingModel->getSetting('tax3_rate', '0'),
            'tax3_name' => $this->settingModel->getSetting('tax3_name', 'Tax 3'),
            'tax4_rate' => $this->settingModel->getSetting('tax4_rate', '0'),
            'tax4_name' => $this->settingModel->getSetting('tax4_name', 'Tax 4'),
            'tax_inclusive' => $this->settingModel->getSetting('tax_inclusive', '0')
        ];
        
        // Load view
        $this->view('admin/settings/index', [
            'generalSettings' => $generalSettings,
            'storeSettings' => $storeSettings,
            'paymentSettings' => $paymentSettings,
            'emailSettings' => $emailSettings,
            'taxSettings' => $taxSettings
        ]);
    }
    
    /**
     * Update general settings
     */
    public function updateGeneral() {
        // Check for POST
        if($this->isPost()) {
            // Initialize data array
            $data = [
                'site_name' => 'Sivakamy', // Always set to Sivakamy
                'site_description' => sanitize($this->post('site_description')),
                'site_email' => sanitize($this->post('site_email')),
                'site_phone' => sanitize($this->post('site_phone')),
                'site_address' => sanitize($this->post('site_address')),
                'site_favicon' => sanitize($this->post('site_favicon')),
                'home_categories_bg_color' => trim($this->post('home_categories_bg_color')),
                'header_bg_color' => trim($this->post('header_bg_color')),
                'header_width' => trim($this->post('header_width')),
                'banner_width_percent' => trim($this->post('banner_width_percent')),
                'banner_height_desktop' => trim($this->post('banner_height_desktop')),
                'banner_height_mobile' => trim($this->post('banner_height_mobile')),
                'theme_primary_color' => trim($this->post('theme_primary_color')),
                'theme_secondary_color' => trim($this->post('theme_secondary_color')),
                'theme_background_color' => trim($this->post('theme_background_color')),
                'theme_text_color' => trim($this->post('theme_text_color')),
                'theme_default_mode' => trim($this->post('theme_default_mode')),
                'theme_dark_primary_color' => trim($this->post('theme_dark_primary_color')),
                'theme_dark_secondary_color' => trim($this->post('theme_dark_secondary_color')),
                'theme_dark_background_color' => trim($this->post('theme_dark_background_color')),
                'theme_dark_text_color' => trim($this->post('theme_dark_text_color')),
                // Footer typography
                'footer_text_color' => trim($this->post('footer_text_color')),
                'footer_font_size' => trim($this->post('footer_font_size')),
                'footer_font_family' => trim($this->post('footer_font_family'))
            ];
            
            // Also update store_name for consistency
            $this->settingModel->updateSetting('store_name', 'Sivakamy');
            
            // Handle logo upload
            $currentLogo = $this->post('current_logo');
            $removeLogo = $this->post('remove_logo') == '1';
            
            // Ensure uploads directory exists
            if (!is_dir(UPLOAD_PATH)) {
                mkdir(UPLOAD_PATH, 0755, true);
            }
            
            // If remove logo is checked or a new logo is uploaded
            if ($removeLogo) {
                // Delete the current logo file if it exists
                if (!empty($currentLogo) && file_exists(UPLOAD_PATH . $currentLogo)) {
                    unlink(UPLOAD_PATH . $currentLogo);
                }
                $data['site_logo'] = '';
            } elseif (!empty($_FILES['site_logo']['name'])) {
                // Handle file upload
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 2 * 1024 * 1024; // 2MB
                
                $upload = $this->uploadFile('site_logo', UPLOAD_PATH, $allowedTypes, $maxSize);
                
                if ($upload['success']) {
                    // Delete old logo if it exists and is different
                    if (!empty($currentLogo) && $currentLogo !== $upload['file_name'] && file_exists(UPLOAD_PATH . $currentLogo)) {
                        unlink(UPLOAD_PATH . $currentLogo);
                    }
                    $data['site_logo'] = $upload['file_name'];
                } else {
                    flash('setting_error', 'Logo upload failed: ' . $upload['error'], 'alert alert-danger');
                    redirect('setting/index');
                    return;
                }
            } else {
                // Keep the current logo
                $data['site_logo'] = $currentLogo;
            }
            
            // Validate data
            $errors = $this->validate($data, [
                'site_name' => 'required',
                'site_email' => 'required|email'
            ]);

            if (!empty($data['home_categories_bg_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['home_categories_bg_color'])) {
                $errors['home_categories_bg_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }

            if (empty($data['home_categories_bg_color'])) {
                $data['home_categories_bg_color'] = '#fff';
            }

            if (!empty($data['header_bg_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['header_bg_color'])) {
                $errors['header_bg_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }

            if (empty($data['header_bg_color'])) {
                $data['header_bg_color'] = '#ffffff';
            }

            if (empty($data['header_width'])) {
                $data['header_width'] = 'boxed';
            }

            if (!in_array($data['header_width'], ['boxed', 'full'], true)) {
                $errors['header_width'] = 'Invalid header width option.';
            }

            $bannerWidthPercent = (int)$data['banner_width_percent'];
            if ($bannerWidthPercent < 10 || $bannerWidthPercent > 100) {
                $errors['banner_width_percent'] = 'Banner width must be between 10 and 100.';
            }
            if (empty($data['banner_width_percent'])) {
                $data['banner_width_percent'] = '100';
            }

            $bannerHeightDesktop = (int)$data['banner_height_desktop'];
            if ($bannerHeightDesktop < 150 || $bannerHeightDesktop > 1200) {
                $errors['banner_height_desktop'] = 'Banner desktop height must be between 150 and 1200.';
            }
            if (empty($data['banner_height_desktop'])) {
                $data['banner_height_desktop'] = '600';
            }

            $bannerHeightMobile = (int)$data['banner_height_mobile'];
            if ($bannerHeightMobile < 120 || $bannerHeightMobile > 800) {
                $errors['banner_height_mobile'] = 'Banner mobile height must be between 120 and 800.';
            }
            if (empty($data['banner_height_mobile'])) {
                $data['banner_height_mobile'] = '250';
            }

            if (!empty($data['theme_primary_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['theme_primary_color'])) {
                $errors['theme_primary_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }
            if (empty($data['theme_primary_color'])) {
                $data['theme_primary_color'] = '#0d6efd';
            }

            if (!empty($data['theme_secondary_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['theme_secondary_color'])) {
                $errors['theme_secondary_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }
            if (empty($data['theme_secondary_color'])) {
                $data['theme_secondary_color'] = '#6c757d';
            }

            if (!empty($data['theme_background_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['theme_background_color'])) {
                $errors['theme_background_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }
            if (empty($data['theme_background_color'])) {
                $data['theme_background_color'] = '#ffffff';
            }

            if (!empty($data['theme_text_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['theme_text_color'])) {
                $errors['theme_text_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }
            if (empty($data['theme_text_color'])) {
                $data['theme_text_color'] = '#212529';
            }

            if (empty($data['theme_default_mode'])) {
                $data['theme_default_mode'] = 'light';
            }

            if (!in_array($data['theme_default_mode'], ['light', 'dark'], true)) {
                $errors['theme_default_mode'] = 'Invalid theme mode.';
            }

            if (!empty($data['theme_dark_primary_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['theme_dark_primary_color'])) {
                $errors['theme_dark_primary_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }
            if (empty($data['theme_dark_primary_color'])) {
                $data['theme_dark_primary_color'] = '#4dabf7';
            }

            if (!empty($data['theme_dark_secondary_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['theme_dark_secondary_color'])) {
                $errors['theme_dark_secondary_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }
            if (empty($data['theme_dark_secondary_color'])) {
                $data['theme_dark_secondary_color'] = '#adb5bd';
            }

            if (!empty($data['theme_dark_background_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['theme_dark_background_color'])) {
                $errors['theme_dark_background_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }
            if (empty($data['theme_dark_background_color'])) {
                $data['theme_dark_background_color'] = '#0b1220';
            }

            if (!empty($data['theme_dark_text_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['theme_dark_text_color'])) {
                $errors['theme_dark_text_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }
            if (empty($data['theme_dark_text_color'])) {
                $data['theme_dark_text_color'] = '#e9ecef';
            }

            // Footer typography validation
            if (!empty($data['footer_text_color']) && !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $data['footer_text_color'])) {
                $errors['footer_text_color'] = 'Invalid color. Use hex like #fff or #ffffff.';
            }
            if (empty($data['footer_text_color'])) {
                $data['footer_text_color'] = '#EEEEEE';
            }

            // Accept sizes like 14px, 0.95rem, 1em, 95% (basic safety)
            if (!empty($data['footer_font_size']) && !preg_match('/^\d+(px|rem|em|%)$/', $data['footer_font_size'])) {
                $errors['footer_font_size'] = 'Invalid size. Use values like 14px, 0.95rem, 1em, or 95%.';
            }
            if (empty($data['footer_font_size'])) {
                $data['footer_font_size'] = '0.95rem';
            }

            // Whitelist common families; fallback to inherit
            $allowedFamilies = [
                'inherit',
                'Arial, Helvetica, sans-serif',
                'Roboto, Arial, sans-serif',
                'Georgia, serif',
                'Times New Roman, Times, serif',
                'Courier New, Courier, monospace',
                'system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif'
            ];
            if (empty($data['footer_font_family'])) {
                $data['footer_font_family'] = 'inherit';
            } elseif (!in_array($data['footer_font_family'], $allowedFamilies, true)) {
                $errors['footer_font_family'] = 'Invalid font family selection.';
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                $success = true;
                $updateErrors = [];
                
                // Update settings
                foreach($data as $key => $value) {
                    if(!$this->settingModel->updateSetting($key, $value)) {
                        $updateErrors[] = "Failed to update {$key}: " . $this->settingModel->getLastError();
                        $success = false;
                    }
                }
                
                if($success) {
                    flash('setting_success', 'General settings updated successfully');
                    // Redirect to the same page with success message
                    header('Location: ' . BASE_URL . '?controller=setting&action=index&tab=general');
                    exit();
                } else {
                    flash('setting_error', 'Failed to update some settings: ' . implode(', ', $updateErrors), 'alert alert-danger');
                    // Redirect back to the general tab with errors
                    header('Location: ' . BASE_URL . '?controller=setting&action=index&tab=general');
                    exit();
                }
            } else {
                // Load view with errors
                $this->view('admin/settings/index', [
                    'errors' => $errors,
                    'generalSettings' => $data,
                    'storeSettings' => $this->settingModel->getSettingsByGroup('store'),
                    'paymentSettings' => $this->settingModel->getSettingsByGroup('payment'),
                    'emailSettings' => $this->settingModel->getSettingsByGroup('email')
                ]);
            }
        } else {
            redirect('setting/index');
        }
    }
    
    /**
     * Update store settings
     */
    public function updateStore() {
        // Check for POST
        if($this->isPost()) {
            // Process form
            $data = [
                'store_currency' => trim(sanitize($this->post('store_currency'))),
                'store_currency_symbol' => trim(sanitize($this->post('store_currency_symbol'))),
                'store_tax_rate' => floatval($this->post('store_tax_rate')),
                'store_shipping_flat_rate' => floatval($this->post('store_shipping_flat_rate')),
                'store_free_shipping_threshold' => floatval($this->post('store_free_shipping_threshold')),
                'store_inventory_management' => $this->post('store_inventory_management') ? 1 : 0,
                'store_low_stock_threshold' => intval($this->post('store_low_stock_threshold'))
            ];
            
            // Ensure required fields are not empty
            if (empty($data['store_currency'])) $data['store_currency'] = 'INR';
            if (empty($data['store_currency_symbol'])) $data['store_currency_symbol'] = '₹';
            
            // Validate data
            $errors = $this->validate($data, [
                'store_currency' => 'required',
                'store_currency_symbol' => 'required',
                'store_tax_rate' => 'required|numeric',
                'store_shipping_flat_rate' => 'required|numeric',
                'store_free_shipping_threshold' => 'required|numeric',
                'store_low_stock_threshold' => 'required|numeric'
            ]);
            
            // Make sure there are no errors
            if(empty($errors)) {
                $success = true;
                $errors = [];
                
                // Update settings
                foreach($data as $key => $value) {
                    if(!$this->settingModel->updateSetting($key, $value)) {
                        $errors[] = "Failed to update {$key}: " . $this->settingModel->getLastError();
                        $success = false;
                    }
                }
                
                if($success) {
                    flash('setting_success', 'Store settings updated successfully');
                    // Redirect to the same page with success message
                    header('Location: ' . BASE_URL . '?controller=setting&action=index&tab=store');
                    exit();
                } else {
                    flash('setting_error', 'Failed to update some settings: ' . implode(', ', $errors), 'alert alert-danger');
                    // Redirect back to the store tab with errors
                    header('Location: ' . BASE_URL . '?controller=setting&action=index&tab=store');
                    exit();
                }
            } else {
                // Load view with errors
                $this->view('admin/settings/index', [
                    'errors' => $errors,
                    'generalSettings' => $this->settingModel->getSettingsByGroup('general'),
                    'storeSettings' => $data,
                    'paymentSettings' => $this->settingModel->getSettingsByGroup('payment'),
                    'emailSettings' => $this->settingModel->getSettingsByGroup('email')
                ]);
            }
        } else {
            redirect('setting/index');
        }
    }
    
    /**
     * Update payment settings
     */
    public function updatePayment() {
        // Check for POST
        if($this->isPost()) {
            // Process form
            $data = [
                'payment_cod_enabled' => $this->post('payment_cod_enabled') ? 1 : 0,
                'payment_bank_transfer_enabled' => $this->post('payment_bank_transfer_enabled') ? 1 : 0,
                'payment_bank_details' => sanitize($this->post('payment_bank_details')),
                'payment_paypal_enabled' => $this->post('payment_paypal_enabled') ? 1 : 0,
                'payment_paypal_email' => sanitize($this->post('payment_paypal_email')),
                'payment_paypal_sandbox' => $this->post('payment_paypal_sandbox') ? 1 : 0
            ];
            
            // Validate PayPal email if enabled
            $errors = [];
            if($data['payment_paypal_enabled'] && empty($data['payment_paypal_email'])) {
                $errors['payment_paypal_email'] = 'PayPal email is required when PayPal is enabled';
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Update settings
                $success = true;
                
                foreach($data as $key => $value) {
                    if(!$this->settingModel->updateSetting($key, $value)) {
                        $success = false;
                        break;
                    }
                }
                
                if($success) {
                    flash('setting_success', 'Payment settings updated successfully');
                } else {
                    flash('setting_error', 'Failed to update settings: ' . $this->settingModel->getLastError(), 'alert alert-danger');
                }
                
                redirect('setting/index');
            } else {
                // Load view with errors
                $this->view('admin/settings/index', [
                    'errors' => $errors,
                    'generalSettings' => $this->settingModel->getSettingsByGroup('general'),
                    'storeSettings' => $this->settingModel->getSettingsByGroup('store'),
                    'paymentSettings' => $data,
                    'emailSettings' => $this->settingModel->getSettingsByGroup('email')
                ]);
            }
        } else {
            redirect('setting/index');
        }
    }
    
    /**
     * Update email settings
     */
    /**
     * Update tax settings
     */
    public function updateTax() {
        // Check for POST
        if($this->isPost()) {
            // Process form
            $data = [
                'tax1_rate' => floatval($this->post('tax1_rate')),
                'tax1_name' => sanitize($this->post('tax1_name')),
                'tax2_rate' => floatval($this->post('tax2_rate')),
                'tax2_name' => sanitize($this->post('tax2_name')),
                'tax3_rate' => floatval($this->post('tax3_rate')),
                'tax3_name' => sanitize($this->post('tax3_name')),
                'tax4_rate' => floatval($this->post('tax4_rate')),
                'tax4_name' => sanitize($this->post('tax4_name')),
                'tax_inclusive' => $this->post('tax_inclusive') ? 1 : 0
            ];
            
            // Validate data
            $errors = [];
            
            // Validate tax rates (0-100)
            $taxRates = [
                'tax1_rate' => $data['tax1_rate'],
                'tax2_rate' => $data['tax2_rate'],
                'tax3_rate' => $data['tax3_rate'],
                'tax4_rate' => $data['tax4_rate']
            ];
            
            foreach ($taxRates as $key => $rate) {
                if ($rate < 0 || $rate > 100) {
                    $errors[$key] = 'Tax rate must be between 0 and 100';
                }
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                $success = true;
                $updateErrors = [];
                
                // Update settings
                foreach($data as $key => $value) {
                    if(!$this->settingModel->updateSetting($key, $value)) {
                        $updateErrors[] = "Failed to update {$key}";
                        $success = false;
                    }
                }
                
                if($success) {
                    flash('setting_success', 'Tax settings updated successfully');
                    // Redirect to the tax tab with success message
                    header('Location: ' . BASE_URL . '?controller=setting&action=index&tab=tax');
                    exit();
                } else {
                    flash('setting_error', 'Failed to update some tax settings: ' . implode(', ', $updateErrors), 'alert alert-danger');
                    // Redirect back to the tax tab with errors
                    header('Location: ' . BASE_URL . '?controller=setting&action=index&tab=tax');
                    exit();
                }
            } else {
                // Load view with errors
                $this->view('admin/settings/index', [
                    'errors' => $errors,
                    'generalSettings' => $this->settingModel->getSettingsByGroup('general'),
                    'storeSettings' => $this->settingModel->getSettingsByGroup('store'),
                    'paymentSettings' => $this->settingModel->getSettingsByGroup('payment'),
                    'emailSettings' => $this->settingModel->getSettingsByGroup('email'),
                    'taxSettings' => $data
                ]);
            }
        } else {
            redirect('setting/index');
        }
    }
    
    /**
     * Update email settings
     */
    public function updateEmail() {
        // Check for POST
        if($this->isPost()) {
            // Process form
            $data = [
                'email_from_name' => sanitize($this->post('email_from_name')),
                'email_from_address' => sanitize($this->post('email_from_address')),
                'email_smtp_enabled' => $this->post('email_smtp_enabled') ? 1 : 0,
                'email_smtp_host' => sanitize($this->post('email_smtp_host')),
                'email_smtp_port' => sanitize($this->post('email_smtp_port')),
                'email_smtp_username' => sanitize($this->post('email_smtp_username')),
                'email_smtp_password' => $this->post('email_smtp_password'),
                'email_smtp_encryption' => sanitize($this->post('email_smtp_encryption'))
            ];
            
            // Validate data
            $errors = $this->validate($data, [
                'email_from_name' => 'required',
                'email_from_address' => 'required|email'
            ]);
            
            // Validate SMTP settings if enabled
            if($data['email_smtp_enabled']) {
                if(empty($data['email_smtp_host'])) {
                    $errors['email_smtp_host'] = 'SMTP host is required when SMTP is enabled';
                }
                if(empty($data['email_smtp_port'])) {
                    $errors['email_smtp_port'] = 'SMTP port is required when SMTP is enabled';
                }
            }
            
            // Make sure there are no errors
            if(empty($errors)) {
                // Update settings
                $success = true;
                
                foreach($data as $key => $value) {
                    if(!$this->settingModel->updateSetting($key, $value)) {
                        $success = false;
                        break;
                    }
                }
                
                if($success) {
                    flash('setting_success', 'Email settings updated successfully');
                } else {
                    flash('setting_error', 'Failed to update settings: ' . $this->settingModel->getLastError(), 'alert alert-danger');
                }
                
                redirect('setting/index');
            } else {
                // Load view with errors
                $this->view('admin/settings/index', [
                    'errors' => $errors,
                    'generalSettings' => $this->settingModel->getSettingsByGroup('general'),
                    'storeSettings' => $this->settingModel->getSettingsByGroup('store'),
                    'paymentSettings' => $this->settingModel->getSettingsByGroup('payment'),
                    'emailSettings' => $data
                ]);
            }
        } else {
            redirect('setting/index');
        }
    }
}
