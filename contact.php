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
        <form>
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Your Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit" onclick="window.location.href='thanks.php'">Send Message</button>
        </form>
    </div>

    <!-- Footer -->
    <?php include "_components/footer.php"; ?>
</body>
</html>
