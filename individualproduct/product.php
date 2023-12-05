<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        
    <img src="mirrorlogo.png" alt="Store Logo" class="store-logo">
        <p>Welcome to our online store</p>
        <div class="header-links">
            <a href="#">Home</a>
            <a href="#">Product</a>
            <a href="#">Contact</a>
            <a href="#">Refund</a>
            <a href="#">About Us</a>
        </div>
    </header>

    <div class="container">
        <?php
           <?php
           include 'db_connect.php';
           
           // This will get product ID from URL
           $product_id = isset($_GET['id']) ? $_GET['id'] : 0;
           
           // This will fetch product from the database
           $sql = "SELECT * FROM products WHERE id = $product_id";
           $result = $conn->query($sql);
           
           if ($result->num_rows > 0) {
               
               while($row = $result->fetch_assoc()) {
                   echo "<h1>" . $row["name"] . "</h1>";
                   echo "<p>" . $row["description"] . "</p>";
                   echo "<p>Price: $" . $row["price"] . "</p>";
                   
               }
           } else {
               echo "Product not found";
           }
           $conn->close();
           ?>
           
    </div>

    <footer class="footer">
        <div class="footer-links">
        <a href="#">Home</a>
        <a href="#">Product</a>
        <a href="#">Contact</a>
        <a href="#">Refund</a>
        <a href="#">About Us</a>
        </div>
        <p>Contact us at: mirror_refund@gmail.com</p>
        <p>&copy; 2023 Your Store Name. All rights reserved.</p>
        
</footer>
</body>
</html>
