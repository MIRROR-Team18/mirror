<?php
    $errorMessage = "";
    if (isset($_POST["submitted"])) {
        if (!isset($_POST["name"]) || !isset($_POST["email"]) ||
            !isset($_POST['confirmEmail']) || !isset($_POST["message"])) {
            $errorMessage = "Fields missing from submission";
        }

        if ($_POST["email"] !== $_POST["confirmEmail"]) {
            $errorMessage = "Emails do not match";
        }

        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $message = htmlspecialchars($_POST["message"]);

        $message = $message . "\n\nOrder Number: " . $_POST["order_number"] ?? "none" . "\nProduct Number: " . $_POST["product_number"] ?? "none" . "\n";

        try {
            if ($errorMessage === "") {
				require_once "_components/database.php";
				$db = new Database();
				$success = $db->createContactEnquiry($name, $email, $message);

				if ($success) {
					header("Location: thanks.php");
					exit();
				} else {
					$errorMessage = "Didn't save enquiry, perhaps it is a duplicate ticket?";
				}
            }
        } catch (Exception $e) {
            $errorMessage = "There was an issue with the database.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "_components/default.php"; ?>
    <title>Contact Us - MIRЯOR</title>
    <link rel="stylesheet" href="_stylesheets/contact.css">
</head>
<body>
    <!-- Header -->
    <?php include "_components/header.php"; ?>

    <!-- Contact Us Content -->
    <main class="content">
        <section class="upper-half">
            <h1>WHAT'S UP?</h1>
            <p>If you need to contact us about anything, whether it's about a product, shipping, or something else, feel free to contact us!<br>
                We can be contacted by:
            </p>
            <ul>
                <li>Phone: 01892755555</li>
                <li>Email: support@mirror.com</li>
                <li>Address: 71 This Is Real Street, Real City A11 2BB</li>
            </ul>
            <p>Or you can fill out our Contact Form below.<br><br>
                Companies, if you want to see your product with us, please send a query to the email below:
            </p>
            <ul>
                <li>sales@mirror.com</li>
            </ul>
        </section>
        <section class="lower-half">
            <h1>CONTACT FORM</h1>
            <p>Please fill in all details.</p>
            <p class="error-message"><?php echo $errorMessage; ?></p>
            <form class="contact-form" method="POST">
                <input type="hidden" name="submitted" value="true">
                <div class="row">
                    <label for="name" class="sr-only">Name</label>
                    <input type="text" id="name" name="name" placeholder="Your Name" required>
                </div>
                <div class="row">
                    <label for="email" class="sr-only">Email</label>
                    <input type="email" id="email" name="email" placeholder="Your Email" required>
                    <label for="confirmEmail" class="sr-only"></label>
                    <input type="email" id="confirmEmail" name="confirmEmail" placeholder="Confirm Email" required>
                </div>
                <div class="row">
                    <label for="orderNumber" class="sr-only">Order Number</label>
                    <input type="text" id="orderNumber" name="order_number" placeholder="Order Number (if applicable)">
                    <label for="productNumber" class="sr-only">Product Number</label>
                    <input type="text" id="productNumber" name="product_number" placeholder="Product Number (if applicable)">
                </div>
                <div class="row">
                    <label for="message" class="sr-only">Message</label>
                    <textarea name="message" id="message" rows="5" placeholder="Your Message" required></textarea>
                </div>
                <button type="submit">Send</button>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <?php include "_components/footer.php"; ?>
</body>
</html>
