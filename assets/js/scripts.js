document.addEventListener('DOMContentLoaded', function () {
    // Reusable function to toggle favourite status
    const handleFavouriteToggle = (favouriteBtn, userId, recipeId) => {
        fetch('/recipe-web-app/templates/mark_favourite.php', {
            method: 'POST',
            body: JSON.stringify({ userId: userId, recipeId: recipeId }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const isInAccountPage = window.location.pathname.includes("account.php");

                if (data.action === 'added') {
                    favouriteBtn.innerText = 'Remove from Favourites';
                } else if (data.action === 'removed') {
                    if (isInAccountPage) {
                        // Remove the entire table row for My Account page
                        const row = favouriteBtn.closest('tr');
                        if (row) row.remove();
                    } else {
                        favouriteBtn.innerText = 'Mark as Favourite';
                    }
                }
            } else {
                alert('Something went wrong. Please try again.');
            }
        })
        .catch(error => {
            console.error('Toggle favourite failed:', error);
            alert('Failed to connect to server.');
        });
    };

    // Bind all favourite buttons
    document.querySelectorAll('.favourite-btn').forEach(button => {
        const userId = button.dataset.userId;
        const recipeId = button.dataset.recipeId;

        if (!userId || !recipeId) {
            console.warn('Missing data attributes on favourite button');
            return;
        }

        // Prevent duplicate listener
        button.removeEventListener('click', button._favouriteListener);

        // Assign event
        button._favouriteListener = function () {
            handleFavouriteToggle(button, userId, recipeId);
        };

        button.addEventListener('click', button._favouriteListener);
    });

    // Search form validation
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            const query = document.getElementById('query');
            if (query && query.value.trim() === '') {
                e.preventDefault();
                alert('Please enter a search term.');
            }
        });
    }
});
