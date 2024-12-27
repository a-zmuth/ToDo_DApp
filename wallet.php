<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require 'db_connect.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read the JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if wallet and username are set
    if (!isset($data['wallet']) || !isset($data['username'])) {
        echo json_encode(["status" => "error", "message" => "Missing wallet or username"]);
        exit;
    } else {
        error_log('Request method: ' . $_SERVER['REQUEST_METHOD']);
    }

    $walletAddress = $data['wallet'];
    $username = $data['username'];

    // Prepare statement to check if wallet already exists
    $query = "SELECT * FROM users WHERE wallet_address = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $walletAddress);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Wallet already registered"]);
    } else {
        // Insert into database
        $insertQuery = "INSERT INTO users (wallet_address, username) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param('ss', $walletAddress, $username);
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Wallet connected"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error"]);
        }
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Close the database connection
$conn->close();
?>