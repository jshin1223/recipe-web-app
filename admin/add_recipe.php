<?php
// Include common files
require_once '../includes/header.php';
require_once '../includes/db.php';

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form input
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);

    // Insert new recipe into database
    $stmt = $pdo->prepare("INSERT INTO recipes (title, description) VALUES (?, ?)");
    if ($stmt->execute([$title, $description])) {
        $message = "Recipe added successfully!";
    } else {
        $message = "Error adding recipe.";
    }
}
?>

<div>
    <h2>Add New Recipe</h2>
    <!-- Display success or error message -->
    <?php if(isset($message)) echo "<p>$message</p>"; ?>

    <!-- Form to add recipe -->
    <form action="add_recipe.php" method="post">
        <label for="title">Recipe Title:</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" rows="4" required></textarea>
        <br>
        <!-- Fields for ingredients, steps, etc. can be added -->
        <button type="submit">Add Recipe</button>
    </form>
</div>

<?php
require_once '../includes/footer.php';
?>
