<?php
class AboutController {
    private $aboutStoreModel;
    
    public function __construct($db) {
        $this->aboutStoreModel = new AboutStore($db);
    }

    // Display about page
    public function index() {
        $about_entries = $this->aboutStoreModel->getAll();
        require_once __DIR__ . '/../views/about/index.php';
    }
}
