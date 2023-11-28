<?php
//if the form has been submitted
if (isset($_POST['submitted'])){
 #prepare the form input

  // connect to the database
  require_once('connectdb.php');
	
  $username=isset($_POST['username'])?$_POST['username']:false;
  $password=isset($_POST['password'])?password_hash($_POST['password'],PASSWORD_DEFAULT):false;
  $confirm_pass=isset($_POST['confirm_pass'])?$_POST['confirm_pass']:true;
  $email=isset($_POST['email'])?$_POST['email']:false;
  
  if (!($username)){
	echo "Username wrong!";
    exit;
	}
  if (!($password)){
	exit("password wrong!");
	}
 
    
  //Creating a stronger password, 
  $number = preg_match('@[0-9]@', $password,$confirm_pass);
  $lowercase = preg_match('@[a-z]@', $password,$confirm_pass);
  $uppercase = preg_match('@[A-Z]@', $password,$confirm_pass);
  $specialchars = preg_match('@[^\w]@', $password,$confirm_pass);
  
  if (!$uppercase || !$lowercase || !$number || !$specialchars || strlen($password) < 7) {
    echo 'Password needs to be stronger. ';
    echo ' <a href = "Register.php"> Try again</a>';
    exit;
   } else {
   echo(" ");
 }
 
    
 // checking if the passwords match
 
    if ($_POST["password"] === $_POST["confirm_pass"]) {
    
      echo '';
   }
   else {
      echo'Passwords need to  match';
      echo ' <a href = "Register.php"> Try again</a>';
     exit;
   }
    
 try{
	
	#register user by inserting the user info 

	$stat=$db->prepare("insert into users values(default,?,?,?)");
	$stat->execute(array($username, $password, $email));
	
	$id=$db->lastInsertId();
	echo " Congratulations! You are now registered. Your ID is: $id  ";  	
	
 }
 catch (PDOexception $ex){
	echo "Sorry, a database error occurred! <br>";
	echo "Error details: <em>". $ex->getMessage()."</em>";
 }

}
?>


<!DOCTYPE html>
<html lang ="en">
  <meta charset = "utf-8">
<head>
<link rel = "stylesheet" type="text/css" href="css\main.css"/>


<link rel="stylesheet" type="text/css"
href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css"
/>
<style>
    body{
       /*  background-image:url('https://img.freepik.com/premium-photo/abstract-luxury-gradient-blue-background-smooth-dark-blue-with-black-vignette-studio-banner_1258-54339.jpg');
        background-repeat:no-repeat; */
        background-size:  100% 100%;
        background-attachment:fixed;
        }
    
</style>
  
  <title>Registration System </title>

</head>
<div class = "tnav">
    <h1>Register</h1>
    <header id = "main-header"></header>

    
    <a href = "Login.php"> Login </a>
    
    
</div>
<body>
<section id = "Register">
  You can register if you are a new user and need to set up login details. 
  <br> Please be ready to provide a username, password and valid email address.<br>
  <br><br>
  
  <form  method = "post" action="Register.php">
	
    FirstName: <input type = "text" name = "firstname" placeholder = "firstname"/><br>
    LastName: <input type = "text" name = "lastname" placeholder = "lastname"/><br>
    Email: <input type="email" placeholder="email" required pattern=".+(\.co.uk\.uk|\.com)"title=
  "Please a valid email address."/><br>
	Password: <input type="password" name="password" placeholder = "password"/><br>
    Confirm Password: <input type="password" name="confirm_pass" placeholder = "confirmpassword"/><br>
    

	<input type="submit" value="Register" /> 
	<input type="reset" value="clear"/>
	<input type="hidden" name="submitted" value="true"/>
  </form>  

  <p> Already a user? <a href="Login.php">Log in</a>  </p>
   <p> Want to return back to the home page <a href = ""><em>Home page</em></a></p> <!-- change html to homepage  -->
  </section>

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
</html>
