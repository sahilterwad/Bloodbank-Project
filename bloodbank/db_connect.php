<?php
// db_connect.php

$host = 'localhost'; // Database server hostname
$dbname = 'bloodbank'; // Name of your database
$username = 'root'; // Database username
$password = ''; // Database password

try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    // Display error message if connection fails
    echo "Database connection failed: " . $e->getMessage();
    exit(); // Stop further execution if connection fails
}
?>
