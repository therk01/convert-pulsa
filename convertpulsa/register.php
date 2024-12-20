<?php
session_start();
include 'db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Handle Registration
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password

    // Simpan data pengguna ke database
    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$name, $email, $phone, $password])) {
        $_SESSION['user_id'] = $pdo->lastInsertId(); // Set session
        header('Location: login.php'); 
        echo "Register Berhasil";// Redirect ke halaman dashboard
        exit();
    } else {
        $error = "Terjadi kesalahan saat mendaftar.";
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    <title>Registration Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"></link>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.19/dist/full.min.css" rel="stylesheet" type="text/css" />

</head>
<body class="bg-gray-100 font-roboto">
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-6 text-center">Create an Account</h2>
            <form action="#" method="POST">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Full Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email Address</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="confirm-password" class="block text-gray-700">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-6">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">Register</button>
                </div>
                <div class="text-center">
                    <p class="text-gray-700">Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>