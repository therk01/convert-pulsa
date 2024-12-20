<?php
session_start();
include 'db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Jika belum login, redirect ke halaman login
    exit();
}

// Ambil data transaksi pengguna dari database
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ?");
$stmt->execute([$userId]);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-roboto">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-2xl font-bold text-blue-600" href="dashboard.php">PulsaConvert</a>
            <ul class="flex space-x-6">
                <li><a href="dashboard.php" class="text-gray-700 hover:text-blue-600">Home</a></li>
                <li><a href="logout.php" class="text-gray-700 hover:text-blue-600">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-center">Status Transaksi Anda</h1>
        <div class="overflow-x-auto bg-white rounded-lg shadow-md">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border px-4 py-2">ID</th>
                        <th class="border px-4 py-2">Provider</th>
                        <th class="border px-4 py-2">Jumlah</th>
                        <th class="border px-4 py-2">Status</th>
                        <th class="border px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                    <tr class="hover:bg-gray-100">
                        <td class="border px-4 py-2"><?= htmlspecialchars($transaction['id']) ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($transaction['provider']) ?></td>
                        <td class="border px-4 py-2">Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
                        <td class="border px-4 py-2"><?= htmlspecialchars($transaction['status']) ?></td>
                        <td class="border px-4 py-2">
                            <a href="transaction_detail.php?id=<?= $transaction['id'] ?>" class="text-blue-600">Lihat Detail</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>