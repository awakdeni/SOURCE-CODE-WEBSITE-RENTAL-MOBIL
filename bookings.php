<?php
include 'includes/header.php'; // Sudah ada session_start() di sini
include 'includes/db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['id'];
$bookings = $pdo->prepare('SELECT bookings.*, cars.name FROM bookings JOIN cars ON bookings.car_id = cars.id WHERE user_id = ?');
$bookings->execute([$user_id]);
?>

<section>
    <h1>Pemesanan Saya</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Mobil</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Total Harga</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
            <tr>
                <td><?= $booking['id'] ?></td>
                <td><?= $booking['name'] ?></td>
                <td><?= $booking['start_date'] ?></td>
                <td><?= $booking['end_date'] ?></td>
                <td>Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></td>
                <td><?= $booking['status'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

<?php
include 'includes/footer.php';
?>