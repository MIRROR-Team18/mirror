<?php

session_start();

 // connect to the database
  require_once('./_components/database.php');
  $db = new Database();

// Generate a random number from 1000-9999
$captcha = rand(10000, 99999);

// The captcha will be stored for the session
$_SESSION["captcha"] = $captcha; 

// Generate a 90x64 standard captcha image
$image = imagecreatetruecolor(90, 64); 

// White color for the image text
$white = imagecolorallocate($image, 255, 255, 255);

// Give the image a black background
imagefill($image, 0, 0, 0); 

//imagestring will print the captcha text within the image, with a position and size.
imagestring($image, 20, 30, 40, $captcha, $white);

// used to prevent any Browser Cache!!
header("Cache-Control: no-store, no-cache, must-revalidate"); 

// makes the PHP-file rendered as an image
header('Content-type: image/png');

//the captcha will be ouput as a PNG
imagepng($image); 

// destroys the image
imagedestroy($image);
?>
