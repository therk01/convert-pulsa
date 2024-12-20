<?php
session_start();
include '../db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Handle Login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Ambil data admin berdasarkan email
    $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?"); // Pastikan Anda memiliki tabel admins
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    // Verifikasi password
    if ($admin && password_verify($password, $admin['password'])) {
        // Set session dan redirect ke halaman admin dashboard
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['role'] = 'admin'; // Set role admin
        header('Location: index.php'); // Ganti dengan halaman dashboard admin
        exit();
    } else {
        $error = "Email atau password salah.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center">Admin Login</h2>
        <?php if (isset($error)): ?>
            <div class="bg-red-200 text-red-600 p-2 rounded mb-4 text-center">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <form action="#" method="POST">
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter your email" required>
            </div>
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter your password" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
                <a href="index.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Back to Home</a>
            </div>
        </form>
    </div>
</body>
</html>