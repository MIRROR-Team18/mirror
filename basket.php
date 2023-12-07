<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Basket - MIЯЯOR</title>
    <link rel="stylesheet" href="_stylesheets/main.css">
    <link rel="stylesheet" href="_stylesheets/basket.css">
</head>
<body>
    <?php include '_components/header.php'; ?>

    <div class="basket-container">
        <h1>Your Shopping Basket</h1>
        <table id="basket">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Add your product rows dynamically here using PHP -->
                <tr>
                    <td><img src="./_images/peach.jpg" alt="Hoodie" class="product-image"></td>
                    <td>
                        <p><strong>Hoodie</strong></p>
                        <p>Color: Peach</p>
                    </td>
                    <td>
                    <select>
            <?php
                // Assuming a maximum quantity of 10, you can adjust as needed
                for ($i = 1; $i <= 10; $i++) {
                    echo "<option value='$i'>$i</option>";
                }
            ?>
        </select>
                    </td>
                    <td>£19.99</td>
                    <td><!-- Actions buttons go here --></td>
                </tr>
            </tbody>
        </table>

        <!-- Order Summary Section -->
        <div id="order-summary">
            <h2>Order Summary</h2>
            <p>Order Value: £19.99</p>
            <!-- Add other order summary details as needed -->
            <div id="total">TOTAL: £19.99</div>
            <!-- Use an anchor tag around the button for navigation -->
            <a href="checkout.php" id="continue-to-checkout">Continue to Checkout</a>
        </div>
    </div>

    <?php include '_components/footer.php'; ?>
</body>
</html>
