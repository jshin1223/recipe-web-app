<?php
// Include the header and database connection
require_once '../includes/header.php';
require_once '../includes/db.php';

// Initialize success and error message variables
$success = null;
$error = null;

// Handle the form submission only if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone    = trim($_POST['phone'] ?? '');
    $country  = trim($_POST['country'] ?? '');

    // Check if the email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $error = "⚠️ This email is already registered. Please use a different one.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, country) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt->execute([$name, $email, $password, $phone, $country])) {
            $errorInfo = $stmt->errorInfo();
            $error = "❌ Registration failed: " . htmlspecialchars($errorInfo[2]);
        } else {
            $success = "✅ Registration successful! You can now <a href='login.php'>log in</a>.";
        }
    }
}
?>

<div class="container" style="padding-bottom: 100px;"> <!-- Extra padding for fixed footer -->
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-10 col-12">
            <!-- Page title -->
            <h2 class="text-center mb-4">Register</h2>

            <!-- Feedback message -->
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <!-- Registration form -->
            <form action="register.php" method="post">
                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control">
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" class="form-control">
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-dark register-btn">Register</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Include the footer
require_once '../includes/footer.php';
?>
