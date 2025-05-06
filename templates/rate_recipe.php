<?php
session_start();
require_once '../includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /recipe-web-app/templates/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from the form
    $recipe_id = isset($_POST['recipe_id']) ? intval($_POST['recipe_id']) : 0;
    $difficulty = isset($_POST['difficulty']) ? intval($_POST['difficulty']) : 0;
    $aesthetics = isset($_POST['aesthetics']) ? intval($_POST['aesthetics']) : 0;
    $taste = isset($_POST['taste']) ? intval($_POST['taste']) : 0;
    $overall = isset($_POST['overall']) ? intval($_POST['overall']) : 0;

    if ($recipe_id > 0 && $difficulty && $aesthetics && $taste && $overall) {
        // Check if the user has already rated this recipe
        $stmt = $pdo->prepare("SELECT * FROM ratings WHERE user_id = ? AND recipe_id = ?");
        $stmt->execute([$_SESSION['user_id'], $recipe_id]);
        $existingRating = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingRating) {
            // Update the existing rating
            $stmt = $pdo->prepare("UPDATE ratings SET difficulty = ?, aesthetics = ?, taste = ?, overall = ? WHERE user_id = ? AND recipe_id = ?");
            $stmt->execute([$difficulty, $aesthetics, $taste, $overall, $_SESSION['user_id'], $recipe_id]);
        } else {
            // Insert a new rating
            $stmt = $pdo->prepare("INSERT INTO ratings (user_id, recipe_id, difficulty, aesthetics, taste, overall) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $recipe_id, $difficulty, $aesthetics, $taste, $overall]);
        }

        // Redirect back to the recipe page with a success message
        header("Location: /recipe-web-app/templates/recipe.php?id=$recipe_id&message=Rating submitted successfully!");
        exit();
    } else {
        // If some rating data is invalid, redirect back with an error message
        header("Location: /recipe-web-app/templates/recipe.php?id=$recipe_id&error=Please provide valid ratings.");
        exit();
    }
}
?>
