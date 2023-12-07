<?php

//if the form has been submitted
if (isset($_POST['submitted'])) {
    if (!isset($_POST['email'], $_POST['password'])) {
        // Could not get the data that should have been sent.
        exit('Please fill both the email and password fields!');
    }
    // connecting to the DataBase
    require_once("_components/database.php"); //db name
    //require_once("connectdb.php"); //db name
    $db = new Database();
    try {

        //A query that should help find the matching records.
        /* var_dump($_POST);*/
        $password = $db->getPassword($_POST['email']);
        /*var_dump($password); */

        if (!is_null($password)) {  // matching username
            if (password_verify($_POST['password'], $password)) { //matching password

                //recording the user session  
                session_start();
                $_SESSION["email"] = $_POST['email'];
                header("Location:index.php"); // Change location to home page
                exit();

            } else {
                echo "<p style='color:red'>Error logging in, Password does not match </p>";
            }
        } else {
            //else display an error
            echo "<p style='color:red'>Error logging in, email not found </p>";
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
    <link rel="stylesheet" type="text/css" href="_stylesheets/main.css" />

<style>
      
      
   h2{
    text-align: center;
   }

   /* .mirrorlogo{
     position:fixed;
     left:0;
     top:0;
   }
 */
    .login-1 {
      background-color: #fff;
      border: 1px solid #ccc;
      padding: 20px;
      width: 300px;
      border-radius: 8px;
      margin:auto ;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      margin-bottom: 8px;
    }

    input {
      padding: 8px;
      margin-bottom: 16px;
    }
      
    </style>

    <title> Login to your MIЯЯOR account </title>
    <!-- <div id = "mirrorlogo">
        <img src = "_images\logo_narrow.svg">
</div> -->
    
</head>
<?php include '_components/header.php'; ?>
<body>
    
    
    <div class="login-1">

    <h2>Log In</h2>
    <form method="POST">

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

     <label for = "name"> Password:</label>
         <input type="password" id = "email" name="password"  required /> 
            <input type="submit" value="Log in" /><br></br>
            <input type="hidden" name="submitted" value="true" />
      
    </form>
    
    <p> Not a register user? <a href="register.php"> Register </a> </p>
         
    </style>
  </div>
   
</body>

<?php include '_components/footer.php'; ?>

</php>

</html>
