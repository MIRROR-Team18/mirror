// Sample reviews data with added date property
const reviewsData = [
    { username: 'John Doe', rating: 4, comment: 'Great product! I love how it exceeded my expectations. The quality is exceptional, and it arrived sooner than I expected.', date: new Date('2023-11-10') },
    { username: 'Jane Smith', rating: 5, comment: 'Excellent service! The customer support team was very helpful in addressing my queries. The product itself is of top-notch quality. Will definitely recommend to others.', date: new Date('2023-11-15') },
    { username: 'Alice', rating: 3, comment: 'Good experience. The product was decent, but there is room for improvement. Delivery was prompt, and the packaging was secure.', date: new Date('2023-11-05') },
    { username: 'Bob', rating: 5, comment: 'Impressive quality. I was pleasantly surprised by the high quality of the product. It has added great value to my daily routine.', date: new Date('2023-11-20') },
    { username: 'Charlie', rating: 4, comment: 'Satisfied with the purchase. The product met my expectations. It is durable and performs well. Overall, a good investment.', date: new Date('2023-11-08') },
    { username: 'Eva', rating: 3, comment: 'Decent product. It serves its purpose, but there are better options available in the market.', date: new Date('2023-11-18') },
    { username: 'Frank', rating: 4, comment: 'Reliable product. I have been using it for months without any issues. The performance is consistent, and I am happy with my purchase.', date: new Date('2023-11-12') },
    { username: 'Grace', rating: 5, comment: 'Exceptional quality. The product exceeded my expectations. It is durable, reliable, and worth every penny.', date: new Date('2023-11-25') },,
    { username: 'Henry', rating: 2, comment: 'Disappointing. The product did not live up to the hype. I encountered issues shortly after purchase, and customer support was unhelpful.', date: new Date('2023-11-07') },
    { username: 'Ivy', rating: 4, comment: 'Good value for money. The product provides good features at an affordable price. I am satisfied with my purchase.', date: new Date('2023-11-22')}];



// Function to display reviews on the page
function displayReviews() {
    const reviewsList = document.getElementById('reviews-list');
    reviewsList.innerHTML = ''; // Clear previous reviews

    reviewsData.forEach(review => {
        const reviewElement = document.createElement('div');
        reviewElement.classList.add('review');
        reviewElement.innerHTML = `
            <h3>${review.username}</h3>
            <p>Rating: ${review.rating}/5</p>
            <p>${review.comment}</p>
        `;
        reviewsList.appendChild(reviewElement);
    });

    updateReviewInfo(); // Update review information after displaying reviews
}

// Function to sort reviews based on specified criteria
function sortReviews(criteria) {
    switch (criteria) {
        case 'overall':
            reviewsData.sort((a, b) => b.rating - a.rating);
            break;
        case 'lowest':
            reviewsData.sort((a, b) => a.rating - b.rating);
            break;
        case 'highest':
            reviewsData.sort((a, b) => b.rating - a.rating);
            break;
        case 'newest':
            reviewsData.sort((a, b) => new Date(b.date) - new Date(a.date));
            break;
        case 'oldest':
            reviewsData.sort((a, b) => new Date(a.date) - new Date(b.date));
            break;
        default:
            break;
    }

    // Display updated reviews
    displayReviews();
}

// Function to show the review form
function showReviewForm() {
    const reviewForm = document.getElementById('review-form');
    reviewForm.style.display = 'block';
}

// Function to submit a new review
function submitReview() {
    const usernameInput = document.getElementById('username');
    const ratingInput = document.getElementById('rating');
    const commentInput = document.getElementById('comment');

    // Get the values from the inputs
    const username = usernameInput.value.trim();
    const rating = parseInt(ratingInput.value, 10);
    const comment = commentInput.value.trim();

    // Validate inputs
    if (!username || isNaN(rating) || rating < 1 || rating > 5 || !comment) {
        alert('Please fill in all fields and provide a valid rating between 1 and 5.');
        return;
    }

    // Add the new review to the data
    const newReview = {
        username,
        rating,
        comment,
        date: new Date(), // Use the current date as the review date
    };
    reviewsData.push(newReview);

    // Clear the input fields
    usernameInput.value = '';
    ratingInput.value = '';
    commentInput.value = '';

    // Display updated reviews
    displayReviews();

    // Hide the review form
    const reviewForm = document.getElementById('review-form');
    reviewForm.style.display = 'none';
}

// Function to update review information (overall rating, lowest rating, etc.)
function updateReviewInfo() {
    const overallRatingElement = document.getElementById('overall-rating');
    const lowestRatingElement = document.getElementById('lowest-rating');
    const highestRatingElement = document.getElementById('highest-rating');
    const newestReviewElement = document.getElementById('newest-review');
    const oldestReviewElement = document.getElementById('oldest-review');

    const overallRating = calculateOverallRating();
    const lowestRating = findLowestRating();
    const highestRating = findHighestRating();
    const newestReview = findNewestReview();
    const oldestReview = findOldestReview();

    overallRatingElement.textContent = overallRating.toFixed(2);
    lowestRatingElement.textContent = lowestRating ? `${lowestRating}/5` : '--';
    highestRatingElement.textContent = highestRating ? `${highestRating}/5` : '--';
    newestReviewElement.textContent = newestReview ? formatDate(newestReview.date) : '--';
    oldestReviewElement.textContent = oldestReview ? formatDate(oldestReview.date) : '--';
}

// Function to calculate the overall rating based on existing reviews
function calculateOverallRating() {
    const totalRating = reviewsData.reduce((sum, review) => sum + review.rating, 0);
    const averageRating = totalRating / reviewsData.length;
    return isNaN(averageRating) ? 0 : averageRating;
}

// Function to find the review with the lowest rating
function findLowestRating() {
    return reviewsData.length > 0 ? Math.min(...reviewsData.map(review => review.rating)) : null;
}

// Function to find the review with the highest rating
function findHighestRating() {
    return reviewsData.length > 0 ? Math.max(...reviewsData.map(review => review.rating)) : null;
}

// Function to find the newest review
function findNewestReview() {
    return reviewsData.length > 0 ? reviewsData.reduce((newest, review) => (newest.date > review.date ? newest : review)) : null;
}

// Function to find the oldest review
function findOldestReview() {
    return reviewsData.length > 0 ? reviewsData.reduce((oldest, review) => (oldest.date < review.date ? oldest : review)) : null;
}

// Function to format a date as "MM/DD/YYYY"
function formatDate(date) {
    const options = { month: '2-digit', day: '2-digit', year: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}
// Function to redirect to reviews based on the selected criteria
function redirectToReviews(criteria) {
    switch (criteria) {
        case 'overall':
            sortReviews('overall');
            break;
        case 'lowest':
            sortReviews('lowest');
            break;
        case 'highest':
            sortReviews('highest');
            break;
        case 'newest':
            sortReviews('newest');
            break;
        case 'oldest':
            sortReviews('oldest');
            break;
        default:
            break;
    }

    // Scroll to the reviews section
    const reviewsContainer = document.getElementById('reviews-list');
    reviewsContainer.scrollIntoView({ behavior: 'smooth' });
}

// Initial display of reviews
displayReviews();
