// Sample reviews data (replace with your actual data)
const reviewsData = [
    { name: "John Doe", rating: 4, comment: "Great product!", date: "November 10, 2023" },
    { name: "Jane Smith", rating: 5, comment: "Excellent service!", date: "November 15, 2023" },
    // Add more reviews as needed
];

// Function to render reviews
function renderReviews(reviews) {
    const reviewsContainer = document.getElementById("reviews-list");
    reviewsContainer.innerHTML = "";

    reviews.forEach(review => {
        const reviewElement = document.createElement("div");
        reviewElement.classList.add("review");

        reviewElement.innerHTML = `'
            <img src="./images/istockphoto-1364917563-612x612.jpg" alt="${review.name}">
            <p><strong>${review.name}</strong></p>
            <p>Rating: ${review.rating}/5</p>
            <p>Comment: ${review.comment}</p>
            <p class="review-date">Date: ${review.date}</p>
        `;

        reviewsContainer.appendChild(reviewElement);
    });
}

// Function to sort reviews
function sortReviews() {
    const sortSelect = document.getElementById("sort-select");
    const selectedSort = sortSelect.value;

    // Perform sorting logic based on selectedSort
    let sortedReviews = [...reviewsData];

    switch (selectedSort) {
        case "lowest":
            sortedReviews.sort((a, b) => a.rating - b.rating);
            break;
        case "highest":
            sortedReviews.sort((a, b) => b.rating - a.rating);
            break;
        case "newest":
            sortedReviews.sort((a, b) => new Date(b.date) - new Date(a.date));
            break;
        case "oldest":
            sortedReviews.sort((a, b) => new Date(a.date) - new Date(b.date));
            break;
        // Default to overall rating
        case "overall":
        default:
            sortedReviews = sortedReviews; // No specific sorting for overall rating
            break;
    }

    // Render the sorted reviews
    renderReviews(sortedReviews);
}

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
        sortReviews(); // Re-render reviews after adding a new one
        clearReviewForm(); // Clear the form
    }
}

// Function to clear the review form
function clearReviewForm() {
    document.getElementById("username").value = "";
    document.getElementById("rating").value = "";
    document.getElementById("comment").value = "";
}

// Initial render
sortReviews();
