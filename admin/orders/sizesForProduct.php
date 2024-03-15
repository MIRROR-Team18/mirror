<?php // This is an API endpoint. Consider moving this to a separate folder.
session_start();

// Check if user is logged in and admin
if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
	exit(json_encode(['error' => 'You do not have permission to access this page.'], ['code' => 403]));
}

require_once "../../_components/database.php";
$db = new Database();

// If we're getting a product's sizes, we need to know which product.
if (isset($_GET['productID'])) {
	$productID = $_GET['productID'];
	// Get the sizes for the product. $db->getSizesOfProduct() is private so we have to do this.
	$sizes = $db->getProduct($productID)->sizes;
	echo json_encode($sizes);
	// Example output: {"3":{"sizeID":"3","name":"M","isKids":false,"price":4},"4":{"sizeID":"4","name":"L","isKids":false,"price":5}}
} else {
	echo json_encode([]);
}