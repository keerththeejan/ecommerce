<?php
/**
 * Banner Management Controller 2026
 * Handles all banner-related operations for the modern admin dashboard
 */
class BannerController2026 extends Controller {
    
    public function __construct() {
        parent::__construct();
        // Check if user is admin
        if (!$this->isAdmin()) {
            redirect('user/login');
        }
    }
    
    /**
     * Default action - Show banner management dashboard
     */
    public function index() {
        try {
            // Load required models
            $this->model('Banner');
            
            // Get all banners with sorting
            $banners = $this->model('Banner')->getAllBanners();
            
            // Get banner settings
            $settings = $this->model('Banner')->getSettings();
            
            // Load view
            $this->view('admin/banner/management-2026', [
                'banners' => $banners,
                'settings' => $settings,
                'pageTitle' => 'Banner Management'
            ]);
            
        } catch (Exception $e) {
            error_log('Banner index error: ' . $e->getMessage());
            $this->view('admin/banner/management-2026', [
                'banners' => [],
                'settings' => $this->getDefaultSettings(),
                'pageTitle' => 'Banner Management'
            ]);
        }
    }
    
    /**
     * Add new banner
     */
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleBannerUpload();
        } else {
            $this->view('admin/banner/add-2026', [
                'pageTitle' => 'Add New Banner',
                'categories' => $this->getBannerCategories(),
                'settings' => $this->model('Banner')->getSettings()
            ]);
        }
    }
    
    /**
     * Edit existing banner
     */
    public function edit($id = null) {
        if (!$id) {
            redirect('banner/index');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleBannerUpdate($id);
        } else {
            try {
                $banner = $this->model('Banner')->getBannerById($id);
                if (!$banner) {
                    $_SESSION['error'] = 'Banner not found';
                    redirect('banner/index');
                }
                
                $this->view('admin/banner/edit-2026', [
                    'banner' => $banner,
                    'pageTitle' => 'Edit Banner',
                    'categories' => $this->getBannerCategories()
                ]);
                
            } catch (Exception $e) {
                error_log('Banner edit error: ' . $e->getMessage());
                $_SESSION['error'] = 'Error loading banner';
                redirect('banner/index');
            }
        }
    }
    
    /**
     * Banner slider settings
     */
    public function slider() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateSliderSettings();
        } else {
            $this->view('admin/banner/slider-settings-2026', [
                'pageTitle' => 'Banner Slider Settings',
                'settings' => $this->model('Banner')->getSettings()
            ]);
        }
    }
    
    /**
     * Homepage hero banner management
     */
    public function hero() {
        try {
            $heroBanners = $this->model('Banner')->getHeroBanners();
            
            $this->view('admin/banner/hero-2026', [
                'pageTitle' => 'Homepage Hero Banner',
                'heroBanners' => $heroBanners
            ]);
            
        } catch (Exception $e) {
            error_log('Hero banner error: ' . $e->getMessage());
            $this->view('admin/banner/hero-2026', [
                'pageTitle' => 'Homepage Hero Banner',
                'heroBanners' => []
            ]);
        }
    }
    
    /**
     * Mobile banner management
     */
    public function mobile() {
        try {
            $mobileBanners = $this->model('Banner')->getMobileBanners();
            
            $this->view('admin/banner/mobile-2026', [
                'pageTitle' => 'Mobile Banner Management',
                'mobileBanners' => $mobileBanners
            ]);
            
        } catch (Exception $e) {
            error_log('Mobile banner error: ' . $e->getMessage());
            $this->view('admin/banner/mobile-2026', [
                'pageTitle' => 'Mobile Banner Management',
                'mobileBanners' => []
            ]);
        }
    }
    
    /**
     * Promotional banners
     */
    public function promotional() {
        try {
            $promoBanners = $this->model('Banner')->getPromotionalBanners();
            
            $this->view('admin/banner/promotional-2026', [
                'pageTitle' => 'Promotional Banners',
                'promoBanners' => $promoBanners
            ]);
            
        } catch (Exception $e) {
            error_log('Promotional banner error: ' . $e->getMessage());
            $this->view('admin/banner/promotional-2026', [
                'pageTitle' => 'Promotional Banners',
                'promoBanners' => []
            ]);
        }
    }
    
    /**
     * Campaign banners
     */
    public function campaigns() {
        try {
            $campaignBanners = $this->model('Banner')->getCampaignBanners();
            
            $this->view('admin/banner/campaigns-2026', [
                'pageTitle' => 'Offer Campaign Banners',
                'campaignBanners' => $campaignBanners
            ]);
            
        } catch (Exception $e) {
            error_log('Campaign banner error: ' . $e->getMessage());
            $this->view('admin/banner/campaigns-2026', [
                'pageTitle' => 'Offer Campaign Banners',
                'campaignBanners' => []
            ]);
        }
    }
    
    /**
     * Handle banner upload and creation
     */
    private function handleBannerUpload() {
        try {
            // Validate required fields
            $required = ['title', 'status'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    $_SESSION['error'] = ucfirst($field) . ' is required';
                    redirect('banner/add');
                }
            }
            
            // Handle image upload
            $imagePath = $this->handleImageUpload('banner_image');
            if (!$imagePath) {
                $_SESSION['error'] = 'Please upload a valid banner image';
                redirect('banner/add');
            }
            
            // Prepare banner data
            $bannerData = [
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? '',
                'image' => $imagePath,
                'cta_text' => $_POST['cta_text'] ?? '',
                'cta_link' => $_POST['cta_link'] ?? '',
                'start_date' => $_POST['start_date'] ?? null,
                'end_date' => $_POST['end_date'] ?? null,
                'status' => $_POST['status'],
                'type' => $_POST['type'] ?? 'general',
                'sort_order' => $_POST['sort_order'] ?? 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Save banner
            $bannerId = $this->model('Banner')->create($bannerData);
            
            if ($bannerId) {
                $_SESSION['success'] = 'Banner created successfully';
                redirect('banner/index');
            } else {
                $_SESSION['error'] = 'Failed to create banner';
                redirect('banner/add');
            }
            
        } catch (Exception $e) {
            error_log('Banner upload error: ' . $e->getMessage());
            $_SESSION['error'] = 'Error creating banner';
            redirect('banner/add');
        }
    }
    
    /**
     * Handle banner update
     */
    private function handleBannerUpdate($id) {
        try {
            // Validate required fields
            $required = ['title', 'status'];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    $_SESSION['error'] = ucfirst($field) . ' is required';
                    redirect("banner/edit/$id");
                }
            }
            
            // Handle image upload if new image provided
            $imagePath = null;
            if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
                $imagePath = $this->handleImageUpload('banner_image');
                if (!$imagePath) {
                    $_SESSION['error'] = 'Please upload a valid banner image';
                    redirect("banner/edit/$id");
                }
            }
            
            // Prepare banner data
            $bannerData = [
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? '',
                'cta_text' => $_POST['cta_text'] ?? '',
                'cta_link' => $_POST['cta_link'] ?? '',
                'start_date' => $_POST['start_date'] ?? null,
                'end_date' => $_POST['end_date'] ?? null,
                'status' => $_POST['status'],
                'type' => $_POST['type'] ?? 'general',
                'sort_order' => $_POST['sort_order'] ?? 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($imagePath) {
                $bannerData['image'] = $imagePath;
            }
            
            // Update banner
            $success = $this->model('Banner')->update($id, $bannerData);
            
            if ($success) {
                $_SESSION['success'] = 'Banner updated successfully';
                redirect('banner/index');
            } else {
                $_SESSION['error'] = 'Failed to update banner';
                redirect("banner/edit/$id");
            }
            
        } catch (Exception $e) {
            error_log('Banner update error: ' . $e->getMessage());
            $_SESSION['error'] = 'Error updating banner';
            redirect("banner/edit/$id");
        }
    }
    
    /**
     * Handle image upload
     */
    private function handleImageUpload($fieldName) {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        $file = $_FILES[$fieldName];
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            return null;
        }
        
        // Validate file size (5MB max)
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'banner_' . time() . '_' . uniqid() . '.' . $extension;
        
        // Create upload directory if it doesn't exist
        $uploadDir = ROOT_PATH . 'public/uploads/banners/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move uploaded file
        $uploadPath = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return 'uploads/banners/' . $filename;
        }
        
        return null;
    }
    
    /**
     * Update slider settings
     */
    private function updateSliderSettings() {
        try {
            $settings = [
                'auto_slide' => isset($_POST['auto_slide']) ? 1 : 0,
                'slide_interval' => intval($_POST['slide_interval'] ?? 5000),
                'transition_effect' => $_POST['transition_effect'] ?? 'fade',
                'show_arrows' => isset($_POST['show_arrows']) ? 1 : 0,
                'show_dots' => isset($_POST['show_dots']) ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $success = $this->model('Banner')->updateSettings($settings);
            
            if ($success) {
                $_SESSION['success'] = 'Slider settings updated successfully';
            } else {
                $_SESSION['error'] = 'Failed to update slider settings';
            }
            
        } catch (Exception $e) {
            error_log('Slider settings error: ' . $e->getMessage());
            $_SESSION['error'] = 'Error updating slider settings';
        }
        
        redirect('banner/slider');
    }
    
    /**
     * Toggle banner status
     */
    public function toggleStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        
        $bannerId = intval($_POST['banner_id'] ?? 0);
        if (!$bannerId) {
            $this->json(['success' => false, 'message' => 'Invalid banner ID'], 400);
        }
        
        try {
            $banner = $this->model('Banner')->getBannerById($bannerId);
            if (!$banner) {
                $this->json(['success' => false, 'message' => 'Banner not found'], 404);
            }
            
            $newStatus = $banner['status'] === 'active' ? 'inactive' : 'active';
            $success = $this->model('Banner')->update($bannerId, [
                'status' => $newStatus,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            if ($success) {
                $this->json([
                    'success' => true,
                    'message' => "Banner $newStatus successfully",
                    'new_status' => $newStatus
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to update banner status'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Toggle status error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Error updating banner status'], 500);
        }
    }
    
    /**
     * Delete banner
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        
        $bannerId = intval($_POST['banner_id'] ?? 0);
        if (!$bannerId) {
            $this->json(['success' => false, 'message' => 'Invalid banner ID'], 400);
        }
        
        try {
            $banner = $this->model('Banner')->getBannerById($bannerId);
            if (!$banner) {
                $this->json(['success' => false, 'message' => 'Banner not found'], 404);
            }
            
            // Delete image file
            if (!empty($banner['image'])) {
                $imagePath = ROOT_PATH . 'public/' . $banner['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            // Delete banner from database
            $success = $this->model('Banner')->delete($bannerId);
            
            if ($success) {
                $this->json(['success' => true, 'message' => 'Banner deleted successfully']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to delete banner'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Delete banner error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Error deleting banner'], 500);
        }
    }
    
    /**
     * Update banner order (drag and drop)
     */
    public function updateOrder() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        
        $bannerOrders = $_POST['banner_orders'] ?? [];
        if (empty($bannerOrders)) {
            $this->json(['success' => false, 'message' => 'No banner orders provided'], 400);
        }
        
        try {
            $success = true;
            foreach ($bannerOrders as $order) {
                $bannerId = intval($order['id'] ?? 0);
                $sortOrder = intval($order['sort_order'] ?? 0);
                
                if ($bannerId) {
                    $result = $this->model('Banner')->update($bannerId, [
                        'sort_order' => $sortOrder,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    if (!$result) {
                        $success = false;
                        break;
                    }
                }
            }
            
            if ($success) {
                $this->json(['success' => true, 'message' => 'Banner order updated successfully']);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to update banner order'], 500);
            }
            
        } catch (Exception $e) {
            error_log('Update order error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Error updating banner order'], 500);
        }
    }
    
    /**
     * Get banner analytics
     */
    public function getAnalytics() {
        $bannerId = intval($_GET['banner_id'] ?? 0);
        $dateRange = $_GET['date_range'] ?? '7days';
        
        try {
            $analytics = $this->model('Banner')->getAnalytics($bannerId, $dateRange);
            
            $this->json([
                'success' => true,
                'data' => $analytics
            ]);
            
        } catch (Exception $e) {
            error_log('Analytics error: ' . $e->getMessage());
            $this->json(['success' => false, 'message' => 'Error loading analytics'], 500);
        }
    }
    
    /**
     * Track banner click
     */
    public function trackClick() {
        $bannerId = intval($_POST['banner_id'] ?? 0);
        
        if ($bannerId) {
            try {
                $this->model('Banner')->trackClick($bannerId);
                $this->json(['success' => true]);
            } catch (Exception $e) {
                error_log('Track click error: ' . $e->getMessage());
                $this->json(['success' => false], 500);
            }
        } else {
            $this->json(['success' => false], 400);
        }
    }
    
    /**
     * Get banner categories
     */
    private function getBannerCategories() {
        return [
            'general' => 'General',
            'hero' => 'Hero Banner',
            'mobile' => 'Mobile',
            'promotional' => 'Promotional',
            'campaign' => 'Campaign',
            'seasonal' => 'Seasonal',
            'offer' => 'Special Offer'
        ];
    }
    
    /**
     * Get default settings
     */
    private function getDefaultSettings() {
        return [
            'auto_slide' => 1,
            'slide_interval' => 5000,
            'transition_effect' => 'fade',
            'show_arrows' => 1,
            'show_dots' => 1
        ];
    }
    
    /**
     * Check if user is admin
     */
    private function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }
}
