<?php

require '../vendor/autoload.php'; // Path ini disesuaikan

use Aditdev\ApiGames;

header('Content-Type: application/json');

$playerid = isset($_GET['username']) ? $_GET['username'] : '';
$zoneid = isset($_GET['zoneid']) ? $_GET['zoneid'] : '';

if (empty($playerid)) {
    echo json_encode(['error' => 'Player ID is required']);
    exit;
}

$api = new ApiGames();

try {
    $result = $api->MOBILE_LEGENDS($playerid, $zoneid);
    $resultArray = json_decode($result, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        echo json_encode($resultArray);
    } else {
        echo json_encode(['error' => 'Invalid JSON response from API']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
