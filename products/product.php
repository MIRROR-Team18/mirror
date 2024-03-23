<?php
require_once '../_components/database.php';
$db = new Database();

if (isset($_POST['product_id'])) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['basket'])) $_SESSION['basket'] = array();

    $product = $db->getProduct($_POST['product_id']);
    $product->sizes = array_filter($product->sizes, fn($size) => $size->sizeID == $_POST['size_id']);
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
                    <button data-id="$size->sizeID" data-price="$size->price" data-stock="$size->stock" $isDisabled>$size->name</button>
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
            document.querySelector(".product-price span").innerHTML = `£${price}`;
            document.querySelector("#stockIndicator").innerHTML = `Stock: ${stock} available`;

            document.querySelectorAll(".product-sizes button").forEach(button => button.classList.remove("selected"));
			button.classList.add("selected");
        });
    });

    document.querySelector(".add-to-cart").addEventListener("click", async () => {
        const selectedSize = document.querySelector(".product-sizes button.selected");
        if (selectedSize) {
            await fetch("/products/product.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({
                    product_id: "<?= $product->productID ?>",
                    size_id: selectedSize.getAttribute("data-id")
                })
            });

            window.location.href = "/basket.php";
        } else {
            console.log("Please select a size.");
        }
    });
</script>

<?php include '../_components/footer.php'; ?>
</body>
</html>
