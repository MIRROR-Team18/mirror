<?php
//if the form has been submitted
if (isset($_POST['submitted'])) {
	#prepare the form input

	// connect to the database
	require_once('./_components/database.php');
	$db = new Database();

	if (!isset($_POST['email'])) {
		echo "Email wrong!";
		exit;
	}
	if (!isset($_POST['password']) || !isset($_POST['confirm_pass'])) {
		exit("password wrong!");
	}
	if (!isset($_POST['Firstname']) || !isset($_POST['Last_name'])) {
		exit("Name not provided!");
	}

	$email = $_POST["email"];
	$password = $_POST["password"];
	$confirm_pass = $_POST["confirm_pass"];
	$confirm_email = $_POST["confirm_email"];
	$firstname = $_POST["Firstname"];
	$lastname = $_POST["Last_name"];

	//Creating a stronger password,
	$number = preg_match('@[0-9]@', $password, $confirm_pass);
	$lowercase = preg_match('@[a-z]@', $password, $confirm_pass);
	$uppercase = preg_match('@[A-Z]@', $password, $confirm_pass);
	$specialchars = preg_match('@\W@', $password, $confirm_pass);

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
	} else {
		echo 'Passwords need to  match';
		echo ' <a href = "register.php"> Try again</a>';
		exit;
	}

	//Checking if the emails match

	if ($_POST["email"] == $_POST["confirm_email"]) {
		echo '';
	} else {
		echo 'Email needs to match';
		echo '<a href = "register.php"> Try again </a>';
		exit;
	}

	try {

		#register user by inserting the user info
		echo $email;
		$user = $db->registerUser($email, $firstname, $lastname, $password);

		$id = $user->userID;
		echo " Congratulations! You are now registered. Your ID is: $id  ";

	} catch (PDOexception $ex) {
		echo "Sorry, a database error occurred! <br>";
		echo "Error details: <em>" . $ex->getMessage() . "</em>";
	} catch (Exception $e) {
		echo "Sorry, an error occurred!<br>Do you already have an account?";
	}

}
?>


<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
<head>
	<?php include '_components/default.php'; ?>
    <link rel="stylesheet" type="text/css" href="_stylesheets/login.css"/>
    <title>Register </title>
</head>

<body>
<?php include '_components/header.php'; ?>
<main>
    <h1>
        <i class="fa-solid fa-circle-plus"></i>
        REGISTER
    </h1>
    <form method="post" action="register.php" class="Register">
        <div class="left">
            <label for="Firstname" class="sr-only">First Name:</label>
            <input type="text" id="Firstname" name="Firstname" placeholder="First Name" required tabindex="1">
            <label for="email" class="sr-only">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email" required pattern=".+(\.co.uk\.uk|\.com)" title="Please a valid email address." tabindex="3">
            <label for="password" class="sr-only">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required tabindex="5">
        </div>
        <div class="right">
            <label for="Lastname" class="sr-only">Last Name:</label>
            <input type="text" id="Lastname" name="Last name" placeholder="Last Name" required tabindex="2">
            <label for="confirm_email" class="sr-only">Confirm Email:</label>
            <input type="email" id="confirm_email" name="confirm_email" placeholder="Confirm Email" required tabindex="4">
            <label for="confirm_pass" class="sr-only">Confirm Password:</label>
            <input type="password" id="confirm_pass" name="confirm_pass" placeholder="Confirm password" required tabindex="6">
        </div>
        <div class="bottom">
            <input class="button" type="submit" value="Register"/>
            <input class="button" type="reset" value="Clear"/>
            <input type="hidden" name="submitted" value="true"/>
        </div>

    </form>
    <div class="center">

        <p> Already a user? Did you mean to <a href="login.php">log in</a>?</p>
        <p> Return back to the <a href="index.php">home page</a></p> <!-- changed html to homepage  -->
    </div>
</main>

<?php include '_components/footer.php'; ?>
</body>

</html>
















      