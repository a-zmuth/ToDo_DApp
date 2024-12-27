<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'todo_dapp'; 

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set character set to UTF-8 for better compatibility
$conn->set_charset("utf8");
?>