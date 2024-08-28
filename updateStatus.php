<?php
require '../vendor/autoload.php';

use Dotenv\Dotenv;

// Memuat file .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Konfigurasi koneksi database dari .env
$servername = $_ENV['DB_SERVER'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil data dari POST request
$order_id = $_POST['orderId'];
$status = 'Proses'; // Status yang ingin diupdate
$payer_name = $_POST['payerName'];

// Query untuk memperbarui status dan nama pengirim
$sql = "UPDATE orders SET status = ?, payer_name = ? WHERE order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $status, $payer_name, $order_id);

if ($stmt->execute()) {
    echo "Status berhasil diperbarui.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
