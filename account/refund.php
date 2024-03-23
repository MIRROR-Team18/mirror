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
    <?php include '../_components/default.php'; ?>
    <title>Refund Request</title>
    <link rel="stylesheet" href="../_stylesheets/refund.css">
</head>
<body>
    <?php include '../_components/header.php'; ?>

    <main>
        <section class="refund">
            <h1>REFUND FORM</h1>
			<?php if ($successMessage): ?>
                <p class="success-message"><?= $successMessage ?></p>
			<?php endif; ?>
            <p class="subtitle">Please fill in all the details.</p>
            <form action="refund_processed.php" method="post">
                <div class="row">
                    <label for="order_number" class="sr-only">Order Number:</label>
                    <input type="text" id="order_number" name="order_number" value="<?= $orderNum ?>" required placeholder="Order Number">

                    <label for="product_number" class="sr-only">Product Number (if returning one item):</label>
                    <input type="text" id="product_number" name="product_number" placeholder="Product Number (if returning one item)">
                </div>

                <label for="reason" class="sr-only">Reason for Refund:</label>
                <textarea id="reason" name="reason" rows="4" required placeholder="Reason"></textarea>

                <input class="button" type="submit" value="Submit Refund Request">
            </form>
        </section>
    </main>

    <?php include '../_components/footer.php'; ?>
</body>
</html>
