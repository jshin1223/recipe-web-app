<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /recipe-web-app/templates/login.php');
    exit();
}

// Get user details
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Get favourite recipes
$stmtFavourites = $pdo->prepare("SELECT recipes.id, recipes.title FROM recipes 
                                 JOIN favourites ON recipes.id = favourites.recipe_id 
                                 WHERE favourites.user_id = ?");
$stmtFavourites->execute([$_SESSION['user_id']]);
$favourites = $stmtFavourites->fetchAll(PDO::FETCH_ASSOC);

// Get user's average ratings
$stmtRatings = $pdo->prepare("SELECT AVG(difficulty) AS avg_difficulty, 
                                      AVG(aesthetics) AS avg_aesthetics,
                                      AVG(taste) AS avg_taste, 
                                      AVG(overall) AS avg_overall
                               FROM ratings 
                               WHERE user_id = ?");
$stmtRatings->execute([$_SESSION['user_id']]);
$ratings = $stmtRatings->fetch(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>My Account</h2>

    <!-- User Details -->
    <table class="recipe-table">
        <tr><th>Name</th><td><?php echo htmlspecialchars($user['name']); ?></td></tr>
        <tr><th>Email</th><td><?php echo htmlspecialchars($user['email']); ?></td></tr>
    </table>

    <hr>

    <!-- Favourite Recipes -->
    <h3>My Favourite Recipes</h3>
    <table class="recipe-table">
        <thead>
            <tr><th>Recipe Title</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($favourites as $favourite): ?>
                <tr>
                    <td>
                        <a href="/recipe-web-app/templates/recipe.php?id=<?php echo $favourite['id']; ?>">
                            <?php echo htmlspecialchars($favourite['title']); ?>
                        </a>
                    </td>
                    <td>
                        <button class="favourite-btn" style="font-size: 0.8em; padding: 4px 9px;"
                            data-recipe-id="<?php echo $favourite['id']; ?>"
                            data-user-id="<?php echo $_SESSION['user_id']; ?>">
                            Remove from Favourites
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <!-- Ratings -->
    <h3>My Average Ratings</h3>
    <table class="recipe-table">
        <tr><td><strong>Difficulty:</strong></td><td><?php echo round($ratings['avg_difficulty'], 2) . '/5'; ?></td></tr>
        <tr><td><strong>Aesthetics:</strong></td><td><?php echo round($ratings['avg_aesthetics'], 2) . '/5'; ?></td></tr>
        <tr><td><strong>Taste:</strong></td><td><?php echo round($ratings['avg_taste'], 2) . '/5'; ?></td></tr>
        <tr><td><strong>Overall Rating:</strong></td><td><?php echo round($ratings['avg_overall'], 2) . '/5'; ?></td></tr>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.favourite-btn').forEach(function (favouriteBtn) {
        favouriteBtn.addEventListener('click', function () {
            const recipeId = favouriteBtn.getAttribute('data-recipe-id');
            const userId = favouriteBtn.getAttribute('data-user-id');
            const isFavourite = favouriteBtn.innerText === 'Remove from Favourites';

            fetch('/recipe-web-app/templates/mark_favourite.php', {
                method: 'POST',
                body: JSON.stringify({ userId: userId, recipeId: recipeId }),
                headers: { 'Content-Type': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // On account page, remove the row
                    const row = favouriteBtn.closest('tr');
                    if (data.action === 'removed' && row) {
                        row.remove();
                    } else {
                        favouriteBtn.innerText = isFavourite ? 'Mark as Favourite' : 'Remove from Favourites';
                    }
                } else {
                    alert('Something went wrong.');
                }
            });
        });
    });
});
</script>

<?php
require_once '../includes/footer.php';
?>
