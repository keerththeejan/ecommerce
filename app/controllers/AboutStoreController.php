<?php
class AboutStoreController {
    private $aboutStoreModel;
    
    public function __construct() {
        // Use the globally initialized Database instance for consistency
        $db = isset($GLOBALS['db']) ? $GLOBALS['db'] : new Database();
        $this->aboutStoreModel = new AboutStore($db);
    }

    // Show all about store entries
    public function index() {
        $about_entries = $this->aboutStoreModel->getAll();
        require_once __DIR__ . '/../views/aboutstore/index.php';
    }

    // Show create form / handle create
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $imagePath = null;
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $imagePath = $this->uploadImage($_FILES['image']);
                }

                $data = [
                    'title' => trim($_POST['title']),
                    'content' => trim($_POST['content']),
                    'image_path' => $imagePath
                ];

                $this->aboutStoreModel->insert($data);
                $_SESSION['success'] = 'About entry created successfully';
                header('Location: ?controller=aboutstore');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        }
        require_once __DIR__ . '/../views/aboutstore/create.php';
    }

    // Show edit form
    public function edit($id) {
        $about = $this->aboutStoreModel->getById($id);
        if (!$about) {
            $_SESSION['error'] = 'About entry not found';
            header('Location: ?controller=aboutstore');
            exit;
        }
        require_once __DIR__ . '/../views/aboutstore/edit.php';
    }

    // Update about entry
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $about = $this->aboutStoreModel->getById($id);
            if (!$about) {
                $_SESSION['error'] = 'About entry not found';
                header('Location: ?controller=aboutstore');
                exit;
            }

            $image_path = $about['image_path'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                if (!empty($image_path) && file_exists('../public' . '/' . ltrim($image_path, '/'))) {
                    @unlink('../public' . '/' . ltrim($image_path, '/'));
                }
                $image_path = $this->uploadImage($_FILES['image']);
            }

            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'image_path' => $image_path
            ];

            $this->aboutStoreModel->update($id, $data);
            $_SESSION['success'] = 'About entry updated successfully';
            header('Location: ?controller=aboutstore');
            exit;
        }
    }

    // Delete about entry
    public function delete($id) {
        $about = $this->aboutStoreModel->getById($id);
        if ($about && !empty($about['image_path']) && file_exists('../public' . '/' . ltrim($about['image_path'], '/'))) {
            @unlink('../public' . '/' . ltrim($about['image_path'], '/'));
        }
        $this->aboutStoreModel->delete($id);
        $_SESSION['success'] = 'About entry deleted successfully';
        header('Location: ?controller=aboutstore');
        exit;
    }

    // Helper method to handle image upload
    private function uploadImage($file) {
        $uploadsDir = '../public/uploads/about';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }
        $fileName = time() . '_' . basename($file['name']);
        $targetFile = rtrim($uploadsDir, '/\\') . '/' . $fileName;
        $check = getimagesize($file['tmp_name']);
        if ($check === false) {
            throw new Exception('File is not an image.');
        }
        if ($file['size'] > 5000000) {
            throw new Exception('File too large (max 5MB).');
        }
        $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','gif'])) {
            throw new Exception('Only JPG, JPEG, PNG, GIF allowed.');
        }
        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            throw new Exception('Error uploading file.');
        }
        // Return web path relative to public
        return 'uploads/about/' . $fileName;
    }
}
