<?php
session_start();
include '../db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Jika belum login, redirect ke halaman login admin
    exit();
}

// Ambil ID transaksi dari URL
$transactionId = $_GET['id'] ?? null;

if ($transactionId) {
    // Ambil detail transaksi dari database
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE id = ?");
    $stmt->execute([$transactionId]);
    $transaction = $stmt->fetch();

    if (!$transaction) {
        echo "Transaction not found.";
        exit();
    }
} else {
    echo "No transaction ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Detail</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-roboto">
    <div class="min-h-screen flex">
        <?php include '../admin/includes/sidebar.php'; ?>
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Transaction Detail</h1>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Transaction Information</h2>
                <p><strong>ID:</strong> <?= htmlspecialchars($transaction['id']) ?></p>
                <p><strong>User ID:</strong> <?= htmlspecialchars($transaction['user_id']) ?></p>
                <p><strong>Provider:</strong> <?= htmlspecialchars($transaction['provider']) ?></p>
                <p><strong>Amount:</strong> Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></p>
                <p><strong>Sender's Phone:</strong> <?= htmlspecialchars($transaction['sender_phone']) ?></p>
                <p><strong>Receiver's Phone:</strong> <?= htmlspecialchars($transaction['receiver_phone']) ?></p>
                <p><strong>Bank Account:</strong> <?= htmlspecialchars($transaction['account']) ?></p>
                <p><strong>E-Wallet:</strong> <?= htmlspecialchars($transaction['ewallet']) ?></p>
                <p><strong>Rate:</strong> <?= htmlspecialchars($transaction['rate']) ?></p>
                <p><strong>Result:</strong> Rp <?= number_format($transaction['result'], 0, ',', '.') ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($transaction['status']) ?></p>
                <div class="mt-4">
                    <h4 class="text-lg font-bold">Payment Proof:</h4>
                    <img src="<?= htmlspecialchars($transaction['payment_proof']); ?>" alt="Payment Proof" class="mt-2 max-w-full h-auto rounded-lg shadow-md">
                    
                </div>
            </div>
            <div class="mt-6">
                <a href="manage_transactions.php" class="bg-blue-600 text-white px-4 py-2 rounded">Back to Transactions</a>
            </div>
        </div>
    </div>
</body>
</html>