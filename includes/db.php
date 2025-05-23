<?php
// db.php – Connects to MySQL and resets the 'recipe_app' database on every run

$host = 'localhost';
$dbname = 'recipe_app';
$username = 'root';
$password = '';

try {
    // Step 1: Run init.php to drop, create, and import the database
    require_once __DIR__ . '/../init.php';

    // Step 2: Reconnect to the newly created recipe_app database using PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("❌ DB connection failed: " . $e->getMessage());
}
