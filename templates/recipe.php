<?php
// Include shared header and database connection
require_once '../includes/header.php';
require_once '../includes/db.php';

// Get recipe ID from query string (e.g., ?id=2), default to 0 if missing
$recipeId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($recipeId > 0) {
    // Fetch recipe details from the database
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$recipeId]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch ingredients for the recipe
    $stmtIngredients = $pdo->prepare("SELECT * FROM recipe_ingredients WHERE recipe_id = ?");
    $stmtIngredients->execute([$recipeId]);
    $ingredients = $stmtIngredients->fetchAll(PDO::FETCH_ASSOC);

    // Fetch preparation steps
    $stmtSteps = $pdo->prepare("SELECT * FROM recipe_steps WHERE recipe_id = ?");
    $stmtSteps->execute([$recipeId]);
    $steps = $stmtSteps->fetchAll(PDO::FETCH_ASSOC);

    // Fetch average user ratings for this recipe
    $stmtRatings = $pdo->prepare("SELECT AVG(difficulty) AS avg_difficulty, AVG(aesthetics) AS avg_aesthetics, AVG(taste) AS avg_taste, AVG(overall) AS avg_overall FROM ratings WHERE recipe_id = ?");
    $stmtRatings->execute([$recipeId]);
    $ratings = $stmtRatings->fetch(PDO::FETCH_ASSOC);

    // Check if the current user has marked this recipe as favourite
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

    <!-- Display the recipe title -->
    <h2 style="text-align: center; font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem;">
        <?php echo htmlspecialchars($recipe['title']); ?>
    </h2>

    <!-- Display recipe image if available -->
    <?php if (!empty($recipe['image_url'])): ?>
        <div style="text-align:center; margin: 1rem 0;">
            <img src="/recipe-web-app/<?php echo htmlspecialchars($recipe['image_url']); ?>" 
                 alt="Image of <?php echo htmlspecialchars($recipe['title']); ?>" 
                 style="max-width:100%; height:auto; border-radius: 8px;">
        </div>
    <?php endif; ?>

    <!-- Embed audio file if available -->
    <?php if (!empty($recipe['audio_filename'])): ?>
        <div style="text-align:center; margin-bottom: 1.5rem;">
            <audio controls preload="none">
                <source src="/recipe-web-app/assets/audio/<?php echo htmlspecialchars($recipe['audio_filename']); ?>" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>
    <?php endif; ?>

    <!-- Main recipe content to be read aloud or viewed -->
    <div id="main-content">
        <!-- Recipe description -->
        <p class="description"><?php echo nl2br(htmlspecialchars($recipe['description'])); ?></p>

        <!-- Basic metadata: prep/cook time, serves, dietary info -->
        <table class="recipe-table" style="margin-top: 1rem;">
            <tr><td><strong>Prep Time:</strong></td><td><?php echo htmlspecialchars($recipe['prep_time']); ?></td></tr>
            <tr><td><strong>Cook Time:</strong></td><td><?php echo htmlspecialchars($recipe['cook_time']); ?></td></tr>
            <tr><td><strong>Serves:</strong></td><td><?php echo htmlspecialchars($recipe['serve']); ?></td></tr>
            <tr><td><strong>Dietary:</strong></td><td><?php echo htmlspecialchars($recipe['dietary']); ?></td></tr>
        </table>

        <hr>

        <!-- Ingredient list -->
        <h3>Ingredients</h3>
        <table class="recipe-table">
            <thead>
                <tr><th>Ingredient</th><th>Quantity</th></tr>
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

        <!-- Step-by-step preparation instructions -->
        <h3>Preparation Steps</h3>
        <ol class="recipe-steps">
            <?php foreach ($steps as $step): ?>
                <li><?php echo htmlspecialchars($step['instruction']); ?></li>
            <?php endforeach; ?>
        </ol>

        <!-- Optional tips provided for the recipe -->
        <?php if (!empty($recipe['recipe_tips'])): ?>
            <hr>
            <h3>Recipe Tips</h3>
            <p><?php echo nl2br(htmlspecialchars($recipe['recipe_tips'])); ?></p>
        <?php endif; ?>

        <hr>
    </div>

    <!-- Display average user ratings for various categories -->
    <h3>Average Ratings</h3>
    <table class="recipe-table">
        <tr><td><strong>Difficulty:</strong></td><td><?php echo round($ratings['avg_difficulty'], 2) . '/5'; ?></td></tr>
        <tr><td><strong>Aesthetics:</strong></td><td><?php echo round($ratings['avg_aesthetics'], 2) . '/5'; ?></td></tr>
        <tr><td><strong>Taste:</strong></td><td><?php echo round($ratings['avg_taste'], 2) . '/5'; ?></td></tr>
        <tr><td><strong>Overall Rating:</strong></td><td><?php echo round($ratings['avg_overall'], 2) . '/5'; ?></td></tr>
    </table>

    <hr>

    <!-- Rating submission form -->
    <h3>Rate this Recipe</h3>
    <form action="/recipe-web-app/templates/rate_recipe.php" method="post">
        <input type="hidden" name="recipe_id" value="<?php echo $recipeId; ?>">
        <label for="difficulty">Difficulty (1–5):</label>
        <input type="number" id="difficulty" name="difficulty" min="1" max="5" required><br>
        <label for="aesthetics">Aesthetics (1–5):</label>
        <input type="number" id="aesthetics" name="aesthetics" min="1" max="5" required><br>
        <label for="taste">Taste (1–5):</label>
        <input type="number" id="taste" name="taste" min="1" max="5" required><br>
        <label for="overall">Overall (1–5):</label>
        <input type="number" id="overall" name="overall" min="1" max="5" required><br>
        <button type="submit">Submit Rating</button>
    </form>

    <hr>

    <!-- Favourite toggle button for logged-in users -->
    <?php if (isset($userId)): ?>
        <button id="favourite-btn" class="favourite-btn" data-recipe-id="<?php echo $recipeId; ?>" data-user-id="<?php echo $_SESSION['user_id']; ?>">
            <?php echo $isFavourite ? 'Remove from Favourites' : 'Mark as Favourite'; ?>
        </button>
    <?php endif; ?>

    <hr>

    <!-- Show source URL if available -->
    <?php if (!empty($recipe['source_url'])): ?>
        <p style="word-wrap: break-word; overflow-wrap: break-word; word-break: break-word;">
        <strong>Source:</strong> 
        <a href="<?php echo htmlspecialchars($recipe['source_url']); ?>" target="_blank" style="word-break: break-word;">
            <?php echo htmlspecialchars($recipe['source_url']); ?>
        </a>
    </p>
    <?php endif; ?>

<?php else: ?>
    <!-- Fallback message if the recipe ID is invalid or missing -->
    <p>Recipe not found.</p>
<?php endif; ?>
</div>

<?php
// Include the footer to close off the page layout
require_once '../includes/footer.php';
?>
