<?php
// Include the shared header (black background and menu intact)
require_once '../includes/header.php';
require_once '../includes/db.php';
?>

<!-- Background wrapper with image -->
<div class="background-wrapper" style="background-image: url('/recipe-web-app/assets/images/bgimage.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed; padding: 2rem; border-radius: 12px;">

    <!-- Theme-aware semi-transparent overlay -->
    <div class="container themed-overlay">
        <h2>All Recipes</h2>

        <!-- Sort Form -->
        <form method="get" action="">
            <label for="sort">Sort By:</label>
            <select name="sort" id="sort" onchange="this.form.submit()">
                <option value="">-- Sort By --</option>
                <option value="prep_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'prep_asc') echo 'selected'; ?>>Prep Time (Asc)</option>
                <option value="prep_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'prep_desc') echo 'selected'; ?>>Prep Time (Desc)</option>
                <option value="cook_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'cook_asc') echo 'selected'; ?>>Cook Time (Asc)</option>
                <option value="cook_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'cook_desc') echo 'selected'; ?>>Cook Time (Desc)</option>
                <option value="serve_asc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'serve_asc') echo 'selected'; ?>>Serves (Asc)</option>
                <option value="serve_desc" <?php if (isset($_GET['sort']) && $_GET['sort'] == 'serve_desc') echo 'selected'; ?>>Serves (Desc)</option>
            </select>
        </form>

        <?php
        // Sort logic
        $sortSql = "ORDER BY title ASC";
        if (isset($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'prep_asc': $sortSql = "ORDER BY prep_time_min ASC"; break;
                case 'prep_desc': $sortSql = "ORDER BY prep_time_min DESC"; break;
                case 'cook_asc': $sortSql = "ORDER BY cook_time_min ASC"; break;
                case 'cook_desc': $sortSql = "ORDER BY cook_time_min DESC"; break;
                case 'serve_asc': $sortSql = "ORDER BY serves_min ASC"; break;
                case 'serve_desc': $sortSql = "ORDER BY serves_min DESC"; break;
            }
        }

        // Fetch all recipes from the database with sort
        $stmt = $pdo->query("SELECT * FROM recipes $sortSql");
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($recipes) > 0) {
            echo "<ul style='padding-left: 2rem; margin-top: 1rem;'>";
            foreach ($recipes as $recipe) {
                echo "<li style='margin-bottom: 0.5rem;'><a href='/recipe-web-app/templates/recipe.php?id=" . $recipe['id'] . "'>" . htmlspecialchars($recipe['title']) . "</a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No recipes available.</p>";
        }
        ?>
    </div>
</div>

<?php
// Include the shared footer (black background)
require_once '../includes/footer.php';
?>
