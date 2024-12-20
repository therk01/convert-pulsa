<?php
session_start();
include '../db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Jika belum login, redirect ke halaman login admin
    exit();
}

// Ambil data admin dari database
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();

// Ambil data laporan dari database
$stmt = $pdo->query("SELECT COUNT(*) as total_transactions, SUM(amount) as total_amount FROM transactions");
$reportSummary = $stmt->fetch();
$totalTransactions = $reportSummary['total_transactions'];
$totalAmount = $reportSummary['total_amount'] ?? 0; // Menghindari error jika tidak ada transaksi

// Ambil jumlah pengguna
$stmt = $pdo->query("SELECT COUNT(*) as total_users FROM users");
$userSummary = $stmt->fetch();
$totalUsers = $userSummary['total_users'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
</head>
<body class="bg-gray-100 font-roboto">
    <div class="min-h-screen flex">
        <?php include 'includes/sidebar.php'; // Menyertakan sidebar admin ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Welcome to the Admin Dashboard, <?= htmlspecialchars($admin['name']); ?>!</h1>
            
            <!-- Summary Reports -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Total Transactions</h2>
                    <p class="text-2xl"><?= htmlspecialchars($totalTransactions) ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Total Amount</h2>
                    <p class="text-2xl">Rp <?= number_format($totalAmount, 0, ',', '.') ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-xl font-bold mb-4">Total Users</h2>
                    <p class="text-2xl"><?= htmlspecialchars($totalUsers) ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>