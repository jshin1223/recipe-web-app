<?php
// Database connection settings
$host = 'localhost';
$dbname = 'recipe_app';
$username = 'root';
$password = '';

try {
    // Create a new PDO connection to the MySQL database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Set PDO to throw exceptions on errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve all table names in the database
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

    // Initialize an empty array to store schema info
    $schema = [];

    // Loop through each table to get its column names
    foreach ($tables as $table) {
        // Get column names for the current table
        $columns = $pdo->query("SHOW COLUMNS FROM `$table`")->fetchAll(PDO::FETCH_COLUMN);
        
        // Save the column names under the table name
        $schema[$table] = $columns;
    }

    // Save the schema data as a pretty-printed JSON file in the same directory
    file_put_contents(__DIR__ . '/schema_baseline.json', json_encode($schema, JSON_PRETTY_PRINT));

    // Output success message to console or browser
    echo "✅ Schema baseline saved to schema_baseline.json";
} catch (Exception $e) {
    // Handle connection or query errors and print error message
    die("❌ Error generating schema baseline: " . $e->getMessage());
}
