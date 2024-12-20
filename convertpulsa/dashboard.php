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

// Ambil data rates dari database
$stmt = $pdo->query("SELECT * FROM rates");
$rates = $stmt->fetchAll(); // Ambil semua data rates

?>

<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Convert Pulsa to Money</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.12.19/dist/full.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="font-roboto bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-2xl font-bold text-blue-600" href="#">
                PulsaConvert
            </a>
            <ul class="hidden md:flex space-x-6">
                <li>
                    <h1>Hai, <?php echo htmlspecialchars($user['name']); ?></h1>
                </li>
                <li>
                    <a href="status.php">Transaksi</a>
                </li>
                <li>
                    <a href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-blue-600 text-white py-20">
   <div class="container mx-auto px-4 text-center">
    <h1 class="text-4xl font-bold mb-4">
    Jadikan Pulsa Berlebih Jadi Uang, Mudah dan Aman
    </h1>
    <p class="text-lg mb-8">
    Layanan cepat, aman, dan andal untuk mengubah pulsa Anda menjadi uang.    </p>
    <a class="bg-white text-blue-600 px-6 py-3 rounded-full font-bold" href="form.php">
     Mulai
    </a>
   </div>
  </section>

    <!-- Rates Section -->
    <section class="bg-gray-200 py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-12">Our Rates</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($rates as $rate): ?>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-bold mb-2">
                        <?= htmlspecialchars($rate['provider']) ?>
                    </h3>
                    <p>
                        Rate: <?= htmlspecialchars($rate['rate']) ?>
                    </p>
                </div>
                <?php endforeach; ?>
                </div>
        </div>
    </section>

    <!-- Pulsa Calculation Section -->
    <section class="py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-12">
            Hitung Konversi Pulsa Anda
            </h2>
            <form class="max-w-lg mx-auto">
                <div class="mb-4">
                    <label class="block text-left mb-2 font-bold" for="provider">
                        pilih Provider
                    </label>
                    <select class="w-full px-4 py-2 border rounded-lg" id="provider" onchange="updateRate()">
                        <option value="">Select a provider</option>
                        <?php foreach ($rates as $rate): ?>
                            <option value="<?= htmlspecialchars($rate['provider']) ?>" data-rate="<?= htmlspecialchars($rate['rate']) ?>">
                                <?= htmlspecialchars($rate['provider']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-left mb-2 font-bold" for="amount">
                        Enter Pulsa Amount
                    </label>
                    <input class="w-full px-4 py-2 border rounded-lg" id="amount" placeholder="Enter amount in IDR" type="number" required/>
                </div>
                <button class="bg-blue-600 text-white px-6 py-3 rounded-full font-bold" onclick="calculateConversion()" type="button">
                    Calculate
                </button>
                <div class="mt-4">
                    <p class="text-lg font-bold" id="result"></p>
                </div>
                <div class="mt-8">
                <a href="form.php" class="bg-green-600 text-white px-6 py-3 rounded-full font-bold">
                    Start Convert
                </a>
            </div>
            </form>
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

    <script>
        function updateRate() {
            const providerSelect = document.getElementById('provider');
            const selectedOption = providerSelect.options[providerSelect.selectedIndex];
            const rate = selectedOption.getAttribute('data-rate');
            // You can display the rate if needed
        }

        function calculateConversion() {
            const providerSelect = document.getElementById('provider');
            const amount = parseFloat(document.getElementById('amount').value);
            const selectedOption = providerSelect.options[providerSelect.selectedIndex];
            const rate = parseFloat(selectedOption.getAttribute('data-rate'));

            if (!isNaN(amount) && rate) {
                const result = amount * rate;
                document.getElementById('result').innerText = `You will receive: Rp ${result.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}`;
            } else {
                document.getElementById('result').innerText = 'Please select a provider and enter a valid amount.';
            }
        }
    </script>
</body>
</html>