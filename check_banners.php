<?php
require_once 'config/database.php';
$db = new Database();
$bannerModel = new Banner($db);
$banners = $bannerModel->getAll('active');
echo "Active Banners: " . count($banners) . "\n\n";
foreach ($banners as $banner) {
    echo "ID: " . $banner['id'] . "\n";
    echo "Title: " . $banner['title'] . "\n";
    echo "Image URL: " . $banner['image_url'] . "\n\n";
}
?>
