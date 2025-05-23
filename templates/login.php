<?php
// login.php - Login form for the Recipe Web App

// Include the HTML header, navigation, and CSS/JS
require_once '../includes/header.php';
?>

<div class="container" style="padding-bottom: 100px;"> <!-- Ensures visibility above fixed footer -->
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-10 col-12">
            <!-- Page heading -->
            <h2 class="text-center mb-4">Login</h2>

            <?php
            // Display an error message if 'error' parameter is passed via URL (e.g., from failed login)
            if (isset($_GET['error'])) {
                echo "<p style='color:red;' class='text-center'>" . htmlspecialchars($_GET['error']) . "</p>";
            }
            ?>

            <!-- Login form -->
            <form action="../includes/auth.php" method="post">
                <!-- Hidden input to mark this as a login request -->
                <input type="hidden" name="login" value="1">

                <!-- Email field -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <!-- Password field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <!-- Submit button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-dark login-btn">Login</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include the footer
require_once '../includes/footer.php';
?>
