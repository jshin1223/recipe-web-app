<?php
// test/test_db.php – Verifies database setup and connection

// Include the database connection script
require_once __DIR__ . '/../includes/db.php';

// Display a heading for the test
echo "<h2>✅ Database Connection Test</h2>";

try {
    // Run a simple query to get a list of all tables in the connected database
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Check if any tables exist
    if (empty($tables)) {
        // Warn if the database is empty
        echo "<p style='color:red;'>⚠️ No tables found in the database.</p>";
    } else {
        // Output the list of tables found in the database
        echo "<p style='color:green;'>✅ Connected to 'recipe_app' and found the following tables:</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }
} catch (PDOException $e) {
    // Handle query errors gracefully
    echo "<p style='color:red;'>❌ Query failed: " . $e->getMessage() . "</p>";
}
?>
