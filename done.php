<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '_components/default.php'; ?>
    <title>Order Confirmation - MIRЯOR</title>
    <link rel="stylesheet" href="./_stylesheets/basket.css">
</head>

<body>
    <?php include '_components/header.php'; ?>
    <main>
        <div class="confirmation-container">
            <h1>THANK YOU</h1>
            <p>Please check your emails for updates on your purchase</p>
            <p>Or, if you need help, please contact us.</p>

            <h3><i class="fa-solid fa-leaf"></i>Your purchase saved a total of <span class="green">4.12kg</span> of CO2!</h3>

            <!---Buttons-->
            <div class="row">
                <!-- Go to Account button with onclick event to redirect users to Log in page-->
                <button id="GoToAccountBtn" onclick="window.location.href = 'login.php';"> Go to Account </button>

                <!-- Continue Shopping button with onclick event to redirect users to the main shopping page-->
                <button id="ContinueShoppingBtn" onclick="window.location.href = 'products/';"> Continue Shopping </button>
            </div>
        </div>
    </main>
    <!-- Don't worry I accidentally did this for you haha -->
    <?php include '_components/footer.php'; ?>
</body>

</html>