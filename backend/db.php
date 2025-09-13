<?php
// Database configuration
$host = 'localhost';
$dbname = 'craftnexus';
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}
?>