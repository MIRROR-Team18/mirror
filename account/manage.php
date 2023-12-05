<?php
    session_start();
    if (isset($_SESSION["userID"]) == false) echo '<script>window.location.replace("../index.php");</script>';
    require '../_components/database.php';
    if (isset($_GET['option']) && in_array($_GET['option'], array('details', "details-change", 'security', 'pastOrders', 'security-change'))) {
        $currentView = $_GET['option'];
    }
    else {
        $currentView = "details";
    }
    //Get form submision and update database
    $inputError = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $fName = $_POST['firstName'] ?? null;
        $sName = $_POST['surName'] ?? null;
        $email = $_POST['email'] ?? null;
        $oldPassword = $_POST['currentPassword'] ?? null;
        $newPassword = $_POST['newPassword'] ?? null;
        $confNewPassword = $_POST['confirmNewPassword'] ?? null;
        
        //POST for details change
        if($fName != null) {
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

        //POST for password change
        else if ($oldPassword != null) {

            //Get pre-existing hashed password
            $userID = $_SESSION["userID"];
            $sql = "SELECT password FROM users WHERE userID = '$userID'";
            $conn = getConnection();
            $dbPassword = $conn->query($sql);
            $conn->close();

            //ensure password entered matchs pre-existing password
            if(password_verify($oldPassword, $dbPassword)) {

                //ensure that new password and confirm password match
                if ($newPassword == $confNewPassword) {

                    //ensure that password is stron enough
                    if (preg_match('/^(?=.[a-z])(?=.[A-Z])(?=.\d)(?=.[^A-Za-z\d])[\s\S]{7,256}$/', $newPassword)) {
                        
                        //update data base with new password
                        $userID = $_SESSION["userID"];
                        $newHashedPassword = password_hash($newPassword, null);
                        $sql = "UPDATE users SET password = ''$newHashedPassword' WHERE userID = '$userID'";
                        $conn = getConnection();
                        $conn->query($sql);
                        $conn->close();
                        $currentView = "security";
                    }
                    else {
                        $inputError .= "Password not strong enough.\n";
                        $currentView = "security-change";
                    }
                }
                else {
                    $inputError .= "Confirm Password and New Password do not match.\n";
                    $currentView = "security-change";
                }
            }
            else {
                $inputError .= "Current Password is Incorrect.\n";
                $currentView = "security-change";
            }
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
                        $userID = $_SESSION["userID"];
                        $user = $sql = "SELECT * FROM users WHERE userID = '$userID'";
                        $conn = getConnection();
                        $orders = $conn->query($sql);
                        $conn->close();
                        $fName = $user["firstName"];
                        $sName = $user["lastName"];
                        $email = $user["email"]; 
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
                        <a href = "manage.php?option=security-change"><input class = "button" type = "submit" value = "Change Password"></a>
                        <?php
                        break;
                    case "security-change":
                        ?>
                        <form method = "post" action="<?php echo $_SERVER['PHP_SELF'];?>">
                            <label for="currentPassword">Current Password</label>
                            <?php echo "<p>". nl2br($inputError) ."</p><br>"?>
                            <input type="password" name="currentPassword" id="currentPassword">
                            <br>
                            <label for="newPassword">New Password</label>
                            <input type="password" name="newPassword" id="newPassword">
                            <br>
                            <label for="confirmNewPassword">Confirm New Password</label>
                            <input type="password" name="confirmNewPassword" id="confirmNewPassword">
                            <br><br>
                            <input class = "button" type = "submit" value = "Submit Changes">
                        </form>
                        <?php
                        break;
                    case "pastOrders":
                        // Make call to database to return orders.
                        ?><div class = "wrapper"><?php
                            $userID = $_SESSION["userID"];
                            $sql = "SELECT * FROM orders WHERE userID = '$userID'";
                            $conn = getConnection();
                            $orders = $conn->query($sql);
                            $conn->close();
                            foreach($orders as $order) {
                                echo '<a href= "orderDetails?orderID="'.$order["orderID"].'><div style="background-color: grey;"><p>'.$order["orderID"].'</p><br>
                                <p>'.$order["status"].'</p><br></div>
                                <p>'.$order["paidAmount"].'</p></div></a>';
                            }
                        ?></div><?php
                        break;
                    }
                ?>
            </div>
        </section>
    </main>
    <?php include '../_components/footer.php'; ?>
</body>
</html>