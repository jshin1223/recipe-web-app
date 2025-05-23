<?php
// init.php – Reset the database only once after XAMPP restart

$host = 'localhost';
$dbname = 'recipe_app';
$username = 'root';
$password = '';
$sqlFile = __DIR__ . '/sql/recipe_app_dump.sql';
$resetFlagFile = __DIR__ . '/reset.flag';

// Only reset the DB if the flag file does not exist
$shouldReset = !file_exists($resetFlagFile);

if (!file_exists($sqlFile)) {
    die("❌ SQL file not found at $sqlFile");
}

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($shouldReset) {
        // Drop and recreate database
        $pdo->exec("DROP DATABASE IF EXISTS `$dbname`");
        $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

        $mysqli = new mysqli($host, $username, $password, $dbname);
        if ($mysqli->connect_error) {
            throw new Exception("MySQLi connection error: " . $mysqli->connect_error);
        }

        $sql = file_get_contents($sqlFile);
        if (!$mysqli->multi_query($sql)) {
            throw new Exception("SQL import failed: " . $mysqli->error);
        }

        do {
            $mysqli->store_result();
        } while ($mysqli->more_results() && $mysqli->next_result());

        // Create the flag file so reset doesn’t run again
        file_put_contents($resetFlagFile, "reset done");
    }

} catch (Exception $e) {
    die("❌ init.php error: " . $e->getMessage());
}
