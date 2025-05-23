<?php
// Include the shared header (black background and menu intact)
require_once '../includes/header.php';
require_once '../includes/db.php';

$params = [];
$conditions = [];

$sql = "SELECT DISTINCT recipes.* 
        FROM recipes 
        LEFT JOIN recipe_ingredients ri ON recipes.id = ri.recipe_id 
        LEFT JOIN recipe_steps rs ON recipes.id = rs.recipe_id";

// Keyword search
if (!empty($_GET['query'])) {
    $searchTerm = '%' . trim($_GET['query']) . '%';
    $conditions[] = "(recipes.title LIKE ? OR recipes.description LIKE ? OR ri.ingredient LIKE ? OR rs.instruction LIKE ?)";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

// Filters
if (!empty($_GET['prep_time_range'])) {
    [$min, $max] = explode('-', $_GET['prep_time_range']);
    $conditions[] = "prep_time_min BETWEEN ? AND ?";
    $params[] = $min;
    $params[] = $max;
}

if (!empty($_GET['cook_time_range'])) {
    [$min, $max] = explode('-', $_GET['cook_time_range']);
    $conditions[] = "cook_time_min BETWEEN ? AND ?";
    $params[] = $min;
    $params[] = $max;
}

if (!empty($_GET['serves_min'])) {
    $conditions[] = "serves_min >= ?";
    $params[] = (int) $_GET['serves_min'];
}

if (!empty($_GET['dietary'])) {
    $conditions[] = "dietary LIKE ?";
    $params[] = '%' . $_GET['dietary'] . '%';
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Sorting
if (!empty($_GET['sort'])) {
    switch ($_GET['sort']) {
        case 'prep_asc': $sql .= " ORDER BY prep_time_min ASC"; break;
        case 'prep_desc': $sql .= " ORDER BY prep_time_min DESC"; break;
        case 'cook_asc': $sql .= " ORDER BY cook_time_min ASC"; break;
        case 'cook_desc': $sql .= " ORDER BY cook_time_min DESC"; break;
        case 'serve_asc': $sql .= " ORDER BY serves_min ASC"; break;
        case 'serve_desc': $sql .= " ORDER BY serves_min DESC"; break;
        default: $sql .= " ORDER BY title ASC"; break;
    }
} else {
    $sql .= " ORDER BY title ASC";
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$recipes = $stmt->fetchAll();
?>

<div class="container">
    <div>
        <!-- Flex justified content -->
        <h2 class="d-flex justify-content-center h2">Search Recipes</h2>
        <br><br>
    </div>

    <!-- Search and Filter Form -->
    <form id="search-form" action="search.php" method="get">
        <!-- Centered and responsive search input -->
        <div class="form-group d-flex justify-content-center">
            <label for="query" class="mr-2" style="white-space: nowrap;">Search:</label>
            <input type="text" id="query" name="query" placeholder="Enter keywords" class="form-control" style="max-width: 300px;">
        </div>

        <!-- Dropdown Filters -->
        <div style="margin-top: 15px;" class="form-row container-fluid">
            <div class="d-flex flex-column">
                <label for="prep_time_range">Prep Time:</label>
                <select name="prep_time_range" id="prep_time_range">
                    <option value="">-- Select Prep Time --</option>
                    <option value="0-30">less than 30 mins</option>
                    <option value="30-60">30 mins to 1 hour</option>
                    <option value="60-120">1 to 2 hours</option>
                    <option value="120-999">more than 2 hours</option>
                </select>
            </div>
            <div class="d-flex flex-column">
                <label for="cook_time_range">Cook Time:</label>
                <select name="cook_time_range" id="cook_time_range">
                    <option value="">-- Select Cook Time --</option>
                    <option value="0-30">less than 30 mins</option>
                    <option value="30-60">30 mins to 1 hour</option>
                    <option value="60-120">1 to 2 hours</option>
                    <option value="120-999">more than 2 hours</option>
                </select>
            </div>
            <div class="d-flex flex-column">
                <label for="serves_min">Serves:</label>
                <select name="serves_min" id="serves_min">
                    <option value="">-- Serves --</option>
                    <option value="2">2+</option>
                    <option value="4">4+</option>
                    <option value="6">6+</option>
                    <option value="8">8+</option>
                </select>
            </div>
            <div class="d-flex flex-column">
                <label for="dietary">Dietary:</label>
                <select name="dietary" id="dietary">
                    <option value="">-- Dietary --</option>
                    <option value="Vegetarian">Vegetarian</option>
                    <option value="Vegan">Vegan</option>
                    <option value="Egg-free">Egg-free</option>
                    <option value="Nut-free">Nut-free</option>
                    <option value="Gluten-free">Gluten-free</option>
                    <option value="Healthy">Healthy</option>
                    <option value="Pregnancy-friendly">Pregnancy-friendly</option>
                </select>
            </div>
            <div class="d-flex flex-column">
                <label for="sort">Sort By:</label>
                <select name="sort" id="sort">
                    <option value="">-- Sort By --</option>
                    <option value="prep_asc">Prep Time (Asc)</option>
                    <option value="prep_desc">Prep Time (Desc)</option>
                    <option value="cook_asc">Cook Time (Asc)</option>
                    <option value="cook_desc">Cook Time (Desc)</option>
                    <option value="serve_asc">Serves (Asc)</option>
                    <option value="serve_desc">Serves (Desc)</option>
                </select>
            </div>
        </div>

        <!-- Search Button -->
        <div style="margin-top: 15px;">
            <button type="submit" class="btn btn-success btn-lg btn-block">Search</button>
        </div>
    </form>

    <hr>

    <!-- Button to List All Recipes -->
    <div class="list-recipes-btn">
        <form action="all_recipes.php" method="get">
            <button type="submit" class="btn btn-primary btn-sm">List All Recipes</button>
        </form>
    </div>

    <hr>

    <?php
    $hasFilters = !empty($_GET['query']) || !empty($_GET['prep_time_range']) || !empty($_GET['cook_time_range']) || !empty($_GET['serves_min']) || !empty($_GET['dietary']) || !empty($_GET['sort']);

    if ($hasFilters && !empty($recipes)) {
        echo "<h3>Search Results:</h3>\n<ul style='padding-left: 2rem; margin-top: 1rem;'>";
        foreach ($recipes as $recipe) {
            echo "<li style='margin-bottom: 0.5rem;'><a href='/recipe-web-app/templates/recipe.php?id=" . $recipe['id'] . "'>" . htmlspecialchars($recipe['title']) . "</a></li>";
        }
        echo "</ul>";
    } elseif ($hasFilters) {
        echo "<p>No recipes matched your search criteria.</p>";
    }
    ?>
</div>

<?php
require_once '../includes/footer.php';
?>