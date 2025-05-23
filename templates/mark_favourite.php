<?php
// Include database connection
require_once '../includes/db.php';

// Start session to access user authentication data
session_start();

// Set the content type to JSON for the response
header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse JSON input from the request body
    $data = json_decode(file_get_contents("php://input"));

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit();
    }

    // Check if recipeId is provided in the request
    if (!isset($data->recipeId)) {
        echo json_encode(['success' => false, 'message' => 'Missing recipeId']);
        exit();
    }

    // Extract user ID from session and recipe ID from input
    $userId = $_SESSION['user_id'];
    $recipeId = (int) $data->recipeId;

    try {
        // Check if the recipe is already marked as a favourite by this user
        $stmtCheck = $pdo->prepare("SELECT * FROM favourites WHERE user_id = ? AND recipe_id = ?");
        $stmtCheck->execute([$userId, $recipeId]);
        $isFavourite = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($isFavourite) {
            // If already favourite, remove it from the database
            $stmt = $pdo->prepare("DELETE FROM favourites WHERE user_id = ? AND recipe_id = ?");
            $stmt->execute([$userId, $recipeId]);
            $action = 'removed';
        } else {
            // If not favourite yet, add it to the database
            $stmt = $pdo->prepare("INSERT INTO favourites (user_id, recipe_id) VALUES (?, ?)");
            $stmt->execute([$userId, $recipeId]);
            $action = 'added';
        }

        // Send JSON response with success status and action taken
        echo json_encode(['success' => true, 'action' => $action]);
    } catch (Exception $e) {
        // Handle any database errors
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    // Reject any request that is not POST
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
