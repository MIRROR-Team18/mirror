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
    <link rel="stylesheet" href="../_stylesheets/main.css">
    <link rel="stylesheet" href="../_stylesheets/product.css">
</head>
<body>
<?php include '../_components/header.php'; ?>

<div class="container">
	<?php
	// This will get product ID from URL
    if (!isset($_GET['id'])) {
        header("Location: /products");
        exit();
    }
	$product_id = $_GET['id'];

	// This will fetch product from the database
	$product = $db->getProduct($product_id);

	if (!is_null($product)) {
		echo "<h1>" . $product->name . "</h1>";
		echo "<p>" . $product->type . "</p>";
		echo "<p>Price: $2.00</p>";

		echo "<form action='' method='post'>";
		echo "<input type='hidden' name='product_id' value='" . $product_id . "'>";
		echo "<input type='submit' value='Add to Cart'>";
		echo "</form>";


	} else {
		echo "Product not found";
	}
	?>
</div>

<?php include '../_components/footer.php'; ?>
</body>
</html>
