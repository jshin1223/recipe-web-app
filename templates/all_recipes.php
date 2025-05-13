<?php
// Include the shared header (black background and menu intact)
require_once '../includes/header.php';
require_once '../includes/db.php';
?>

<div class="container">
    <h2>All Recipes</h2>

    <?php
    // Fetch all recipes from the database
    $stmt = $pdo->query("SELECT * FROM recipes");
    $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($recipes) > 0) {
        echo "<ul>";
        foreach ($recipes as $recipe) {
            echo "<li><a href='/recipe-web-app/templates/recipe.php?id=" . $recipe['id'] . "'>" . htmlspecialchars($recipe['title']) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No recipes available.</p>";
    }
    ?>
</div>

<?php
// Include the shared footer (black background)
require_once '../includes/footer.php';
?>
