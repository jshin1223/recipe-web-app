<?php
// includes/header.php - Displays HTML header and navigation bar
session_start(); // Starts a session to track user login and other session data

// Set font class from cookie (default to medium if not set)
$fontClass = 'font-medium'; // default font class
if (isset($_COOKIE['preferredFontSize'])) {
    $safeFont = htmlspecialchars($_COOKIE['preferredFontSize']);
    if (in_array($safeFont, ['small', 'medium', 'large'])) {
        $fontClass = "font-$safeFont";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic meta tags for proper rendering on different devices -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Page title shown in the browser tab -->
    <title style="font-family:georgia,garamond,serif;">Recipe Web App</title>
    
    <!-- Local CSS stylesheet for custom styles -->
    <link rel="stylesheet" href="/recipe-web-app/assets/css/style.css">

    <!-- Bootstrap 4 for responsive design and prebuilt components -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
          crossorigin="anonymous">
    
    <!-- Custom JavaScript file, loaded after HTML parsing (defer) -->
    <script src="/recipe-web-app/assets/js/scripts.js" defer></script>
</head>

<!-- Apply font class to body from cookie -->
<body class="<?php echo $fontClass; ?>">

<header>
    <div class="container-fluid">
        <div class="row">

        <!-- Left Column: Logo and Title -->
        <div class="col-sm">
            <a href="/recipe-web-app/index.php">
                <img 
                    id="theme-logo" 
                    src="/recipe-web-app/assets/images/title_light.png" 
                    alt="Cursive fonts"
                    style="max-height: 60px;"
                >
            </a>
        </div>

            <!-- Middle Column: Navigation Links -->
            <div class="col-sm">
                <nav>
                    <ul>
                        <li class="nav-link"><a href="/recipe-web-app/index.php">Home</a></li>
                        <li class="nav-link"><a href="/recipe-web-app/templates/search.php">Search Recipes</a></li>

                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                   My Account
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a href="/recipe-web-app/templates/account.php" class="dropdown-item">Profile</a>
                                    <a href="/recipe-web-app/includes/auth.php?action=logout" class="dropdown-item">Logout</a>
                                </div>
                            </li>
                        <?php else: ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                                   aria-haspopup="true" aria-expanded="false">
                                   Account
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                    <a href="/recipe-web-app/templates/register.php" class="dropdown-item">Register</a>
                                    <a href="/recipe-web-app/templates/login.php" class="dropdown-item">Login</a>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>

            <!-- Right Column: Theme, Font Size, and Translation Controls -->
            <div class="col-sm">
                <div style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center; justify-content: center; margin-top: 10px;">

                    <!-- Theme Toggle Buttons -->
                    <div style="display: flex; gap: 5px;">
                        <button data-theme="light" class="btn btn-light btn-sm">Light Mode</button>
                        <button data-theme="dark" class="btn btn-secondary btn-sm">Dark Mode</button>
                        <button data-theme="high-contrast" class="btn btn-dark btn-sm" style="border: 2px solid yellow; color: yellow;">High Contrast</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Main container starts here -->
<div class="container">
