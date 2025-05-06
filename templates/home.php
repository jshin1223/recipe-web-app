<?php
// templates/home.php - Home page content
?>
<div>
    <h2>Welcome<?php if(isset($_SESSION['name'])) echo ", " . htmlspecialchars($_SESSION['name']); ?>!</h2>
    <p>Discover delicious recipes, search by ingredients or categories, and save your favourite recipes.</p>
    <p>Use the navigation menu to register, login, or search recipes.</p>
</div>