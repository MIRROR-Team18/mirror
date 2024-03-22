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
        $msg = '<span style="color:green"> Successful, you will now be logged in </span>';
        header("Location: index.php");
        exit();
    } else {
        $msg = '<span style="color:red"> Unsuccessful, try again by clicking refresh </span>';
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

   <div class = "center"> <h3>Prove you are not a robot to succefully log in... enter the code that is shown in the image</h3></div>
    
    <img src="captcha.php">
 
 
    <form method="POST" >
        <input type="text" name="input" />
        <input type="hidden" name="flag" value="1" />
        <input type="submit" value="Submit" name="submit" />
    </form>
    
    <div style='margin-bottom:5px'>
        <?php echo $msg; ?>
    </div>
 
     <div class = "center">
      Are you unable to view the code. Click 
        <a href='<?php echo $_SERVER['PHP_SELF']; ?>'>
            to refresh
        </a>
     </div>
</body>
<?php include '_components/footer.php'; ?>
</html>