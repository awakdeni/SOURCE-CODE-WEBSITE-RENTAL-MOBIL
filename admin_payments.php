<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $bank = $_POST['bank'];
    
    // Hitung total harga
    $stmt = $pdo->prepare('SELECT price_per_day FROM cars WHERE id = ?');
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();
    $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
    $total_price = $car['price_per_day'] * $days;
    
    // Simpan pemesanan
    $stmt = $pdo->prepare('INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price, status) VALUES (?, ?, ?, ?, ?, "pending")');
    $stmt->execute([$_SESSION['user']['id'], $car_id, $start_date, $end_date, $total_price]);
    
    $booking_id = $pdo->lastInsertId();
    
    // Simpan data pembayaran
    if (!empty($_FILES['proof']['name'])) {
        $target_dir = "uploads/";
        $proof_image = $target_dir . basename($_FILES["proof"]["name"]);
        move_uploaded_file($_FILES["proof"]["tmp_name"], $proof_image);
        
        $stmt = $pdo->prepare("INSERT INTO payments (booking_id, user_id, bank_name, proof_image, status) VALUES (?, ?, ?, ?, 'Pending')");
        $stmt->execute([$booking_id, $_SESSION['user']['id'], $bank, $proof_image]);
    }

    header('Location: bookings.php');
    exit();
}

// Panel Admin: Konfirmasi Pembayaran
if (isset($_GET['confirm_payment']) && isset($_GET['payment_id'])) {
    $payment_id = $_GET['payment_id'];
    $stmt = $pdo->prepare("UPDATE payments SET status = 'Confirmed' WHERE id = ?");
    $stmt->execute([$payment_id]);
    header('Location: admin_payments.php');
    exit();
}

$car_id = $_GET['car_id'];
$stmt = $pdo->prepare('SELECT * FROM cars WHERE id = ?');
$stmt->execute([$car_id]);
$car = $stmt->fetch();

// Ambil daftar bank dari database
$bankStmt = $pdo->query('SELECT bank_name, account_number, account_holder FROM banks');
$banks = $bankStmt->fetchAll();

include 'includes/header.php';
?>
<section>
    <h2>Konfirmasi Pembayaran (Admin)</h2>
    <table border="1">
        <tr>
            <th>ID Pembayaran</th>
            <th>User</th>
            <th>Bank</th>
            <th>Bukti</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT payments.*, users.username FROM payments JOIN users ON payments.user_id = users.id");
        while ($payment = $stmt->fetch()): ?>
            <tr>
                <td><?= $payment['id'] ?></td>
                <td><?= $payment['username'] ?></td>
                <td><?= $payment['bank_name'] ?></td>
                <td><a href="<?= $payment['proof_image'] ?>" target="_blank">Lihat Bukti</a></td>
                <td><?= $payment['status'] ?></td>
                <td>
                    <?php if ($payment['status'] == 'Pending'): ?>
                        <a href="?confirm_payment=1&payment_id=<?= $payment['id'] ?>">Konfirmasi</a>
                    <?php else: ?>
                        Dikonfirmasi
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</section>

<script>
function calculatePrice() {
    let startDate = document.getElementById('start_date').value;
    let endDate = document.getElementById('end_date').value;
    let pricePerDay = <?= $car['price_per_day'] ?>;

    if (startDate && endDate) {
        let start = new Date(startDate);
        let end = new Date(endDate);
        let days = (end - start) / (1000 * 60 * 60 * 24);
        if (days > 0) {
            document.getElementById('total_price').value = 'Rp ' + (pricePerDay * days).toLocaleString();
        } else {
            document.getElementById('total_price').value = '';
        }
    }
}
</script>
<?php include 'includes/footer.php'; ?>
