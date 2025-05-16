<?php
require_once '../includes/header.php';
require_once '../includes/db.php';

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Store as plain text or hash it in production

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $error = "⚠️ This email is already registered. Please use a different one.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $email, $password])) {
            $success = "✅ Registration successful! You can now <a href='login.php'>log in</a>.";
        } else {
            $error = "❌ Registration failed. Please try again.";
        }
    }
}
?>

<div>
    <h2>Register</h2>
    <?php if ($error): ?>
        <p style='color:red;'><?= htmlspecialchars($error) ?></p>
    <?php elseif ($success): ?>
        <p style='color:green;'><?= $success ?></p>
    <?php endif; ?>
    
    <form action="register.php" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Register</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
