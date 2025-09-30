<?php
// Set the content-type
header('Content-Type: image/png');

// Create a 200x200 image
$image = imagecreatetruecolor(200, 200);

// Allocate colors
$bgColor = imagecolorallocate($image, 245, 245, 245);
$textColor = imagecolorallocate($image, 150, 150, 150);
$borderColor = imagecolorallocate($image, 220, 220, 220);

// Fill the background
imagefill($image, 0, 0, $bgColor);

// Add a border
imagerectangle($image, 0, 0, 199, 199, $borderColor);

// Path to font file (using a system font)
$font = 'C:/Windows/Fonts/arial.ttf';

// The text to draw
$text = 'BRAND';

// Get the size of the text
$textBox = imagettfbbox(20, 0, $font, $text);
$textWidth = $textBox[4] - $textBox[6];
$textHeight = $textBox[3] - $textBox[5];

// Calculate position to center the text
$x = (200 - $textWidth) / 2;
$y = (200 - $textHeight) / 2 + $textHeight;

// Add the text
imagettftext($image, 20, 0, $x, $y, $textColor, $font, $text);

// Save the image
imagepng($image, __DIR__ . '/default-brand.png');

// Free up memory
imagedestroy($image);

echo "Default brand image generated successfully!\n";
?>
