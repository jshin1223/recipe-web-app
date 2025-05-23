<?php
// test/test_db_status.php – Independent DB test with fallback to init

$host = 'localhost';
$dbname = 'recipe_app';
$username = 'root';
$password = '';

// Step 1: Connect to MySQL server (no DB yet)
try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Step 2: Check if database exists
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
    $exists = $stmt->fetch();

    if (!$exists) {
        echo "<p>ℹ️ Database '$dbname' not found. Running init.php to create it...</p>";
        require_once __DIR__ . '/../init.php';
    }

    // Step 3: Connect to the recipe_app database now
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Step 4: List tables
    echo "<h2>✅ Database Connection Test</h2>";
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "<p style='color:red;'>⚠️ No tables found in the 'recipe_app' database.</p>";
    } else {
        echo "<p style='color:green;'>✅ Found the following tables:</p><ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";

        // Optional: Check for users
        if (in_array('users', $tables)) {
            $userCheck = $pdo->query("SELECT COUNT(*) FROM users");
            $count = $userCheck->fetchColumn();
            echo "<p>👥 Users table has <strong>$count</strong> rows.</p>";
        }
    }

} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}
