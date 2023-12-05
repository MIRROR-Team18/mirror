<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request</title>
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
        <h2>Refund Request</h2>
        <form action="process_refund.php" method="post">
            <label for="order_number">Order Number:</label>
            <input type="text" id="order_number" name="order_number" required>

            <label for="reason">Reason for Refund:</label>
            <textarea id="reason" name="reason" rows="4" required></textarea>

            <input type="submit" value="Submit Refund Request">
        </form>
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
