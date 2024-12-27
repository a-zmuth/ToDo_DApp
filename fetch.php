<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $walletAddress = $_GET['wallet'];

    // Fetch data for the user
    $query = "SELECT * FROM tasks WHERE wallet_address = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $walletAddress);
    $stmt->execute();
    $result = $stmt->get_result();

    $tasks = [];
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }

    echo json_encode($tasks);
}
?>