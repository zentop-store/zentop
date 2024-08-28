<?php
require '../vendor/autoload.php';
use GuzzleHttp\Client;
use Twilio\Rest\Client as TwilioClient;
use Dotenv\Dotenv;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Memuat variabel dari file .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$client = new Client();
$twilioSid = $_ENV['TWILIO_SID'];
$twilioToken = $_ENV['TWILIO_TOKEN'];
$twilioFrom = $_ENV['TWILIO_FROM'];
$twilioTo = $_ENV['TWILIO_TO'];

if (isset($_FILES['paymentScreenshot']) && isset($_POST['orderId']) && isset($_POST['totalPayment']) && isset($_POST['selectedPaymentMethod']) && isset($_POST['payerName'])) {
    $orderId = $_POST['orderId'];
    $totalPayment = $_POST['totalPayment'];
    $selectedPaymentMethod = $_POST['selectedPaymentMethod'];
    $payerName = $_POST['payerName'];

    $file = $_FILES['paymentScreenshot'];
    $filename = $file['name'];
    $fileData = file_get_contents($file['tmp_name']);
    $fileEncoded = base64_encode($fileData);
    $filePath = "uploads/$filename";

    $githubToken = $_ENV['GITHUB_TOKEN'];
    $repoOwner = $_ENV['REPO_OWNER'];
    $repoName = $_ENV['REPO_NAME'];
    $branch = $_ENV['BRANCH'];

    try {
        // Upload to GitHub
        $response = $client->request('PUT', "https://api.github.com/repos/$repoOwner/$repoName/contents/$filePath", [
            'headers' => [
                'Authorization' => "token $githubToken",
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'message' => "Upload bukti pembayaran: $filename",
                'content' => $fileEncoded,
                'branch' => $branch
            ]
        ]);

        $responseBody = json_decode($response->getBody(), true);
        $fileUrl = $responseBody['content']['download_url'];

        // Send WhatsApp message
        $twilio = new TwilioClient($twilioSid, $twilioToken);
        $message = "Order ID: $orderId\nTotal Payment: Rp $totalPayment\nPayment Method: $selectedPaymentMethod\nPayer Name: $payerName telah melakukan transaksi ✅ tolong cek untuk memastikan sudah masuk atau belum!";
        $twilio->messages->create($twilioTo, [
            'from' => $twilioFrom,
            'body' => $message,
            'mediaUrl' => [$fileUrl]
        ]);

        echo 'Bukti pembayaran berhasil dikirim!';
    } catch (Exception $e) {
        error_log("Error uploading to GitHub or sending WhatsApp message: " . $e->getMessage());
        echo 'Terjadi kesalahan saat mengunggah bukti pembayaran atau mengirim pesan WhatsApp.';
    }
} else {
    error_log("No order details, order ID, total payment, payment method, or screenshot provided");
    echo 'Harap unggah bukti pembayaran dan isi nama pengirim.';
}
