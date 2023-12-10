<?php
    require_once "./_components/database.php";
    $db = new Database();

    if (isset($_POST["submitted"])) {
        $db = new Database();
        try {
			$db->addReview($_POST["name"], $_POST["rating"], $_POST["comment"]);
        } catch (Exception $e) {
            exit("An error occurred when saving your review! " . $e->getMessage());
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="_stylesheets/main.css">
    <link rel="stylesheet" href="_stylesheets/reviews.css">
    <title>Rating & Reviews</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto&display=swap">

</head>
<body>
    <?php include "_components/header.php"; ?>
    <div class="header">
        <h1>Rating & Reviews</h1>
    </div>

    <main class="container">
        <div class="sort-options">
            <label for="sort-select">Sort By:</label>
            <select id="sort-select" onchange="redirectToReviews(this.value)">
                <option value="overall">Overall Rating</option>
                <option value="lowest">Lowest Rating</option>
                <option value="highest">Highest Rating</option>
                <option value="newest">Newest Review</option>
                <option value="oldest">Oldest Review</option>
            </select>
        </div>
        <div id="reviews-list"></div>

        <div class="reviews-container">

        <?php
            $allReviews = $db->getAllReviews();
            $pathToPhotos = "./_images/reviews/";
            $images = array_slice(scandir($pathToPhotos), 2); // Get all images from folder, remove first two ("." and "..")

            foreach ($allReviews as $review) {
                $photoSelected =$pathToPhotos . $images[random_int(0, sizeof($images) - 1)];

                echo <<<EOT
                <div class="review">
                    <img src="{$photoSelected}" alt="{$review['name']}_photo" class="person-image">
                    <p><strong>{$review['name']}</strong></p>
                    <p>Rating: {$review['rating']}/5 </p>
                    <p>Comment: {$review['comment']}</p>
                    <p>Date: {$review['date']}</p>
                </div>
                EOT;
            }
    	?>

        <!-- Add a Review Button -->
        <button id="add-review-button" onclick="showReviewForm()">Add a Review</button>

        <!-- Updated review form initially hidden -->
        <div id="review-form" class="review-form" style="display: none; text-align: center; margin-top: 20px;">
            <form id="user-review-form" method="POST">
                <label for="username">Your Name:</label>
                <input type="text" name="name" id="username" required><br><br>
                <label for="rating">Rating:</label>
                <input type="number" name="rating" id="rating" min="1" max="5" required><br><br>
                <label for="comment">Your Review:</label><br>
                <textarea id="comment" name="comment" required></textarea><br><br>
                <input type="hidden" name="submitted" value="yeah">
                <button type="submit">Submit Review</button>
            </form>
        </div>

        <script src="_scripts/reviews.js"></script>
        <script>

        </script>
    </div>
    <?php include "_components/footer.php"; ?>
</body>
</html>
