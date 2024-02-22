<?php
session_start();

require_once('./_components/database.php');
  $db = new Database();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_otp = $_POST['otp'];
    $stored_otp = $_SESSION['user']['otp'];
    $user_id = $_SESSION['user']['id'];

    $sql = "SELECT * FROM users WHERE id='$user_id' AND otp='$user_otp'";
    $query = mysqli_query($database.php, $sql);
    $data = mysqli_fetch_array($query);

    if ($data) {
        $otp_expiry = strtotime($data['otp_expiry']);
        if ($otp_expiry >= time()) {
            $_SESSION['user_id'] = $data['id'];
            unset($_SESSION['user']);
            header("Location: index.php");
            exit();
        } else {
            ?>
                <script>
    alert("The OTP has expired. Please try again.");
    function navigateToPage() {
        window.location.href = 'login.php';
    }
    window.onload = function() {
        navigateToPage();
    }
     </script>
            <?php 
        }
    } else {
        echo "<script> alert('The OTP is not valid. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="stylesheet" type="text/css" href="_stylesheets/main.css"/>
    <link rel="stylesheet" type="text/css" href="_stylesheets/login.css"/>

</head>
<body>
<?php include '_components/header.php'; ?>
    <div id="container">
        <h1>Two-Step Verification</h1>
        <p>Enter the 5 Digit OTP Code that has been sent <br> to your email address: <?php echo $_SESSION['email']; ?></p>
        <form method="post" action="otp_verification.php">
            <label for="otp">Enter OTP Code:</label><br>
            <input type="number" name="otp" pattern="\d{5}" placeholder="Five-Digit OTP" required><br><br>
            <button type="submit">Verify OTP</button>
        </form>
    </div>
    <?php include "_components/footer.php"; ?>
</body>
</html>
