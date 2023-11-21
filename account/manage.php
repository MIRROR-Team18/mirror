<?php
    session_start();
    if (isset($_GET['option']) && in_array($_GET['option'], array('details', "details-change", 'security', 'pastOrders'))) {
        $currentView = $_GET['option'];
    }
    else {
        $currentView = "details";
    }
?>
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
    <!-- Check if user is logged into an account if not send them back to the home page since they are trying to access this page by entering in the 
    url manualy without being logged into an account which will potentially cause errors fetching data etc later in the page -->
    <?php
        //if (isset($_SESSION["userID"]) == false) echo '<script>window.location.replace("../index.php");</script>';
    ?>
    <?php include '../_components/header.php'; ?>
    <main>
        <h1>Account Management</h1>
        <section id="accountPanel">
            <div id="options">
                <a href = "manage.php?option=details">Your Details</a><br><br>
                <a href = "manage.php?option=security">Security</a><br><br>
                <a href = "manage.php?option=pastOrders">Past Orders</a>
            </div>
            <div id="view">
                <?php
                switch ($currentView) {
                    case "details":
                        //Needs to get names and stuff from username based on userID
                        $fName = "FIRST NAME";
                        $sName = "LAST NAME";
                        $email = "EMAIL"; 
                        echo'
                        <p>First Name: ' . $fName . ' </p> 
                        <p>Last Name: ' . $sName . ' </p>
                        <p>Email: ' . $email . ' </p><br>
                        <a href = "manage.php?option=details-change"><input class = "button" type = "submit" value = "Change Details"></a>
                        ';
                        break;
                    case "details-change":
                        //Following stuff needs to be updated to the database when the form is submited
                        echo '
                        <form>
                            <label for="firstName">First Name</label>
                            <input type="text" name="firstName" id="firstName" value="">
                            <br>
                            <label for="surName">Last Name</label>
                            <input type="text" name="surName" id="surName" value="">
                            <br>
                            <label for="email">Email</label>
                            <input type="text" name = "email" id="email" value="">
                            <br><br>
                            <input class = "button" type="submit" value = "Submit Changes">
                        </form>
                        ';
                        break;
                    case "security":
                        echo'
                        <div class="row">
                            <label for="currentPassword">Current Password</label>
                            <input type="password" name="currentPassword" id="currentPassword">
                        </div>
                        <div class="row">
                            <label for="newPassword">New Password</label>
                            <input type="password" name="newPassword" id="newPassword">
                        </div>
                        <div class="row">
                            <label for="confirmNewPassword">Confirm New Password</label>
                            <input type="password" name="confirmNewPassword" id="confirmNewPassword">
                        </div>
                        ';
                        break;
                    case "pastOrders":
                        // Make call to database to return orders.
                        break;
                }
                ?>
            </div>
        </section>
    </main>
    <?php include '../_components/footer.php'; ?>
</body>
</html>