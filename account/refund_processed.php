<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Here I need to validate and process the refund request
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
    // Redirect users to the refund page if they try to access refund_processed.php directly
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
    <link rel="stylesheet" href="../_stylesheets/main.css">
    <link rel="stylesheet" href="../_stylesheets/refund.css">
</head>
<body>


    <?php include '../_components/header.php'; ?>



    <div class="container">
        <h2>Refund Request Confirmation</h2>
    
            <p>Thank you for contacting the refund team. Your refund request has been sent, and we will contact you regarding your order very soon.</p>
            <p>Thank you</p>
    
        <a href="refund.php">Back to Refund Page</a>
    </div>





	<?php include '../_components/footer.php'; ?>
</body>
</html>

