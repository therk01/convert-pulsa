<?php
session_start();
include '../db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Jika belum login, redirect ke halaman login admin
    exit();
}
// Handle Update Rate
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $provider = $_POST['provider'];
    $rate = $_POST['rate'];

    // Update hanya rate tanpa mengubah nomor handphone
    $stmt = $pdo->prepare("UPDATE rates SET rate = ? WHERE provider = ?");
    $stmt->execute([$rate, $provider]);
}
// Handle Delete
if (isset($_GET['delete'])) {
    $provider = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM rates WHERE provider = ?");
    $stmt->execute([$provider]);
}
// Handle Add Provider
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $provider = $_POST['new_provider'];
    $rate = $_POST['new_rate'];
    $phone = $_POST['new_phone'];

    $stmt = $pdo->prepare("INSERT INTO rates (provider, rate, phone) VALUES (?, ?, ?)");
    $stmt->execute([$provider, $rate, $phone]);
}

// Fetch Rates
$stmt = $pdo->query("SELECT * FROM rates");
$rates = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rates</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-roboto">
    <div class="min-h-screen flex">
    <?php include '../admin/includes/sidebar.php';?>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Manage Rates</h1>

            <!-- Rates Table -->
            <div class="overflow-x-auto bg-white rounded-lg shadow-md mb-6">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">Provider</th>
                            <th class="border px-4 py-2">Rate</th>
                            <th class="border px-4 py-2">Phone</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rates as $rate): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border px-4 py-2"><?= htmlspecialchars($rate['provider']) ?></td>
                            <td class="border px-4 py-2">
                                <form method="POST" class="flex items-center">
                                    <input type="hidden" name="provider" value="<?= htmlspecialchars($rate['provider']) ?>">
                                    <input type="number" name="rate" value="<?= htmlspecialchars($rate['rate']) ?>" class="border rounded px-2 py-1 w-24" step="0.01" required>
                                    <span class="border rounded px-2 py-1 w-24 ml-2"><?= htmlspecialchars($rate['phone']) ?></span>
                                    <button type="submit" name="update" class="bg-blue-600 text-white px-4 py-1 rounded ml-2">Update</button>
                                </form>
                            </td>
                            <td class="border px-4 py-2">
                                <a href="?delete=<?= urlencode($rate['provider']) ?>" class="text-red-600" onclick="return confirm('Are you sure you want to delete this rate?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Add New Provider Form -->
            <h2 class="text-xl font-bold mb-4">Add New Provider</h2>
            <form method="POST" class="bg-white p-4 rounded shadow-md">
                <input type="text" name="new_provider" placeholder="Provider Name" required class="border p-2 mb-2 w-full rounded">
                <input type="number" name="new_rate" placeholder="Rate" required class="border p-2 mb-2 w-full rounded" step="0.01">
                <input type="text" name="new_phone" placeholder="Phone Number" required class="border p-2 mb-2 w-full rounded">
                <button type="submit" name="add" class="bg-blue-600 text-white px-4 py-2 rounded">Add Provider</button>
            </form>
        </div>
    </div>

    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-4 text-center">
            <p>
                © 2023 PulsaConvert. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>