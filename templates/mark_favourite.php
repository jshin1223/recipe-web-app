<?php
require_once '../includes/db.php'; // Include your database connection
session_start(); // Ensure session is started

// Ensure that the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents("php://input"));

    // Validate that the data contains the userId and recipeId
    if (isset($data->userId) && isset($data->recipeId)) {
        $userId = $data->userId;
        $recipeId = $data->recipeId;

        try {
            // Check if the user has already marked the recipe as a favourite
            $stmtCheck = $pdo->prepare("SELECT * FROM favourites WHERE user_id = ? AND recipe_id = ?");
            $stmtCheck->execute([$userId, $recipeId]);
            $isFavourite = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            // If the recipe is already a favourite, remove it
            if ($isFavourite) {
                // Delete the favourite record from the database
                $stmt = $pdo->prepare("DELETE FROM favourites WHERE user_id = ? AND recipe_id = ?");
                $stmt->execute([$userId, $recipeId]);
                $action = 'removed';
            } else {
                // If the recipe is not a favourite, add it
                $stmt = $pdo->prepare("INSERT INTO favourites (user_id, recipe_id) VALUES (?, ?)");
                $stmt->execute([$userId, $recipeId]);
                $action = 'added';
            }

            // Return a successful response with the action (added or removed)
            echo json_encode(['success' => true, 'action' => $action]);
        } catch (Exception $e) {
            // Log and return the error message if there is a database issue
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        // If the required data (userId or recipeId) is missing, return an error
        echo json_encode(['success' => false, 'message' => 'Missing userId or recipeId']);
    }
} else {
    // If the request method is not POST, return an error
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
