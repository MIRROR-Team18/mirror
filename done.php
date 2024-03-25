<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - MIЯЯOR</title>
    <link rel="stylesheet" href="_stylesheets/main.css">
    <link rel="stylesheet" href="./_stylesheets/basket.css">
</head>

<body>
    <?php include '_components/header.php'; ?>

    <div class="confirmation-container">
        <h1>THANK YOU</h1>
        <p>Please check your emails for updates on your purchase</p>
        <p>Or, if you need help, please contact us.</p>

        <h3>Your purchase saved a total of 4.12kg of CO2!</h3>
    </div>
    <br><br><br><br>

    <!---Buttons-->
    <!-- Go to Account button with onclick event to redirect users to Log in page-->
    <button id="GoToAccountBtn" onclick="window.location.href = 'login.php';"> Go to Account </button>

    <!-- Continue Shopping button with onclick event to redirect users to the main shopping page-->
    <button id="ContinueShoppingBtn" onclick="window.location.href = 'index.php';"> Continue Shopping </button>

    <br><br><br><br><br><br><br><br><br><br><br>
    <!-- Don't worry I accidentally did this for you haha -->
    <?php include '_components/footer.php'; ?>
</body>

</html>