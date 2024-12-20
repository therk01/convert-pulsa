<?php
session_start();
include '../db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Jika belum login, redirect ke halaman login admin
    exit();
}

// Cek apakah ID transaksi ada di URL
if (!isset($_GET['id'])) {
    header('Location: manage_transactions.php'); // Jika tidak ada ID, redirect ke halaman manage transactions
    exit();
}

// Ambil data transaksi dari database
$transactionId = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ?");
$stmt->execute([$transactionId]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header('Location: manage_transactions.php'); // Jika transaksi tidak ditemukan, redirect ke halaman manage transactions
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
    <div class="min-h-screen flex">
        <?php include 'includes/sidebar.php'; // Menyertakan sidebar admin ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Detail Transaksi</h1>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p><strong>ID Transaksi:</strong> <?= htmlspecialchars($transaction['id']) ?></p>
                <p><strong>User ID:</strong> <?= htmlspecialchars($transaction['user_id']) ?></p>
                <p><strong>Penyedia:</strong> <?= htmlspecialchars($transaction['provider']) ?></p>
                <p><strong>Jumlah:</strong> Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></p>
                <p><strong>Nomor Telepon Pengirim:</strong> <?= htmlspecialchars($transaction['sender_phone']) ?></p>
                <p><strong>Nomor Telepon Penerima:</strong> <?= htmlspecialchars($transaction['receiver_phone']) ?></p>
                <p><strong>Akun:</strong> <?= htmlspecialchars($transaction['account']) ?></p>
                <p><strong>E-Wallet:</strong> <?= htmlspecialchars($transaction['ewallet']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($transaction['status']) ?></p>
                <div class="mt-4">
                    <strong>Bukti Pembayaran:</strong><br>
                    <img src="<?= htmlspecialchars($transaction['payment_proof']) ?>" alt="Bukti Pembayaran" class="mt-2 max-w-xs rounded-lg">
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="manage_transactions.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Kembali ke Manage Transactions</a>
            </div>
        </div>
    </div>
</body>
</html>