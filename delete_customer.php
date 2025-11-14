<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Customer.php';

$database = new Database();
$db = $database->getConnection();
$customer = new Customer($db);

$customer->id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Missing ID.');

if ($customer->delete()) {
    $_SESSION['success_message'] = "Customer deleted successfully.";
} else {
    $_SESSION['success_message'] = "Unable to delete customer.";
}

header("location: index.php");
exit();
?>