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
    die(json_encode(['error' => 'Koneksi database gagal: ' . $conn->connect_error]));
}

// Mengambil data dari query parameter
$order_id = $_GET['order_id'] ?? '';

// Menyiapkan query untuk mengambil data pesanan
$sql = "SELECT order_id, status, game, player_id, username, item, total_payment, payment_method, payer_name, whatsapp_number, created_at 
        FROM orders 
        WHERE order_id = ?";

// Menyiapkan statement
$stmt = $conn->prepare($sql);

// Mengecek apakah statement berhasil disiapkan
if ($stmt === false) {
    die(json_encode(['error' => 'Error preparing statement: ' . $conn->error]));
}

// Mengikat parameter ke statement
$stmt->bind_param('s', $order_id);

// Menjalankan statement
$stmt->execute();

// Mengambil hasil query
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Mengambil data sebagai array asosiatif
    $data = $result->fetch_assoc();
    echo json_encode($data);
} else {
    echo json_encode(['error' => 'Pesanan tidak ditemukan.']);
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
?>
