<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "<div class='alert alert-success'>✅ Database connected successfully!</div>";
    
    // Test if table exists
    $query = "SHOW TABLES LIKE 'customers'";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "<div class='alert alert-success'>✅ Customers table exists!</div>";
    } else {
        echo "<div class='alert alert-danger'>❌ Customers table not found!</div>";
    }
} else {
    echo "<div class='alert alert-danger'>❌ Database connection failed!</div>";
}
?>