<?php
session_start(); // Mulai session

// Cek apakah pengguna sudah login dan memiliki role admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php'); // Redirect ke halaman login jika bukan admin
    exit();
}

include '../includes/db.php'; // Include koneksi database
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo">
                <a href="index.php">Admin Panel</a>
            </div>
            <nav class="navbar">
                <ul class="nav-links">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="manage_cars.php">Kelola Mobil</a></li>
                    <li><a href="manage_orders.php">Kelola Pemesanan</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
                <div class="hamburger" id="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <main>
        <section class="dashboard">
            <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['user']['username']) ?>!</h1>
            <p>Anda login sebagai <strong>Admin</strong>.</p>

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-car"></i>
                    <h2>Total Mobil</h2>
                    <p><?= $pdo->query('SELECT COUNT(*) FROM cars')->fetchColumn(); ?></p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h2>Total Pengguna</h2>
                    <p><?= $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(); ?></p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-calendar-check"></i>
                    <h2>Total Pemesanan</h2>
                    <p><?= $pdo->query('SELECT COUNT(*) FROM bookings')->fetchColumn(); ?></p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-money-bill-wave"></i>
                    <h2>Pendapatan Bulan Ini</h2>
                    <p>Rp <?= number_format($pdo->query('SELECT SUM(total_price) FROM bookings WHERE MONTH(start_date) = MONTH(CURRENT_DATE())')->fetchColumn(), 0, ',', '.'); ?></p>
                </div>
            </div>
        </section>
    </main>

    <script src="../assets/js/script.js"></script>
</body>
</html>