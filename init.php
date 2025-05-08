<?php
// init.php – Creates recipe_app database and seeds it if not already present

$host = 'localhost';
$dbname = 'recipe_app';
$username = 'root';
$password = '';
$sqlFile = __DIR__ . '/sql/recipe_app_dump.sql';

try {
    // Connect to MySQL server without selecting database
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the recipe_app database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    if ($stmt->rowCount() === 0) {
        // Create the database
        $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
        $pdo->exec("USE `$dbname`");

        // Load and execute SQL dump
        if (!file_exists($sqlFile)) {
            throw new Exception("SQL dump not found at $sqlFile");
        }

        $sql = file_get_contents($sqlFile);
        $queries = array_filter(array_map('trim', explode(';', $sql)));

        foreach ($queries as $query) {
            if (!empty($query)) {
                $pdo->exec($query);
            }
        }
    }
} catch (Exception $e) {
    die("❌ init.php error: " . $e->getMessage());
}
