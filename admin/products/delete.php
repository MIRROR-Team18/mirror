<?php
	require_once "../_components/adminCheck.php";
	require_once "../../_components/database.php";

	if (!isset($_GET['id'])) {
		exit("ID not provided for deletion!");
	}

	// Delete from DB first
	$db = new Database();
	$result = $db->deleteProduct($_GET['id']);

	// Delete folder of images
	$dir = "../../_images/products/" . $_GET['id'];
	if (is_dir($dir)) {
		$files = scandir($dir);
		foreach ($files as $file) {
			if ($file != "." && $file != "..") {
				unlink($dir . "/" . $file);
			}
		}
		rmdir($dir);
	}

	if (!$result) exit ("Failed to delete product.");
	else header("Location: ./");