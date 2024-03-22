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
        header("Location:confirm.php"); // Change location to home page
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

  
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '_components/default.php'; ?>
    <link rel="stylesheet" type="text/css" href="_stylesheets/login.css"/>
    <title> Login to your MIRЯOR account </title>
</head>
<body>
<?php include '_components/header.php'; ?>

<main class="login-1">

    <h1>
        <i class="fa-solid fa-right-to-bracket"></i>
        LOGIN
    </h1>
    <form method="POST">
        <label for="email" class="sr-only">Email:</label>
        <input type="email" id="email" name="email" placeholder="Email" required />

        <label for="name" class="sr-only"> Password:</label>
        <input type="password" id="email" name="password" placeholder="Password" required />

        <input class="button" type="submit" value="Log in" /><br />
        <input type="hidden" name="submitted" value="true" />
    </form>
   <div class="center">
        <p>Haven't got an account yet? Why not <a href="register.php">register</a>?</p>
   </div>
</main>

<?php include '_components/footer.php'; ?>
</body>
</html>
