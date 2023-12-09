<?php
    require_once "./_components/database.php";
    $db = new Database();

    if (isset($_POST["submitted"])) {
        $db = new Database();
        try {
			$db->addReview($_POST["username"], $_POST["rating"], $_POST["comment"]);
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
<div class="container">
    <div class="header">
        <h1>Rating & Reviews</h1>
    </div>

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
        <!-- John Doe -->
        <div class="review">
            <img src="./images/male 1.jpg" alt="John Doe" class="person-image">
            <p><strong>John Doe</strong></p>
            <p>Rating: 4/5 </p>
            <p>Comment: Great product! I love how it exceeded my expectations. The quality is exceptional, and it arrived sooner than I expected.</p>
            <p>Date: November 10, 2023</p>
        </div>

        <!-- Jane Smith -->
        <div class="review">
            <img src="./images/female5.jpg" alt="Jane Smith" class="person-image">
            <p><strong>Jane Smith</strong></p>
            <p>Rating: 5/5 </p>
            <p>Comment: Excellent service! The customer support team was very helpful in addressing my queries. The product itself is of top-notch quality. Will definitely recommend to others.</p>
            <p>Date: November 15, 2023</p>
        </div>

        <!-- Alice -->
        <div class="review">
            <img src="./images/female4.jpg" alt="Alice" class="person-image">
            <p><strong>Alice</strong></p>
            <p>Rating: 3/5 </p>
            <p>Comment: Good experience. The product was decent, but there is room for improvement. Delivery was prompt, and the packaging was secure.</p>
            <p>Date: November 5, 2023</p>
        </div>

        <!-- Bob -->
        <div class="review">
            <img src="./images/male2.webp" alt="Bob" class="person-image">
            <p><strong>Bob</strong></p>
            <p>Rating: 5/5</p>
            <p>Comment: Impressive quality. I was pleasantly surprised by the high quality of the product. It has added great value to my daily routine.</p>
            <p>Date: November 20, 2023</p>
        </div>

        <!-- Eve -->
        <div class="review">
            <img src="./images/female2.webp" alt="Eve" class="person-image">
            <p><strong>Eve</strong></p>
            <p>Rating: 4/5 </p>
            <p>Comment: I'm happy with my purchase. The product is durable and meets my expectations. Delivery was on time, and the packaging was secure.</p>
            <p>Date: November 25, 2023</p>
        </div>

        <!-- Charlie -->
        <div class="review">
            <img src="./images/male3.jpg" alt="Charlie" class="person-image">
            <p><strong>Charlie</strong></p>
            <p>Rating: 2/5 </p>
            <p>Comment: Not satisfied with the product quality. It didn't meet my expectations, and I encountered issues shortly after purchase. Customer support was slow to respond.</p>
            <p>Date: November 8, 2023</p>
        </div>

        <!-- Grace -->
        <div class="review">
            <img src="./images/female3.webp" alt="Grace" class="person-image">
            <p><strong>Grace</strong></p>
            <p>Rating: 5/5 </p>
            <p>Comment: Amazing product! It exceeded my expectations, and the customer service was exceptional. I highly recommend it to others.</p>
            <p>Date: November 30, 2023</p>
        </div>
    </div>

    <?php
        $allReviews = $db->getAllReviews();

        foreach ($allReviews as $review) {
            echo <<<EOT
            <div class="review">
                <!-- <img src="./images/{$review['image']}" alt="{$review['name']}" class="person-image"> -->
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
        <form id="user-review-form" action="" method="POST">
            <label for="username">Your Name:</label>
            <input type="text" id="username" required><br><br>
            <label for="rating">Rating:</label>
            <input type="number" id="rating" min="1" max="5" required><br><br>
            <label for="comment">Your Review:</label><br>
            <textarea id="comment" required></textarea><br><br>
            <input type="hidden" name="submitted" value="yeah">
            <button type="button" onclick="submitReview()">Submit Review</button>
        </form>
    </div>

    <script src="_scripts/reviews.js"></script>
    <script>
        
    </script>
</div>
<?php include "_components/footer.php"; ?>
</body>
</html>
