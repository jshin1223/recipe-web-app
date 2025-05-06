<?php
// Include common components
require_once '../includes/header.php';
require_once '../includes/db.php';

// Get recipe ID from the query string
$recipeId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validate ID
if ($recipeId <= 0) {
    echo "<p>Invalid Recipe ID.</p>";
    exit();
}

// Fetch existing recipe from the database
$stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
$stmt->execute([$recipeId]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

// If recipe doesn't exist
if (!$recipe) {
    echo "<p>Recipe not found.</p>";
    exit();
}

// If form is submitted to update the recipe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Update the database
    $stmt = $pdo->prepare("UPDATE recipes SET title = ?, description = ? WHERE id = ?");
    if ($stmt->execute([$title, $description, $recipeId])) {
        $message = "Recipe updated successfully!";
    } else {
        $message = "Failed to update recipe.";
    }

    // Refresh data from database
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = ?");
    $stmt->execute([$recipeId]);
    $recipe = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div>
    <h2>Edit Recipe</h2>
    <!-- Show result message -->
    <?php if(isset($message)) echo "<p>$message</p>"; ?>

    <!-- Edit form -->
    <form action="edit_recipe.php?id=<?php echo $recipeId; ?>" method="post">
        <label for="title">Recipe Title:</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($recipe['title']); ?>" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($recipe['description']); ?></textarea>
        <br>
        <!-- More editable fields can be added here -->
        <button type="submit">Update Recipe</button>
    </form>
</div>

<?php
require_once '../includes/footer.php';
?>
