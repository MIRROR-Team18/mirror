<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Here i need to validate and process the refund request
    $order_number = htmlspecialchars($_POST["order_number"]);
    $reason = htmlspecialchars($_POST["reason"]);

   
    if (empty($order_number) || empty($reason)) {
        // Redirect back to the refund page with an error message
        header("Location: refund.html?error=empty_fields");
        exit();
    }

    // Simulate checking order existence (replace with database query)
    $order_exists = true; // Replace with your logic to check if the order exists

    if (!$order_exists) {
        // Redirect back to the refund page with an error message
        header("Location: refund.html?error=invalid_order");
        exit();
    }

    // Process refund (simulated output, replace with your logic)
    $refund_message = "Refund request for Order Number $order_number due to $reason";

} else {
    // Redirect users to the refund page if they try to access process_refund.php directly
    header("Location: refund.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<header class="header1">
        
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
        <h2>Refund Request Confirmation</h2>
    
            <p>Thank you for contacting the refund team. Your refund request has been sent and we will contanct you regarding your order very soon.</p>
            <p>Thank you</p>
    
        <a href="refund.php">Back to Refund Page</a>
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

