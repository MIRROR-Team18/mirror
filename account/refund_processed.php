<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	require_once '../_components/database.php';
    $db = new Database();

    // First check user is logged in
	if (session_status() === PHP_SESSION_NONE) session_start();
    if (!isset($_SESSION['userID'])) {
        // Redirect back to the refund page with an error message
        header("Location: login.php?error=not_logged_in");
        exit();
    }

    // Here I need to validate and process the refund request
    $order_number = htmlspecialchars($_POST["order_number"]);
    $reason = htmlspecialchars($_POST["reason"]);

    if (empty($order_number) || empty($reason)) {
        // Redirect back to the refund page with an error message
        header("Location: refund.php?error=empty_fields");
        exit();
    }

    // Simulate checking order existence (replace with database query)
    $order_exists = $db->getOrderByID($order_number);

    if (!$order_exists) {
        // Redirect back to the refund page with an error message
        header("Location: refund.php?error=invalid_order");
        exit();
    }

    // Process refund (simulated output, replace with your logic)
    $refund_message = "Refund request for Order Number $order_number due to: $reason";
    try {
		$db->createRefundRequest($_SESSION["userID"], $refund_message);
    } catch (Exception $e) {
        // Redirect back to the refund page with an error message
        header("Location: refund.php?error=database_error");
        exit();
    }
} else {
    // Redirect users to the refund page if they try to access refund_processed.php directly
    header("Location: refund.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '../_components/default.php'; ?>
    <title>Refund Request Confirmation</title>
    <link rel="stylesheet" href="../_stylesheets/refund.css">
</head>
<body>
    <?php include '../_components/header.php'; ?>

    <main>
        <section class="processed">
            <h1>CONFIRMED</h1>
            <p>
                Thank you for sending that through.<br>
                We're sorry we've failed you on this occasion, we'll be in contact to set things right soon.
            </p>

            <a class="return" href="/account/manage.php">Go back to your Account?</a>
        </section>
    </main>

	<?php include '../_components/footer.php'; ?>
</body>
</html>

