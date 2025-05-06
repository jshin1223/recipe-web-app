<?php
// includes/db.php - Handles database connection using PDO

// Define database configuration settings
$host     = 'localhost'; // Hostname where the database server runs
$dbname   = 'recipe_app'; // Name of the database
$username = 'root'; // Database username (default for XAMPP)
$password = ''; // No password by default in XAMPP

try {
    // Create a PDO data source name string
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    // Create a new PDO instance for database connection
    $pdo = new PDO($dsn, $username, $password);

    // Enable exception mode for better error reporting
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If the connection fails, show a descriptive error
    die("Database connection failed: " . $e->getMessage());
}
?>