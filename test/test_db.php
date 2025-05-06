<?php
// test_db.php – Basic MySQL connection test

// Update these values if different
$host     = 'localhost';          // or 'localhost:3307' if using alternate port
$dbname   = 'recipe_app';         // your actual database name
$username = 'root';               // or your MySQL user
$password = ''; // the password you set for root

try {
    // Set up DSN and create PDO connection
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h2 style='color:green;'>✅ Success! Connected to the database.</h2>";
} catch (PDOException $e) {
    echo "<h2 style='color:red;'>❌ Connection failed:</h2> " . $e->getMessage();
}
?>
