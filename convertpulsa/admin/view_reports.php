<?php
session_start();
include '../db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Jika belum login, redirect ke halaman login admin
    exit();
}

// Ambil data laporan dari database
$stmt = $pdo->query("SELECT * FROM transactions"); // Ganti dengan query yang sesuai untuk laporan Anda
$reports = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-roboto">
    <div class="min-h-screen flex">
        <?php include 'includes/sidebar.php'; // Menyertakan sidebar admin ?>
        
        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Transaction Reports</h1>

            <!-- Reports Table -->
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">User  ID</th>
                            <th class="border px-4 py-2">Provider</th>
                            <th class="border px-4 py-2">Amount</th>
                            <th class="border px-4 py-2">Status</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border px-4 py-2"><?= htmlspecialchars($report['id']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($report['user_id']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($report['provider']) ?></td>
                            <td class="border px-4 py-2">Rp <?= number_format($report['amount'], 0, ',', '.') ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($report['status']) ?></td>
                            <td class="border px-4 py-2">
                                <a href="transaction_detail.php?id=<?= $report['id'] ?>" class="text-blue-600">View Details</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>