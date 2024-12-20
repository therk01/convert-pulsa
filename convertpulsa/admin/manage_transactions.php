<?php
session_start();
include '../db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Jika belum login, redirect ke halaman login admin
    exit();
}

// Handle Update Status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $transactionId = $_POST['transaction_id'];
    $newStatus = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE transactions SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $transactionId]);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $transactionId = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ?");
    $stmt->execute([$transactionId]);
}

// Fetch Transactions
$stmt = $pdo->query("SELECT * FROM transactions");
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-roboto">
    <div class="min-h-screen flex">
    <?php include '../admin/includes/sidebar.php';?>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Manage Transactions</h1>

            <!-- Transactions Table -->
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
                        <?php foreach ($transactions as $transaction): ?>
                        <tr class="hover:bg-gray-100">
                        <td class="border px-4 py-2"><?= $transaction['id'] ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($transaction['user_id']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($transaction['provider']) ?></td>
                            <td class="border px-4 py-2">Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($transaction['status']) ?></td>
                            <td class="border px-4 py-2">
                                <form method="POST" class="inline">
                                    <input type="hidden" name="transaction_id" value="<?= $transaction['id'] ?>">
                                    <select name="status" required>
                                        <option value="Pending" <?= $transaction['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Completed" <?= $transaction['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="Cancelled" <?= $transaction['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                    <button type="submit" name="update_status" class="bg-blue-600 text-white px-2 py-1 rounded">Update</button>
                                </form>
                                <a href="?delete=<?= $transaction['id'] ?>" class="text-red-600 ml-4" onclick="return confirm('Are you sure you want to delete this transaction?');">Delete</a>
                            </td>
                            <td class="border px-4 py-2">
    <a href="transaction_detail.php?id=<?= $transaction['id'] ?>" class="text-blue-600">View Details</a>
    <!-- ... -->
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