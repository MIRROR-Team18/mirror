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
           include '../_components/database.php';
           $db = new Database();

           // This will get product ID from URL
           $product_id = isset($_GET['id']) ? $_GET['id'] : 0;
           
           // This will fetch product from the database
           $product = $db->getProduct($product_id);
           
           if (!is_Null($product)) {
               echo "<h1>" . $product->name . "</h1>";
               echo "<p>" . $product->type . "</p>";
               echo "<p>Price: $2.00</p>";

           } else {
               echo "Product not found";
           }
       ?>
    </div>

    <?php include '../_components/footer.php'; ?>
</body>
</html>
