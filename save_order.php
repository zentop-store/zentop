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
$order_id = $_POST['order_id'] ?? '';
$game = $_POST['game'] ?? '';
$player_id = $_POST['player_id'] ?? '';
$whatsapp_number = $_POST['whatsapp_number'] ?? '';
$item = $_POST['item'] ?? '';
$total_payment = $_POST['total_payment'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$status = $_POST['status'] ?? '';
$username = $_POST['username'] ?? ''; // Mengambil username
$payer_name = $_POST['payer_name'] ?? ''; // Mengambil payer_name jika ada

// Menyiapkan query untuk menyimpan data ke dalam tabel orders
$sql = "INSERT INTO orders (order_id, game, player_id, whatsapp_number, item, total_payment, payment_method, status, username, payer_name)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Menyiapkan statement
$stmt = $conn->prepare($sql);

// Mengecek apakah statement berhasil disiapkan
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Mengikat parameter ke statement
$stmt->bind_param('ssssssssss', $order_id, $game, $player_id, $whatsapp_number, $item, $total_payment, $payment_method, $status, $username, $payer_name);

// Menjalankan statement
if ($stmt->execute()) {
    echo "Pesanan berhasil disimpan.";
} else {
    echo "Error: " . $stmt->error;
}

// Menutup statement dan koneksi
$stmt->close();
$conn->close();
?>
