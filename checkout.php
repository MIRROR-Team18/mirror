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
            /*
            if (!isset($_SESSION["userID"])) {
                echo "<p>You must be logged in to check out.</p>";
                echo "<p><a href='./login.php'>Login</a></p>";
                exit();
            } else if (!isset($_SESSION["basket"]) || count($_SESSION["basket"]) === 0) {
				echo "<p>Your basket is empty.</p>";
				echo "<p><a href='./products'>Go to get some products first!</a></p>";
				exit();
			}
            */
        ?>

<form id="checkout-form" action="">
            <div class="checkout-content">
                <div class="order-summary">
                    <h2>MIRROR</h2>
                    <p>£ 54.70</p>
                </div>
                <div class="delivery-options">
                    <h2>DELIVERY OPTIONS</h2>
                    <input type="radio" id="dpd" name="delivery-option" value="dpd">
                    <label for="dpd">DPD (Next Working Day)</label><br>

                    <input type="radio" id="evri" name="delivery-option" value="evri">
                     <label for="evri">Evri (3-5 days)</label><br>

                    <input type="radio" id="courier" name="delivery-option" value="courier">
                    <label for="courier">Local Courier (5-7 days)</label><br>
                </div>
                <div class="delivery-address">
                    <h2>DELIVERY ADDRESS</h2>
                    <input type="text" name="first-name" placeholder="First Name" required>
                    <input type="text" name="last-name" placeholder="Last Name" required>
                    <input type="text" name="address-line-1" placeholder="Address Line 1" required>
                    <input type="text" name="address-line-2" placeholder="Address Line 2">
                    <input type="text" name="address-line-3" placeholder="Address Line 3">
                    <input type="text" name="city" placeholder="City" required>
                    <input type="text" name="country" placeholder="Country" required>
                    <input type="text" name="postcode" placeholder="Postcode" required>
                </div>
                <br><br>
                <div class="card-details">
                    <h2>CARD DETAILS</h2>
                    <input type="text" name="card-number" placeholder="Card Number" required>
                    <input type="text" name="card-name" placeholder="Name" required>
                    <input type="text" name="card-expiry" placeholder="MM/YY" required>
                    <input type="text" name="card-cvv" placeholder="CVV" required>
                </div>
                <div class="billing-address">
                <br><br>
    <h2>BILLING ADDRESS</h2>
    <input type="checkbox" name="same-address" id="same-address">
    <label for="same-address">Same as delivery address?</label>
    <br><br>
    <div class="billing-address-form">
        <input type="text" name="billing-first-name" placeholder="First Name" required>
        <input type="text" name="billing-last-name" placeholder="Last Name" required>
        <input type="text" name="billing-address-line-1" placeholder="Address Line 1" required>
        <input type="text" name="billing-address-line-2" placeholder="Address Line 2">
        <input type="text" name="billing-address-line-3" placeholder="Address Line 3">
        <input type="text" name="billing-city" placeholder="City" required>
        <input type="text" name="billing-country" placeholder="Country" required>
        <input type="text" name="billing-postcode" placeholder="Postcode" required>
    </div>
</div>
            </div>
            
        </form>
    <br> <br>
    <input type="hidden" name="continue" value="yeah">
    <button id="payNowBtn" type="submit"> PAY NOW </button>
    </div>
</div>


<script>
        // Find the Pay Now button by its ID
        var payNowBtn = document.getElementById("payNowBtn");

        // Add a click event listener to the Pay Now button
        payNowBtn.addEventListener("click", function() {
            // Redirect the user to the order confirmation page
            window.location.href = "processedCheckout.php";
        });
    </script>



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
