<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

$recipeId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($recipeId > 0) {
    // Retrieve recipe details from the database
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$recipeId]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Retrieve ingredients for this recipe
    $stmtIngredients = $pdo->prepare("SELECT * FROM recipe_ingredients WHERE recipe_id = ?");
    $stmtIngredients->execute([$recipeId]);
    $ingredients = $stmtIngredients->fetchAll(PDO::FETCH_ASSOC);
    
    // Retrieve preparation steps for this recipe
    $stmtSteps = $pdo->prepare("SELECT * FROM recipe_steps WHERE recipe_id = ?");
    $stmtSteps->execute([$recipeId]);
    $steps = $stmtSteps->fetchAll(PDO::FETCH_ASSOC);
    
    // Retrieve ratings for this recipe
    $stmtRatings = $pdo->prepare("SELECT AVG(difficulty) AS avg_difficulty, AVG(aesthetics) AS avg_aesthetics, AVG(taste) AS avg_taste, AVG(overall) AS avg_overall FROM ratings WHERE recipe_id = ?");
    $stmtRatings->execute([$recipeId]);
    $ratings = $stmtRatings->fetch(PDO::FETCH_ASSOC);

    // Check if the current user has this recipe marked as a favourite
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $stmtFavourite = $pdo->prepare("SELECT * FROM favourites WHERE user_id = ? AND recipe_id = ?");
        $stmtFavourite->execute([$userId, $recipeId]);
        $isFavourite = $stmtFavourite->fetch(PDO::FETCH_ASSOC) ? true : false;
    }
}
?>

<div class="container">
    <?php if ($recipe): ?>
        <h2><?php echo htmlspecialchars($recipe['title']); ?></h2>
        <p class="description"><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>

        <hr>

        <!-- Ingredients Section -->
        <h3>Ingredients</h3>
        <table class="recipe-table">
            <thead>
                <tr>
                    <th>Ingredient</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ingredients as $ingredient): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ingredient['ingredient']); ?></td>
                        <td><?php echo htmlspecialchars($ingredient['quantity']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>

        <!-- Preparation Steps Section -->
        <h3>Preparation Steps</h3>
        <ol>
            <?php foreach ($steps as $step): ?>
                <li><?php echo htmlspecialchars($step['instruction']); ?></li>
            <?php endforeach; ?>
        </ol>

        <hr>

        <!-- Ratings Section -->
        <h3>Average Ratings</h3>
        <table class="recipe-table">
            <tr>
                <td><strong>Difficulty:</strong></td>
                <td><?php echo round($ratings['avg_difficulty'], 2) . '/5'; ?></td>
            </tr>
            <tr>
                <td><strong>Aesthetics:</strong></td>
                <td><?php echo round($ratings['avg_aesthetics'], 2) . '/5'; ?></td>
            </tr>
            <tr>
                <td><strong>Taste:</strong></td>
                <td><?php echo round($ratings['avg_taste'], 2) . '/5'; ?></td>
            </tr>
            <tr>
                <td><strong>Overall Rating:</strong></td>
                <td><?php echo round($ratings['avg_overall'], 2) . '/5'; ?></td>
            </tr>
        </table>

        <hr>

        <!-- Rating Submission Section -->
        <h3>Rate this Recipe</h3>
        <form action="/recipe-web-app/templates/rate_recipe.php" method="post">
            <input type="hidden" name="recipe_id" value="<?php echo $recipeId; ?>">
            <label for="difficulty">Difficulty (1-5):</label>
            <input type="number" id="difficulty" name="difficulty" min="1" max="5" required>
            <br>
            <label for="aesthetics">Aesthetics (1-5):</label>
            <input type="number" id="aesthetics" name="aesthetics" min="1" max="5" required>
            <br>
            <label for="taste">Taste (1-5):</label>
            <input type="number" id="taste" name="taste" min="1" max="5" required>
            <br>
            <label for="overall">Overall (1-5):</label>
            <input type="number" id="overall" name="overall" min="1" max="5" required>
            <br>
            <button type="submit">Submit Rating</button>
        </form>

        <hr>

        <!-- Favourite Button -->
        <?php if (isset($userId)): ?>
            <button id="favourite-btn" class="favourite-btn" data-recipe-id="<?php echo $recipeId; ?>" data-user-id="<?php echo $_SESSION['user_id']; ?>">
                <?php echo $isFavourite ? 'Remove from Favourites' : 'Mark as Favourite'; ?>
            </button>
        <?php endif; ?>

    <?php else: ?>
        <p>Recipe not found.</p>
    <?php endif; ?>
</div>

<script>
// JavaScript for handling the favourite button
document.addEventListener('DOMContentLoaded', function () {
    const favouriteBtn = document.getElementById('favourite-btn');
    if (favouriteBtn) {
        favouriteBtn.addEventListener('click', function () {
            const recipeId = favouriteBtn.getAttribute('data-recipe-id');
            const userId = favouriteBtn.getAttribute('data-user-id');
            const isFavourite = favouriteBtn.innerText === 'Remove from Favourites';

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
                // Update button text based on favourite status
                if (data.success) {
                    favouriteBtn.innerText = isFavourite ? 'Mark as Favourite' : 'Remove from Favourites';
                } else {
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    }
});
</script>

<?php
require_once '../includes/footer.php';
?>
