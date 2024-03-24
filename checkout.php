<!DOCTYPE html>
<html lang="en">
<head>
	<?php include '_components/default.php'; ?>
    <title>Checkout - MIRЯOR</title>
    <link rel="stylesheet" href="./_stylesheets/basket.css">
</head>

<body>
<?php include '_components/header.php'; ?>

<main class="checkout-container">
    <div class="checkout-content">
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

        <form method="post">
            <div class="deliveryOptions formSection">
                <h2>DELIVERY OPTIONS</h2>
                <input type="radio" id="evri" name="delivery-option" value="evri">
                <label for="evri">Evri (3-5 days)</label><br>

                <input type="radio" id="courier" name="delivery-option" value="courier">
                <label for="courier">Local Courier (5-7 days)</label><br>
            </div>

            <div class="card-details formSection">
                <h2>CARD DETAILS</h2>
                <input type="text" name="card-number" placeholder="Card Number" required>
                <input type="text" name="card-name" placeholder="Name" required>
                <div class="row">
                    <input type="text" name="card-expiry" placeholder="MM/YY" required>
                    <input type="text" name="card-cvv" placeholder="CVV" required>
                </div>
                <br><br>
                <input type="checkbox" name="different-billing" id="different-billing">
                <label for="different-billing">Different billing address?</label>
                <br><br>
            </div>

            <div class="delivery-address  formSection">
                <h2>DELIVERY ADDRESS</h2>
                <div class="row">
                    <input type="text" name="first-name" placeholder="First Name" required>
                    <input type="text" name="last-name" placeholder="Last Name" required>
                </div>
                <input type="text" name="address-line-1" placeholder="Address Line 1" required>
                <input type="text" name="address-line-2" placeholder="Address Line 2">
                <input type="text" name="address-line-3" placeholder="Address Line 3">
                <div class="row">
                    <input type="text" name="city" placeholder="City" required>
                    <input type="text" name="postcode" placeholder="Postcode" required>
                </div>
                <input type="text" name="country" placeholder="Country" required>
            </div>

            <!-- Billing Address -->
            <div class="billing-address formSection" id="billing-address" style="display: none;">
                <h2>BILLING ADDRESS</h2>
                <!-- Billing address form fields -->
                <div class="row">
                    <input type="text" name="billing-first-name" placeholder="First Name" required>
                    <input type="text" name="billing-last-name" placeholder="Last Name" required>
                </div>
                <input type="text" name="billing-address-line-1" placeholder="Address Line 1" required>
                <input type="text" name="billing-address-line-2" placeholder="Address Line 2"><br>
                <input type="text" name="billing-address-line-3" placeholder="Address Line 3">
                <div class="row">
                    <input type="text" name="billing-city" placeholder="City" required><br>
                    <input type="text" name="billing-postcode" placeholder="Postcode" required>
                </div>
                <input type="text" name="billing-country" placeholder="Country" required>
            </div>
        </form>
        <input type="hidden" name="continue" value="yeah">
        <button id="payNowBtn" type="submit">PAY NOW</button>
    </div>
</main>

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
		billingAddressForm.style.display = this.checked ? "flex" : "none";
	});
</script>


<?php include '_components/shortFooter.php'; ?>

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
