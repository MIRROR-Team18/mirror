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

		require_once '_components/database.php';
		$db = new Database();
		$parsedQuantities = json_decode($_COOKIE['quantities'], TRUE);

		// Check if the form is submitted and the "Pay Now" button is clicked
		if (isset($_POST['continue'])) {
			// $parsedQuantities is defined much earlier in the code
			$addressID = $db->createOrGetAddress($_POST['first-name'] . " " . $_POST['last-name'], $_POST['address-line-1'], $_POST['address-line-2'], $_POST['address-line-3'], $_POST['city'], $_POST['postcode'], $_POST['country']);
			$db->createOrder($_SESSION['userID'], $_SESSION['basket'], $parsedQuantities, $addressID);

			// Redirect to done.php
			header('Location: done.php');
			exit();
		}
		?>

        <div class="sidebar">
            <div class="products">
				<?php
                $total = 0;
                $basket = array_map(function ($item) {
                    return unserialize(serialize($item));
                }, $_SESSION['basket']);

                foreach ($basket as $item) {
                    $photo = $db->findPrimaryProductImageUrl($item->productID);
                    $size = reset($item->sizes);
                    $sizeName = $size->name ?? "One Size";
                    $sizePrice = $size->price ?? 0;
                    $quantity = $parsedQuantities[$item->productID][$size->sizeID];
                    $total += $sizePrice * $quantity;
                    echo <<<HTML
                        <img src="{$photo}" alt="{$item->name}" class="product-image">
                    HTML;
                }
				?>
            </div>
            <div class="total">
                £<?php echo number_format($total, 2); ?>
            </div>
        </div>

        <form method="POST" id="checkoutForm" action="">
            <div class="deliveryOptions formSection">
                <h2>DELIVERY OPTIONS</h2>
                <input type="radio" id="evri" name="delivery-option" value="evri">
                <label for="evri">Evri (3-5 days)</label>

                <input type="radio" id="courier" name="delivery-option" value="courier">
                <label for="courier">Local Courier (5-7 days)<i class="fa-solid fa-leaf"></i></label>
            </div>

            <div class="card-details formSection">
                <h2>CARD DETAILS</h2>
                <input type="text" name="card-number" placeholder="Card Number" required aria-label="Card Number">
                <input type="text" name="card-name" placeholder="Name" required aria-label="Cardholder Name">
                <div class="row">
                    <input type="text" name="card-expiry" placeholder="MM/YY" required aria-label="Card Expiry Date">
                    <input type="text" name="card-cvv" placeholder="CVV" required aria-label="Card Security Code">
                </div>
                <input type="checkbox" name="different-billing" id="different-billing">
                <label for="different-billing">Different billing address?</label>
            </div>

            <div class="delivery-address formSection">
                <h2>DELIVERY ADDRESS</h2>
                <div class="row">
                    <input type="text" name="first-name" placeholder="First Name" required aria-label="First Name">
                    <input type="text" name="last-name" placeholder="Last Name" required aria-label="Last Name">
                </div>
                <input type="text" name="address-line-1" placeholder="Address Line 1" required aria-label="Delivery Address Line 1">
                <input type="text" name="address-line-2" placeholder="Address Line 2" aria-label="Delivery Address Line 2">
                <input type="text" name="address-line-3" placeholder="Address Line 3" aria-label="Delivery Address Line 3">
                <div class="row">
                    <input type="text" name="city" placeholder="City" required aria-label="Delivery Address City">
                    <input type="text" name="postcode" placeholder="Postcode" required aria-label="Delivery Address Postcode">
                </div>
                <input type="text" name="country" placeholder="Country" required aria-label="Delivery Address Country">
            </div>

            <!-- Billing Address -->
            <div class="billing-address formSection" id="billing-address" style="display: none;">
                <h2>BILLING ADDRESS</h2>
                <!-- Billing address form fields -->
                <div class="row">
                    <input type="text" name="billing-first-name" placeholder="First Name" required aria-label="Billing Address First Name">
                    <input type="text" name="billing-last-name" placeholder="Last Name" required aria-label="Billing Address Last Name">
                </div>
                <input type="text" name="billing-address-line-1" placeholder="Address Line 1" required aria-label="Billing Address Line 1">
                <input type="text" name="billing-address-line-2" placeholder="Address Line 2" aria-label="Billing Address Line 2">
                <input type="text" name="billing-address-line-3" placeholder="Address Line 3" aria-label="Billing Address Line 3">
                <div class="row">
                    <input type="text" name="billing-city" placeholder="City" required aria-label="Billing Address City">
                    <input type="text" name="billing-postcode" placeholder="Postcode" required aria-label="Billing Address Postcode">
                </div>
                <input type="text" name="billing-country" placeholder="Country" required aria-label="Billing Address Country">
            </div>
            <input type="hidden" name="continue" value="yeah">
        </form>
        <button id="payNowBtn">PAY NOW</button>
    </div>
</main>

<script>
	// Find the Pay Now button by its ID and add a click event listener
	document.getElementById("payNowBtn").addEventListener("click", function () {
		// Submit form
		document.querySelector("#checkoutForm").submit();
	});

	// Find the checkbox for different billing address
	const differentBillingCheckbox = document.getElementById("different-billing");
	// Find the billing address section
	const billingAddressForm = document.getElementById("billing-address");

	// Add event listener to the checkbox
	differentBillingCheckbox.addEventListener("change", function () {
		// Toggle the visibility of the billing address section based on checkbox state
		billingAddressForm.style.display = this.checked ? "flex" : "none";
	});
</script>
<?php include '_components/shortFooter.php'; ?>
</body>
</html>
