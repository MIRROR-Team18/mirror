<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - MIЯЯOR</title>
    <link rel="stylesheet" href="_stylesheets/main.css">
    <link rel="stylesheet" href="./_stylesheets/basket.css">
</head>
<body>
    <?php include '_components/header.php'; ?>

<div class="checkout-container">
    <div class="checkout-content">
        <h1>Checkout</h1>
        <p>Enter your email address to proceed.</p>
        <p>We need your email address to send updates about your order.</p> <br>
        <form action="processedCheckout.php" method="post">
            <label for="email" class="label-large">Email:</label>
            <input type="email" name="email" class="input-large" required>
            <br> </br>
            <button type="submit">Continue</button>
        </form>
    </div>
</div>
    
    <?php include '_components/footer.php'; ?>

    <?php
    // Check if the form is submitted and the "Continue" button is clicked
    if (isset($_POST['continue'])) {
        // Redirect to processedCheckout.php
        header('Location: processedCheckout.php');
        exit(); // Make sure to stop further execution
    }
    ?>


</body>
</html>

