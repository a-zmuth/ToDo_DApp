<?php
require 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $walletAddress = $_POST['wallet'];
    $task = $_POST['task'];
    $date = date('Y-m-d H:i:s');

    // Insert task into the database
    $query = "INSERT INTO tasks (wallet_address, task, created_at) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $walletAddress, $task, $date);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Task saved"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save task"]);
    }
}
?>

composer.json

{
    "require": {
        "vlucas/phpdotenv": "^5.6"
    }
}
