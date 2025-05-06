<?php
// login.php
require_once '../includes/header.php';
?>

<div>
    <h2>Login</h2>
    
    <?php
    // Display error message if any
    if (isset($_GET['error'])) {
        echo "<p style='color:red;'>" . htmlspecialchars($_GET['error']) . "</p>";
    }
    ?>
    
    <form action="../includes/auth.php" method="post">
        <input type="hidden" name="login" value="1">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>
</div>

<?php
require_once '../includes/footer.php';
?>
