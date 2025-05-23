<?php
require_once '../includes/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

// Check if form was submitted properly
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['update_error'] = "❌ Invalid request.";
    header("Location: /recipe-web-app/templates/account.php");
    exit();
}

// Sanitize input
$name     = isset($_POST['name']) ? trim($_POST['name']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone    = isset($_POST['phone']) ? trim($_POST['phone']) : null;
$country  = isset($_POST['country']) ? trim($_POST['country']) : null;
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

// Validate required fields
if ($name === '' || $email === '') {
    $_SESSION['update_error'] = "❌ Name and email are required.";
    header("Location: /recipe-web-app/templates/account.php");
    exit();
}

// Check for duplicate email (other users)
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
$stmt->execute([$email, $userId]);
if ($stmt->fetch()) {
    $_SESSION['update_error'] = "⚠️ This email is already used by another account.";
    header("Location: /recipe-web-app/templates/account.php");
    exit();
}

// Build query based on password field
try {
    if ($password !== '') {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, country = ?, password = ? WHERE id = ?");
        $success = $stmt->execute([$name, $email, $phone, $country, $password, $userId]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, country = ? WHERE id = ?");
        $success = $stmt->execute([$name, $email, $phone, $country, $userId]);
    }

    if ($success) {
        $_SESSION['update_success'] = "✅ Profile updated successfully.";
    } else {
        $_SESSION['update_error'] = "❌ Failed to update profile. Please try again.";
    }

} catch (Exception $e) {
    $_SESSION['update_error'] = "❌ Error: " . $e->getMessage();
}

if (!defined('SUPPRESS_REDIRECT')) {
    header("Location: account.php");
    exit();
}

header("Location: /recipe-web-app/templates/account.php");
exit();
