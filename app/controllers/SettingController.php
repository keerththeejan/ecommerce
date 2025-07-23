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
        $paymentSettings = $this->settingModel->getSettingsByGroup('payment');
        $emailSettings = $this->settingModel->getSettingsByGroup('email');
        
        // Load view
        $this->view('admin/settings/index', [
            'generalSettings' => $generalSettings,
            'storeSettings' => $storeSettings,
            'paymentSettings' => $paymentSettings,
            'emailSettings' => $emailSettings
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
                'site_favicon' => sanitize($this->post('site_favicon'))
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
