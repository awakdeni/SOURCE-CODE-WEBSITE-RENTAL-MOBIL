<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../includes/db.php';

// Update status pemesanan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare('UPDATE bookings SET status = ? WHERE id = ?');
    $stmt->execute([$status, $booking_id]);
}

// Ambil daftar pemesanan
$bookings = $pdo->query('SELECT bookings.*, users.username, cars.name FROM bookings JOIN users ON bookings.user_id = users.id JOIN cars ON bookings.car_id = cars.id')->fetchAll();
$stmt = $pdo->query("SELECT payments.*, users.username FROM payments JOIN users ON payments.user_id = users.id");
while ($payment = $stmt->fetch());
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
        <h2>Kelola Pemesanan</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pengguna</th>
                    <th>Mobil</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Total Harga</th>
                    <th>Bukti</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?= $booking['id'] ?></td>
                    <td><?= $booking['username'] ?></td>
                    <td><?= $booking['name'] ?></td>
                    <td><?= $booking['start_date'] ?></td>
                    <td><?= $booking['end_date'] ?></td>
                    <td>Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></td>
                    <td><a href="<?= $payment['proof_image'] ?>" target="_blank">Lihat Bukti</a></td>
                    <td><?= $booking['status'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                            <select name="status">
                                <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="approved" <?= $booking['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                <option value="rejected" <?= $booking['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                            <button type="submit" name="update_status">Update</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>