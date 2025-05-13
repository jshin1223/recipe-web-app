<?php
// init.php – Creates recipe_app database and seeds it if not already present

$host = 'localhost';
$dbname = 'recipe_app';
$username = 'root';
$password = '';
$sqlFile = __DIR__ . '/sql/recipe_app_dump.sql';

try {
    // 1. Connect to MySQL server without selecting database
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Create the database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE `$dbname`");

    // 3. Check if the 'recipes' table exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$dbname' AND table_name = 'recipes'");
    $tableExists = $stmt->fetchColumn();

    if (!$tableExists) {
        // 4. Load and execute SQL dump
        if (!file_exists($sqlFile)) {
            throw new Exception("SQL dump not found at $sqlFile");
        }

        $sql = file_get_contents($sqlFile);

        // Use mysqli to handle session variables like @recipe_id
        $mysqli = new mysqli($host, $username, $password, $dbname);
        if ($mysqli->connect_error) {
            throw new Exception("MySQLi connection failed: " . $mysqli->connect_error);
        }

        if (!$mysqli->multi_query($sql)) {
            throw new Exception("Multi-query execution failed: " . $mysqli->error);
        }

        // Clear remaining results to complete multi_query
        do {
            $mysqli->store_result();
        } while ($mysqli->more_results() && $mysqli->next_result());
    }
} catch (Exception $e) {
    die("❌ init.php error: " . $e->getMessage());
}
