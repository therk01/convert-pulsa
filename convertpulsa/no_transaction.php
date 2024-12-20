<?php
session_start();
include 'db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Jika belum login, redirect ke halaman login
    exit();
}

// Ambil data pengguna dari database
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>No Transactions Found</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-roboto">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-2xl font-bold text-blue-600" href="#">
                PulsaConvert
            </a>
            <ul class="hidden md:flex space-x-6">
                <li>
                    <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
                </li>
                <li>
                    <a href="dashboard.php">Home</a>
                </li>
                <li>
                    <a href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- No Transaction Found Section -->
    <section class="py-20">
        <div class="container mx-auto px-4 text-center">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-8" role="alert">
                <strong class="font-bold">No Transaction Found!</strong>
                <span class="block sm:inline">It seems you have not made any transactions yet. Please check back later or start a new transaction.</span>
            </div>
            <div class="mt-8">
                <a href="form.php" class="bg-blue-600 text-white px-6 py-3 rounded-full font-bold">
                    Start a New Transaction
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
        <div class="container mx-auto px-4 text-center">
            <p>
                © 2023 PulsaConvert. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>