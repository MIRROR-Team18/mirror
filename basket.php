<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basket</title>
    <link rel="stylesheet" href="_stylesheets/main.css">
    <link rel="stylesheet" href="./_stylesheets/basket.css">
</head>
<body>
    <?php include '_components/header.php'; ?>
    <h1>Current Basket</h1>
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
            <!-- Add your product rows dynamically here using JavaScript -->
            <tr>
                <td><img src="./_images/peach.jpg" alt="Hoodie" class="product-image"></td>
                <td>
                    <p><strong>Hoodie</strong></p>
                    <p>Color: Peach</p>
                </td>
                <td>1</td>
                <td>£20.00</td>
                <td><!-- Actions buttons go here --></td>
            </tr>
        </tbody>
    </table>

    <!-- Order Summary Section -->
    <div id="order-summary">
        <h2>Order Summary</h2>
        <p>Order Value: £20.00</p>
        <!-- Add other order summary details as needed -->
        <div id="total">TOTAL: £20.00</div>
        <button id="continue-to-checkout">Continue to Checkout</button>
    </div>

    <script src="basket.js"></script>
    <?php include '_components/footer.php'; ?>
</body>
</html>
