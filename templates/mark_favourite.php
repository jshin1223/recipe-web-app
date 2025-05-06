<?php
require_once '../includes/db.php';
session_start();
header('Content-Type: application/json');

// Check POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    // Ensure user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'User not logged in']);
        exit();
    }

    // Validate recipeId
    if (!isset($data->recipeId)) {
        echo json_encode(['success' => false, 'message' => 'Missing recipeId']);
        exit();
    }

    $userId = $_SESSION['user_id'];  // Use session user ID
    $recipeId = (int) $data->recipeId;

    try {
        $stmtCheck = $pdo->prepare("SELECT * FROM favourites WHERE user_id = ? AND recipe_id = ?");
        $stmtCheck->execute([$userId, $recipeId]);
        $isFavourite = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($isFavourite) {
            $stmt = $pdo->prepare("DELETE FROM favourites WHERE user_id = ? AND recipe_id = ?");
            $stmt->execute([$userId, $recipeId]);
            $action = 'removed';
        } else {
            $stmt = $pdo->prepare("INSERT INTO favourites (user_id, recipe_id) VALUES (?, ?)");
            $stmt->execute([$userId, $recipeId]);
            $action = 'added';
        }

        echo json_encode(['success' => true, 'action' => $action]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
