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
$order_id = $_POST['orderId'] ?? ''; // Default ke string kosong jika orderId tidak ada

// Query untuk mengambil status order
if ($order_id) {
    $sql = "SELECT status FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $order_id);
    $stmt->execute();
    $stmt->bind_result($status);
    $stmt->fetch();
    
    // Mengembalikan status sebagai JSON
    echo json_encode(['status' => $status ?: 'Tidak tersedia']);
    
    $stmt->close();
} else {
    // Jika orderId tidak ada
    echo json_encode(['status' => 'Tidak tersedia']);
}

$conn->close();
?>
