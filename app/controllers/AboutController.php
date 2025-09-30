<?php
class AboutController {
    private $aboutStoreModel;
    
    public function __construct() {
        // Obtain the global database instance to match how controllers are instantiated
        $db = isset($GLOBALS['db']) ? $GLOBALS['db'] : null;
        $this->aboutStoreModel = new AboutStore($db);
    }

    // Display about page
    public function index() {
        $about_entries = $this->aboutStoreModel->getAll();
        require_once __DIR__ . '/../views/about/index.php';
    }
}
