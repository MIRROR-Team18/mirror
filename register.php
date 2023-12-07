<?php
//if the form has been submitted
if (isset($_POST['submitted'])){
 #prepare the form input

  // connect to the database
  require_once('./_components/database.php');
  $db = new Database();
  
  if (!isset($_POST['email'])){
	echo "Email wrong!";
    exit;
	}
  if (!isset($_POST['password']) || !isset($_POST['confirm_pass'])){
	exit("password wrong!");
	}
  if (!isset($_POST['Firstname']) || !isset($_POST['Last_name'])) {
    exit("Name not provided!");
  }
 
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirm_pass = $_POST["confirm_pass"];
  $firstname = $_POST["Firstname"];
  $lastname = $_POST["Last_name"];
    
  //Creating a stronger password, 
  $number = preg_match('@[0-9]@', $password,$confirm_pass);
  $lowercase = preg_match('@[a-z]@', $password,$confirm_pass);
  $uppercase = preg_match('@[A-Z]@', $password,$confirm_pass);
  $specialchars = preg_match('@[^\w]@', $password,$confirm_pass);
  
  if (!$uppercase || !$lowercase || !$number || !$specialchars || strlen($password) < 7) {
    echo 'Password needs to be stronger. ';
    echo ' <a href = "register.php"> Try again</a>';
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
      echo ' <a href = "register.php"> Try again</a>';
     exit;
   }
    
 try{
	
	#register user by inserting the user info 
  echo $email;
  $user = $db->registerUser($email, $firstname, $lastname, $password);
	
	$id= $user->userID;
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
    <link rel="stylesheet" type="text/css" href="_stylesheets/main.css" />

<style>
      
      
   h2{
    text-align: center;
    text-decoration: underline;
   }

   /* .mirrorlogo{
     position:fixed;
     left:0;
     top:0;
   }
 */
    .Register {
      background-color: #fff;
      border:1px solid #ccc;
      padding: 20px;
      width: 500px;
      border-radius: 18px;
      margin:auto ;
    }

    form {
      display: flex;
      flex-direction: column;
    } 

    label {
      margin-bottom: 4px;
    }

    input {
      padding: 4px;
      margin-bottom: 10px;
    } 
      
    </style>

  
  <title>Register </title>
  <link rel="stylesheet" href="./_stylesheets/main.css">

</head>

<?php include '_components/header.php'; ?>

<body>
<div class = "Register">
<h2>Register</h2><br>
 <h4> You can register if you are a new user and need to set up login details. </h4>
  <br><h4> Please be ready to provide a username, password and valid email address.</h4><br>
  <br>
  
  <form  method = "post" action="register.php">
	
    FirstName: <input type = "text" name = "Firstname" placeholder = "First name" required /><br>
    LastName: <input type = "text" name = "Last name" placeholder = "Last name" required/><br>
    Email: <input type="email" name="email" placeholder="Email" required pattern=".+(\.co.uk\.uk|\.com)"title=
  "Please a valid email address."/><br>
	Password: <input type="password" name="password" placeholder = "Password" required/><br>
    Confirm Password: <input type="password" name="confirm_pass" placeholder = "Confirm password" required/><br>
    

	<input type="submit" value="Register" /> 
	<input type="reset" value="Clear"/>
	<input type="hidden" name="submitted" value="true"/>
  </form>  

  <p> Already a user? <a href="login.php">Log in</a>  </p>
   <p> Return back to the home page <a href = ""><em>Home page</em></a></p> <!-- change html to homepage  -->
  
  </div>
</body>
<?php include '_components/footer.php'; ?>


      </php>
</html>
