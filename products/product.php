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
        <img src="<?= Database::findPrimaryProductImageUrl($product->productID); ?>" alt="Product Image">
    </div>
    <div class="product-details">
        <div class="topHalf">
            <h1>
				<?= $product->isSustainable ? "<i class='fa-solid fa-leaf'></i>" : "" ?>
				<?= $product->name ?>
            </h1>
            <p><?= $product->description !== "" ? $product->description : "Looks like this product doesn't come with a description... Well, we're sure it's still awesome." ?></p>

            <h2>SIZES</h2>
            <div class="product-sizes">
				<?php
				$sizes = $product->sizes;
				foreach ($sizes as $size) {
					/** @var $size Size */
					$isDisabled = $size->stock === 0 ? "disabled" : "";
					echo <<<HTML
                    <button data-price="$size->price" data-stock="$size->stock" $isDisabled>$size->name</button>
                HTML;
				}
				?>
            </div>
            <p id="stockIndicator"></p>
        </div>
        <div class="bottomHalf">
            <div class="product-price">
                <span>Click on a size...</span>
            </div>
            <button class="add-to-cart">Add to Cart</button>
        </div>
    </div>
</main>
<script>
    document.querySelectorAll(".product-sizes button").forEach(button => {
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
