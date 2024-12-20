<?php
session_start();
session_start();
include '../db.php'; // Pastikan Anda memiliki file db.php untuk koneksi database

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php'); // Jika belum login, redirect ke halaman login admin
    exit();
}
session_destroy(); // Hancurkan session
header('Location: admin_login.php'); // Redirect ke halaman login admin
exit();
?>