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
    <?php include '../_components/default.php'; ?>
    <title>Product Page</title>
    <link rel="stylesheet" href="../_stylesheets/product.css?v=1.1">
</head>
<body>
<?php include '../_components/header.php';
    $product = $db->getProduct($_GET['id']);
?>
<main class="product-container">
    <div class="product-image">
        <!-- Place your product image here -->
        <?php
            $img = Database::findAllProductImageUrls($product->productID);
        ?>
        <img src="<?= $img[2] ?>" alt="Product Image">
    </div>
    <div class="product-details">
        <h1><?= $product->name ?></h1>
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

