<?php
// Only accessible if logged in
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['userID'])) {
    // Redirect back to the refund page with an error message
    header("Location: ../login.php?error=not_logged_in");
    exit();
}
$orderNum = $_GET['order_number'] ?? "";
$successMessage = isset($_GET['success']) ? $_GET['success'] : ""; // Check if there's a success message
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Request</title>
    <link rel="stylesheet" href="../_stylesheets/main.css">
    <link rel="stylesheet" href="../_stylesheets/refund.css">
</head>
<body>
    <?php include '../_components/header.php'; ?>
    
    <main class="container">
        <h2>Refund Request</h2>
        <?php if ($successMessage): ?>
            <p class="success-message"><?= $successMessage ?></p>
        <?php endif; ?>
        <p class="subtitle">Fill in all the details</p>
        <form action="refund_processed.php" method="post">
            <label for="order_number">Order Number:</label>
            <input type="text" id="order_number" name="order_number" value="<?= $orderNum ?>" required>

            <label for="product_number">Product Number (if returning one item):</label>
            <input type="text" id="product_number" name="product_number">

            <label for="reason">Reason for Refund:</label>
            <textarea id="reason" name="reason" rows="4" required></textarea>

            <input type="submit" value="Submit Refund Request">
        </form>
    </main>

    <?php include '../_components/footer.php'; ?>
</body>
</html>
