<?php
require_once '../vendor/autoload.php'; // Update dengan path ke autoload.php

use Twilio\Rest\Client;
use Dotenv\Dotenv;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memuat variabel dari file .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// SID dan token Twilio Anda
$sid    = $_ENV['TWILIO_SID'];
$token  = $_ENV['TWILIO_TOKEN'];
$twilio = new Client($sid, $token);

// Nomor pengirim dan penerima
$from_number = $_ENV['TWILIO_FROM'];
$to_number   = $_ENV['TWILIO_TO'];

// Ambil data POST dari JavaScript
if (isset($_POST['orderDetails'])) {
    $orderDetails = $_POST['orderDetails'];
    $message_body = "Rincian Pesanan:\n" . $orderDetails;

    try {
        $message = $twilio->messages->create(
            $to_number,
            array(
                "from" => $from_number,
                "body" => $message_body
            )
        );
        echo "Message sent! SID: " . $message->sid;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "No order details provided.";
}
?>
