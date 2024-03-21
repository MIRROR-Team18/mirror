<?php
    require_once "./_components/database.php";
    $db = new Database();

    if (isset($_POST["submitted"])) {
        $db = new Database();
        try {
            $img = $_FILES['image'];
            $path = "./_image/reviews/";

            if (!scandir($path)) {
                mkdir($path);
            }

            $imgPath = $path . $img['name'];
            if ($img['error'] == 0) {
                move_uploaded_file($img['tmp_name'], $imgPath);
            } else {
                throw new Exception("Issue with image!");
            }

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
    <script src="/_scripts/reviews.js" defer async></script>
</head>
<body>
    <?php include "_components/header.php"; ?>
    <div class="header">
        <h1>Rating & Reviews</h1>
    </div>

    <main class="container">
        <div class="sort-options">
            <label for="sort-select">Sort By:</label>
            <form method="POST" action="">
                <select id="sort-select" name="ordered" onchange="this.form.submit()">
                    <option value="overall">Overall Rating</option>
                    <option value="lowest">Lowest Rating</option>
                    <option value="highest">Highest Rating</option>
                    <option value="newest">Newest Review</option>
                    <option value="oldest">Oldest Review</option>
                </select>
            </form>
        </div>
        <div id="reviews-list">
            <?php 
                $ordered = $_POST['ordered'] ?? 'overall';
                $reviews = match($ordered) {
                    'overall' => $db->getAllReviews(),
                    'lowest' => $db->sortbyLowest(),
                    'highest' => $db->sortbyHighest(),
                    'newest' => $db->sortbyNewest(),
                    'oldest' => $db->sortbyOldest(),
                };
                foreach ($reviews as $review) {
                    $name = $review["name"];
                    $rating = $review["rating"];
                    $comment = $review["comment"];
                    $date = $review["date"];
                    $imagePath = "/_images/reviews/" . $review["filename"];
                
                    echo <<<HTML
                        <div class="review">
                            <img src="$imagePath" alt="$name's photo" class="review-image">
                            <div class="review-details">
                                <h3>$name</h3>
                                <p class="rating">Rating: $rating/5</p>
                                <p class="comment">Comment: $comment</p>
                                <p class="date">Date: $date</p>
                            </div>
                        </div>
                    HTML;
                }
            ?>
        </div>

        <div class="reviews-container">
            <!-- Add a Review Button -->
            <button id="add-review-button" onclick="showReviewForm()">Add a Review</button>

            <!-- Updated review form initially hidden -->
            <div id="review-form" class="review-form" style="display: none; text-align: center; margin-top: 20px;">
                <form id="user-review-form" method="POST" enctype="multipart/form-data">
                    <label for="username">Your Name:</label>
                    <input type="text" name="name" id="username" required><br><br>
                    <label for="rating">Rating:</label>
                    <input type="number" name="rating" id="rating" min="1" max="5" required><br><br>
                    <label for="comment">Your Review:</label><br>
                    <textarea id="comment" name="comment" required></textarea><br><br>
                    <input type="hidden" name="submitted" value="yeah">
                    <label for="imageInput">Choose an image:</label>
                    <input type="file" id="imageInput" name="image" accept="image/*" required>
                    <button type="submit">Submit Review</button><br><br>
                </form>
            </div>
        </div>
    </main>
    <?php include "_components/footer.php"; ?>
</body>
</html>
