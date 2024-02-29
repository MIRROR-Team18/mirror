<?php
require_once '../_components/database.php';
$db = new Database();

if (isset($_POST['product_id'])) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['basket'])) $_SESSION['basket'] = array();

    $product = $db->getProduct($_POST['product_id']);
    $_SESSION['basket'][] = $product;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="../_stylesheets/main.css?v=1.1">
    <link rel="stylesheet" href="../_stylesheets/product.css?v=1.1">
</head>
<body>
<?php include '../_components/header.php'; ?>

<main class="product-container">
    <div class="product-image">
        <!-- Place your product image here -->
        <img src="path_to_your_image.jpg" alt="Product Image">
    </div>
    <div class="product-details">
        <h1>PRODUCT NAME</h1>
        <!-- Product description here -->
        <p>Product description...</p>
        
        <div class="product-sizes">
            <span>SIZES</span>
            <button>S</button>
            <button>M</button>
            <button>L</button>
            <button>XL</button>
            <p>3 items left at this size!</p>
        </div>
        
        <div class="product-price">
            <span>£23.99</span>
        </div>
        
        <button class="add-to-cart">Add to Cart</button>
    </div>
</main>

<?php include '../_components/footer.php'; ?>
</body>
</html>

