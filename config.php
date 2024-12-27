<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Load environment variables
require 'vendor/autoload.php'; 
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Retrieve other environment variables
$ganacheUrl = $_ENV['GANACHE_URL'];
$contractAddress = $_ENV['CONTRACT_ADDRESS'];

// Load ABI from JSON file
$abiFile = __DIR__ . 'ToDo.ABI.json';
if (!file_exists($abiFile)) {
    echo json_encode(['error' => 'ABI file not found']);
    exit;
}

$abi = json_decode(file_get_contents($abiFile), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Invalid ABI format: ' . json_last_error_msg()]);
    exit;
}

// Prepare the response
$config = [
    'ganacheUrl' => $ganacheUrl,
    'contractAddress' => $contractAddress,
    'abi' => $abi,
];

echo json_encode($config);
exit;