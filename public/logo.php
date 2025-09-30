<?php
/**
 * Serve logo images
 */

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if logo parameter is provided
if (!isset($_GET['logo']) || empty($_GET['logo'])) {
    header('HTTP/1.0 400 Bad Request');
    exit('Logo not specified');
}

// Get the logo filename
$logoFile = basename($_GET['logo']);
$basePath = dirname(dirname(__DIR__));
$uploadPath = $basePath . '/public/uploads/';
$logoPath = $uploadPath . $logoFile;

// Log the paths for debugging
error_log("Base path: " . $basePath);
error_log("Upload path: " . $uploadPath);
error_log("Logo path: " . $logoPath);

// Check if the upload directory exists
if (!is_dir($uploadPath)) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Upload directory does not exist');
}

// Check if the file exists and is readable
if (!file_exists($logoPath) || !is_readable($logoPath)) {
    header('HTTP/1.0 404 Not Found');
    exit('Logo file not found or not readable: ' . $logoPath);
}

// Get the file info
$finfo = finfo_open(FILEINFO_MIME_TYPE);
if ($finfo === false) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Failed to open fileinfo');
}

$mimeType = finfo_file($finfo, $logoPath);
finfo_close($finfo);

if ($mimeType === false) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Failed to determine file type');
}

// Set the appropriate headers
header('Content-Type: ' . $mimeType);
header('Content-Length: ' . filesize($logoPath));
header('Cache-Control: public, max-age=31536000');
header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');

// Output the file
if (@readfile($logoPath) === false) {
    header('HTTP/1.0 500 Internal Server Error');
    exit('Failed to read file');
}
