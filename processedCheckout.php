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
        <p>Please check youremails for updates on your purchase.</p>
        <p>Or, if you need help, please contact us.</p>
        <br></br>

        <h2>Your purchase saved a total of 4.12kg of CO2.</h2>
    </div>
    <br><br><br><br>
    
<!---Buttons--->
<input type="hidden" name="GoToAccountBtn" value="yeah">
<button id="GoToAccountBtn" type="submit"> Go to Account </button>

<input type="hidden" name="ContinueShoppingBtn" value="yeah">
<button id="ContinueShoppingBtn" type="submit"> Continue Shopping </button>


    <br><br><br><br><br><br><br><br><br><br><br>
    <!-- Don't worry I accidentally did this for you haha -->
    <?php include '_components/footer.php'; ?>
</body>
</html>
