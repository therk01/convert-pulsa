<?php
session_start();
include 'db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Jika belum login, redirect ke halaman login
    exit();
}

// Mendapatkan data dari form
// Mendapatkan data dari form
$userId = $_SESSION['user_id'];
$provider = $_POST['provider'];
$amount = floatval($_POST['amount']); // Konversi ke float
$senderPhone = $_POST['sender_phone'];
$receiverPhone = $_POST['receiver_phone'];
$account = $_POST['account'];
$ewallet = $_POST['ewallet'];
$rate = floatval($_POST['rate']); // Konversi ke float
$result = $amount * $rate; // Sekarang ini aman

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
if ($_FILES["payment-proof"]["size"] > 5000000) { // 500KB
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
        $status = 'Success'; // Status transaksi
        $stmt->execute([$userId, $provider, $amount, $senderPhone, $receiverPhone, $account, $ewallet, $rate, $result, $paymentProof, $status]);

        // Redirect ke halaman status
        header('Location: status.php');
        exit();
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>

<script>
   function updateRate() {
    const providerSelect = document.getElementById('provider');
    const selectedOption = providerSelect.options[providerSelect.selectedIndex];
    const rate = selectedOption.getAttribute('data-rate');
    document.getElementById('rate-display').innerText = `Rate: ${rate}`;
}
</script>