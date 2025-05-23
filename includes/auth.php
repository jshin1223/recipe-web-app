<?php
session_start();
require_once 'db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action === 'logout') {
    // Destroy session and redirect to home page
    session_destroy();
    header('Location: ../index.php');
    exit();
}

// For login processing, expect a POST submission from login.php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Look up user in database
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && trim($user['password']) === trim($password)){ // Direct comparison for plain text passwords
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        header('Location: ../index.php');
        exit();
    } else {
        // If the login fails, redirect back with an error message
        $error = "Invalid email or password.";
        header("Location: ../templates/login.php?error=" . urlencode($error));
        exit();
    }
}
?>
