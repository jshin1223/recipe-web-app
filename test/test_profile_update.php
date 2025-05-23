<?php
session_start();
require_once '../includes/db.php';

echo "<h2>🔧 Testing Profile Update</h2>";

// Simulate logged-in user (change to match an existing user in your DB)
$_SESSION['user_id'] = 1;

// Test data to update
$testData = [
    'name' => 'Alice',
    'email' => 'alice@example.com',
    'phone' => '010-1234-5678',
    'country' => 'South Korea',
    'password' => ''  // Leave blank to simulate no password change
];

$userId = $_SESSION['user_id'];

// Check for duplicate email (optional logic)
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->execute([$testData['email'], $userId]);
if ($stmt->fetch()) {
    die("❌ Email already in use by another user.");
}

// Perform update
try {
    if (!empty($testData['password'])) {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, country = ?, password = ? WHERE id = ?");
        $stmt->execute([$testData['name'], $testData['email'], $testData['phone'], $testData['country'], $testData['password'], $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, country = ? WHERE id = ?");
        $stmt->execute([$testData['name'], $testData['email'], $testData['phone'], $testData['country'], $userId]);
    }

    echo "<p>✅ Profile updated successfully!</p>";

    // Fetch updated data
    $stmt = $pdo->prepare("SELECT name, email, phone, country FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<h4>🔍 Updated User Info:</h4><ul>";
    foreach ($user as $key => $value) {
        echo "<li><strong>" . htmlspecialchars($key) . ":</strong> " . htmlspecialchars($value) . "</li>";
    }
    echo "</ul>";

} catch (Exception $e) {
    echo "<p>❌ Update failed: " . htmlspecialchars($e->getMessage()) . "</p>";
}
