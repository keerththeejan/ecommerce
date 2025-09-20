<?php
class BannerController {
    private $bannerModel;
    
    public function __construct() {
        require_once '../config/database.php';
        $db = new Database();
        $this->bannerModel = new Banner($db);
    }

    // Default action to display all banners
    public function index() {
        $banners = $this->bannerModel->getAll();
        // Load the admin view so the Admin Dashboard layout (sidebar) is shown
        require_once __DIR__ . '/../views/admin/banners/index.php';
    }

    // Handle banner creation
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Handle file upload
                $uploads_dir = '../public/uploads/banners';
                if (!is_dir($uploads_dir)) {
                    mkdir($uploads_dir, 0777, true);
                }

                // Check if file was uploaded
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['image'];
                    $file_name = time() . '_' . basename($file['name']);
                    $target_path = $uploads_dir . '/' . $file_name;

                    if (move_uploaded_file($file['tmp_name'], $target_path)) {
                        $data = [
                            'title' => $_POST['title'],
                            'description' => $_POST['description'],
                            'image_url' => 'uploads/banners/' . $file_name,
                            'status' => $_POST['status'] ?? 'active'
                        ];
                    } else {
                        throw new Exception('Failed to upload image');
                    }
                } else {
                    throw new Exception('No image uploaded');
                }
                
                $result = $this->bannerModel->insert($data);
                header('Location: ?controller=banner');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error creating banner: ' . $e->getMessage();
                header('Location: ?controller=banner');
                exit;
            }
        }
        // Show admin create form with Admin Dashboard layout
        require_once __DIR__ . '/../views/admin/banners/create.php';
    }

    // Handle banner edit display
    public function edit($id) {
        try {
            $banner = $this->bannerModel->getById($id);
            if (!$banner) {
                throw new Exception('Banner not found');
            }
            require_once __DIR__ . '/../views/banner/edit.php';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ?controller=banner');
            exit;
        }
    }

    // Handle banner update
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'status' => $_POST['status']
                ];

                // Handle new image upload if provided
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $uploads_dir = '../public/uploads/banners';
                    if (!is_dir($uploads_dir)) {
                        mkdir($uploads_dir, 0777, true);
                    }
                    
                    $file = $_FILES['image'];
                    $file_name = time() . '_' . basename($file['name']);
                    $target_path = $uploads_dir . '/' . $file_name;
                    
                    if (move_uploaded_file($file['tmp_name'], $target_path)) {
                        // Delete old image if exists
                        $old_image = $_POST['current_image'];
                        if (!empty($old_image) && file_exists('../public/' . $old_image)) {
                            unlink('../public/' . $old_image);
                        }
                        
                        $data['image_url'] = 'uploads/banners/' . $file_name;
                    } else {
                        throw new Exception('Failed to upload image');
                    }
                }

                $result = $this->bannerModel->update($id, $data);
                if ($result > 0) {
                    $_SESSION['success'] = 'Banner updated successfully';
                } else {
                    $_SESSION['error'] = 'No changes made';
                }
                header('Location: ?controller=banner');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error updating banner: ' . $e->getMessage();
                header('Location: ?controller=banner');
                exit;
            }
        }
    }

    // Handle banner deletion
    public function delete($id) {
        try {
            $banner = $this->bannerModel->getById($id);
            if (!$banner) {
                throw new Exception('Banner not found');
            }

            // Delete banner image if exists
            if (!empty($banner['image_url']) && file_exists('../public/' . $banner['image_url'])) {
                unlink('../public/' . $banner['image_url']);
            }

            $result = $this->bannerModel->delete($id);
            if ($result > 0) {
                $_SESSION['success'] = 'Banner deleted successfully';
            } else {
                $_SESSION['error'] = 'Failed to delete banner';
            }
            header('Location: ?controller=banner');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error deleting banner: ' . $e->getMessage();
            header('Location: ?controller=banner');
            exit;
        }
    }

    // Handle banner show
    public function show() {
        if (isset($_GET['id'])) {
            try {
                $banner = $this->bannerModel->getById($_GET['id']);
                if (!$banner) {
                    throw new Exception('Banner not found');
                }
                require_once __DIR__ . '/../views/banner/show.php';
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: ?controller=banner');
                exit;
            }
        } else {
            header('Location: ?controller=banner');
            exit;
        }
    }

    // Get banner by ID
    public function getById($id) {
        try {
            $banner = $this->bannerModel->getById($id);
            if ($banner) {
                return ['success' => true, 'data' => $banner];
            }
            return ['success' => false, 'message' => 'Banner not found'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error fetching banner: ' . $e->getMessage()];
        }
    }

    // Get all banners
    public function getAll($status = null) {
        try {
            $banners = $this->bannerModel->getAll($status);
            return ['success' => true, 'data' => $banners];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error fetching banners: ' . $e->getMessage()];
        }
    }
}
