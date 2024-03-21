
<?php
require_once '../_components/database.php';
$db = new Database();

if (isset($_POST['product_id'], $_POST['size'])) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['basket'])) $_SESSION['basket'] = array();

    $product = $db->getProduct($_POST['product_id']);
    $size = $_POST['size'];

    // Add the product and selected size to the session basket
    $product_with_size = (object) ['product' => $product, 'size' => $size];
    $_SESSION['basket'][] = $product_with_size;

    // Redirect to prevent duplicate submissions on page refresh
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}

$product = $db->getProduct($_GET['id']);
$sizes = ['S', 'M', 'L', 'XL']; // Example sizes, replace with actual sizes from your database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../_components/default.php'; ?>
    <title>Product Page</title>
    <link rel="stylesheet" href="../_stylesheets/product.css?v=1.1">
</head>
<body>
<?php include '../_components/header.php'; ?>
<main class="product-container">
    <div class="product-image">
        <?php $img = Database::findAllProductImageUrls($product->productID); ?>
        <img src="<?= $img[2] ?>" alt="Product Image">
    </div>
    <div class="product-details">
        <h1><?= $product->name ?></h1>
        <p>Product description...</p>
        <form id="add-to-cart-form" action="" method="post">
            <div class="product-sizes">
                <span>SIZES</span>
                <?php foreach ($sizes as $size) : ?>
                    <button type="button" class="size-button" data-size="<?= $size ?>"><?= $size ?></button>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="product_id" value="<?= $product->productID ?>">
            <input type="hidden" id="selected-size" name="size">
            <div class="product-price">
                <span>£<?= $product->price ?></span>
            </div>
            <button type="submit" class="add-to-cart">Add to Cart</button>
        </form>
    </div>
</main>
<?php include '../_components/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sizeButtons = document.querySelectorAll(".size-button");
        const sizeInput = document.getElementById("selected-size");

        sizeButtons.forEach(button => {
            button.addEventListener("click", () => {
                sizeButtons.forEach(btn => btn.classList.remove("selected"));
                button.classList.add("selected");
                sizeInput.value = button.dataset.size;
            });
        });
    });
</script>
</body>
</html>