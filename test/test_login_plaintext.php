<?php
// test_login_plaintext.php – Test login with plain text password

require_once '../includes/db.php';

echo "<h2>🔐 Testing plaintext login...</h2>";

// Test credentials (make sure this user exists and uses plaintext password)
$testEmail = 'test@test.com';
$testPassword = '9999';

// Fetch user with matching email
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$testEmail]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<p style='color: red;'>❌ No user found with email <strong>$testEmail</strong></p>";
    exit;
}

// Compare plain text password directly
if ($user['password'] === $testPassword) {
    echo "<p style='color: green;'>✅ Login successful!</p>";
    echo "<ul>";
    echo "<li><strong>ID:</strong> {$user['id']}</li>";
    echo "<li><strong>Name:</strong> " . htmlspecialchars($user['name']) . "</li>";
    echo "<li><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</li>";
    echo "<li><strong>Phone:</strong> " . htmlspecialchars($user['phone'] ?? '') . "</li>";
    echo "<li><strong>Country:</strong> " . htmlspecialchars($user['country'] ?? '') . "</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>❌ Invalid password for <strong>$testEmail</strong></p>";
}
?>
