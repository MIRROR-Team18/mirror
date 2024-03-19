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
            <!--<h1>Checkout</h1>-->
            <?php
            if (session_status() === PHP_SESSION_NONE)
                session_start();
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

            <form method="post">

            
    <label for="email" class="label-large">Email:</label>
    <input type="email" id="email" name="email" class="input-large" required>
    <br>

    <!-- Dummy Card Details -->
    <label for="card_number" class="label-large">Card Number:</label>
    <input type="text" id="card_number" name="card_number" class="input-large" placeholder="4242 4242 4242 4242" required>
    <br>

    <label for="cardholder_name" class="label-large">Cardholder's Name:</label>
    <input type="text" id="cardholder_name" name="cardholder_name" class="input-large" placeholder="John Doe" required>
    <br>

    <label for="expiry_date" class="label-large">Expiry Date (MM/YY):</label>
    <input type="text" id="expiry_date" name="expiry_date" class="input-large" placeholder="12/23" required>
    <br>

    <label for="cvv" class="label-large">CVV:</label>
    <input type="text" id="cvv" name="cvv" class="input-large" placeholder="123" required>
    <br>
    <!-- End Dummy Card Details -->

    <!--Pay Now Button -->
    <input type="hidden" name="continue" value="yeah">
    <button type="submit">Continue</button>
</form>

                        <input type="radio" id="evri" name="delivery-option" value="evri">
                        <label for="evri">Evri (3-5 days)</label><br>

                        <input type="radio" id="courier" name="delivery-option" value="courier">
                        <label for="courier">Local Courier (5-7 days)</label><br>
                    </div>
                    <br><br><br>
                    <div class="delivery-address">
                        <h2>DELIVERY ADDRESS</h2>
                        <input type="text" name="first-name" placeholder="First Name" required>
                        <input type="text" name="last-name" placeholder="Last Name" required><br>
                        <input type="text" name="address-line-1" placeholder="Address Line 1" required>
                        <input type="text" name="address-line-2" placeholder="Address Line 2"><br>
                        <input type="text" name="address-line-3" placeholder="Address Line 3">
                        <input type="text" name="city" placeholder="City" required><br>
                        <input type="text" name="country" placeholder="Country" required>
                        <input type="text" name="postcode" placeholder="Postcode" required>
                    </div>
                    <br><br>
                    <div class="card-details">
                        <h2>CARD DETAILS</h2>
                        <input type="text" name="card-number" placeholder="Card Number" required>
                        <input type="text" name="card-name" placeholder="Name" required> <br>
                        <input type="text" name="card-expiry" placeholder="MM/YY" required>
                        <input type="text" name="card-cvv" placeholder="CVV" required>
                        <br><br>
                        <input type="checkbox" name="different-billing" id="different-billing">
                        <label for="different-billing">Different billing address?</label>
                        <br><br>
                    </div>

                    <!-- Billing Address -->
                    <div class="billing-address" id="billing-address" style="display: none;">
                        <h2>BILLING ADDRESS</h2>
                        <!-- Billing address form fields -->
                        <div class="billing-address-form">
                            <input type="text" name="billing-first-name" placeholder="First Name" required>
                            <input type="text" name="billing-last-name" placeholder="Last Name" required><br>
                            <input type="text" name="billing-address-line-1" placeholder="Address Line 1" required>
                            <input type="text" name="billing-address-line-2" placeholder="Address Line 2"><br>
                            <input type="text" name="billing-address-line-3" placeholder="Address Line 3">
                            <input type="text" name="billing-city" placeholder="City" required><br>
                            <input type="text" name="billing-country" placeholder="Country" required>
                            <input type="text" name="billing-postcode" placeholder="Postcode" required>
                        </div>
                    </div>
                </div>
        </div>
        </form>
        <br><br><br><br>
        <input type="hidden" name="continue" value="yeah">
        <button id="payNowBtn" type="submit"> PAY NOW </button>
    </div>
    </div>
    <br><br><br><br>


    <script>
        // Find the Pay Now button by its ID
        var payNowBtn = document.getElementById("payNowBtn");

        // Add a click event listener to the Pay Now button
        payNowBtn.addEventListener("click", function () {
            // Redirect the user to the order confirmation page
            window.location.href = "processedCheckout.php";
        });

        // Find the checkbox for different billing address
        var differentBillingCheckbox = document.getElementById("different-billing");

        // Find the billing address section
        var billingAddressForm = document.getElementById("billing-address");

        // Add event listener to the checkbox
        differentBillingCheckbox.addEventListener("change", function () {
            // Toggle the visibility of the billing address section based on checkbox state
            billingAddressForm.style.display = this.checked ? "block" : "none";
        });
    </script>



    <?php include '_components/footer.php'; ?>

    <?php
    // Check if the form is submitted and the "Pay Now" button is clicked
    if (isset($_POST['continue'])) {
        require_once '_components/database.php';
        $db = new Database();

        $parsedQuantities = json_decode($_COOKIE['quantities'], TRUE);
        $db->createOrder($_SESSION['userID'], $_SESSION['basket'], $parsedQuantities);

        // Redirect to processedCheckout.php
        header('Location: processedCheckout.php');
        exit(); 
    }
    ?>


</body>

</html>
