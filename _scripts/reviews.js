// Function to show the review form
function showReviewForm() {
    const reviewForm = document.getElementById("review-form");
    const isVisible = window.getComputedStyle(reviewForm).display !== "none";

    // Toggle the display of the review form
    reviewForm.style.display = isVisible ? "none" : "flex";
}

// Function to add a new review
function submitReview() {
    const username = document.getElementById("username").value;
    const rating = document.getElementById("rating").value;
    const comment = document.getElementById("comment").value;

    if (username && rating && comment) {
        const newReview = {
            name: username,
            rating: parseInt(rating),
            comment: comment,
            date: new Date().toLocaleDateString("en-US", { year: 'numeric', month: 'long', day: 'numeric' })
        };

        reviewsData.push(newReview);
        clearReviewForm(); // Clear the form
    }
}

// Function to clear the review form
function clearReviewForm() {
    document.getElementById("username").value = "";
    document.getElementById("rating").value = "";
    document.getElementById("comment").value = "";
}