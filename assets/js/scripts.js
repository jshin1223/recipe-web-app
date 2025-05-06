document.addEventListener('DOMContentLoaded', function () {
    // Function to handle the favourite button toggle for a single recipe page
    const handleFavouriteToggle = (favouriteBtn, userId, recipeId) => {
        // AJAX request to toggle favourite status
        fetch('/recipe-web-app/templates/mark_favourite.php', {
            method: 'POST',
            body: JSON.stringify({ userId: userId, recipeId: recipeId }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            // If successful, update the button text based on the action
            if (data.success) {
                if (data.action === 'added') {
                    favouriteBtn.innerText = 'Remove from Favourites';
                } else if (data.action === 'removed') {
                    favouriteBtn.innerText = 'Mark as Favourite';
                }
            } else {
                alert('Something went wrong. Please try again.');
            }
        });
    };

    // Handling for individual recipe page favourite button
    const favouriteBtn = document.getElementById('favourite-btn');
    if (favouriteBtn) {
        const userId = favouriteBtn.getAttribute('data-user-id');
        const recipeId = favouriteBtn.getAttribute('data-recipe-id');
        
        favouriteBtn.addEventListener('click', function () {
            handleFavouriteToggle(favouriteBtn, userId, recipeId);
        });
    }

    // Handling for all favourite buttons across recipe cards (e.g., on the homepage or search page)
    const favouriteBtns = document.querySelectorAll('.favourite-btn');
    favouriteBtns.forEach(favouriteBtn => {
        const userId = favouriteBtn.getAttribute('data-user-id');
        const recipeId = favouriteBtn.getAttribute('data-recipe-id');
        
        favouriteBtn.addEventListener('click', function () {
            handleFavouriteToggle(favouriteBtn, userId, recipeId);
        });
    });

    // Handle the search form validation (for Search Recipe page)
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            const query = document.getElementById('query');
            if (query.value.trim() === '') {
                e.preventDefault(); // Prevent form submission
                alert('Please enter a search term.');
            }
        });
    }
});
