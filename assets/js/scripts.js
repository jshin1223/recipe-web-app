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

    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            const query = document.getElementById('query')?.value.trim();
            const prep = document.getElementById('prep_time_range')?.value;
            const cook = document.getElementById('cook_time_range')?.value;
            const serves = document.getElementById('serves_min')?.value;
            const diet = document.getElementById('dietary')?.value;
            const sort = document.getElementById('sort')?.value;

            const anyInput = query || prep || cook || serves || diet || sort;

            if (!anyInput) {
                e.preventDefault();
                alert('Please enter a search term or select at least one filter.');
            }
        });
    }

    // Theme switcher logic
    function updateHeaderImage(theme) {
        const logoImg = document.getElementById('theme-logo');
        if (!logoImg) return;

        switch (theme) {
            case 'dark':
                logoImg.src = '/recipe-web-app/assets/images/title_dark.png';
                break;
            case 'high-contrast':
                logoImg.src = '/recipe-web-app/assets/images/title_highcontrast.png';
                break;
            case 'light':
            default:
                logoImg.src = '/recipe-web-app/assets/images/title_light.png';
                break;
        }
    }

    function setTheme(mode) {
        document.body.classList.remove('theme-light', 'theme-dark', 'theme-high-contrast');
        document.body.classList.add(`theme-${mode}`);
        localStorage.setItem('preferredTheme', mode);
        updateHeaderImage(mode);
    }

    // Apply saved theme on load
    const savedTheme = localStorage.getItem('preferredTheme') || 'light';
    setTheme(savedTheme);

    // Attach click events to theme buttons
    document.querySelectorAll('button[data-theme]').forEach(button => {
        button.addEventListener('click', () => {
            const mode = button.getAttribute('data-theme');
            setTheme(mode);
        });
    });

    // Font size toggling
    document.querySelectorAll('.font-size-btn').forEach(button => {
        button.addEventListener('click', () => {
            const fontSize = button.getAttribute('data-font');
            document.body.classList.remove('font-small', 'font-medium', 'font-large');
            document.body.classList.add(`font-${fontSize}`);
            localStorage.setItem('preferredFontSize', fontSize);
        });
    });

    // Apply saved font size
    const savedFontSize = localStorage.getItem('preferredFontSize') || 'medium';
    document.body.classList.add(`font-${savedFontSize}`);

    // Toggle Edit Profile form on My Account page
    const toggleEditBtn = document.getElementById('toggleEditBtn');
    const editFormContainer = document.getElementById('editFormContainer');

    if (toggleEditBtn && editFormContainer) {
        toggleEditBtn.addEventListener('click', function () {
            editFormContainer.style.display =
                editFormContainer.style.display === 'none' ? 'block' : 'none';
        });
    }

});
