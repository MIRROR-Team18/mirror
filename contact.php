<?php
    $errorMessage = "";
    if (isset($_POST["submitted"])) {
        if (!isset($_POST["name"]) || !isset($_POST["email"]) || !isset($_POST["message"])) {
            $errorMessage = "Fields missing from submission";
            return;
        }

        $name = htmlspecialchars($_POST["name"]);
        $email = htmlspecialchars($_POST["email"]);
        $message = htmlspecialchars($_POST["message"]);

        try {
            require_once "_components/database.php";
            $db = new Database();
            $success = $db->createContactEnquiry($name, $email, $message);

            if ($success) {
                header("Location: thanks.php");
                exit();
            } else {
                $errorMessage = "Didn't save enquiry, perhaps it is a duplicate ticket?";
            }
        } catch (Exception $e) {
            $errorMessage = "There was an issue with the database.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - MIRЯOR</title>
    <link rel="stylesheet" href="_stylesheets/main.css">
    <link rel="stylesheet" href="_stylesheets/contact.css">
</head>
<body>
    <!-- Header -->
    <?php include "_components/header.php"; ?>

    <!-- Contact Us Content -->
    <div class="contact-us-content">
        <h1>Contact Us</h1>
        <form method="POST">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Your Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <input type="hidden" name="submitted" value="true">
            <button type="submit">Send Message</button>
        </form>

        <pre><?= $errorMessage ?></pre>
    </div>

    <!-- Footer -->
    <?php include "_components/footer.php"; ?>
</body>
</html>
