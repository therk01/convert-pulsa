<?php
session_start();
include 'db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Jika belum login, redirect ke halaman login
    exit();
}

// Ambil data rates dari database
$stmt = $pdo->query("SELECT * FROM rates");
$rates = $stmt->fetchAll(); // Ambil semua data rates

// Proses form jika ada pengiriman data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mendapatkan data dari form
    $userId = $_SESSION['user_id'];
    $provider = $_POST['provider'];
    $amount = floatval($_POST['amount']);
    $senderPhone = $_POST['sender_phone'];
    $receiverPhone = $_POST['receiver_phone'];
    $account = $_POST['account'];
    $ewallet = $_POST['ewallet'];
    $rate = floatval($_POST['rate']); // Ambil rate dari data yang dipilih
    $result = $amount * $rate; // Hitung hasil konversi

    // Proses upload foto
    $targetDir = "uploads/"; // Folder untuk menyimpan foto
    $paymentProof = $targetDir . basename($_FILES["payment-proof"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($paymentProof, PATHINFO_EXTENSION));

    // Cek apakah file gambar adalah gambar sebenarnya atau palsu
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["payment-proof"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Cek ukuran file
    if ($_FILES["payment-proof"]["size"] > 50000000) { // 5MB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Izinkan format file tertentu
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Cek jika $uploadOk diatur ke 0 oleh kesalahan
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Jika semuanya baik-baik saja, coba untuk mengupload file
        if (move_uploaded_file($_FILES["payment-proof"]["tmp_name"], $paymentProof)) {
            // Simpan data transaksi ke database
            $stmt = $pdo->prepare("INSERT INTO transactions (user_id, provider, amount, sender_phone, receiver_phone, account, ewallet, rate, result, payment_proof, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $status = 'Pending'; // Status transaksi
            $stmt->execute([$userId, $provider, $amount, $senderPhone, $receiverPhone, $account, $ewallet, $rate, $result, $paymentProof, $status]);

            // Ambil ID transaksi yang baru saja dibuat
            $transactionId = $pdo->lastInsertId();

            // Redirect ke halaman detail transaksi
            header("Location: transaction_detail.php?id=$transactionId");
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-roboto">
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a class="text-2xl font-bold text-blue-600" href="dashboard.php">PulsaConvert</a>
            <ul class="flex space-x-6">
            <li><a href="dashboard.php" class="text-gray-700 hover:text-blue-600">Home</a></li>
                <li><a href="status.php" class="text-gray-700 hover:text-blue-600">Status</a></li>
                <li><a href="logout.php" class="text-gray-700 hover:text-blue-600">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-center">Form Transaksi Pulsa</h1>
        <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-4">
            <div class="mb-4">
                <label for="provider" class="block text-gray-700">Pilih Penyedia</label>
                <select id="provider" name="provider" class="w-full px-4 py-2 border rounded-lg" required onchange="updateRateAndPhone()">
                    <option value="">Pilih Penyedia</option>
                    <?php foreach ($rates as $rate): ?>
                        <option value="<?= htmlspecialchars($rate['provider']) ?>" data-rate="<?= htmlspecialchars($rate['rate']) ?>" data-phone="<?= htmlspecialchars($rate['phone']) ?>">
                            <?= htmlspecialchars($rate['provider']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <p id="rate-display" class="text-gray-700">Rate: -</p>
                <p id="result-display" class="text-gray-700">Hasil Konversi: Rp 0</p>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-gray-700">Jumlah Pulsa (IDR)</label>
                <input type="number" id="amount" name="amount" class="w-full px-4 py-2 border rounded-lg" required oninput="calculateConversion()">
            </div>
            <div class="mb-4">
                <label for="sender_phone" class="block text-gray-700">Nomor Telepon Pengirim</label>
                <input type="text" id="sender_phone" name="sender_phone" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="receiver_phone" class="block text-gray-700">Masukan Nomor Penerima</label>
                <input type="text" id="receiver_phone" name="receiver_phone" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="account" class="block text-gray-700">Akun (jika ada)</label>
                <input type="text" id="account" name="account" class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="ewallet" class="block text-gray-700">E-Wallet (jika ada)</label>
                <input type="text" id="ewallet" name="ewallet" class="w-full px-4 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label for="payment-proof" class="block text-gray-700">Bukti Pembayaran</label>
                <input type="file" id="payment-proof" name="payment-proof" class="w-full px-4 py-2 border rounded-lg" accept="image/*" required>
            </div>
            <button type="submit" name="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Kirim Transaksi</button>
        </form>
    </div>

    <script>
        // Fungsi untuk memperbarui tampilan rate dan menghitung hasil konversi
        function updateRateAndPhone() {
            const providerSelect = document.getElementById('provider');
            const selectedOption = providerSelect.options[providerSelect.selectedIndex];
            const rate = selectedOption.getAttribute('data-rate');
            const phone = selectedOption.getAttribute('data-phone');

            document.getElementById('rate-display').innerText = `Rate: ${rate}`;
            document.getElementById('sender_phone').value = phone; // Mengisi nomor telepon pengirim jika ada
            calculateConversion(); // Hitung konversi saat provider dipilih
        }

        function calculateConversion() {
            const providerSelect = document.getElementById('provider');
            const amount = parseFloat(document.getElementById('amount').value);
            const selectedOption = providerSelect.options[providerSelect.selectedIndex];
            const rate = parseFloat(selectedOption.getAttribute('data-rate'));

            if (!isNaN(amount) && rate) {
                const result = amount * rate;
                document.getElementById('result-display').innerText = `Hasil Konversi: Rp ${result.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })}`;
            } else {
                document.getElementById('result-display').innerText = 'Hasil Konversi: Rp 0';
            }
        }
    </script>
</body>
</html>