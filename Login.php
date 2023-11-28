<?php

//if the form has been submitted
if (isset($_POST['submitted'])) {
    if (!isset($_POST['username'], $_POST['password'])) {
        // Could not get the data that should have been sent.
        exit('Please fill both the username and password fields!');
    }
    // connecting to the DataBase
    require_once("connectdb.php"); //db name
    try {

        //A query that should help find the matching records.

        $stat = $db->prepare('SELECT password FROM users WHERE username = ?');
        $stat->execute(array($_POST['username']));


        if ($stat->rowCount() > 0) {  // matching username
            $row = $stat->fetch();

            if (password_verify($_POST['password'], $row['password'])) { //matching password

                //recording the user session  
                session_start();
                $_SESSION["username"] = $_POST['username'];
                header("Location: .php"); // Change location to home page
                exit();

            } else {
                echo "<p style='color:red'>Error logging in, Password does not match </p>";
            }
        } else {
            //else display an error
            echo "<p style='color:red'>Error logging in, Username not found </p>";
        }
    } catch (PDOException $ex) {
        echo ("Failed to connect to the database.<br>");
        echo ($ex->getMessage());
        exit;
    }

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="css\main.css" />

    <style>
        body {
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-attachment: fixed;
        }

        .login-1 {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-container label {
            display: block;
            margin-bottom: 8px;
        }

        .login-container input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        .login-container button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>

    <title> Login to your MIЯЯOR account </title>
</head>
<div class="tnav">
    <h1>Login</h1>
    <header id="main-header"></header>



    <a href="Register.php"> Register </a>
    <a href="Homepage. "> Home Page </a> <!-- * Add the homepage once created. --->
    <a href="AboutUs.php"> About Us </a> <!-- * Add the about us once created. --->




</div>

<body>
    <br>
    <br>

    <div class="login-l">
        <form method="post" action="Login.php">
            <p> Username: <input type="text" name="username" /> </p>
            <p> Password: <input type="password" name="password" /> </p>
            <input type="submit" value="Log in" /><br></br>
            <input type="hidden" name="submitted" value="true" />
        </form>
        <p> Not a user? <a href="Register.php"> Register </a> </p>

    </div>
</body>

<footer>
    <div class="col">
        <h1>MIЯЯOR</h1>
    </div>
    <div class="col">
        <a href="">Terms of Service</a>
        <a href="">Privacy Policy</a>
    </div>
    <div class="col">
        <a href="">Help</a>
        <a href="">Returns</a>
        <a href="">Deliveries</a>
        <a href="">Track Order</a>
    </div>
</footer>

</php>

</html>