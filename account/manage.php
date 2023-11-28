<?php $currentView = "details" ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Account Management</title>
    <link rel="stylesheet" href="../_stylesheets/main.css">
    <link rel="stylesheet" href="../_stylesheets/accountManage.css">
</head>
<body>
    <?php include '../_components/header.php'; ?>
    <main>
        <h1>Account Management</h1>
        <section id="accountPanel">
            <div id="options">
                <a>Your Details</a>
                <a>Security</a>
                <a>Past Orders</a>
            </div>
            <div id="view">
                <?php if ($currentView == "details") { // Could this be a switch/case? ?>
                    <div class="row">
                        <label for="firstName">First Name</label>
                        <input type="text" name="firstName" id="firstName" value="">

                        <label for="surName">Last Name</label>
                        <input type="text" name="surName" id="surName" value="">
                    </div>
                    <div class="row">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email">
                    </div>
                <?php } else if ($currentView == "security") { ?>
                    <div class="row">
                        <label for="currentPassword">Current Password</label>
                        <input type="password" name="currentPassword" id="currentPassword">
                    </div>
                    <div class="row">
                        <label for="newPassword">Current Password</label>
                        <input type="password" name="newPassword" id="newPassword">
                    </div>
                    <div class="row">
                        <label for="confirmNewPassword">Current Password</label>
                        <input type="password" name="confirmNewPassword" id="confirmNewPassword">
                    </div>
                <?php } else if ($currentView == "orders") {
                    // Make call to database to return orders.
                    ?>
                    <p>#0000000 | Hardtail Shoes | UK 11 | £59.99</p>
                    <?php
                } else { throw new LogicException("View matches nothing."); } ?>
            </div>
        </section>
    </main>
    <?php include '../_components/footer.php'; ?>
</body>
</html>