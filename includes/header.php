<?php
// includes/header.php - Displays HTML header and navigation bar
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Web App</title>
    <link rel="stylesheet" href="/recipe-web-app/assets/css/style.css">
    <script src="/recipe-web-app/assets/js/scripts.js" defer></script>
</head>
<body>
    <header>
        <h1>Recipe Web App</h1>
        <nav>
            <ul>
                <li><a href="/recipe-web-app/index.php">Home</a></li>
                <li><a href="/recipe-web-app/templates/search.php">Search Recipes</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="/recipe-web-app/templates/account.php">My Account</a></li>
                    <li><a href="/recipe-web-app/includes/auth.php?action=logout">Logout</a></li>
                <?php else: ?>
                    <li><a href="/recipe-web-app/templates/register.php">Register</a></li>
                    <li><a href="/recipe-web-app/templates/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div style="margin-top: 10px; text-align: center;">
            <button data-theme="light">Light Mode</button>
            <button data-theme="dark">Dark Mode</button>
            <button data-theme="high-contrast">High Contrast</button>
        </div>

    </header>
    <div class="container">