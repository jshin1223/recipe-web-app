<?php
// Include the common header and database connection
require_once '../includes/header.php';
require_once '../includes/db.php';

// Ensure the user is logged in; otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: /recipe-web-app/templates/login.php');
    exit();
}

// Retrieve user details from the database based on the session user ID
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Retrieve all recipes marked as favourites by the user
$stmtFavourites = $pdo->prepare("SELECT recipes.id, recipes.title FROM recipes 
                                 JOIN favourites ON recipes.id = favourites.recipe_id 
                                 WHERE favourites.user_id = ?");
$stmtFavourites->execute([$_SESSION['user_id']]);
$favourites = $stmtFavourites->fetchAll(PDO::FETCH_ASSOC);

// Get the user's average ratings for difficulty, aesthetics, taste, and overall
$stmtRatings = $pdo->prepare("SELECT AVG(difficulty) AS avg_difficulty, 
                                      AVG(aesthetics) AS avg_aesthetics,
                                      AVG(taste) AS avg_taste, 
                                      AVG(overall) AS avg_overall
                               FROM ratings 
                               WHERE user_id = ?");
$stmtRatings->execute([$_SESSION['user_id']]);
$ratings = $stmtRatings->fetch(PDO::FETCH_ASSOC);
?>

<div class="container">
    <h2>My Account</h2>

    <!-- Display success or error messages after profile update -->
    <?php if (isset($_SESSION['update_success'])): ?>
        <p style="color: green;"><?php echo $_SESSION['update_success']; unset($_SESSION['update_success']); ?></p>
    <?php elseif (isset($_SESSION['update_error'])): ?>
        <p style="color: red;"><?php echo $_SESSION['update_error']; unset($_SESSION['update_error']); ?></p>
    <?php endif; ?>

    <!-- Show user details in a table format -->
    <table class="recipe-table">
        <tr><th>Name:</th><td><?= htmlspecialchars($user['name']) ?></td></tr>
        <tr><th>Email:</th><td><?= htmlspecialchars($user['email']) ?></td></tr>
        <tr><th>Phone:</th><td><?= htmlspecialchars($user['phone'] ?? '') ?></td></tr>
        <tr><th>Country:</th><td><?= htmlspecialchars($user['country'] ?? '') ?></td></tr>
    </table><br><br>

    <!-- Button to show/hide the edit profile form -->
    <button id="toggleEditBtn" class="btn btn-secondary">Edit Profile</button>

    <!-- Editable profile form for updating user info -->
    <div id="editFormContainer" style="display: none; margin-top: 20px;">
        <form action="update_profile.php" method="post">
            <table class="recipe-table">
                <tr>
                    <th><label for="name">Name:</label></th>
                    <td><input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="email">Email:</label></th>
                    <td><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required></td>
                </tr>
                <tr>
                    <th><label for="phone">Phone:</label></th>
                    <td><input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="country">Country:ㅤ</label></th>
                    <td><input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>"></td>
                </tr>
                <tr>
                    <th><label for="password">New Password:</label></th>
                    <td><input type="password" id="password" name="password" placeholder="Leave blank to keep current"></td>
                </tr>
            </table>
            <br>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <hr>

    <!-- Display list of user's favourite recipes -->
    <h3>My Favourite Recipes</h3>
    <table class="recipe-table">
        <thead>
            <tr><th>Recipe Titleㅤㅤ</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($favourites as $favourite): ?>
                <tr>
                    <td>
                        <!-- Link to view the full recipe -->
                        <a href="/recipe-web-app/templates/recipe.php?id=<?php echo $favourite['id']; ?>">
                            <?php echo htmlspecialchars($favourite['title']); ?>
                        </a>
                    </td>
                    <td>
                        <!-- Button to remove from favourites -->
                        <button class="favourite-btn" style="font-size: 0.8em; padding: 4px 9px;"
                            data-recipe-id="<?php echo $favourite['id']; ?>"
                            data-user-id="<?php echo $_SESSION['user_id']; ?>">
                            Remove from Favourites
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <hr>

    <!-- Display user's average ratings across categories -->
    <h3>My Average Ratings</h3>
    <table class="recipe-table">
        <tr><td><strong>Difficulty:</strong></td><td><?php echo round($ratings['avg_difficulty'], 2) . '/5'; ?></td></tr>
        <tr><td><strong>Aesthetics:</strong></td><td><?php echo round($ratings['avg_aesthetics'], 2) . '/5'; ?></td></tr>
        <tr><td><strong>Taste:</strong></td><td><?php echo round($ratings['avg_taste'], 2) . '/5'; ?></td></tr>
        <tr><td><strong>Overall Rating:</strong></td><td><?php echo round($ratings['avg_overall'], 2) . '/5'; ?></td></tr>
    </table>
</div>

<?php
// Include the common footer
require_once '../includes/footer.php';
?>
