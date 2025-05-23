<?php
// test/test_form_submission.php — Simulate form POST to update_profile.php and verify persistence
session_start();
$_SESSION['user_id'] = 1;

// Simulate POST
$_SERVER['REQUEST_METHOD'] = 'POST';

$_POST = [
    'name'     => 'Alice',
    'email'    => 'alice@example.com',
    'phone'    => '010-1234-5678',
    'country'  => 'Wonderland',
    'password' => ''
];

define('SUPPRESS_REDIRECT', true);

ob_start();
include '../templates/update_profile.php';
ob_end_clean();


// Now check what was saved in the database
require_once '../includes/db.php';
$stmt = $pdo->prepare("SELECT name, email, phone, country FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Show result
echo "<h2>🧪 Final User Record After Test</h2><ul>";
foreach ($user as $key => $value) {
    echo "<li><strong>$key:</strong> " . htmlspecialchars($value ?? '[NULL]') . "</li>";
}
echo "</ul>";

// Validation
$passed = (
    $user['name'] === 'Alice Test' &&
    $user['email'] === 'alice@example.com' &&
    $user['phone'] === '010-1234-5678' &&
    $user['country'] === 'Wonderland'
);

echo $passed
    ? "<p style='color: green;'>✅ Test passed: All fields updated correctly.</p>"
    : "<p style='color: red;'>❌ Test failed: Some fields were not updated as expected.</p>";
?>
