<?php
    session_start();
    //if (isset($_SESSION["userID"]) == false) echo '<script>window.location.replace("../index.php");</script>';
    require '../_components/database.php';
    if (isset($_GET['option']) && in_array($_GET['option'], array('details', "details-change", 'security', 'pastOrders'))) {
        $currentView = $_GET['option'];
    }
    else {
        $currentView = "details";
    }
    //Get form submision and update database
    $inputError = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fName = $_POST['firstName'];
        $sName = $_POST['surName'];
        $email = $_POST['email'];
        
        //First name
        if ($fName == "") {
            //name not changed so do nothing
        }

        //ensure fname is valid
        else if (!preg_match("/^[a-zA-Z-' ]*$/",$fName)) {
            $inputError .= "First name can only contain letters and spaces.\n";
            $currentView = "details-change";
        }
        else if (strlen($fName) > 100) {
            $inputError .= "First name cannot exede 100 characters";
        }

        //update fname in db
        else {
            $userID = $_SESSION["userID"];
            $sql = "UPDATE users SET firstName = '$fName' WHERE userID = '$userID'";
            $conn = getConnection();
            $conn->query($sql);
            $conn->close();
        }

        //Surname
        if ($sName == "") {
            //name not changed so do nothing
        }

        //Ensure sname is valid
        else if (!preg_match("/^[a-zA-Z-' ]*$/",$sName)) {
            $inputError .= "Surname can only contain letters and spaces.\n";
            $currentView = "details-change";
        }
        else if (strlen($sName) > 100) {
            $inputError .= "Surname name cannot exede 100 characters";
        }

        //update sname in db
        else {
            $userID = $_SESSION["userID"];
            $sql = "UPDATE users SET lastName = '$sName' WHERE userID = '$userID'";
            $conn = getConnection();
            $conn->query($sql);
            $conn->close();
        }

        //Email
        if ($email == "") {
            //email not changed so do nothing
        }

        //Ensure email is valid
        else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $inputError .= "Email is an invalid format.\n";
            $currentView = "details-change";
        }
        else if (strlen($email) > 320) {
            $inputError .= "Email cannot exede 320 characters";
        }

        //Update email in db
        else {
            $userID = $_SESSION["userID"];
            $sql = "UPDATE users SET email = '$email' WHERE userID = '$userID'";
            $conn = getConnection();
            $conn->query($sql);
            $conn->close();
        }
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
                        //Following stuff needs to be updated to the database when the form is submited + email has to be confirmed
                        ?>
                        <form method = "post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                            <p>Leave any fields you do not wish to change blank.</p><br>
                            <?php echo "<p>". nl2br($inputError) ."</p>"?>
                            <br>
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
                        <?php
                        break;
                    case "security":
                        ?>
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
                        <?php
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