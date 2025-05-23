<?php
// templates/home.php - Home page content
?>

<!-- Static background wrapper with fixed image -->
<div class="home-background" 
     style="background-image: url('/recipe-web-app/assets/images/bgimage.jpg'); 
            background-size: cover; 
            background-position: center; 
            background-repeat: no-repeat; 
            background-attachment: fixed; 
            padding: 2rem; 
            border-radius: 12px;">

    <!-- Theme-aware semi-transparent overlay -->
    <div class="container themed-overlay">
        <div class="d-flex justify-content-center">
            <h2>Welcome<?php if (isset($_SESSION['name'])) echo ", " . htmlspecialchars($_SESSION['name']); ?>!</h2>
        </div>
        <br><br>

        <div class="row">
            <p>Discover delicious recipes, search by ingredients or categories, and save your favourite recipes.</p>
        </div>

        <div class="row">
            <p>Use the navigation menu to register, login, or search recipes.</p>
        </div>
    </div>
</div>
