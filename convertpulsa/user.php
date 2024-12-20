<?php
session_start();
include 'db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari database
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

// Handle Update Profile
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->execute([$name, $email, $phone, $userId]);

    // Refresh data after update
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 font-roboto">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-center">User  Profile</h1>

        <!-- User Information -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Profile Information</h2>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
            <button id="editProfileBtn" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">Edit Profile</button>
        </div>

        <!-- Update Profile Form -->
        <div id="editProfileForm" class="bg-white p-6 rounded-lg shadow-md hidden">
            <h2 class="text-xl font-bold mb-4">Update Profile</h2>
            <form method="POST">
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="border p-2 mb-2 w-full rounded" placeholder="Name">
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="border p-2 mb-2 w-full rounded" placeholder="Email">
                <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required class="border p-2 mb-2 w-full rounded" placeholder="Phone">
                <button type="submit" name="update" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-200">Update Profile</button>
                <button type="button" id="cancelEdit" class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition duration-200">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#editProfileBtn').on('click', function() {
                $('#editProfileForm').removeClass('hidden');
                $(this).addClass('hidden');
            });

            $('#cancelEdit').on('click', function() {
                $('#editProfileForm').addClass('hidden');
                $('#editProfileBtn').removeClass('hidden');
            });
        });
    </script>
</body>
</html>