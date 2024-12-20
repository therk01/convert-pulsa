<?php
session_start();
include 'db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Jika belum login, redirect ke halaman login
    exit();
}

// Cek apakah ID transaksi ada di URL
if (!isset($_GET['id'])) {
    header('Location: status.php'); // Jika tidak ada ID, redirect ke halaman status
    exit();
}

// Ambil ID transaksi dari URL
$transactionId = $_GET['id'];

// Ambil data transaksi dari database
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$transactionId, $_SESSION['user_id']]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header('Location: status.php'); // Jika transaksi tidak ditemukan, redirect ke halaman status
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-roboto">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Detail Transaksi</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4">Informasi Transaksi</h2>
            <p><strong>ID Transaksi:</strong> <?= htmlspecialchars($transaction['id']) ?></p>
            <p><strong>Penyedia:</strong> <?= htmlspecialchars($transaction['provider']) ?></p>
            <p><strong>Jumlah Pulsa:</strong> Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></p>
            <p><strong>Nomor Pengirim:</strong> <?= htmlspecialchars($transaction['sender_phone']) ?></p>
            <p><strong>Nomor Penerima:</strong> <?= htmlspecialchars($transaction['receiver_phone']) ?></p>
            <p><strong>Akun:</strong> <?= htmlspecialchars($transaction['account']) ?></p>
            <p><strong>E-Wallet:</strong> <?= htmlspecialchars($transaction['ewallet']) ?></p>
            <p><strong>Rate:</strong> <?= htmlspecialchars($transaction['rate']) ?></p>
            <p><strong>Hasil Konversi:</strong> Rp <?= number_format($transaction['result'], 0, ',', '.') ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($transaction['status']) ?></p>
            <p><strong>Bukti Pembayaran:</strong></p>
            <img src="<?= htmlspecialchars($transaction['payment_proof']) ?>" alt="Bukti Pembayaran" class="mt-4 max-w-xs rounded-lg shadow-md">
        </div>

        <div class="mt-6">
            <a href="status.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Kembali ke Status Transaksi</a>
        </div>
    </div>
</body>
</html>