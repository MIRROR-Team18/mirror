<?php
require_once '../_components/database.php';
$db = new Database();

if (isset($_POST['product_id'])) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['basket'])) $_SESSION['basket'] = array();

    $product = $db->getProduct($_POST['product_id']);
    $_SESSION['basket'][] = $product;
}

// Ensure $product is initialized to prevent errors
$product = $db->getProduct($_GET['id']);
if (is_null($product)) {
    header('Location: /products');
    exit();
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
<?php include '../_components/header.php'; ?>
<main class="product-container">
    <div class="product-image">
        <!-- Place your product image here -->
        <?php
        // Ensure $img is initialized before usage
        $img = Database::findAllProductImageUrls($product->productID);
        ?>
        <img src="<?= $img[2] ?>" alt="Product Image">
    </div>
    <div class="product-details">
        <h1><?= $product->name ?></h1>
        <!-- Product description here -->
        <p><?= $product->description ?></p>

        <div class="product-sizes">
            <span>SIZES</span>
            <!-- Adding size buttons -->
            <button data-price="23.99" data-stock="10">Small</button>
            <button data-price="25.99" data-stock="8">Medium</button>
            <button data-price="27.99" data-stock="5">Large</button>
            <button data-price="29.99" data-stock="3">Extra Large</button>
            <p id="stockIndicator"></p>
        </div>

        <div class="product-price">
            <span>Click on a size...</span>
        </div>

        <button class="add-to-cart">Add to Cart</button>
    </div>
</main>
<script>
    document.querySelectorAll("button").forEach(button => {
        button.addEventListener("click", () => {
            const price = button.getAttribute("data-price");
            const stock = button.getAttribute("data-stock");
            document.querySelector(".product-price span").innerHTML = `Price: £${price}`;
            document.querySelector("#stockIndicator").innerHTML = `Stock: ${stock} available`;
            document.querySelector(".add-to-cart").setAttribute("data-price", price); // Add price attribute to the add-to-cart button
        });
    });

    document.querySelector(".add-to-cart").addEventListener("click", () => {
        const selectedSize = document.querySelector(".product-sizes button:focus");
        if (selectedSize) {
            const price = selectedSize.getAttribute("data-price");
            const productId = selectedSize.getAttribute("data-product-id");
            // You can now use the price and productId to add the product to the basket
            console.log("Price:", price);
            console.log("Product ID:", productId);
        } else {
            console.log("Please select a size.");
        }
    });
</script>

<?php include '../_components/footer.php'; ?>
</body>
</html>
