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
    if (is_null($product)) {
        header('Location: /products');
        exit();
    }
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
        <p><?= $product->description ?></p>

        <div class="product-sizes">
            <span>SIZES</span>
            <?php
                $sizes = $product->sizes;
                foreach ($sizes as $size) {
                    /** @var $size Size */
                    echo <<<HTML
                    <button data-price="$size->price" data-stock="$size->stock">$size->name</button>
                    HTML;
                }
            ?>
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
        });
    });
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
<?php include '../_components/footer.php'; ?>
</body>
</html>

