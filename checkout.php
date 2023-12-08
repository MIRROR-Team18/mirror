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
        <?php
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION["userID"])) {
                echo "<p>You must be logged in to check out.</p>";
                echo "<p><a href='./login.php'>Login</a></p>";
                exit();
            } else if (!isset($_SESSION["basket"]) || count($_SESSION["basket"]) === 0) {
				echo "<p>Your basket is empty.</p>";
				echo "<p><a href='./products'>Go to get some products first!</a></p>";
				exit();
			}
        ?>
        <p>Enter your email address to proceed.</p>
        <p>We need your email address to send updates about your order.</p> <br>
        <form method="post">
            <label for="email" class="label-large">Email:</label>
            <input type="email" id="email" name="email" class="input-large" required>
            <br>
            <input type="hidden" name="continue" value="yeah">
            <button type="submit">Continue</button>
        </form>
    </div>
</div>
    
    <?php include '_components/footer.php'; ?>

    <?php
    // Check if the form is submitted and the "Continue" button is clicked
    if (isset($_POST['continue'])) {
        require_once '_components/database.php';
        $db = new Database();

        $parsedQuantities = json_decode($_COOKIE['quantities'], TRUE);
        $db->createOrder($_SESSION['userID'], $_SESSION['basket'], $parsedQuantities);

		// Redirect to processedCheckout.php
        header('Location: processedCheckout.php');
        exit(); // Make sure to stop further execution
    }
    ?>


</body>
</html>

