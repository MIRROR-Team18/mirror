<?php

//if the form has been submitted
if (isset($_POST['submitted'])) {
    if (!isset($_POST['email'], $_POST['password'])) {
        // Could not get the data that should have been sent.
        exit('Please fill both the email and password fields!');
    }

    // connecting to the DataBase
    require_once("_components/database.php"); //db name
    $db = new Database();
    try {
        $login = $db->loginUser($_POST["email"], $_POST["password"]);

        // recording the user session
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION["userID"] = $login->userID;
        $_SESSION["isAdmin"] = $login->isAdmin;
        header("Location:index.php"); // Change location to home page
        exit();
    } catch (PDOException $ex) {
        echo ("Failed to connect to the database.<br>");
        echo ($ex->getMessage());
        exit;
    } catch (Exception $e) {
        echo "<p style='color:red'>" . $e->getMessage() . "</p>";
        exit;
    }
}

    //Creating the OTP

    $sql = "SELECT * FROM User WHERE email='$email'";
    $query = mysqli_query($conn, $sql);
   // $code = mysqli_fetch_array($query);

    if ($code && password_verify($password, $data['password'])) {
        $otp = rand(10000, 99999);
        $otp_expiry = date("Y-m-d H:i:s", strtotime("+3 minute"));
        $subject= "Your OTP for Login";
        $message="Your OTP is: $otp";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="_stylesheets/main.css"/>
    <link rel="stylesheet" type="text/css" href="_stylesheets/login.css"/>

    <title> Login to your MIRЯOR account </title>
</head>
<body>
<?php include '_components/header.php'; ?>

<main class="login-1">

    <h2>Log In</h2>
    <form method="POST">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required />

        <label for="name"> Password:</label>
        <input type="password" id="email" name="password" required />
        <input type="submit" value="Log in" /><br />
        <input type="hidden" name="submitted" value="true" />

    </form>

    <p> Not a register user? <a href="register.php"> Register </a></p>
</main>

<?php include '_components/footer.php'; ?>
</body>
</html>
