<?php
// Include the shared header (black background and menu intact)
require_once '../includes/header.php';
require_once '../includes/db.php';
?>

<div class="container">
    <h2>Search Recipes</h2>
    
    <!-- Search Form -->
    <form id="search-form" action="search.php" method="get">
        <label for="query">Search:</label>
        <input type="text" id="query" name="query" placeholder="Enter keywords">
        <button type="submit" class="btn-search">Search</button>
    </form>
    
    <hr>
    
    <!-- Button to List All Recipes -->
    <div class="list-recipes-btn">
        <form action="all_recipes.php" method="get">
            <button type="submit" class="btn-list-recipes">List All Recipes</button>
        </form>
    </div>
    
    <hr>

    <?php
    // If a search query is submitted and not empty
    if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
        $query = trim($_GET['query']);

        // Perform search query on recipe title and description
        $stmt = $pdo->prepare("SELECT * FROM recipes WHERE title LIKE ? OR description LIKE ?");
        $searchTerm = '%' . $query . '%';
        $stmt->execute([$searchTerm, $searchTerm]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If there are matching recipes, display them
        if (count($results) > 0) {
            echo "<h3>Results for: " . htmlspecialchars($query) . "</h3>";
            echo "<ul>";
            foreach ($results as $recipe) {
                echo "<li><a href='/recipe-web-app/templates/recipe.php?id=" . $recipe['id'] . "'>" . htmlspecialchars($recipe['title']) . "</a></li>";
            }
            echo "</ul>";
        } else {
            // If no results were found
            echo "<p>No recipes found for '" . htmlspecialchars($query) . "'.</p>";
        }
    }
    ?>
</div>

<?php
// Include the shared footer (black background)
require_once '../includes/footer.php';
?>
