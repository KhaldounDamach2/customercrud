<?php
require_once __DIR__ . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "<h2>Database Connection: ✅ Success</h2>";
    
    // Create the customers table if it doesn't exist
    $createTableQuery = "
        CREATE TABLE IF NOT EXISTS customers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            phone VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";
    
    try {
        $db->exec($createTableQuery);
        echo "<h2>Table Creation: ✅ Success</h2>";
        echo "<p>The 'customers' table has been created successfully.</p>";
        
        // Check if table exists and show structure
        $stmt = $db->query("DESCRIBE customers");
        if ($stmt) {
            echo "<h3>Table Structure:</h3>";
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . $row['Default'] . "</td>";
                echo "<td>" . $row['Extra'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        echo "<br><br>";
        echo "<a href='index.php' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Go to Customer Management</a>";
        
    } catch (PDOException $e) {
        echo "<h2>Table Creation: ❌ Error</h2>";
        echo "<p>Error creating table: " . $e->getMessage() . "</p>";
    }
    
} else {
    echo "<h2>Database Connection: ❌ Failed</h2>";
    echo "<p>Please check your database configuration and make sure MySQL is running.</p>";
    echo "<p>Make sure the database 'customerdb' exists in your MySQL server.</p>";
}
?>