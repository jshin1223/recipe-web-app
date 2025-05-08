<?php
// test/test_db.php – Verifies database setup and connection

require_once __DIR__ . '/../includes/db.php';

echo "<h2>✅ Database Connection Test</h2>";

try {
    // Simple test query: list tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($tables)) {
        echo "<p style='color:red;'>⚠️ No tables found in the database.</p>";
    } else {
        echo "<p style='color:green;'>✅ Connected to 'recipe_app' and found the following tables:</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Query failed: " . $e->getMessage() . "</p>";
}
?>
