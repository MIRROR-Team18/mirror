<!DOCTYPE html>
<html lang="en">

<head>
	<?php include '_components/default.php'; ?>
    <title>Basket - MIRЯOR</title>
    <link rel="stylesheet" href="_stylesheets/basket.css">
</head>

<body>
<?php include '_components/header.php'; ?>

<main>
    <h1>
        <i class="fa-solid fa-basket-shopping"></i>
        BASKET
    </h1>
    <section class="basket-container">
        <table id="basket">
            <tbody>
			<?php
			require_once '_components/database.php';
			if (session_status() === PHP_SESSION_NONE) session_start();
			$total = 0; // Used at the bottom

			if (isset($_SESSION['basket'])) {
				// Fixes __PHP_Incomplete_Class_Name. Did you know I dislike PHP? - Pawel
				$basket = array_map(function ($item) {
					return unserialize(serialize($item));
				}, $_SESSION['basket']);

				$MAX_QUANTITY = 10;
				$quantityOptions = "";
				for ($i = 1; $i <= $MAX_QUANTITY; $i++) {
					$quantityOptions .= "<option value='$i'>$i</option>";
				}

				foreach ($basket as $item) {
					/** @var Product $item */
                    $photo = Database::findPrimaryProductImageUrl($item->productID);

                    $size = reset($item->sizes); // Get the first (chosen) size
					$sizeName = $size->name ?? "One Size";
					$sizePrice = $size->price ?? 0;

					echo <<<EOT
                        <tr id="{$item->productID}">
                            <td><img src="{$photo}" alt="{$item->name}" class="product-image"></td>
                            <td>
                                <select id="quantity-{$item->productID}">$quantityOptions</select>
                            </td>
                            <td>
                                <p class="name">{$item->name}</p>
                                <p class="size">Size: {$sizeName}</p>
                            </td>
                            <td class="price" data-original-price="{$sizePrice}">£{$sizePrice}</td>
                        </tr>      
                    EOT;

					$total += $sizePrice;
				}
			} else {
				echo "<tr><td colspan='4'>Your basket is empty!</td></tr>";
			}
			?>
            </tbody>
        </table>


        <!-- Order Summary Section -->
        <div id="order-summary">
            <p>
                <i class="fa-solid fa-leaf"></i>
                you saved <span class="green">0kg</span> of CO<sub>2</sub> emissions!
            </p>
            <h2>Total: £<span id="totalPrice"><?= $total ?></span></h2>
        </div>
    </section>
    <button onclick="storeQuantityData()" id="continue-to-checkout">Continue to Checkout</button>
</main>
<script>
	function storeQuantityData() {
		const basket = document.getElementById("basket");
		const quantities = {};
		for (let i = 0; i < basket.rows.length; i++) {
			if (basket.rows[i].cells.length === 1) continue; // Skip if the row is empty
			const quantityInput = basket.rows[i].getElementsByTagName("select")[0];
			quantities[`${basket.rows[i].id.replace("quantity-", "")}`] = quantityInput.value
		}
		// Save cookie
		document.cookie = `quantities=${JSON.stringify(quantities)}; path=/`;
		window.location.href = "checkout.php";
	}

	function updatePrice(productId) {
		const select = document.getElementById(`quantity-${productId}`);
		const priceSpan = select.parentElement.parentElement.querySelector(".price");
		const pricePerItem = priceSpan.dataset.originalPrice;
		const quantity = parseInt(select.value);
		const price = (pricePerItem * quantity).toFixed(2);
		priceSpan.innerText = `£${price}`;
		calculateTotal();
	}

	function calculateTotal() {
		let totalPrice = 0;
		document.querySelectorAll("select").forEach(select => {
            const pricePerItem = select.parentElement.parentElement.querySelector(".price").innerText.replace("£", "");
            const quantity = parseInt(select.value);
			totalPrice += pricePerItem * quantity;
        });

		// Update the total price in the order summary section
		const totalPriceElement = document.getElementById("totalPrice");
		totalPriceElement.innerText = totalPrice.toFixed(2);
	}

	// Don't need to do calculations on page load, as PHP does that for us
	document.querySelectorAll("select").forEach(select => select.addEventListener("change", () => {
		calculateTotal();
		updatePrice(select.id.substring(9)); // Extract product ID from select element ID
	}));
</script>

<?php include '_components/footer.php'; ?>
</body>

</html> 
