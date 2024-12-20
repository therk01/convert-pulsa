<?php
session_start();
include '../db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Jika belum login, redirect ke halaman login admin
    exit();
}
// Handle Create
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $email, $phone, $password]);
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?");
    $stmt->execute([$name, $email, $phone, $id]);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

// Fetch Users
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 font-roboto">
    <div class="min-h-screen flex">
    <?php include '../admin/includes/sidebar.php';?>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <h1 class="text-3xl font-bold mb-6">Manage Users</h1>

            <!-- Create User Form -->
            <form method="POST" class="mb-6 bg-white p-4 rounded shadow-md">
                <h2 class="text-xl font-bold mb-4">Add New User</h2>
                <input type="text" name="name" placeholder="Name" required class="border p-2 mb-2 w-full rounded">
                <input type="email" name="email" placeholder="Email" required class="border p-2 mb-2 w-full rounded">
                <input type="text" name="phone" placeholder="Phone" required class="border p-2 mb-2 w-full rounded">
                <input type="password" name="password" placeholder="Password" required class="border p-2 mb-2 w-full rounded">
                <button type="submit" name="create" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Add User</button>
            </form>

            <!-- Users Table -->
            <div class="overflow-x-auto bg-white rounded-lg shadow-md">
                <table class="min-w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Phone</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border px-4 py-2"><?= $user['id'] ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($user['name']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                            <td class="border px-4 py-2"><?= htmlspecialchars($user['phone']) ?></td>
                            <td class="border px-4 py-2">
                                <button class="text-blue-600 edit-btn" data-id="<?= $user['id'] ?>" data-name="<?= htmlspecialchars($user['name']) ?>" data-email="<?= htmlspecialchars($user['email']) ?>" data-phone="<?= htmlspecialchars($user['phone']) ?>">Edit</button>
                                <a href="?delete=<?= $user['id'] ?>" class="text-red-600 ml-4" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit User Form Modal -->
            <div id="editModal" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                <div class="bg-white p-6 rounded shadow-md w-96">
                    <h2 class="text-xl font-bold mb-4">Edit User</h2>
                    <form id="editForm" method="POST">
                        <input type="hidden" name="id" id="editId">
                        <input type="text" name="name" id="editName" required class="border p-2 mb-2 w-full rounded" placeholder="Name">
                        <input type="email" name="email" id="editEmail" required class="border p-2 mb-2 w-full rounded" placeholder="Email">
                        <input type="text" name="phone" id="editPhone" required class="border p-2 mb-2 w-full rounded" placeholder="Phone">
                        <button type="submit" name="update" class="bg-blue-600 text-white px-4 py-2 rounded">Update User</button>
                        <button type="button" id="closeModal" class="bg-gray-400 text-white px-4 py-2 rounded mt-2">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Show edit modal
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const email = $(this).data('email');
                const phone = $(this).data('phone');

                $('#editId').val(id);
                $('#editName').val(name);
                $('#editEmail').val(email);
                $('#editPhone').val(phone);
                $('#editModal').removeClass('hidden');
            });

            // Close modal
            $('#closeModal').on('click', function() {
                $('#editModal').addClass('hidden');
            });
        });
    </script>
</body>
</html>