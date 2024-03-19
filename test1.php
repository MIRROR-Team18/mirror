<?php
session_start();
  // connect to the database
  require_once('./_components/database.php');
  $db = new Database();
$msg = '';

// If user has given a captcha
if (isset($_POST['input']) && !empty($_POST['input'])) {
    // If the captcha is valid
    if ($_POST['input'] == $_SESSION['captcha']) {
        $msg = '<span style="color:green">SUCCESSFUL!!!</span>';
    } else {
        $msg = '<span style="color:red">CAPTCHA FAILED!!!</span>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" type="text/css" href="_stylesheets/main.css"/>
    <link rel="stylesheet" type="text/css" href="_stylesheets/login.css"/>

    <title> Verify by Captcha </title>
</head>
<body>
<?php include '_components/header.php'; ?>

    <h2>PROVE THAT YOU ARE NOT A ROBOT!!</h2>
    <img src="mirror\captcha.php">
   

    <form method="POST" >
        <input type="text" name="input" />
        <input type="hidden" name="flag" value="1" />
        <input type="submit" value="Submit" name="submit" />
    </form>

    <div style='margin-bottom:5px'>
        <?php echo $msg; ?>
    </div>

    <div>
        Can't read the image? Click
        <a href='<?php echo $_SERVER['PHP_SELF']; ?>'>
            here
        </a>
        to refresh!
    </div>
</body>
<?php include '_components/footer.php'; ?>
</html>