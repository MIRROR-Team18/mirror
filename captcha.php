<?php

// We start a session to access
// the captcha externally!
session_start();

 // connect to the database
  //require_once('./_components/database.php');
  //$db = new Database();

// Generate a random number
// from 1000-9999
$captcha = rand(10000, 99999);

// The captcha will be stored
// for the session
$_SESSION["captcha"] = $captcha; 

// Generate a 90x64 standard captcha image
$image = imagecreatetruecolor(90, 64); 

// White color
$white = imagecolorallocate($image, 255, 255, 255);

// Give the image a blue background
imagefill($image, 0, 0, 0); 

// Print the captcha text in the image
// with random position & size
// Adjusted font size and position for better visibility
imagestring($image, 5, 10, 20, $captcha, $white);

// VERY IMPORTANT: Prevent any Browser Cache!!
header("Cache-Control: no-store, no-cache, must-revalidate"); 

// The PHP-file will be rendered as image
header('Content-type: image/png');

// Finally output the captcha as
// PNG image the browser
imagepng($image); 

// Free memory
imagedestroy($image);
?>
