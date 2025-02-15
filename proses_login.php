<?php
session_start(); // Mulai session
include 'includes/db.php'; // Include koneksi database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cek apakah username ada di database
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // Simpan data user ke session
            $_SESSION['user'] = $user;

            // Redirect berdasarkan role
            if ($user['role'] === 'admin') {
                header('Location: admin/index.php'); // Redirect ke admin panel
            } else {
                header('Location: index.php'); // Redirect ke halaman utama
            }
            exit();
        } else {
            // Password salah
            $_SESSION['error'] = 'Username atau password salah!';
            header('Location: login.php'); // Kembali ke halaman login
            exit();
        }
    } else {
        // Username tidak ditemukan
        $_SESSION['error'] = 'Username atau password salah!';
        header('Location: login.php'); // Kembali ke halaman login
        exit();
    }
} else {
    // Jika bukan metode POST, redirect ke halaman login
    header('Location: login.php');
    exit();
}