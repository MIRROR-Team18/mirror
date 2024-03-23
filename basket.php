<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket - MIЯЯOR</title>
    <link rel="stylesheet" href="_stylesheets/main.css">
    <link rel="stylesheet" href="_stylesheets/basket.css">
</head>

<body>
    <?php include '_components/header.php'; ?>

    <main class="basket-container">
        <h1>Basket</h1><br><br><br>
        <table id="basket">
            <thead>

                <!-- First item -->
                <tr>
                    <td colspan="3">
                        <div class="basket-item">
                        
                            <div class="item-details" width="500" height="600">
                                <h2>Product 1</h2>
                                <p>Available in all colours</p>
                                <label for="quantity1">Quantity:</label>
                                <select id="quantity1" name="quantity1" onchange="updatePrice(1)">
                                    <?php
                                    for ($i = 1; $i <= 10; $i++) {
                                        echo "<option value='{$i}'>{$i}</option>";
                                    }
                                    ?>
                                </select>
                                <p>Price: <span id="price1">£20.99</span></p>
                            </div>
                        </div>
                    </td>
                </tr>

                <!-- Second item -->
                <tr>
                    <td colspan="3">
                        <div class="basket-item">
                        
                            <div class="item-details" width="500" height="600">
                                <h2>Product 2</h2>
                                <p>Available in limited colours</p>
                                <label for="quantity2">Quantity:</label>
                                <select id="quantity2" name="quantity2" onchange="updatePrice(2)">
                                    <?php
                                    for ($i = 1; $i <= 10; $i++) {
                                        echo "<option value='{$i}'>{$i}</option>";
                                    }
                                    ?>
                                </select>
                                <p>Price: <span id="price2">£4.99</span></p>
                            </div>
                        </div>
                    </td>
                </tr>

            </thead>
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

                        foreach ($basket as $item) {
							/* @var $item Product */

                            $MAX_QUANTITY = 10;
                            $quantityOptions = "";
                            for ($i = 1; $i <= $MAX_QUANTITY; $i++) {
                                $quantityOptions .= "<option value='$i'>$i</option>";
                            }

							$pathForPhoto = "./_images/products/" . $item->productID . "/";
							$photo = file_exists($pathForPhoto) ? $pathForPhoto . scandir($pathForPhoto)[2] : "https://picsum.photos/512"; // [0] is ".", [1] is ".."

                            $sizeName = $item->sizes[0]->name ?? "One Size";
                            $sizePrice = $item->sizes[0]->price ?? 0;

                            echo <<<EOT
                            <tr id="{$item->productID}">
                                <td><img src="{$photo}" alt="{$item->name}" class="product-image"></td>
                                <td>
                                    <p class="name">{$item->name}</p>
                                    <p>Color: {$sizeName}</p>
                                </td>
                                <td>
                                    <select>$quantityOptions</select>
                                </td>
                                <td class="price">£{$sizePrice}</td>
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
    <h2>Order Summary</h2>
    <div>Total: £<span id="totalPrice">0.00</span></div><br><br><br><br>
    <button onclick="storeQuantityData()" id="continue-to-checkout">Continue to Checkout</button>
</div>
</main>
    <script>
        function storeQuantityData() {
            const basket = document.getElementById("basket");
            const quantities = {};
            for (let i = 1; i < basket.rows.length; i++) {
                if (basket.rows[i].cells.length === 1) continue; // Skip if the row is empty
                const quantityInput = basket.rows[i].cells[1].getElementsByTagName("input")[0];
                quantities[`${basket.rows[i].id.replace("product-", "")}`] = quantityInput.value
            }
            // Save cookie
            document.cookie = `quantities=${JSON.stringify(quantities)}; path=/`;
            window.location.href = "checkout.php";
        }

        function updatePrice(productId) {
            const quantitySelect = document.getElementById(`quantity${productId}`);
            const priceSpan = document.getElementById(`price${productId}`);
            const pricePerItem = productId === 1 ? 24.99 : 4.99; // can be changed accordingly
            const quantity = parseInt(quantitySelect.value);
            const price = (pricePerItem * quantity).toFixed(2);
            priceSpan.innerText = `£${price}`;
            calculateTotal();
        }
        function calculateTotal() {
            const price1 = parseFloat(document.getElementById('price1').innerText.replace("£", ""));
            const price2 = parseFloat(document.getElementById('price2').innerText.replace("£", ""));
            const quantity1 = parseInt(document.getElementById('quantity1').value);
            const quantity2 = parseInt(document.getElementById('quantity2').value);

            const totalPrice = (price1 * quantity1) + (price2 * quantity2);

            // Update the total price in the order summary section
            const totalPriceElement = document.getElementById("totalPrice");
            totalPriceElement.innerText = totalPrice.toFixed(2);
        }

        window.addEventListener("load", calculateTotal);
        document.querySelectorAll("select").forEach(select => select.addEventListener("change", () => {
            calculateTotal();
            updatePrice(select.id.substring(8)); // Extract product ID from select element ID
        }));
    </script>
  
    <?php include '_components/footer.php'; ?>
</body>

</html> 
